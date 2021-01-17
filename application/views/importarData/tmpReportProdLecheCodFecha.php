<table id="reportProdLecheCodFecha"  cellspacing="0" width="100%" >

    <tr>
        <?php
        $iniVal=0;
        $iniArrFecha=null;
        foreach($dt["fechas"] as $va){
            $iniVal++;
            $iniArrFecha[]=$va["fechaprodleche"];
            ?>
            <?php if($iniVal == 1){ ?>
                <td style="padding: 0px;" >
                    <table  border="1"  style="margin-bottom:0px;width: 100%">
                        <tr>
                            <th rowspan="2" style="padding: 0px;width: 25%;text-align: center;"  >Cod</th>
                            <th colspan="3" style=";text-align: center;"  > <?=$va["fechaprodleche"]?></th>
                        </tr>
                        <tr>

                            <th style="padding: 0px;width: 25%;text-align: center;" >M</th>
                            <th style="padding: 0px;width: 25%;text-align: center;">T</th>
                            <th style="padding: 0px;width: 25%;text-align: center;" >R</th>
                        </tr>
                    </table>
                </td>
            <?php    }else{ ?>
                <td style="padding: 0px;" >
                    <table  border="1"    style="margin-bottom:0px;width: 100%">
                        <tr>
                            <th colspan="3" style=";text-align: center;"  > <?=$va["fechaprodleche"]?></th>
                        </tr>
                        <tr>
                            <th style="padding: 0px;width: 33%;text-align: center;" >M</th>
                            <th style="padding: 0px;width: 33%;text-align: center;">T</th>
                            <th style="padding: 0px;width: 33%;text-align: center;" >R</th>
                        </tr>
                    </table>
                </td>
            <?php  }?>
        <?php }?>

    </tr>

    <?php
    $arrFila=null;
    $arrValXFecha=null;

    foreach($dt["dataProd"] as $vacod){
        $iniRow=0;
        ?>
        <tr>
            <?php foreach($dt["fechas"] as $valCol){
                $iniRow++;



                ?>

                <td style="padding: 0px;"  >
                    <table border="1" style="margin-bottom:0px;width: 100%">
                        <tr>
                            <?php
                            $wdtCol="33%";
                            if($iniRow == 1){ $wdtCol="25%"; ?>
                                <td style="padding: 0px; text-align: center;"  ><?=$vacod["codanimal"]?></td>
                            <?php } ?>

                            <?php foreach($vacod["prodFechas"] as $prodxFechax){ ?>
                                <?php if($valCol["fechaprodleche"] == $prodxFechax["fechaprod"]){ ?>

                                    <?php if(sizeof($prodxFechax["dtProdCodAnimalFecha"])>0){ ?>
                                        <?php $arrValXFecha[$valCol["fechaprodleche"]]["m"][]=$prodxFechax["dtProdCodAnimalFecha"][0]["cantmaniana"];?>
                                        <?php $arrValXFecha[$valCol["fechaprodleche"]]["t"][]=$prodxFechax["dtProdCodAnimalFecha"][0]["canttarde"];?>
                                        <?php $arrValXFecha[$valCol["fechaprodleche"]]["r"][]=$prodxFechax["dtProdCodAnimalFecha"][0]["cantrecria"];?>

                                        <td style="padding: 0px;width: <?=$wdtCol?>;text-align: center;"  >
                                            <?=$prodxFechax["dtProdCodAnimalFecha"][0]["cantmaniana"]?>
                                        </td>
                                        <td style="padding: 0px;width: <?=$wdtCol?>;text-align: center;" >

                                            <?=$prodxFechax["dtProdCodAnimalFecha"][0]["canttarde"]?>
                                        </td>
                                        <td style="padding: 0px;width: <?=$wdtCol?>;text-align: center;"  >
                                             <?=$prodxFechax["dtProdCodAnimalFecha"][0]["cantrecria"]?>
                                        </td>
                                    <?php   }else{ ?>
                                        <td style="padding: 0px;width: <?=$wdtCol?>;text-align: center;"  >

                                            -
                                        </td>
                                        <td style="padding: 0px;width: <?=$wdtCol?>;text-align: center;" >

                                            -
                                        </td>
                                        <td style="padding: 0px;width: <?=$wdtCol?>;text-align: center;"  >

                                            -
                                        </td>
                                    <?php }?>

                                <?php   }?>



                            <?php }?>





                        </tr>
                    </table>
                </td>
            <?php }?>
        </tr>
    <?php }?>

    <tr>
        <?php
        $iniValas=0;
        foreach($dt["fechas"] as $va){
            $iniValas++;
            ?>
            <?php
            $subTotalFechaM=0;
            $subTotalFechaT=0;
            $subTotalFechaR=0;
            foreach($arrValXFecha as $kk=>$v){
                if($kk == $va["fechaprodleche"] ){
                    $subTotalFechaM=array_sum($v["m"]);
                    $subTotalFechaT=array_sum($v["t"]);
                    $subTotalFechaR=array_sum($v["r"]);
                }
            }

            ?>
            <?php if($iniValas == 1){ ?>
                <td style="padding: 0px;" >
                    <table border="1" style="margin-bottom:0px;width: 100%">

                        <tr>
                            <td style="padding: 0px;width: 25%;text-align: center;" >-</td>
                            <td style="padding: 0px;width: 25%;text-align: center;" >
                               <?=$subTotalFechaM?>
                            </td>
                            <td style="padding: 0px;width: 25%;text-align: center;"> <?=$subTotalFechaT?></td>
                            <td style="padding: 0px;width: 25%;text-align: center;" > <?=$subTotalFechaR?></td>
                        </tr>
                    </table>
                </td>
            <?php    }else{ ?>
                <td style="padding: 0px;" >
                    <table border="1" style="margin-bottom:0px;width: 100%">

                        <tr>
                            <td style="padding: 0px;width: 33%;text-align: center;" >

                                <?=$subTotalFechaM?>

                            </td>
                            <td style="padding: 0px;width: 33%;text-align: center;">
                                <?=$subTotalFechaT?>
                            </td>
                            <td style="padding: 0px;width: 33%;text-align: center;" >
                                <?=$subTotalFechaR?>
                            </td>
                        </tr>
                    </table>
                </td>
            <?php  }?>
        <?php }?>

    </tr>
</table>

