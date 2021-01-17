 
<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                
                <div class="panel-control">
                    <button class="add-tooltip  btn btn-default btn-lg"  data-toggle="tooltip" data-container="body" data-placement="left" data-original-title="Representa los datos de analisis de suelos"  ><i class="fa fa-question-circle-o"></i></button>
                </div>

                <h3 class="panel-title">Insumos</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;" >
                <br>
                <div class="table-toolbar-left">
                    <!--<button class="btn btn-purple" id="add"><i class="demo-pli-add icon-fw"></i> Agregar</button> -->
                    <button class="btn btn-purple" id="addBtn"><i class="demo-pli-add icon-fw"></i> Agregar Nuevo</button>
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
                        <th>Insumo</th>
                        <th>Tipo Insumo</th>
                        <th>Cantidad</th>
                        <th>Precio S/.</th>
                        <th>Unidad</th>
                        <th>Ubicación</th>
                        <th>Fecha Reg</th>
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


<div class="modal fade"   id="modal_id" role="dialog" data-backdrop="static"   tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body">
                 <b style="color:red" >* </b>= <span class="label label-danger">Campo necesario</span>
                <form class="panel-body form-horizontal form-padding" id="formInsumo">
                    <input type="hidden" name="isEdit" id="isEdit" value="0">
                    <input type="hidden" name="idEdit" id="idEdit" value="0">

                    <div class="form-group">
                        <label class="col-md-2 control-label" for="demo-text-input"><b>Tipo Insumo </b><b>*</b></label>
                        <div class="col-md-10">
                            <select  class="form-control"   id="idtpinsumo" name="idtpinsumo" >
                                <option value="">seleccione...</option>

                            </select>
                            <small class="help-block" style="display: none">This is a help text</small>
                        </div>
                    </div>
                    <div id="bodyFormSelect">

                    </div>
                    <div id="bodyStock">

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


<div class="modal fade"   id="modal_stock" role="dialog" data-backdrop="static"   tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle2"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" >
                <form class="panel-body form-horizontal form-padding" id="formStock">
                    <input type="hidden" name="idStock" id="idStock" value="0">
                    <div id="modalbody" ></div>

                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-9">
                            <button type="button" id="btnSaveStock" class="pull-left btn btn-primary btn-file">
                                Guardar
                            </button>
                        </div>
                    </div>


            </div>

        </div>
    </div>
</div>

<script type="text/template" id="tmpFertORIGINAL" >

    <div class="form-group">
        <label class="col-md-3 control-label" for="demo-text-input">Nombre Comercial <b>*</b></label>
        <div class="col-md-9">
            <input type="text" id="ninsumo" name="ninsumo" class="form-control" placeholder="Nombre Tipo Insumo">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label" for="demo-text-input">Unidad de Medida <b>*</b></label>
        <div class="col-md-9">
            <select class="form-control" id="idumedida" name="idumedida" >
             
            </select>
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>


    <div class="form-group">
        <label class="col-md-3 control-label" for="demo-text-input">Funcion <b>*</b></label>
        <div class="col-md-9">
            <input type="text" id="funcion" name="funcion" class="form-control" placeholder="funcion">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label" for="demo-text-input">Composicion</label>
        <div class="col-md-9">
            <input type="text" id="composicion" name="composicion" class="form-control" placeholder="composicion">
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


</script>
<script type="text/template" id="tmpFert" >

    <div class="form-group">
        <label class="col-md-2 control-label" for="demo-text-input">Nombre Comercial <b>*</b></label>
        <div class="col-md-4">
            <input type="text" id="ninsumo" name="ninsumo" class="form-control" placeholder="Nombre Tipo Insumo">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
        <label class="col-md-2 control-label" for="demo-text-input">Unidad de Medida <b>*</b></label>
        <div class="col-md-4">
            <select class="form-control" id="idumedida" name="idumedida" >

            </select>
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label" for="demo-text-input">Funcion <b>*</b></label>
        <div class="col-md-4">
            <input type="text" id="funcion" name="funcion" class="form-control" placeholder="funcion">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
        <label class="col-md-2 control-label" for="demo-text-input">Composicion</label>
        <div class="col-md-4">
            <input type="text" id="composicion" name="composicion" class="form-control" placeholder="composicion">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>
    <!--Textarea-->
    <div class="form-group">
        <label class="col-md-2 control-label" for="demo-textarea-input">Descripción</label>
        <div class="col-md-10">
            <textarea id="desc" name="desc" rows="3" class="form-control" placeholder="¿Alguna descripcion?"></textarea>
        </div>
    </div>


