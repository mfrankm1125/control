<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-control">
                    <ul class="pager pager-rounded" >
                        <li ><a href="javascript:void(0)" id="btnReport" style="font-weight:bold;color: darkblue;" >Generar Reporte</a></li>
                    </ul>

                </div>
                <h3 class="panel-title">Reg de Salidas</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;" >
                <br>
                <div class="table-toolbar-left">
                    <!--<button class="btn btn-purple" id="add"><i class="demo-pli-add icon-fw"></i> Agregar</button> -->
                    <button class="btn btn-purple" id="btnAdd"><i class="demo-pli-add icon-fw"></i> Agregar Nuevo</button>
                    <!--<div class="btn-group">
                      <button class="btn btn-default" id="refresh"  ><i class="demo-pli-refresh" ></i>Refrescar</button>

                  </div>-->
              </div>
              <br><br>
              <!--CUerpot aqui -->
                <h1></h1>
                <table id="tabla_grid" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr><th>#</th>
                        <th>Tipo Salida</th>
                        <th>Producto</th>
                        <th>Cliente</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Total(S/.)</th>
                        <th>Fecha Reg.</th>
                        <th>Acción</th>

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



<div class="modal fade"   id="modal_id" role="dialog" data-backdrop="static" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle">Registrar Salida</h4>
            </div>
            <div class="modal-body" id="divform">
            </div>
        </div>
    </div>
</div>

<div class="modal fade"   id="modalNewCliente" role="dialog" data-backdrop="static" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id=" ">Nuevo Cliente</h4>
            </div>
            <div class="modal-body" id="divNewClienteForm">
                <form class="panel-body form-horizontal form-padding" id="formNewCliente">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Cliente</label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Dirección</label>
                                <input type="text" class="form-control" name="direccion" id="direccion">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Telefono</label>
                                <input type="text" class="form-control" name="telefono" id="telefono">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Ruc</label>
                                <input type="text" class="form-control" name="ruc" id="ruc">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-9">
                            <a href="javascript:void(0)" id="btnSaveNewCliente" type="button" class=" btn btn-success ">
                                Guardar y terminar</a>
                            <button type="button" id="btnCancel" data-dismiss="modal" class=" btn btn-danger ">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"   id="modalReport" role="dialog" data-backdrop="static" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 80%;" >
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" ><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle">Generar Reporte</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divformReport">

                
                <div class="row" style="text-align: center">

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="demo-is-inputsmall">Salida Animales:</label>
                        <div class="col-sm-3"  >
                            <b style="display: inline-block">Del </b> <input style="display: inline-block;width: 80%"  type="date" id="fechaIniSale" value="<?=date("Y-m-d")?>">
                        </div>
                        <div class="col-sm-3"  >
                            <b style="display: inline-block">AL:</b> <input style="display: inline-block;width: 80%"  type="date" id="fechaFinSale" value="<?=date("Y-m-d")?>">
                        </div>
                        <div class="col-sm-3">
                            <button style="border: 1px solid blue;margin-right: 2px;" class="btn btn-default" type="button" onclick="loadReport('salida','word')" title="Reporte Semestre Word"><i style="color:blue" class="fa fa-file-word-o"></i> Word</button>
                            <button style="border: 1px solid green;margin-right: 2px;" class="btn btn-default" type="button" onclick="loadReport('salida','excel')" title="Reporte Semestre Word"><i style="color:green" class="fa fa-file-excel-o"></i> Excel</button>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>


<?php echo $_js?>
<?php echo $_css?>
<script type="text/template" id="tmpBodyForm">
    <form class="panel-body form-horizontal form-padding" id="form">
        <input type="hidden" name="isEdit" id="isEdit" value="<%if (typeof isEdit != 'undefined') { print(isEdit);}%>">
        <input type="hidden" name="idEdit" id="idEdit" value="<%if (typeof id != 'undefined') { print(id);}%>">
        <!--Text Input-->

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="control-label">Descripción de tarea</label>
                    <textarea rows="3" id="name" name="name"    class="form-control"><%if (typeof tarea != 'undefined') { print(tarea);}%></textarea>
                </div>
            </div>

        </div>

        <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-9">
                <a href="javascript:void(0)" onclick="btnSave(1)" type="button"   class=" btn btn-success ">
                    Guardar y terminar
                </a>

                <button type="button" id="btnCancel"  data-dismiss="modal" class=" btn btn-danger ">
                    Cancelar
                </button>

            </div>
        </div>
    </form>

