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
            <!--===================================================-->
            <!--End Data Table-->

        </div>
    </div>

</div>



<div class="modal fade"   id="modalId" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body">
                <form class="panel-body form-horizontal form-padding" id="formPersonal">
                    <input type="hidden" name="isEdit" id="isEdit" value="0">
                    <input type="hidden" name="idEdit" id="idEdit" value="0">
                    <!--Text Input-->
                    <div class="form-group">
                        <label class="col-md-2 control-label"  >Cultivo</label>
                        <div class="col-md-4">
                           <select class="form-control" id="selCultivo" name="selCultivo">
                               <option>Seleccione</option>

                           </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label"  >Variedad</label>
                        <div class="col-md-4">
                            <select class="form-control" id="selVariedad" name="selVariedad">
                                <option>Seleccione</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label"  >Area</label>
                        <div class="col-md-3" >
                            <input type="number" style="display: inline-block;width: 80%;text-align: right;" id="area" name="area"  class="form-control"  /> <span style="display: inline-block">Ha</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label"  >Campaña</label>
                        <div class="col-md-4">
                            <input id="lastname" name="lastname"  class="form-control"  />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label"  >Fecha siembra </label>
                        <div class="col-md-3">
                            <input id="fechasiembra" name="fechasiembra"  class="form-control"  />
                        </div>
                        <label class="col-md-2 control-label"  >Fecha Cosecha </label>
                        <div class="col-md-3">
                            <input id="fechacosecha" name="fechacosecha"  class="form-control"  />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label"  >Localidad </label>
                        <div class="col-md-4">
                            <input id="localidad" name="localidad"  class="form-control" placeholder="Ejemplo: LLosa" />
                        </div>
                    </div>
                    <div id="divActividadCoste">

                    </div>




                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-9">
                            <a href="javascript:void(0)" onclick="btnSave(1)" type="button"   class=" btn btn-success ">
                                Guardar y terminar
                            </a>

                            <label id="btn2"   >

                             </label>

                            <button type="button" id="btnCancel"  data-dismiss="modal" class=" btn btn-danger ">
                                Cancelar
                            </button>

                        </div>
                    </div>
                </form>


            </div>

        </div>
    </div>
</div>

<?php echo $_js?>
<?php echo $_css?>

<script type="text/javascript">
    $(document).on("ready",function () {

    });

    $(document).on("click","#btnAdd",function () {
        open_modal("modalId");
        getHtmlActividadCoste();
    });

    function getHtmlActividadCoste() {
        $.post(url_base+"semillas/getDataActividadCostePlantilla",function (data) {
          //  console.log(data);
            $("#divActividadCoste").html(data);
            calcTotalNivel1();
        });
    }

    function calcTotalNivel1() {
        var input=$("input[name='nivel1[]']") ;
        var total1=0;
        $.each(input,function (k,i) {
            total1=total1+parseFloat($(i).val());
        });

        console.log(total1);
    }

    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
          //  console.log(timer);
        };
    })();

    function calCosto(thisx){
        delay(function(){

            var parentTR= $(thisx).parent().parent();

            var cantidad=parseFloat(parentTR.find('input[name="cantidad[]"]').val())||0;
            var costo=parseFloat(parentTR.find('input[name="costounitario[]"]').val())||0;
            var idupd=parseFloat(parentTR.find('input[name="id[]"]').val())||0;

            //  console.log(meses);
            var cant=0;
            // var meses=parentTR.find('input[name="mes[]"]').serializeArray();
            console.log(cantidad,costo,idupd);
            var datasend={"idupd":idupd,"cantidad":cantidad , "costo":costo };
            $.post(url_base+"semillas/setValCantCostoActCosto",datasend,function (data) {
                console.log(data);
                if(data.status == "ok"){
                    getHtmlActividadCoste();
                }else{
                    alert_error("Ups");
                }

            },'json');

        }, 500 );
              //getHtmlActividadCoste();
    }

</script>
