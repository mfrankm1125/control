<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Helper extends CMS_Controller {
    private $_list_perm;
    public $forecast;
    public $val = 0;
    public $acpadres;
    
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


        $this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->add_js('base', 'underscore');

        $this->template->renderizar('helper/index');
    }


    public function getTablesBD(){
       // SELECT TABLE_NAME FROM INFORMATION_SCHEMA.tables WHERE TABLE_SCHEMA='pyinia';
        $bd=$this->db->database;
        $this->db->select('TABLE_NAME as tabla');
        $this->db->from('INFORMATION_SCHEMA.tables');
        $this->db->where(["TABLE_SCHEMA"=>$bd]);
        $this->db->order_by('TABLE_NAME', 'asc');
        $result = $this->db->get()->result_array();
        echo json_encode($result);
    }

    public function getHelperTable(){
       // select COLUMN_NAME from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME = 'animales';
        $return=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
           $tabla=$post["tabla"];
            $bd=$this->db->database;
            /*$this->db->select('COLUMN_NAME as columna');
            $this->db->from('INFORMATION_SCHEMA.COLUMNS');
            $this->db->where(["TABLE_NAME"=>$tabla]);*/
            $query="select COLUMN_NAME as columna from INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA='".$bd."' and TABLE_NAME = '".$tabla."';";
            $return = $this->db->query($query)->result_array();
        }else{

        }
        echo json_encode($return);
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
        $campoEditaID=$this->pk_idtable;
        $idd=(int)$id;
        $condicion=array(
            'estado'=>1
        );
        if($id > 0) $condicion[$campoEditaID]=$idd;

        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($condicion);
        $this->db->order_by('fechacrea', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function setForm(){
        $post=$this->input->post(null,true);
        $d=array();
        if(sizeof($post) > 0){
           // echo "<pre>";print_r($post);exit();
            $idEdit=(int)$post['idEdit'];
            $isEdit=(int)$post['isEdit'];
            $nombre=trim($post['name']);
            $nombre=str_replace( "\r\n", ' ', $nombre);
            //$nombre=str_replace( "\n", '', $nombre);

            //echo $nombre;exit();
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
            }

        }else{
            $d["status"]= 'Error post';
        }
        echo json_encode($d);
    }

    public function deleteData(){
        if(sizeof($this->input->post()) > 0){
            $d=array();
            $id=(int)$this->input->post('id',true);
            $condicion=array($this->pk_idtable =>$id);
            $dataD=array(
                'userdelete'=>$this->getUserId(),
                'fechadelete' =>$this->getfecha_actual(),
                'estado'=>0
            );

            $this->db->where($condicion)->update($this->table, $dataD);
            $d['status']="ok";

        }else{
            $d['status']="error post";
        }
        echo json_encode($d);
    }


}
?>