</script>

<script type="text/template" id="tmpTipoProductoSalida" >
    <div class="form-group"  style="margin-top: 5px;">
        <label class="col-sm-2 control-label" style="text-align: right" >Tipo Producto</label>
        <div class="col-sm-4">
            <select class="form-control" id="selTipoProducto" name="selTipoProducto">
                <?php foreach($tipoproducto as $key=>$value){
                    if( strtolower(trim($value["nombre"])) !="semilla" ){ ?>
                        <option value="<?=$value["idtipoproducto"]?>"><?=$value["nombre"]?></option>
                    <?php }?>
                <?php }?>
            </select>
        </div>
    </div>
</script>

<script type="text/template" id="tmpFormRegSalida">
    <form id="FormRegSalida">
        <input type="hidden" id="isEdit" name="isEdit" value="0">
        <input type="hidden" id="idEdit" name="idEdit" value="0">

        <div class="form-group">
            <label class="col-sm-2 control-label" style="text-align: right" >Tipo Salida</label>
            <div class="col-sm-4">
                <select class="form-control" id="selTipoSalida" name="selTipoSalida">
                    <option value="">Seleccione..</option>
                    <?php foreach($tiposalida as $key=>$value){ ?>
                            <option value="<?=$value["idtiposalidaanimal"]?>"><?=$value["nombre"]?></option>
                    <?php }?>
                </select>
            </div>
            <label class="col-sm-2 control-label" style="text-align: right" >Fecha salida: </label>
            <input type="date" name="fechaventa" id="fechaventa" value="<?=date("Y-m-d")?>">
        </div>

        <div id="divTipoProducto">

        </div>
        <div id="divTipoProductoDisable">

        </div>

        <div class="form-group" style="" id="divSelMotivoSalida" >

        </div>

        <div class="form-group" style=" " id="divSelCliente" >

         </div>
        <div class="form-group" style=" " id="divDescMotivoSalida" >

        </div>
        <div class="form-group" style="margin-bottom: 0px;" id="divDescMotivoDonacion" >

        </div>

         <div id="divDetSalida" >

                <!--<div class="row">
                    <table class="table table-condensed table-hover table-bordered">
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Litros</th>
                            <th>Precio</th>
                            <th>Total</th>
                            <th>Acción</th>
                        </tr>
                        <tbody>
                        <tr>

                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="5" style="text-align: right;">Total</td>
                            <td>...</td>
                        </tr>
                        </tfoot>
                    </table>

                </div>-->


            </div>
            <div class="row">
                <hr>
                <div class="col-sm-12" style="text-align: center;">
                    <button class="btn btn-mint" id="btnSaveRegSalida" type="button">Guardar</button>
                    <button class="btn btn-warning" data-dismiss="modal" type="button">Cancelar</button>
                </div>
            </div>
    </form>
</script>

<script type="text/template" id="tmpSelCliente">
    <br><br>
    <label class="col-sm-2 control-label" style="text-align: right" >Cliente</label>
    <div class="col-sm-6">
        <select class="form-control" id="selCliente" name="selCliente" style=" width:80%;display: inline-block">
            <?php foreach ($clientes as $key=>$value){ ?>
                <option value="<?=$value["idcliente"]?>"><?=$value["nombre"]?></option>
            <?php } ?>
        </select>
        <button title="Agregar nuevo cliente" style="display: inline-block" type="button" class="btn btn-sm btn-purple" id="addNewClienteSalida"><b>+</b></button>
    </div>
</script>



<script type="text/template" id="tmpSelCliente2">
    <br><br>
    <label class="col-sm-2 control-label" style="text-align: right" >Cliente</label>
    <div class="col-sm-6">
        <select class="form-control" id="selCliente" name="selCliente" style=" width:80%;display: inline-block">
            <% _.each(data,function(i,k){ %>
            <option value="<%= i.idcliente %>"><%= i.nombre %></option>
            <% }); %>

        </select>
        <button title="Agregar nuevo cliente" style="display: inline-block" type="button" class="btn btn-sm btn-purple" id="addNewClienteSalida"><b>+</b></button>
    </div>
