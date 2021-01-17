<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perfiles extends CMS_Controller
{
    public function __construct(){
        parent::__construct();

        if(!$this->acceso()){
            redirect('errors/error/1');
        }
        $this->_list_perm=$this->lista_permisos();
    }

    public function index(){
        
        
        $data=array('titulo'=>'Perfíles',
            'contenido'=>['hola']);
        //$x=$this->db->query('select * from modulos')->result_array();
        //echo '<pre>' ;print_r($x); exit();
        $this->template->add_js('url_theme','plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme','plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme','plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
        $this->template->add_js('view','index');

        $this->template->add_js('base','underscore');
        $this->template->set_data('data',$data);
        $this->template->set_data('dato','datox');
        $this->template->renderizar('perfiles/index');
    }
    
    public function perfiles_json(){
        $this->db->select('id, role');
        $result = $this->db->get_where('roles',['estado'=> 1])->result_array();
        $result=json_encode($result);
        $result='{"data":'.$result.'}';
        //print_r($result);
        echo $result;
        
    }

    public function add(){
        $op=$this->input->post('op', true);
        $desc = $this->input->post('descripcion', true);
        $id = $this->input->post('id', true);

        if($op == 0) {
            $data = array('role' => $desc);
            $this->db->insert('roles', $data);
            echo "ok";
        }
        if($op == 1){
            $condicion=array('id'=>$id);
            $data=array('role'=>$desc);
            $this->db->where($condicion)->update('roles',$data);
            echo "ok";
        }
    }

    public function update_data(){
        $id=$this->input->post('id',true);
        $condicion=array('id'=>$id);
        $result=json_encode($this->db->get_where('roles',$condicion)->result_array());
        echo $result;
    }

    public function delete(){
        $id=$this->input->post('id',true);
        $condicion=array('id'=>$id);
        $data=array('estado'=>0);
        $this->db->where($condicion)->update('roles',$data);
        echo 'ok';
    }

}