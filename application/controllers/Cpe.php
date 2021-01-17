<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//require 'vendor/autoload.php';
//use mikehaertl\wkhtmlto\Pdf;


class Cpe extends CMS_Controller {

    public function index(){

        $this->template->renderizar_web('cpe/index');
    }



    public function getDataComprobante(){
        $post=$this->input->post(null,true);
        $return=null;
        if(sizeof($post)>0){
            $tipocomprobante=$post["tipocomprobante"];
            $tipocomprobante=preg_replace('/\s+/', '', $tipocomprobante);
            $serie=$post["serie"];
            $serie=preg_replace('/\s+/', '', $serie);
            $num=$post["num"];
            $num=preg_replace('/\s+/', '', $num);
            $total=floatval($post["total"]);
            $dd=$this->getDataX($tipocomprobante,$serie,$num,$total);
            // echo "<pre>"; print_r($dd);exit();
            if(sizeof($dd)==1){
                $htButtons="";
                $ref=$dd[0]["referencia"];
                $idref=$dd[0]["idreferencia"];
                $idbandejafacturacion=$dd[0]["idbandejafacturacion"];
                $fecha=urlencode($dd[0]["fecharegistroorigen"]);
                $filename=$dd[0]["seriecorrelativo"];
                $fileXml=$dd[0]["xmlfile"];
                $filepdf=$filename.".pdf";
                $gt=file_get_contents(base_url()."facturacion/reporteFactHtmlPDF/$ref/$idbandejafacturacion/$idbandejafacturacion/$fecha");

                /* $pdf = new Pdf();
                $pdf->setOptions([
                    'no-outline',
                    'print-media-type',
                    'viewport-size' => '1280x1024',
                    'page-width' => '21cm',
                    'page-height' => '29.7cm',
                    'footer-html' => ''
                ]);
                $pdf->binary = $this->getPathBin();
                $pdf->addPage($gt);
                if (!$pdf->saveAs('filesfact/pdfhtml/'.$filepdf)) {
                    $error = $pdf->getError();
                    $gt="ERROR...";
                }else{*/

                /* $uriPdf="filesfact/pdfhtml/".$filepdf;
                 $htButtons="<div id='divButtons' >";
                 if(file_exists($uriPdf)){
                     $strPdf=$this->encrypt_to_url_param($uriPdf); */
                // $gt.="<br><i class='fa fa-file-pdf-o'></i><a target='_blank' href='".base_url()."cpe/downloadFile/".$strPdf."' >Descargar Pdf</a> ";
                $htButtons="<div id='divButtons' >";
                $htButtons.="<button type='button' id='btnGenPdf'  class='btn btn-xs btn-purple' onclick='generate(this)'  > Descargar PDF </button>";
                /* } */

                $uriXml="filesfact/comprobantes/".$fileXml;

                if(file_exists($uriXml) and $fileXml !="" ){
                    $strXml=$this->encrypt_to_url_param($uriXml);
                    $htButtons.=" &nbsp;<i class='fa fa-file-pdf-o'></i><a target='_blank' href='".base_url()."cpe/downloadFile/".$strXml."' >Descargar XML</a> ";
                }
                $htButtons.="</div>";
                /*  }*/
                $return["dtHtml"]=$gt;
                $return["dtHtmlButtons"]=$htButtons;
                echo json_encode($return);

            }else{
                $return["dtHtml"]="<h1> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sin resultados</h1>";
                $return["dtHtmlButtons"]="";
                echo json_encode($return);
            }


        }else{
            $return["dtHtml"]="<h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sin resultados</h1>";
            $return["dtHtmlButtons"]="";
            echo json_encode($return);
        }
    }

    public function downloadFile($tt){
        $uri=$this->decrypt_from_url_param($tt);

        $ff=explode("/",$uri);
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$ff[2]");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");
        // Read the file
        readfile(base_url().$uri);
    }

    public function getComp(){
        $gt=file_get_contents("http://localhost:8080/ventas//facturacion/reporteFactHtmlPDF/venta/3869/3/2020-02-18%2011:54:06");
        // echo $gt;
        $this->load->library('pdf');
        $this->pdf->loadHtml($gt);

        $this->pdf->render();
        $this->pdf->setPaper('carta', 'portrait');
        return $this->pdf->stream("dompdf_out.pdf", array("Attachment" => false));
    }


    private function getDataX($tipocomprobante,$serie,$num,$total){
        $total=floatval($total);
        $q="SELECT
bandejafacturacion.idtipodoc,
bandejafacturacion.exonerado,
bandejafacturacion.correlativo,
bandejafacturacion.serie,
bandejafacturacion.idbandejafacturacion,
bandejafacturacion.referencia,
bandejafacturacion.xmlfile,
bandejafacturacion.idreferencia,date(bandejafacturacion.fecharegistroorigen) AS fecharegistroorigen ,bandejafacturacion.seriecorrelativo

FROM
bandejafacturacion
where bandejafacturacion.idtipodoc in(03,01)
and bandejafacturacion.exonerado =$total     

and bandejafacturacion.estado>0    and
bandejafacturacion.idtipodoc='$tipocomprobante' and bandejafacturacion.correlativo='$num' and bandejafacturacion.serie='$serie'
";
        $r=$this->db->query($q)->result_array();
        return $r;
    }

    public static function getPathBin(){
        $DIRX=realpath(dirname(__DIR__) . '/../') ;
        $path=$DIRX.'/vendor/bin/wkhtmltopdf';
        //echo $path;exit();
        if (self::isWindows()) {
            $path .= '.exe';
        }
        return $path;
    }
    public static function isWindows(){
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    const OPEN_SSL_METHOD = 'aes-256-cbc';
    // Generate a 256-bit encryption key
    const BASE64_ENCRYPTION_KEY = 'G1fM0aXhguJ5tVaqVMJOVHB+Jk6QFd99FgkfAcEgwjI';//base64_encode(openssl_random_pseudo_bytes(32));
    const BASE64_IV = 'xIkaHuquZFjtP4SI4mIyOg';//base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length(AES_256_CBC)));

    static private function base64_url_encode($input) {
        return strtr(base64_encode($input), '+/=', '-_,');
    }

    static private function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_,', '+/='));
    }


    static function encrypt_to_url_param($message){
        $encrypted = openssl_encrypt($message, self::OPEN_SSL_METHOD, base64_decode(self::BASE64_ENCRYPTION_KEY), 0, base64_decode(self::BASE64_IV));
        $base64_encrypted = self::base64_url_encode($encrypted);
        return $base64_encrypted;
    }

    static function decrypt_from_url_param($base64_encrypted){
        $encrypted = self::base64_url_decode($base64_encrypted);
        $decrypted = openssl_decrypt($encrypted, self::OPEN_SSL_METHOD, base64_decode(self::BASE64_ENCRYPTION_KEY), 0, base64_decode(self::BASE64_IV));
        return $decrypted;
    }

}
