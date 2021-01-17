<P style="font-weight: bold;font-size: 20px;"  >Registro de Salidas</P>

<p style="font-weight: bold;font-size: 16px;" >Del <?=date("d/m/Y",strtotime($fechaini))?> al <?=date("d/m/Y",strtotime($fechaend))?> </p>

<?php if(sizeof($data) > 0 ){
    $totalventa=0;
    ?>


    <?php foreach ($data as $k=>$v){ ?>
        <p style="font-weight: bold;font-size: 18px;" >-<?=$v["tiposalida"]?></p>
        <?php foreach ($v["dataproducto"] as $kk=>$vv){ ?>
            <p>Tipo:<?=$vv["tipoproducto"]?></p>

            <?php if( (strtolower($v["tiposalida"]) == "venta" && strtolower($vv["tipoproducto"]) == "animal") ||  (strtolower($v["tiposalida"]) == "muerte" && strtolower($vv["tipoproducto"]) == "animal") ||  (strtolower($v["tiposalida"]) == "donación" && strtolower($vv["tipoproducto"]) == "animal")  ) {?>

                <table style="border: 1px solid #ddd; border-collapse: collapse;" border="1" >
                <tr style="font-weight: bold;">
                    <td style="font-weight: bold;" >Nro</td>
                    <td style="font-weight: bold;" >Fecha venta</td>
                    <td style="font-weight: bold;" >Motivo</td>
                    <td style="font-weight: bold;" >Nro Id.</td>
                    <td style="font-weight: bold;" >Raza</td>
                    <td style="font-weight: bold;" >Clase</td>
                    <?php if(strtolower($v["tiposalida"]) == "venta" && strtolower($vv["tipoproducto"]) == "animal"){ ?>
                        <td style="font-weight: bold;" >Nombre del comprador</td>
                        <td style="font-weight: bold;" >Total S/.</td>
                     <?php  }?>
                    <?php if(strtolower($v["tiposalida"]) == "muerte" && strtolower($vv["tipoproducto"]) == "animal"){ ?>

                    <?php  }?>
                    <?php if(strtolower($v["tiposalida"]) == "donación" && strtolower($vv["tipoproducto"]) == "animal"){ ?>
                        <td style="font-weight: bold;" >Donado a</td>
                    <?php  }?>


                </tr>
                <?php $sbtotal=0;

                      foreach ($vv["dataSalida"] as $kkk=>$vvv){
                                  $sbt=$vvv["cantidad"]* $vvv["precio"];
                                  $sbtotal=$sbtotal+$sbt;
                              if( (strtolower($v["tiposalida"]) == "venta" && strtolower($vv["tipoproducto"]) == "animal")){

                                     $totalventa=$totalventa+$sbt;
                              }


                          ?>

                    <tr>
                        <td style="padding: 5px;" ><?=($kkk+1)?></td>
                        <td style="padding: 5px;" ><?=date("d/m/Y", strtotime($vvv["fechasalida"]))?></td>
                        <td style="padding: 5px;" ><?=$vvv["motivosalida"]?></td>
                        <td style="padding: 5px;" ><?=$vvv["codanimal"]?></td>
                        <td style="padding: 5px;" ><?=$vvv["codraza"]?></td>
                        <td style="padding: 5px;" ><?=$vvv["claseanimal"]?></td>
                        <?php if(strtolower($v["tiposalida"]) == "venta" && strtolower($vv["tipoproducto"]) == "animal"){ ?>
                            <td style="text-align: right"><?=$vvv["cliente"] ?></td>
                            <td style="padding: 5px;" ><?=number_format($vvv["precio"],2,',',' ')?></td>
                        <?php  }?>
                        <?php if(strtolower($v["tiposalida"]) == "muerte" && strtolower($vv["tipoproducto"]) == "animal"){ ?>

                        <?php  }?>
                        <?php if(strtolower($v["tiposalida"]) == "donación" && strtolower($vv["tipoproducto"]) == "animal"){ ?>
                            <td style="text-align: right"><?=$vvv["cliente"] ?></td>
                        <?php  }?>

                    </tr>

                <?php }?>

                    <?php if( (strtolower($v["tiposalida"]) == "venta" && strtolower($vv["tipoproducto"]) == "animal")){?>
                    <tr>
                        <td style="text-align: right;"    colspan="7">Totales</td>
                        <td style="text-align: right;"   ><?=number_format($sbtotal,2,',',' ')?></td>
                    </tr>
                    <?php  } ?>

                </table>
            <?php }?>

            <?php if((strtolower($v["tiposalida"]) == "venta" && strtolower($vv["tipoproducto"]) == "leche") || (strtolower($v["tiposalida"]) == "donación" && strtolower($vv["tipoproducto"]) == "leche")   ){ ?>

                <table style="border: 1px solid #ddd; border-collapse: collapse;padding: 3px;" border="1" >
                <tr>
                    <td>#</td>
                    <td>Fecha</td>
                    <td>Cliente</td>
                    <td>Cantidad(ltrs.)</td>
                    <td>Precio(S/.)</td>
                    <td>Total (S/.)</td>
                </tr>
                    <?php $stotal=0;$scantidad=0;
                    foreach ($vv["dataSalida"] as $kx=>$vx){

                        $totals=$vx["cantidad"]* $vx["precio"];
                        $scantidad=$scantidad+$vx["cantidad"];
                        $stotal=$stotal+$totals;
                        if( (strtolower($v["tiposalida"]) == "venta" && strtolower($vv["tipoproducto"]) == "leche")){

                            $totalventa=$totalventa+$totals;
                        }
                        ?>
                        <tr>
                            <td style="padding: 5px;" ><?=($kx+1)?></td>
                            <td style="padding: 5px;" ><?=date("d/m/Y", strtotime($vx["fechasalida"]))?></td>
                            <td style="padding: 5px;" ><?=$vx["cliente"]?></td>
                            <td style="text-align: right;padding: 5px;"><?=number_format($vx["cantidad"],2,',',' ') ?> </td>
                            <td style="text-align: right;padding: 5px;"><?=number_format($vx["precio"],2,',',' ') ?> </td>
                            <td style="text-align: right;padding: 5px;" ><?=number_format($totals,2,',',' ')?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="3">Totales</td>
                        <td style="text-align: right;"  ><?=number_format($scantidad,2,',',' ')?> </td>
                        <td style="text-align: right;" colspan="2" ><?=number_format($stotal,2,',',' ')?></td>
                    </tr>
                </table>
            <?php }?>

        <?php }?>


    <?php }?>
    <p style="font-size: 20px;font-weight: bold;"> Resumen: Total Venta S/. <?=number_format($totalventa,2,',',' ')?></p>

<?php }else{echo "Sin datos" ;}  ?>