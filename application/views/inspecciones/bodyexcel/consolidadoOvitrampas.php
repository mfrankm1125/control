<table  border="1" style="width: 2000px">
    <thead>
    <tr  >
        <th rowspan="2" >DISTRITO</th>
        <th rowspan="2" >LOCALIDAD (EE.SS)</th>
        <th rowspan="2" >CODIGO OVITRAMPA</th>
        <th rowspan="2" >DIRECCIÓN DE VIVIENDA</th>
        <th rowspan="2" >UBICACIÓN OVITRAMPA</th>
        <th rowspan="2" >SECTOR</th>
        <th rowspan="2" >LONGITUD (W)</th>
        <th rowspan="2">LATITUD (S)</th>
        <th rowspan="2" >ALTITUD (msnm)</th>
        <?php for($i=1; $i <= 53 ;$i++){?>
            <th> <u>Fecha </u> <br> SE <?=$i?> </th>
        <?php }?>
    </tr>

    </thead>
    <tbody id="tbodyConsolidadoVigilanciaOvi">
        <?=$ht?>
    </tbody>
</table>