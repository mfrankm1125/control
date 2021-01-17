<?php
$filename=$dataR[0]['filename'];
$filename=str_replace(" ","",$filename);
$filename=trim(eliminar_tildes($filename))."_".time();

header("Pragma: public");
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8");
//header("Content-Type: application/vnd.ms-excel; charset=utf-8");
//header("Content-Type: text/csv");application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
header("Content-Disposition: attachment; filename=".$filename.".xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header('Cache-Control: max-age=0');

$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
?>

<p>Evaluaci&oacute;n de <b>'<?=$mes[$mesIni] ?>' </b> a <b>'<?=$mes[$mesEnd]?>' del '<?=$periodo?>' </b></p>
<?php
$labelSemestreAnual="";
if($mesIni == 1 && $mesEnd == 6){
    $labelSemestreAnual="al I Semestre";
}
if($mesIni == 1 && $mesEnd == 12){
    $labelSemestreAnual="Anual";
}
//echo "<pre>";print_r($dataR);exit();
foreach ($dataR as $dataArea){  ?>

    <p style="font-size: 15px;">Unidad Org&aacute;nica:-<b id="" > <?php echo htmlentities($dataArea["nombrearearesponsable"]) ?> [ <?php echo floatval(round($dataArea["DataArea"]["GaArea"]*100,2)) ?> %] </b></p>

    <?php
    $cantidadActOp=0;
    $sumatotalActOp=0;

    foreach($dataArea["DataArea"]["dataArea"] as $i ) {
        ?>
        <!--<b style="padding-top: 0px;padding-bottom: 0px;" >Acci&oacute;n Estrat&eacute;gica: <?php //htmlentities($i["accestrategica"])?></b><br> -->
        <?php foreach($i["dataactpre"] as $ii){ ?>

            <!--<b style="margin-bottom: 10px">Actividad/Proyecto: <?php //htmlentities($ii["actpre"])?></b><br><br> -->

            <?php

                ?>
               <!--Actividad Operativa : <?php // htmlentities($iii["actop"]) ?> | <?php //$califica?> -->

                <table  style="margin-top: 10px;margin-left: 10px;font-size: 12px; font-family: Arial,sans-Serif;" border="1" width="1000"  >
                    <thead>

                    <tr>
                        <th > Nro  </th>

                        <th>Actividad Operativa</th>     <!--<th>M. de <br>Verificacion</th>-->
                        <th>Unidad de Medida</th>
                        <th>Ejecuci&oacute;n Programada <br><?=$labelSemestreAnual?></th>
                        <th>Ejecuci&oacute;n Acumulada <br><?=$labelSemestreAnual?></th>
                        <th align="center">Ejecuci&oacute;n Total %</th>
                        <!--<th>Obs.</th> -->

                    </tr>
                    </thead>
                    <?php
                    $sumatotaltareas=0;
                    $counttareas=0;

                    $countActOp=0;
                    $sumActOp=0;
                    $gaActOp=0;
                    foreach($ii["dataactop"] as $kkk=>$iii){
                          $gAoP=round($iii["gaop"]*100,2);
                        if( $gAoP<=25 ){
                            $califica='<b style="font-size: 20px;color: #ef5350"> &#x25CF;<span style="font-size: 12px"> Bajo </span> </b>';
                        }else if($gAoP <= 50 ){
                            $califica='<b style="font-size: 20px;color: #ffa726"> &#x25CF;<span style="font-size: 12px"> Regular </span>  </b>';
                        }else if($gAoP <= 75){
                            $califica='<b style="font-size: 20px;color: #c7c361"> &#x25CF;<span style="font-size: 12px"> Buena </span>  </b>';
                        }else if($gAoP <= 100){
                            $califica='<b style="font-size: 20px;color: #8bc34a"> &#x25CF;<span style="font-size: 12px"> Muy Buena </span> </b>';
                        }


                    //foreach($iii["datatarea"] as $kk => $j ){
                        $gaini=$iii["datagaop"][0]["porcenajeFinalejecAll"]; //porcenajeFinal
                        $ga= $gaini*100 ;

                        ?>
                        <tr>
                            <td style="text-align: center;width: 30px;padding: 2px;" ><?=($kkk+1)?></td>

                            <td style="text-align: left;width: 500px;padding: 2px;" ><?= htmlentities($iii["actop"])?></td>
                            <td style="text-align: center;width: 100px;padding: 2px;" ><?= htmlentities($iii["um"]) ?></td>
                            <td style="text-align: right;width: 100px;padding: 2px;" ><?=str_replace(".", ",",$iii["datagaop"][0]["metax"])?></td>
                            <td style="text-align: right;width: 100px;padding: 2px;" ><?= str_replace(".", ",",$iii["datagaop"][0]["ejecAll"]) ?></td>
                            <td style="text-align: right;width: 100px;padding: 2px;" ><?= str_replace(".", ",",round($ga,2)) ?>%</td>
                        </tr>
                    <?php }

                    if($countActOp >0){
                        $gaActOp=floatval(round(($sumActOp/$countActOp)*100,2));
                    }else{
                        $gaActOp=0;
                    }
                    ?>



                    <tr style="padding-top: 0px;">
                        <td style="text-align: right;width: 550px;padding: 2px;" colspan="5" ><b>Total</b></td>
                        <td style="text-align: right;width: 50px;padding: 2px;"  >


                            <?=$gaActOp ?>
                        </td>
                    </tr>

                </table>
                <br>


        <?php }?>

    <?php } ?>

<?php }?>



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
