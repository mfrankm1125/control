<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-control">
                    <button class="add-tooltip  btn btn-default btn-lg"  data-toggle="tooltip" data-container="body" data-placement="left" data-original-title="Representa los datos de analisis de suelos"  ><i class="fa fa-question-circle-o"></i></button>
                </div>
                <h3 class="panel-title">Almacenes</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;">
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
                <table id="tabla_grid" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr><th>#</th>
                        <th>Nombre</th>
                        <th>Ubicación</th>
                        <th>Fecha Crea</th>
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



<div class="modal fade"   id="modal_id" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body">
                <form class="panel-body form-horizontal form-padding" id="formAlmacen">
                    <input type="hidden" name="isEdit" id="isEdit" value="0">
                    <input type="hidden" name="idEdit" id="idEdit" value="0">
                    <!--Text Input-->
                    <div class="form-group">
                        <label class="col-md-3 control-label"  >Nombre *</label>
                        <div class="col-md-9">
                            <input id="name" name="name" class="form-control" placeholder="Ejemplo: Fumigadora Still 1" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label"  >Ubicación </label>
                        <div class="col-md-9">
                            <input id="direccion" name="direccion" class="form-control" placeholder="Ejemplo: Jr. Lima 999" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="demo-textarea-input">Descripción</label>
                        <div class="col-md-9">
                            <textarea id="desc" name="desc" rows="9" class="form-control" placeholder="¿Alguna descripcion?"></textarea>
                        </div>
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


<script type="text/template" id="tmpDivIsProp">
    <div class="form-group">
        <label class="col-md-3 control-label"  >Modelo</label>
        <div class="col-md-9">
            <input id="model" name="model"  class="form-control" placeholder="Ejemplo: Xy-22" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label"  >Marca</label>
        <div class="col-md-9">
            <input id="marca" name="marca" class="form-control" placeholder="Ejemplo: Toyota" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label"  >Ubicación</label>
        <div class="col-md-9">
            <input id="almacen" name="almacen" class="form-control" placeholder="Ejemplo: Alamacen 1" />
        </div>
    </div>

</script>


<script type="text/template" id="tmpDivIsntProp">

    <div class="form-group">
        <label class="col-md-3 control-label"  >Coste  Alquiler Referencial S/.</label>
        <div class="col-md-9">
            <input id="costealq" name="costealq"  class="form-control" placeholder="Ejemplo: 500.00" />
        </div>
    </div>


</script>
<?php echo $_css?>
<?php echo $_js?>
