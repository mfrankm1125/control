<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Soap extends CMS_Controller {
    private $_list_perm;
    private $table="tipoinsumo";

    private $className="Tipo Insumo";
    public function __construct()
    {
        parent::__construct();
        require_once APPPATH."/libraries/nusoap/nusoap.php";
        $this->nusoapserver=new soap_server();
        $this->nusoapserver->configureWSDL("my servicio","urn:miwebservice");
        $this->nusoapserver->register("getMyName",
            array("nombre"=>"xsd:string"),
            array("return"=>"xsd:string"),
            "urn:miwebservice",
            "urn:miwebservice#getMyName",
            "rpc",
            "encoded",
            "Get my name"
        );



    }

    public function index(){
        function getMyName($nombre){
                $res=array(
                    "nombre"=>$nombre
                );

              return json_encode($res)  ;
        }
        $this->nusoapserver->service(file_get_contents("php://input"));
    }


    public function test(){
        $params = array(
            'nombre' => "sss"
        );

        $result = $this->nusoapserver->call('getMyName', $params);
        var_dump($result);
    }

}
?>