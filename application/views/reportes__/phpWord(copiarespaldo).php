<?php
/** * Created by . * User: Frank Montilla Pérez * Date: 11/09/2017 * Time: 16:33 */

require_once APPPATH.'third_party/docxpresso/CreateDocument.inc';
$doc = new Docxpresso\createDocument();

$format = '.odt';//.pdf, .doc, .docx, .odt, .rtf
//insert a basic column bar chart
$html = '
<html>
    <head>
        <style>
         
        </style>
    </head>
    <body>
         
        <table border="1" style="width:600px;" >
            <tr>
                <th class="firstCol">Table title</th>
                <th>Column 1</th>
                <th>Column 2</th>
            </tr>
            <tr class="odd">
                <td class="firstCol">Row 1</td>
                <td class="odd">Cell_1_1</td>
                <td class="odd">Cell_1_2</td>
            </tr>
            <tr>
                <td class="firstCol">Row 2</td>
                <td>Cell_2_1</td>
                <td>Cell_2_2</td>
            </tr>
        </table>
    </body>
</html>
';

//echo "<pre>";print_r($dataR);exit();

$html2='<html>';
$html2.='<body style="font-size: 12px;font-family: Arial,sans-serif; " >';
$html2.='<p>Unidad Organica: '.$dataR["data"][0]["nombrearearesponsable"].'-<b id="" >  % </b></p>';

$cantidadActOp=0;
$sumatotalActOp=0;
foreach($dataR["data"] as $i ) {

$html2.='<b style="padding-top: 0px;padding-bottom: 0px;" >Accion Estrategica:'. $i["accestrategica"].'</b> ';
     foreach($i["dataactpre"] as $ii){
         $html2.='<b style="margin-bottom: 10px">Actividad/Proyecto:'.$ii["actpre"].'</b> ';
        foreach($ii["dataactop"] as $iii){
         $html2.='<table  style="margin-top: 10px; border: 1px solid #ddd; border-collapse: collapse;font-size: 12px; font-family: Arial,sans-Serif;" border="1" width="600"  >';
            $html2.='<thead>';
             $html2.='<tr>';
            $html2.='<th style="padding: 2px;" >Actividad Operativa </th>';
            $html2.='<td style="padding: 2px;" colspan="3">'.$iii["actop"].'</td>';
             $html2.='<td style="padding: 2px;" colspan="2" >';
                         $gAoP=round($iii["gaop"]*100,2);
                        if( $gAoP<=25 ){
                            $html2.='<b style="font-size: 20px;color: #ef5350"> &#x25CF;<span style="font-size: 12px"> Bajo </span> </b>';
                        }else if($gAoP <= 50 ){
                            $html2.='<b style="font-size: 20px;color: #ffa726"> &#x25CF;<span style="font-size: 12px"> Regular </span>  </b>';
                        }else if($gAoP <= 75){
                            $html2.='<b style="font-size: 20px;color: #c7c361"> &#x25CF;<span style="font-size: 12px"> Buena </span>  </b>';
                        }else if($gAoP <= 100){
                            $html2.='<b style="font-size: 20px;color: #8bc34a"> &#x25CF;<span style="font-size: 12px"> Muy Buena </span> </b>';
                        }


            $html2.='</td>';
            $html2.='</tr>';
            $html2.='<tr>';
            $html2.='<th >#</th>';

            $html2.='<th>Tarea</th>';
            $html2.='<th>UM</th>';
            $html2.='<th>Ejecutado</th>';
            $html2.='<th>Meta</th>';
            $html2.='<th align="center">%</th>';

            $html2.=' </tr>';
            $html2.='</thead>';
                $sumatotaltareas=0;
                $counttareas=0;
                foreach($iii["datatarea"] as $kk => $j ){
                    $gaini=$j["ga"][0]["porcenajeFinalejecAll"]; //porcenajeFinal
                    $ga= $gaini*100 ;

                    $html2.='<tr>';
                    $html2.='<td style="text-align: left;width: 10px;padding: 2px;" >'.($kk+1).'</td>';
                    $html2.='<td style="text-align: left;width: 500px;padding: 2px;" >'. $j["tarea"].'</td>';
                    $html2.='<td style="text-align: right;width: 40px;padding: 2px;" >'. $j["um"] .'</td>';
                    $html2.='<td style="text-align: right;width: 20px;padding: 2px;" >'. $j["ga"][0]["ejecAll"] .'</td>';
                    $html2.='<td style="text-align: right;width: 20px;padding: 2px;" >'.$j["ga"][0]["metax"].'</td>';
                     $html2.='<td style="text-align: right;width: 20px;padding: 2px;" >'. round($ga,2) .'</td>';
                    $html2.='</tr>';
                  }
                $html2.='
                <tr style="padding-top: 0px;">
                    <td style="text-align: right;width: 550px;padding: 2px;" colspan="5" ><b>Total</b></td>
                    <td style="text-align: right;width: 50px;padding: 2px;"  >'.round($iii["gaop"]*100,2);
            $html2.=' </td>
                </tr>
            </table><p>.</p>
             ';

            $html2 .= '
<style>
.centeredText {text-align: center; font-family: Georgia; font-size: 16pt; color: #5689dd}
</style>
 
<p class="centeredText">
    <chart type="column" data-label-number="value" label-position="top" stacked="true" style="width: 10cm;">
        <legend legend-position="bottom"/>
        <component type="floor" fill-color="#fff0f0" />
        <series>
            <ser name="Tareas" />            
        </series>
        <categories>
        
            <category name="01">
                <data value="50" />
                
            </category>
            <category name="02">
                <data value="680" />
                
            </category>
            <category name="03">
                <data value="650" />
                 
            </category>
             
        </categories>
    </chart>
</p>
';




        }

     }
}
$html2.='
</body>
</html>';



$doc->html(array('html' => $html2));




//echo  $html2;exit();




$data = array(
    'series' => array('F'),
    'Category 1' => array(20),
    'Category 2' => array(30),
    'Category 3' => array(12.5),
    'Category 4' => array(12.5),
    'Category 5' => array(55.5),
    'Category 6' => array(100),
);
$chartProperties = array('data-label-number' => 'percentage', 'label-position' => 'center');
$doc->paragraph()->style('text-align: center')
    ->chart('column', array('data' => $data,'chart-properties' => $chartProperties))->style('border: 1pt solid #777; width: 10cm; height: 6cm; padding: 0.2cm')	->chartLegend();

$data = array(
    'series' => array('F'),
    'Category 1' => array(20),
    'Category 2' => array(30),
    'Category 3' => array(12.5),
    'Category 4' => array(12.5),
    'Category 5' => array(55.5),
    'Category 6' => array(100),
);
$chartProperties = array('data-label-number' => 'percentage', 'label-position' => 'center');
$doc->paragraph()->style('text-align: center')
    ->chart('column', array('data' => $data,'chart-properties' => $chartProperties))->style('border: 1pt solid #777; width: 10cm; height: 6cm; padding: 0.2cm')	->chartLegend();

//include in the render method the path where you want your document to be saved
$filename="filename".time().$format;
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

echo 'You may download the generated document from the link below:<br/>';
echo '<a href="'.base_url().$filename. '">Download document</a>';


?>