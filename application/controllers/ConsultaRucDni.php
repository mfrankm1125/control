<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Peru\Http\ContextClient;
use Peru\Jne\Dni;
use Peru\Jne\DniParser;

use Peru\Sunat\HtmlParser;
use Peru\Sunat\Ruc;
use Peru\Sunat\RucParser;

require 'vendor/autoload.php';


class ConsultaRucDni extends CMS_Controller
{

    public function index(){

    }

    public function consultaDNI()
    {
        $return["status"] = null;
        $post = $this->input->post(null, true);
        if (sizeof($post) > 0) {
            $documento = $post["documento"];
            $dni = $documento;
            $cs = new Dni(new ContextClient(), new DniParser());
            $person = $cs->get($dni);
            if (!$person) {
                $return["result"] = 'Not found';

            }
            $return["result"] = $person;
            $return["status"] = "ok";
            echo json_encode($return);
        }

    }

    public function consultaRUC()
    {
        $return["status"] = null;
        $post = $this->input->post(null, true);
        if (sizeof($post) > 0) {
            $ruc = preg_replace('/\s+/', '',$post["ruc"]);
            $tipo=substr($ruc,0,2);
            $dd=[];

            $cs = new Ruc(new ContextClient(), new RucParser(new HtmlParser()));
            $cs->urlConsult='http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS03Alias';
            $company = $cs->get($ruc);
            if (!$company) {
                $return["result"] = 'Not found';
                $return["status"] = "fail";
            } else {
                $this->load->model('ubigeo_model');
                $return["ubigeo"] =[];

                if($company->departamento){
                    $drr=[];
                    $d=$this->ubigeo_model->getUbigeoByDPD($company->departamento,$company->provincia,$company->distrito);
                    if(sizeof($d)>0){
                        $drr=$d;
                    }
                    $return["ubigeo"]=$drr;
                }
                $return["result"] = $company;
                $return["extra"] = $dd;
                $return["status"] = "ok";
            }
        }
        echo json_encode($return);
    }

    private function _getDataRucBD($ruc){
        $q="SELECT
padronsunat.UBIGEO,
ubigeo.DESC_DPTO,
ubigeo.DESC_PROV,
ubigeo.DESC_DIST,
REPLACE(concat(COALESCE(padronsunat.TIPOVIA,'')
,'',COALESCE(padronsunat.NOMBREVIA,'')
,'',COALESCE(padronsunat.CODIGOZONA,'')
,'',COALESCE(padronsunat.TIPOZONA,'')
,'',COALESCE(padronsunat.NUMERO,'')
,'',COALESCE(padronsunat.KILOMETRO,'')
,'',COALESCE(padronsunat.INTERIOR,'')
,'',COALESCE(padronsunat.LOTE,'')
,'',COALESCE(padronsunat.DEPARTAMENTO,'')
,'',COALESCE(padronsunat.MANZANA,'')
 ),'-',' ') as dir
FROM
padronsunat
INNER JOIN ubigeo ON ubigeo.UBIGEO = padronsunat.UBIGEO
where RUC='$ruc'";
        $r=$this->db->query($q)->result_array();
        return $r;
    }


}