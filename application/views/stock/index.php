<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Stock Insumos</h3>
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
                <table id="tabla_grid" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr><th>#</th>
                        <th>Insumo</th>
                        <th>Tipo Insumo</th>
                        <th>Cantidad</th>
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



<div class="modal fade"   id="modal_id" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="modalbody">


            </div>

        </div>
    </div>
</div>


<script type="text/template" id="tmpForm">
    <form class="panel-body form-horizontal form-padding" id="formStock">
        <input type="hidden" name="isEdit" id="isEdit" value="0">
        <input type="hidden" name="idEdit" id="idEdit" value="0">

        <div class="form-group">
            <label class="col-md-2 control-label"  >Provedor*</label>
            <div class="col-md-3">
               <select class="form-control" id="proveedor" name="proveedor">
                <?php foreach ($dataProveedor as $dprov){
                   echo "<option value='".$dprov['id_proveedor']."'>".$dprov['razonsocial']."</option>";
                }?>
               </select>

            </div>
            <label class="col-md-2 control-label"  >Fecha</label>
            <div class="col-md-3">
                <input type="text" id="date" name="date" class="form-control" placeholder="Ejemplo: Fumigadora Still 1" />
            </div>

        </div>
        <div class="form-group" >
            <label class="col-md-2 control-label"  > NumFact</label>
            <div class="col-md-3">
                <input type="text" id="numfact" name="numfact" class="form-control" placeholder="Ejemplo: Fumigadora Still 1" />
            </div>
            <label class="col-md-2 control-label"  >Almacen </label>
            <div class="col-md-3">
                <select class="form-control" id="almacen" name="almacen">
                    <?php foreach ($dataAlmacen as $dal){
                        echo "<option value='".$dal['id_almacen']."'>".$dal['nombre']."</option>";
                    }?>
                </select>
            </div>
            <label class="col-md-2 control-label" >Agregar más </label>
        </div>




        <div class=" ">
            <table class="table table-bordered table-condensed  table-hover   ">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Insumo*</th>
                    <th>Cantidad*</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                    <th>i</th>
                </tr>
                </thead>
                <tbody id="tmpTTbody">


                </tbody>
                <tfoot>
                <tr>
                    <th colspan="4" style="text-align: right" >Total</th>
                    <th > <p id="total" style="text-align: right;"  >0</p></th>
                    <th > </th>

                </tr>
                </tfoot>
            </table>
        </div>
        <div id="divisProp">

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




</script>

<script type="text/template" id="tmpTbody">
    <tr>
        <td class="text-center">1</td>
        <td>
            <select class="selectpicker form-control insum " name="insumox[]">
                <option value="0"> seleccione </option>
                <?php foreach ($dataInsumo as $dinsumo){
                    echo "<option value='".$dinsumo['id_insumo']."'>".$dinsumo['nombre']."</option>";
                }?>
            </select>
        </td>
        <td>
            <input  type="text" value="1" name="cantidadx[]" onkeyup="calSub(this)"  style="text-align: right;" class="form-control cant" placeholder="0" />
        </td>

        <td>
            <input   type="text" value="" name="preciox[]" onkeyup="calSub(this)"  style="text-align: right;"  class="form-control" placeholder="0" />
        </td>
        <td>
             <p id="subtotalx" style="text-align: right;" >0</p>

            <input type="hidden" class="subtotal" name="subtotal[]" value=""   />

        </td>

        <td>
            <button type="button" name="btnx" onclick="btnX(this)">+ </button>
            <button type="button" onclick="btnEdit(this)">Edit </button>
            <button type='button' value='-' class='eliminart btn  btn-danger btn-xs' > <i class='fa fa-trash-o'></i></button>
        </td>
    </tr>
</script>
<?php echo $_css?>
<?php echo $_js?>