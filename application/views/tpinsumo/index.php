<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><?= $title ;?></h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body">
                <br>
                <div class="table-toolbar-left">
                    <!--<button class="btn btn-purple" id="add"><i class="demo-pli-add icon-fw"></i> Agregar</button> -->
                    <button class="btn btn-purple" id="addBtn"><i class="demo-pli-add icon-fw"></i> Agregar Nuevo</button>
                    <div class="btn-group">
                        <button class="btn btn-default" id="refreshBtn"  ><i class="demo-pli-refresh" ></i>Refrescar</button>

                    </div>
                </div>
                <br><br>
                <!--CUerpot aqui -->
                <h1></h1>
                <table id="tabla_grid" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr><th>#</th>
                        <th>Nombre Campo</th>
                        <th>Descripcion</th>
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
                <form class="panel-body form-horizontal form-padding" id="formTpinsumo">
                    <input type="hidden" name="isEdit" id="isEdit" value="0">
                    <input type="hidden" name="idEdit" id="idEdit" value="0">
                    <!--Text Input-->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="demo-text-input">Tipo Insumo</label>
                        <div class="col-md-9">
                            <input type="text" id="ntipoinsumo" name="ntipoinsumo" class="form-control" placeholder="Nombre Tipo Insumo">
                            <small class="help-block" style="display: none">This is a help text</small>
                        </div>
                    </div>



                    <!--Textarea-->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="demo-textarea-input">Descripción</label>
                        <div class="col-md-9">
                            <textarea id="desc" name="desc" rows="9" class="form-control" placeholder="¿Alguna descripcion?"></textarea>
                        </div>
                    </div>



                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-9">
					                        <button type="button" id="btnSave" class="pull-left btn btn-primary btn-file">
					                        Guardar
					                        </button>
                        </div>
                    </div>
                </form>


            </div>

        </div>
    </div>
</div>

<?php echo $_css?>
<?php echo $_js?>

