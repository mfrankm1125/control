<?php
defined('BASEPATH') or exit('No direct script access allowed');

use classutil\Util;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Greenter\Report\Resolver\DefaultTemplateResolver;
use Greenter\Data\SharedStore;
use Greenter\Report\XmlUtils;
use Greenter\Ws\Reader\XmlFilenameExtractor;
use Greenter\Ws\Reader\XmlReader;
use Greenter\Ws\Resolver\XmlTypeResolver;
use Greenter\Ws\Services\SunatEndpoints;

use Greenter\Ubl\UblValidator;
use Luecano\NumeroALetras\NumeroALetras;

use Greenter\Model\Sale\Document;
use Greenter\Model\Summary\Summary;
use Greenter\Model\Summary\SummaryDetail;
use Greenter\Model\Summary\SummaryPerception;

use Greenter\Model\Voided\Voided;
use Greenter\Model\Voided\VoidedDetail;

use Greenter\Model\Sale\Note;

use Greenter\See;
use Greenter\Model\Response\StatusCdrResult;
use Greenter\Ws\Services\ConsultCdrService;
use Greenter\Ws\Services\SoapClient;



require 'vendor/autoload.php';
require 'filesfact/extra/src/Util.php';

class Statuscdr extends CMS_Controller
{
    private $_list_perm;
    private $utilx;
    private $endPoint = SunatEndpoints::FE_BETA;

    private $errorMsg = null;
    private $filename = null;

    public function __construct()
    {
        parent::__construct();


    }



    /**
     * @param array<string, string> $items
     * @return bool
     */
     function validateFields(array $items): bool
    {
        global $errorMsg;
        $validateFiels = ['rucSol', 'userSol', 'passSol', 'ruc', 'tipo', 'serie', 'numero'];
        foreach ($items as $key => $value) {
            if (in_array($key, $validateFiels) && empty($value)) {
                $errorMsg = 'El campo '.$key.', es requerido';
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $user
     * @param string $password
     * @return ConsultCdrService
     */
    function getCdrStatusService(?string $user, ?string $password): ConsultCdrService
    {
        $ws = new SoapClient(SunatEndpoints::FE_CONSULTA_CDR.'?wsdl');
        $ws->setCredentials($user, $password);

        $service = new ConsultCdrService();
        $service->setClient($ws);

        return $service;
    }

    /**
     * @param string $filename
     * @param string $content
     */
    function savedFile(?string $filename, ?string $content): void
    {
       // $fileDir = __DIR__.'/../../files';
        $fileDir="filesfact/comprobantes/";
        //print_r($fileDir);exit();
        if (!file_exists($fileDir)) {
            mkdir($fileDir, 0777, true);
        }
        $pathZip = $fileDir.DIRECTORY_SEPARATOR.$filename;
        file_put_contents($pathZip, $content);
    }

    /**
     * @param array<string, string> $fields
     * @return StatusCdrResult|null
     */
    public function process(array $fields): ?StatusCdrResult
    {
        global $filename;

        if (!isset($fields['rucSol'])) {
            return null;
        }

        if (!$this->validateFields($fields)) {
            return null;
        }

        $service = $this->getCdrStatusService($fields['rucUserSol'], $fields['passSol']);

        $arguments = [
            $fields['ruc'],
            $fields['tipo'],
            $fields['serie'],
            $fields['numero']
        ];
        if ( $fields['getCdr']) {
            $result = $service->getStatusCdr(...$arguments);
            if ($result->getCdrZip()) {
                $filename = 'R-'.implode('-', $arguments).'.zip';
                $this->savedFile($filename, $result->getCdrZip());
            }


            return $result;
        }else{
            return $service->getStatus(...$arguments);
        }


    }

    public function getStatusCdrJSON(){
        $post=$this->input->post(null,true);
        $return["status"]=false;
        if(sizeof($post)>0){
            $idbfact=$post["idfact"];
            $q="select * from bandejafacturacion where idbandejafacturacion=$idbfact ";
            $r=$this->db->query($q)->result_array();
            $dataEmpresa=[];
            if(sizeof($r)>0){
            $dataEmpresa=$this->getDataEmpresa($r[0]["idempresa"]);
            }

           // $return["datafact"]=$r;
           // $return["dataempresa"]=$dataEmpresa;


            $fields['rucSol']=$dataEmpresa["ruc"];
            $fields['rucUserSol']=$dataEmpresa["usersunat"];
            $fields['passSol']=$dataEmpresa["passsunat"];
            $fields['ruc']=$r[0]["numruc"];
            $fields['tipo']=$r[0]["idtipodoc"];
            $fields['serie']=$r[0]["serie"];
            $fields['numero']=$r[0]["correlativo"];

            $fields['getCdr']=true;//cambiar si es stado(false) o generar(true) o pedir el CDR

            $rsSunat=$this->process($fields);
           // print_r($fields);exit();
            if($rsSunat->isSuccess()){
                $arguments = [
                    $fields['ruc'],
                    $fields['tipo'],
                    $fields['serie'],
                    $fields['numero']
                ];
                $filename = 'R-'.implode('-', $arguments).'.zip';
                $json = array();
                $json["Estado"] = "Cargado";
                $json['data']['rsCodigo'] = $rsSunat->getCode();
                $json['data']['rsDescripcion'] = $rsSunat->getMessage();

                if(file_exists('filesfact/comprobantes/' . $filename)){
                    $json['data']['rsFilename']=$filename;
                }

                $ArrCrdResp=$rsSunat->getCdrResponse();
                if( $fields['getCdr'] ){
                    $json['data']['cdrResponse']["id"] =$ArrCrdResp->getId();
                    $json['data']['cdrResponse']["code"] =$ArrCrdResp->getCode();
                    $json['data']['cdrResponse']["description"] =$ArrCrdResp->getDescription();
                    $json['data']['cdrResponse']["notes"] =$ArrCrdResp->getNotes();

                    if($ArrCrdResp->getCode() == 0){

                        $dataRESunat["respuestacdrfile"] = $filename;
                        $dataRESunat["existcdr"] = "1";
                        $dataRESunat["codesunatrespuesta"] =$ArrCrdResp->getCode();
                        $dataRESunat["msjsunat"] =$ArrCrdResp->getDescription();

                        $this->db->where(["idbandejafacturacion"=>$idbfact])->update("bandejafacturacion",$dataRESunat);
                    }
                }

            }else{
                 $error = $rsSunat->getError();
                throw new Exception("Error " . $error->getCode() . " | " .$error->getMessage());
            }

            $return["data"]=$json;
            $return["status"]=true;
        }
        echo json_encode($return);

    }


    private function getDataEmpresa($idempresa){
        $q="select * from empresa where estado>0 and idempresa=$idempresa ";
        $r=$this->db->query($q)->result_array();
        return $r[0];
    }


}


    ?>