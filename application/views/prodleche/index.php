<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-control">
                    <ul class="pager pager-rounded" >
                        <li ><a href="javascript:void(0)" id="btnRegSalida" style="font-weight:bold;color: darkblue;" >Registrar Salida</a></li>
                    </ul>
                    <ul class="pager pager-rounded">
                        <li><a href="javascript:void(0)" id="btnReportProdLeche" >Generar Reporte</a></li>
                    </ul>
                </div>
                <h3 class="panel-title">Registro de producción de leche</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 5px;" >
                <div class="row" style="margin-top: 5px;">
                    <div class="col-sm-8">
                        <button class="btn btn-purple" id="btnAdd"><i class="demo-pli-add icon-fw"></i> Agregar Nuevo</button>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label " style="font-size: 18px;">Stock Actual : </label>
                            <b style=" " id="bStockActualProdLeche" class="text-2x mar-no text-semibold"></b>
                            <b>Litros</b>
                        </div>
                    </div>
                </div>




                <!--CUerpot aqui -->

                <table id="tabla_grid" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha Reg.</th>
                        <th>Litros Vendibles</th>
                        <th>Recria</th>
                        <th>Litros Totales</th>
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
    <div class="modal-dialog modal-lg" style="width: 80%;">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle">Registrar producción de leche</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divformModal">

            </div>

        </div>
    </div>
</div>

<div class="modal fade"   id="modalRegSalida" role="dialog" data-backdrop="static" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 80%;">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="m">Registrar Salida de leche</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divModalFormSalida">

            </div>

        </div>
    </div>
</div>

<div class="modal fade"   id="modalReporte" role="dialog" data-backdrop="static" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="m">Generar Reporte de producción de leche</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="">
                <div class="row" style="text-align: center" >
                    <div class="col-sm-12"  >
                        <div class="col-sm-3"  >
                            <b style="display: inline-block">Desde:</b> <input style="display: inline-block;width: 70%"  type="date" id="fechaIniReport" value="<?=$fechaprodlecheminmax["fechaprodmin"]?>">
                        </div>
                        <div class="col-sm-3"  >
                            <b style="display: inline-block">Al:</b> <input style="display: inline-block;width: 80%"  type="date" id="fechaFinReport" value="<?=$fechaprodlecheminmax["fechaprodmax"]?>">
                        </div>
                        <div class="col-sm-3">
                            <button style="border: 1px solid blue;margin-right: 2px;" class="btn btn-default" type="button" onclick="loadReport('reportproduc','dehasta','word')" title="Reporte Semestre Word"><i style="color:blue" class="fa fa-file-word-o"></i> Word</button>
                            <button style="border: 1px solid green;margin-right: 2px;" class="btn btn-default" type="button" onclick="loadReport('reportproduc','dehasta','excel')" title="Reporte Semestre Word"><i style="color:green" class="fa fa-file-excel-o"></i> Excel</button>

                        </div>
                    </div>
                </div>
                <div class="row" style="text-align: center" >
                     <h4>Reporte de rendimiento por Raza </h4>
                    <div class="col-sm-12"  >
                        <div class="col-sm-3"  >
                            <b style="display: inline-block">Desde:</b> <input style="display: inline-block;width: 70%"  type="date" id="fechaIniReportRaza" value="<?=$fechaprodlecheminmax["fechaprodmin"]?>">
                        </div>
                        <div class="col-sm-3"  >
                            <b style="display: inline-block">Al:</b> <input style="display: inline-block;width: 80%"  type="date" id="fechaFinReportRaza" value="<?=$fechaprodlecheminmax["fechaprodmax"]?>">
                        </div>
                        <div class="col-sm-3"  >
                            <select  id="raza-multiselect" name="raza-multiselect" data-placeholder="Seleccione las razas" multiple tabindex="5" >
                                <?php foreach ($razasprodleche as $kk){?>
                                <option value="<?=$kk["raza"]?>"><?=$kk["raza"]?></option>
                                <?php }?>
                            </select>
                       </div>
                        <div class="col-sm-3">
                            <button style="border: 1px solid blue;margin-right: 2px;" class="btn btn-default btn-sm" type="button" onclick="loadReport('reportproducraza','dehasta','html')" title="Reporte Digital Word"><i style="color:blue" class="fa fa-html5"></i>Ver</button>

                            <button style="border: 1px solid blue;margin-right: 2px;" class="btn btn-default  btn-sm " type="button" onclick="loadReport('reportproducraza','dehasta','word')" title="Reporte  Word"><i style="color:blue" class="fa fa-file-word-o"></i> Word</button>
                            <button style="border: 1px solid green;margin-right: 2px;" class="btn btn-default  btn-sm " type="button" onclick="loadReport('reportproducraza','dehasta','excel')" title="Reporte  Excel"><i style="color:green" class="fa fa-file-excel-o"></i> Excel</button>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php echo $_js?>
