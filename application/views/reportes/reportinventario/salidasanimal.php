<?php if(sizeof($data)>0){ ?>
<p style="font-weight: bold;font-size: 18px;" >CUADRO DE SALIDA DE ANIMALES</p>
<p style="font-weight: bold;font-size: 17px;">E.E.A "EL PORVENIR"</p>
<p style="font-weight: bold;font-size: 16px;" >Del <?=date("d/m/Y",strtotime($fechaini))?> al <?=date("d/m/Y",strtotime($fechaend))?> </p>


<table style="border: 1px solid #ddd; border-collapse: collapse;" border="1" >
    <?php
      $total=0;
    foreach ($data as $k=>$v){  ?>
    <tr>
        <td style="text-align: center; font-size: 20px;font-weight: bold" colspan="6"><?=$v["tipoanimal"]?></td>
    </tr>
    <tr style="font-weight: bold;">
        <td style="font-weight: bold;" >Nro</td>
        <td style="font-weight: bold;" >Fecha venta</td>
        <td style="font-weight: bold;" >Clase</td>
        <td style="font-weight: bold;" >Nro de Identificación</td>
        <td style="font-weight: bold;" >Total S/.</td>

        <td style="font-weight: bold;" >Nombre del comprador</td>
    </tr>
  <?php $c=0;$totaltipo=0;
        foreach ($v["datasalida"] as $kk=>$vv){ $c++;$totaltipo=$totaltipo+floatval($vv["precio"]); ?>
            <tr>
                <td style="padding: 5px;" ><?=$c?></td>
                <td style="padding: 5px;" ><?=date("d/m/Y",strtotime($vv["fechasalida"]))?></td>
                <td style="padding: 5px;" ><?=$vv["claseanimal"]?></td>
                <td style="padding: 5px;" ><?=$vv["codanimal"]?></td>
                <td style="text-align: right"><?=number_format($vv["precio"],2,',',' ') ?></td>

                <td style="padding: 5px;" ><?=$vv["cliente"]?></td>
            </tr>
        <?php } $total=$total+$totaltipo; ?>
        <tr>
            <td colspan="3" style="text-align: right;font-size: 18px;font-weight: bold;padding: 5px;">Total</td>
            <td colspan="3" style="font-size: 18px;font-weight: bold;padding: 5px;" > S/. <?=number_format($totaltipo,2,',',' ')?></td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="3" style="text-align: right;font-size: 20px;font-weight: bold;padding: 5px;">Total General</td>
        <td colspan="3" style="font-size: 20px;font-weight: bold;padding: 5px;" > S/. <?=number_format($total,2,',',' ')?></td>
    </tr>
</table>

<?php } else{
    echo "<h3>Sin Datos para estas fechas ".date("d/m/Y",strtotime($fechaini))." al ".date("d/m/Y",strtotime($fechaend))." </h3>";
} ?>
