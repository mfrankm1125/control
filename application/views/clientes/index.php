<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-control">
                    <button class="add-tooltip  btn btn-default btn-lg"  data-toggle="tooltip" data-container="body" data-placement="left" data-original-title="Representa los datos de analisis de suelos"  ><i class="fa fa-question-circle-o"></i></button>

                </div>
                <h3 class="panel-title">Clientes</h3>
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
                <form class="panel-body form-horizontal form-padding" id="formPersonal">
                    <input type="hidden" name="isEdit" id="isEdit" value="0">
                    <input type="hidden" name="idEdit" id="idEdit" value="0">
                    <!--Text Input-->
                    <div class="form-group">
                        <label class="col-md-3 control-label"  >Nombres</label>
                        <div class="col-md-9">
                            <input id="name" name="name" class="form-control" placeholder="Ejemplo: Miguel" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label"  >Apellidos</label>
                        <div class="col-md-9">
                            <input id="lastname" name="lastname"  class="form-control" placeholder="Ejemplo: LLosa" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"  >Dirección</label>
                        <div class="col-md-9">
                            <input id="address" name="address" class="form-control" placeholder="Ejemplo: Jr Lima 932" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label"  >Tel./Cel.</label>
                        <div class="col-md-9">
                            <input id="phone" name="phone"  class="form-control" placeholder="Ejemplo: 999 099 222" />
                        </div>
                    </div>
                    <!--Textarea
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="demo-textarea-input">Descripción</label>
                        <div class="col-md-9">
                            <textarea id="desc" name="desc" rows="9" class="form-control" placeholder="¿Alguna descripcion?"></textarea>
                        </div>
                    </div>
                    -->


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