<?php echo $_css?>
<script type="text/template" id="tmpDivModalbody">

    <div class="row" id="divFechaIni">
        <% if(isVer == 0){ %>
        <div class="col-sm-3" style="text-align: right;">
            <div class="form-group">
                <label class="control-label">Fecha : </label>
                <input type="date" id="fechaIniReg" class=""  value="<?=date("Y-m-d")?>" >
            </div>
        </div>
        <div class="col-sm-1">
            <div class="form-group">
                <label class="control-label">.</label>
                <button type="button" class="btn btn-sm btn-mint" id="btnEmpezar" >Empezar</button>
            </div>
        </div>
        <% } %>
    </div>

    <div class="row" id="divFormRegLeche">

    </div>
    <div class="row" id="divTableFechaProdLeche">

    </div>
</script>
<script type="text/template" id="tmpDivFormRegLeche" >
   <h4 style="padding-top: 0px;margin-top: 0px">
       &nbsp;&nbsp;Fecha: <%=fechaIni%> &nbsp;
       <%if(isVer == 0 ){ %>
       <button class="btn btn-xs btn-info" id="btncambiarfecha">Cambiar</button>
       <% }%>
   </h4>
    <br>
    <div class="row">
        <form id="formRegProdLeche">
            <div class="col-sm-12">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label" >Cod animal</label>
                        <div id="divSelAnimal">
                            Cargando...
                         </div>

                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-group">
                        <label class="control-label">Mañana</label>
                        <input type="text" id="cantmaniana" name="cantmaniana"   style="text-align: right;" class="form-control">
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-group">
                        <label class="control-label">Tarde</label>
                        <input type="text" id="canttarde" name="canttarde" style="text-align: right;"  class="form-control">
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-group">
                        <label class="control-label"><u>Con Recria</u></label><br>
                        <label for="si">Si</label>
                        <input type="radio" name="isrecria" id="si" value="si">
                        <label for="no"> &nbsp; No</label>
                        <input type="radio" name="isrecria" id="no" value="no" checked="checked">
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-group">
                        <label class="control-label">Subtotal</label>
                        <input type="text" class="form-control" id="subtotal" readonly="readonly" value="0">
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-group">
                        <label class="control-label">Recria</label>
                        <input type="text" class="form-control" id="inrecria" readonly="readonly" value="0">
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-group">
                        <label class="control-label"><b>Total</b></label>
                        <input type="text" class="form-control" id="total" readonly="readonly">
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-group">
                        <label class="control-label">.</label><br>
                        <button type="button" class="btn btn-success   btn-sm" id="btnSaveProdLeche" >Guardar</button>
                    </div>
                </div>
             </div>
        </form>
    </div>
</script>