</script>



<script type="text/template" id="tmpIsLeche">
    <br><br>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Stock</label>
                <input type="number" style="text-align: right;font-weight: bolder" id="stockactual" class="form-control"  value="" readonly="readonly" >
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label class="control-label">Cantidad</label>
                <input type="number" id="cantsalida" name="cantsalida" class="form-control"  value="0" >
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label class="control-label">Precio S/.</label>
                <input type="number" id="preciosalida" name="preciosalida" class="form-control"  value="<?=$valprecioleche?>"  >
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Total</label><br>
                <input type="number" class="form-control" id="mtotal"  value="0" readonly="readonly" style="display: inline-block; width: 70%;" >
               <!-- <button type="button" class="btn btn-xs btn-success" style="display: inline-block;" >Agregar</button>-->
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="tmpSelAnimalEditar">
    <label class=" control-label" style="text-align: right" >Cod Animal</label>
    <select class="form-control" id="selAnimal" name="selAnimal">
       <% _.each(data,function(i,k){ %>
        <option value="<%=i.idanimal%>"><%=i.codanimal%></option>
        <% });%>
    </select>
</script>

<script type="text/template" id="tmpSelMotivoSalida">
    <br><br><br>
    <label class="col-sm-2 control-label" style="text-align: right; " >Motivo</label>
    <div class="col-sm-6">
        <select class="form-control" id="selMotivoSalida" name="selMotivoSalida">
        <option value="1">Reproductor</option>
        <option value="2">Descarte</option>
    </select>
    </div>
</script>

<script type="text/template" id="tmpIsAnimal">
    <br><br>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group" id="divSelAnimal">
                <label class=" control-label" style="text-align: right" >Cod Animal</label>
                    <select class="form-control" id="selAnimal" name="selAnimal">
                        <?php foreach ($animales as $key=>$value){ ?>
                            <option value="<?=$value["idanimal"]?>"><?=$value["codanimal"]?></option>
                        <?php } ?>
                    </select>

            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label class="control-label">Precio S/.</label>
                <input type="hidden" id="cantsalida" name="cantsalida" class="form-control"  value="1" >

                <input type="number" id="preciosalida" name="preciosalida" class="form-control"  value="0"  >
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Total</label><br>
                <input type="number" class="form-control" id="mtotal" value="0" readonly="readonly" style="display: inline-block; width: 70%;" >
                <!--<button type="button" class="btn btn-xs btn-success" style="display: inline-block;" >Agregar</button>-->
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="tmpFormRegSalidaDivIsVenta">

</script>

<script type="text/javascript">
var stock=null;
var tableT;
var tmpFormRegSalida=_.template($("#tmpFormRegSalida").html());
var tmpFormRegSalidaDivIsVenta=_.template($("#tmpFormRegSalidaDivIsVenta").html());
var tmpFormRegSalidaDivIsDescarte;

