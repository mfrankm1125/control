<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Soapclient extends CMS_Controller {
    private $_list_perm;
    private $table="tipoinsumo";

    private $className="Tipo Insumo";
    public function __construct()
    {
        parent::__construct();



    }

    public function index(){
        require_once APPPATH."/libraries/nusoap/nusoap.php";
        $this->client=new nusoap_client("http://localhost/pyaedes/index.php/soap/?wsdl");
        $error=$this->client->getError();
        if($error){
            echo $error;
        }
        $res=$this->client->call("getMyName",array("nombre"=>"raul"));
        if($this->client->fault){
            echo $this->client->fault;
        }else{
            $data=json_encode($res);
            echo $data;
        }

    }


}
?>