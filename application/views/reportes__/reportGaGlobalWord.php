<?php
/*
$filename=$data[0]['nombrearearesponsable'];
$filename=str_replace(" ","",$filename);
$filename=trim(eliminar_tildes($filename)).date("Ymd");

header("Pragma: public");
header('Content-type: application/vnd.ms-word');
header("Content-Disposition: attachment; filename=".$filename.".doc; charset=utf-8");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Expires: 0");
*/
$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

?>
<html>
<body style="font-size: 12px;font-family: Arial,sans-serif; " >
<p>Evaluación de <b>'<?=$mes[$mesIni] ?>' </b> a <b>'<?=$mes[$mesEnd]?>' del '<?=$periodo?>' </b></p>
<?php
   //echo "<pre>";print_r($dataR);exit();
    foreach ($dataR as $dataArea){  ?>

    <b>Unidad Orgánica: <?php echo $dataArea["nombrearearesponsable"] ?>-<b id="" >[ <?php echo floatval(round($dataArea["DataArea"]["GaArea"]*100,2)) ?> %] </b></b><br>

        <?php
        $cantidadActOp=0;
        $sumatotalActOp=0;
        foreach($dataArea["DataArea"]["dataArea"] as $i ) {
            ?>
            <b style="padding-top: 0px;padding-bottom: 0px;" >Acci&oacute;n Estrat&eacute;gica: <?= htmlentities($i["accestrategica"])?></b><br>
            <?php foreach($i["dataactpre"] as $ii){ ?>
                <b style="margin-bottom: 10px">Actividad/Proyecto: <?=htmlentities($ii["actpre"])?></b><br><br>
                <?php foreach($ii["dataactop"] as $iii){ ?>

                    <table  style="margin-top: 10px; border: 1px solid #ddd; border-collapse: collapse;font-size: 12px; font-family: Arial,sans-Serif;" border="1" width="600"  >
                        <thead>
                        <tr>
                            <th style="padding: 2px;" >Actividad Operativa </th>
                            <td style="padding: 2px;" colspan="3"><?= htmlentities($iii["actop"]) ?></td>
                            <td style="padding: 2px;" colspan="2" >
                                <?php $gAoP=round($iii["gaop"]*100,2);
                                if( $gAoP<=25 ){
                                    echo ' <b style="font-size: 20px;color: #ef5350"> &#x25CF;<span style="font-size: 12px"> Bajo </span> </b>';
                                }else if($gAoP <= 50 ){
                                    echo '<b style="font-size: 20px;color: #ffa726"> &#x25CF;<span style="font-size: 12px"> Regular </span>  </b>';
                                }else if($gAoP <= 75){
                                    echo '<b style="font-size: 20px;color: #c7c361"> &#x25CF;<span style="font-size: 12px"> Buena </span>  </b>';
                                }else if($gAoP <= 100){
                                    echo ' <b style="font-size: 20px;color: #8bc34a"> &#x25CF;<span style="font-size: 12px"> Muy Buena </span> </b>';
                                }

                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th >#</th>

                            <th>Tarea</th>     <!--<th>M. de <br>Verificacion</th>-->
                            <th>UM</th>
                            <th>Ejecutado</th>
                            <th>Meta</th>
                            <th align="center">%</th>
                            <!--<th>Obs.</th> -->

                        </tr>
                        </thead>
                        <?php
                        $sumatotaltareas=0;
                        $counttareas=0;
                        foreach($iii["datatarea"] as $kk => $j ){
                            $gaini=$j["ga"][0]["porcenajeFinalejecAll"]; //porcenajeFinal
                            $ga= $gaini*100 ;

                            ?>
                            <tr>
                                <td style="text-align: left;width: 10px;padding: 2px;" ><?=($kk+1)?></td>

                                <td style="text-align: left;width: 500px;padding: 2px;" ><?= htmlentities($j["tarea"])?></td>
                                <td style="text-align: right;width: 40px;padding: 2px;" ><?= htmlentities($j["um"]) ?></td>
                                <td style="text-align: right;width: 20px;padding: 2px;" ><?= $j["ga"][0]["ejecAll"] ?></td>
                                <td style="text-align: right;width: 20px;padding: 2px;" ><?=$j["ga"][0]["metax"]?></td>
                                <td style="text-align: right;width: 20px;padding: 2px;" ><?= round($ga,2) ?></td>
                            </tr>
                        <?php }

                        ?>





                        <tr style="padding-top: 0px;">
                            <td style="text-align: right;width: 550px;padding: 2px;" colspan="5" ><b>Total</b></td>
                            <td style="text-align: right;width: 50px;padding: 2px;"  >


                                <?= round($iii["gaop"]*100,2) ?>
                            </td>
                        </tr>

                    </table>
                    <br>



                <?php }?>


            <?php }?>

        <?php } ?>

    <?php }?>

</body>
</html>

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
