<?php /*
header("Pragma: public");
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
//header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=test.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");

*/
?>

<table border="1" width="800" cellpadding="2">
    <thead>
    <tr>
        <td colspan="2">
             15.3846%
        </td>
        <td colspan="3">

        </td>
    </tr>
    <tr>
        <th  >Actividad </th>
        <td colspan="4">GESTION ADMINISTRATIVA</td>

    </tr>
    <tr>
        <th  >U.M</th>
        <td colspan="4">ACCION</td>

    </tr>
    <tr>
        <th >Mes</th>
        <th>Ejecutado</th>
        <th>Meta</th>
        <!--<th>M. de <br>Verificacion</th>-->
        <th>Estado</th>
        <th><?php echo htmlentities("Acción")?></th>
        <!--<th>Obs.</th> -->

    </tr>
    </thead>

    <tr>
        <td>

            11
        </td>

        <td >
            -
        </td>
        <td   >
            -
        </td>
        <td  >
            -

        </td>

        <td  >
            -
        </td>
    </tr> <tr>
        <td>
            12
        </td>

        <td>
            <?php  echo str_replace(".", ",",   0.00);?>


        </td>
        <td>
            <?php  echo str_replace(".", ",", 40.00);?>
        </td>
        <td  >

        </td>

        <td  >

        </td>
    </tr><tr>
        <td> <b>Total</b></td>
        <td  ><b>8</b> </td>
        <td  ><b>52</b> </td>
        <td colspan="2"></td>
    </tr>


</table>


<?php /*
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=file.csv");
header("Pragma: no-cache");
header("Expires: 0"); */ ?>