<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends CMS_Controller {


    public function error($item)
    {
        if($item == 1 ){
            $this->template->renderizar('errors/html2/error_404');
        }
    }


}
