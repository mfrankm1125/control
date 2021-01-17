<?php/*
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
header("Expires: 0");*/

$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
?>


<?php
$cga=0;
$cantidadActOpGa=0;
$sumGAxActOpGa=0;
foreach ($data as $dataActPrex){
    foreach ($dataActPrex["actvops"] as $dataAtvOpsx){
        if(sizeof($dataAtvOpsx)>0 ){
            $gainix=$dataAtvOpsx["ga"][0]["porcenajeFinal"];
            $cga=$cga+1;
            $cantidadActOpGa=$cantidadActOpGa+1;
            $sumGAxActOpGa=$sumGAxActOpGa+$gainix;
        }
    }

}
$gaFinal=round((floatval($sumGAxActOpGa/$cantidadActOpGa)*100),2);
?>



<h3>&Aacute;rea :
    <?php echo htmlentities($data[0]['nombrearearesponsable']); ?></h3>
<p>Evaluaci&oacute;n de <b><?=$mes[$mesIni]?></b> a <b><?=$mes[$mesEnd] ?> del <?=$periodo?> </b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Grado de Avance:<?=$gaFinal?> </p>






<?php
$cantidadActOp=0;
$sumGAxActOp=0;
foreach ($data as $dataActPre){

    ?>

    <table style=" border: 1px solid black;
    border-collapse: collapse;" border="1" width="1000"   cellpadding="5">
        <thead>
        <tr>
            <th  >Act.Pre. </th>
            <td colspan="3"><?= htmlentities($dataActPre['actpre'])?></td>

        </tr>
        <tr>
            <th >#</th>
            <th>UM</th>
            <th>Act.OP</th>       <!--<th>M. de <br>Verificacion</th>-->

            <th>%</th>
            <!--<th>Obs.</th> -->

        </tr>
        </thead>
        <?php $c=0; $sumGa=0; foreach ($dataActPre["actvops"] as $dataAtvOps){ $sumGa=$sumGa+ floatval($dataAtvOps["ga"][0]["porcenajeFinal"]) ;   ?>
            <?php if(sizeof($dataAtvOps)>0 ){
                $gaini=$dataAtvOps["ga"][0]["porcenajeFinal"];
                $ga=round((floatval($gaini)*100),2);
                $gaf=str_replace(".", ",", $ga);
                $c=$c+1;
                $cantidadActOp=$cantidadActOp+1;
                $sumGAxActOp=$sumGAxActOp+$gaini;
                ?>
                <tr>
                    <td><?=$c?></td> <td><?=htmlentities($dataAtvOps["um"])?></td> <td><?=htmlentities($dataAtvOps["nameactvop"])?></td>
                    <td style="text-align: right" ><?=$gaf?></td>
                </tr>
            <?php   }
        }?>
        <!--<tr>
            <td colspan="3" >Total</td>
            <td><?php str_replace(".", ",",round((floatval($sumGa/$c)*100),2)) ;?></td>
        </tr>-->
    </table>
    <br>
<?php } ?>

Total:<?php echo $sumGAxActOp."/".$cantidadActOp; ?>
<!-- a<img    src="<? base_url()?>assets/images/profile-fotos/foto_frank.png" alt="" /> -->



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
