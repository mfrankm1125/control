<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CMS_Controller
{
    public  function  index($id){

         
        $this->template->add_js('template','scripts1','utf8',TRUE,TRUE);
        $this->template->renderizar('web/somos');
    }
}