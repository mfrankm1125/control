<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Viviendas extends CMS_Controller {
    private $_list_perm;


    public function __construct()
    {
        parent::__construct();

        if (!$this->acceso()) {
            redirect('errors/error/1');
        }
        $this->_list_perm = $this->lista_permisos();
        $this->template->set_data('lista_permisos', $this->_list_perm);
        $this->load->model('ubigeo_model');
    }

    public function index(){

        // $this->template->set_data('data',$data);
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');

        $this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->add_js('base', 'underscore');

        $this->template->renderizar('viviendas/index');
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

    private function _getData($id){
        $campoEditaID="idvivienda";
        $idd=(int)$id;
        $condicion=array(
            'viviendas.estado > '=>0,

        );
        if($id > 0) $condicion[$campoEditaID]=$idd;
        $this->db->select('viviendas.ubigeo,
                    dist.DESC_DIST,
                    dist.DESC_PROV,
                    dist.DESC_DPTO,
                    dist.COD_DIST,
                    viviendas.idsector,
                    viviendas.idbarrio,
                    viviendas.idmanzana,
                    viviendas.lat,
                    viviendas.lng,
                    viviendas.fechareg,
                    viviendas.idvivienda,
                    viviendas.direccion,
                    viviendas.iddistrito,
                    viviendas.iddisa,
                    viviendas.idred,
                    viviendas.idipress,              
                    viviendas.nro');
        $this->db->from("viviendas");
        $this->db->join("dist","dist.UBIGEO = viviendas.ubigeo","inner");
        $this->db->where($condicion);
        $this->db->order_by("viviendas.idvivienda desc");

        $result = $this->db->get()->result_array();
        return $result;
    }

    public function setForm(){



    }

    public function deleteData(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $id=(int)$post['id'];
            $condicion=array("idvivienda" =>$id );
            $dataD=array(
                'estado'=>0
            );
            $this->db->where($condicion)->update("viviendas", $dataD);
            $return['status']='ok';
        }else{
            $return['status']='Fallo ';
        }
        echo json_encode($return);
    }

    public function setIniRegInsOvi(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){

               $region =$post["region"] ;
               $provincia=$post["provincia"];
            $distrito=$post["distrito"];
            $localidad=$post["localidad"];
            $finstalacion=$post["finstalacion"];
            $inspector=$post["inspector"];
            $dtIns=array(
              "idtipoinspeccion"=>2 ,
              "idinspector"=>2 ,
              "estado"=>1 ,
              "region"=>$region,
              "provincia"=>$provincia,
              "distrito"=>$distrito,
              "localidad"=>$localidad,
              "fechainstalacion"=>$finstalacion,
              "fechainspeccionovi"=>2
            );
            $this->db->trans_start();
            $this->db->insert("inspeccion",$dtIns);
            $idMax = $this->db->insert_id();
            $this->db->trans_complete();
            $return["status"]="ok";
            $return["id"]=$idMax;
        }else{
            $return["status"]="error";
        }

        echo json_encode($return);
    }


    public function setDataFormVivivenda(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $isEdit=$post["isEdit"];
            $idEdit=$post["idEdit"];

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
              "nro"=>$post["dirnro"],
              "lat"=>$post["lat"],
              "lng"=>$post["lng"]
          );
          if($isEdit == 0){
              $dtIns["fechareg"]=date("Y-m-d");
              $dtIns["estado"]=1;
              $this->db->insert("viviendas",$dtIns);
             $return["status"]="ok";
          }else{
              $this->db->update('viviendas',$dtIns,["idvivienda"=>$idEdit]);
              $return["status"]="ok";
          }

        }else{
            $return["status"]="errror";
        }

        echo json_encode($return);
    }

    public function getDepProvByDist(){
        $search = trim($this->input->get('term',TRUE));
        $r=$this->ubigeo_model->getDepartamentoProvinciaByDistrito($search);
        echo json_encode($r);
    }

}