<p>PRODUCCIÓN DE LECHE POR RAZAS  </p>
<p>ESTACIÓN EXPERIMENTAL AGRARIA "EL PORVENIR" </p>
<p>Rendimiento por raza </p>
<p>Del: <?=$fechaini?> al  <?=$fechaend ?> </p>
<?php  // echo "<pre>";print_r($data);exit(); ?>
<table style="border: 1px solid #ddd; border-collapse: collapse;padding: 3px;" border="1" >
    <tr>
        <td>#</td>
        <td style="text-align: center;padding: 5px;">Raza</td>
        <td style="text-align: center;padding: 5px;" >Cod Animal</td>
        <td style="text-align: center;padding: 5px;" >Días Producidos</td>
        <td style="text-align: center;padding: 5px;" >Prod. Mañana</td>
        <td style="text-align: center;padding: 5px;" >Prod. Tarde</td>
        <td style="text-align: center;padding: 5px;" >Cant. Recría</td>
        <td style="text-align: center;padding: 5px;" >Total Prod</td>
        <td style="text-align: center;padding: 5px;" >Total Vendible</td>
        <td style="text-align: center;padding: 5px;" >Prom Sin Recria</td>
        <td style="text-align: center;padding: 5px;" >Prom Con Recria</td>
    </tr>
     <?php foreach($data as $k=>$v) { ?>
         <tr>
          <td></td>
          <td colspan=""><?=$v["raza"]?></td>
          <td colspan="8"></td>
         <tr>
         <?php $cantidadAnimalesXRaza=0;
               $sumPromedioConRecria=0;
               $sumPromedioSinRecria=0;
               $PromedioConRecria=0;
               $PromedioSinRecria=0;
         foreach($v["data"] as $kk=>$ii){
                 $AvgConRecria= $ii["tconrecria"]/$ii["diasproducidos"] ;
                 $AvgConRecria=round($AvgConRecria,2);
                 $sumPromedioConRecria=$sumPromedioConRecria+$AvgConRecria;
                 $AvgSinRecria= $ii["tsinrecria"]/$ii["diasproducidos"] ;
                 $AvgSinRecria=round($AvgSinRecria,2);
                 $sumPromedioSinRecria=$sumPromedioSinRecria+$AvgSinRecria;
                 $cantidadAnimalesXRaza++;
             ?>
             <tr>
                 <td style="text-align: center ;padding: 5px;" ><?= ($kk+1)?></td>
                 <td style="text-align: center ;padding: 5px;" > -</td>
                 <td style="text-align: center;padding: 5px;" ><?= $ii["codanimal"]?></td>
                 <td style="text-align: center;padding: 5px;" ><?= $ii["diasproducidos"]."/".$ii["diasentre"] ?></td>
                 <td style="text-align: right;padding: 5px;"><?=  number_format($ii["sprodmaniana"],2,',',' ') ?> </td>
                 <td style="text-align: right;padding: 5px;" ><?=  number_format($ii["sprodtarde"],2,',',' ') ?> </td>
                 <td style="text-align: right;padding: 5px;" ><?=  number_format($ii["sprodrecria"],2,',',' ') ?> </td>
                 <td style="text-align: right;padding: 5px;" ><?=   number_format($ii["tconrecria"],2,',',' ') ?> </td>
                 <td style="text-align: right;padding: 5px;" ><?= number_format($ii["tsinrecria"],2,',',' ') ?> </td>
                 <td style="text-align: right;padding: 5px;" ><?=  number_format($AvgSinRecria,2,',',' ')  ?> </td>
                 <td style="text-align: right;padding: 5px;" ><?=  number_format($AvgConRecria, 2,',',' ')  ?></td>
             </tr>
         <?php }
         $PromedioConRecria=$sumPromedioConRecria/$cantidadAnimalesXRaza;
         $PromedioConRecria=round($PromedioConRecria,2);
         $PromedioSinRecria=$sumPromedioSinRecria/$cantidadAnimalesXRaza;
         ?>
            <tr>
                <td style="text-align: right;padding: 5px;" ></td>
                <td  style="text-align: right;padding: 5px;" colspan=""></td>
                <td  style="text-align: right;padding: 5px;" colspan="7">Promedios: </td>
                <td  style="text-align: right;padding: 5px;" ><?=number_format($PromedioSinRecria,2,',',' ')?></td>
                <td style="text-align: right;padding: 5px;" ><?=number_format($PromedioConRecria,2,',',' ') ?></td>

            <tr>
     <?php }?>

</table>
