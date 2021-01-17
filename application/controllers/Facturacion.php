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


require 'vendor/autoload.php';
require 'filesfact/extra/src/Util.php';

class Facturacion extends CMS_Controller
{
    private $_list_perm;
    private $utilx;
    private $endPoint = SunatEndpoints::FE_PRODUCCION;

    public function __construct()
    {
        parent::__construct();


    }

    public function index()
    {

        $this->template->set_data('title', "Ventas");
        $this->template->set_data('anioscompras', $this->getAniosCompras());
        $this->template->set_data("tiponotacredito", $this->getTipoNotaCredito());
        $this->template->set_data("listaempresas",$this->getEmpresas());
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/jquery.dataTables');
        $this->template->add_js('url_theme', 'plugins/datatables/media/js/dataTables.bootstrap');
        $this->template->add_js('url_theme', 'plugins/datatables/extensions/Responsive/js/dataTables.responsive.min');
        $this->template->add_css('url_theme', 'plugins/datatables/extensions/Responsive/css/dataTables.responsive');

        $this->template->add_css('url_theme', 'plugins/magic-check/css/magic-check.min');
        $this->template->add_css('url_theme', 'plugins/chosen/chosen.min');
        $this->template->add_js('url_theme', 'plugins/chosen/chosen.jquery.min');
        $this->template->add_js('base', 'underscore');


        $this->template->renderizar('facturacion/index');
    }

