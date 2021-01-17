<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tpinsumo extends CMS_Controller {
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
        $this->template->add_js('view','index');

        $this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');


        //$this->template->add_js('base', 'underscore');
        $this->template->renderizar('tpinsumo/index');
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

    public function deleteTpinsumo(){
        if(sizeof($this->input->post()) > 0){

            $id=(int)$this->input->post('id',true);
            $condicion=array('id_tipoinsumo'=>$id,'usercrea'=>$this->getUserId());
            $dataD=array(
                'userdelete'=>$this->getUserId(),
                'fechadelete' =>$this->getfecha_actual(),
                'estado'=>0
            );

            $this->db->where($condicion)->update($this->table, $dataD);
            echo json_encode('ok');

        }else{

        }
    }



}
?>