var tmpSelCliente=_.template($("#tmpSelCliente").html());
var tmpIsLeche=_.template($("#tmpIsLeche").html());
var tmpIsAnimal=_.template($("#tmpIsAnimal").html());

    $(document).ready(function () {
      iniDataTables();
     });

 function iniDataTables() {
     tableT = $('#tabla_grid').DataTable({
         "ajax": url_base + 'Salidas/getDataTable',
         "columns": [
             {"data": null},
             {"data": "tiposalida"},
             {
                 sortable: true,
                 "render": function (data, type, full, meta) {
                     var tipoproducto=full.tipoproducto;
                     var idanimal=parseInt(full.idanimal);
                     var label="";
                     if(idanimal >0){
                         var codanimal=full.dataanimal[0].codanimal;
                         var claseanimal=full.dataanimal[0].nombre;
                         label=tipoproducto+" ("+claseanimal+"-"+codanimal+")";
                     }else{
                         label=tipoproducto;
                     }

                     html=''+label;
                     return html;
                 }
             },
             {
                 sortable: true,
                 "render": function (data, type, full, meta) {
                     var dataCliente=full.dataCliente;
                     var html="-";
                     if(dataCliente.length > 0){
                         html=dataCliente[0].nombre;
                     }
                     return html;
                 }
             },

             {"data": "cantidad"},
             {"data": "precio"},
             {
                 sortable: true,
                 "render": function (data, type, full, meta) {
                     var total = parseFloat(full.cantidad)*parseFloat(full.precio);
                     html=''+total.toFixed(2);
                     return html;
                 }
             },
             {
                 sortable: true,
                 "render": function (data, type, full, meta) {
                     var fecha = formatDateDMY(full.fechasalida);
                     html=''+fecha;
                     return html;
                 }
             },
             {
                 sortable: false,
                 "render": function (data, type, full, meta) {
                     var id = parseInt(full.idsalida);
                     //console.log(full);
                     var dt=JSON.stringify(full);
                     html='';
                     //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                     //html=html+'&nbsp; <a href="javascript:void(0)" onclick="editar('+id +');"  class="btn btn-mint btn-icon btn-xs"><i class="demo-psi-pen-5 icon-xs"></i>Editar</a>';
                     html=html+"&nbsp; <a href='javascript:void(0)' onclick='eliminar("+ dt +");'  class='btn btn-danger btn-icon btn-xs'><i class='demo-psi-recycling icon-xs'></i> Eliminar</a>";

                     return html;
                 }
             }

         ],
         "responsive": true,
         "pageLength": 50,
         "language": {
             "lengthMenu": "Mostrar _MENU_ registros",
             "emptyTable": "<b>Ningún dato disponible en esta tabla</b>",
             "zeroRecords": "No se encontraron resultados",
             "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
             "search": "Buscar:",
             "paginate": {
                 "previous": '<i class="demo-psi-arrow-left"></i>',
                 "next": '<i class="demo-psi-arrow-right"></i>'
             }
         },
         "initComplete": function (settings, json) {
             var info = tableT.page.info();

             // console.log(info.recordsTotal);
             //alert( 'DataTables has finished its initialisation.' );
         }
     });

     $('#tabla_grid').on('click', 'tr', function () {
         if ($(this).hasClass('selected')) {
             $(this).removeClass('selected');
         }
         else {
             tableT.$('tr.selected').removeClass('selected');
             $(this).addClass('selected');
         }
     });

     tableT.on('order.dt search.dt', function () {
         tableT.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
             cell.innerHTML = i + 1;
         });
     }).draw();

 }

    function actualizar (isDelete) {
    tableT.ajax.reload(function (json) {
        //console.log(json);
        if(isDelete == 1){
            alert_success("Se actualizo correctamente");
        }

    });
}
//------------------------------------------------------------

