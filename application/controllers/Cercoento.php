<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cercoento extends CMS_Controller {
    private $_list_perm;
    private $table="tipoinsumo";

    private $className="Tipo Insumo";
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

        $this->template->set_data('title',$this->className);

        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
        $this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');


        $this->template->add_css('url_theme','plugins/chosen/chosen.min');
        $this->template->add_js('url_theme', 'plugins/chosen/chosen.jquery.min');

        $this->template->add_js('base', 'underscore');
        $this->template->renderizar('cercoento/index');
    }

    public function getDataTpinsumo(){
        $result=json_encode($this->_getDataTPI(0));
        $result='{"data":'.$result.'}';
        //print_r($result);
        echo $result;
    }

    public function getDataTInsumo(){
        if(sizeof($this->input->post())>0){
            $id=(int)$this->input->post('id',true);
            $result=json_encode($this->_getDataTPI($id));
            echo $result;
        }
    }

    private function _getDataTPI($id){
        $idd=(int)$id;
        if($id == 0){
            $condicion=array(
                'estado'=>1,
                'usercrea'=> $this->getUserId());

        }else{
            $condicion=array(
                'id_tipoinsumo'=>$idd,
                'estado'=>1,
                'usercrea'=> $this->getUserId());
        }
        $this->db->select('id_tipoinsumo,nombre,descripcion,fechacrea');
        $this->db->order_by('fechacrea', 'DESC');
        $result = $this->db->get_where($this->table,$condicion)->result_array();
        return $result;
    }

    public function setForm(){

        if(sizeof($this->input->post()) > 0){

            $idEdit=(int)$this->input->post('idEdit',true);
            $isEdit=(int)$this->input->post('isEdit',true);

            $nombre=$this->input->post('ntipoinsumo',true);
            $desc=$this->input->post('desc',true);
            $userId=$this->getUserId();
            $fechaActual=$this->getfecha_actual();
            $data=array(
                'nombre' =>$nombre ,
                'descripcion'=>$desc ,
                'usercrea' =>$userId ,
                'fechacrea'=> $fechaActual
            );
            $dataU=array(
                'nombre' =>$nombre ,
                'descripcion'=>$desc ,
                'userupdate' =>$userId,
                'fechaupdate'=>$fechaActual
            );

            if( $isEdit == 0 ){

                $this->db->insert($this->table, $data);
                echo 'ok';
            }
            if( $isEdit == 1 ){

                $condicion=array('id_tipoinsumo'=>$idEdit,'usercrea'=>$this->getUserId());
                $this->db->where($condicion)->update($this->table, $dataU);
                echo 'ok';
            }

        }else{
            echo "-.-";
        }

    }

    public function deleteData(){
        $post=$this->input->post(null,true);
        $return["status"]="";
        if(sizeof($post) > 0){

            $id=(int)$post["id"];
            $condicion=array('iddetcercoento'=>$id);
            $dataD=array(
                'estado'=>0
            );
            $this->db->where($condicion)->update("detcercoento", $dataD);
            $return["status"]="ok";

        }
        echo json_encode($return);
    }

    public function setMarker(){
        $return["status"]="fail";
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $isEdit=$post["isEdit"];
            $idEdit=$post["idEdit"];
            $ovi=$post["ovix"];
            $lat=$post["lat"];
            $lng=$post["lng"];
            $vivienda=$post["vivienda"];
            $anio=$post["anio"];

            $dataDetCerco=array(
                "ipress"=>"",
                "codovi"=>$ovi,
                "lat"=>$lat,
                "lng"=>$lng,
                "estado"=>1,
                "vivienda"=>$vivienda
            );

            if($isEdit == 1){
                $dataDetCerco["idcercoento"]=$idEdit;
                $condicion=array('iddetcercoento'=>$idEdit);
                $this->db->where($condicion)->update("detcercoento", $dataDetCerco);
                $return["status"]="ok";
                $return["data"]=$this->queryResult("1");
            }else if($isEdit == 0){
                $data=array(
                    "anio"=>$anio,
                    "nombre"=>"",
                    "descripcion"=>"",
                    "estado" =>1
                );

                $this->db->trans_start(); # Starting Transaction
                $this->db->insert('cercoento', $data); # Inserting data
                $idx = $this->db->insert_id();
                $this->db->trans_complete(); # Completing transaction

                $dataDetCerco["idcercoento"]=$idx;

                $this->db->insert('detcercoento', $dataDetCerco);
                $return["status"]="ok";
                $return["data"]=$this->queryResult("1");
            }else{

            }

        }

        echo json_encode($return);
    }

    public function queryResult($id='%%%'){
        $query="SELECT
        cercoento.idcercoento,
        cercoento.anio,
        detcercoento.iddetcercoento,
             
        
        detcercoento.lat,
        detcercoento.lng,
        detcercoento.codovi,
          detcercoento.vivienda
        FROM
        cercoento
        INNER JOIN detcercoento ON cercoento.idcercoento = detcercoento.idcercoento where detcercoento.estado>0 ";

        $ret=$this->db->query($query)->result_array();
        if($id=='%%%'){
            echo json_encode($ret);
        }else{
            return $ret;
        }


    }

    public function setMapa(){
        $strRequest = file_get_contents('php://input');
        $Request = json_decode($strRequest);
        $return["status"]="fail";
        $post=$this->input->post();
        if(sizeof($post)>0){
            $return=$post;
        }
        echo json_encode($Request);
    }
}
?>