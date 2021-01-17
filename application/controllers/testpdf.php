<?php // User: Frank Date: 08/11/2016* Time: 02:12 PM
defined('BASEPATH') OR exit('No direct script access allowed');

class Testpdf extends CMS_Controller
{
    private $_list_perm;
    public function __construct()
    {
        parent::__construct();

        /* if (!$this->acceso()) {
             redirect('errors/error/1');
         } */

        $this->_list_perm = $this->lista_permisos();

    }

    public function index(){
        //establecemos el nombre del archivo

        $this->load->library('html2pdf/html2pdf');
        $this->load->view('testpdf/index', '');
        //$this->template->renderizar('testpdf/index');

    }

    public function test(){
        $string = $this->template->renderizar('stock/index',TRUE);


        $string = $this->load->view('stock/index',["_js"=>1,"_css"=>2], TRUE);

        echo $string;
    }


}