<script type="text/template" id="tmpTableFechaProdLeche">
    <div class="col-xs-12">
        <table id="tableProdLeche" style="text-align: center;" class="table table-hover table-bordered table-condensed " cellspacing="0" width="100%">
            <thead>
            <tr><th>#</th>
                <th>Cod Animal</th>
                <th>Mañana</th>
                <th>Tarde</th>
                <th>Litros vendibles</th>
                <th>+Recria</th>
                <th style="text-align: center;">Total</th>
                <th>Acción</th>
            </tr>
            </thead>
            <tbody id="tbodyProdLeche">
                <%
                var tmaniana=0,ttarde=0,trecria=0,tsubtotal=0,ttotal=0;
                _.each(data,function(i,k){
                var lmaniana=parseFloat(i.cantmaniana)||0;
                var ltarde=parseFloat(i.canttarde)||0;
                var lrecria=parseFloat(i.cantrecria)||0;
                var subtotal=ltarde+lmaniana;
                var total=subtotal+lrecria;

                tmaniana=tmaniana+lmaniana;
                ttarde=ttarde+ltarde;
                trecria=trecria+lrecria;
                tsubtotal=tsubtotal+subtotal;
                ttotal=ttotal+total;
                %>
                <tr>
                    <td><%=(k+1)%></td>
                    <td><%=i.codanimal%></td>
                    <td><%=lmaniana.toFixed(2)%></td>
                    <td><%=ltarde.toFixed(2)%></td>
                    <td><%=subtotal.toFixed(2)%></td>
                    <td><%=lrecria.toFixed(2)%></td>
                    <td style="text-align: center;"><%=total.toFixed(2)%></td>
                    <td>
                         <a href="javascript:void(0)" onclick="deleteProdLeche('<%=i.idprodleche%>')"  class="btn btn-xs btn-danger">Eliminar</a>
                    </td>
                </tr>
                <% })%>

            </tbody>
            <tfoot>
            <tr style="font-weight: bold;">
                <td colspan="2"></td>
                <td><%=tmaniana.toFixed(2)%></td>
                <td><%=ttarde.toFixed(2)%></td>
                <td><%=tsubtotal.toFixed(2)%></td>
                <td><%= trecria.toFixed(2)%></td>
                <td><%=ttotal.toFixed(2)%></td>
                <td></td>
            </tr>
            </tfoot>
        </table>
    </div>
</script>

<script type="text/template" id="tmpSelAnimal">
    <select id="selAnimal" name="selAnimal">
        <option value="0" >Seleccione</option>
        <% _.each(data,function(i,k){  %>
            <option value="<%=i.idanimal%>"><%= i.codanimal%></option>
        <% }) %>
    </select>
</script>

<!--Salida-->

<script type="text/template" id="tmpFormRegSalida">
    <form id="FormRegSalida">
        <input type="hidden" id="isEdit" name="isEdit" value="0">
        <input type="hidden" id="idEdit" name="idEdit" value="0">

        <div class="form-group">
            <label class="col-sm-2 control-label" style="text-align: right" >Tipo Salida</label>
            <div class="col-sm-4">
                <select class="form-control" id="selTipoSalida" name="selTipoSalida">
                    <?php foreach($tiposalida as $key=>$value){ ?>
                       <?php if( strtolower(trim($value["nombre"])) !="muerte" ){ ?>
                        <option value="<?=$value["idtiposalidaanimal"]?>"><?=$value["nombre"]?></option>
                        <?php }?>
                    <?php }?>
                </select>
            </div>
            <label class="col-sm-2 control-label" style="text-align: right" >Fecha salida: </label>
            <input type="date" name="fechaventa" id="fechaventa" value="<?=date("Y-m-d")?>">
        </div>

        <div class="form-group"  style="margin-top: 5px;">
            <label class="col-sm-2 control-label" style="text-align: right" >Producto</label>
            <div class="col-sm-4">
                <select class="form-control" id="selTipoProducto" name="selTipoProducto">
                    <?php foreach($tipoproducto as $key=>$value){
                        if( strtolower(trim($value["nombre"])) =="leche" ){ ?>
                            <option value="<?=$value["idtipoproducto"]?>"><?=$value["nombre"]?></option>
                        <?php }?>
                    <?php }?>
                </select>
            </div>
        </div>
        <br><br>
        <div class="form-group" style="margin-top: 10px;" id="divSelCliente" >

        </div>

        <div id="divDetSalida" >
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
    <label class="col-sm-2 control-label" style="text-align: right" >Cliente</label>
    <div class="col-sm-6">
        <select class="form-control" id="selCliente" name="selCliente">
            <?php foreach ($clientes as $key=>$value){ ?>
                <option value="<?=$value["idcliente"]?>"><?=$value["nombre"]?></option>
            <?php } ?>
        </select>
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



