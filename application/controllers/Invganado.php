<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invganado extends CMS_Controller {
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
        // $this->template->set_data('data',$data);
        $dataTipoAnimales=$this->global_model->getDataTipoAnimal();
        $dataTipoIngresoAnimal=$this->global_model->getDataTipoIngresoAnimal();
        $dataTipoSalidaAnimal=$this->global_model->getDataTipoSalidaAnimal();
        $dataClientes=$this->global_model->getDataCliente();
        $dataProveedores=$this->global_model->getDataProveedor();

        $dataSexo=$this->global_model->getDataSexoAnimal();


        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
        $this->template->set_data("tipoanimal",$dataTipoAnimales);
        $this->template->set_data("tipoingresoanimal",$dataTipoIngresoAnimal);
        $this->template->set_data("tiposalidaanimal",$dataTipoSalidaAnimal);
        $this->template->set_data("clientes",$dataClientes);
        $this->template->set_data("proveedores",$dataProveedores);
        $this->template->set_data("sexo",$dataSexo);

        //$this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->add_js('base', 'underscore');
        $this->template->renderizar('invganado/index');
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
        $campoEditaID="animales.idanimal";
        $idd=(int)$id;
        $condicion=array(
            'animales.estado'=>1
        );
        if($id > 0) $condicion[$campoEditaID]=$idd;

        $this->db->select('animales.* , tipoanimales.nombre as tipoanimal');
        $this->db->from("animales");
        $this->db->join('tipoanimales', 'animales.idtipoanimal = tipoanimales.idtipoanimal', 'inner');
        $this->db->where($condicion);
        $this->db->order_by('codanimal', 'DESC');
        $dataanimal = $this->db->get()->result_array();
        $data=null;
        foreach ($dataanimal as $key=>$value ){
            $dataCliente=$this->getDataClienteByAnimal($value["idanimal"]);
            $dataProveedor=$this->getDataProveedorByAnimal($value["idanimal"]);
            $dataClases=$this->getDataClasesByAnimal($value["idanimal"]);

            $dataCol = array_column($dataClases, 'isclaseactual');

            $keyCol = array_search('1', $dataCol);
             
            $claseActual=$dataClases[$keyCol]["claseanimal"];

            $data[]=array(
                "idanimal"=>$value["idanimal"],
                "idtipoanimal"=>$value["idtipoanimal"],
                "idanimalpadre" =>$value["idanimalpadre"],
                "idanimalmadre"=>$value["idanimalmadre"],
                "codanimal"=>$value["codanimal"] ,
                "codraza"=>$value["codraza"],
                "apodo"=>"",
                "descripcion"=>"",
                "fechanacimiento"=>$value["fechanacimiento"] ,
                "sexo"=>$value["sexo"],
                "isHijo"=>null,
                "isPadre"=>null,
                "isMadre"=>null,
                "idtipoingresoanimal"=>$value["idtipoingresoanimal"],
                "idtiposalidaanimal"=>$value["idtiposalidaanimal"],
                "idcliente"=>$value["idcliente"],
                "idproveedor"=>$value["idproveedor"],
                "compradoa"=>$value["compradoa"],
                "vendidoa"=>$value["vendidoa"],
                "pureza"=>$value["pureza"] ,
                "proposito"=>$value["proposito"] ,
                "preciocompra"=>$value["preciocompra"] ,
                "precioventa"=>$value["precioventa"]  ,
                "pesonace"=>$value["pesonace"]  ,
                "usercrea"=>$value["usercrea"],
                "fechacrea"=>$value["fechacrea"],
                "estado"=>$value["estado"],
                "tipoanimal"=>$value["tipoanimal"],
                "fechaingreso"=>$value["fechaingreso"],
                "datacliente"=>$dataCliente,
                "dataproveedor"=>$dataProveedor,
                "dataclases"=>$dataClases,
                "claseactual"=>$claseActual
            );

        }


        return $data;
    }

    public function getDataClienteByAnimal($idanimal=null){
        $condicion=["animales.idanimal"=>$idanimal,"clientes.estado"=>1];
        $this->db->select('clientes.nombre as cliente , clientes.idcliente');
        $this->db->from("animales");
        $this->db->join('clientes', 'animales.idcliente = clientes.idcliente', 'inner');
        $this->db->where($condicion);
        //$this->db->order_by('codanimal', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getDataProveedorByAnimal($idanimal=null){
        $condicion=["animales.idanimal"=>$idanimal,"proveedores.estado"=>1];
        $this->db->select('proveedores.razonsocial , proveedores.idproveedor');
        $this->db->from("animales");
        $this->db->join('proveedores', 'animales.idproveedor = proveedores.idproveedor', 'inner');
        $this->db->where($condicion);
        //$this->db->order_by('codanimal', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
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
    


    public function setForm(){
        $post=$this->input->post(null,true);
        $return=null;
        if(sizeof($post) > 0){

           //echo "<pre>";print_r($post);exit();
            $isEdit=$post["isEdit"];
            $idEdit=$post["idEdit"];

            $idtipoanimal=$post["selTipoAnimal"];
            $codanimal=$post["codanimal"];
            $codpadre=trim($post["codpadre"]);
            $codmadre=trim($post["codmadre"]);

            $fechanace=$post["fechanace"];
            $selSexo=$post["selSexo"];
            $raza=$post["raza"];
            $pureza=$post["pureza"];
            $proposito=$post["proposito"];
            $seltipoentrada=$post["seltipoentrada"];
            $selproveedor=$post["selproveedor"];
            $preciocompra=floatval($post["preciocompra"]);
            $seltiposalida=$post["seltiposalida"];
            $selcliente=$post["selcliente"];
            $precioventa=floatval($post["precioventa"]);
            $pesonace=floatval($post["pesonace"]);
            $fechaingreso=$post["fechaingreso"];

            $hidclaseanimal=$post["hidclaseanimal"];
            $hfechaIni=$post["fechaIni"];
            $claseactivo=$post["claseactivo"];
            $userID=$this->getUserId();
            $fechaActual=$this->getfecha_actual();
            $dataGlo=null;
            $isMuertoAlNacer=0;
            $descMuertoAlNacer="";
            if(isset($post['ismuertoalnacer']) && $post['ismuertoalnacer']!=""){
                $isMuertoAlNacer=1;
                $descMuertoAlNacer=$post["descmuertoalnacer"];
            }
            $dataGlo=array(
              "idtipoanimal"=>$idtipoanimal,
              "idanimalpadre"=>trim($codpadre),
              "idanimalmadre"=>trim($codmadre),
              "codanimal"=>$codanimal ,
              "codraza"=>$raza,
              "apodo"=>"",
              "descripcion"=>"",
              "fechanacimiento"=>$fechanace ,
              "sexo"=>$selSexo,
              "isHijo"=>null,
              "isPadre"=>null,
              "isMadre"=>null,
              "idtipoingresoanimal"=>$seltipoentrada,
              "idtiposalidaanimal"=>$seltiposalida,
              "idcliente"=>$selcliente,
              "idproveedor"=>$selproveedor,
              "compradoa"=>"",
              "vendidoa"=>"",
              "pureza"=>$pureza,
              "proposito"=>$proposito ,
              "preciocompra"=>$preciocompra,
              "precioventa"=>$precioventa,
              "pesonace"=>$pesonace,
              "fechaingreso"=>$fechaingreso,
              "ismuertoalnacer"=>$isMuertoAlNacer,
              "descmuertoalnacer"=>$descMuertoAlNacer
            );

            if($isEdit == 0 && $idEdit == 0){
                $dataGlo["usercrea"]=$userID;
                $dataGlo["fechacrea"]=$fechaActual;
                if($isMuertoAlNacer == 1){
                    $dataGlo["estado"]=100;
                    $dataGlo["fechamuerteanimal"]=$fechanace;
                }else{
                    $dataGlo["estado"]=1;
                }


                $this->db->trans_start();
                $this->db->insert("animales", $dataGlo);
                $idMaxAnimal = $this->db->insert_id();
                $this->db->trans_complete();
                $dataDet=[];
                
                for($i=0;$i<sizeof($hidclaseanimal);$i++){
                    $dataDet[]=array(
                        "idanimal"=>$idMaxAnimal,
                        "idclaseanimal"=>$hidclaseanimal[$i],
                        "fechaestadoini"=>$hfechaIni[$i],
                        "estado"=>1
                    );

                }
                if(sizeof($dataDet)>0){
                    $this->db->insert_batch("detanimalxclaseanimal", $dataDet);
                    $this->db->where(["idanimal"=>$idMaxAnimal,"idclaseanimal"=>$claseactivo])
                        ->update("detanimalxclaseanimal",["isclaseactual"=>1]);
                    $return["status"]="ok";
                }


            }elseif ($isEdit != 0 && $idEdit != 0){

                $this->db->where(["idanimal"=>$idEdit])->update("detanimalxclaseanimal",["estado"=>0]);
               // $claseEdit=$this->global_model->getDataClaseAnimalByTipoAnimalSexo($idtipoanimal,$selSexo);
                for($i=0;$i<sizeof($hidclaseanimal);$i++){
                    $dataDet[]=array(
                        "idanimal"=>$idEdit,
                        "idclaseanimal"=>$hidclaseanimal[$i],
                        "fechaestadoini"=>$hfechaIni[$i],
                        "estado"=>1
                    );

                }
                if(sizeof($dataDet)>0){
                    $this->db->insert_batch("detanimalxclaseanimal", $dataDet);
                    $this->db->where(["idanimal"=>$idEdit,"idclaseanimal"=>$claseactivo])
                        ->update("detanimalxclaseanimal",["isclaseactual"=>1]);
                    $return["status"]="ok";
                }


                $this->db->where(["idanimal"=>$idEdit])->update("animales", $dataGlo);

               /* $this->db->where(["idanimal"=>$idEdit])
                    ->update("detanimalxclaseanimal",["isclaseactual"=>0]);

                $this->db->where(["idanimal"=>$idEdit,"idclaseanimal"=>$claseactivo])
                    ->update("detanimalxclaseanimal",["isclaseactual"=>1]);*/
                $return["status"]="ok";
             // echo "<pre>";print_r($post);exit();
            }


         
           // echo "<pre>";print_r($post);exit();
            /*$idEdit=(int)$post['idEdit'];
            $isEdit=(int)$post['isEdit'];
            $nombre=trim($post['name']);
            $nombre=str_replace( "\r\n", ' ', $nombre);

            $userId=$this->getUserId();
            $fechaActual=$this->getfecha_actual();

            $data=array(
                'nombre' =>$nombre

            );

            if( $isEdit == 0 ){
                $data['usercrea']=$userId;
                $data['fechacrea']=$fechaActual;
                $data['estado']=1;

                $this->db->insert($this->table, $data);
                $d["status"]= 'ok';
            }
            if( $isEdit == 1 ){
                $data['userupdate']=$userId;
                $data['fechaupdate']=$fechaActual;

                $condicion=array($this->pk_idtable =>$idEdit);
                $this->db->where($condicion)->update($this->table, $data);
                $d["status"]= 'ok';
            }*/

        }else{
            $return["status"]= 'Error post';
        }
        echo json_encode($return);
    }

    public function deleteData(){
        $return["status"]="Fail";
        $post=$this->input->post(null,true);
        if( sizeof($post) > 0){
            $d=array();
            $id=(int)$post['id'];
            $condicion=array("idanimal" =>$id);
            $dataD=array(
                'userdelete'=>$this->getUserId(),
                'fechadelete' =>$this->getfecha_actual(),
                'estado'=>0
            );

            $this->db->where($condicion)->update("animales", $dataD);
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
            $idtipoanimal=(int)$post["idtipoanimal"];
            $idsexo=(int)$post["idsexo"];
            $return=$this->global_model->getDataClaseAnimalByTipoAnimalSexo($idtipoanimal,$idsexo);
        }else{
            $return["status"]="faill request";
        }

        echo json_encode($return);
    }

    //-----------Historial

    public function historialganado(){
        $dataTipoAnimales=$this->global_model->getDataTipoAnimal();
        $dataTipoIngresoAnimal=$this->global_model->getDataTipoIngresoAnimal();
        $dataTipoSalidaAnimal=$this->global_model->getDataTipoSalidaAnimal();
        $dataClientes=$this->global_model->getDataCliente();
        $dataProveedores=$this->global_model->getDataProveedor();

        $dataSexo=$this->global_model->getDataSexoAnimal();


        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
        $this->template->set_data("tipoanimal",$dataTipoAnimales);
        $this->template->set_data("tipoingresoanimal",$dataTipoIngresoAnimal);
        $this->template->set_data("tiposalidaanimal",$dataTipoSalidaAnimal);
        $this->template->set_data("clientes",$dataClientes);
        $this->template->set_data("proveedores",$dataProveedores);
        $this->template->set_data("sexo",$dataSexo);

        $this->template->add_js('base', 'underscore');
        $this->template->renderizar('invganado/historialganado');
    }

    public function getDataTableHistorial(){
        $result=json_encode($this->_getDataHistorial(0));
        $result='{"data":'.$result.'}';
        //print_r($result);
        echo $result;
    }

    public function getDataHistorial(){
        if(sizeof($this->input->post()) > 0){
            $id=$this->input->post('id',true);
            $result=json_encode($this->_getDataHistorial($id));
            echo $result;
        }

    }


    private function _getDataHistorial($id=null){
        $campoEditaID="animales.idanimal";
        $idd=(int)$id;
        $condicion=array(
            'animales.estado >'=>1
        );
        if($id > 0) $condicion[$campoEditaID]=$idd;

        $this->db->select('animales.* , tipoanimales.nombre as tipoanimal');
        $this->db->from("animales");
        $this->db->join('tipoanimales', 'animales.idtipoanimal = tipoanimales.idtipoanimal', 'inner');
        $this->db->where($condicion);
        $this->db->order_by('codanimal', 'DESC');
        $dataanimal = $this->db->get()->result_array();
        $data=null;
        foreach ($dataanimal as $key=>$value ){
            $dataCliente=$this->getDataClienteByAnimal($value["idanimal"]);
            $dataProveedor=$this->getDataProveedorByAnimal($value["idanimal"]);
            $dataClases=$this->getDataClasesByAnimal($value["idanimal"]);

            $dataCol = array_column($dataClases, 'isclaseactual');

            $keyCol = array_search('1', $dataCol);

            $claseActual=$dataClases[$keyCol]["claseanimal"];

            $data[]=array(
                "idanimal"=>$value["idanimal"],
                "idtipoanimal"=>$value["idtipoanimal"],
                "idanimalpadre" =>$value["idanimalpadre"],
                "idanimalmadre"=>$value["idanimalmadre"],
                "codanimal"=>$value["codanimal"] ,
                "codraza"=>$value["codraza"],
                "apodo"=>"",
                "descripcion"=>"",
                "fechanacimiento"=>$value["fechanacimiento"] ,
                "sexo"=>$value["sexo"],
                "isHijo"=>null,
                "isPadre"=>null,
                "isMadre"=>null,
                "idtipoingresoanimal"=>$value["idtipoingresoanimal"],
                "idtiposalidaanimal"=>$value["idtiposalidaanimal"],
                "idcliente"=>$value["idcliente"],
                "idproveedor"=>$value["idproveedor"],
                "compradoa"=>$value["compradoa"],
                "vendidoa"=>$value["vendidoa"],
                "pureza"=>$value["pureza"] ,
                "proposito"=>$value["proposito"] ,
                "preciocompra"=>$value["preciocompra"] ,
                "precioventa"=>$value["precioventa"]  ,
                "pesonace"=>$value["pesonace"]  ,
                "usercrea"=>$value["usercrea"],
                "fechacrea"=>$value["fechacrea"],
                "estado"=>$value["estado"],
                "tipoanimal"=>$value["tipoanimal"],
                "fechaingreso"=>$value["fechaingreso"],
                "datacliente"=>$dataCliente,
                "dataproveedor"=>$dataProveedor,
                "dataclases"=>$dataClases,
                "claseactual"=>$claseActual
            );

        }


        return $data;
    }


    //-----

    public function verdescendencia(){
        $return=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $idanimal=$post["idanimal"];
            $query="SELECT
            claseanimal.nombre AS claseanimal,
            animales.codraza,
            animales.idanimalmadre,
            animales.idanimalpadre,
            animales.codanimal,
            animales.fechanacimiento,
            animales.ismuertoalnacer,
            animales.pureza,
            animales.proposito,
            animales.fechamuerteanimal,
            animales.descmuertoalnacer,
            animales.estado,
            animales.idtiposalidaanimal
            FROM
            animales
            INNER JOIN detanimalxclaseanimal ON detanimalxclaseanimal.idanimal = animales.idanimal
            INNER JOIN claseanimal ON detanimalxclaseanimal.idclaseanimal = claseanimal.idclaseanimal
            where animales.estado > 0 and (animales.idanimalmadre=".$idanimal." or  animales.idanimalpadre=".$idanimal.") 
            and detanimalxclaseanimal.isclaseactual=1 and detanimalxclaseanimal.estado=1";
            $return = $this->db->query($query)->result_array();
        }else{
            $return["status"]="Fail";
        }

        echo json_encode($return);

    }

    public function issetCodanimal(){
        if ($this->input->server('REQUEST_METHOD') != 'POST'){
            echo 0;
        }
        $post=$this->input->post(null,true);
        $codanimal=trim($post["codanimal"]);

        $this->db->select('codanimal');
        $this->db->from("animales");
        $this->db->where(["estado"=>1,"codanimal"=>$codanimal]);
        $dt = $this->db->get()->result_array();
        $cc=sizeof($dt);

        echo json_encode(["c"=>$cc]);
    }

    public function issetCodanimalDescen(){
        if ($this->input->server('REQUEST_METHOD') != 'POST'){
            echo 0;
        }
        $post=$this->input->post(null,true);
        $codanimalmadre=$post["codanimalmadre"];
        $codanimalhijo=trim($post["codanimalhijo"]);
        $tipoanimal=(int)$post["tipoanimalmadre"];
        $query="	SELECT
                animales.fechanacimiento,
                animales.codraza,
                claseanimal.nombre as clase ,
                tipoanimales.nombre as tipoanimal,
                animales.codanimal,
				animales.idanimal
                FROM
                animales
                INNER JOIN detanimalxclaseanimal ON detanimalxclaseanimal.idanimal = animales.idanimal
                INNER JOIN claseanimal ON detanimalxclaseanimal.idclaseanimal = claseanimal.idclaseanimal
                INNER JOIN tipoanimales ON animales.idtipoanimal = tipoanimales.idtipoanimal AND claseanimal.idtipoanimal = tipoanimales.idtipoanimal
                where detanimalxclaseanimal.estado=1 and detanimalxclaseanimal.isclaseactual=1 
                and animales.estado > 0  and animales.estado <>100
                and animales.codanimal='".$codanimalhijo."'                
                ;";

        $returnx = $this->db->query($query)->result_array();

        $return["existencia"]=sizeof($returnx);
        $return["datacria"]=$returnx;
        echo json_encode($return);
    }

    public function saveaddnewdescendencia(){
        if ($this->input->server('REQUEST_METHOD') != 'POST'){
            echo 0;
        }
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            //echo "<pre>";print_r($post);exit();
           $idCodAnimalMadre=$post["idCodAnimalMadre"];
            $issetInInventario=$post["issetInInventario"];
            $codanimaldescen= $post["codanimaldescen"];
            $codpadredescen= $post["codpadredescen"];
            $codmadredescen=$post["codmadredescen"];
            $tipoanimalmadre=$post["idTipoAnimalPadre"];


            if($issetInInventario == "1"){
                $this->db->where(["codanimal"=>$codanimaldescen])->update("animales", ["idanimalpadre"=>$codpadredescen,"idanimalmadre"=>$codmadredescen]);
                $return["status"]="ok";
            }elseif ($issetInInventario == "0"){
                 //echo "<pre>";print_r($post);exit();
                $sexoAnimalDescen=$post["selSexoNewDescen"];
                $selClaseAnimalNewDescen=$post["selClaseAnimalNewDescen"];
                $fechanacedescen=$post["fechanacedescen"];
                $codrazadescen=$post["codrazadescen"];
                $dataGlo=array(
                    "idtipoanimal"=>$tipoanimalmadre,
                    "idanimalpadre"=>$codpadredescen,
                    "idanimalmadre"=>$codmadredescen,
                    "codanimal"=>$codanimaldescen,
                    "codraza"=>$codrazadescen,
                    "apodo"=>"",
                    "descripcion"=>"",
                    "fechanacimiento"=>$fechanacedescen ,
                    "sexo"=>$sexoAnimalDescen,
                    "isHijo"=>null,
                    "isPadre"=>null,
                    "isMadre"=>null,
                    "idtipoingresoanimal"=>"",
                    "idtiposalidaanimal"=>"",
                    "idcliente"=>"",
                    "idproveedor"=>"",
                    "compradoa"=>"",
                    "vendidoa"=>"",
                    "pureza"=>"",
                    "proposito"=>"" ,
                    "preciocompra"=>"",
                    "precioventa"=>"",
                    "pesonace"=>"",
                    "fechaingreso"=>"",
                    "ismuertoalnacer"=>"",
                    "descmuertoalnacer"=>"",
                    "estado"=>999
                );
                $this->db->trans_start();
                $this->db->insert("animales", $dataGlo);
                $idMaxAnimal = $this->db->insert_id();
                $this->db->trans_complete();
                $dClase=$this->global_model->getDataClaseAnimalByTipoAnimalSexo($tipoanimalmadre,$sexoAnimalDescen);

                $dataDet=[];

                for($j=0;$j<sizeof($dClase);$j++){
                    $dataDet[]=array(
                        "idanimal"=>$idMaxAnimal,
                        "idclaseanimal"=>$dClase[$j]["idclaseanimal"],
                        "estado"=>1
                    );
                }
                if(sizeof($dataDet)>0){
                    $this->db->insert_batch("detanimalxclaseanimal", $dataDet);
                    $this->db->where(["idanimal"=>$idMaxAnimal,"idclaseanimal"=>$selClaseAnimalNewDescen])
                        ->update("detanimalxclaseanimal",["isclaseactual"=>1]);
                    $return["status"]="ok";
                }else{
                    $return["status"]="Fail";
                }


            }



        }else{
            $return["status"]="faill request";
        }
        echo json_encode($return);
    }




    ///----------------------------------------------------------

    public function getDataAnimalesActualizaClase(){
        $query="SELECT               
                 animales.idanimal,
                animales.idtipoanimal,
                animales.sexo,               
                animales.codanimal,               
                timestampdiff(month,animales.fechanacimiento ,curdate()) as mesestranscurridos
                FROM
                animales
                INNER JOIN detanimalxclaseanimal ON detanimalxclaseanimal.idanimal = animales.idanimal
                INNER JOIN claseanimal ON detanimalxclaseanimal.idclaseanimal = claseanimal.idclaseanimal
                INNER JOIN tipoanimales ON animales.idtipoanimal = tipoanimales.idtipoanimal AND claseanimal.idtipoanimal = tipoanimales.idtipoanimal
                where detanimalxclaseanimal.estado=1 and detanimalxclaseanimal.isclaseactual=1 and claseanimal.isestadofinal=0
                and animales.estado=1";
        $result = $this->db->query($query)->result_array();
        $dataR=null;
        $jdxx=null;
        foreach ($result as $v){

            $queryr="SELECT
                        claseanimal.idclaseanimal                        
                        FROM
                        claseanimal
                        where claseanimal.idsexo='".$v["sexo"]."' and  claseanimal.idtipoanimal='".$v["idtipoanimal"]."' and  ".$v["mesestranscurridos"]." BETWEEN claseanimal.mesini  and claseanimal.mesfin ";
            $rr= $this->db->query($queryr)->result_array();


            $queryIsHijo="SELECT animales.codanimal  FROM   animales
                          where animales.estado=1  and (animales.idanimalmadre ='".$v["codanimal"]."' or animales.idanimalpadre ='".$v["codanimal"]."' )";
            $IsPadreData= $this->db->query($queryIsHijo)->result_array();
            $claseFinalId=0;

            if(sizeof($IsPadreData) > 0){
                $ClaseFinal="SELECT claseanimal.idclaseanimal FROM claseanimal
                    where claseanimal.idsexo='".$v["sexo"]."' and claseanimal.idtipoanimal='".$v["idtipoanimal"]."' and claseanimal.estado=1 and claseanimal.isestadofinal=1";
                $dtClaseFinal= $this->db->query($ClaseFinal)->result_array();
                if(sizeof($dtClaseFinal) > 0){
                    $claseFinalId=$dtClaseFinal[0]["idclaseanimal"];
                    $jdxx[]= $claseFinalId;
                }

            }


            if(sizeof($rr)>0){
                $this->db->where(["idanimal"=>$v["idanimal"]])->update("detanimalxclaseanimal",["isclaseactual"=>0]);
                if($claseFinalId == 0){
                    $this->db->where(["idanimal"=>$v["idanimal"],"idclaseanimal"=>$rr[0]["idclaseanimal"]])->update("detanimalxclaseanimal",["isclaseactual"=>1]);
                }else{
                    $this->db->where(["idanimal"=>$v["idanimal"],"idclaseanimal"=>$claseFinalId])->update("detanimalxclaseanimal",["isclaseactual"=>1]);

                }
          }

            /*

            $dt[]=array(
                'codanimal'=>$v["codanimal"],
                'fechanacimiento'=>$v["fechanacimiento"],
                'codraza'=>$v["codraza"],
                'clase'=>$v["clase"],
                'tipoanimal'=>$v["tipoanimal"],

                'mesfin'=>$v["mesfin"],
                'mesini'=>$v["mesini"],
                'mesestranscurridos'=>$v["mesestranscurridos"],
                'data'=>$rr,
                'isPadre'=>$IsPadreData
            );*/
        }
        $this->db->where(["codigo"=>"actualizaclase"])->update("logdata",["fechareg"=>$this->getfecha_actual()]);
        echo json_encode(["status"=>"ok","dd"=>$jdxx]);

    }

    public function RESPALDOgetDataAnimalesActualizaClase(){
        $query="SELECT
                animales.fechanacimiento,
                animales.codraza,
                 animales.idanimal,
                animales.idtipoanimal,
                	animales.sexo,
                claseanimal.nombre as clase ,
                tipoanimales.nombre as tipoanimal,
                animales.codanimal,
                claseanimal.mesfin,
                claseanimal.mesini,
                timestampdiff(month,animales.fechanacimiento ,curdate()) as mesestranscurridos
                FROM
                animales
                INNER JOIN detanimalxclaseanimal ON detanimalxclaseanimal.idanimal = animales.idanimal
                INNER JOIN claseanimal ON detanimalxclaseanimal.idclaseanimal = claseanimal.idclaseanimal
                INNER JOIN tipoanimales ON animales.idtipoanimal = tipoanimales.idtipoanimal AND claseanimal.idtipoanimal = tipoanimales.idtipoanimal
                where detanimalxclaseanimal.estado=1 and detanimalxclaseanimal.isclaseactual=1 and claseanimal.isestadofinal=0
                and animales.estado=1";
        $result = $this->db->query($query)->result_array();
        $dataR=null;
        foreach ($result as $v){

            $queryr="SELECT
                        claseanimal.idclaseanimal,
                        claseanimal.nombre
                        FROM
                        claseanimal
                        where claseanimal.idsexo='".$v["sexo"]."' and  claseanimal.idtipoanimal='".$v["idtipoanimal"]."' and  ".$v["mesestranscurridos"]." BETWEEN claseanimal.mesini  and claseanimal.mesfin ";
            $rr= $this->db->query($queryr)->result_array();


            $queryIsHijo="SELECT animales.codanimal  FROM   animales
                          where animales.estado=1  and (animales.idanimalmadre ='".$v["codanimal"]."' or animales.idanimalpadre ='".$v["codanimal"]."' )";
            $IsPadreData= $this->db->query($queryIsHijo)->result_array();
            $claseFinalId=0;
            if(sizeof($IsPadreData) > 0){
                $ClaseFinal="SELECT claseanimal.idclaseanimal FROM claseanimal
                    where claseanimal.idsexo=1 and claseanimal.idtipoanimal='".$v["idtipoanimal"]."' and claseanimal.estado=1 and claseanimal.isestadofinal=1";
                $dtClaseFinal= $this->db->query($ClaseFinal)->result_array();
                if(sizeof($dtClaseFinal) > 0){
                    $claseFinalId=$dtClaseFinal[0]["idclaseanimal"];
                }

            }


            if(sizeof($rr)>0){
                $this->db->where(["idanimal"=>$v["idanimal"]])->update("detanimalxclaseanimal",["isclaseactual"=>0]);
                if($claseFinalId == 0){
                    $this->db->where(["idanimal"=>$v["idanimal"],"idclaseanimal"=>$rr[0]["idclaseanimal"]])->update("detanimalxclaseanimal",["isclaseactual"=>1]);
                }else{
                    $this->db->where(["idanimal"=>$v["idanimal"],"idclaseanimal"=>$claseFinalId])->update("detanimalxclaseanimal",["isclaseactual"=>1]);

                }
            }



            $dt[]=array(
                'codanimal'=>$v["codanimal"],
                'fechanacimiento'=>$v["fechanacimiento"],
                'codraza'=>$v["codraza"],
                'clase'=>$v["clase"],
                'tipoanimal'=>$v["tipoanimal"],

                'mesfin'=>$v["mesfin"],
                'mesini'=>$v["mesini"],
                'mesestranscurridos'=>$v["mesestranscurridos"],
                'data'=>$rr,
                'isPadre'=>$IsPadreData
            );

        }


        echo json_encode($dt);

    }


    public function finalFechaActualizaClase(){
        if ($this->input->server('REQUEST_METHOD') != 'POST'){ echo 0; }
        $return["status"]=null;
        $this->db->select('fechareg');
        $this->db->from("logdata");
        $this->db->where(["codigo"=>"actualizaclase"]);
        $return = $this->db->get()->result_array();
        echo json_encode(["fechareg"=>date("d-m-Y h:i:s",strtotime($return[0]["fechareg"]))]);
    }


}
