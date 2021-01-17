
<p>PRODUCCIÓN Y DISTRIBUCIÓN DE LECHE(ltrs.)</p>
<p>ESTACIÓN EXPERIMENTAL AGRARIA "EL PORVENIR" </p>
<p>Del:  <?= date("d/m/Y", strtotime($fechaini)); ?> al <?= date("d/m/Y", strtotime($fechaend));?>  </p>
<p>PRODUCCIÓN</p>
<table style="border: 1px solid #ddd; border-collapse: collapse;padding: 3px;" border="1" >
    <tr>
        <td>Fecha</td>
        <td>Mañana</td>
        <td>Tarde</td>
        <td>Recría</td>
        <td>Total</td>
        <td>Total Vendible</td>
    </tr>
    <?php
    $tmaniana=0;
    $ttarde=0;
    $trecria=0;
    $ttotal=0;
    $ttotalvendible=0;
    foreach($data as $k=>$v){
        $tmaniana=$tmaniana+ $v["tmaniana"];
        $ttarde=$ttarde+$v["ttarde"];
        $trecria=$trecria+$v["recria"];
        $subtotal= $v["tmaniana"]+$v["ttarde"];
        $ttotalvendible=$ttotalvendible+$subtotal;
        $total=$subtotal+$v["recria"];
        $ttotal=$ttotal+$total;
        ?>
    <tr>
        <td><?=date("d/m/Y", strtotime($v["fechaprodleche"]));?></td>
        <td style="text-align: right;padding: 5px;"><?=number_format($v["tmaniana"],2,',',' ')?></td>
        <td style="text-align: right;padding: 5px;" ><?=number_format($v["ttarde"],2,',',' ')?></td>
        <td style="text-align: right;padding: 5px;" ><?=number_format($v["recria"],2,',',' ')?></td>
        <td style="text-align: right;padding: 5px;" ><?=number_format($total,2,',',' ')?></td>
        <td style="text-align: right;padding: 5px;" ><?=number_format($subtotal,2,',',' ')?></td>
    </tr>
    <?php } ?>
    <tr>
        <td>Totales </td>
        <td style="text-align: right;padding: 5px;"><?=number_format($tmaniana,2,',',' ')?></td>
        <td style="text-align: right;padding: 5px;" ><?=number_format($ttarde,2,',',' ')?></td>
        <td style="text-align: right;padding: 5px;" ><?=number_format($trecria,2,',',' ')?></td>
        <td style="text-align: right;padding: 5px;" ><?=number_format($ttotal,2,',',' ')?></td>
        <td style="text-align: right;padding: 5px;" ><?=number_format($ttotalvendible,2,',',' ')?></td>
    </tr>

</table>
<br>
<p>DISTRIBUCIÓN</p>
<table style="border: 1px solid #ddd; border-collapse: collapse;padding: 3px;" border="1" >
    <tr>
        <td>#</td>
        <td>Fecha</td>
        <td>Cliente</td>
        <td>Cantidad(ltrs.)</td>
        <td>Total (S/.)</td>
    </tr>
    <?php $stotal=0;$scantidad=0;
        foreach ($dataSalida as $k=>$v){
          $totals=$v["cantidad"]* $v["precio"];
            $scantidad=$scantidad+$v["cantidad"];
            $stotal=$stotal+$totals;
        ?>
        <tr>
            <td style="padding: 5px;" ><?=($k+1)?></td>
            <td style="padding: 5px;" ><?=date("d/m/Y", strtotime($v["fechasalida"]))?></td>
            <td style="padding: 5px;" ><?=$v["cliente"]?></td>
            <td style="text-align: right;padding: 5px;"><?=number_format($v["cantidad"],2,',',' ') ?> </td>
            <td style="text-align: right;padding: 5px;" ><?=number_format($totals,2,',',' ')?></td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="3">Totales</td>
        <td style="text-align: right;" ><?=number_format($scantidad,2,',',' ')?> </td>
        <td style="text-align: right;" ><?=number_format($stotal,2,',',' ')?></td>
    </tr>
</table>

<br>
<p>RESUMEN</p>
<table style="border: 1px solid #ddd; border-collapse: collapse;padding: 3px;" border="1" >
    <tr>
        <td colspan="5">Producción</td>

    </tr>
    <tr>
        <td style="text-align: center;padding: 5px;" >Mañana</td>
        <td style="text-align: center;padding: 5px;" >Tarde</td>
        <td style="text-align: center;padding: 5px;" >Recria</td>
        <td style="text-align: center;padding: 5px;" >Total</td>
        <td style="text-align: center;padding: 5px;" >Total Vendible</td>
    </tr>
    <tr>
        <td  style="text-align: right;padding: 5px;"><?=number_format($tmaniana,2,',',' ')?></td>
        <td style="text-align: right;padding: 5px;"><?=number_format($ttarde,2,',',' ')?></td>
        <td style="text-align: right;padding: 5px;"><?=number_format($trecria,2,',',' ')?></td>
        <td style="text-align: right;padding: 5px;" ><?=number_format($ttotal,2,',',' ')?></td>
        <td style="text-align: right;padding: 5px;"><?=number_format(($tmaniana+$ttarde),2,',',' ')?></td>
    </tr>
    <tr>
        <td colspan="5">Distribución </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;" >Litros</td>
        <td colspan="3" style="text-align: center;" >Total Recaudado(S/.)</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: right;padding: 5px;" ><?=number_format($scantidad,2,',',' ')?></td>
        <td colspan="3" style="text-align: right;padding: 5px;" ><?=number_format($stotal,2,',',' ')?></td>
    </tr>
</table>