var tmpBodyForm=_.template($("#tmpBodyForm").html());

    $(document).on("click","#btnAdd",function () {
        stock=null;
        $("#divform").html(tmpFormRegSalida({isEdit:0,idEdit:0}));
        var selTipoSalida=$("#selTipoSalida").val();
       // var selTipoProducto=$("#selTipoProducto").val();
        var selTipoSalidaText=($("#selTipoSalida option:selected").html()).toLowerCase();
       // var selTipoProductoHtml=($("#selTipoProducto option:selected").html()).toLowerCase();
       // console.log(selTipoSalidaText,selTipoProductoHtml);
      //  opTipoSalida(selTipoSalidaText);
      //  opTipoProducto(selTipoProductoHtml);
        open_modal("modal_id");
    });

    $(document).on("change","#selTipoSalida" ,function (e) {
        var selTipoSalidaText=($("#selTipoSalida option:selected").html()).toLowerCase();
        opTipoSalida(selTipoSalidaText);

    });
    $(document).on("change","#selTipoProducto",function (e) {
        var selTipoProductoHtml=($("#selTipoProducto option:selected").html()).toLowerCase();
        opTipoProducto(selTipoProductoHtml);
    });

    $(document).on("keyup","#preciosalida",function () {
        var preciosalida=$("#preciosalida").val();
        var cantidad=parseFloat($("#cantsalida").val()) || 1;
        var mtotal=parseFloat(preciosalida*cantidad).toFixed(2);
        $("#mtotal").val(mtotal);
        console.log(preciosalida,mtotal);
    });

    $(document).on("keyup","#cantsalida",function () {

        calculatotal();
       // console.log(preciosalida,mtotal);
    });

    function calculatotal(){
        var preciosalida=parseFloat($("#preciosalida").val())||0;
        var cantidad=parseFloat($("#cantsalida").val()) || 0;
        var mtotal=parseFloat(preciosalida*cantidad).toFixed(2);
        if(cantidad <= stock){
            $("#stockactual").val(parseFloat(stock-cantidad));
            $("#mtotal").val(mtotal);
        }else{
            alert_error("Cantidad Sobrepasa el stock");
        }
    }


    function opTipoSalida(option) {
        console.log(option);
        switch (option){
            case "venta":
                $("#divTipoProductoDisable").html("");
                var tmpTipoProductoSalida=_.template($("#tmpTipoProductoSalida").html());
                $("#divTipoProducto").html(tmpTipoProductoSalida);
                var selTipoProductoHtml=($("#selTipoProducto option:selected").html()).toLowerCase();
                opTipoProducto(selTipoProductoHtml);
                $("#divDescMotivoSalida").html("<input type='hidden' name='descMotivoMuerte' value='' >");
                $("#divDescMotivoDonacion").html("<input type='hidden' name='descMotivoDonacion' value='' >");
                break;
            case "muerte":
                var tmpTipoProductoSalida=_.template($("#tmpTipoProductoSalida").html());
                $("#divTipoProducto").html(tmpTipoProductoSalida);
                $("#selTipoProducto").find('option:contains("' + "Animal" + '")').attr('selected', "selected");
                $("#selTipoProducto").attr('disabled',true);

                var valSelTipoproducto=$("#selTipoProducto").val();
                $("#divTipoProductoDisable").html("<input type='hidden' name='selTipoProducto' value='"+valSelTipoproducto+"' >");
                var selTipoProductoHtml=($("#selTipoProducto option:selected").html()).toLowerCase();
                opTipoProducto(selTipoProductoHtml);
                $("#divSelCliente").html("<input type='hidden' name='selCliente' value='' >");
                var ttt=_.template($("#divFormDescMotivoSalida").html());
                $("#divDescMotivoSalida").html(ttt);
                $("#divDescMotivoDonacion").html("<input type='hidden' name='descMotivoDonacion' value='' >");
                break;

            case "donación" :
                $("#divTipoProductoDisable").html("");
                var tmpTipoProductoSalida=_.template($("#tmpTipoProductoSalida").html());
                $("#divTipoProducto").html(tmpTipoProductoSalida);
                $("#selTipoProducto").find('option:contains("' + "Animal" + '")').attr('selected', "selected");
               // $("#selTipoProducto").attr('disabled',"disabled");
                $("#divDescMotivoSalida").html("<input type='hidden' name='descMotivoMuerte' value='' >");

                var selTipoProductoHtml=($("#selTipoProducto option:selected").html()).toLowerCase();
                opTipoProducto(selTipoProductoHtml);

                var ttx=_.template($("#tmpDivFormDescMotivoDonacion").html());
                $("#divDescMotivoDonacion").html(ttx);
                break;
            default :

                    alert("error");
                break;
        }
     }
    function opTipoProducto(option) {
        console.log(option);
        switch (option){
            case "leche":
                $("#divSelMotivoSalida").html("");
                $("#divDetSalida").html(tmpIsLeche);
                getstock();
                getClientes(0);
                break;
            case "animal":
                var tmpSelMotivoSalida=_.template($("#tmpSelMotivoSalida").html());
                 $("#divSelMotivoSalida").html(tmpSelMotivoSalida);
                 $("#divDetSalida").html(tmpIsAnimal);
                $('#selAnimal').chosen({width:'80%'});
                 var TipoSalida=($("#selTipoSalida option:selected").html()).toLowerCase();
                 if(TipoSalida == "donación"){
                     $("#selMotivoSalida").find('option:contains("' + "Descarte" + '")').attr('selected', true);
                 }
                getClientes(0);

                break;
            default:$("#divDetSalida").html("x");
                break;
        }
    }

    function getstock() {
        $.post(url_base+"Salidas/getStockActualLeche",function (data) {
            console.log(data);
            stock=parseFloat(data.stockleche) || 0;
            $("#stockactual").val(data.stockleche);
            
        },"json");
    }

    $(document).on("click","#btnSaveRegSalida",function () {
        var FormReg=$("#FormRegSalida").serialize();
        var selTipoProductoHtml=($("#selTipoProducto option:selected").html()).toLowerCase();
        var bol=true;
        if(selTipoProductoHtml == "leche"){
           bol=bol&&$("#cantsalida").required();
           var cantidad=parseFloat($("#cantsalida").val()) || 0;
           var stock=parseFloat($("#stockactual").val()) || 0;
            if(cantidad > stock){
                bol=bol&&false;
                alert_error("stock insuficiente");
            }

        }
        if(bol !=true){
            return;
        }
        if(confirm("Esta seguro de realizar esta acción")){
            var selTipoSalidaText=($("#selTipoSalida option:selected").html()).toLowerCase();

            var dataSend=FormReg+"&txtTipoSalida="+selTipoSalidaText+"&txtTipoProducto="+selTipoProductoHtml;
            $.post(url_base+"Salidas/setForm",dataSend,function (data) {
                if(data.status == "ok"){
                    close_modal("modal_id");
                    alert_success("Correcto");
                    actualizar("");
                }else{
                    close_modal("modal_id");
                    alert_error("Fallo");
                    actualizar("");
                }
            },'json');
        }else{

        }
    });

    function btnSave(d) {

        var form=$("#form").serialize();
        var bol=true;
        bol=bol&& $("#name").required();
        if(bol){
            $.post(url_base+"Salidas/setForm",form,function (data) {

                var obLen=objectLen(data);
                if(obLen>0){
                    if(data.status=="ok"){
                        actualizar(1);
                        close_modal("modal_id");
                        alert_success("Se realizo Correctamente");
                    }else{
                        alert_error("Ocurrio Algo Inesperado :(");
                    }

                }else{
                    alert_error("Ocurrio Algo Inesperado :(");
                }
                console.log(data);
            },'json');
        }

    }


