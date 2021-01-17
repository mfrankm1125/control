<h3>Del <?=   date("d/m/Y", strtotime($data[0]["fini"])); ?> al <?=date("d/m/Y", strtotime($data[0]["fend"])); ?></h3>
<h3>-</h3>
<?php foreach($data as $dt ){ ?>


    <h3><?=$dt["ruc"]?>-<?=$dt["razonsocial"]?></h3>
    <table border="1">
        <thead>
        <tr>
            <th>#</th>
            <th>Serie Correlativo</th>
            <th>Moneda</th>
            <th>Monto</th>
            <th>Fecha</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach($dt["detalle"] as $k=>$dtdet){
             $color="#85898d";
             if($dtdet["idtipodoc"]=="3"){
                 $color="#9dd79d";
             }else if($dtdet["idtipodoc"]=="1"){
                 $color="#80a0f5";
             }
            ?>

            <tr style="background-color:  <?=$color;?>">
                <td><?=($k+1)?></td>
                <td><?=$dtdet["seriecorrelativo"] ?></td>
                <td><?=$dtdet["moneda"] ?></td>
                <td><?=$dtdet["exonerado"] ?></td>
                <td><?=$dtdet["fecharegistroorigenformt"] ?></td>

            </tr>

        <?php }?>

        </tbody>
    </table>

<?php  } ?>

