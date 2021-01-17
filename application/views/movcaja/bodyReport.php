<h3>Reporte de caja del <?= date("d/m/Y", strtotime($fini)); ?> -  <?=date("d/m/Y", strtotime($fend))?>  </h3>
<table   border="1" cellspacing="0"  class="table table-bordered table-condensed" >
    <thead>
    <tr>
        <th>*</th>
        <th>Tipo</th>
        <th>Mov</th>
        <th>Desc</th>
        <th>Mov.Dol</th>
        <th>Fecha</th>
        <th style="text-al$ign: r$ight;">Ingreso</th>
        <th style="text-al$ign: r$ight;">Egreso</th>
        <!--<th style="text-al$ign: r$ight;">Salida</th>-->
    </tr>
    </thead>
    <tbody>
    <?php  $movdolaresxxxxx_=0;
    $ttIngresoSoles=0;
    $ttIngresoDolares=0;

    $ttEgresoSoles=0;
    $ttEgresoDolares=0;

    $ttInversionSoles=0;
    $ttInversionDolares=0;

    foreach($data as $k=>$i){

    if( $i["nmoneda"] =="Soles" ){
        $ig= $i["ingreso"];
        $ig= floatval(substr($ig,2));
        if($ig != 0){
        }else{
        $ig=0;
        }

        $eg=$i["egreso"];
        $eg= floatval(substr($eg,2));
        if($eg != 0){
        }else{
           $eg=0;
        }

        $inv=0;
        if($inv != 0 ){
            $inv=floatval(substr($inv,2));
        }else{
          $inv=floatval($inv);
        }

        $ttIngresoSoles=$ttIngresoSoles+floatval($ig);
        $ttEgresoSoles=$ttEgresoSoles+floatval($eg);
        $ttInversionSoles=$ttInversionSoles+floatval($inv);

    }else if($i["nmoneda"] =="Dolares"){

        $ig= $i["ingreso"];
        if($ig != 0){
        $ig=floatval(substr($ig,1));
        }else{
        $ig=floatval($ig);
        }

        $eg= $i["egreso"];
        if($eg != 0){
        $eg=floatval(substr($eg,1)) ;
        }else{
        $eg=floatval( $eg);
        }

        $inv=$i["inversion"];
        if($inv != 0 ){
        $inv=floatval(substr($inv,1)) ;
        }else{
        $inv=floatval($inv);
        }

    $ttIngresoDolares=$ttIngresoDolares+floatval($ig );
    $ttEgresoDolares=$ttEgresoDolares+floatval($eg);
    $ttInversionDolares=$ttInversionDolares+floatval($inv);

    }


    ?>
    <tr style="font-size: 12px;" >
        <td><?=($k+1)?></td>
        <td   ><?=$i["ntipoconceptocaja"]?></td>
        <td  ><?=$i["nconceptocaja"] ?></td>
        <td><?=$i["descmovcaja"]?></td>
        <td style="text-align: right;">

            <?php $sinbol="";
            $color_="";

            if($i["idtipoconceptocaja"] == "1"){
                if($i["idconceptocaja"] == 1 || $i["idconceptocaja"] == 5){
                $sinbol="+";
                $color_="green;";
                $movdolaresxxxxx_=$movdolaresxxxxx_+ floatval($i["montodolar"]);

                }else if($i["idconceptocaja"] == 3){
                $sinbol="-";
                $color_="red;";
                $movdolaresxxxxx_=$movdolaresxxxxx_-  floatval($i["montodolar"]);

                }

            }else{

                if($i["idconceptocaja"] == 4){
                $sinbol="+";
                $color_="green";
                $movdolaresxxxxx_=$movdolaresxxxxx_ + floatval($i["montodolar"]);
                }

            }
            ?>
            <?php if( floatval($i["montodolar"]) > 0   ){
                setlocale(LC_MONETARY, 'es_PE');
                ?>
            <b style='color:<?=$color_?>;text-align: right;' ><?=$sinbol." $". number_format($i["montodolar"],2,'.',' ') ?> </b>
            <?php }else{ echo 0.00;} ?>


        </td>
        <td><?=$i["fecharegistro"] ?></td>
        <td  style="text-align: right; " class="tdIngreso"  ><b style="color: darkgreen;" ><?=  $i["ingreso"]  ?></b></td>
        <!--<td class="tdInversion" ><?=$i["inversion"]?></td>-->
        <td style="text-align: right; " class="tdEgreso"  ><b style="color: red;" ><?=$i["egreso"]?></b></td>
    </tr>
    <?php }


    $valTasaCambio=3.30;
    $ttDolaresInSolesI=0;
    $ttDolaresInSolesInv=0;
    $ttDolaresInSolesE=0;

    $ttFinalInSolesI=0;
    $ttFinalInSolesInv=0;
    $ttFinalInSolesE=0;

    ?>
    </tbody>
    <tfoot style="border-top: 2px solid black;" >
    <tr style="text-align: right" >
        <td>*</td>
        <td> </td>
        <td> </td>
        <td>Total: </td>
        <td>$<?=$movdolaresxxxxx_?> </td>
        <td>Total S/</td>
        <td style="text-align: right;font-weight: bold; " >
            <input type="hidden" id="$ttIngresoSoles" value="<?=$ttIngresoSoles?>" >
            <input type="hidden" id="$ttInversionSoles" value="<?=$ttInversionSoles?>" >
            <input type="hidden" id="$ttEgresoSoles" value="<?=$ttEgresoSoles?>" >
            S/<?=$ttIngresoSoles?>
        </td>
        <!--<td style="text-al$ign: r$ight;font-we$ight: bold; " >S/<?=$ttInversionSoles?></td>-->
        <td style="text-align: right;font-weight: bold; " >S/<?=$ttEgresoSoles?></td>
    </tr>



    <tr style="text-align: right">
        <td>*</td>
        <td> </td>
        <td> </td>
        <td>Stock Histórico </td>
        <td>$<?=$dataStockDolares?> </td>
        <td>INGRESO-EGRESO </td>
        <td style="text-al$ign: r$ight;font-we$ight: bold;" id="Ingreso-Egreso"  >S/ <?=$ttIngresoSoles - $ttEgresoSoles ?></td>
        <td style="text-al$ign: r$ight;font-we$ight: bold;" > </td>



    </tr>
    <tr style="text-align: right" >
        <td>*</td>
        <td> </td>
        <td> </td>
        <td><b>Stock Dolares</b> </td>
        <td><b>$<?=(floatval($dataStockDolares)+ floatval($movdolaresxxxxx_))?> </b></td>
        <td><b>Stock Soles </b></td>
        <td style="text-al$ign: r$ight;font-we$ight: bold;" id="Ingreso-Egreso"  ><b>S/ <?=$ttIngresoSoles - $ttEgresoSoles ?> </b></td>
        <td style="text-al$ign: r$ight;font-we$ight: bold;" > </td>



    </tr>
    </tfoot>
</table>