function eliminar(data) {
   console.log(data);
    if(confirm('¿Esta seguro?')){
      //  var idd=parseInt(id);
        $.post(url_base+'Salidas/deleteData',data,function (data) {
            if( typeof data.status != "undefined" ){
                actualizar("");
                alert_success("Se realizo Correctamente");
            }else{
                alert_error("Ocurrio algo inesperado");
            }
            console.log(data);
        },'json').error(function () {

            alert_error("Ocurrio algo inesperado");
        });
    }
}


function editar(id) {
        var idd=parseInt(id);
        $.post(url_base+'salidas/getData','id='+idd,function (data) {
            if(arrayLen(data) > 0 ){
                open_modal("modal_id");
                $("#divform").html(tmpFormRegSalida({isEdit:0,idEdit:0}));
                var selTipoSalida=$("#selTipoSalida").val(data[0].idtiposalida);
                var selTipoProducto=$("#selTipoProducto").val(data[0].idtipoproducto);
                var selTipoSalidaText=($("#selTipoSalida option:selected").html()).toLowerCase();
                var selTipoProductoHtml=($("#selTipoProducto option:selected").html()).toLowerCase();

                // console.log(selTipoSalidaText,selTipoProductoHtml);
                opTipoSalida(selTipoSalidaText);
                opTipoProducto(selTipoProductoHtml);
                $("#fechaventa").val(data[0].fechasalida);
                $("#selCliente").val(data[0].idcliente);
                $("#preciosalida").val(data[0].precio);


                $("#selAnimal").val(data[0].idanimal);
                if(data[0].tipoproducto == "Animal"){
                    stock=1;
                    selAnimalEditar(data[0].idanimal);
                }else{
                    $("#cantsalida").val(data[0].cantidad);
                }
                //opTipoProducto(selTipoProductoHtml);

            }else{
                alert_error("Ocurrio algo inesperado");
            }
            console.log(data);
        },'json').error(function () {

            alert_error("Ocurrio algo inesperado");
        });

}

