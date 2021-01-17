<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends CMS_Controller {

 
	public function index()
	{	$datax = array('titulo'=>'titulx');
		//$this->template->add_js('template','scripts1','utf8',TRUE,TRUE);
	 	//$this->template->add_css('base',['css1','css2'],'print');
		//$this->template->add_message('testing..','error');
		//$this->template->add_message(['success'=>['bien uno','bien dos']]);
		//$this->load->library('acl',['id'=>1]);
		//$this->load->library('user',['id'=>1]);
		//echo '<pre>';print_r($this->acl->permissions); exit();

		$this->template->set_data('titulo',$datax);
		
		//$this->template->renderizar('web/web');
		$this->template->renderizar_web('welcome/index');
	}

	public function somos(){
		$data = array('titulo'=>'titulo');
		$this->template->set_flash_message(['success'=>['bien uno','bien dos']]);
		redirect();
		$this->template->set_data('titulo',$data);
		$this->template->renderizar('theme/somos');
	}

	public function dataBase()
	{	/*$this->load->database();
		echo "<pre>";print_r($this->db->get('pruebaci')->result_array());return;
		$this->load->view('theme/dbtest');*/
		$this->load->model('Test_model','t_model');
		$d=array('titulo','descripcion');
		$w=array('id_pruebaci'=>1);
		$r=$this->t_model->registro($d,$w,'object');
		echo "<pre>";print_r($r);
		$this->load->view('theme/dbtest');

	}
	public function insertaTemplate(){
        /*Forma de insercion
        $data=array('nombre'=>'admin',
                    'descripcion'=>'Template back-end',
                    'panel'=>'b',
                   );
        $this->db->insert('templates',$data);
        */
        $condicion=array('id'=>2);
        $data=array('nombre'=>'admin',
            'descripcion'=>'Template back-end',
            'panel'=>'b',
        );
        $this->db->where($condicion)->update('templates',$data);

        echo "se actualizo correctamente";
    }

}
