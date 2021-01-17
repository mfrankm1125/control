
<table border="0" style="width: 800px">
    <tr>
        <td></td>
        <td></td>
        <td>Kardex - Semilla - <?=$kardex[0]["cultivo"]?> <br>
            <?=$kardex[0]["cultivar"]?><br>
            <?=$kardex[0]["clasecultivo"]?> - <?=$kardex[0]["catcultivo"]?></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>Campaña:</td>
        <td><?=$kardex[0]["anio"]?></td>
        <td></td>
    </tr>
    <tr>
        <td>Fecha Ingreso:</td>
        <td><?=date("d-m-Y",strtotime($kardex[0]["fechaenvioalmacen"]))?></td>
        <td></td>
    </tr>
    <tr>
        <td>Cantidad Semilla:</td>
        <td><?= $kardex[0]["semillavendible"]?></td>
        <td></td>
    </tr>
    <tr>
        <td> Precio(kg):</td>
        <td> </td>
        <td></td>
    </tr>
    <tr>
        <td> Lote:</td>
        <td><?=$kardex[0]["nroloteproduccion"]?></td>
        <td></td>
    </tr>
    <tr>
        <td> Sacos (<?= $kardex[0]["kgsaco"]?>kg):</td>
        <td><?=  round($kardex[0]["semillavendible"]/$kardex[0]["kgsaco"],2) ?></td>
        <td></td>
    </tr>

</table>

<table border="1">
    <thead>

    <tr>
        <th class="text-center" rowspan="2" >Fecha</th>
        <th  rowspan="2">Documento</th>
        <th rowspan="2" >Cliente</th>
        <th colspan="2" > Ingreso de Semilla </th>
        <th colspan="2" > Salida de Semilla </th>
        <th colspan="2" > Saldo de Semilla </th>
    </tr>
    <tr>
        <th>Kg</th>
        <th>Saco</th>
        <th>Kg</th>
        <th>Saco</th>
        <th>Kg</th>
        <th>Saco</th>
    </tr>
    </thead>
    <tbody>
    <?php    $histoSaldo=$kardex[0]["semillavendible"];

    foreach($detkardex as $i){
     $montosalidaproducto=0;
     $montoingresoproducto=0;
    if($i["idtipoflujo"] == 1){
        $montosalidaproducto=$i["cantidad"];
        $histoSaldo=$histoSaldo-$montosalidaproducto;
    }else{
        $montoingresoproducto=$i["cantidad"];
        $histoSaldo=$histoSaldo+$montoingresoproducto;
    }

    ?>
    <tr>
        <td><input type="date" style="border: 0"   readonly="readonly" value="<?= $i["fechareg"] ?>"></td>
        <td><?= $i["nrodoc"]  ?></td>
        <td><?= $i["nombre"] ?></td>
        <td><?= $montoingresoproducto ?></td>
        <td><?= round($montoingresoproducto/$kardex[0]["kgsaco"],2)  ?></td>
        <td><?= $montosalidaproducto ?></td>
        <td><?= round($montosalidaproducto/$kardex[0]["kgsaco"],2)  ?></td>
        <td><?= $histoSaldo ?></td>
        <td><?= round($histoSaldo/$kardex[0]["kgsaco"],2)  ?></td>
    </tr>
    <?php }?>
    </tbody>
</table>