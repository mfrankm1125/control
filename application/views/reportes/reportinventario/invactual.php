<P>INVENTARIO DE CRIANZA</P>
<p>EXISTENCIA DE ANIMALES EN LA ESTACION EXPERIMENTAL</p>
<p>"EL PORVENIR" AL <?=date("d/m/Y",strtotime($fechareport))?> </p>
<?php if(sizeof($data) > 0 ){  ?>
    <?php foreach($data as $kk=>$vv){  ?>
        <b><?=$vv["tipoanimal"]?></b>
        <table style="border: 1px solid #ddd; border-collapse: collapse;" border="1" >
            <tr>
                <td>Cantidad Total</td>
                <td>Nro de orden</td>
                <td>Nro de identificacion</td>
                <td>Fecha Nacimiento</td>
                <td>Raza o cruze</td>
            </tr>
            <?php
                $c=0;

                foreach ($vv["dataclases"] as $key=>$value){?>
                    <tr>
                    <td colspan="5" style="text-align: center"><b style="font-size: 20px;"><?=$value["claseanimal"]?></b></td>
                    </tr>
                    <?php
                     $cc=0;
                     foreach($value["animales"] as $k=>$v){ $c++;$cc++;?>
                        <tr>
                            <td><?=$c?></td>
                            <td><?=$cc ?></td>
                            <td><?=$v["codanimal"]?></td>
                            <td><?=date("d/m/Y",strtotime($v["fechanacimiento"]))?></td>
                            <td><?=$v["codraza"]?></td>
                        </tr>
                    <?php }?>
            <?php }?>
        </table >
    <?php } ?>
<?php }else{echo "Sin datos" ;}  ?>
