<style type="text/css" >
    body {
        background: white;

    }
    #chart {
        width: 100%;
        height: 289px;
        margin: 30px auto 0;
        display: block;
    }
    #chart #numbers {
        width: 10%;
        height: 289px;
        margin: 0;
        padding: 0;
        display: inline-block;
        float: left;
    }
    #chart #numbers li {
        text-align: right;
        padding-right: 1em;
        list-style: none;
        height: 29px;
        border-bottom: 1px solid #444;
        position: relative;
        bottom: 30px;
    }
    #chart #numbers li:last-child {
        height: 30px;
    }
    #chart #numbers li span {
        color: black;
        position: absolute;
        bottom: 0;
        right: 10px;
    }
    #chart #bars {
        display: inline-block;
        background:white;
        width: 90%;
        height: 289px;
        padding: 0;
        margin: 0;
        box-shadow: 0 0 0 1px #444;
    }
    #chart #bars li {
        display: table-cell;
        width: 50px;
        height: 289px;
        margin: 0;
        text-align: center;
        position: relative;
    }
    #chart #bars li .bar {
        display: block;
        width: 80%;
        margin-left: 15px;
        background: #49E;
        position: absolute;
        bottom: 0;
    }
    #chart #bars li .bar:hover {
        background: #5AE;
        cursor: pointer;
    }

    #chart #bars li span {
        color: black;
        width: 100%;
        position: absolute;
        bottom: -2em;
        left: 0;
        text-align: center;

    }


</style>


 
<?php
$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

$arrayArea=array();

?>
<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-body" id="bodyReportTable">
                <p>Evaluación de <b>'<?=$mes[$mesIni] ?>' </b> a <b>'<?=$mes[$mesEnd]?>' del '<?=$periodo?>' </b></p>
                <?php
                $labelSemestreAnual="";
                if($mesIni == 1 && $mesEnd == 6){
                    $labelSemestreAnual="al I Semestre";
                }
                if($mesIni == 1 && $mesEnd == 12){
                    $labelSemestreAnual="Anual";
                }
                $countareas=0;
                $gaArea=0;
                //echo "<pre>".sizeof($dataR);print_r($dataR);exit();
                foreach ($dataR as $dataArea){
                    $countareas=$countareas+1;
                    $gaArea=$gaArea+floatval(round($dataArea["DataArea"]["GaArea"]*100,2));
                    if($dataArea["DataArea"] != null){
                        $d=array(
                            "iduser"=>$dataArea["id_user"],
                            'iddependecia'=>$dataArea["iddependeciauser"],
                            "area"=>$dataArea["nombrearearesponsable"],
                            "ga"=>floatval(round($dataArea["DataArea"]["GaArea"]*100,2))
                        );
                        array_push($arrayArea,$d);
                    ?>

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
                                <?php $gAoP=round($iii["gaop"]*100,2);
                                if( $gAoP<=25 ){
                                    $califica='<b style="font-size: 20px;color: #ef5350"> &#x25CF;<span style="font-size: 12px"> Bajo </span> </b>';
                                }else if($gAoP <= 50 ){
                                    $califica='<b style="font-size: 20px;color: #ffa726"> &#x25CF;<span style="font-size: 12px"> Regular </span>  </b>';
                                }else if($gAoP <= 75){
                                    $califica='<b style="font-size: 20px;color: #c7c361"> &#x25CF;<span style="font-size: 12px"> Buena </span>  </b>';
                                }else if($gAoP <= 100){
                                    $califica='<b style="font-size: 20px;color: #8bc34a"> &#x25CF;<span style="font-size: 12px"> Muy Buena </span> </b>';
                                }

                                ?>
                                Actividad Operativa : <?= htmlentities($iii["actop"]) ?> | <?=$califica?>
                                <table  style="margin-top: 10px; border: 1px solid #ddd; border-collapse: collapse;font-size: 12px; font-family: Arial,sans-Serif;" border="1" width="600"  >
                                    <thead>

                                    <tr>
                                        <th >#</th>

                                        <th>Tarea</th>     <!--<th>M. de <br>Verificacion</th>-->
                                        <th>Unidad de medida</th>
                                        <th>Ejecución Programada <?=$labelSemestreAnual?></th>
                                        <th>Ejecución Acumulada <?=$labelSemestreAnual?> </th>
                                        <th align="center">Ejecución Total %</th>
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
                                            <td style="text-align: right;width: 20px;padding: 2px;" ><?=$j["ga"][0]["metax"]?></td>
                                            <td style="text-align: right;width: 20px;padding: 2px;" ><?= $j["ga"][0]["ejecAll"] ?></td>
                                            <td style="text-align: right;width: 20px;padding: 2px;" ><?= round($ga,2) ?>%</td>
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
                                <p><u>Justificaciones:</u></p>
                                <?php foreach($iii["datatarea"] as  $valTarea ){
                                        foreach ($valTarea["meses"]  as $valMeses){

                                            if(sizeof($valMeses["datajustify"]) > 0){
                                                echo "<b>".$mes[(int)$valMeses["id_tiempo"]].":</b><br>";
                                                foreach ($valMeses["datajustify"] as $valJustify){
                                                    echo "-".$valJustify["motivo"]."<br>";
                                                }
                                            }
                                        }
                                    ?>

                            <?php }  ?>

                                <br>
                                <div id="chart">
                                    <ul id="numbers">
                                        <li><span>100%</span></li>
                                        <li><span>90%</span></li>
                                        <li><span>80%</span></li>
                                        <li><span>70%</span></li>
                                        <li><span>60%</span></li>
                                        <li><span>50%</span></li>
                                        <li><span>40%</span></li>
                                        <li><span>30%</span></li>
                                        <li><span>20%</span></li>
                                        <li><span>10%</span></li>
                                        <li><span>0%</span></li>
                                    </ul>

                                    <ul id="bars">
                                        <?php

                                        foreach ($iii["datatarea"] as $jk => $vk){
                                            $gaini2=$vk ["ga"][0]["porcenajeFinalejecAll"]; //porcenajeFinal
                                            $ga2= floatval(round($gaini2*100,2)) ;

                                            ?>

                                        <li>
                                            <div data-percentage="<?=$ga2?>" class="bar" style="color:white;height: <?=$ga2?>% ;">
                                                <p style="color:black; position: relative; bottom: 15px;">
                                                    <b><?=$ga2?></b></p>
                                            </div>
                                                    <span style="text-align:right ;padding-right: 11px;">
                                                     <b><?=($jk+1)?></b>
                                                    </span></li>

                                        <?php }  ?>

                                    </ul>
                                </div>
                                 <br>
                                <br>

                            <?php }?>


                        <?php }?>

                    <?php } ?>
                  <?php }?>
                <?php }
                $ht="";
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
                    $ht='<h1 style="color:black;font-size: 20px">[ Avance Global del POI: "'.$gaGlobalAreas.'% " - Calificación: ' .$calificacion.']</h1>';
                    }
                }?>
                <?=$ht?>
            </div>
        </div>
    </div>
