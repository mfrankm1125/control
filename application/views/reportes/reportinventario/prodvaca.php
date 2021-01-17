<?php if(sizeof($data)>0){ ?>
<p style="font-weight: bold;font-size: 18px;" >Rendimiento Vacas</p>
<p style="font-weight: bold;font-size: 17px;">E.E.A "EL PORVENIR"</p>
<p style="font-weight: bold;font-size: 16px;" >Del <?=date("d/m/Y",strtotime($fechaini))?> al <?=date("d/m/Y",strtotime($fechaend))?> </p>
<table style="border: 1px solid #ddd; border-collapse: collapse;" border="1" >
    <tr>
        <td>Cod Animal</td>
        <td>Raza</td>
        <td>Recria</td>
        <td>Total Vendible</td>
        <td>Total</td>

    </tr>
    <?php foreach($data as $k=>$v){ ?>
        <tr>
            <td><?=$v["codanimal"]?></td>
            <td><?=$v["codraza"]?></td>
            <td><?=number_format($v["sumrecria"],2,',',' ') ?></td>
            <td><?=number_format($v["totalvendible"],2,',',' ') ?></td>
            <td><?=number_format($v["total"],2,',',' ') ?></td>
        </tr>
    <?php } ?>
</table>

<?php } else{
    echo "<h3>Sin Datos para estas fechas ".date("d/m/Y",strtotime($fechaini))." al ".date("d/m/Y",strtotime($fechaend))." </h3>";
}  ?>