function selAnimalEditar(idanimal){
    var tmpSelAnimalEditar=_.template($("#tmpSelAnimalEditar").html());
    $.post(url_base+"Salidas/getAnimalEdit",{"id":idanimal},function (data) {
        //console.log(data);
        $("#divSelAnimal").html(tmpSelAnimalEditar({data:data}));
        $('#selAnimal').chosen({width:'100%'});
        $("#selAnimal").val(idanimal);


    },"json");
}

///----------------Reg Salida


function openModalRegSalida(){
    open_modal("modal_id");
    $("#divModalFormRegSalida").html(tmpFormRegSalida);
    $("#divTipoSalida").html(tmpFormRegSalidaDivIsVenta);
}

$(document).on("change","#selTipoSalida",function(){
    var valSel=$(this).val();

    console.log(valSel);
});


//-->

$(document).on("click","#btnReport",function () {
    open_modal("modalReport");
});

function loadReport(tipotiempo,tipodoc){
    var anioReport=0;
    var mesIniReport=0;
    var mesEndReport=0;
    var rtipoanimal="x";

    var fechaini="x";
    var fechaend="x";
    if(tipotiempo =="actual"){
        fechaend=$("#fechaActualReport").val();
        rtipoanimal=$("#RselTipoanimal").val();
    }else if(tipotiempo == "nacimiento"){
        fechaini=$("#fechaIniNace").val();
        fechaend=$("#fechaFinNace").val();
        rtipoanimal=$("#RselTipoanimal").val();
    }else if(tipotiempo == "salida"){
        fechaini=$("#fechaIniSale").val();
        fechaend=$("#fechaFinSale").val();
    }
    //var urlReport=""+tipotiempo+"/"+tipodoc+"/"+anioReport+"/"+mesIniReport+"/"+mesEndReport;
    //console.log(urlReport);
    var urlReport=tipotiempo+"/"+tipodoc+"/"+rtipoanimal+"/"+fechaini+"/"+fechaend;
    window.open(url_base+"Reportes/reportInventario/"+urlReport,"", "width=400,height=300");

}

///Neewww Client
    $(document).on("click","#addNewClienteSalida",function () {
        $("#formNewCliente")[0].reset();
        open_modal("modalNewCliente");

    });
    
$(document).on("click","#btnSaveNewCliente",function () {
    var formNewCliente=$("#formNewCliente").serialize();
    $.post(url_base+"salidas/newCliente",formNewCliente,function (data) {
        if(data.status=="ok"){
            getClientes(data.insertId);
        }else{
            alert_error("Ocurrio algo inesperado")
        }
        close_modal("modalNewCliente");
    },'json');

});

function getClientes(idcliente) {
    $("#divSelCliente").html("Cargando...");
    $.post(url_base+"salidas/getclientes",function (data) {
        var t=_.template($("#tmpSelCliente2").html());
        $("#divSelCliente").html(t({data:data}));
        if(idcliente > 0){
            $('#selCliente').val(idcliente);
        }

        $('#selCliente').chosen({width:'80%'});
    },'json');
}



function addTextAreaCallback(textArea, callback, delay) {
    var timer = null;
    textArea.onkeypress = function() {
        if (timer) {
            window.clearTimeout(timer);
        }
        timer = window.setTimeout( function() {
            timer = null;
            callback();
        }, delay );
    };
    textArea = null;
}

</script>


<script type="text/template" id="divFormDescMotivoSalida">
    <br> <br>
    <div class="form-group">
        <label class="col-md-2 control-label" for="demo-textarea-input" style="text-align:right">Motivo Muerte</label>
        <div class="col-md-6">
            <textarea id="descMotivoMuerte" name="descMotivoMuerte" rows="2" class="form-control" ></textarea>
        </div>
    </div>
    <br> <br>
</script>

<script type="text/template" id="tmpDivFormDescMotivoDonacion">
    <br> <br>
    <div class="form-group" style="margin-bottom: 0px;" >
        <label class="col-md-2 control-label" for="demo-textarea-input" style="text-align:right">Desc donación</label>
        <div class="col-md-6">
            <textarea id="descMotivoDonacion" name="descMotivoDonacion" rows="2" class="form-control" ></textarea>
        </div>
    </div>
    <br> <br>
</script>