</script>

<script type="text/template" id="tmpAgroqOriginal" >

    <div class="form-group">
        <label class="col-md-3 control-label" for="demo-text-input">Tipo Agroquímico <b>*</b></label>
        <div class="col-md-9">
            <select class="form-control" id="idtpagroquimico" name="idtpagroquimico" >
                <option value="0" >Seleccione...</option>
            </select>
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label" for="demo-text-input">Nombre Comercial <b>*</b> </label>
        <div class="col-md-9">
            <input type="text" id="ninsumo" name="ninsumo" class="form-control" placeholder="Nombre Tipo Insumo">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label" for="demo-text-input">Unidad de Medida <b>*</b> </label>
        <div class="col-md-9">
            <select class="form-control" id="idumedida" name="idumedida" >
                <option value="0" >Seleccione...</option>
            </select>
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>


    <div class="form-group">
        <label class="col-md-3 control-label" for="demo-text-input">Función <b>*</b> </label>
        <div class="col-md-9">
            <input type="text" id="funcion" name="funcion" class="form-control" placeholder="funcion">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label" for="demo-text-input">Composición</label>
        <div class="col-md-9">
            <input type="text" id="composicion" name="composicion" class="form-control" placeholder="composicion">
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


</script>

<script type="text/template" id="tmpAgroq" >

    <div class="form-group">
        <label class="col-md-2 control-label" for="demo-text-input">Tipo Agroquímico <b>*</b></label>
        <div class="col-md-10">
            <select class="form-control" id="idtpagroquimico" name="idtpagroquimico" >
                <option value="0" >Seleccione...</option>
            </select>
            <small class="help-block" style="display: none">This is a help text</small>
        </div>

    </div>
    <div class="form-group">
        <label class="col-md-2 control-label" for="demo-text-input">Nombre Comercial <b>*</b> </label>
        <div class="col-md-4">
            <input type="text" id="ninsumo" name="ninsumo" class="form-control" placeholder="Nombre Tipo Insumo">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
        <label class="col-md-2 control-label" for="demo-text-input">Unidad de Medida <b>*</b> </label>
        <div class="col-md-4 " >
            <select class="form-control" id="idumedida" name="idumedida" >
                <option value="0" >Seleccione...</option>
            </select>
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label" for="demo-text-input">Función <b>*</b> </label>
        <div class="col-md-4">
            <input type="text" id="funcion" name="funcion" class="form-control" placeholder="funcion">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
        <label class="col-md-2 control-label" for="demo-text-input">Composición</label>
        <div class="col-md-4">
            <input type="text" id="composicion" name="composicion" class="form-control" placeholder="composicion">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>


    <!--Textarea-->
    <div class="form-group">
        <label class="col-md-2 control-label" for="demo-textarea-input">Descripción</label>
        <div class="col-md-10">
            <textarea id="desc" name="desc" rows="3" class="form-control" placeholder="¿Alguna descripcion?"></textarea>
        </div>
    </div>


</script>


<script type="text/template" id="tmpSemillaOriginial" >

    <div class="form-group">
        <label class="col-md-3 control-label" for="demo-text-input">Cultivo <b>*</b> </label>
        <div class="col-md-9">
            <select class="form-control" id="idcultivo" name="idcultivo" >
                <option value="0" >Seleccione...</option>
            </select>
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label" for="demo-text-input">Nombre Comercial <b>*</b> </label>
        <div class="col-md-9">
            <input type="text" id="ninsumo" name="ninsumo" class="form-control" placeholder="Nombre Tipo Insumo">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label" for="demo-text-input">Variedad <b>*</b> </label>
        <div class="col-md-9">
            <input type="text" id="variedad" name="variedad" class="form-control" placeholder="funcion">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label" for="demo-text-input">Unidad de Medida <b>*</b> </label>
        <div class="col-md-9">
            <select class="form-control" id="idumedida" name="idumedida" >
                <option value="0" >Seleccione...</option>
            </select>
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>




    <div class="form-group">
        <label class="col-md-3 control-label" for="demo-text-input">Poder Germinativo(%)</label>
        <div class="col-md-8">
            <input type="text" id="pgermi" name="pgermi" class="form-control " placeholder="%PG">
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

