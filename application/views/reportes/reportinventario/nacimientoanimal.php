<?php
if(sizeof($data)> 0){

    foreach ($data as $k=>$v){?>

    <p><?=$v["tipoanimal"]?> - del <?=date("d/m/Y",strtotime($fechaini))?> al <?=date("d/m/Y",strtotime($fechaend))?> </p>
    <p>NACIMIENTOS</p>

    <table  style="border: 1px solid #ddd; border-collapse: collapse;" border="1" >
        <tr>
            <td>Nro de orden</td>
            <td>Fecha de nacimiento</td>
            <td>Nro de Animal</td>
            <td>Raza</td>
            <td>Sexo</td>
            <td>Peso nacimiento</td>
            <td>Nro de Madre</td>
            <td>Nro de Padre</td>
        </tr>
        <?php $c=0; foreach($v["dataanimales"] as $kk=>$vv){ $c++;?>
            <tr>
                <td><?=$c++?></td>
                <td><?=date("d/m/Y",strtotime($vv["fechanacimiento"]))?></td>
                <td><?=$vv["codanimal"]?></td>
                <td><?=$vv["codraza"]?></td>
                <td><?= ($vv["sexo"] == 1) ?  "Macho" :  "Hembra" ; ?></td>
                <td><?=$vv["pesonace"]?></td>
                <td><?=$vv["idanimalmadre"]?></td>
                <td><?=$vv["idanimalpadre"]?></td>
            </tr>

        <?php }?>

    </table>
    <?php }?>
<?php }else{echo "<h3>Sin Datos para estas fechas ".date("d/m/Y",strtotime($fechaini))." al ".date("d/m/Y",strtotime($fechaend))." </h3>";}?>
