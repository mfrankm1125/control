<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends CMS_Controller {
    private $_list_perm;

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

        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');


        //$this->template->add_css('url_theme','plugins/magic-check/css/magic-check.min');
        $this->template->add_js('base', 'underscore');
        $this->template->renderizar('config/index');
    }


    public function getValPrecioleche(){
        $d=$this->global_model->getPrecioVentaLeche();
            echo json_encode($d);
    }
    public function getValRecria(){
        $d=$this->global_model->getValRecria();
            echo json_encode($d);
    }

    public function setvalue(){
        $return["status"]=null;
        $post=$this->input->post(null,true);
        if(sizeof($post)>0){
            $op=$post["op"];
            $val=$post["val"];
              if($op == "recria"){
                  $this->db->where(["idcod"=>"recria"])->update("configuracion",["valor"=>$val]);
              }elseif ($op == "precioleche"){
                  $this->db->where(["idcod"=>"precioleche"])->update("configuracion",["valor"=>$val]);
              }
            $return["status"]="ok";
        }else{
            $return["status"]="Fail;";
        }
        echo json_encode($return);
    }
    


}
?>