<script type="text/javascript" >
var tableT;
var tmpDivFormRegLeche=_.template($("#tmpDivFormRegLeche").html());
var tmpTableFechaProdLeche=_.template($("#tmpTableFechaProdLeche").html());
var tmpDivModalbody=_.template($("#tmpDivModalbody").html());
var tmpSelAnimal=_.template($("#tmpSelAnimal").html());

var tmpFormRegSalida=_.template($("#tmpFormRegSalida").html());
//var tmpFormRegSalidaDivIsVenta=_.template($("#tmpFormRegSalidaDivIsVenta").html());
var tmpFormRegSalidaDivIsDescarte;

var tmpSelCliente=_.template($("#tmpSelCliente").html());
var tmpIsLeche=_.template($("#tmpIsLeche").html());
var stockLeche=0;
var fechainireg=null;
    $(document).on("ready",function () {
        $('#raza-multiselect').chosen({width:'100%'});
        dataTable();
        getStockActualProdLeche();
    });

    function dataTable() {
        tableT = $('#tabla_grid').DataTable({
            "ajax": url_base + 'Prodleche/getDataTable',
            "columns": [
                {"data": null},
                {
                    sortable: true,
                    "render": function (data, type, full, meta) {
                        var fecha =formatDateDMY(full.fechaprodleche);

                        return fecha;
                    }
                },
                {
                    sortable: true,
                    "render": function (data, type, full, meta) {
                        var totalvendible =parseFloat(full.totalvendible).toFixed(2);
                        var ht="<b style='text-align: right'>"+totalvendible+"</b>";
                        return ht;
                    }
                },
                {
                    sortable: true,
                    "render": function (data, type, full, meta) {
                        var recria =parseFloat(full.recria).toFixed(2);
                        var ht="<b style='text-align: right'>"+recria+"</b>";
                        return ht;
                    }
                },
                {
                    sortable: true,
                    "render": function (data, type, full, meta) {
                        var total = parseFloat(full.total).toFixed(2);
                        var ht="<b style='text-align: right'>"+total+"</b>";
                        return ht;
                    }
                },
                {
                    sortable: false,
                    "render": function (data, type, full, meta) {
                        var fecha =  full.fechaprodleche ;
                        html='';
                        //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                        html=html+'&nbsp; <a href="javascript:void(0)" onclick="ver(\''+fecha +'\');"  class="btn btn-default btn-icon btn-xs"><i class="fa fa-search icon-xs"></i>Ver</a>';

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

function actualizarGrid (alert) {
    tableT.ajax.reload(function (json) {
        //console.log(json);
        if(alert == "onAlert"){
            alert_success("Se actualizo correctamente");
        }

    });
}

    $(document).on("click","#btnAdd",function () {
        open_modal("modal_id");
        $("#divformModal").html(tmpDivModalbody({isVer:0}));
    });

    $(document).on("click","#btncambiarfecha",function () {
        $("#divformModal").html(tmpDivModalbody({isVer:0}));
    });

    $(document).on("click","#btnEmpezar",function () {
        var fechaini=$("#fechaIniReg").val();
        fechainireg=fechaini;
        iniFormRegLeche(fechaini,0);
        $("#divFechaIni").remove();
        getDataProdLechebyFecha();
    });

    function iniFormRegLeche(fini,isVer){
        var fechaini=formatDateDMYText(fini);
        $("#divFormRegLeche").html(tmpDivFormRegLeche({fechaIni:fechaini,isVer:isVer}));
        getSelAnimalesByFecha();

    }

    function getDataProdLechebyFecha(){
       if(fechainireg != null){
        $.post(url_base+"Prodleche/getDataProdLecheDiaria",{"fecha":fechainireg},function (data) {
            console.log(data);
            $("#divTableFechaProdLeche").html(tmpTableFechaProdLeche({data:data}));
        },'json');
       }else{
           alert_error("Vuelva a elegir la fecha");
       }
    }

    function getSelAnimalesByFecha() {
        if(fechainireg != null){
            $.post(url_base+"Prodleche/getAnimalLechero",{"fecha":fechainireg},function (data) {
                //console.log(data);
                $("#divSelAnimal").html(tmpSelAnimal({data:data}));
                $('#selAnimal').chosen({width:'100%'});
            },'json');
        }else{
            alert_error("Vuelva a elegir la fecha");
        }
    }


    $(document).on("change","input[name='isrecria']",function () {
        var radiovalue=$(this).val();
        var recria=parseFloat("<?=$valrecria?>");
        var inrecria=$("#inrecria");
        if(radiovalue =="si"){
            inrecria.val(recria);
        }else if(radiovalue =="no"){
            inrecria.val(0);
        }

        calculaTotales();
        //console.log(radiovalue);
    });


$(document).on("keyup","#canttarde,#cantmaniana",function(){
    // console.log("sss");
    calculaTotales();
});

    function calculaTotales() {
        var canttarde=parseFloat($("#canttarde").val()) || 0;
        var cantmaniana=parseFloat($("#cantmaniana").val()) || 0;
        var valrecria=parseFloat($("#inrecria").val()) || 0;
        var subtotal=cantmaniana+canttarde;
        var total=subtotal+valrecria;
        $("#subtotal").val(subtotal);
        $("#total").val(total);
    }

function ver(fecha) {
    open_modal("modal_id");
    fechainireg=null;
    fechainireg=fecha;
    $("#divformModal").html(tmpDivModalbody({isVer:1}));
    iniFormRegLeche(fechainireg,1);
    getDataProdLechebyFecha();
}

$(document).on("click","#btnSaveProdLeche",function(){
    var btn=$(this);
    var bol=true;
    bol=bol && $("#selAnimal").requiredSelect();
    var canttarde=parseFloat($("#canttarde").val()) || 0;
    var cantmaniana=parseFloat($("#cantmaniana").val()) || 0;
    sutotal=canttarde+cantmaniana;
    if(sutotal ==0){
        bol=false;
        alert_error("Ingrese los campos requeridos");
    }

    if(bol == true){
        var formProdLeche=$("#formRegProdLeche").serialize();
        var fechareg=fechainireg;
        /*var codAnimal=$('#selAnimal').val();
        var canttarde=parseFloat($("#canttarde").val()) || 0;
        var cantmaniana=parseFloat($("#cantmaniana").val()) || 0;
        var isRecria=$("input[name='isrecria']").val();
        var dataSend={"fechareg":fechareg,"codanimal":codAnimal,"cantmaniana":cantmaniana,"canttarde":canttarde,"isrecria":isRecria};
        */
        btn.button("loading");
        var dataSend=formProdLeche+"&fechareg="+fechareg;
        //onsole.log(formProdLeche);
        //console.log(dataSend);
        $.post(url_base+"Prodleche/saveProdLeche",dataSend,function(data) {
            if (data.status == "ok") {
                alert_success("Se realizo correctamente");
                iniFormRegLeche(fechareg);
                getDataProdLechebyFecha();
                getStockActualProdLeche();
                btn.button("reset");
            } else {
                alert_error("ocurrio un problema");
            }

        },'json');
    }else{

    }
});

function deleteProdLeche(id){
    $.post(url_base+"Prodleche/deleteProdleche",{"id":id},function(data){
        if(data.status =="ok"){
            alert_success("Se Realizo correctamente");
            getDataProdLechebyFecha();
            getSelAnimalesByFecha();
            getStockActualProdLeche();
        }else{
            alert_error("Error ");
        }
        console.log(data);
    },"json");
}

$('#modal_id').on('hidden.bs.modal', function () {
    // do something…
    console.log("saliendo");
    actualizarGrid("");
});



    //-----stock

function getStockActualProdLeche(){
    $.post(url_base+"Prodleche/getStockActualProdLeche",function(data){
        stockLeche=parseFloat(data.stockactual)||0;
        $("#bStockActualProdLeche").html(data.stockactual+ "");
        $("#stockactual").val(data.stockactual);

    },'json');
}
    
//---salida
$(document).on("click","#btnRegSalida",function () {
   open_modal("modalRegSalida");
    $("#divModalFormSalida").html(tmpFormRegSalida);
    $("#divSelCliente").html(tmpSelCliente);
    $("#divDetSalida").html(tmpIsLeche);
    getStockActualProdLeche();
});

$(document).on("keyup","#cantsalida",function () {
    var preciosalida=parseFloat($("#preciosalida").val())||0;
    var cantidad=parseFloat($("#cantsalida").val()) || 0;
    var mtotal=parseFloat(preciosalida*cantidad);
    if(cantidad <= stockLeche){
        $("#stockactual").val(parseFloat(stockLeche-cantidad));
        $("#mtotal").val(mtotal);
    }else{
        alert_error("Cantidad Sobrepasa el stock");
    }

    // console.log(preciosalida,mtotal);
});

$(document).on("keyup","#preciosalida",function () {
    var preciosalida=$("#preciosalida").val();
    var cantidad=parseFloat($("#cantsalida").val()) || 1;
    var mtotal=parseFloat(preciosalida*cantidad);
    $("#mtotal").val(mtotal);
    console.log(preciosalida,mtotal);
});


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
                close_modal("modalRegSalida");
                alert_success("Correcto");
                getStockActualProdLeche();
                actualizarGrid("");
            }else{
                close_modal("modalRegSalida");
                alert_error("Fallo");
                actualizarGrid("");
            }
        },'json');
    }else{

    }
});
    
    
$(document).on("click","#btnReportProdLeche",function () {
    open_modal("modalReporte");
});    