</script>
<script type="text/template" id="tmpSemilla" >

    <div class="form-group">
        <label class="col-md-2 control-label" for="demo-text-input">Cultivo <b>*</b> </label>
        <div class="col-md-4">
            <select class="form-control" id="idcultivo" name="idcultivo" >
                <option value="0" >Seleccione...</option>
            </select>
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
        <label class="col-md-2 control-label" for="demo-text-input">Nombre Comercial <b>*</b> </label>
        <div class="col-md-4">
            <input type="text" id="ninsumo" name="ninsumo" class="form-control" placeholder="Nombre Tipo Insumo">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>



    <div class="form-group">
        <label class="col-md-2 control-label" for="demo-text-input">Variedad <b>*</b> </label>
        <div class="col-md-4">
            <input type="text" id="variedad" name="variedad" class="form-control" placeholder="funcion">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
        <label class="col-md-2 control-label" for="demo-text-input">Unidad de Medida <b>*</b> </label>
        <div class="col-md-4">
            <select class="form-control" id="idumedida" name="idumedida" >
                <option value="0" >Seleccione...</option>
            </select>
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label" for="demo-text-input">Poder Germinativo(%)</label>
        <div class="col-md-4">
            <input type="text" id="pgermi" name="pgermi" class="form-control " placeholder="%PG">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
        <label class="col-md-2 control-label" for="demo-textarea-input">Descripción</label>
        <div class="col-md-4">
            <textarea id="desc" name="desc" rows="3" class="form-control" placeholder="¿Alguna descripcion?"></textarea>
        </div>
    </div>





</script>

<script type="text/template" id="tmpOther" >



</script>


<script type="text/template" id="tmpBodyStock">
    <form>
    <b>Stock</b>
    <hr style="    margin-top: 0px;">
    <div class="form-group" >
        <label class="col-md-2 control-label" for="demo-text-input">Proveedor <b>*</b></label>
        <div class="col-md-4">
            <select  class="form-control" id="proveedor" name="proveedor" >
                <option value="">Seleccione...</option>
                <?php foreach ($dataProv as $dtprov){
                    echo '<option value="'.$dtprov['id_proveedor'] .'">'.$dtprov['razonsocial'].'</option>';
                }?>
            </select>
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
        <label class="col-md-2 control-label" for="demo-text-input">Fecha <b>*</b></label>
        <div class="col-md-4">
            <input type="text" id="fecha" name="fecha" class="form-control" placeholder="" value="0">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>
    <div class="form-group" >
        <label class="col-md-2 control-label" for="demo-text-input">Almacen <b>*</b></label>
        <div class="col-md-3">
            <select  class="form-control" id="almacen" name="almacen" >
                <option value="">Seleccione...</option>
                <?php foreach ($dataAlma as $dtalma){
                    echo '<option value="'.$dtalma['id_almacen'] .'">'.$dtalma['nombre'].'</option>';
                }?>
            </select>
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
        <label class="col-md-1 control-label" for="demo-text-input">Cantidad*</label>
        <div class="col-md-2">
            <input type="number" id="cant" name="cant" style="text-align: right" class="form-control" placeholder="" value="0">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
        <label class="col-md-2 control-label" for="demo-text-input">Precio S/.</label>

        <div class="col-md-2">
            <input type="number" id="precio"  style="text-align: right" name="precio" class="form-control" placeholder="" value="0">
            <small class="help-block" style="display: none">This is a help text</small>
        </div>
    </div>
    <div class="form-group" >
        <div class="col-md-7">

        </div>
        <label class="col-md-1 control-label" for="demo-text-input"><b>Total:</b></label>
        <label class="col-md-4 control-label" for="demo-text-input" id="total"> </label>

    </div>
    </form>
</script>
<?php echo $_css?>
<?php echo $_js?>