</div>
<?php

$htTableAreas="<table border='1'>";
$htTableAreas.="<tr><td style='text-align: center' colspan='4'>CUADRO DE CUMPLIMIENTO DE METAS REALIZADAS DURANTE EL AÑO ".$periodo."</td></tr>";
$htTableAreas.="<tr><td>Nro</td><td>UNIDADES ORGÁNICAS </td><td>NIVEL DE AVANCE %</td><td>NIVEL DE AVANCE TOTAL %</td></tr> ";
$arrayAreaPrincipal=$arrayArea;
$ctareas=0;
foreach ($arrayAreaPrincipal as $arrAreas){
    if($arrAreas["iddependecia"]==0 ){
    $ctareas++;
    $htTableAreas.="<tr style='background-color: darkgrey;'><td>".$ctareas."</td><td  >".$arrAreas["area"]."</td><td style='text-align: right'  >".$arrAreas["ga"]."</td></tr>";
    $countHijos=1;
    $SumGaHijos=floatval($arrAreas["ga"]);
     foreach ($arrayArea as $arrDep){
        if($arrAreas["iduser"] == $arrDep["iddependecia"]){
            $countHijos++;
            $ctareas++;
            $SumGaHijos=$SumGaHijos+floatval($arrDep["ga"]);
            $htTableAreas.="<tr><td>".$ctareas."</td><td>".$arrDep["area"]."</td><td style='text-align: right'  >".$arrDep["ga"]."</td></tr>";
        }
    }

        $gaAreaDep=floatval($SumGaHijos/$countHijos);
        $htTableAreas.="<tr><td></td><td></td><td></td><td style='text-align: right' >".round($gaAreaDep,2)."</td></tr>"    ;
    }
}
$htTableAreas.="</table>";


echo $htTableAreas;
echo $_css ?>
<?php echo $_js?>