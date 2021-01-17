<?php
/** * Created by . * User: Frank Montilla Pérez * Date: 11/09/2017 * Time: 16:33 */

require_once APPPATH.'third_party/docxpresso/CreateDocument.inc';
$doc = new Docxpresso\createDocument();

$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a basic column bar chart
$html = $data;

$html2='<html>';


$doc->html(array('html' => $html));




//echo  $html2;exit();




//include in the render method the path where you want your document to be saved

$filename=$file_name ;
$filename=str_replace(" ","",$filename);
$filename=trim(eliminar_tildes($filename));
$filename=$filename."_".time().$format;
$doc->render($filename);

//echo "<pre>";print_r($doc);exit();
//echo a link to the generated document
/*
$fichero=getcwd()."/".$filename;
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=$filename");
header("Content-Type: application/vnd.oasis.opendocument.text ");
header("Content-Transfer-Encoding: binary");

// read the file from disk
readfile($fichero);*/

echo 'Se genero correctamente el Reporte';

echo '<br><a href="'.base_url().$filename. '">Click para descargar el Archivo</a>';


?>



<?php
function eliminar_tildes($cadena){

    //Codificamos la cadena en formato utf8 en caso de que nos de errores


    //Ahora reemplazamos las letras
    $cadena = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $cadena
    );

    $cadena = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $cadena );

    $cadena = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $cadena );

    $cadena = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $cadena );

    $cadena = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $cadena );

    $cadena = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $cadena
    );

    return $cadena;
}
?>
