<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CMS_Lang extends CI_Lang{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function line($line= '', $log_errors = true){
        
        $value = parent::line($line);
        
        if($value == FALSE){
            
          return $line;  
        }
        return $value;
    }
      
}
 


