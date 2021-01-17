<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Languaje extends CMS_controller {

    public function change ($lang){

        if(in_array($lang , $this->config->item('cms_lenguages'))){

            $this->session->set_userdata('global_lang', $lang);
        }
        redirect();
    }


}