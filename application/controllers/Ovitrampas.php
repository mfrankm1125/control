<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ovitrampas extends CMS_Controller {
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
        //$this->template->set_data("viviendas",$this->getDataListViviendas());
        $this->template->renderizar('ovitrampas/index');
    }

    public function getDataListViviendas(){
         return json_encode($this->inspecciones_model->getDataListViviendas());
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

    public function getDataO(){
        if(sizeof($this->input->post()) > 0){
            $id=$this->input->post('id',true);
            $result=json_encode($this->ubigeo_model->getOvitrampas($id,1));
            echo $result;
        }

    }

    private function _getData($id){
        $idd=(int)$id;
        $condicion=null;
        if($id > 0) $condicion=$idd;
        $result=$this->ubigeo_model->getOvitrampas($condicion);
        return $result;
    }

    public function setForm(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $cod=$post["codOvi"];
            $finstala=$post["finstala"];
            $idvivienda=$post["idvivienda"];

            $selEstablecimientoSalud=$post["selEstablecimientoSalud"];
            $idCodOviExist=$post["idCodOviExist"];
            $idDetEdit=$post["idDetEdit"];
            $ubicacionovi=$post["ubicacionovi"];
            $sector=$post["sector"];
            $mz=$post["manzana"];
           // $iddistrito=$post["iddistrito"];
            $lat=floatval($post["lat"]);
            $lng=floatval($post["lng"]);
            $dataOvi=array(
                "codigoovitrampa"=>$cod,
                "estado"=>1
            );
            if($idvivienda=="0"){
               $ress=$this->_insertVivienda($post);
               $idvivienda=$ress["idvivienda"];
              // $this->showErrors($ress);
            }

            if($idCodOviExist ==""){
                $this->db->trans_start();
                $this->db->insert("ovitrampas",$dataOvi);
                $idovi = $this->db->insert_id();
                $this->db->trans_complete();

            }else{
                $idovi=$idCodOviExist;
            }
            $selRedSalud= $post["selRedSalud"];
            $ddd=explode("-",$selRedSalud);
            $iddisa=$ddd[0];
            $idred=$ddd[1];
            $dDetOvi=array(
              "idovitrampa"=>$idovi,
              "idvivienda"=>$idvivienda,
              "codovitrampa"=>$cod,
              "fechainstalacion"=>$finstala,
              "observacion"=>"",
              "ubicacionenvivienda"=>$ubicacionovi,
              "lat"=>$lat,
              "lng"=>$lng,
              "idred"=>$idred,
              "iddisa"=>$iddisa,
              "idipress"=>$selEstablecimientoSalud,
              "sector"=>$sector,
              "mz"=>$mz

            );
          //  $this->showErrors($dDetOvi);
            if($idDetEdit != ""){
                $this->db->where(["iddetovitrampasvivienda"=>$idDetEdit])->update("detovitrampavivienda",$dDetOvi);
            }
            if($idDetEdit =="" && $idCodOviExist !=""){
                $this->db->where(["idovitrampa"=>$idCodOviExist])->update("detovitrampavivienda",["isactual"=>0]);
                $dDetOvi["isactual"]=1;
                $dDetOvi["fechareg"]=$this->getfecha_actual();
                $dDetOvi["estado"]=1;
                $this->db->insert("detovitrampavivienda",$dDetOvi);
            }
            if($idDetEdit =="" && $idCodOviExist ==""){
                $dDetOvi["isactual"]=1;
                $dDetOvi["fechareg"]=$this->getfecha_actual();
                $dDetOvi["estado"]=1;
                $this->db->insert("detovitrampavivienda",$dDetOvi);
            }
            $return["status"]="ok";
        }else{
            $return["status"]="errror";
        }
        echo json_encode( $return);
    }

    public function deleteData(){
        $return["status"]=null;
        if(sizeof($this->input->post()) > 0){
            $id=(int)$this->input->post('id',true);
            $condicion=array("idovitrampa" =>$id );
            $dataD=array(
                'estado'=>0
            );
            $this->db->where($condicion)->update("ovitrampas", $dataD);
            $return["status"]="ok";
        }
        echo json_encode($return);
    }

    public function getDirViviendaByDistri(){
      $post=$this->input->post(null,true);
      if(sizeof($post)>0){
            $term=$post["term"];
            $iddistri=$post["iddistri"];
          $r=$this->ubigeo_model->getDirViviendaByDistri($iddistri,$term);
      }
      echo json_encode($r);
    }
    public function getIssetOvitrampa(){
        $search = trim($this->input->get('term',TRUE));
        $r=$this->ubigeo_model->getIssetOvitrampa($search);
        echo json_encode($r);
    }

    public function getDepProvByDist(){
        $search = trim($this->input->get('term',TRUE));
        $r=$this->ubigeo_model->getDepartamentoProvinciaByDistrito($search);
        echo json_encode($r);
    }

    public function getUbicacionOvitrampaInVivienda(){
        $search = trim($this->input->get('term',TRUE));
        $return=$this->_getUbicacionOvitrampaInVivienda($search);
        echo json_encode($return);
    }
    private function _getUbicacionOvitrampaInVivienda($search){
        $query="SELECT DISTINCT
                detovitrampavivienda.ubicacionenvivienda as value
                FROM
                detovitrampavivienda where ubicacionenvivienda like '%$search%' ";
        $ret=$this->db->query($query)->result();
        return $ret;
    }

    private function _insertVivienda($post){
        $selRedSalud= $post["selRedSalud"];
        $ddd=explode("-",$selRedSalud);
        $iddisa=$ddd[0];
        $idred=$ddd[1];
        $dtIns=array(
            "ubigeo"=>$post["idubigeo"],
            "idbarrio"=>$post["barrio"],
            "idsector"=>$post["sector"],
            "idmanzana"=>$post["manzana"],
            "iddistrito"=>$post["iddistrito"],
            "iddisa"=>$iddisa,
            "idred"=>$idred,
            "idipress"=>$post["selEstablecimientoSalud"],
            "codigo"=>"",
            "nombre"=>"",
            "direccion"=>$post["dir"],
            "nro"=>"",
            "lat"=>$post["lat"],
            "lng"=>$post["lng"]
        );
        $dtIns["fechareg"]=date("Y-m-d");
        $dtIns["estado"]=1;

        $this->db->trans_start();
        $this->db->insert("viviendas",$dtIns);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        $return["status"]="ok";
        $return["idvivienda"]=$insert_id;
        return $return;
    }
}