function loadReport(tiporeport,tipotiempo,tipodoc){
    var fechaini=$("#fechaIniReport").val();
    var fechaend=$("#fechaFinReport").val();
    var fechainiraza=$("#fechaIniReportRaza").val();
    var fechaendraza=$("#fechaFinReportRaza").val();
    if(tiporeport == "reportproduc"){
      var urlReport=tipotiempo+"/"+tipodoc+"/"+fechaini+"/"+fechaend;
      window.open(url_base+"Reportes/reportProdleche/"+urlReport,"", "width=400,height=300");
    }
    if(tiporeport == "reportproducraza"){
      var razas=  $("#raza-multiselect").val();
      if(razas != null){
          razas=($("#raza-multiselect").val()).toString();
          razas = razas.replace(/\//g, "vv");
          razas = razas.replace(/,/g, "-");
      }else{
          razas="";
      }


      var urlReport=tipotiempo+"/"+tipodoc+"/"+fechainiraza+"/"+fechaendraza+"/"+razas;
      window.open(url_base+"Reportes/reportProdLecheRaza/"+urlReport,"", "width=400,height=300");
      /*var urlReport=tipotiempo+"/"+tipodoc+"/"+fechainiraza+"/"+fechaendraza;
      window.open(url_base+"Reportes/reportProdLecheRaza/"+urlReport,"", "width=400,height=300");*/
    }
   
}
</script>
