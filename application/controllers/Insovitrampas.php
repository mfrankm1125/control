<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Insovitrampas extends CMS_Controller {
    private $_list_perm;
    public function __construct()
    {
        parent::__construct();

        if (!$this->acceso()) {
            redirect('errors/error/1');
        }
        $this->_list_perm = $this->lista_permisos();
        $this->template->set_data('lista_permisos', $this->_list_perm);
        $this->load->model('inspecciones_model');
        $this->load->model('ubigeo_model');
    }

    public function index(){
        // $this->template->set_data('data',$data);
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');

        $this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->add_js('base', 'underscore');
        $this->template->set_data("inspectores",$this->inspecciones_model->getUsersByRole("Inspector"));
          $this->template->set_data("estadosviviendaeninspeccion",$this->inspecciones_model->getEstadoViviendaEnInspeccionOvi());

        $this->template->renderizar('inspecciones/indexOvitrampas');
    }

    public function getDataListViviendas(){
         return json_encode($this->inspecciones_model->getDataListViviendas());
    }

    public function getDataTable(){
        $result=json_encode($this->inspecciones_model->getInspeccionOvitrampa());
        $result='{"data":'.$result.'}';
        //print_r($result);
        echo $result;
    }

    public function getData(){
        if(sizeof($this->input->post()) > 0){
            $id=$this->input->post('id',true);
            $result=json_encode($this->inspecciones_model->getInspeccionOvitrampa());
            echo $result;
        }

    }




    public function setForm(){

        if(sizeof($this->input->post()) > 0){

            $idEdit=(int)$this->input->post('idEdit',true);
            $isEdit=(int)$this->input->post('isEdit',true);


            $nombre=$this->input->post('name',true);
            $dirorubi=$this->input->post('direccion',true);
            $desc=$this->input->post('desc',true);
            //--
            $userId=$this->getUserId();
            $fechaActual=$this->getfecha_actual();

            $data=array(
                'nombre' =>$nombre ,
                'descripcion'=>$desc,
                'direccion'=>$dirorubi,
            );


            if( $isEdit == 0 ){
                $data['usercrea']=$userId;
                $data['fechacrea']=$fechaActual;
                $data['estado']=1;

                $this->db->insert($this->table, $data);
                echo 'ok';
            }
            if( $isEdit == 1 ){
                $data['userupdate']=$userId;
                $data['fechaupdate']=$fechaActual;

                $condicion=array($this->pk_idtable =>$idEdit,'usercrea'=>$this->getUserId());
                $this->db->where($condicion)->update($this->table, $data);
                echo 'ok';
            }

        }else{
            echo "-.-";
        }

    }



       public function getDepProvByDist(){
        $search = trim($this->input->get('term',TRUE));
        $r=$this->ubigeo_model->getDepartamentoProvinciaByDistrito($search);
        echo json_encode($r);
    }

    public function setIniRegInsOvi(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0) {
            $iddistrito = $post["iddistrito"];
            $idubigeo = $post["idubigeo"];
            $finspeccion = $post["finspeccion"];
            $selInspector = $post["selInspector"];
            $selEstablecimientoSalud=$post["selEstablecimientoSalud"];
            $selRedSalud= $post["selRedSalud"];
            $ddd=explode("-",$selRedSalud);
            $iddisa=$ddd[0];
            $idred=$ddd[1];
          $dataInsp=array(
          "idtipoinspeccion"=>2,
          "idinspector"=>$selInspector,
          "fechareg"=>$this->getfecha_actual(),
          "estado"=>1,
          "idubigeo"=>$idubigeo,
          "idred"=>$idred,
          "iddisa"=>$iddisa,
          "idipress"=>$selEstablecimientoSalud,
          "iddistrito"=>$iddistrito,
           "finspeccion"=>$finspeccion
          );
            $issetFecha=$this->inspecciones_model->_issetFechaInspeccion($selEstablecimientoSalud,$finspeccion);

            if($issetFecha=="0"){
                $this->db->trans_start();
                $this->db->insert("inspeccion",$dataInsp);
                $idinspeccion = $this->db->insert_id();
                $this->db->trans_complete();
                $return["status"]="ok";
                $return["idinspecion"]=$idinspeccion;
            }else{
                $return["status"]="failfecha";
                $return["msj"]="La fecha ya fue registrada";
            }

        }else{
            $return["status"]="errror";
        }
        echo json_encode($return);
    }


    public function getOvitrampasByDistrito(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $iddistri=$post["iddistri"];
            $term=$post["term"];
            $return=$this->inspecciones_model->getOvitrampasByDistrito($iddistri,$term);
        }else{
            $return["status"]="errror";
        }

        echo json_encode($return);
    }

    public function setDetalleInsOvitrampa(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
           // $this->showErrors($post);
            $selEstadoVivienda=$post["selEstadoVivienda"];
            $isEdit=$post["isEdit"];
            $idEdit=$post["idEdit"];
            $data=array(
                  "idinspeccion"=>$post["idinspeccion"],
                  "idvivienda"=> $post["idvivienda"],
                  "idestadoinspeccion"=>$post["alt"],
                  "codigoovitrampa"=>$post["codovitrampa"],
                  "ubicacionovitrampa"=>$post["ubicacion"],
                  "sectorovitrampa"=>$post["sector"],
                  "mzovitrampa"=>$post["mz"],
                  "latovitrampa"=>$post["lat"],
                  "lngovitrampa"=>$post["lng"],
                  "altovitrampa"=>$post["alt"],
                  "fechainspeccionovitrampa"=>$post["finspeccion"],
                  "nrohuevosovitrampa"=>$post["nhuevos"],
                  "estadoviviendainspeccion"=>$selEstadoVivienda
                 );
            if($isEdit == "0"){
                $data["estado"]=1;
                $data["fechareg"]=$this->getfecha_actual();
                 $data["fechacrea"]=$this->getfecha_actual();
                $this->db->trans_start();
                $this->db->insert("detinspeccion",$data);
                $idovi = $this->db->insert_id();
                $this->db->trans_complete();
                $return["data"]=$this->inspecciones_model->getListOvitrampasByIpressByIddetinspeccion($post["ipress"],$post["idinspeccion"],$post["finspeccion"],$idovi);


                $return["status"]="ok";
            }else if($isEdit == "1"){
                $this->db->where(["iddetinspeccion"=>$idEdit])->update("detinspeccion", $data);
                $return["data"]=$this->inspecciones_model->getListOvitrampasByIpressByIddetinspeccion($post["ipress"],$post["idinspeccion"],$post["finspeccion"],$idEdit);

               $return["status"]="ok";
            }

        }else{
            $return["status"]="errror";
        }

        echo json_encode($return);
    }

    public function getDetalleInspeccionOvitrampa(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $idinspeccion=$post["idinspeccion"];
            $return=$this->inspecciones_model->getDataDetalleInsOvitrampasBYInspLocalidad($idinspeccion);
        }else{
            $return["status"]="errror";
        }
        echo json_encode($return);
    }

    public function deleteDetalleInspeccionOvitrampa(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $iddetinspeccion=$post["iddetinspeccion"];
            $this->db->where(["iddetinspeccion"=>$iddetinspeccion])->update("detinspeccion", ["estado"=>0]);
            $return["status"]="ok";
        }else{
            $return["status"]="errror";
        }
        echo json_encode($return);
    }

    public function vermapa(){
        $this->template->set_data('anio',$this->inspecciones_model->getAniosRegOvitrampa());
        $this->template->add_js('base', 'underscore');
        $this->template->add_js('base', 'highcharts/highcharts');
        $this->template->renderizar('inspecciones/reporteOvitrampa');
    }

    public function getValuesHistoricoOvi(){
        $dt=null;
        for($i=0;$i<=3;$i++){
            $dt[]=array(
                "name"=>"s",
                "y"=>$i,
                "color"=>"red"
            );
        }

        echo  json_encode($dt);
    }

    public function getReportMap(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $idubigeo=$post["idubigeo"];
            $selEstablecimientoSalud=$post["selEstablecimientoSalud"];
            $anio=$post["anio"];
            $semana=$post["semana"];
            $sector=$post["sector"];
            $selEstadoVisita=$post["selEstadoVisita"];
            $return=$this->inspecciones_model->getReportMap($idubigeo,$selEstablecimientoSalud,$anio,$semana,$sector,$selEstadoVisita);
        }else{
            $return["status"]="fail";
        }
        echo json_encode($return);
    }

    public function getPoints(){
        $r=$this->inspecciones_model->getDataReportForOvitrampas();
        echo json_encode($r);
    }


    public function getDataDepProvbyDistri(){
        $search = trim($this->input->get('term',TRUE));
        $r=$this->ubigeo_model->getDepartamentoProvinciaByDistrito($search);
        echo json_encode($r);
    }

    public function getRedSalud(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $return=$this->ubigeo_model->getRedSalud($post["region"]);
        }else{
            $return["status"]="error";
        }
        echo json_encode($return);
    }

    public function getEstablecimientoSalud(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $coddisa=$post["coddisa"];
            $codred=$post["codred"];
            $iddistrito=$post["iddistrito"];
            $return=$this->ubigeo_model->getEstablecimientoSalud($coddisa,$codred,$iddistrito);
        }else{
            $return["status"]="error";
        }
        echo json_encode($return);
    }

    public function getDataCompendioVigilanciaOvi(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0) {
            $anio=$post["anio"];
            $idubigeo=$post["idubigeo"];
            $idipress=$post["idipress"];
            $nivel=$post["nivel"];
            if($nivel == "ovitrampa"){
              $return = $this->inspecciones_model->getOvitrampasConsolidado($anio,$idubigeo,$idipress);
            }else if($nivel == "sector"){
             $return = $this->inspecciones_model->getOvitrampasConsolidadoBySector($anio,$idubigeo,$idipress);
            }else if($nivel == "ipress"){
                $return = $this->inspecciones_model->getOvitrampasConsolidadoByIpress($anio,$idubigeo,$idipress);
            }else if($nivel == "distrito"){
                $return = $this->inspecciones_model->getOvitrampasConsolidadoByDistrito($anio,$idubigeo,$idipress);
            }


        }else{
                $return["status"]="error";
        }
        echo json_encode($return);
    }

    public function getListOvitrampasByIpress(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $ipress=$post["ipress"];
            $idinsp=$post["idinspeccion"];
            $finspeccion=$post["finspeccion"];
            $return=$this->inspecciones_model->getListOvitrampasByIpress($ipress,$idinsp,$finspeccion);
        }else{
            $return["status"]="error";
        }
        echo json_encode($return);
    }

    public function setNewInspector(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){

            $name=$post["nombreins"];
            $apellidos=$post["apellidosins"];
            $dni=$post["dniins"];
            $email=$post["emailins"];
            $tel=$post["telcelins"];
            $drr=array(
            "role"=>20,
            "name"=>$name,
            "lastnames"=>$apellidos,
            "nombrearearesponsable"=>$name." ".$apellidos,
            "telefono1"=>$tel,
            "dni"=>$dni,
            "direccion"=>"",
            "ciudad"=>"",
            "email"=>$email,
            "user"=>md5(time()),
            "password"=>time(),
            "status"=>1,
            "active"=>1
            );
            $this->db->trans_start();
            $this->db->insert("users",$drr);
            $idMax = $this->db->insert_id();
            $this->db->trans_complete();
            $ret=$this->inspecciones_model->getUsersByRole("Inspector");
            $return["status"]="ok";
            $return["id"]="$idMax";
            $return["data"]=$ret;
        }else{
            $return["status"]="error";
        }
        echo json_encode($return);
    }


    public function seeLineChartHistoOvi(){
        $post=$this->input->post(null,true);
        $anio=$post["anio"];
        $idubigeo=$post["idubigeo"];
        $ipress=$post["ipress"];
        $codovi=$post["codovi"];

        $return = $this->inspecciones_model->getListValuesOvitrampasByOvi($anio,$idubigeo,$ipress,$codovi);
        $r=null;
        foreach($return as $i){
            $r[]=array(
               "name"=>$i["name"],
                "y"=> floatval($i["y"]),
                "color"=>  $i["color"],
                "datavigi"=>  $i["datavigi"]

            );
        }
        echo json_encode($r);
    }

    public function deleteInsp(){
        $return["status"]="";
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
                $id=$post["id"];
            $this->db->where(["idinspeccion"=>$id])->update("inspeccion",["estado"=>0]);
            $this->db->where(["idinspeccion"=>$id])->update("detinspeccion",["estado"=>0]);
            $return["status"]="ok";
        }

        echo json_encode($return);
    }

    public function updLatLngByIdDetInspeccion(){
        $return["status"]="";
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $id=$post["iddetinspeccion"];
            $lat=$post["lat"];
            $lng=$post["lng"];
            $this->db->where(["iddetinspeccion"=>$id])->update("detinspeccion",["latovitrampa"=>$lat,"lngovitrampa"=>$lng]);
            $return["status"]="ok";
        }

        echo json_encode($return);
    }
}