<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TipoAnimal extends CMS_Controller {
    private $_list_perm;
    public $forecast;
    public $val = 0;
    public $acpadres;
    private $table="tipoanimales";
    private $pk_idtable="idtipoanimal";
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
        //$this->template->add_js('view','index');
        $this->template->renderizar('tipoanimal/index');
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

        }
        echo $result;

    }

    private function _getData($id){
        $campoEditaID=$this->pk_idtable;
        $idd=(int)$id;
        $condicion=array(
            'estado'=>1
        );
        if($id > 0) $condicion[$campoEditaID]=$idd;

        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($condicion);
       // $this->db->order_by('fechacrea', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function setForm(){
        $return['status']=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){
            $idEdit=(int)$post['idEdit'];
            $isEdit=(int)$post['isEdit'];
            $nombre=$post['name'];
            $nombre=str_replace( "\r\n", ' ', $nombre);
            //--

            $userId=$this->getUserId();
            $fechaActual=$this->getfecha_actual();

            $data=array(
                'nombre' =>$nombre

            );

            if( $isEdit == 0 ){

                $data['estado']=1;

                $this->db->insert($this->table, $data);
                $return['status']='ok';
            }
            if( $isEdit == 1 ){
                //$data['userupdate']=$userId;
                //$data['fechaupdate']=$fechaActual;

                $condicion=array($this->pk_idtable =>$idEdit);
                $this->db->where($condicion)->update($this->table, $data);
                $return['status']='ok';
            }

        }else {
            $return['status'] = 'Fallo Post';
        }
        echo json_encode($return);
    }

    public function deleteData(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){

            $id=(int)$post['id'];
            $condicion=array($this->pk_idtable =>$id);
            $dataD=array(

                'estado'=>0
            );

            $this->db->where($condicion)->update($this->table, $dataD);
            $return['status']='ok';

        }else{
           $return['status']='Fallo ';
        }
        echo json_encode($return);
    }


}
?>