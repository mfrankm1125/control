<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-control">
                    <button class="add-tooltip  btn btn-default btn-lg"  data-toggle="tooltip" data-container="body" data-placement="left" data-original-title="Representa los datos de analisis de suelos"  ><i class="fa fa-question-circle-o"></i></button>

                </div>
                <h3 class="panel-title">Plan de Costos</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;" >
                <br>
                <div class="table-toolbar-left">
                    <!--<button class="btn btn-purple" id="add"><i class="demo-pli-add icon-fw"></i> Agregar</button> -->
                    <button class="btn btn-purple" id="btnAdd"><i class="demo-pli-add icon-fw"></i> Agregar Nuevo</button>
                    <div class="btn-group">
                        <button class="btn btn-default" id="refresh"  ><i class="demo-pli-refresh" ></i>Refrescar</button>

                    </div>
                </div>
                <br><br>
                <!--CUerpot aqui -->
                <h1></h1>
                <table id="tabla_grid" class="table table-hover table-bordered table-condensed " cellspacing="0" width="100%">
                    <thead>
                    <tr><th>#</th>
                        <th>Nombre </th>
                        <th>Dirección</th>
                        <th>Telefono</th>
                        <th>Accion</th>

                    </tr>
                    </thead>
                    <tbody id="tabla_body">
                    <tr>
                        <td colspan="5">Sin datos...</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-lg-12">
                <table id="" class="table  table-bordered table-condensed table-responsive" >
                    <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Lunes </th>
                        <th>Martes</th>
                        <th>Miercoles</th>
                        <th>Jueves</th>
                        <th>Viernes</th>
                        <th>Sabado</th>
                        <th>Domingo</th>
                    </tr>
                    </thead>
                    <tbody id="tabla_body">
                    <?php for($i=6;$i<24;$i++){
                        $x=$i;
                        ?>
                    <tr>
                        <td >
                            <?php echo $x."-".($x+1) ?>
                        </td>
                        <td class="diahora"  dia="lunes" horaini="<?=$x?>" horaend="<?=($x+1)?>" onclick="diaHora(this)" >

                        </td>
                        <td class="diahora"  dia="martes" horaini="<?=$x?>" horaend="<?=($x+1)?>" onclick="diaHora(this)" >

                        </td>
                        <td class="diahora"  dia="miercoles" horaini="<?=$x?>" horaend="<?=($x+1)?>" onclick="diaHora(this)" >

                        </td>
                        <td class="diahora"  dia="jueves" horaini="<?=$x?>" horaend="<?=($x+1)?>"  onclick="diaHora(this)"  >

                        </td>
                        <td class="diahora"  dia="viernes" horaini="<?=$x?>" horaend="<?=($x+1)?>"  onclick="diaHora(this)" >

                        </td>
                        <td class="diahora"  dia="sabado" horaini="<?=$x?>" horaend="<?=($x+1)?>"  onclick="diaHora(this)" >

                        </td>
                        <td class="diahora"  dia="domingo" horaini="<?=$x?>" horaend="<?=($x+1)?>"  onclick="diaHora(this)" >

                        </td>
                    </tr>
                    <?php }?>

                    </tbody>
                </table>
                </div>
            </div>
            <!--===================================================-->
            <!--End Data Table-->

        </div>
    </div>

</div>

<script type="text/javascript" >
    /*$(document).on("click",".diahora",function (e) {
        var ctx=e.target.parentNode;
        var dia=$(ctx).attr("dia");
        //var dia=ctx.attr("dia");
        console.log(dia);
    });*/

   $(document).on("ready",function () {
       seleccionarHorario();
   });

    var HoraIni=0;
    var HoraEnd=0;

    function diaHora(thisx) {
        var ctx=$(thisx);
        var dia=ctx.attr("dia");

        var horaIni=ctx.attr("horaini");
        var horaEnd=ctx.attr("horaend");


        console.log("ss---",dia,horaIni,horaEnd );
    }
    
    function seleccionarHorario() {
        var claseDiaHora=$(".diahora");
        var dia="lunes";

        $(claseDiaHora).each(function(k,i) {
          var diaHoraItem=$(i);
            var diax=diaHoraItem.attr("dia");

            var horaInix=diaHoraItem.attr("horaini");
            var horaEndx=diaHoraItem.attr("horaend");
            if(diax == "lunes" ){
                if(horaInix >= "7" && horaEndx<="9"){
                    diaHoraItem.css("background-color","red");
                }

            }

            console.log("ss---",diax,horaInix,horaEndx );
        });
        console.log(claseDiaHora);
    }


</script>