    public function getData()
    {
        $this->load->library('Datatables');
        $this->db->simple_query('SET SESSION group_concat_max_len=15000');
        $this->datatables->select('
               idbandejafacturacion,
 idtipodoc,
 referencia,
 idreferencia,
 serie,
 correlativonro,
correlativo,
seriecorrelativo,
isenviadosunat,
codesunatrespuesta,
xmlfile,
msjsunat,
ublvalido,
msjubl,
respuestacdrfile,
existcdr,
numruc,
nombrearchxml,
resumenvalue,
resumenfirma,
tipdocucliente,
numdocucliente,
razoncliente,
direccioncliente,
xml_content,
pdf_content,
codsucursal,
gravado,
inafecto,
exonerado,
suma_igv,
suma_descuento,
descuento_global,
gratuito,
imagen_qr,
html_content,
estado,
fecharegistroorigen,
fechageneraxml,
fechaenvio,
fechareg,
idempresa,
emailcliente,ftipocomprobante.descripcion as tipodoccompro
                  ')->from('bandejafacturacion')
            ->join('ftipocomprobante', 'bandejafacturacion.idtipodoc = ftipocomprobante.idftipocomprobante', 'inner')
            ->where("bandejafacturacion.estado > 0")->order_by("fecharegistroorigen  desc ");

        echo $this->datatables->generate();
    }

    public function getDataR()
    {
        $this->load->library('Datatables');

        $this->db->simple_query('SET SESSION group_concat_max_len=15000');
        $this->datatables->select('
        subtotalventa,
        totalventa,
                 totalventaf,
                 fechaventa,
                 clientex,
                 igv,
                 nroboletafactura,
                 idventa,precioventa,precioventaf,idtipoventa,tipoventa ,pagoini,
                 tipocomprobante,  idboletafactura  ,deudacancelada,deudalabel ,idusuario ,vendedor,
                  isenviadosunat,
                 xmlfile,
                 msjsunat,
                 serie,
                 correlativo ,seriecorrelativo ,msjubl,ublvalido,respuestacdrfile,existcdr,codesunatrespuesta
                  ')->from('(                  
                  select *, 
(dventax.subtotalventa) +(dventax.subtotalventa*dventax.igv/100) as totalventa,
(dventax.subtotaventaf) +(dventax.subtotaventaf*dventax.igv/100) as totalventaf
from (SELECT
Sum(detventa.cantidad *detventa.precioventa) AS subtotalventa,
Sum(detventa.cantidad *detventa.precioventaf) AS subtotaventaf,
venta.fechaventa,
venta.deudacancelada,
if(COALESCE(venta.idtipoventa,0) = 1,"",if(COALESCE(venta.deudacancelada,0)=1,"Pagado","Deuda")) as deudalabel,
venta.pagoini,
cliente.nombre,
cliente.apellidos,
concat(cliente.nombre,\' \',cliente.apellidos ) as clientex,
venta.igv,
venta.idusuario,
venta.idventa,
if(venta.idtipoventa = 1,"Contado","Credito") as tipoventa,
venta.idtipoventa,
if(venta.idboletafactura = 1,"Ninguno",if(venta.idboletafactura = 2,"Boleta","Factura")) as tipocomprobante,
venta.idboletafactura,
venta.nroboletafactura,
detventa.precioventa,
detventa.precioventaf,
users.name as vendedor,
venta.isenviadosunat,
venta.xmlfile,
venta.msjsunat,
venta.serie,
venta.seriecorrelativo,
venta.correlativo,
venta.msjubl,
venta.ublvalido,
venta.respuestacdrfile,
venta.existcdr,
venta.codesunatrespuesta

FROM
venta
INNER JOIN cliente ON cliente.idcliente = venta.idcliente
inner join users on venta.idusuario=users.id
INNER JOIN detventa ON venta.idventa = detventa.idventa
INNER JOIN detalmacen ON detalmacen.idproducto = detventa.idproducto AND  detalmacen.lote = detventa.lote
where venta.estado>0 and detventa.estado>0 and detalmacen.estado>0 and (venta.idboletafactura =2 or venta.idboletafactura =3) 
GROUP BY venta.idventa order by venta.fechaventa desc) as dventax
       )temp ');

        echo $this->datatables->generate();
    }

    public function getDatosEmpresa($id)
    {
        $q = "select * from empresa where idempresa=$id";
        $r = $this->db->query($q)->result_array();
        return $r[0];
    }


    private function _generaFacturacion($id, $fini = null, $fend = null, $tipo = null)
    {
        return $this->getDataVenta($id, $fini, $fend, $tipo);
    }

    private function getDataVenta($idventa = null, $fini = null, $fend = null, $tipo = null)
    {
        $w = "";
        if ($fini != null && $fend != null) {
            $w .= " and  bandejafacturacion.fecharegistroorigen between '$fini' and '$fend' ";
        }
        if ($idventa != 0 and $tipo = "only") {
            $w .= " and  bandejafacturacion.idbandejafacturacion='$idventa' ";
        } else if ($idventa == 0 and $tipo = "all") {
            $w .= "and bandejafacturacion.seriecorrelativo<>'' and COALESCE(bandejafacturacion.isenviadosunat,0)=0
            and  COALESCE(bandejafacturacion.xmlfile,'') ='' ";
        }
// coregiri
        $q="select * from bandejafacturacion where estado>0 $w";
        $r = $this->db->query($q)->result_array();


       // return $r;
        $dt = [];
        foreach ($r as $k => $i) {
            $dtventx = $this->_detVentax($i["idbandejafacturacion"], $fini, $fend);
            $dt[] = array(
                "idbandejafacturacion"=>$i["idbandejafacturacion"],
                "idtipodoc"=>$i["idtipodoc"],
                "referencia"=>$i["referencia"],
                "idreferencia"=>$i["idreferencia"],
                "serie"=>$i["serie"],
                "correlativonro"=>$i["correlativonro"],
                "correlativo"=>$i["correlativo"],
                "seriecorrelativo"=>$i["seriecorrelativo"],
                //"isenviadosunat"=>
                //"codesunatrespuesta"=>
                //"xmlfile"=>
                // "msjsunat"=>
                //"ublvalido"=>
                // "msjubl"=>
                //"respuestacdrfile"=>
                // "existcdr"=>
                "numruc"=>$i["numruc"],
                // "nombrearchxml"=>
                // "resumenvalue"=>
                // "resumenfirma"=>
                "tipdocucliente"=>$i["tipdocucliente"],
                "numdocucliente"=>$i["numdocucliente"],
                "razoncliente"=>$i["razoncliente"],
                "direccioncliente"=>$i["direccioncliente"],
                // "xml_content"=>
                // "pdf_content"=>
                // "codsucursal"=>
                "gravado"=>0,
                "inafecto"=>0,
                "exonerado"=>$i["exonerado"],
                "suma_igv"=>$i["suma_igv"],
                "suma_descuento"=>$i["suma_descuento"],
                "descuento_global"=>$i["descuento_global"],
                "gratuito"=>$i["gratuito"],
                //"imagen_qr"=>
                // "html_content"=>
                "estado"=>$i["estado"],
                "fecharegistroorigen"=>$i["fecharegistroorigen"],
                //"fechageneraxml"=>
                // "fechaenvio"=>
                "fechareg"=>$i["fechareg"],
                "emailcliente"=>$i["emailcliente"],
                //"nroticket"=>
                //"fecharegistracdr"=>
                //"xmlgenerado"=>
                //"isdadobaja"=>
                "idempresa"=>$i["idempresa"],
                //"descripcion"=>$descItemCompro,
                //"unidadmedida"=>$unidadItemCompro,
                "moneda"=>$i["moneda"],
                "ubigeocli"=>$i["ubigeocli"],
                "departamentocli"=>$i["departamentocli"],
                "provinciacli"=>$i["provinciacli"],
                "distritocli"=>$i["distritocli"],
                "razonsocialempresa"=>$i["razonsocialempresa"],
                "ubigeoempresa"=>$i["ubigeoempresa"],
                "departamentoempresa"=>$i["departamentoempresa"],
                "provinciaempresa"=>$i["provinciaempresa"],
                "distritoempresa"=>$i["distritoempresa"],
                "direccionempresa"=>$i["direccionempresa"],
                "nombrecomercialempresa"=>$i["nombrecomercialempresa"],
                //cantPrecio
                // "cantidadprod"=>$cantItemCompro,
                //  "precioprod"=>$precioUItemCompro
                "detalle"=>$dtventx



            );
        }

        return $dt;
    }

    public function getDetalleVentaN()
    {
        $return["status"] = "fail";
        $post = $this->input->post(null, true);
        if (sizeof($post) > 0) {
            $return["data"] = $this->_detVentax($post["id"]);
            $return["status"] = "ok";
            echo json_encode($return);
        }

    }

    private function _detVentax($idventa = null, $fini = null, $fend = null)
    {
        $w = "";
        if ($fini != null && $fend != null) {
            $w .= " and  venta.fechaventa between '$fini' and '$fend' ";
        }
        if ($idventa) {
            $w .= " and  bandejafacturacion.idbandejafacturacion='$idventa' ";
        }
        $q="select
             detallebandejafacturacion.* 
           from 
            bandejafacturacion 
                inner join detallebandejafacturacion 
                    on bandejafacturacion.idbandejafacturacion= detallebandejafacturacion.idbandejafacturacion
           where bandejafacturacion.estado >0 and detallebandejafacturacion.estado>0
            $w
                ";
        $r = $this->db->query($q)->result_array();
        return $r;
    }


    private function _cuerpoComprobante($arrayVentas)
    {
       // print_r($arrayVentas);exit();
        //$dataEmpresa = $this->getDatosEmpresa(1);

        $dataEmpresa=$arrayVentas;
        $invoice = null;
        $venta = $arrayVentas;
        // Emisor
        $address = new Address();
        $address->setUbigueo($dataEmpresa["ubigeoempresa"])
            ->setDepartamento($dataEmpresa["departamentoempresa"])
            ->setProvincia($dataEmpresa["provinciaempresa"])
            ->setDistrito($dataEmpresa["distritoempresa"])
            ->setUrbanizacion('NONE')
            ->setDireccion($dataEmpresa["direccionempresa"]);

        $company = new Company();
        $company->setRuc($dataEmpresa["numruc"])
            ->setRazonSocial($dataEmpresa["razonsocialempresa"])
            ->setNombreComercial($dataEmpresa["nombrecomercialempresa"])
            ->setAddress($address);


        $montoTotalVenta = 0;
        $precioTotalVenta = 0;
        $impuesto = 0;
        $detItem = [];


        $isdniRUC = $venta["tipdocucliente"];
        $NumDoc = $venta["numdocucliente"];
        $razonCliente = $venta["razoncliente"];
        $dirD = $venta["direccioncliente"];
        $dirDSinesacio = preg_replace('/\s\s+/', ' ', $dirD);
        $emailventa = $venta["emailcliente"];

        $departamento = $venta["departamentocli"];
        $provincia = $venta["provinciacli"];
        $distrito = $venta["distritocli"];
        $ubigeo = $venta["ubigeocli"];


        //Clinte
        $client = new Client();
        $client->setTipoDoc($isdniRUC) // ruc // 1 dni
        ->setNumDoc($NumDoc)
            ->setRznSocial($razonCliente);

        if (isset($departamento) && isset($provincia) && isset($distrito) && isset($ubigeo) && strlen($dirDSinesacio) > 0) {
            $addressCli = new Address();
            $addressCli->setUbigueo($ubigeo)
                ->setDepartamento($departamento)
                ->setProvincia($provincia)
                ->setDistrito($distrito)
                ->setUrbanizacion('NONE')
                ->setDireccion($dirD);
        } else if (strlen($dirDSinesacio) > 0) {
            $addressCli = $dirD;
        } else {
            $addressCli = "";
        }
        $client->setAddress($addressCli);

        //var_dump($client);exit();



        $SumTotal = 0;
        $SumOperGravadas = 0;
        $SumOperInafectas = 0;
        $SumOperExoneradas = 0;
        $SumOperExportacion = 0;
        $SumOtrosCargos = 0;
        $SumMtoIGV = 0;


        $sumTotalValorVenta = 0;
        $sumTotalMontoVenta = 0;

        foreach ($venta["detalle"] as $detVenta) {
            $igvprod = 0;
            $igvprodTotal = 0;
            $cantidad = floatval($detVenta["cantidad"]);
            $valorunitario = $detVenta["precio"];
            $preciounitario = $detVenta["precio"];

            $preciounitarioIGV = floatval($preciounitario) + $igvprod;
            $montoventa = floatval($cantidad * $preciounitario);
            $PrecioVenta = floatval($cantidad * $preciounitarioIGV);

            $sumTotalValorVenta = $sumTotalValorVenta + $montoventa;

            $desc = $detVenta["descripcion"] ;
                $item = (new SaleDetail())
                    ->setCodProducto($detVenta["idproducto"])
                    ->setUnidad($detVenta["idunidadmedida"])
                    ->setCantidad($cantidad)
                    ->setDescripcion(trim($desc))
                    ->setMtoBaseIgv($montoventa) // monto base igv
                    ->setPorcentajeIgv(0) // 18%
                    ->setIgv($igvprodTotal)
                    ->setTipAfeIgv('20') // tipo afec igv exonerada
                    ->setTotalImpuestos($igvprodTotal) //
                    ->setMtoValorVenta($montoventa)// Monto Valor venta
                    ->setMtoValorUnitario($preciounitario) // sin igv  // Monto Valor Unitario
                    ->setMtoPrecioUnitario($preciounitarioIGV);// se incluye el ig // Monto Precio  Unitariov
                array_push($detItem, $item);

        }



        $SumOperExoneradas = $sumTotalValorVenta;





        $invoice = (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101') // Catalog. 51
            ->setTipoDoc($venta["idtipodoc"])
            ->setSerie($venta["serie"])
            ->setCorrelativo($venta["correlativo"])
            ->setFechaEmision(new DateTime($venta["fecharegistroorigen"]))
            ->setFecVencimiento(new DateTime($venta["fecharegistroorigen"]))
            ->setTipoMoneda($venta["moneda"])
            ->setClient($client)
            ->setMtoOperGravadas(0)
            ->setMtoOperExoneradas($sumTotalValorVenta)
            ->setMtoIGV(0)
            ->setTotalImpuestos(0)
            ->setValorVenta($sumTotalValorVenta)
            ->setSubTotal($sumTotalValorVenta)
            ->setMtoImpVenta($sumTotalValorVenta)
            ->setIcbper(0) // modifica
            ->setCompany($company);

        /*if ($venta["nroguia"]) {
            $invoice->setGuias([(new Document())
                ->setTipoDoc('09') //codigo  9 Guia
                ->setNroDoc(strtoupper($venta["nroguia"]))
            ]);
        } */
        /*   setDireccionEntrega((new Address())
               ->setUbigueo('150101')
               ->setDistrito('LIMA')
               ->setProvincia('LIMA')
               ->setDepartamento('LIMA')
               ->setUrbanizacion('ASINC')
               ->setDireccion('ALGARROBOS 1650')); */
        $currency="";
        if($venta["moneda"] =="PEN"){
            $currency="soles";
        }else if($venta["moneda"] == "USD"){
            $currency="dolares";
        }

        $numeroALetras = NumeroALetras::convert(floatval($sumTotalValorVenta), $currency);

        $legend = (new Legend())
            ->setCode('1000')
            ->setValue($numeroALetras);
        $invoice->setDetails($detItem)
            ->setLegends([$legend]);

        $dataDB = array(
            "SumTotal" => $SumOperExoneradas,
            "SumOperGravadas" => $SumOperGravadas,
            "SumOperInafectas" => $SumOperInafectas,
            "SumOperExoneradas" => $SumOperExoneradas,
            "SumOperExportacion" => $SumOperExportacion,
            "SumOtrosCargos" => $SumOtrosCargos,
            "SumMtoIGV" => $SumMtoIGV
        );

        return ["invoice" => $invoice, "dataTAX" => $dataDB];
    }

    /*public function reporteHtmlPDF(){
        $return["status"]="fail";
        $post=$this->input->post(null,true);
        if(sizeof($post)>0) {

                $util = \classutil\Util::getInstance();

                $idventa = $post["id"];
                $dataVenta=$this->_generaFacturacion($idventa,null,null,null);
                foreach($dataVenta as $venta){
                    $invoice=$this->_cuerpoComprobante($venta);

                    // Guardar XML
                    $see = $util->getSee(SunatEndpoints::FE_PRODUCCION);
                    file_put_contents("filesfact/comprobantes/".$invoice->getName().'.xml',
                        $see->getFactory()->getLastXml());


                   // $reportHtml=$this->isReportHTMLPDF($util,$invoice,'pdf');
                   // echo $reportHtml;
                }

        }

    } */

    private function isReportHTMLPDF($obConfig,$util, $invoice, $isHtmlOrpdf, $tipodocumento)
    {

        $utilx =$util;

        if ($isHtmlOrpdf == "html") {
            $report = new HtmlReport();
            if ($tipodocumento == "ventafactura") {
                $report->setTemplate('invoice.html.twig');
            } else if ($tipodocumento == "resumendiario") {
                $report->setTemplate('summary.html.twig');
            } else if ($tipodocumento == "combaja") {
                $report->setTemplate('voided.html.twig');
            } else if ($tipodocumento == "notacredito") {
                $report->setTemplate('invoice.html.twig');
            }
            $see = $utilx->getSee($obConfig,$this->endPoint);
            $xml = $see->getXmlSigned($invoice);
            $hash = (new XmlUtils())->getHashSign($xml);
            $html = $report->render($invoice, [
                'system' => [
                    'logo' => base_url().'assets/images/ico.png',
                    'hash' => $hash,
                ],
                'user' => [
                    'header' => 'Telf/Cel: <b>974 719 882</b>',
                    'resolucion' => '212321',
                ]
            ]);
            $response = $html;
        } else if ($isHtmlOrpdf == "pdf") {
            try {
                $pdf = $util->getPdf($invoice);
                $util->showPdf($pdf, $invoice->getName() . '.pdf');
            } catch (Exception $e) {
                var_dump($e);
            }
            $response = "";
        }
        return $response;
    }

    private function getListPorGenerarFacturacion($idreferencia = null)
    {
        $w = "";
        if ($idreferencia != 0) {
            $w = "and bandejafacturacion.idbandejafacturacion=$idreferencia";
        }
        $q = "SELECT
	bandejafacturacion.idbandejafacturacion,
	bandejafacturacion.idtipodoc,
	bandejafacturacion.idreferencia,
	bandejafacturacion.referencia,
       	bandejafacturacion.idempresa
FROM
	bandejafacturacion
WHERE
	bandejafacturacion.estado > 0 AND COALESCE(bandejafacturacion.seriecorrelativo,'') <> ''
AND COALESCE (bandejafacturacion.isenviadosunat,0) = 0 
AND COALESCE (bandejafacturacion.xmlfile,	'') = ''
and bandejafacturacion.referencia='venta'
$w
 order by bandejafacturacion.fecharegistroorigen asc";
        $r = $this->db->query($q)->result_array();
        return $r;
    }


    public function generaValidadXml()
    {
        $return["status"] = "ok";
        $post = $this->input->post(null, true);
        $idventa = 0;
        $tipo = 'all';
        $referencia = 'venta';
        $idreferencia = 0;
        $fecha = date("Y-m-d");
        if (sizeof($post) > 0) {
            $idbandejafact = $post["id"];
            $tipo = $post["tipo"];
            $referencia = $post["referencia"];
            $idreferencia = $post["idreferencia"];
            $fecha = $post["fechaorigen"];

        }

        if ($referencia == 'venta') {
            $r = $this->getListPorGenerarFacturacion($idreferencia);
            //print_r($r);exit();
            foreach ($r as $k => $i) {
                if ($i["referencia"] == "venta") {
                    $this->_generaValidadXmlSales($i["idempresa"],$i["idbandejafacturacion"], $i["idreferencia"], null, null, "only");
                }
            }
            $return["data"] = ["status" => "ok"];
        } else if ($referencia == 'resumendiario') {
            $r = $this->generaXMLValidadXMLSUMMARY($idreferencia, $fecha);
            $return["data"] = $r;
        } else if ($referencia == 'combaja') {
            $return["data"] = $this->generaXMLValidaComBaja($idbandejafact, $idreferencia);
        } else if ($referencia == 'notacredito') {
            $return["data"] = $this->generaXmlValidaNotas($idbandejafact, $idreferencia);
        }

        echo json_encode($return);
    }

    private function _generaValidadXmlSales($idempresa,$idfact, $idref, $fini = null, $fend = null, $tipo)
    {
      //  $idventa = $idref;
        $idventa=$idfact;
        $obConfig=$this->getSeeConfigFact($idempresa);
        $obUtil=new Util($obConfig);
        $utilx =$obUtil::getInstance();

       // $utilx = $this->utilx;

        $dataVenta = $this->_generaFacturacion($idventa, null, null, $tipo);


        foreach ($dataVenta as $venta) {
            $invoiceG = $this->_cuerpoComprobante($venta);
            $invoice = $invoiceG["invoice"];
            $see = $utilx->getSee($obConfig,$this->endPoint);
            $xml = $see->getXmlSigned($invoice);
            file_put_contents("filesfact/comprobantes/" . $invoice->getName() . '.xml', $xml);
            $exist = file_exists('filesfact/comprobantes/' . $invoice->getName() . '.xml');
            $dataRESunat["xmlfile"] = "ARchivo no encontrado";
            $dataRESunat["xmlgenerado"] = "0";
            if ($exist) {
                $dataRESunat["xmlgenerado"] = "1";
                $dataRESunat["xmlfile"] = $invoice->getName() . '.xml';
                //ValidaUBL
                $returnUBL = $this->ublValidator($invoice->getName() . '.xml');
                if ($returnUBL["status"] == "ok") {
                    $dataTAX = $invoiceG["dataTAX"];
                    ///SumTotal SumOperExportacion SumOtrosCargos

                    $dataRESunat["gravado"] = $dataTAX["SumOperGravadas"];
                    $dataRESunat["inafecto"] = $dataTAX["SumOperInafectas"];
                    $dataRESunat["exonerado"] = $dataTAX["SumOperExoneradas"];
                    $dataRESunat["gratuito"] = 0;
                    $dataRESunat["suma_igv"] = $dataTAX["SumMtoIGV"];
                    $dataRESunat["suma_descuento"] = 0;
                    $dataRESunat["descuento_global"] = 0;

                    $dataRESunat["ublvalido"] = '1';
                    $dataRESunat["msjubl"] = $returnUBL["msj"];
                    $dataRESunat["fechageneraxml"] = date("Y-m-d H:m:s");

                } else {
                    $dataRESunat["ublvalido"] = '0';
                    $dataRESunat["msjubl"] = $returnUBL["msj"];
                }
            }
            // $this->db->update('venta',$dataRESunat, ["idventa"=>$idref]);
          //  $this->db->update('venta', ["xmlgenerado" => $dataRESunat["xmlgenerado"]], ["idventa" => $idref]);
            $this->db->update('bandejafacturacion', $dataRESunat, ["idbandejafacturacion" => $idfact]);
        }
    }


    // validador UBL
    public function ublValidator($xmlname)
    {
        $exist = file_exists('filesfact/comprobantes/' . $xmlname);
        $return["status"] = "fail";
        if ($exist) {
            $xml = file_get_contents('filesfact/comprobantes/' . $xmlname, false);
            $validator = new UblValidator();
            $return["msj"] = "";
            if ($validator->isValid($xml)) {
                $return["msj"] = 'correcto';
                $return["status"] = "ok";
            } else {
                ob_start();
                var_dump($validator->getError());
                $resultxxx = ob_get_clean();
                $return["msj"] = $resultxxx;
            }
        } else {
            $return["msj"] = 'xml no se encuentra en la ubicacion';
        }
        return $return;
    }

    public function reporteFactHtmlPDF($idempresa=null,$ref = null, $idref = null, $idbandejafact = null, $forigen = null)
    {

        $obConfig=$this->getSeeConfigFact($idempresa);
        $obUtil=new Util($obConfig);
        $util =$obUtil::getInstance();


        if ($ref == "venta") {
            $tipodoc = "ventafactura";
            $idventa = $idref;
            $dataVenta = $this->_generaFacturacion($idventa, null, null, "only");
            foreach ($dataVenta as $venta) {
                $invoice = $this->_cuerpoComprobante($venta);
                $reportHtml = $this->isReportHTMLPDF($obConfig,$util, $invoice["invoice"], 'html', $tipodoc);//pdf
                echo $reportHtml;
            }
        } else if ($ref == "resumendiario") {
            $tipodoc = "resumendiario";

            $dataSUM = $this->validaSiYaExisteFechaInResumen("", $idref);
            if (sizeof($dataSUM) == 1) {

                $fechaDocs = $dataSUM[0]["fechadocinresumen"];
                $fechaResumen = $dataSUM[0]["fecharesumen"];

                $dt = $this->getDetalleResumen($idref, $fechaDocs);
                $cuerpoSummary = $this->cuerpoResumenDiario($dt, $fechaResumen, $fechaDocs);
                $reportHtml = $this->isReportHTMLPDF($util, $cuerpoSummary, 'html', $tipodoc);
                echo $reportHtml;
            } else {
                echo "Error";
            }

        } else if ($ref == "combaja") {
            $tipodoc = $ref;
            $cuerpo = $this->_bodyCerpoBaja($idref);
            $reportHtml = $this->isReportHTMLPDF($util, $cuerpo, 'html', $tipodoc);
            echo $reportHtml;
        } else if ($ref == "notacredito") {
            $tipodoc = $ref;
            $cuerpo = $this->_bodyCuerpoNotas($idref);
            $reportHtml = $this->isReportHTMLPDF($util, $cuerpo, 'html', $tipodoc);
            echo $reportHtml;
        }
    }

    public function enviaFacturacion()
    {
        $return["status"] = "fail";
        $post = $this->input->post(null, true);
        if (sizeof($post) > 0) {
            $idbfact = $post["idbandejafact"];
            $idref = $post["idreferencia"];
            $ref = $post["referencia"];
            $xml = $post["xmlf"];
            $idempresa = $post["idempresa"];
            $r = $this->_sendAllXML($idempresa,$idbfact, $ref, $idref, $xml);
            $return["status"] = "ok";
            $return["d"] = $r;
        }
        echo json_encode($return);

    }

    public function sendAllXML()
    {

        $qq2 = "SELECT
	bandejafacturacion.idbandejafacturacion,
	bandejafacturacion.idtipodoc,
	bandejafacturacion.idreferencia,
	bandejafacturacion.referencia,
	 bandejafacturacion.xmlfile,
        bandejafacturacion.idempresa
FROM
	bandejafacturacion
WHERE
 bandejafacturacion.estado>0 and bandejafacturacion.seriecorrelativo <>'' and COALESCE(bandejafacturacion.isenviadosunat,0)=0  
						and  COALESCE(bandejafacturacion.xmlfile,'') <>'' and bandejafacturacion.ublvalido=1";
        $r = $this->db->query($qq2)->result_array();
        $rrr = [];
        foreach ($r as $xmls) {
            $rs = $this->_sendAllXML($xmls["idempresa"],$xmls["idbandejafacturacion"], $xmls["referencia"], $xmls["idreferencia"], $xmls["xmlfile"]);
            $rrr[] = $rs;
            sleep(0.5);
        }
        echo json_encode(["status" => "ok", "dd" => $rrr]);
    }

    private function _sendAllXML($idempresa,$idbfact, $ref, $idref, $xmlfile)
    {
        $obConfig=$this->getSeeConfigFact($idempresa);
        $obUtil=new Util($obConfig);
        $utilx =$obUtil::getInstance();
       // $utilx = $this->utilx;
        $exist = file_exists('filesfact/comprobantes/' . $xmlfile);
        $return["status"] = "fail";
        if ($exist) {
            $return["status"] = "ddd";
            $xmlname = substr($xmlfile, 0, -4);

            $see = $utilx->getSee($obConfig,$this->endPoint);
            $XML = file_get_contents('filesfact/comprobantes/' . $xmlfile);
            $res = $see->sendXmlFile($XML);

            if (!$res->isSuccess()) {
                ob_start();
                var_dump($res->getError());
                $resultxxx = ob_get_clean();
                $dataRESunat["msjsunat"] = $resultxxx;
                $return["statusSunatx"] = $resultxxx;
                $return["statusSunat"] = "fail";
                $return["status"] = "ok";
                // $this->db->update('venta',$dataRESunat, ["idventa"=>$idref]);
                $this->db->update('bandejafacturacion', $dataRESunat, ["idbandejafacturacion" => $idbfact]);
            } else {
                if ($ref == "resumendiario") {
                    $ticket = $res->getTicket();
                    $this->db->update('bandejafacturacion', ["nroticket" => $ticket], ["idbandejafacturacion" => $idbfact]);
                    $rSt = $this->getStatusResumenDiarioCombaja($idbfact, $ticket, $xmlfile);
                    $return["data"] = $rSt;
                    $return["status"] = $rSt;

                } else if ($ref == "venta") {
                    $dataRESunat["msjsunat"] = $res->getCdrResponse()->getDescription() . PHP_EOL;
                    $dataRESunat["codesunatrespuesta"] = $res->getCdrResponse()->getCode() . PHP_EOL;
                    $return["statusSunat"] = "ok";
                    $dataRESunat["isenviadosunat"] = "1";
                    file_put_contents('filesfact/comprobantes/R-' . $xmlname . '.zip', $res->getCdrZip());

                    $existCDR = file_exists('filesfact/comprobantes/R-' . $xmlname . '.zip');
                    if ($existCDR) {
                        $dataRESunat["fechaenvio"] = date("Y-m-d H:i:s");
                        $dataRESunat["respuestacdrfile"] = 'R-' . $xmlname . '.zip';
                        $dataRESunat["existcdr"] = "1";
                        $dataRESunat["fecharegistracdr"] = date("Y-m-d H:m:s");
                    } else {
                        $dataRESunat["existcdr"] = "0";
                    }

                    $return["status"] = "ok";
                   // $this->db->update('venta', ["existcdr" => $dataRESunat["existcdr"], "isenviadosunat" => $dataRESunat["isenviadosunat"]], ["idventa" => $idref]);
                    $this->db->update('bandejafacturacion', $dataRESunat, ["idbandejafacturacion" => $idbfact]);

                } else if ($ref == "combaja") {
                    $ticket = $res->getTicket();
                    $this->db->update('bandejafacturacion', ["nroticket" => $ticket], ["idbandejafacturacion" => $idbfact]);
                    $rSt = $this->getStatusResumenDiarioCombaja($idbfact, $ticket, $xmlfile);
                    $return["data"] = $rSt;
                    $return["status"] = "ok";
                } else if ($ref == "notacredito") {
                    $dataRESunat["msjsunat"] = $res->getCdrResponse()->getDescription() . PHP_EOL;
                    $dataRESunat["codesunatrespuesta"] = $res->getCdrResponse()->getCode() . PHP_EOL;
                    $return["statusSunat"] = "ok";
                    $dataRESunat["isenviadosunat"] = "1";
                    file_put_contents('filesfact/comprobantes/R-' . $xmlname . '.zip', $res->getCdrZip());
                    $existCDR = file_exists('filesfact/comprobantes/R-' . $xmlname . '.zip');
                    if ($existCDR) {
                        $dataRESunat["fechaenvio"] = date("Y-m-d H:i:s");
                        $dataRESunat["respuestacdrfile"] = 'R-' . $xmlname . '.zip';
                        $dataRESunat["existcdr"] = "1";
                        $dataRESunat["fecharegistracdr"] = date("Y-m-d H:m:s");
                    } else {
                        $dataRESunat["existcdr"] = "0";
                    }
                    $return["status"] = "ok";
                    $this->db->update('bandejafacturacion', $dataRESunat, ["idbandejafacturacion" => $idbfact]);
                }

            }
        }
        return $return;
    }

    public function getStatusResumenDiarioCombaja($idbfact, $ticket, $xmlfile)
    {
        $utilx = $this->utilx;
        $see = $utilx->getSee($this->endPoint);
        $res = $see->getStatus($ticket);
        $xmlname = substr($xmlfile, 0, -4);
        if (!$res->isSuccess()) {
            ob_start();
            var_dump($res->getError());
            $resultxxx = ob_get_clean();
            $dataRESunat["msjsunat"] = $resultxxx;
            $return["statusSunat"] = "Fail";
            $this->db->update('bandejafacturacion', $dataRESunat, ["idbandejafacturacion" => $idbfact]);
        } else {
            //$cdr = $res->getCdrResponse()->getCode().PHP_EOL;;
            $dataRESunat["msjsunat"] = $res->getCdrResponse()->getDescription() . PHP_EOL;
            $dataRESunat["codesunatrespuesta"] = $res->getCdrResponse()->getCode() . PHP_EOL;
            $return["statusSunat"] = "ok";
            $dataRESunat["isenviadosunat"] = "1";
            file_put_contents('filesfact/comprobantes/R-' . $xmlname . '.zip', $res->getCdrZip());
            $existCDR = file_exists('filesfact/comprobantes/R-' . $xmlname . '.zip');
            if ($existCDR) {
                $dataRESunat["fechaenvio"] = date("Y-m-d H:i:s");
                $dataRESunat["respuestacdrfile"] = 'R-' . $xmlname . '.zip';
                $dataRESunat["existcdr"] = "1";
                $dataRESunat["fecharegistracdr"] = date("Y-m-d H:m:s");
            } else {
                $dataRESunat["existcdr"] = "0";
            }
            $this->db->update('bandejafacturacion', $dataRESunat, ["idbandejafacturacion" => $idbfact]);
        }
        return $return;
    }

    public function getListFilesDirFact()
    {
        $return["status"] = "fail";
        $post = $this->input->post(null, true);
        if (sizeof($post) > 0) {
            $URLxx = realpath(dirname(__DIR__) . '/..');
            $directorio = $URLxx . '/filesfact/comprobantes';
            $ficheros1 = scandir($directorio);
            $dtFicha = [];
            foreach ($ficheros1 as $f) {
                if ($f != "." && $f != "..") {
                    $fecha = date("Y-m-d H:m:s", filectime($directorio . "/" . $f));
                    $filesize = filesize($directorio . "/" . $f);
                    $dtFicha[] = array(
                        "filename" => $f,
                        "ultimamodificacion" => $fecha,
                        "size" => floatval($filesize) / 1024
                    );
                }
            }
            $return["data"] = $dtFicha;
            $return["status"] = "ok";
        }
        echo json_encode($return);
    }


    public function resetearXmlSunat()
    {
        $return["status"] = "fail";
        $post = $this->input->post(null, true);
        if (sizeof($post) > 0) {
            $idbfact = $post["idbf"];
            $dataRESunat["msjsunat"] = "";
            $dataRESunat["existcdr"] = "0";
            $dataRESunat["fechaenvio"] = null;
            $dataRESunat["respuestacdrfile"] = '';
            $dataRESunat["codesunatrespuesta"] = "";
            $dataRESunat["isenviadosunat"] = "0";
            $dataRESunat["xmlfile"] = "";
            $dataRESunat["ublvalido"] = '0';
            $dataRESunat["msjubl"] = "";
            $dataRESunat["fechageneraxml"] = null;
            $dataRESunat["nroticket"] = null;
            $dataRESunat["fecharegistracdr"] = null;
            $dataRESunat["xmlgenerado"] = null;

            $this->db->update('bandejafacturacion', $dataRESunat, ["idbandejafacturacion" => $idbfact]);
            $return["status"] = "ok";
        }
        echo json_encode($return);
    }


    /// RESUMEN DIARIO  

    public function generaResumenDiario()
    {
        $correlativo = $this->getCorrelativoResumenDiario();
        $fecharesumenUltima = $this->getUltimaFechaEnviada();
        $isForEject = 1;

        $days = "- 0 days";
        $fechaResumen = date("Y-m-d H:m:s"); // fecha genracion del resumen
        $fechadocsbol = date("Y-m-d", strtotime(date("Y-m-d") . $days));  // fecha de los documentos -boletas notas


        /*if($fecharesumenUltima==0){
            $fechaResumen=date("Y-m-d H:m:s");
        }else{
            $fecharesumenCREAR=date("d-m-Y",strtotime($fecharesumenUltima."+ 1 days"));
            $fechaActual=date("Y-m-d");
            if($fecharesumenCREAR == $fechaActual ){
                $fechaResumen=$fecharesumenUltima;
            }else{
                $fechaResumen=$fechaActual;
            }
        }*/
        $existBoletas = $this->existBoletasInDayForResumen($fechadocsbol);
        if ($existBoletas > 0) {
            $yaExisteFecha = $this->validaSiYaExisteFechaInResumen($fechaResumen);
            if (sizeof($yaExisteFecha) == 0) {
                $dtResumen = array(
                    "correlativo" => $correlativo,
                    "idempresa" => 1,
                    "idsucursal" => 1,
                    "fecharesumen" => $fechaResumen,
                    "fechadocinresumen" => $fechadocsbol,
                    "estado" => 1,
                    "fechareg" => date("Y-m-d H:m:s")
                );
                $this->db->trans_start();
                $this->db->insert("resumendiario", $dtResumen);
                $idRES = $this->db->insert_id();
                $this->db->trans_complete();


                $dataInsBandeja = array(
                    "idtipodoc" => "RC",
                    "referencia" => "resumendiario",
                    "idreferencia" => $idRES,
                    "serie" => "RC01",
                    "correlativonro" => $correlativo,
                    'seriecorrelativo' => $correlativo,
                    "isenviadosunat" => 0,
                    "fecharegistroorigen" => $fechaResumen,
                    "fechareg" => date("Y-m-d H:m:s"),
                    "estado" => 1
                );
                //echo $idMax;
                $this->db->trans_start();
                $this->db->insert("bandejafacturacion", $dataInsBandeja);
                $idX = $this->db->insert_id();
                $this->db->trans_complete();
                $this->generaXMLValidadXMLSUMMARY($idRES, $fechaResumen, $fechadocsbol);
            } else {
                $this->generaXMLValidadXMLSUMMARY($yaExisteFecha[0]["idresumendiario"], $yaExisteFecha[0]["fecharesumen"], $yaExisteFecha[0]["fechadocinresumen"]);

            }
            $return["estado"] = "ok";
        } else {
            $return["estado"] = "No existen boletas en el día";
        }
        echo json_encode($return);
    }

    private function existBoletasInDayForResumen($fecha)
    {
        $q = "SELECT
        count(bandejafacturacion.idbandejafacturacion) as cant
        FROM
        bandejafacturacion
        where date(bandejafacturacion.fecharegistroorigen) ='$fecha' 
        and bandejafacturacion.idtipodoc in('03','07','08') and bandejafacturacion.estado>0 ";
        $r = $this->db->query($q)->result_array();
        return $r[0]["cant"];
    }

    private function validaSiYaExisteFechaInResumen($f = null, $id = null)
    {
        $w = "";
        if ($f) {
            $f = substr($f, 0, 10);
            $w = " and date(resumendiario.fecharesumen)='$f'";
        }
        if ($id) {
            $w = " and resumendiario.idresumendiario =$id ";
        }

        $q = "SELECT
        resumendiario.idresumendiario,  
        resumendiario.fechadocinresumen,
        resumendiario.fecharesumen
        FROM  
        resumendiario 
        where resumendiario.estado>0  $w  ";
        $r = $this->db->query($q)->result_array();
        return $r;
    }

    public function generaXMLValidadXMLSUMMARY($idRES = null, $fechares = null, $fechadocs = null)
    {
        $dataRESUMEN = $this->getDetalleResumen($idRES, $fechadocs);
        //print_r($dataRESUMEN);exit();
        $this->_generaXmlResumenDiario($dataRESUMEN, $fechares, $fechadocs);
        return ["estado" => "ok"];
    }

    private function _generaXmlResumenDiario($detResumen, $fechares, $fechadocs)
    {
        $utilx = $this->utilx;
        $invoiceSUM = $this->cuerpoResumenDiario($detResumen, $fechares, $fechadocs);
        //print_r($invoiceSUM);exit();
        $see = $utilx->getSee($this->endPoint);
        $xml = $see->getXmlSigned($invoiceSUM);
        file_put_contents("filesfact/comprobantes/" . $invoiceSUM->getName() . '.xml', $xml);
        $exist = file_exists('filesfact/comprobantes/' . $invoiceSUM->getName() . '.xml');
        $dataRESunat["xmlfile"] = "ARchivo no encontrado";
        $dataRESunat["xmlgenerado"] = "0";
        if ($exist) {
            $dataRESunat["xmlfile"] = $invoiceSUM->getName() . '.xml';
            $dataRESunat["xmlgenerado"] = "1";
            //ValidaUBL
            $returnUBL = $this->ublValidator($invoiceSUM->getName() . '.xml');
            if ($returnUBL["status"] == "ok") {
                $dataRESunat["ublvalido"] = '1';
                $dataRESunat["msjubl"] = $returnUBL["msj"];
                $dataRESunat["fechageneraxml"] = date("Y-m-d H:m:s");

            } else {
                $dataRESunat["ublvalido"] = '0';
                $dataRESunat["msjubl"] = $returnUBL["msj"];
            }
            //
            $this->db->update('bandejafacturacion', $dataRESunat, ["referencia" => "resumendiario", "idreferencia" => $detResumen[0]["idresumendiario"]]);
        }
    }


    private function getDataResumenFechaById($id)
    {
        $q = "SELECT
            resumendiario.fecharesumen,
            resumendiario.fechadocinresumen,
               resumendiario.correlativo
            FROM
            resumendiario
            where resumendiario.idresumendiario=$id 
            and resumendiario.estado>0";
        $r = $this->db->query($q)->result_array();
        if (sizeof($r) > 0) {
            $return["status"] = "ok";
            $return["data"] = $r[0];
        } else {
            $return["status"] = "fail";
        }
        return $return;

    }


    private function getDetalleResumen($idres = null, $fecha = null){
        $w = "";
        $f1 = "";
        $f2 = "";
        $dataResumenFechas = $this->getDataResumenFechaById($idres);
        if ($dataResumenFechas["status"] == "ok") {
            $fechaR = $dataResumenFechas["data"]["fecharesumen"];
            $ff = substr($fechaR, 0, 10);
            $f1 = "and date(detcombajasnotas.fechadocbaja)='$ff'";
            $f2 = "and date(bandejafacturacion.fecharegistroorigen)='$ff'";

            $q = "SELECT
                0 as gravado,
                0 as inafecto,
                Sum(if( coalesce(detventa.precioventaf,0)=0,detventa.precioventa,detventa.precioventaf) * detventa.cantbolfact) AS exonerado, 
                0 as suma_igv,
                0 as gratuito,
                0 as suma_descuento,
                0 as descuento_global,
                bandejafacturacion.idbandejafacturacion,  
                bandejafacturacion.idtipodoc,
                bandejafacturacion.serie,
                bandejafacturacion.correlativo as bcorrelativo,
                bandejafacturacion.seriecorrelativo,
                bandejafacturacion.numdocucliente,
                bandejafacturacion.tipdocucliente,
                bandejafacturacion.direccioncliente,
                bandejafacturacion.razoncliente,
                bandejafacturacion.emailcliente, 
                detcombajasnotas.idresumen as idresumendiario, 
                '$correlativo' as correlativo,
                detcombajasnotas.fechadocbaja as fecharesumen,
                
                1 as  isdadobaja  
                FROM
                venta
                INNER JOIN bandejafacturacion ON venta.idventa = bandejafacturacion.idreferencia and 'venta'=bandejafacturacion.referencia
                inner join detcombajasnotas on venta.idventa=detcombajasnotas.idreferenciaboleta
                INNER JOIN detventa ON detventa.idventa = venta.idventa
                INNER JOIN producto ON detventa.idproducto = producto.idproducto
                where venta.estado>0 and detventa.estado>0  $f1  
                group by venta.idventa
                union 
                SELECT
                bandejafacturacion.gravado,
                bandejafacturacion.inafecto,
                bandejafacturacion.exonerado,
                bandejafacturacion.suma_igv,
                bandejafacturacion.gratuito,
                bandejafacturacion.suma_descuento,
                bandejafacturacion.descuento_global,
                bandejafacturacion.idbandejafacturacion,    
                bandejafacturacion.idtipodoc,
                bandejafacturacion.serie,
                bandejafacturacion.correlativo as bcorrelativo,
                bandejafacturacion.seriecorrelativo,
                bandejafacturacion.numdocucliente,
                bandejafacturacion.tipdocucliente,
                bandejafacturacion.direccioncliente,
                bandejafacturacion.razoncliente,
                bandejafacturacion.emailcliente,    
                1 as idresumendiario,
                 '$correlativo' as correlativo,
                '$fechaR' as fecharesumen, 
                0 as isdadobaja
                FROM
                bandejafacturacion 
                where bandejafacturacion.idtipodoc in ('07','08') 
                and bandejafacturacion.estado>0  $f2
                and bandejafacturacion.isenviadosunat = 0
                and ISNULL(bandejafacturacion.existcdr)
                   and SUBSTR(bandejafacturacion.serie, 1 ,2)!='FC'
                 and SUBSTR(bandejafacturacion.serie, 1 ,2)!='FD' 
                ";

            //no Se enviara las boletas por resumen 03;
            $r = $this->db->query($q)->result_array();
            return $r;

        } else {
            echo "error";
            exit();
        }

    }


    private function getUltimaFechaEnviada()
    {
        $q = "SELECT        
        COALESCE(MAX(DATE_FORMAT(resumendiario.fecharesumen, '%Y-%m-%d')) , 0) AS f
        FROM
        resumendiario
        where resumendiario.estado>0";
        $r = $this->db->query($q)->result_array();
        return $r[0]["f"];
    }

    private function getCorrelativoResumenDiario()
    {
        $q = "SELECT
        COALESCE(MAX(resumendiario.correlativo),0)+1 as c
        FROM
        resumendiario
        where resumendiario.estado>0 ";
        $r = $this->db->query($q)->result_array();
        return $r[0]["c"];
    }

    public function generaXMLResumenDiario()
    {
        $return["status"] = "fail";
        $post = $this->input->post(null, true);
        if (sizeof($post) > 0) {


        }
        echo json_encode(null);
    }

    private function cuerpoResumenDiario($dataResumen, $fechares, $fechadocs)
    {
        //print_r($dataResumen);exit();
        $dataEmpresa = $this->getDatosEmpresa(1);

        // Emisor
        $address = new Address();
        $address->setUbigueo($dataEmpresa["ubigeo"])
            ->setDepartamento($dataEmpresa["departamento"])
            ->setProvincia($dataEmpresa["provincia"])
            ->setDistrito($dataEmpresa["distrito"])
            ->setUrbanizacion('NONE')
            ->setDireccion($dataEmpresa["direccion"]);

        $company = new Company();
        $company->setRuc($dataEmpresa["ruc"])
            ->setRazonSocial($dataEmpresa["razonsocial"])
            ->setNombreComercial($dataEmpresa["nombrecomercial"])
            ->setAddress($address);
        $arrDetalleRe = [];
        $correlativoSumaary = "";
        foreach ($dataResumen as $dtres) {
            $estado = 1;
            if ($dtres["isdadobaja"] == 1) {
                $estado = 3;
            }
            $correlativoSumaary = $dtres["correlativo"];
            $fecharesumenx = $dtres["fecharesumen"];

            $detiail1 = new SummaryDetail();
            $detiail1->setTipoDoc($dtres["idtipodoc"])
                ->setSerieNro($dtres["seriecorrelativo"])
                ->setEstado($estado)// 1adicionar 2modifica 3 anula
                ->setClienteTipo($dtres["tipdocucliente"])
                ->setClienteNro($dtres["numdocucliente"])
                ->setTotal(floatval($dtres["exonerado"]))
                ->setMtoOperGravadas(floatval($dtres["gravado"]))
                ->setMtoOperInafectas(floatval($dtres["inafecto"]))
                ->setMtoOperExoneradas(floatval($dtres["exonerado"]))
                ->setMtoOperExportacion(0)
                ->setMtoOtrosCargos(0)
                ->setMtoIGV(floatval($dtres["suma_igv"]));

            array_push($arrDetalleRe, $detiail1);
        }


        $sum = new Summary();
        $sum->setFecGeneracion(new DateTime($fechadocs))
            ->setFecResumen(new DateTime($fechares))
            ->setCorrelativo($correlativoSumaary)
            ->setCompany($company)
            ->setDetails($arrDetalleRe);
        return $sum;
    }

    //End REsumen diario


    //Obtener Datos del XML

    public function getDataOfXML($file=null)
    {

        $file="10709295760-03-B001-00000119.xml";

        ///----------------
        $nsXmlsInvo="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2";
        $nsCac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2";
        $nsCbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2";
        $nsExt="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2";
        $nsDs="http://www.w3.org/2000/09/xmldsig#";

        $FileXmlContent = file_get_contents("filesfact/comprobantes/".$file);
        $XmlContent = new SimpleXmlElement($FileXmlContent);

        $GInvoice=$XmlContent->children($nsCbc); // datos invoice
        $GCACInvoice=$XmlContent->children($nsCac); // daots empresa x

        $UblVersion=$GInvoice->UBLVersionID; //Version UBL
        $CustomizationID=$GInvoice->CustomizationID;
        $ID=$GInvoice->ID;// serie-correlativo
        $IssueDate=$GInvoice->IssueDate; //fecha asunto
        $IssueTime=$GInvoice->IssueTime; //Time Asunto
        $DueDate=$GInvoice->DueDate; // Fecha vence

        $InvoiceTypeCode=$GInvoice->InvoiceTypeCode; // Tipo doc Bol Fact;
        $attrInvoiceTypeCode=$InvoiceTypeCode->attributes();
        $TypeCodelistID=$attrInvoiceTypeCode["listID"];  // Código de tipo de factura

        $DocumentCurrencyCode=$GInvoice->DocumentCurrencyCode; // Moneda;

        //text;
        $NotaTextoTotales=$GInvoice->Note; // texto soles Dolares
        $attrNotaTextoTotales=$NotaTextoTotales->attributes();
        $languageLocaleID=$attrNotaTextoTotales["languageLocaleID"]; // Códigos de leyendas

        //text;

        //-->AccountingSupplierParty Emisor
            $A_GCACInvoice=$GCACInvoice->AccountingSupplierParty;
            $ObParty=$A_GCACInvoice->children($nsCac);
            $Party=$ObParty->Party;
            $ObChildParty=$Party->children($nsCac);

            $PartyIdentification=$ObChildParty->PartyIdentification;
            $ObID=$PartyIdentification->children($nsCbc);
            $RucIDObId=$ObID->ID; // RUC Empresa;
            //AtributeID
            $attrIDObId=$RucIDObId->attributes();
            $schemaId=$attrIDObId["schemeID"]; //id ruc sunat

            $PartyName=$ObChildParty->PartyName;
            $obNamePartyName=$PartyName->children($nsCbc);
            $PartyNameName=$obNamePartyName->Name;// nombre comercial

            $PartyLegalEntity=$ObChildParty->PartyLegalEntity;
            $obCbcPartyLegalEntity=$PartyLegalEntity->children($nsCbc);
            $RegistrationName=$obCbcPartyLegalEntity->RegistrationName; // razon social

            $obCacPartyLegalEntity=$PartyLegalEntity->children($nsCac);
            $RegistrationAddress=$obCacPartyLegalEntity->RegistrationAddress;
            $obRegistrationAddress=$RegistrationAddress->children($nsCbc);

            $RegAddID=$obRegistrationAddress->ID; // Ubigeo
            $RegAddAddressTypeCode=$obRegistrationAddress->AddressTypeCode;
            $RegAddCitySubdivisionName =$obRegistrationAddress->CitySubdivisionName;
            $RegAddCityName =$obRegistrationAddress->CityName;
            $RegAddCountrySubentity =$obRegistrationAddress->CountrySubentity;
            $RegAddDistrict =$obRegistrationAddress->District;

            $obRegistrationAddressCAC=$RegistrationAddress->children($nsCac);
            $RegAddAddressLine =$obRegistrationAddressCAC->AddressLine;
            $obRegAddAddressLine=$RegAddAddressLine->children($nsCbc);
            $RegAddLine=$obRegAddAddressLine->Line; // Jiron

            $RegAddCountry =$obRegistrationAddressCAC->Country;
            $obRegAddAddressCountry=$RegAddCountry->children($nsCbc);
            $RegAddCountry=$obRegAddAddressCountry->IdentificationCode; // ID Pais

            //  CLIENTE Datos
            $CliA_GCACInvoice=$GCACInvoice->AccountingCustomerParty;
            $CliObParty=$CliA_GCACInvoice->children($nsCac);
            $CliParty=$CliObParty->Party;
            $CliObChildParty=$CliParty->children($nsCac);

            $CliPartyIdentification=$CliObChildParty->PartyIdentification;
            $CliObID=$CliPartyIdentification->children($nsCbc);
            $CliIDObId=$CliObID->ID; // nro Doc cliente
            //AtributeID
            $CliattrIDObId=$CliIDObId->attributes();
            $ClischemaId=$CliattrIDObId["schemeID"]; //id DNI /RUC

        $CliPartyLegalEntity=$CliObChildParty->PartyLegalEntity;
        $CliobCbcPartyLegalEntity=$CliPartyLegalEntity->children($nsCbc);
        $CliRegistrationName=$CliobCbcPartyLegalEntity->RegistrationName;

                //taxTotal
            $ObTaxTotal=$GCACInvoice->TaxTotal;
            $tTaxAmount=$ObTaxTotal->children($nsCbc);
            $tTaxSubtotal=$ObTaxTotal->children($nsCac);

            $TaxAmount=$tTaxAmount->TaxAmount; //

            $TaxSubtotal=$tTaxSubtotal->TaxSubtotal;
            $obCbcTTaxableAmount=$TaxSubtotal->children($nsCbc);
            $obCacTTaxableAmount=$TaxSubtotal->children($nsCac);
            $TTaxableAmount=$obCbcTTaxableAmount->TaxableAmount;
            $TTaxAmount=$obCbcTTaxableAmount->TaxAmount;

            $TxTaxCategory=$obCacTTaxableAmount->TaxCategory;
            $txTaxScheme=$TxTaxCategory->children($nsCac);
            $TTaxScheme=$txTaxScheme->TaxScheme;
            $obTTaxScheme=$TTaxScheme->children($nsCbc);
            $tTaxSchemeID=$obTTaxScheme->ID; // tipos codigo monto
            $tTaxSchemeName=$obTTaxScheme->Name;
            $tTaxSchemeTaxTypeCode=$obTTaxScheme->TaxTypeCode;

            // Legal monetary
            $ObLegalMonetaryTotal=$GCACInvoice->LegalMonetaryTotal;
            $obT2LegalMonetaryTotal=$ObLegalMonetaryTotal->children($nsCbc);
            $LineExtensionAmount=$obT2LegalMonetaryTotal->LineExtensionAmount;
            $TaxInclusiveAmount=$obT2LegalMonetaryTotal->TaxInclusiveAmount;
            $PayableAmount=$obT2LegalMonetaryTotal->PayableAmount;


        //$XmlContent->registerXPathNamespace('Invoice',$nsXmlsInvo);
       //  $dd=$XmlContent->xpath("Invoice");

        //$XmlContent->registerXPathNamespace('cbc',$nsCbc);

        //$ID=$entries->xpath('//cbc:ID');

        //echo "<pre>".$TypeCodelistID;print_r($GCACInvoice);
        //$entries->registerXPathNamespace('cac',$nsCac);


        // Emisor
        $address = new Address();
        $address->setUbigueo($RegAddID)
            ->setDepartamento($RegAddCountrySubentity)
            ->setProvincia($RegAddCityName)
            ->setDistrito($RegAddDistrict)
            ->setUrbanizacion($RegAddCitySubdivisionName)
            ->setDireccion(strtoupper($RegAddLine));



       // echo "$RegAddLine";


        $company = new Company();
        $company->setRuc($RucIDObId)
            ->setRazonSocial(strtoupper($RegistrationName))
            ->setNombreComercial(strtoupper($PartyNameName))
            ->setAddress($address);



        $montoTotalVenta = 0;
        $precioTotalVenta = 0;
        $impuesto = 0;
        $detItem = [];


        $isdniRUC = $ClischemaId;
        $NumDoc = $CliIDObId;
        $razonCliente = strtoupper($CliRegistrationName);
        $dirD = "";
        $dirDSinesacio = preg_replace('/\s\s+/', ' ', $dirD);
        $emailventa = "";

        $departamento = "";
        $provincia = "";
        $distrito = "";
        $ubigeo = "";


        //Clinte
        $client = new Client();
        $client->setTipoDoc($isdniRUC) // ruc // 1 dni
        ->setNumDoc($NumDoc)
            ->setRznSocial($razonCliente);

        if (isset($departamento) && isset($provincia) && isset($distrito) && isset($ubigeo) && strlen($dirDSinesacio) > 0) {
            $addressCli = new Address();
            $addressCli->setUbigueo($ubigeo)
                ->setDepartamento($departamento)
                ->setProvincia($provincia)
                ->setDistrito($distrito)
                ->setUrbanizacion('NONE')
                ->setDireccion($dirD);
        } else if (strlen($dirDSinesacio) > 0) {
            $addressCli = $dirD;
        } else {
            $addressCli = "";
        }
        $client->setAddress($addressCli);

        print_r($client);
        //print_r($address);
        exit();



        $InvoiceLineResult=$XmlContent->xpath('//cac:InvoiceLine');
        foreach($InvoiceLineResult as $r){

            $cbcItem = $r->children($nsCbc);
            $cacItem = $r->children($nsCac);

            $nroItem=$cbcItem->ID; //Nro Tiem
            $InvoicedQuantity=$cbcItem->InvoicedQuantity; //Cantidad Item
            $ObUnitCode=$InvoicedQuantity->attributes();
            $unitCode=$ObUnitCode["unitCode"] ; // Codigo Sunat Unidad

            $LineExtensionAmount=$cbcItem->LineExtensionAmount;

            $obPricingReference=$cacItem->PricingReference;
            $chObPricingReference=$obPricingReference->children($nsCac);
            $obchObPricingReference=$chObPricingReference->AlternativeConditionPrice;
            $chobchObPricingReference=$obchObPricingReference->children($nsCbc);
            $ItemPriceAmount=$chobchObPricingReference->PriceAmount;
            $ItemPriceTypeCode=$chobchObPricingReference->PriceTypeCode;

            $obTaxTotal=$cacItem->TaxTotal;
            $chiCbcObTaxTotal=$obTaxTotal->children($nsCbc);
            $chiCacObTaxTotal=$obTaxTotal->children($nsCac);
            $ItemXTaxAmount=$chiCbcObTaxTotal->TaxAmount;

            $obTaxSubtotal= $chiCacObTaxTotal->TaxSubtotal;
            $chiObCbcTaxSubtotal=$obTaxSubtotal->children($nsCbc);
            $chiObCacTaxSubtotal=$obTaxSubtotal->children($nsCac);

            $itemxTaxableAmount=$chiObCbcTaxSubtotal->TaxableAmount;
            $itemxTaxAmount=$chiObCbcTaxSubtotal->TaxAmount;

            $obITaxCategory=$chiObCacTaxSubtotal->TaxCategory;
            $chiobCbcITaxCategory=$obITaxCategory->children($nsCbc);
            $chiobCacITaxCategory=$obITaxCategory->children($nsCac);

            $ItemxPercent=$chiobCbcITaxCategory->Percent;
            $ItemxTaxExemptionReasonCode=$chiobCbcITaxCategory->TaxExemptionReasonCode;

            $obTaxScheme =$chiobCacITaxCategory->TaxScheme;

            $obChiTaxScheme=$obTaxScheme->children($nsCbc);
            $TaxSchemeID=$obChiTaxScheme->ID;
            $TaxSchemeName=$obChiTaxScheme->Name;
            $TaxSchemeTaxTypeCode=$obChiTaxScheme->TaxTypeCode;

            //---
            $obItItem=$cacItem->Item;
            $chiCbcobItItem=$obItItem->children($nsCbc);
            $chiCacobItItem=$obItItem->children($nsCac);

            $iDescription= $chiCbcobItItem->Description;
            $iSellersItemIdentification= $chiCacobItItem->SellersItemIdentification;
            $obiSellersItemIdentification=$iSellersItemIdentification->children($nsCbc);
            $iSellersItemIdentificationID=$obiSellersItemIdentification->ID;

            //--
            $obItPrice=$cacItem->Price;
            $chIobItPrice=$obItPrice->children($nsCbc);
            $ItemPricePriceAmount=$chIobItPrice->PriceAmount;

        }



    }

    //End

    //COmunicación de baja


    private function getCorrelativoCombajaNotas()
    {
        $q = " SELECT
        Coalesce(MAX(bandejafacturacion.correlativonro),0) +1 as c
        FROM
        bandejafacturacion
        INNER JOIN combajasnotas
        ON combajasnotas.idcombajanotas = bandejafacturacion.idreferencia 
        and 'combaja'=bandejafacturacion.referencia
        where combajasnotas.estado>0 and bandejafacturacion.estado>0";
        $r = $this->db->query($q)->result_array();
        return $r[0]["c"];
    }

    public function getIniAutocompleteSearchBolFactNotes($tipo = null)
    {
        $search = trim($this->input->get('term', TRUE));
        $w = "";
        $sel = "";
        $dias = 7;
        if ($search) {
            $w = " and bandejafacturacion.seriecorrelativo like '%$search%' ";

        }
        if ($tipo == "nota") {
            $dias = 15;
            $sel = "bandejafacturacion.idtipodoc,
        bandejafacturacion.referencia,
        bandejafacturacion.idreferencia,       
        bandejafacturacion.serie,
        bandejafacturacion.correlativonro,
        bandejafacturacion.correlativo,
        bandejafacturacion.xmlfile,
        bandejafacturacion.msjsunat,
        bandejafacturacion.ublvalido,
        bandejafacturacion.msjubl,
        bandejafacturacion.respuestacdrfile,
        bandejafacturacion.existcdr,
        bandejafacturacion.codesunatrespuesta,
        bandejafacturacion.estado,
        bandejafacturacion.numruc,
        
        ftipocomprobante.descripcion AS desccomprobante,
        bandejafacturacion.tipdocucliente,
        bandejafacturacion.numdocucliente,
        bandejafacturacion.razoncliente,
        bandejafacturacion.direccioncliente,
        bandejafacturacion.emailcliente,
        venta.fechaventa,
        venta.horaventa,
        venta.idventa,
         ";
        }

        $q = "SELECT
    DATEDIFF(NOW(),  bandejafacturacion.fecharegistracdr ) AS diastranscurridos,
    bandejafacturacion.idbandejafacturacion,
    bandejafacturacion.fecharegistracdr,
    $sel 
    bandejafacturacion.seriecorrelativo as value
    FROM       
    bandejafacturacion
    INNER JOIN ftipocomprobante ON bandejafacturacion.idtipodoc = ftipocomprobante.idftipocomprobante
    inner join venta on bandejafacturacion.idreferencia=venta.idventa and bandejafacturacion.referencia='venta'
    where bandejafacturacion.codesunatrespuesta=0
    and bandejafacturacion.existcdr=1
    and bandejafacturacion.respuestacdrfile <> ''    
    and bandejafacturacion.fecharegistracdr is not null  $w
    and bandejafacturacion.idtipodoc in('01','03') and DATEDIFF(NOW(),  bandejafacturacion.fecharegistracdr ) < $dias";
        $r = $this->db->query($q)->result_array();
        echo json_encode($r);

    }

    public function setComunicaBaja()
    {
        $return["status"] = "fail";
        $post = $this->input->post(null, true);
        if (sizeof($post) > 0) {
            $fgene = $post["fgene"];
            $fgene = $fgene . " " . date("H:m:s");
            $fcom = $post["fcom"];
            $fcom = $fcom . " " . date("H:m:s");
            $idbanfact = $post["idbanfact"];
            $descbaja = $post["descbaja"];

            //$this->db->update('venta',["isdadobaja"=>1], ["idventa"=>$id]);

            $correlativoNro = $this->getCorrelativoCombajaNotas();

            $dComBaja = array(
                "fechacomnotas" => $fcom,
                "fechagencomnotas" => $fgene,
                "fechareg" => date("Y-m-d H:m:s"),
                "correlativo" => $correlativoNro,
                "estado" => 1
            );
            $this->db->trans_start();
            $this->db->insert("combajasnotas", $dComBaja);
            $idcombajas = $this->db->insert_id();
            $this->db->trans_complete();
            $dtCombaja = [];

            $dtCombaja[] = array(
                "idcombajasnotas" => $idcombajas,
                "idbandejafacturacion" => $idbanfact,
                "descbaja" => $descbaja,
                "estado" => 1);

            $this->db->insert_batch("detcombajasnotas", $dtCombaja);
            $serie = "RA01";
            $correlativo = str_pad($correlativoNro, 8, "0", STR_PAD_LEFT);
            $serieCorre = $serie . "-" . $correlativo;
            $dataInsBandeja = array(
                "idtipodoc" => "RA",
                "referencia" => "combaja",
                "idreferencia" => $idcombajas,
                "serie" => $serie,
                "correlativonro" => $correlativoNro,
                "correlativo" => $correlativo,
                'seriecorrelativo' => $serieCorre,
                "isenviadosunat" => 0,
                "fecharegistroorigen" => $fgene,
                "fechareg" => date("Y-m-d H:m:s"),
                "estado" => 1
            );
            $this->db->trans_start();
            $this->db->insert("bandejafacturacion", $dataInsBandeja);
            $idbandfact = $this->db->insert_id();
            $this->db->trans_complete();
            $return["status"] = "ok";
            //$return["data"]=$this->generaXMLValidaComBaja($idbandfact,$idcombajas);
        }
        echo json_encode($return);
    }

    public function generaXMLValidaComBaja($idbandfact, $idref)
    {
        $dataXmlCuerpo = $this->_bodyCerpoBaja($idref);
        $r = $this->generaXMLetc("combaja", $idbandfact, $idref, $dataXmlCuerpo);
        return $r;
    }

    public function generaXmlValidaNotas($idbandfact, $idref)
    {
        $dataXmlCuerpo = $this->_bodyCuerpoNotas($idref);
        $r = $this->generaXMLetc("notacredito", $idbandfact, $idref, $dataXmlCuerpo);
        return $r;
    }

    private function _bodyCerpoBaja($idref)
    {
        $dataCuerpoBaja = $this->getDataCuerpoBaja($idref);
        $dataXmlCuerpo = $this->cuerpoComBaja($dataCuerpoBaja);
        return $dataXmlCuerpo;
    }

    private function _bodyCuerpoNotas($idref)
    {
        $dataCuerpo = $this->getDataCuerpoNotas($idref);
        $dataXmlCuerpo = $this->cuerpoNotas($dataCuerpo);
        return $dataXmlCuerpo;
    }

    private function generaXMLetc($tipo, $idbandfact, $idref, $dataXmlCuerpo)
    {
        $utilx = $this->utilx;
        $cuerpoXML = $dataXmlCuerpo;
        $see = $utilx->getSee($this->endPoint);
        $xml = $see->getXmlSigned($cuerpoXML);
        file_put_contents("filesfact/comprobantes/" . $cuerpoXML->getName() . '.xml', $xml);
        $exist = file_exists('filesfact/comprobantes/' . $cuerpoXML->getName() . '.xml');
        $dataRESunat["xmlfile"] = "ARchivo no encontrado";
        $dataRESunat["xmlgenerado"] = "0";
        if ($exist) {
            $dataRESunat["xmlfile"] = $cuerpoXML->getName() . '.xml';
            //ValidaUBL
            $dataRESunat["xmlgenerado"] = "1";
            $returnUBL = $this->ublValidator($cuerpoXML->getName() . '.xml');
            if ($returnUBL["status"] == "ok") {
                $dataRESunat["ublvalido"] = '1';
                $dataRESunat["msjubl"] = $returnUBL["msj"];
                $dataRESunat["fechageneraxml"] = date("Y-m-d H:m:s");

            } else {
                $dataRESunat["ublvalido"] = '0';
                $dataRESunat["msjubl"] = $returnUBL["msj"];
            }
            $this->db->update('venta', ["xmlgenerado" => $dataRESunat["xmlgenerado"]], ["idventa" => $idref]);
            $this->db->update('bandejafacturacion', $dataRESunat, ["referencia" => $tipo, "idreferencia" => $idref]);
        }
    }

    private function getDataCuerpoBaja($idcombaja = null)
    {
        $w = "";
        if ($idcombaja) {
            $w = " and combajasnotas.idcombajanotas=$idcombaja ";
        }
        $q = "SELECT
        combajasnotas.correlativo,
        combajasnotas.fechagencomnotas,
        combajasnotas.fechacomnotas,
        bandejafacturacion.idtipodoc,
        bandejafacturacion.seriecorrelativo,
        detcombajasnotas.descbaja,
        bandejafacturacion.referencia,
        bandejafacturacion.idreferencia,
        bandejafacturacion.serie as seriecompro ,
        bandejafacturacion.correlativo as correlativocompro
        FROM
        combajasnotas
        INNER JOIN detcombajasnotas ON combajasnotas.idcombajanotas = detcombajasnotas.idcombajasnotas
        INNER JOIN bandejafacturacion ON bandejafacturacion.idbandejafacturacion = detcombajasnotas.idbandejafacturacion
        where   combajasnotas.estado>0 and bandejafacturacion.estado>0 and detcombajasnotas.estado>0 $w ";
        $r = $this->db->query($q)->result_array();
        return $r;
    }

    private function cuerpoComBaja($dataCuerpoBaja)
    {
        $dataEmpresa = $this->getDatosEmpresa(1);

        // Emisor
        $address = new Address();
        $address->setUbigueo($dataEmpresa["ubigeo"])
            ->setDepartamento($dataEmpresa["departamento"])
            ->setProvincia($dataEmpresa["provincia"])
            ->setDistrito($dataEmpresa["distrito"])
            ->setUrbanizacion('NONE')
            ->setDireccion($dataEmpresa["direccion"]);

        $company = new Company();
        $company->setRuc($dataEmpresa["ruc"])
            ->setRazonSocial($dataEmpresa["razonsocial"])
            ->setNombreComercial($dataEmpresa["nombrecomercial"])
            ->setAddress($address);
        $dtDetail = [];
        foreach ($dataCuerpoBaja as $dcb) {
            $detial1 = new VoidedDetail();
            $detial1->setTipoDoc($dcb["idtipodoc"])
                ->setSerie($dcb["seriecompro"])
                ->setCorrelativo($dcb["correlativocompro"])
                ->setDesMotivoBaja($dcb["descbaja"]);
            array_push($dtDetail, $detial1);
        }

        $voided = new Voided();
        $voided->setCorrelativo($dataCuerpoBaja[0]["correlativo"])
            ->setFecGeneracion(new DateTime($dataCuerpoBaja[0]["fechagencomnotas"]))
            ->setFecComunicacion(new DateTime($dataCuerpoBaja[0]["fechacomnotas"]))
            ->setCompany($company)
            ->setDetails($dtDetail);
        //print_r($voided);exit();
        return $voided;
    }

    //end COM

/// Nota de pedido


    public function getTipoNotaCredito()
    {
        $q = "SELECT
        tiponotacredito.idtiponotacredito,
        tiponotacredito.descripcion
        FROM
        tiponotacredito";
        $r = $this->db->query($q)->result_array();
        return $r;
    }

    public function setNotaCredito()
    {
        $return["status"] = "fail";
        $post = $this->input->post(null, true);
        if (sizeof($post) > 0) {
            //print_r($post);exit();
            $nttiponotacredito = $post["nttiponotacredito"];
            $ntcomprobante = $post["ntcomprobante"];

            $ntcliente = $post["ntcliente"];
            $ntseldoc = $post["ntseldoc"];
            $nttipocomprobante = $post["nttipocomprobante"];
            $ntdocref = $post["ntdocref"];
            $fecha = date("Y-m-d H:m:s");
            $ntmotivo = $post["ntmotivo"];

            $cantidadc = $post["cantidadc"];
            $cantidadcBF = $post["cantidadcBF"];

            $productoventa = $post["productoventa"];
            $prodisboleteo = $post["prodisboleteo"];

            $ntidreferenciaventa = $post["ntidreferenciaventa"];

            $pventa = $post["pventa"];
            $pventaf = $post["pventaf"];
            $inicorrelativo = "";
            if ($nttipocomprobante == "03") {
                $inicorrelativo = "BC01";
            } else if ($nttipocomprobante == "01") {
                $inicorrelativo = "FC01";
            }
            $coorre = $this->getCorrelativoNotaCredito();
            $seriecorrelativonota = $inicorrelativo . "-" . str_pad($coorre, 8, "0", STR_PAD_LEFT);
            $dataNota = array(
                "idtiponotacredito" => $nttiponotacredito,
                "serie" => $inicorrelativo,
                "nro" => $coorre,
                "serienro" => $seriecorrelativonota,
                "motivonota" => $ntmotivo,
                "estado" => 1,
                "tipocomprobante" => $nttipocomprobante,
                "fechareg" => $fecha,
                "fechanota" => $fecha,
                "serienroreferencia" => $ntdocref,
                "idreferenciaventa" => $ntidreferenciaventa
            );


            $this->db->trans_start();
            $this->db->insert("notacredito", $dataNota);
            $idnotacredito = $this->db->insert_id();

            $dataInsBandeja = array(
                "idtipodoc" => "07",
                "referencia" => "notacredito",
                "idreferencia" => $idnotacredito,
                "serie" => $inicorrelativo,
                "correlativonro" => $coorre,
                'seriecorrelativo' => $seriecorrelativonota,
                "isenviadosunat" => 0,
                "fecharegistroorigen" => $fecha,
                "fechareg" => date("Y-m-d H:m:s"),
                "estado" => 1
            );
            $this->db->insert("bandejafacturacion", $dataInsBandeja);
            $idbandfact = $this->db->insert_id();
            $this->db->trans_complete();
            $detNota = [];
            for ($i = 0; $i < count($productoventa); $i++) {
                $detNota[] = array(
                    "idnotacredito" => $idnotacredito,
                    "idproducto" => $productoventa[$i],
                    "precioventa" => $pventa[$i],
                    "precioventaf" => $pventaf[$i],
                    "cantidad" => $cantidadc[$i],
                    "cantidadbol" => $cantidadcBF[$i],
                    "idunidad" => 1,
                    "isforboleteo" => $prodisboleteo[$i],
                    "estado" => 1);
            }
            $this->db->insert_batch("detnotacredito", $detNota);
            $return["status"] = "ok";
        }
        echo json_encode($return);
    }

    private function getCorrelativoNotaCredito()
    {
        $q = "SELECT
        Coalesce(Max(notacredito.nro),0)+1 as c
        FROM
        notacredito
        where notacredito.estado>0";
        $r = $this->db->query($q)->result_array();
        return $r[0]["c"];
    }

    private function getDataCuerpoNotas($idref)
    {
        $w = "";
        if ($idref) {
            $w = "and notacredito.idnotacredito=$idref ";
        }
        $q = "SELECT
notacredito.idnotacredito,
venta.isdniorucventa,
cliente.nombre,
cliente.apellidos,
cliente.razonsocial,
cliente.isnaturalconruc,
cliente.idtipocliente,
detnotacredito.precioventa,
detnotacredito.precioventaf,
detnotacredito.cantidadbol,
bandejafacturacion.seriecorrelativo,
detnotacredito.cantidad,
detnotacredito.isforboleteo,
notacredito.idtiponotacredito,
venta.documentoventa,
venta.nombrerasonsocialventa,
venta.direccionventa,
venta.emailventa,
notacredito.serienroreferencia,
notacredito.motivonota,
bandejafacturacion.serie,
bandejafacturacion.correlativonro,
unidadmedida.codsunat as unidadsunat,
producto.marca,
producto.marcano,
producto.modelo,
producto.nombre as nproducto,
producto.codigobarra,
producto.descripcion as descproducto,
producto.idproducto,
notacredito.tipocomprobante
FROM
bandejafacturacion
INNER JOIN notacredito ON notacredito.idnotacredito = bandejafacturacion.idreferencia AND bandejafacturacion.referencia = 'notacredito'
INNER JOIN detnotacredito ON notacredito.idnotacredito = detnotacredito.idnotacredito
INNER JOIN venta ON venta.idventa = notacredito.idreferenciaventa
INNER JOIN cliente ON venta.idcliente = cliente.idcliente
INNER JOIN producto ON producto.idproducto = detnotacredito.idproducto
INNER JOIN unidadmedida ON producto.idunidadmedida = unidadmedida.idunidadmedida
where bandejafacturacion.estado>0  $w  ";
        $r = $this->db->query($q)->result_array();
        return $r;
    }

    public function cuerpoNotas($cuerponotas)
    {
        $dataEmpresa = $this->getDatosEmpresa(1);
        // Emisor
        $address = new Address();
        $address->setUbigueo($dataEmpresa["ubigeo"])
            ->setDepartamento($dataEmpresa["departamento"])
            ->setProvincia($dataEmpresa["provincia"])
            ->setDistrito($dataEmpresa["distrito"])
            ->setUrbanizacion('NONE')
            ->setDireccion($dataEmpresa["direccion"]);

        $company = new Company();
        $company->setRuc($dataEmpresa["ruc"])
            ->setRazonSocial($dataEmpresa["razonsocial"])
            ->setNombreComercial($dataEmpresa["nombrecomercial"])
            ->setAddress($address);
        //+
        $NotaDetail = $cuerponotas[0];
        $tipodoc = $NotaDetail["isdniorucventa"];
        $ndoc = $NotaDetail["documentoventa"];
        $rsnSocial = $NotaDetail["nombrerasonsocialventa"];

        $numDocfectado = $NotaDetail["serienroreferencia"];
        $idtiponotacredito = $NotaDetail["idtiponotacredito"];
        $motivonota = $NotaDetail["motivonota"];

        $tipocomprobante = $NotaDetail["tipocomprobante"];

        $serie = $NotaDetail["serie"];
        $correlativonro = $NotaDetail["correlativonro"];
        $correlativonro = str_pad($correlativonro, 8, "0", STR_PAD_LEFT);
        $client = new Client();
        $client->setTipoDoc($tipodoc) // ruc // 1 dni
        ->setNumDoc($ndoc)
            ->setRznSocial($rsnSocial);
        $dtNota = [];
        $sumTotalVenta = 0;
        foreach ($cuerponotas as $cpnota) {
            $TotalVenta = floatval($cpnota["cantidad"]) * floatval($cpnota["precioventa"]);
            $sumTotalVenta = $sumTotalVenta + $TotalVenta;
            $detail1 = new SaleDetail();
            $detail1
                ->setCodProducto($cpnota["idproducto"])
                ->setUnidad($cpnota["unidadsunat"])
                ->setCantidad($cpnota["cantidad"])
                ->setDescripcion($cpnota["descproducto"] . ".")
                ->setMtoBaseIgv(0)
                ->setPorcentajeIgv(0)
                ->setIgv(0)
                ->setTipAfeIgv('20')//exonerado
                ->setTotalImpuestos(0)
                ->setMtoValorVenta($TotalVenta)
                ->setMtoValorUnitario($cpnota["precioventa"])
                ->setMtoPrecioUnitario($cpnota["precioventa"]);

            array_push($dtNota, $detail1);
        }

        $note = new Note();
        $note->setUblVersion('2.1')
            ->setTipDocAfectado($tipocomprobante)
            ->setNumDocfectado($numDocfectado)
            ->setCodMotivo($idtiponotacredito)
            ->setDesMotivo($motivonota)
            ->setTipoDoc('07')
            ->setSerie($serie)
            ->setFechaEmision(new DateTime())
            ->setCorrelativo($correlativonro)
            ->setTipoMoneda('PEN')
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas(0)
            ->setMtoOperExoneradas($sumTotalVenta)
            ->setMtoOperGratuitas(0)
            ->setMtoOperInafectas(0)
            ->setMtoIGV(0)
            ->setTotalImpuestos(0)
            ->setMtoImpVenta($sumTotalVenta);
        $numeroALetras = NumeroALetras::convert(floatval($sumTotalVenta), 'soles');
        $legend = new Legend();
        $legend->setCode('1000')
            ->setValue($numeroALetras);
        $note->setDetails($dtNota)
            ->setLegends([$legend]);

        return $note;
    }

    //__________________________________________________________________

       public function reporteFacturacionByDate()
    {
        $post = $this->input->post(null, true);
        $return["status"] = false;
        if (sizeof($post) > 0) {
            $fini = $post["fini"];
            $ffin = $post["ffin"];
            $monedas=["PEN","USD"];

                $w=" 
         and existcdr=1
        and isenviadosunat=1
        and bandejafacturacion.codesunatrespuesta=0 
        and ISNULL(isdadobaja)
        ";
                $q="SELECT
            bandejafacturacion.numruc,
            bandejafacturacion.idempresa,
            bandejafacturacion.moneda,
            empresa.razonsocial
            FROM
            bandejafacturacion
            inner join empresa on bandejafacturacion.idempresa=empresa.idempresa
            where 
            bandejafacturacion.codesunatrespuesta=0 
            and bandejafacturacion.estado>0  
            and existcdr=1
            and isenviadosunat=1
            and ISNULL(isdadobaja)
            and idtipodoc in(01,03)         
       
            and date(fecharegistroorigen) BETWEEN '$fini' and '$ffin'
            GROUP BY bandejafacturacion.numruc,                     
            bandejafacturacion.idempresa  ";
                $r=$this->db->query($q)->result_array();
                $dataR=[];
                foreach($r as $kk=>$ii){
                    $dd=$this->getTipoComprosByRucMoneda($ii["idempresa"],$fini,$ffin);
                    $dataR[]=array(
                        "numruc"=>$ii["numruc"],
                        "idempresa"=>$ii["idempresa"],
                        "razonsocial"=>$ii["razonsocial"],
                        "detalle"=>$dd
                    );
                }



            $return["status"] = true;
            $return["data"] = $dataR;

        }
        echo json_encode($return);
    }



    public function getTipoComprosByRucMoneda($idempresa,$fini,$ffin){
        $w=" 
         and existcdr=1
        and isenviadosunat=1
        and bandejafacturacion.codesunatrespuesta=0 
        and ISNULL(isdadobaja)
        ";
        $q="SELECT        
        bandejafacturacion.moneda
        FROM
        bandejafacturacion
        INNER JOIN detallebandejafacturacion ON bandejafacturacion.idbandejafacturacion = detallebandejafacturacion.idbandejafacturacion
        INNER JOIN ftipocomprobante ON ftipocomprobante.idftipocomprobante = bandejafacturacion.idtipodoc
        where         
        bandejafacturacion.estado>0  
        and detallebandejafacturacion.estado>0
        and idtipodoc in(01,03)
        and idempresa='$idempresa'  
        and  date(fecharegistroorigen) BETWEEN '$fini' and '$ffin'
        GROUP BY                  
                 bandejafacturacion.moneda
                
        ";
        $r=$this->db->query($q)->result_array();
        $dd=[];
        foreach($r as $k=>$i){
            $dt=$this->getMontoTipoFactByMoneda($idempresa,$i["moneda"],$fini,$ffin);

            $dd[]=array(
                "moneda"=>$i["moneda"],
                "detalle2"=>$dt
            );

        }
        return $dd;
    }

    public function getMontoTipoFactByMoneda($idempresa,$moneda,$fini,$ffin){
        $q="
        SELECT        
          sum(detallebandejafacturacion.precio*detallebandejafacturacion.cantidad) as cantprecio,
         ftipocomprobante.descripcion  as tipocomprobante
        FROM
        bandejafacturacion
        INNER JOIN detallebandejafacturacion ON bandejafacturacion.idbandejafacturacion = detallebandejafacturacion.idbandejafacturacion
        INNER JOIN ftipocomprobante ON ftipocomprobante.idftipocomprobante = bandejafacturacion.idtipodoc
        where         
        bandejafacturacion.estado>0  
        and detallebandejafacturacion.estado>0
        and idtipodoc in(01,03)
        and idempresa='$idempresa'  
        and moneda='$moneda'  
        and  date(fecharegistroorigen) BETWEEN '$fini' and '$ffin'
        GROUP BY                  
          ftipocomprobante.descripcion                 
        ";
        $r=$this->db->query($q)->result_array();

        return $r;
    }


    public function reporteFacturacionGroupCorrelativoRUcByDate()
    {
        $post = $this->input->post(null, true);
        $return["status"] = false;
        if (sizeof($post) > 0) {
            $fini = $post["fini"];
            $ffin = $post["ffin"];



            $return["status"] = true;
            $return["data"] = $r;
        }
        echo json_encode($return);
    }

    public function getListaComprasby()
    {
        $msj = "";
        $status = false;
        $post = $this->input->post(null, true);
        if (sizeof($post) > 0) {
            $anio = $post["idanio"];
            $q = "SELECT
        MONTH(compra.fechacompra) as mes,
        YEAR(compra.fechacompra) as anio,
        sum(detcompra.preciocomprafactura * detcompra.cantbolfactc) as monto
        FROM
        detcompra
        INNER JOIN compra ON detcompra.idcompra = compra.idcompra
        where compra.estado>0 and detcompra.estado>0 and YEAR(compra.fechacompra)=$anio
        GROUP BY MONTH(compra.fechacompra) ,YEAR(compra.fechacompra) 
        order BY YEAR(compra.fechacompra) desc ,MONTH(compra.fechacompra) desc 
        ";

            $r = $this->db->query($q)->result_array();
            $return["data"] = $r;
            $msj = "";
            $status = true;

        }
        $return["msj"] = $msj;
        $return["status"] = $status;
        echo json_encode($return);
    }


    public function getAniosCompras()
    {
        $q = "SELECT DISTINCT
        Year(compra.fechacompra) as anioscompra
        FROM
        compra
        where compra.estado>0 
        order by anioscompra desc
        ";
        $r = $this->db->query($q)->result_array();
        return $r;
    }


    public function getSeeConfigFact($id){
        $dataUserConfig=$this->getDataUserForConfig($id);
        $fileCert=$dataUserConfig["certfile"];
        $user=$dataUserConfig["usersunat"];
        $pass=$dataUserConfig["passsunat"];

        $see = new See();
        $see->setService($this->endPoint);
        $see->setCertificate(file_get_contents("filesfact/extra/resources/$fileCert"));
        $see->setCredentials($user, $pass);
        return $see;
    }

    public function getDataUserForConfig($idempresa){
        $q="select usersunat,passsunat,certfile from empresa where estado>0 and idempresa=$idempresa";
        $r=$this->db->query($q)->result_array();
        return $r[0];
    }

    //

    public function getEmpresas($id=0,$isJson=false){
        $where="";
        if($id!=0){
            $where="and idempresa=$id"   ;
        }
        $q="select * from empresa where estado>0 $where ";
        $r=$this->db->query($q)->result_array();
        if($isJson){
            echo json_encode($r);
        }else{
            return $r;
        }

    }

    public function getdataFactById(){
       $status=false;
       $post=$this->input->post(null,true);
       $data="";
        if(sizeof($post)>0){
            $id=$post["id"];
            $q="select *,date(bandejafacturacion.fecharegistroorigen) as fecharegistroorigendate,time(bandejafacturacion.fecharegistroorigen) as horaregistroorigen   from bandejafacturacion where estado>0 and idbandejafacturacion='$id'";
            $data=$this->db->query($q)->result_array();

            $q2="select  
           detallebandejafacturacion.iddetallebandejafacturacion   ,
       detallebandejafacturacion.idunidadmedida   ,
       detallebandejafacturacion.cantidad   ,
       detallebandejafacturacion.precio   ,    detallebandejafacturacion.descripcion   ,
       
       bandejafacturacion.moneda
              from 
              bandejafacturacion
                inner join detallebandejafacturacion 
                on bandejafacturacion.idbandejafacturacion=detallebandejafacturacion.idbandejafacturacion
               where  bandejafacturacion.estado>0 
                  and detallebandejafacturacion.estado>0
                 and bandejafacturacion.idbandejafacturacion='$id'";
            $dataDet=$this->db->query($q2)->result_array();

            $status=true;
        }
        $return["status"]=$status;
        $return["data"]=$data;
        $return["dataDet"]=$dataDet;

       echo json_encode($return);
    }


    public function setNewCompro(){
        $return['status']=false;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){


            $usuariogenera= $post["usuariogenera"] ;
            $tipocomprobante = $post["tipocomprobante"];
            $tipodocclientecompro =    $post["tipodocclientecompro"] ;
            $nroDocClienteCompro = $post["nroDocClienteCompro"] ;
            $clientecompro = $post["clientecompro"] ;
            $fechacompro = $post["fechacompro"];
            $horacompro=$post["horacompro"];
            if(empty($horacompro)){
                $horacompro=date("H:i:s");
            }
            $fechacompro=$fechacompro." ".$horacompro;

            $dirclientecompro = $post["dirclientecompro"] ;

            $depCompro = $post["depCompro"] ;
            $provCompro = $post["provCompro"];
            $distCompro = $post["distCompro"];
            $ubigeoCompro = $post["ubigeoCompro"];
            $emailCompro = $post["emailCompro"];
            //--
            $cantItemCompro = $post["cantItemCompro"];


            $unidadItemCompro = $post["unidadItemCompro"];
            //$unidadItemCompro=$unidadItemCompro[0];

            $descItemCompro = $post["descItemCompro"];
            // $descItemCompro=$descItemCompro[0];

            $precioUItemCompro = $post["precioUItemCompro"];
            // $precioUItemCompro=$precioUItemCompro[0];

            $subTotalUItemCompro = $post["subTotalUItemCompro"] ;
            $tipomonedacompro = $post["tipomonedacompro"];
            $totalVentaCompro = $post["totalVentaCompro"];



            $textRuc = $post["textRuc"];
            $arrTR=explode("-",$textRuc);
            $razonSocialEmpresa=$arrTR[1];
            $rucEmpresa=$arrTR[0];

            $TotalX=floatval($cantItemCompro)*floatval($precioUItemCompro);

            // data empresa
            $dtEmpresa=$this->getEmpresas($usuariogenera,false);
            $nombrecomercialempresa=$dtEmpresa[0]["nombrecomercial"];

            $departamentoEmpresa=$dtEmpresa[0]["departamento"];
            $provinciaEmpresa=$dtEmpresa[0]["provincia"];
            $distritoEmpresa=$dtEmpresa[0]["distrito"];
            $ubigeoEmpresa=$dtEmpresa[0]["ubigeo"];
            $direccionEmpresa=$dtEmpresa[0]["direccion"];

            $serieFact=$dtEmpresa[0]["seriefactura"];
            $serieBol=$dtEmpresa[0]["serieboleta"];
            $serieComm="";
            if($tipocomprobante == "01"){
                $serieComm=$serieFact;
            }else if($tipocomprobante == "03"){
                $serieComm =$serieBol;
            }

           // $maxCorre=$this->getCorrelativoByRuc($tipocomprobante,$rucEmpresa);
           // $maxCorre=$maxCorre+1;
            $idEdit=$post["idEditCompro"];
            $isEdit=$post["isEditCompro"];

            $dt=array(
                //"idtipodoc"=>$tipocomprobante,
                "referencia"=>"venta",
                "idreferencia"=>"",
                //"serie"=>$serieComm,
               // "correlativonro"=>$maxCorre,
               // "correlativo"=>str_pad($maxCorre, 8, "0", STR_PAD_LEFT),
              //  "seriecorrelativo"=>$serieComm."-".str_pad($maxCorre, 8, "0", STR_PAD_LEFT),
                //"isenviadosunat"=>
                //"codesunatrespuesta"=>
                //"xmlfile"=>
                // "msjsunat"=>
                //"ublvalido"=>
                // "msjubl"=>
                //"respuestacdrfile"=>
                // "existcdr"=>
                //"numruc"=>$rucEmpresa,
                // "nombrearchxml"=>
                // "resumenvalue"=>
                // "resumenfirma"=>
                "tipdocucliente"=>$tipodocclientecompro,
                "numdocucliente"=>$nroDocClienteCompro,
                "razoncliente"=>$clientecompro,
                "direccioncliente"=>$dirclientecompro,
                // "xml_content"=>
                // "pdf_content"=>
                // "codsucursal"=>
                "gravado"=>0,
                "inafecto"=>0,
                "exonerado"=>$TotalX,
                "suma_igv"=>0,
                "suma_descuento"=>0,
                "descuento_global"=>0,
                "gratuito"=>0,
                //"imagen_qr"=>
                // "html_content"=>
                "estado"=>1,
                 "fecharegistroorigen"=>$fechacompro,
                //"fechageneraxml"=>
                // "fechaenvio"=>
               // "fechareg"=>date("Y-m-d H:i:s"),
                "emailcliente"=>$emailCompro,
                //"nroticket"=>
                //"fecharegistracdr"=>
                //"xmlgenerado"=>
                //"isdadobaja"=>
                "idempresa"=>$usuariogenera,
                //"descripcion"=>$descItemCompro,
                //"unidadmedida"=>$unidadItemCompro,
                "moneda"=>$tipomonedacompro,
                "ubigeocli"=>$ubigeoCompro,
                "departamentocli"=>$depCompro,
                "provinciacli"=>$provCompro,
                "distritocli"=>$distCompro,
                "razonsocialempresa"=>$razonSocialEmpresa,
                "ubigeoempresa"=>$ubigeoEmpresa,
                "departamentoempresa"=>$departamentoEmpresa,
                "provinciaempresa"=>$provinciaEmpresa,
                "distritoempresa"=>$distritoEmpresa,
                "direccionempresa"=>$direccionEmpresa,
                "nombrecomercialempresa"=>$nombrecomercialempresa,
                //cantPrecio
                //"cantidadprod"=>$cantItemCompro,
               // "precioprod"=>$precioUItemCompro


            );

            $this->db->where(["idbandejafacturacion"=>$idEdit])->update("bandejafacturacion",$dt);
            //print_r($dt);exit();

            $this->db->update('detallebandejafacturacion',["estado"=>0], ["idbandejafacturacion"=>$idEdit]);
            $iddetallebandejafacturacion=$post["iddetallebandejafacturacion"];
            for($i=0;$i<count($cantItemCompro);$i++){
                $iddet=$iddetallebandejafacturacion[$i];
               // echo "dd".$iddet ;exit();
                $dataDet=array(
                    "idbandejafacturacion"=>$idEdit ,
                    "idunidadmedida"=>$unidadItemCompro[$i] ,
                   // "idproducto"=>
                    "cantidad"=> $cantItemCompro[$i],
                    "descripcion"=>$descItemCompro[$i] ,
                    "precio"=>$precioUItemCompro[$i] ,
                    "estado"=> 1,
                    //"preciof"=> ,
                    // "cantidadf"=> ,
                );
                if($iddet !=0 || $iddet !="0" ){
                    $this->db->update('detallebandejafacturacion',$dataDet, ["iddetallebandejafacturacion"=>$iddet]);
                }else{
                    $dataDet["idproducto"]=rand(1,200) ;
                    $this->db->insert("detallebandejafacturacion",$dataDet);
                }
            }
            $return["status"]=true;
        }
        echo json_encode($return);
    }


   public function deleteFact(){
        $return['status']=false;
        $post=$this->input->post(null,true);
        if(sizeof($post) > 0){

            $idfact=$post["idfact"];
            $idempresa=$post["idempresa"];
            $idtipodoc=$post["idtipodoc"];
            $correlativonro=$post["correlativoactual"];

            $existeCorrelativosDespuesDeEste=$this->existeCorrelativoDespues($idempresa,$idtipodoc,$correlativonro);

            if($existeCorrelativosDespuesDeEste == 0){
                $this->db->update('bandejafacturacion',["estado"=>0], ["idbandejafacturacion"=>$idfact]);
                $this->db->update('detallebandejafacturacion',["estado"=>0], ["idbandejafacturacion"=>$idfact]);
                $return['status']=true;
                $return['msj']="Se elimino correctamente";
            }else{
                $return['status']=false;
                $return['msj']="No se puede eliminar, debido a que existe correlativo generados luego de este";
            }
        }
        echo json_encode($return);
    }


    private function existeCorrelativoDespues($idempresa,$idtipodoc,$correlativonro){
        $correlativonroMASUNO=$correlativonro+1;
        $q="SELECT
            COUNT(bandejafacturacion.idbandejafacturacion) as c
            FROM
            bandejafacturacion
            where 
            bandejafacturacion.estado>0
            and bandejafacturacion.idempresa='$idempresa'
            and bandejafacturacion.idtipodoc='$idtipodoc'
            and bandejafacturacion.correlativonro=$correlativonroMASUNO";
        $r=$this->db->query($q)->result_array();
        return intval($r[0]["c"]);

    }
	
	
	 public function getBandejaFacturacion(){
        $post=$this->input->post(null,true);
        $return["status"]=false;
        if(sizeof($post)>0){
            $idbfact=$post["idfact"];
            $q="select * from bandejafacturacion where idbandejafacturacion=$idbfact ";
            $r=$this->db->query($q)->result_array();
            $return["data"]=$r;
            $return["status"]=true;
        }
        echo json_encode($return);
    }

    public function getDataTicket(){
        $post=$this->input->post(null,true);
        $return["status"]=false;
        if(sizeof($post)>0){
            $idempresa=$post["idempresaticket"];
            $idbfact=$post["idbandejaTicket"];
            $ticket=$post["nroTicketConsulta"];
            $xmlfile=$post["xmlfileTicket"];
            $rr=$this->_getDataTicket($idempresa,$idbfact,$ticket,$xmlfile);
            $return["status"]=true;
            $return["data"]=$rr;
        }
        echo json_encode($return);
    }

    public function _getDataTicket($idempresa,$idbfact, $ticket, $xmlfile){
        $obConfig=$this->getSeeConfigFact($idempresa);
        $obUtil=new Util($obConfig);
        $utilx =$obUtil::getInstance();
        //$utilx =$obUtil::getInstance();

        $see = $utilx->getSee($obConfig,$this->endPoint);
        $res = $see->getStatus($ticket);
        $xmlname = substr($xmlfile, 0, -4);
        if (!$res->isSuccess()) {
            ob_start();
            var_dump($res->getError());
            $resultxxx = ob_get_clean();
            $dataRESunat["msjsunat"] = $resultxxx;
            $return["statusSunat"] = "Fail";
            $this->db->update('bandejafacturacion', $dataRESunat, ["idbandejafacturacion" => $idbfact]);
        } else {
            //$cdr = $res->getCdrResponse()->getCode().PHP_EOL;;
            $dataRESunat["msjsunat"] = $res->getCdrResponse()->getDescription() . PHP_EOL;
            $dataRESunat["codesunatrespuesta"] = $res->getCdrResponse()->getCode() . PHP_EOL;
            $return["statusSunat"] = "ok";
            $dataRESunat["isenviadosunat"] = "1";
            file_put_contents('filesfact/comprobantes/R-' . $xmlname . '.zip', $res->getCdrZip());
            $existCDR = file_exists('filesfact/comprobantes/R-' . $xmlname . '.zip');
            if ($existCDR) {
                $dataRESunat["fechaenvio"] = date("Y-m-d H:i:s");
                $dataRESunat["respuestacdrfile"] = 'R-' . $xmlname . '.zip';
                $dataRESunat["existcdr"] = "1";
                $dataRESunat["fecharegistracdr"] = date("Y-m-d H:i:s");
            } else {
                $dataRESunat["existcdr"] = "0";
            }
            $this->db->update('bandejafacturacion', $dataRESunat, ["idbandejafacturacion" => $idbfact]);
        }

        echo json_encode($dataRESunat);

    }

    

    public function getDataForReportExportaComprobantes(){
        $post=$this->input->post(null,true);
        $fini=$post["fini"];
        $fend=$post["fend"];
        $dtemp=$this->getDataEmpresa();
        $dd=[];
        foreach($dtemp as $dte){
            $dd[]=array(
                "idempresa"=>$dte["idempresa"],
                "ruc"=>$dte["ruc"],
                "razonsocial"=>$dte["razonsocial"],
                "fini"=>$fini,
                "fend"=>$fend,
                "detalle"=>$this->getDataReportF($dte["idempresa"],$fini,$fend)
            );
        }

        $string = $this->load->view('facturacion/reportComprobantesResumen.php', ["data"=>$dd], TRUE);

        echo $string;

    }

    private function getDataReportF($idempresa,$fni,$fend){
            $q="SELECT
            bandejafacturacion.idempresa, 
            bandejafacturacion.idtipodoc,
            bandejafacturacion.serie,
            bandejafacturacion.correlativonro,
            bandejafacturacion.correlativo,
            bandejafacturacion.seriecorrelativo,
            bandejafacturacion.exonerado,
            bandejafacturacion.isenviadosunat,
            bandejafacturacion.codesunatrespuesta,
            bandejafacturacion.xmlfile,
            bandejafacturacion.msjsunat,
            bandejafacturacion.ublvalido,
            bandejafacturacion.msjubl,
            bandejafacturacion.respuestacdrfile,
            bandejafacturacion.existcdr,
            bandejafacturacion.numruc,
            bandejafacturacion.fecharegistroorigen,    
       DATE_FORMAT( bandejafacturacion.fecharegistroorigen, '%d/%m/%Y %h:%i:%s') as fecharegistroorigenformt,
            bandejafacturacion.fechageneraxml,
            bandejafacturacion.fechaenvio,
            bandejafacturacion.fechareg, 
            bandejafacturacion.moneda 
            FROM
            bandejafacturacion
            where bandejafacturacion.estado>0 
            and bandejafacturacion.idempresa=$idempresa
            and date(bandejafacturacion.fecharegistroorigen) BETWEEN '$fni' and '$fend'
            order by bandejafacturacion.idempresa asc,
            bandejafacturacion.idtipodoc asc,
            bandejafacturacion.correlativonro asc";
        $r=$this->db->query($q)->result_array();

        return $r;
    }

    private function getDataEmpresa(){
        $q="SELECT
            DISTINCT
            empresa.idempresa,
            empresa.ruc,
            empresa.razonsocial
            FROM
            empresa
            INNER JOIN bandejafacturacion
                ON empresa.idempresa = bandejafacturacion.idempresa";
        $r=$this->db->query($q)->result_array();
        return $r;
    }


}