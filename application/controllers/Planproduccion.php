<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Planproduccion extends CMS_Controller {
    private $_list_perm;
    public $forecast;
    public $val = 0;
    public $acpadres;
    private $table="s";
    private $pk_idtable="s";
    public function __construct()
    {
        parent::__construct();
        if (!$this->acceso()) {
            redirect('errors/error/1');
        }
        $this->_list_perm = $this->lista_permisos();
        $this->template->set_data('lista_permisos', $this->_list_perm);

    }

    public function index(){

        // $this->template->set_data('data',$data);
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');


        //$this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->add_js('base', 'underscore');
        $this->template->set_data("tipoproduccion",$this->getDataTipoProduccion());
        $this->template->set_data("estacionexperimental",$this->getDataEstaExperimental());
        $this->template->set_data("dataCultivo",$this->getCultivo());
        $this->template->set_data("dataClaseCultivo",$this->getClaseCultivo());
        $this->template->set_data("dataCategoriaCultivo",$this->getCategoriaCultivo());

        //$this->template->add_js('view','index');
        $this->template->renderizar('planproduccion/index');
    }

    public function getCultivo(){
        $this->db->select('*');
        $this->db->from("cultivo");
        $this->db->where(["estado"=>1]);
        $this->db->order_by('idcultivo', 'asc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function getCategoriaCultivo(){
        $this->db->select('*');
        $this->db->from("categoriacultivo");
        $this->db->where(["estado"=>1]);
        $this->db->order_by('idcategoriacultivo', 'asc');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getClaseCultivo(){
        $this->db->select('*');
        $this->db->from("clasecultivo");
        $this->db->where(["estado"=>1]);
        $this->db->order_by('idclasecultivo', 'asc');
        $result = $this->db->get()->result_array();
        return $result;
    }


    private function getDataTipoProduccion(){
        $this->db->select('*');
        $this->db->from("tipoproduccion");
        $this->db->where(["estado"=>1]);
        // $this->db->order_by('fechacrea', 'DESC');
        // $this->db->group_by('anio');
        // $this->db->order_by('anio', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }
    private function getDataEstaExperimental(){
        $this->db->select('*');
        $this->db->from("estacionexperimental");
        $this->db->where(["estado"=>1]);
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getDataTable(){
        $result=json_encode($this->_getData(0));
        $result='{"data":'.$result.'}';
        //print_r($result);
        echo $result;
    }

    public function getData(){
        $return=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $id=$post['id'];
            $result=json_encode($this->_getData($id));
        }else{
            $result="sss";
        }
        echo $result;

    }

    private function _getData($id=0){
        $campoEditaID=$this->pk_idtable;
        $idd=(int)$id;
        $condicion=array(
            'planproduccion.estado'=>1,
            'tipoproduccion.estado'=>1
        );
        if((int)$id > 0) $condicion["anio"]=$idd;

        if((int)$id == 0){$this->db->select('anio,tipoproduccion.nombre,tipoproduccion.idtipoproduccion'); }
        else{$this->db->select('*');}
        $this->db->from("planproduccion");
        $this->db->join("tipoproduccion","planproduccion.tipoproduccion=tipoproduccion.idtipoproduccion","inner");
        $this->db->where($condicion);
       // $this->db->order_by('fechacrea', 'DESC');
        if((int)$id == 0){  $this->db->group_by('anio,tipoproduccion.nombre,tipoproduccion.idtipoproduccion');  }
        $this->db->order_by('anio', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getDataPlanProd(){
        $return=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $anio=$post["anio"];
            $tiproduccion=$post["idtipoproduccion"];
            $this->db->select('*,tipoproduccion.nombre as tipoproduccionx,estacionexperimental.nombre as estacionexperimentalx,cultivo.nombre as cultivo,clasecultivo.nombre as clasecultivox,categoriacultivo.nombre as categoriacultivox');
            $this->db->from("planproduccion");
            $this->db->join("tipoproduccion","planproduccion.tipoproduccion=tipoproduccion.idtipoproduccion","inner");
            $this->db->join("estacionexperimental","planproduccion.estacionexperimental=estacionexperimental.idestacionexperimental","inner");
            $this->db->join("cultivo","planproduccion.idcultivo=cultivo.idcultivo","inner");
            $this->db->join("clasecultivo","planproduccion.idclasecultivo=clasecultivo.idclasecultivo","inner");
            $this->db->join("categoriacultivo","planproduccion.idcategoriacultivo=categoriacultivo.idcategoriacultivo","inner");
            $this->db->where("planproduccion.estado=1 and planproduccion.anio=$anio  and  planproduccion.tipoproduccion=$tiproduccion ");
            // $this->db->order_by('fechacrea', 'DESC');
           // $this->db->group_by('anio,tipoproduccion.nombre,tipoproduccion.idtipoproduccion');
            $return = $this->db->get()->result_array();
        }
        echo json_encode($return);
    }

    private function getDataPlanProdByID($id){
        $this->db->select('planproduccion.*,tipoproduccion.nombre as tipoproduccionx,estacionexperimental.nombre as estacionexperimentalx,cultivo.nombre as cultivo,clasecultivo.nombre as clasecultivox,categoriacultivo.nombre as categoriacultivox');
        $this->db->from("planproduccion");
        $this->db->join("tipoproduccion","planproduccion.tipoproduccion=tipoproduccion.idtipoproduccion","inner");
        $this->db->join("estacionexperimental","planproduccion.estacionexperimental=estacionexperimental.idestacionexperimental","inner");
        $this->db->join("cultivo","planproduccion.idcultivo=cultivo.idcultivo","inner");
        $this->db->join("clasecultivo","planproduccion.idclasecultivo=clasecultivo.idclasecultivo","inner");
        $this->db->join("categoriacultivo","planproduccion.idcategoriacultivo=categoriacultivo.idcategoriacultivo","inner");
        $this->db->where("planproduccion.estado=1 and planproduccion.idplanproduccion=$id");
        // $this->db->order_by('fechacrea', 'DESC');
        // $this->db->group_by('anio,tipoproduccion.nombre,tipoproduccion.idtipoproduccion');
        $return = $this->db->get()->result_array();
        return $return;
    }


    public function deleteDataG(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $anio=(int)$post['anio'];
            $tipoprod=(int)$post['tipoprod'];
            $condicion=array("anio" =>$anio , "tipoproduccion" => $tipoprod);
            $dataD=array(
                'estado'=>0
            );
            $this->db->where($condicion)->update("planproduccion", $dataD);
            $return['status']='ok';
        }else{
           $return['status']='Fallo ';
        }
        echo json_encode($return);
    }

    public function deleteDetailPOI(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $id=(int)$post['id'];
            $condicion=array('idpoi' =>$id);
            $dataD=array(
                'estado'=>0
            );
            $this->db->where($condicion)->update("poi", $dataD);
            $return['status']='ok';
        }else{
            $return['status']='Fallo ';
        }
        echo json_encode($return);
    }

    ///-----------------------------------------------------------------------------------
    public function setDataDetail(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
          //echo "<pre>";print_r($post);exit();
            $userId=$this->getUserId();
            $fechaActual=$this->getfecha_actual();
            $actop= $post["actop"];
            $arearesp=$post["arearesp"];
            $um= $post["um"];
            $meta= $post["meta"];
            $anio= $post["anio"];
            $dataIns=array(
                "anio"=>$anio,
                "actop"=>$actop,
                "arearesponsable"=>$arearesp,
                "um"=>$um,
                "metaanual"=>$meta,
                "estado"=>1,
                "usercrea"=>$userId,
                "fechacrea"=>$fechaActual
            );
            $this->db->insert("poi", $dataIns);
            $return['status']='ok';

            $return["data"]=$this->_getData($anio);
        }
        echo json_encode($return);
    }
    public function setFormP(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $idEdit=(int)$post['idEdit'];
            $isEdit=(int)$post['isEdit'];
            $aniopoi=trim($post['aniopoi']);
            //--
            $userId=$this->getUserId();
            $fechaActual=$this->getfecha_actual();
            if( $isEdit == 0 ){
                $actop=$post["actop"];
                $arearesp=$post["arearesp"];
                $um=$post["um"];
                $meta=$post["meta"];
                $dtIns=null;
                for($i=0;$i <count($actop) ;$i++){
                    $dtIns[]=array(
                        "anio"=>$aniopoi,
                        "actop"=>$actop[$i],
                        "arearesponsable"=>$arearesp[$i],
                        "um"=>$um[$i],
                        "metaanual"=>$meta[$i],
                        "estado"=>1,
                        "usercrea"=>$userId,
                        "fechacrea"=>$fechaActual

                    );
                }
               // echo "<pre>";print_r($dtIns);exit();
                if(sizeof($dtIns) > 0){
                    $this->db->insert_batch("poi", $dtIns);
                    $return['status']='ok';
                }else{
                    $return['status']='fail';
                }


            }
            if( $isEdit == 1 ){
                //$data['userupdate']=$userId;
                //$data['fechaupdate']=$fechaActual;
                $idpoi=$post["idpoi"];
                $metaalcanzada=$post["metaalcanzada"];
                for($i=0;$i <count($idpoi) ;$i++){
                    $dtUp=array(
                        "metaalcanzada"=>$metaalcanzada[$i],
                        "anio"=>$aniopoi,
                    );
                    $this->db->where(["idpoi"=>$idpoi[$i]])->update("poi", $dtUp);
                }
                 // $condicion=array($this->pk_idtable =>$idEdit);
                //$this->db->where($condicion)->update($this->table, $data);
                $return['status']='ok';
            }

        }else {
            $return['status'] = 'Fallo Post';
        }
        echo json_encode($return);
    }

    public function setForm(){
        $return['status']=null;
        $post=$this->input->post(null,true);
       //echo "<pre>";print_r($post);exit();
        if(sizeof($post) > 0){
            $idEdit=(int)$post['idEdit'];
            $isEdit=(int)$post['isEdit'];

            $userId=$this->getUserId();
            $fechaActual=$this->getfecha_actual();


            $data=array(
              "tipoproduccion"=>$post["selTipoProduccion"],
              "estacionexperimental" =>$post["selEstacionExperimental"],
              "ubigps" =>$post["ubicagps"],
              "departamento" =>$post["departamento"],
              "provincia"=>$post["provincia"],
              "distrito" =>$post["distrito"],
              "anexo"=>$post["anexo"],
              "responsable"=>$post["responsable"],
              "idcultivo" =>$post["selCultivo"],
              "cultivar"=>$post["cultivar"],
              "idclasecultivo" =>$post["selClase"],
              "idcategoriacultivo"=>$post["selCategoria"],
              "areasembrar" =>$post["areasembrar"],
              "fechasiembra" =>$post["fsiembra"],
              "fechaestimadacosecha" =>$post["fprobablecosecha"],
              "modalidadconduccion" =>$post["modalidadconduccion"],
              "semillaemplear"=>$post["semillaaemplear"],
              "rendimientoestimado"=>$post["rendimientoestimado"],
              "produccionestimado" =>$post["prodestimadapesototal"],
              "semillaacondicionadaestimada" =>$post["semillaacondicioandaestimada"],
              "costodeproduccion" =>$post["costoproduccionprogramado"],
              "riego" =>$post["riego"],
              "tiporiego" =>$post["tiporiego"],
              "distanciamientosurcos" =>$post["distanciasurcos"],
              "distanciamientoplantas"=>$post["distanciaplantas"],
              "ro23" =>$post["ro23"],
              "rdr" =>$post["rdr"],
              "presupuestototal" =>$post["presupuestototal"],
              "nrobeneficiarios" =>$post["nrobeneficiarios"],
              "areaatender" =>$post["areaatender"],
              "proyeccioningresoventa" =>$post["proyecciondeingresoporventa"],
              "margenutilidad"=>$post["margenutilidad"],
              "anio" =>$post["anio"],
              "criteriosdeestacionalidad"=>$post["criteriosdeestacionalidad"]

            );

            if( $isEdit == 0 ){
               $data['usercrea']=$userId;
               $data['fechacrea'] =$fechaActual;
               $data['estado'] =1;
               // echo "<pre>";print_r($data);exit();
                $this->db->insert("planproduccion", $data);
                $return['status']='ok';
            }
            if( $isEdit == 1 ){
                $data['userupdate']=$userId;
                $data['fechaupdate'] =$fechaActual;
                $condicion=array("idplanproduccion" =>$idEdit);
                $this->db->where($condicion)->update("planproduccion", $data);
                $return['status']='ok';
            }
        }else {
            $return['status'] = 'Fallo Post';
        }
        echo json_encode($return);
    }

    public function deleteDataPlanProdByID(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        //echo "<pre>";print_r($post);exit();
        if(sizeof($post) > 0){
            $id=$post["id"];
            $this->db->where(["idplanproduccion"=>$id])->update("planproduccion",["estado"=>0]);
            $return['status']="ok";
        }
        echo json_encode($return);
    }

    public function setDataProdEjec(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        $data=null;
        if(sizeof($post) > 0){
            /*echo "<pre>";print_r($_FILES);
            echo "<pre>";print_r($post);exit();*/
            $estaexpeeje=$post["estaexpeeje"];
            $departamentoeje =$post["departamentoeje"];
            $provinciaeje =$post["provinciaeje"];
            $distritoeje =$post["distritoeje"];
            $anexoeje=$post["anexoeje"];
            $responsableeje =$post["responsableeje"];
            $cultivoeje=$post["cultivoeje"];
            $cultivareje=$post["cultivareje"];
            $claseeje =$post["claseeje"];
            $catcultivoeje=$post["catcultivoeje"];
            $areasembrareje=$post["areasembrareje"];
            $loteeje=$post["loteeje"];
            $germinacioneje=$post["germinacioneje"];
            $purezaeje =$post["purezaeje"];
            $semillahaeje=$post["semillahaeje"];
            $rendimientoeje=$post["rendimientoeje"];
            $producciontotaleje=$post["producciontotaleje"];
            $semillaacondieje=$post["semillaacondieje"];
            $fsiembraeje=$post["fsiembraeje"];
            $fcosechaeje=$post["fcosechaeje"];
            $idplanproduccioneje=$post["idplanproduccioneje"];

            $ingresoplantaproce=$post["ingresoplantaproce"];
            $pesosecoproce=$post["pesosecoproce"];
            $impurezasproce=$post["impurezasproce"];
            $descarteproce=$post["descarteproce"];
            $totalsemillaobtenida=$post["totalsemillaobtenida"];


            $fechaanalisiscalidad=$post["fechaanalisiscalidad"];
            $purezaeje=$post["purezaeje"];
            $materialInerte=$post["materialInerte"];
            $germinacioneje=$post["germinacioneje"];
            $humedadcalidadeje=$post["humedadcalidadeje"];
            $semillaotravariedadeje=$post["semillaotravariedadeje"];
            $semillamalezaeje=$post["semillamalezaeje"];
            $descinfectatntecalidadeje=$post["descinfectatntecalidadeje"];

            $data=array(
                "estacionexperimentaleje"=>$estaexpeeje,
              "departamentoeje"=>$departamentoeje,
              "provinciaeje"=>$provinciaeje,
              "distritoeje" =>$distritoeje,
              "anexoeje"=>$anexoeje,
              "responsableeje"=>$responsableeje,
              "cultivoeje"=>$cultivoeje,
              "cultivareje"=>$cultivareje,
              "clasecultivoeje"=>$claseeje,
              "categoriacultivoeje"=>$catcultivoeje,
              "areasembrareje"=>  $areasembrareje,
              "loteeje"=>$loteeje,
              "germinacioneje" => $germinacioneje,
              "purezaeje"=> $purezaeje ,
              "semillahaeje"=>  $semillahaeje,
              "rendimientoeje"=>   $rendimientoeje,
              "producciontotaleje"=> $producciontotaleje,
              "semillaacondieje"=>  $semillaacondieje,
              "fechasiembraeje"=>  $fsiembraeje,
              "fechacosechaeje" => $fcosechaeje,
              "fechaacteje" => $this->getfecha_actual(),
              "totalingresoplantaeje"=>$ingresoplantaproce ,
              "pesosecoproceeje"=>$pesosecoproce  ,
              "impurezaproceeje"=>$impurezasproce,
              "descarteproceeje"=>$descarteproce,
              "fechaanalisiscalidadeje"=>$fechaanalisiscalidad,
              "purezaanalisiseje"=>$purezaeje,
              "materialinerteeje"=>$materialInerte,
              "germinacionanalisiseje"=>$germinacioneje,
              "humedadeje"=>$humedadcalidadeje,
              "semillaotravariedadeje"=>$semillaotravariedadeje,
              "semillamalezaeje"=>$semillamalezaeje,
              "descinfectanteeje"=>$descinfectatntecalidadeje

            );

            if(!empty($_FILES) ){
                //echo "<pre>";print_r($_FILES);exit();
                $nameInput="fileSiembra";
                //echo "<pre>";print_r($fileplancosto);exit();
                if($_FILES[$nameInput]["size"] > 0){
                    $rt=$this->uploadFile($nameInput);
                    if($rt["status"]=="ok"){
                        $data["urlregistroorgacertificador"]=$rt["namefile"];
                    }

                }
                $nameInput2="fileCosecha";
                if($_FILES[$nameInput2]["size"] > 0){
                    $rtx1=$this->uploadFile($nameInput2);
                    if($rtx1["status"]=="ok"){
                        $data["urlactacoseha"]=$rtx1["namefile"];
                    }
                }

                $nameInput3="fileProcesamiento";
                if($_FILES[$nameInput3]["size"] > 0){
                    $rtx2=$this->uploadFile($nameInput3);
                    if($rtx2["status"]=="ok"){
                        $data["urlinfoprocesamiento"]=$rtx2["namefile"];
                    }
                }
                $nameInput4="fileAnalisisCalidad";
                if($_FILES[$nameInput4]["size"] > 0){
                    $rtx4=$this->uploadFile($nameInput4);
                    if($rtx4["status"]=="ok"){
                        $data["urlinfoanalisiscalidadeje"]=$rtx4["namefile"];
                    }
                }


            }

            $this->db->where(["idplanproduccion"=>$idplanproduccioneje])->update("planproduccion",$data );
            $returnData=$this->getDataPlanProdByID($idplanproduccioneje);
            $return["status"]="ok";
            $return["dtreturn"]=$returnData;
            $return["error"]="";
        }else{
            $return["status"]="Fail";
        }
        echo json_encode($return);
    }
    public function uploadFile($nameInput){
        $data=null;
        //print_r($FILES);exit();
        if (!empty($nameInput) ) {

            $_FILES['filex']['name'] = $_FILES[$nameInput]['name'];
            $_FILES['filex']['type'] = $_FILES[$nameInput]['type'];
            $_FILES['filex']['tmp_name'] = $_FILES[$nameInput]['tmp_name'];
            $_FILES['filex']['error'] = $_FILES[$nameInput]['error'];
            $_FILES['filex']['size'] = $_FILES[$nameInput]['size'];


            $config['upload_path'] = './assets/uploads/archivos';
            $config['allowed_types'] = '*';
            $config['max_size'] = 5000 * 1024;

            $this->load->library('upload', $config);
            $table="";
            if (!$this->upload->do_upload("filex")) {
                $data = array('status' => $this->upload->display_errors());

            } else {
                $data = $this->upload->data();
                $nameFile= htmlentities($data['file_name']);
                $data['status'] = "ok";
                $data['namefile'] = $nameFile;
            }
        } else {
            $data['status'] = "No cargo archivos";
        }
        //echo "<pre>";print_r( $data);
        return $data ;
    }


    public function setEnvioAlmacen(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        $data=null;
        if(sizeof($post) > 0){
           // echo "<pre>"; print_r($post);exit();
            $semillaacondienviox =$post["semillaacondienviox"];
            $semillaacondienvioxkg =$post["semillaacondienvioxkg"];
            $sacosenviaox =  $post["sacosenviaox"];
            $idplanejecEnvioAlmacen =$post["idplanejecEnvioAlmacen"];
            $cultivoejeEnvioAlmacen =$post["cultivoejeEnvioAlmacen"];
            $cultivarejeEnvioAlmacen = $post["cultivarejeEnvioAlmacen"];
            $claseejeEnvioAlmacen =$post["claseejeEnvioAlmacen"];
            $catcultivoejeEnvioAlmacen =$post["catcultivoejeEnvioAlmacen"];
            $loteEjeEnvioAlmacen =$post["loteEjeEnvioAlmacen"];
            $nroloteproduccion =$post["namelote"];
            $dataProd=array(
                "idplanproduccion"=>$idplanejecEnvioAlmacen ,
                "fehareg"=>$this->getfecha_actual() ,
                "estado"=>1
            );

            $this->db->insert("productokardex", $dataProd);
            $dataUpdEnvio=null;
            $nameInput2="fileVerificacionAlmacen";
            if($_FILES[$nameInput2]["size"] > 0){
                $rtx1=$this->uploadFile($nameInput2);
                if($rtx1["status"]=="ok"){
                    $dataUpdEnvio["urlmedioverificacionenvioalmacen"]=$rtx1["namefile"];
                    $dataUpdEnvio["isconcluido"]=1;
                    $dataUpdEnvio["fechaenvioalmacen"]=$post["fechaenvioalmacen"];
                    $dataUpdEnvio["fechaenvioalmacenreg"]=$this->getfecha_actual();
                    $dataUpdEnvio["nroloteproduccion"]=$nroloteproduccion;
                }
            }

            $this->db->where(["idplanproduccion"=>$idplanejecEnvioAlmacen])->update("planproduccion",$dataUpdEnvio);

            $return["status"]="ok";

        }
        echo json_encode($return);
    }

}
?>