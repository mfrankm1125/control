<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salidas extends CMS_Controller {
    private $_list_perm;
    public $forecast;
    public $val = 0;
    public $acpadres;
    private $table="";
    private $pk_idtable="";
    
    public function __construct()
    {
        parent::__construct();
        if (!$this->acceso()) {
            redirect('errors/error/1');
        }
        $this->load->model("global_model");
        $this->_list_perm = $this->lista_permisos();
        $this->template->set_data('lista_permisos', $this->_list_perm);

    }

    public function index(){
        $dataClientes=$this->global_model->getDataCliente();
        $dataTipoProducto=$this->global_model->getDataTipoProductos();
        $dataAnimales=$this->global_model->getDataAnimalSalida();
        //print_r($dataAnimales);exit();
        //$dataTipoSalidaAnimal=$this->global_model->getDataTipoSalidaAnimal();
        $valPrecioLeche=$this->global_model->getPrecioVentaLeche();
        $this->template->set_data("valprecioleche",$valPrecioLeche);
        
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');

        $this->template->add_js('url_theme', 'plugins/chosen/chosen.jquery.min');
        $this->template->add_css('url_theme', 'plugins/chosen/chosen.min');

        //$this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->set_data("tiposalida",$this->global_model->getDataTipoSalida());
        //$this->template->set_data("tiposalidaanimal",$dataTipoSalidaAnimal);
        $this->template->set_data("clientes",$dataClientes);
        $this->template->set_data("tipoproducto",$dataTipoProducto);
        $this->template->set_data("animales",$dataAnimales);
        $this->template->add_js('base', 'underscore');
        $this->template->renderizar('salidas/index');
    }


    public function getDataTable(){
        $result=json_encode($this->_getData(0));
        $result='{"data":'.$result.'}';
        //print_r($result);
        echo $result;
    }

    public function getData(){
        if(sizeof($this->input->post()) > 0){
            $id=$this->input->post('id',true);
            $result=json_encode($this->_getData($id));
            echo $result;
        }
    }

    private function _getData($id){
        $campoEditaID="salida.idsalida";
        $idd=(int)$id;
        $condicion=array(
            'salida.estado'=>1
        );
        if($id > 0) $condicion[$campoEditaID]=$idd;
        $this->db->select('salida.*,tiposalidaanimal.nombre as tiposalida , tipoproducto.nombre as tipoproducto');
        $this->db->from("salida");
        $this->db->join('tiposalidaanimal', 'salida.idtiposalida = tiposalidaanimal.idtiposalidaanimal', 'inner');
        $this->db->join('tipoproducto', 'salida.idtipoproducto = tipoproducto.idtipoproducto', 'inner');
        $this->db->where($condicion);
        $this->db->order_by('salida.fechasalida', 'desc');
        $this->db->order_by('salida.idsalida', 'desc');
        $datax = $this->db->get()->result_array();
        $data=null;
        foreach($datax as $v){
            $dtx=null;
            if((int)$v["idanimal"] > 0 ){
                $query="SELECT
                claseanimal.nombre,
                animales.codanimal,
                animales.idanimal
                FROM
                animales
                INNER JOIN detanimalxclaseanimal ON detanimalxclaseanimal.idanimal = animales.idanimal
                INNER JOIN claseanimal ON detanimalxclaseanimal.idclaseanimal = claseanimal.idclaseanimal
                where animales.estado <> 0 and detanimalxclaseanimal.estado=1 and detanimalxclaseanimal.isclaseactual=1 and  animales.idanimal=".$v["idanimal"];
                $dtx = $this->db->query($query)->result_array();
            }

            $this->db->select('*');
            $this->db->from("clientes");
            $this->db->where(["estado"=>1,"idcliente"=>$v["idcliente"]]);
            $this->db->order_by('fechacrea', 'DESC');
            $dataCliente = $this->db->get()->result_array();;


            $data[]=array('idsalida'=>$v["idsalida"],
              'idtiposalida'=>$v["idtiposalida"],
              'idtipoproducto'=>$v["idtipoproducto"],
              'idmotivosalidaanimal'=>$v["idmotivosalidaanimal"],
              'idcliente'=>$v["idcliente"],
              'idanimal'=>$v["idanimal"],
              'codanimal'=>$v["codanimal"],
              'fechasalida'=>$v["fechasalida"],
              'cantidad'=>$v["cantidad"],
              'precio'=>$v["precio"],
              'estado'=>$v["estado"],
              'tiposalida'=>$v["tiposalida"],
              'tipoproducto'=> $v["tipoproducto"],
               "dataanimal"=>$dtx,
                "dataCliente"=>$dataCliente
            );

        }



        return $data;
    }

    public function getStockActualLeche(){
        $data=$this->global_model->getDataStockActualProleche();
        $stock["stockleche"]=0;
        if(isset($data[0]["totalstock"])){
            $stock["stockleche"]=floatval($data[0]["totalstock"]);
        }
        echo json_encode($stock);
    }
    public function getAnimalEdit(){
        $post=$this->input->post(null,true);
        $return=null;
        if(sizeof($post)>0){
            $id=(int)$post["id"];
            $return=$this->global_model->getDataAnimalEdit($id);
        }else{
            
        }
        echo json_encode($return);
    }


    public function getDataClasesByAnimal($idanimal=null){
        $condicion=["detanimalxclaseanimal.idanimal"=>$idanimal
            ,"detanimalxclaseanimal.estado"=>1];

        $this->db->select('detanimalxclaseanimal.*,claseanimal.nombre as claseanimal ');
        $this->db->from("detanimalxclaseanimal");
        $this->db->join('claseanimal', 'detanimalxclaseanimal.idclaseanimal = claseanimal.idclaseanimal', 'inner');
        $this->db->where($condicion);
        $this->db->order_by('claseanimal.orden', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }

    private function _queryGetData($isEdit=null,$idEdit=null){
        $where="";
        if($isEdit != null && $idEdit != null){
            $where=" and animales.idanimal=".(int)$idEdit;
        }

        $query="SELECT
                animales.*,
                tipoanimales.nombre as tipoanimal
                FROM
                animales
                INNER JOIN tipoanimales ON animales.idtipoanimal = tipoanimales.idtipoanimal
                where tipoanimales.estado=1 and animales.estado=1  ".$where." 
                order by animales.codanimal desc";
        $result = $this->db->query($query)->result_array();
    }


    public function elimina_acentos($text)
    {
        $text = htmlentities($text, ENT_QUOTES, 'UTF-8');
        $text = strtolower($text);
        $patron = array (
            // Espacios, puntos y comas por guion
            //'/[\., ]+/' => ' ',

            // Vocales
            '/\+/' => '',
            '/&agrave;/' => 'a',
            '/&egrave;/' => 'e',
            '/&igrave;/' => 'i',
            '/&ograve;/' => 'o',
            '/&ugrave;/' => 'u',

            '/&aacute;/' => 'a',
            '/&eacute;/' => 'e',
            '/&iacute;/' => 'i',
            '/&oacute;/' => 'o',
            '/&uacute;/' => 'u',

            '/&acirc;/' => 'a',
            '/&ecirc;/' => 'e',
            '/&icirc;/' => 'i',
            '/&ocirc;/' => 'o',
            '/&ucirc;/' => 'u',

            '/&atilde;/' => 'a',
            '/&etilde;/' => 'e',
            '/&itilde;/' => 'i',
            '/&otilde;/' => 'o',
            '/&utilde;/' => 'u',

            '/&auml;/' => 'a',
            '/&euml;/' => 'e',
            '/&iuml;/' => 'i',
            '/&ouml;/' => 'o',
            '/&uuml;/' => 'u',

            '/&auml;/' => 'a',
            '/&euml;/' => 'e',
            '/&iuml;/' => 'i',
            '/&ouml;/' => 'o',
            '/&uuml;/' => 'u',

            // Otras letras y caracteres especiales
            '/&aring;/' => 'a',
            '/&ntilde;/' => 'n',

            // Agregar aqui mas caracteres si es necesario

        );

        $text = preg_replace(array_keys($patron),array_values($patron),$text);
        return $text;
    }

    public function setForm(){
        $post=$this->input->post(null,true);
        $return=null;
        if(sizeof($post) > 0){
            $isEdit=$post["isEdit"];
            $idEdit=$post["idEdit"];
            $txtTipoSalida=$this->elimina_acentos($post["txtTipoSalida"]);
            $txtTipoProducto=$this->elimina_acentos($post["txtTipoProducto"]);
            $cantidad=(!empty($post["cantsalida"]))? $post["cantsalida"]: 1 ;
            $idanimal=(!empty($post["selAnimal"]))? $post["selAnimal"]: 0;
            $idmotivosalida=(!empty($post["selMotivoSalida"]))? $post["selMotivoSalida"]: 0;
            $idCliente=(!empty($post["selCliente"]))? $post["selCliente"]: 0;
            $idtiposalida=$post["selTipoSalida"];
            $precio=floatval($post["preciosalida"]);
            $descMotivoMuerte=$post["descMotivoMuerte"];

            $descMotivoDonacion=$post["descMotivoDonacion"];
            //echo "<pre>";print_r($post);exit();





            $dataG=array(
              "idtiposalida"=>$idtiposalida,
              "idtipoproducto"=>$post["selTipoProducto"],
              "idcliente"=>$idCliente,
              "idanimal"=>$idanimal,
              "fechasalida"=>$post["fechaventa"],
              "cantidad"=>$cantidad,
              "precio"=>$precio,
              "idmotivosalidaanimal"=>$idmotivosalida,
              "descsalida"=> $descMotivoMuerte,
               "descdonacion"=>$descMotivoDonacion
            );

           // echo"<pre>";print_r($dataG);exit();
            /*$cc=1;
            if($txtTipoSalida == "donacion"){
                $cc=0;
            }
            echo"<pre>";print_r($post);
            echo "<br>$cc ";
            echo"<pre>";print_r($dataG);
            exit();*/
            if($isEdit == 0 && $idEdit ==0 ){

                $dataG["estado"]=1;
                //print_r($dataG);exit();
                $this->db->insert("salida", $dataG);

                if($txtTipoProducto == "leche" &&  $txtTipoSalida == "venta"){
                    $statusStock=$this->setStockLeche($cantidad);
                }
                if($txtTipoProducto == "leche" &&  $txtTipoSalida == "donacion"){
                    $statusStock=$this->setStockLeche($cantidad);
                }

                if($txtTipoProducto == "animal"  && $idanimal != 0 &&  $txtTipoSalida == "venta" ){
                    $dU=["idtiposalidaanimal"=>$idtiposalida,"idcliente"=>$idCliente,"precioventa"=>$precio,"estado"=>2];
                    $this->db->where(["idanimal"=>$idanimal])->update("animales",$dU);
                }elseif ($txtTipoProducto == "animal"  && $idanimal != 0 &&  $txtTipoSalida == "muerte"){
                    $dU=["idtiposalidaanimal"=>$idtiposalida,"idcliente"=>$idCliente,"precioventa"=>$precio,"estado"=>3];
                    $this->db->where(["idanimal"=>$idanimal])->update("animales",$dU);
                }elseif ($txtTipoProducto == "animal"  && $idanimal != 0 &&  $txtTipoSalida == "donacion"){
                    $dU=["idtiposalidaanimal"=>$idtiposalida,"idcliente"=>$idCliente,"precioventa"=>$precio,"estado"=>4];
                    $this->db->where(["idanimal"=>$idanimal])->update("animales",$dU);
                }
                $return["status"]= 'ok';
            }elseif($isEdit == 1 && $idEdit !=0){

            }else{
                $return["status"]= 'Error Request';
            }

        }else{
            $return["status"]= 'Error post';
        }
        echo json_encode($return);
    }

    private function setStockLeche($cantidad){
        $return["status"]="fail";
        if($cantidad !=null && $cantidad > 0){
            $this->db->set('montosalida', '(montosalida + '.$cantidad.' )' , FALSE);
            $this->db->set('totalstock', '(totalstock - '.$cantidad.' )' , FALSE);
            $this->db->where('idcod',"stock");
            $this->db->update('prodlechestock');
            $return["status"]="ok";
        }
        return $return;
    }

    public function deleteData(){
        $return["status"]="Fail";
        $post=$this->input->post(null,true);
        //print_r($post);exit();
        if( sizeof($post) > 0){
            $d=array();
            $id=(int)$post['idsalida'];
            $condicion=array("idsalida" =>$id);
            $dataD=array(
                'estado'=>0
            );

            if(strtolower($post["tiposalida"]) =="venta" && strtolower($post["tipoproducto"]) =="animal"){
                $idanimal=$post["idanimal"];
                $this->db->where(["idanimal"=>$idanimal])->update("animales",["idtiposalidaanimal"=>0,"idcliente"=>0,"estado"=>1]);
                $d['status']="ok";

            }elseif(strtolower($post["tiposalida"]) =="muerte" && strtolower($post["tipoproducto"]) =="animal"){
                $idanimal=$post["idanimal"];
                $this->db->where(["idanimal"=>$idanimal])->update("animales",["idtiposalidaanimal"=>0,"idcliente"=>0,"estado"=>1]);
                $d['status']="ok";

            }elseif(strtolower($post["tiposalida"]) =="descarte" && strtolower($post["tipoproducto"]) =="animal"){
                $idanimal=$post["idanimal"];
                $this->db->where(["idanimal"=>$idanimal])->update("animales",["idtiposalidaanimal"=>0,"idcliente"=>0,"estado"=>1]);
                $d['status']="ok";

            }elseif (strtolower($post["tiposalida"]) =="venta" && strtolower($post["tipoproducto"]) =="leche"){
                $cantidad=floatval($post["cantidad"]);
                $this->db->set('totalstock', '(totalstock + '.$cantidad.' )' , FALSE);
                $this->db->where('idcod',"stock");
                $this->db->update('prodlechestock');
            }elseif (strtolower($post["tiposalida"]) =="descarte" && strtolower($post["tipoproducto"]) =="leche"){
                $cantidad=floatval($post["cantidad"]);
                $this->db->set('totalstock', '(totalstock + '.$cantidad.' )' , FALSE);
                $this->db->where('idcod',"stock");
                $this->db->update('prodlechestock');
            }

            $this->db->where($condicion)->update("salida", $dataD);
            $d['status']="ok";


        }else{
            $d['status']="error post";
        }
        echo json_encode($d);
    }


    public  function  getClasesByTipoAnimalSexo(){
        $return=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $idtipoanimal=$post["idtipoanimal"];
            $idsexo=$post["idsexo"];
            $return=$this->global_model->getDataClaseAnimalByTipoAnimalSexo($idtipoanimal,$idsexo);
        }else{
            $return["status"]="faill request";
        }

        echo json_encode($return);
    }

    public function getClientes(){
        if($this->input->server('REQUEST_METHOD') != 'POST'){
            echo 0;
        };
        $this->db->select('*');
        $this->db->from("clientes");
        $this->db->where(["estado"=>1]);
        $this->db->order_by('fechacrea', 'DESC');
        $result = $this->db->get()->result_array();
        echo json_encode($result);
    }

    public function newCliente(){
        $post=$this->input->post(null,true);
        $return=null;
        if(sizeof($post) <=0){
            echo $return["status"]="Fail";
        }
        $nombre=$post['name'];
        $direccion=$post['direccion'];
        $telefono=$post['telefono'];
        $ruc=$post['ruc'];
        $nombre=str_replace( "\r\n", ' ', $nombre);

         $data=array(
            'nombre' =>$nombre,
            'ruc' => $ruc,
            'direccion'=>$direccion,
            'telefono'=>$telefono,
             'estado'=>1

        );

        $this->db->trans_start();
        $this->db->insert("clientes", $data);
        $insertId = $this->db->insert_id();
        $this->db->trans_complete();
        $return["status"]="ok";
        $return["insertId"]=$insertId;
        echo  json_encode($return);
    }


}
?>