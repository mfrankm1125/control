<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ubigeo extends CMS_Controller {
    private $_list_perm;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ubigeo_model');
    }

    public function index(){

    }

    public function getUbigeo(){
        $search = trim($this->input->get('term',TRUE));
        $return=$this->ubigeo_model->getUbigeoByDistrito(null,$search);
        echo json_encode($return);
    }

}
?>