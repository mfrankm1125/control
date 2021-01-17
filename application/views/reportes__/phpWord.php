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

 //echo "<pre>".$dataR[0]["nombrearearesponsable"] ;print_r($dataR);exit();

$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
$arrayArea=array();

$html2='<html>';
$html2.='<body style="font-size: 12px;font-family: Calibri,sans-serif; " >';
$html2.='<p>Evaluación de <b>'.$mes[$mesIni].' </b> a <b>'.$mes[$mesEnd].' del '.$periodo.' </b>  </p>';
$labelSemestreAnual="";
$labelSemestreAnual2="";
if($mesIni == 1 && $mesEnd == 6){
    $labelSemestreAnual="al I Semestre";
    $labelSemestreAnual2="PRIMER SEMESTRE";
}
if($mesIni == 1 && $mesEnd == 12){
    $labelSemestreAnual="Anual";
    $labelSemestreAnual2="ANUAL";
}
$countareas=0;
$gaArea=0;
$areasGa="";


foreach ($dataR as $dataArea) {
    if($dataArea["DataArea"] != null){
        $d=array(
            "iduser"=>$dataArea["id_user"],
            'iddependecia'=>$dataArea["iddependeciauser"],
            "area"=>$dataArea["nombrearearesponsable"],
            "ga"=>floatval(round($dataArea["DataArea"]["GaArea"]*100,2))
        );
        array_push($arrayArea,$d);

        $gAoP = round($dataArea["DataArea"]["GaArea"] * 100, 2);
        if ($gAoP <= 25) {
            $calificacionArea= '<b style="font-size: 20px;color: #ef5350"> &#x25CF;<b style="font-size: 14px;color: black"> Bajo </b> </b>';
        } else if ($gAoP <= 50) {
            $calificacionArea = '<b style="font-size: 20px;color: #ffa726"> &#x25CF;<b style="font-size: 14px;color: black"> Regular </b>  </b>';
        } else if ($gAoP <= 75) {
            $calificacionArea = '<b style="font-size: 20px;color: #c7c361"> &#x25CF;<b style="font-size: 14px;color: black"> Buena </b>  </b>';
        } else if ($gAoP <= 100) {
            $calificacionArea = '<b style="font-size: 20px;color: #8bc34a"> &#x25CF;<b style="font-size: 14px;color: black"> Muy Buena </b> </b>';
        }


        $countareas=$countareas+1;
        $gaArea=$gaArea+floatval(round($dataArea["DataArea"]["GaArea"]*100,2));
    $html2 .= '<h1 style="text-align: center;color: black;font-size: 20px;"><b><u>'.mb_strtoupper($dataArea["nombrearearesponsable"])  .'</u></b></h1>';
     $html2.='<p style="text-align: center;font-size: 20px;"><b>Porcentaje de Avance de la '.$dataArea["nombrearearesponsable"] .'</b> </p>';
        $html2.='<p style="text-align: center;"><b>EVALUACIÓN '.$labelSemestreAnual2.' DEL PLAN OPERATIVO INSTITUCIONAL 2017 CON ENFOQUE DE RESULTADOS</b></p>';
     $html2 .= '<p style="font-size: 15px"><b>Unidad Orgánica: <u>' .$dataArea["nombrearearesponsable"]  . '</u> - '.floatval(round($dataArea["DataArea"]["GaArea"]*100,2)).' %</b></p>';
        $areasGa.='<p style="font-size: 15px"><b>' .$dataArea["nombrearearesponsable"]  . ' - '.floatval(round($dataArea["DataArea"]["GaArea"]*100,2)).' %</b></p>';

        $cantidadActOp = 0;
        $sumatotalActOp = 0;


    foreach ($dataArea["DataArea"]["dataArea"] as $i) {

       // $html2 .= '<b style="padding-top: 0px;padding-bottom: 0px;" >Acción Estratégica: ' . $i["accestrategica"] . '</b> ';
        foreach ($i["dataactpre"] as $ii) {
            $html2 .= '<b style="margin-bottom: 10px">Actividad/Proyecto: ' . $ii["actpre"] . '</b> ';


            $html2 .= '<table  style="margin-top: 10px; border: 1px solid #ddd; border-collapse: collapse;font-size: 12px; font-family: Calibri,sans-Serif;" border="1" width="600"  >';
            $html2 .= '<thead>';
            $html2 .= '<tr>';
            $html2 .= '<th > N° </th>';

            $html2 .= '<th>Actividad Operativa</th>';
            $html2 .= '<th>Unidad de medida</th>';
            $html2 .= '<th>Ejecución Programada '.$labelSemestreAnual.'</th>';
            $html2 .= '<th>Ejecución Acumulada '.$labelSemestreAnual.' </th>';
            $html2 .= '<th align="center">Ejecución Total %</th>';

            $html2 .= ' </tr>';
            $html2 .= '</thead>';

            $countActOp=0;
            $sumActOp=0;
            $gaActOp=0;
            foreach ($ii["dataactop"] as $kkk=> $iii) {
                
                $gAoP = round($iii["gaop"] * 100, 2);
                if ($gAoP <= 25) {
                    $calificacion1= '<b style="font-size: 20px;color: #ef5350"> &#x25CF;<b style="font-size: 14px;color: black"> Bajo </b> </b>';
                } else if ($gAoP <= 50) {
                    $calificacion1 = '<b style="font-size: 20px;color: #ffa726"> &#x25CF;<b style="font-size: 14px;color: black"> Regular </b>  </b>';
                } else if ($gAoP <= 75) {
                    $calificacion1 = '<b style="font-size: 20px;color: #c7c361"> &#x25CF;<b style="font-size: 14px;color: black"> Buena </b>  </b>';
                } else if ($gAoP <= 100) {
                    $calificacion1 = '<b style="font-size: 20px;color: #8bc34a"> &#x25CF;<b style="font-size: 14px;color: black"> Muy Buena </b> </b>';
                }

                $sumatotaltareas = 0;
                $counttareas = 0;
                //foreach ($iii["datatarea"] as $kk => $j) {
                    $gaini =$iii["datagaop"][0]["porcenajeFinalejecAll"]; //porcenajeFinal
                    $ga = $gaini * 100;

                    $countActOp++;
                    $sumActOp=$sumActOp+$gaini;

                    $html2 .= '<tr>';
                    $html2 .= '<td style="text-align: left;width: 10px;padding: 2px;" >' . ($kkk + 1) . '</td>';
                    $html2 .= '<td style="text-align: justify;text-justify: inter-word;width: 500px;padding: 2px;" >' .$iii["actop"] . '</td>';
                    $html2 .= '<td style="text-align: center;width: 40px;padding: 2px;" >' . $iii["um"] . '</td>';
                    $html2 .= '<td style="text-align: right;width: 20px;padding: 2px;" >' . $iii["datagaop"][0]["metax"] . '</td>';
                    $html2 .= '<td style="text-align: right;width: 20px;padding: 2px;" >' . $iii["datagaop"][0]["ejecAll"] . '</td>';
                    $html2 .= '<td style="text-align: right;width: 20px;padding: 2px;" >' . round($ga, 2) . '% </td>';
                    $html2 .= '</tr>';
               // }
            }

            if($countActOp >0){
                $gaActOp=floatval(round(($sumActOp/$countActOp)*100,2));
            }else{
                $gaActOp=0;
            }

            $html2 .= '
                <tr style="padding-top: 0px;">
                    <td style="text-align: right;width: 550px;padding: 2px;" colspan="5" ><b>Total</b></td>
                    <td style="text-align: right;width: 50px;padding: 2px;"  >' .$gaActOp;
                $html2 .= '% </td>
                </tr>
            </table><p>.</p>
             ';


$html2 .='<p><u>Justificaciones:</u></p>';
   foreach($ii["dataactop"]  as  $keyTarea=>$valTarea ){

    foreach ($valTarea["meses"]  as $valMeses){
        if(sizeof($valMeses["datajustify"]) > 0){
            //$html2 .='<b>'.$mes[(int)$valMeses["id_tiempo"]].':</b>';
            foreach ($valMeses["datajustify"] as $valJustify){
                $html2 .="<p><b>Act. Operativa ".($keyTarea+1)."</b> (".$mes[(int)$valMeses["id_tiempo"]]."): ".$valJustify["motivo"]."</p>";
                //$html2 .=''.$valJustify["motivo"].'';
            }
        }
    }

 }
                $html2 .= '
                    <style>
                    .centeredText {text-align: center; font-family: Georgia; font-size: 16pt; color: #5689dd}
                    </style>
                     
                    <p class="centeredText">
                        <chart type="column" data-label-number="value" label-position-negative="inside" label-position="center"  style="width: 10cm;">
                            <title  font-size="9pt">'.$dataArea["nombrearearesponsable"].'</title>
                            
                            <legend legend-position="bottom"/>
                            <component type="floor" fill-color="#fff0f0" />
                            <series>
                                <ser name="Act. Operativas" />            
                            </series>
                            <categories>';
                foreach ($ii["dataactop"] as $kk => $j) {
                    $gaini = $j["datagaop"][0]["porcenajeFinalejecAll"]; //porcenajeFinal
                    $ga = $gaini * 100;

                    $html2 .= '<category name="' . ($kk + 1) . '">';
                    $html2 .= '<data value="' . round($ga, 2) . '" />';
                    $html2 .= '</category>';
                }

                $html2 .= '</categories>
                        </chart>
                    </p> ';



        }

     }
        $html2.="<p style='font-size: 14px'>La unidad orgánica: '<b>".$dataArea["nombrearearesponsable"]."</b>', luego de realizar sus actividades programadas para esta evaluación, obtuvo un grado de avance de <b>".floatval(round($dataArea["DataArea"]["GaArea"]*100,2))." %</b>, el que es calificado como ".$calificacion1." según el Tablero de medición.</p>";

    }

}


if($countareas > 1){
    if($countareas > 0 && $gaArea > 0){
        $gaGlobalAreas=round(floatval($gaArea/$countareas),2);
        $gAoP=$gaGlobalAreas;
        if ($gAoP <= 25) {
            $calificacion= '<b style="font-size: 20px;color: #ef5350"> &#x25CF;<b style="font-size: 12px"> Bajo </b> </b>';
        } else if ($gAoP <= 50) {
            $calificacion = '<b style="font-size: 20px;color: #ffa726"> &#x25CF;<b style="font-size: 12px"> Regular </b>  </b>';
        } else if ($gAoP <= 75) {
            $calificacion = '<b style="font-size: 20px;color: #c7c361"> &#x25CF;<b style="font-size: 12px"> Buena </b>  </b>';
        } else if ($gAoP <= 100) {
            $calificacion = '<b style="font-size: 20px;color: #8bc34a"> &#x25CF;<b style="font-size: 12px"> Muy Buena </b> </b>';
        }
        $html2.='<h1 style="color:black;font-size: 20px">[ Avance Global del POI por Áreas (sin promediar depedencias): "'.$gaGlobalAreas.'% " - Calificación: ' .$calificacion.']</h1>';
    }

    //$tableAreasGa.='</table>';
    //$html2.=$areasGa;
    //$html2.=$tableAreasGa;

}

if($countareas > 1){
$htTableAreas='<table style="margin-top: 10px; border: 1px solid #ddd; border-collapse: collapse;width:600px;" border="1"  >';
$htTableAreas.='<tr><td style="text-align: center" colspan="4">CUADRO DE CUMPLIMIENTO DE METAS REALIZADAS DURANTE EL AÑO '.$periodo.'</td></tr>';
$htTableAreas.='<tr><td>Nro</td><td>UNIDADES ORGÁNICAS </td><td>NIVEL DE AVANCE %</td><td>NIVEL DE AVANCE TOTAL %</td></tr>';
$arrayAreaPrincipal=$arrayArea;
$ctareas=0;
$ccNdep=0;
$sumGaNdep=0;
//echo "<pre>";print_r($arrayArea);exit();

foreach ($arrayAreaPrincipal as $arrAreas){
    if($arrAreas["iddependecia"]==0 ){
        $ctareas++;
        $ccNdep++;
        $htTableAreas.='<tr><td style="background-color:#9ec4f5"  >'.$ctareas.'</td><td style="background-color:#9ec4f5"   ><b>'.$arrAreas["area"].'</b></td><td style="background-color:#9ec4f5;text-align: right" >'.floatval($arrAreas["ga"]).'</td><td style="background-color:#9ec4f5;"></td></tr>';

        $countHijos=1;
        $SumGaHijos=floatval($arrAreas["ga"]);
        foreach ($arrayArea as $arrDep){
            if($arrAreas["iduser"] == $arrDep["iddependecia"]){
                $countHijos++;
                $ctareas++;
                $SumGaHijos=$SumGaHijos+floatval($arrDep["ga"]);
                $htTableAreas.='<tr><td>'.$ctareas.'</td><td>'.$arrDep["area"].'</td><td style="text-align: right"  >'.$arrDep["ga"].'</td><td></td></tr>';
            }
        }

        $gaAreaDep=floatval($SumGaHijos/$countHijos);
        $sumGaNdep=$sumGaNdep+$gaAreaDep;
        $htTableAreas.='<tr><td></td><td></td><td></td><td style="text-align: right" ><b>'.round($gaAreaDep,2).'</b></td></tr>'    ;

    }
}

$gaTotalGaDep=round(floatval($sumGaNdep/$ccNdep),2);

$htTableAreas.='<tr><td colspan="3">Total</td><td style="text-align: right">'.$gaTotalGaDep.'</td></tr>';
$htTableAreas.='</table>';
 $html2.=$htTableAreas;
}




$html2.='</body></html>';



$doc->html(array('html' => $html2));




//echo  $html2;exit();




//include in the render method the path where you want your document to be saved

$filename=$dataR[0]["filename"] ;
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

echo 'Se genero correctamente el Reporte de:" '.$dataR[0]["filename"] .'"';
echo '<br>De:<b>'.$mes[$mesIni].' </b> a <b>'.$mes[$mesEnd].' del '.$periodo.' </b>';
echo '<br><a href="'.base_url().$filename. '">Descargue el Archivo</a>';


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
