<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                    <div class="panel-control">
                        <ul class="pager pager-rounded" >
                            <li ><a href="javascript:void(0)" id="btnActualizarClases" style="font-weight:bold;color: darkblue;" >Actualizar Clases</a></li>
                            <li ><a href="javascript:void(0)" id="btnReport" style="font-weight:bold;color: darkblue;" >Generar Reporte</a></li>
                        </ul>
                    </div>
                <h3 class="panel-title">Inventario de Ganado</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;" >
                <br>
                <div class="table-toolbar-left">
                    <!--<button class="btn btn-purple" id="add"><i class="demo-pli-add icon-fw"></i> Agregar</button> -->
                    <button class="btn btn-purple" id="btnAdd"><i class="demo-pli-add icon-fw"></i> Agregar Nuevo</button>
                    <!--<button class="btn btn-default" id="btnReport"><i class="fa fa-document"></i> Generar Reporte</button>

                <div class="btn-group">
                    <button class="btn btn-default" id="refresh"  ><i class="demo-pli-refresh" ></i>Refrescar</button>

                </div>-->
              </div>
                <div class="table-toolbar-right">
                    <a href="<?=base_url()?>/invganado/historialganado" class="btn btn-warning" id="btnAdd"><i class="fa fa-file"></i> Ver Historial</a>

                </div>

              <!--CUerpot aqui -->

                <table id="tabla_grid" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><b title="Nro Identificación"> Cod Id.</b></th>
                            <th>Tipo </th>
                            <th>Clase </th>
                            <th>Raza</th>
                            <th>Fecha Nacimiento</th>
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
    <div class="modal-dialog modal-lg"  >
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" ><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divform" style="padding-top: 0px;">


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
                        <label class="col-sm-2 control-label" for="demo-is-inputsmall">Inventario actual:</label>
                        <div class="col-sm-2">
                            <select id="RselTipoanimal">
                                <?php foreach ($tipoanimal as $k=>$v){?>
                                    <option value="<?=$v["idtipoanimal"]?>" ><?=$v["nombre"]?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-3"  >
                           <b style="display: inline-block">AL:</b> <input style="background-color:#dedfe0;display: inline-block;width: 80%"  type="date"  id="fechaActualReport" value="<?=date("Y-m-d")?>" readonly="readonly">
                        </div>
                        <div class="col-sm-3">
                            <button style="border: 1px solid blue;margin-right: 2px;" class="btn btn-default" type="button" onclick="loadReport('actual','word')" title="Reporte Semestre Word"><i style="color:blue" class="fa fa-file-word-o"></i> Word</button>
                            <button style="border: 1px solid green;margin-right: 2px;" class="btn btn-default" type="button" onclick="loadReport('actual','excel')" title="Reporte Semestre Word"><i style="color:green" class="fa fa-file-excel-o"></i> Excel</button>

                        </div>
                    </div>
                </div>
                <hr>
                <div class="row" style="text-align: center">

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="demo-is-inputsmall">Nacimientos:</label>
                        <div class="col-sm-2">
                            <select id="RselTipoanimal">
                                <?php foreach ($tipoanimal as $k=>$v){?>
                                    <option value="<?=$v["idtipoanimal"]?>" ><?=$v["nombre"]?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-2"  >
                            <b style="display: inline-block">Del </b> <input style="display: inline-block;width: 80%"  type="date" id="fechaIniNace" value="<?=date("Y-m-d")?>">
                        </div>
                        <div class="col-sm-2"  >
                            <b style="display: inline-block">AL:</b> <input style="display: inline-block;width: 80%"  type="date" id="fechaFinNace" value="<?=date("Y-m-d")?>">
                        </div>
                        <div class="col-sm-3">
                            <button style="border: 1px solid blue;margin-right: 2px;" class="btn btn-default" type="button" onclick="loadReport('nacimiento','word')" title="Reporte Semestre Word"><i style="color:blue" class="fa fa-file-word-o"></i> Word</button>
                            <button style="border: 1px solid green;margin-right: 2px;" class="btn btn-default" type="button" onclick="loadReport('nacimiento','excel')" title="Reporte Semestre Word"><i style="color:green" class="fa fa-file-excel-o"></i> Excel</button>

                        </div>
                    </div>
                </div>
                <hr>
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
                <hr>
                <div class="row" style="text-align: center">

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="demo-is-inputsmall">Ver rendimiento de vacas</label>
                        <div class="col-sm-3"  >
                            <b style="display: inline-block">Del </b> <input style="display: inline-block;width: 80%"  type="date" id="fechaIniProdVaca" value="<?=date("Y-m-d")?>">
                        </div>
                        <div class="col-sm-3"  >
                            <b style="display: inline-block">AL:</b> <input style="display: inline-block;width: 80%"  type="date" id="fechaFinProdVaca" value="<?=date("Y-m-d")?>">
                        </div>
                        <div class="col-sm-3">
                            <button style="border: 1px solid blue;margin-right: 2px;" class="btn btn-default" type="button" onclick="loadReport('vacaprod','word')" title="Reporte Semestre Word"><i style="color:blue" class="fa fa-file-word-o"></i> Word</button>
                            <button style="border: 1px solid green;margin-right: 2px;" class="btn btn-default" type="button" onclick="loadReport('vacaprod','excel')" title="Reporte Semestre Word"><i style="color:green" class="fa fa-file-excel-o"></i> Excel</button>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php echo $_js?>
<?php echo $_css?>




<script type="text/template" id="tmpBodyModalFormRegGanado">

    <div class="panel" style="border: 0px;">
        <!--Panel heading-->
        <div class="panel-heading" style="border-bottom: 1px solid #00000038;">
            <div class="panel-control" style="float: left">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#demo-tabs-box-1" data-toggle="tab" aria-expanded="true">General</a></li>
                    <li class=""><a href="#divVerDescendecia" data-toggle="tab" aria-expanded="false" id="verDependencia">Descendencia</a></li>
                </ul>
            </div>

        </div>
        <!--Panel body-->
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade active in" id="demo-tabs-box-1">
                    <form id="formRegGanado">
                        <input type="hidden" id="isEdit" name="isEdit" value="<%=isEdit%>" >
                        <input type="hidden" id="idEdit" name="idEdit" value="<%=idEdit%>" >
                        <input type="hidden" id="codAminal" name="codAminal" value="<%=codAnimal%>" >
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="demo-is-inputsmall">Tipo Animal</label>
                            <div class="col-sm-6">
                                <select class="form-control" id="selTipoAnimal" name="selTipoAnimal">
                                    <!--<option value="0" >Seleccionar</option>-->
                                    <?php foreach($tipoanimal as $key=>$value){  ?>
                                        <option value="<?=$value["idtipoanimal"]?>"><?=$value["nombre"]?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <br><br><br>

                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" title="Codigo de identificación">Código de Ident. </label>
                                    <input type="text" name="codanimal" id="codanimal" class="form-control" >
                                    <small id="spanCodExiste" class="help-block" style="color: red;font-weight: bold;display:none;" >El código ya existe</small>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" title="Codigo de identificación">Padre </label>
                                    <input type="text" class="form-control" value="" id="codpadre" name="codpadre"  >
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Madre</label>
                                    <input type="text" class="form-control"  value="" id="codmadre" name="codmadre" >
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Fecha Nacimiento</label><br>
                                    <input type="date" class="" style="width:100%;"  id="fechanace" name="fechanace">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Sexo</label>
                                    <select class="form-control" id="selSexo" name="selSexo">
                                        <?php foreach($sexo as $key=>$value){  ?>
                                            <option value="<?=$value["idsexo"]?>"><?=$value["nombre"]?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Raza</label>
                                    <input type="text" class="form-control" id="raza" name="raza">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Pureza</label>
                                    <input type="text" class="form-control" name="pureza" id="pureza">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Edad</label>
                                    <input type="text" readonly="readonly" id="edad"  class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Propósito</label>
                                    <input type="text" class="form-control" name="proposito" id="proposito">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Peso nacimiento</label>
                                    <input type="text" class="form-control" name="pesonace" id="pesonace">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">

                                <input  class="magic-checkbox" id="ismuertoalnacer" name="ismuertoalnacer" value="1" type="checkbox">
                                <label for="ismuertoalnacer" style="color: darkred;"> <b>Muerto al nacer</b></label>
                                <br>

                            </div>
                            <div class="col-sm-6">
                                <div class="form-group" id="divDescMuertoalNacer" style="display: none;" >
                                    <label class="control-label" style="color: darkred;" ><b>Motivo de Muerte al nacer</b></label>
                                    <textarea   id="descmuertoalnacer" name="descmuertoalnacer" rows="2" class="form-control" placeholder=""></textarea>
                                </div>
                            </div>
                        </div>
                        <h5>Detalle de crecimiento</h5>
                        <hr>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Clases</th>
                                        <th>Fecha Inicio</th>
                                        <th>Estado Actual</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbodyClasesAnimal">
                                    <tr>
                                        <td class="text-center">Cargando...</td>

                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <h5>De la Entrada/Salida</h5>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" title=" ">Tipo de Entrada </label>
                                    <select class="form-control" id="selTipoEntrada" name="seltipoentrada">
                                        <option value="0">Seleccione...</option>
                                        <?php foreach($tipoingresoanimal as $key=>$value){  ?>
                                            <option value="<?=$value["idtipoingresoanimal"]?>"><?=$value["nombre"]?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">Comprado a:</label>
                                    <select class="form-control" id="selProveedor" name="selproveedor">
                                        <?php foreach($proveedores as $key=>$value){  ?>
                                            <option value="<?=$value["idproveedor"]?>"><?=$value["razonsocial"]?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label">Precio S/.</label>
                                    <input type="number" class="form-control" name="preciocompra" id="preciocompra" value="" >
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label">Fecha Ingreso</label>
                                    <input type="date" class="" name="fechaingreso" id="fechaingreso" value="" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" title=""> Tipo de Salida</label>
                                    <select class="form-control" id="selTipoSalida" name="seltiposalida">
                                        <option value="0">Seleccione...</option>
                                        <?php foreach($tiposalidaanimal as $key=>$value){  ?>
                                            <option value="<?=$value["idtiposalidaanimal"]?>"><?=$value["nombre"]?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">Vendido a:</label>
                                    <select class="form-control" id="selCliente" name="selcliente">
                                        <option value="0">Seleccione...</option>
                                        <?php foreach($clientes as $key=>$value){  ?>
                                            <option value="<?=$value["idcliente"]?>"><?=$value["nombre"]?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label">Precio S/.</label>
                                    <input type="number" class="form-control" name="precioventa" id="precioventa"  value="" >
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label">Fecha Salida </label>
                                    <input type="date" class="" name="fechasalida" id="fechasalida" value="" >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12" style="text-align: center;">
                                <button class="btn btn-mint" data-loading-text="Guardando..."  id="btnSaveAnimal" type="button">Guardar</button>
                                <button class="btn btn-warning"   data-dismiss="modal" id="btnClose" type="button">Cancelar</button>
                            </div>
                        </div>


                    </form>
                </div>
                <div class="tab-pane fade" id="divVerDescendecia">


                </div>
            </div>
        </div>
    </div>





</script>


<script type="text/template" id="tmpFormRegSalida">
    <form>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="demo-is-inputsmall">Tipo Salida</label>
            <div class="col-sm-6">
                <select class="form-control" id="selTipoSalida">
                    <?php foreach($tiposalida as $key=>$value){
                        if( strtolower($value["nombre"]) != "muerte"){    ?>
                            <option value="<?=$value["idtiposalida"]?>"><?=$value["nombre"]?></option>
                        <?php }?>
                    <?php }?>
                </select>
            </div>
        </div>
        <br><br>
        <div id="divTipoSalida" >


        </div>
    </form>
</script>


<script type="text/template" id="tmpSelTipoAnimal">
    <select class="form-control" id="selTipoAnimal">
        <?php foreach($tipoanimal as $key=>$value){  ?>
                <option value="<?=$value["idtipoanimal"]?>"><?=$value["nombre"]?></option>
        <?php }?>
   </select>
</script>

<script type="text/template" id="tmpTbodyClases">
    <% _.each(data,function(i,k){ %>
        <tr>
            <td><%=(k+1)%></td>
            <td><%=i.claseanimal%>
                <input type="hidden" name="hidclaseanimal[]" value="<%=i.idclaseanimal%>">
            </td>
            <td><input type="date" name="fechaIni[]" value="<%if(typeof i.fechaestadoini != 'undefined'){print(i.fechaestadoini)}%>" ></td>
            <td><% var check="";
                   var isCheckedEdit=0;
                if(typeof i.isclaseactual != 'undefined'){
                    if(i.isclaseactual == 1){
                        isCheckedEdit=1;
                     }
                }
                if(i.orden == 1  || isCheckedEdit ==1){
                check="checked";
                }%>
                <input type="radio" name="claseactivo" value="<%=i.idclaseanimal%>" <%=check%>>
            </td>
        </tr>
    <% }); %>
</script>

<script type="text/javascript">
var issetCodAnimalX=0;


function calculaEdad(dateString) {
    var today = new Date();
    var birthDate = new Date(dateString);
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
}
var tableT;
var tmpBodyModalFormRegGanado=_.template($("#tmpBodyModalFormRegGanado").html());
var tmpTbodyClases=_.template($("#tmpTbodyClases").html());

    $(document).ready(function () {
        iniDtAnimales();
    });
//

function iniDtAnimales() {
    tableT = $('#tabla_grid').DataTable({
        "ajax": url_base + 'Invganado/getDataTable',

        "columns": [
            {"data": null},
            {"data": "codanimal"},
            {"data": "tipoanimal" },
            {"data": "claseactual" },
            {"data": "codraza"},
            {
                sortable: false,
                "render": function (data, type, full, meta) {
                    var html = formatDateDMY(full.fechanacimiento) ;
                    return html;
                }
            },

            {
                sortable: false,
                "render": function (data, type, full, meta) {
                    var id = parseInt(full.idanimal);
                    html='';
                    //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                    html=html+'&nbsp; <a href="javascript:void(0)" onclick="editar('+id +');"  class="btn btn-mint btn-icon btn-xs"><i class="demo-psi-pen-5 icon-xs"></i>Ver</a>';

                    html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar('+id +');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-xs"></i> Eliminar</a>';

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
        if(isDelete == 0){
            alert_success("Se actualizo correctamente");
        }

    });
}
    $(document).on("click","#btnAdd",function () {
        //var t=_.template($("#tmpBodyForm").html());
        $("#divform").html(tmpBodyModalFormRegGanado({isEdit:0,idEdit:0,codAnimal:0}));
        $("#modalTitle").html("Registrar ganado");
        open_modal("modal_id");
        var selTipoAnimal=$("#selTipoAnimal").val();
        var selSexoAnimal=$("#selSexo").val();
        getDetalleClasesByTipoAnimalSexo(selTipoAnimal,selSexoAnimal);

    });


    function getDetalleClasesByTipoAnimalSexo(idtipoanimal,idsexo) {

        var dataSend={"idtipoanimal":idtipoanimal,"idsexo":idsexo};
        $.post(url_base+"Invganado/getClasesByTipoAnimalSexo",dataSend,function (data) {
            console.log(data);
            $("#tbodyClasesAnimal").html(tmpTbodyClases({data:data}));
        },'json');
    }

    function btnSave(d) {

        var form=$("#form").serialize();
        var bol=true;
        bol=bol&& $("#name").required();
        if(bol){
            $.post(url_base+"tareas/setForm",form,function (data) {

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
$(document).on("change","#selSexo,#selTipoAnimal",function () {
    var selTipoAnimal=$("#selTipoAnimal").val();
    var selSexoAnimal=$("#selSexo").val();
    getDetalleClasesByTipoAnimalSexo(selTipoAnimal,selSexoAnimal);
});


$(document).on("change","#fechanace",function () {
    var fechanace=$(this).val();
    var ageactual=calculaEdad(fechanace);
    $("#edad").val(ageactual);
});

function eliminar(id) {
    if(confirm('¿Esta seguro?')){
        var idd=parseInt(id);
        $.post(url_base+'Invganado/deleteData','id='+idd,function (data) {
            if( typeof data.status != "undefined" ){
                actualizar(1);
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
    $("#modalTitle").html("Información de ganado");
        var idd=parseInt(id);
        $.post(url_base+'Invganado/getData','id='+idd,function (data) {
            if(arrayLen(data) > 0 ){
                open_modal("modal_id");
                var dataR=data[0];
                $("#divform").html(tmpBodyModalFormRegGanado({isEdit:1,codAnimal:dataR.codanimal,idEdit:data[0].idanimal}));

                var seltipoanimal=dataR.idtipoanimal;
                var codanimal=dataR.codanimal;
                var idanimalpadre=dataR.idanimalpadre;
                var idaminalmadre=dataR.idanimalmadre;
                var fechanacimiento=dataR.fechanacimiento;
                var sexo=dataR.sexo;
                var codraza=dataR.codraza;
                var pureza=dataR.pureza  ;
                var proposito=dataR.proposito;
                var idtipoingresoanimal=parseFloat(dataR.idtipoingresoanimal)||0;
                var idtiposalidaanimal=parseFloat(dataR.idtiposalidaanimal)||0;
                var dataCliente=dataR.datacliente;
                var dataProveedor=dataR.dataproveedor;
                var dataClases=dataR.dataclases;
                var precioCompra=dataR.preciocompra;
                var precioVenta=dataR.precioventa;
                var pesonace=dataR.pesonace;
                var fechaingreso=dataR.fechaingreso;
               // console.log(idaminalmadre);
                $("#selTipoAnimal").val(seltipoanimal);
                $("#codanimal").val(codanimal);
                $("#codpadre").val(idanimalpadre);
                $("#codmadre").val(idaminalmadre);
                $("#fechanace").val(fechanacimiento);
                $("#edad").val(calculaEdad(fechanacimiento));
                $("#selSexo").val(sexo);
                $("#raza").val(codraza);
                $("#pureza").val(pureza);
                $("#proposito").val(proposito);
                $("#pesonace").val(pesonace);
                $("#selTipoEntrada").val(idtipoingresoanimal);
                $("#selTipoSalida").val(idtiposalidaanimal);

                $("#preciocompra").val(precioCompra);
                $("#precioventa").val(precioVenta);
                $("#fechaingreso").val(fechaingreso);

                if(dataCliente.length > 0){
                    $("#selCliente").val(parseInt(dataCliente[0].idcliente));
                }
                if(dataProveedor.length > 0){
                    $("#selProveedor").val(parseInt(dataProveedor[0].idproveedor));
                }

                $("#tbodyClasesAnimal").html(tmpTbodyClases({data:dataClases}));

                /*var t=_.template($("#tmpBodyForm").html());
                $("#divform").html(t(datae));*/
            }else{
                alert_error("Ocurrio algo inesperado");
            }
            console.log(data);
        },'json').error(function () {
            alert_error("Ocurrio algo inesperado");
        });

}


// save-----
$(document).on("click","#btnSaveAnimal",function () {
    var btn=$(this);
    var btnCancel=$("#btnClose");
    var formGanado=$("#formRegGanado").serialize();
    if(issetCodAnimalX== 1){
        alert_error("El codigo de animal ya se registró");
        return 0;
    }

    if(confirm("Esta seguro de registrar el elemento?")){

        btn.button("loading");
        btnCancel.button("loading");
    $.post(url_base+"invganado/setForm",formGanado,function (data) {
            console.log(data);
            if(data.status == "ok"){
                close_modal("modal_id");
                actualizar(1);
                alert_success("Se realizo Correctamente");

            }else{
                console.log(formGanado);
            }
        },"json");
    }

});
 
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
    }else if(tipotiempo == "vacaprod"){
        fechaini=$("#fechaIniProdVaca").val();
        fechaend=$("#fechaFinProdVaca").val();
    }
    //var urlReport=""+tipotiempo+"/"+tipodoc+"/"+anioReport+"/"+mesIniReport+"/"+mesEndReport;
    //console.log(urlReport);
    var urlReport=tipotiempo+"/"+tipodoc+"/"+rtipoanimal+"/"+fechaini+"/"+fechaend;
    window.open(url_base+"Reportes/reportInventario/"+urlReport,"", "width=400,height=300");

}




$(document).on("click","#btnActualizarClases",function () {
    open_modal("modalActualizaClases");
    $("#divDescActualizaClaseX").css("display","none");
    getFechaFinalActualizaAnimales();


});

function getFechaFinalActualizaAnimales(){
    $.post(url_base+"invganado/finalFechaActualizaClase",function (data) {
        //console.log(data);
        $("#labelFechaUltimaAct").html(data.fechareg);
    },'json');
}
$(document).on("click","#btnActualizaClaseNow",function () {
    var btn=$(this);
    btn.button("loading");
    $("#divDescActualizaClaseX").css("display","block");
    var xty=0;
    $.post(url_base+"invganado/getDataAnimalesActualizaClase",function (data) {

        if(data.status=="ok"){
            actualizar();
        }
        xty="ok";
    },'json');
    var n = 0;
    var timerx=setInterval(function(){

        n++;
        var pgbar=$('#progressBar');
        pgbar.css('width',n + '%');
        pgbar.html(n + '%');
        if(n==100 || xty=="ok" ){
            pgbar.css('width','100%');
            pgbar.html('100%');
            clearInterval(timerx);
            alert_success("Se Actualizo Correctamente");
            btn.button("reset");
            getFechaFinalActualizaAnimales();
        }
    },1000);
});

$(document).on("click","#ismuertoalnacer",function () {
    var ctx=$(this);
    if($("#ismuertoalnacer").is(':checked')) {
        $("#divDescMuertoalNacer").css("display","block");
    }else{
        $("#divDescMuertoalNacer").css("display","none");
    }
});

var arrHijosByPadre=[];
$(document).on("click","#verDependencia",function () {
    iniVerDescendencia();
});

function iniVerDescendencia(){
    arrHijosByPadre=[];
    var divVerDescendecia=$("#divVerDescendecia");
    var idanimal= $("#codAminal").val();
    divVerDescendecia.html("Cargando...");
    if(idanimal > 0){
        var tt=_.template($("#tmpVerDescendecniaAnimal").html());
        $.post(url_base+"invganado/verdescendencia",{"idanimal":idanimal},function (data) {
            console.log(data);
            var cc=data.length;
            if(cc > 0){
                $("#divVerDescendecia").html(tt({codanimalx:idanimal,data:data}));
            }else{
                $("#divVerDescendecia").html("<h3>Sin  descendencia <button onclick='addDescendencia("+idanimal+");' type='button' class='btn btn-purple btn-xs ' title='Agregar descendencia' >+</button></h3>");
            }
        },'json');
        console.log(idanimal);
    }else{
        divVerDescendecia.html("<h3>No se encuentran registros...</h3>");
    }
}


function issetCodAnimal(codanimal){
    $.post(url_base+"invganado/issetCodanimal",{"codanimal":codanimal},function (data) {

    },'json');
}




var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();

$(document).on("keyup","#codanimal",function () {
    var valx=$("#codanimal").val();
    delay(function(){
        $.post(url_base+"invganado/issetCodanimal",{"codanimal":valx},function (data) {
             if(parseInt(data.c)>0){
                $("#spanCodExiste").css("display","block");
                 issetCodAnimalX=1;
             }else{
                 $("#spanCodExiste").css("display","none");
                 issetCodAnimalX=0;
             }
        },'json');

    }, 250 );
});

$(document).on("keyup","#codanimaldescen",function () {
    var codanimalhijo=$("#codanimaldescen").val();
    var codanimalmadre=$("#idCodAnimalMadre").val();
    var tipoanimalmadre=$("#idTipoAnimalPadre").val();
    delay(function(){
        $.post(url_base+"invganado/issetCodanimalDescen",{"tipoanimalmadre":tipoanimalmadre,"codanimalmadre":codanimalmadre,"codanimalhijo":codanimalhijo},function (data) {
            var cc=(data.datacria).length;
            if(cc>0){
                $("#labelHelpAnimalIssetInventario").html("<b style='color:green;'>En los registros <i class='fa fa-check'></i></b>");
                $("#issetInInventario").val(1);

                var tmpIssetInRegAnimalHijo=_.template($("#tmpDivIssetInRegAnimalHijo").html());
                $("#divIssetInRegAnimalHijo").html(tmpIssetInRegAnimalHijo);
                $("#fechanacedescen").val(data.datacria[0].fechanacimiento);
                $("#codmadredescen").val(codanimalmadre);
                $("#codclasedescen").val(data.datacria[0].clase);
                $("#codrazadescen").val(data.datacria[0].codraza);
            }else{
                $("#labelHelpAnimalIssetInventario").html("<b style='color:Red;' >NO se encuentra en los registros <i class='fa fa-times'></i> </b>");
                var tmpDivNOIssetInRegAnimalHijo=_.template($("#tmpDivNOIssetInRegAnimalHijo").html());
                $("#divIssetInRegAnimalHijo").html(tmpDivNOIssetInRegAnimalHijo);
                $("#fechanacedescen").val("");
                $("#codmadredescen").val(codanimalmadre);
                $("#issetInInventario").val(0);
                var idsexo=$("#selSexoNewDescen").val();
                getClasesAnimalBySexoTipoNewDesc(tipoanimalmadre,idsexo);
            }
        },'json');

    }, 900 );
});




function addDescendencia(codanimal) {
    if (typeof codanimal === 'undefined'){
            return 0;
     }
      var idtipoanimal=0;
      idtipoanimal=$("#selTipoAnimal").val();
      open_modal("modalAddNewDescendencia");
      var tmpFormAddNewDesc=_.template($("#tmpFormAddNewDesc").html());
      $("#txtTitleModalCodAnimalDesc").html(codanimal);
      $("#divAddNewDescendencia").html(tmpFormAddNewDesc);
      $("#idTipoAnimalPadre").val(idtipoanimal);
      $("#idCodAnimalMadre").val(codanimal);

}

$(document).on("click","#btnSaveAddNewDesc",function () {
    var formx=$("#formAddNewDescenAnimal").serialize();
    var btn=$(this);
    var issetHijo=arrHijosByPadre.indexOf($("#codanimaldescen").val());
    if(issetHijo > -1){
        alert_error("El hijo ya fue agregado");
        return 0;
    }
    if($("#codanimaldescen").val() == $("#idCodAnimalMadre").val()){
        alert_error("El codigo pertenece a la madre");
        return 0;
    }

    if($("#issetInInventario").val() == "99" || $("#codanimaldescen").val() == ""){
        alert_error("Digite el oodigo del animal hijo");
        return 0;
    }
    btn.button("loading");

    $.post(url_base+"invganado/saveaddnewdescendencia",formx,function (data) {
        console.log(data);
        if(data.status == "ok"){
            close_modal("modalAddNewDescendencia");
            alert_success("Se registro correctamente");
            iniVerDescendencia();
        }else{

        }

        btn.button("Reset");
    },'json');
});

function getClasesAnimalBySexoTipoNewDesc(idtipoanimal,idsexo) {
    var tmpSelSexoNewDescen=_.template($("#tmpSelSexoNewDescen").html());
    $.post(url_base+"invganado/getClasesByTipoAnimalSexo",{"idtipoanimal":idtipoanimal,"idsexo":idsexo},function (data) {
        $("#divSelClaseAnimalNewDesc").html(tmpSelSexoNewDescen({data:data}))
    },'json');
}
    $(document).on("change","#selSexoNewDescen",function () {
        var tipoanimalmadre=$("#idTipoAnimalPadre").val();
        var idsexo=$("#selSexoNewDescen").val();
        getClasesAnimalBySexoTipoNewDesc(tipoanimalmadre,idsexo);
    });

</script>

<script type="text/template" id="tmpVerDescendecniaAnimal" >
    <div class="table-responsive">
        <h3>CodAnimal: <%=codanimalx%> <button onclick='addDescendencia("<%=codanimalx%>");' type='button' class='btn btn-purple btn-xs ' title='Agregar descendencia' >+</button> </h3>
        <table class="table table-striped table-hover table-bordered table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>CodAnimal</th>
                <th>Nacimiento</th>
                <th>Pureza</th>
                <th>Proposito</th>
                <th>Padre</th>
                <th>Madre</th>
                <th>Estado</th>
            </tr>
            </thead>
            <tbody>
            <% _.each(data,function(i,k){%>
            <tr>
                <td><%=(k+1)%></td>
                <td> <%=i.codanimal%> <% arrHijosByPadre.push(i.codanimal); %> </td>
                <td><%=i.fechanacimiento%>
                    <% if(i.ismuertoalnacer == 1){
                    print("<b style='color:red'>Muerto  </b> |");
                    print("<br><b>'"+i.descmuertoalnacer+"'</b>");
                    }else{
                    print("<b style='color:green'>Vivo </b> |");
                    } %> </td>
                <td><%=i.codraza%></td>
                <td><%=i.proposito%></td>
                <td><%=i.idanimalpadre%></td>
                <td><%=i.idanimalmadre%></td>
                <td>
                    <% if(i.estado == 1){ %>
                    <b style="color: green">En inventario</b>
                    <% } %>
                    <% if(i.estado == 2){ %>
                        <b style="color: purple">
                        <% if(i.idtiposalidaanimal == 1 ){%>
                    Salida x Venta
                        <%}%>
                        <% if(i.idtiposalidaanimal == 2){%>
                    Salida  x Muerte
                         <%}%>
                        <% if(i.idtiposalidaanimal == 3 ){%>
                    Salida  x Donación
                       <% }%>
                            </b>

                    <% } %>
                    <% if(i.estado == 3){ %>
                    <b style="color: purple">
                        Salida  x Muerte
                    </b>
                    <% } %>

                    <% if(i.estado == 4){ %>
                    <b style="color: purple">
                        Salida  x Donacion
                    </b>

                    <% } %>

                    <% if(i.estado == 100){ %>
                    <b style="color:red">Muerto al nacer</b>
                    <% } %>
                </td>
            </tr>

            <% }); console.log(arrHijosByPadre);%>

            </tbody>
        </table>
    </div>
</script>

<div class="modal fade"   id="modalActualizaClases" role="dialog" data-backdrop="static" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" ><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="">Actualiza Clases Animal</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divActualizaClases">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="demo-is-inputsmall">Última Actualización: </label>
                    <div class="col-sm-2">
                        <b id="labelFechaUltimaAct">Cargando...</b>
                    </div>
                    <div class="col-sm-2">
                       <button id="btnActualizaClaseNow" data-loading-text="Procesando..." class="btn btn-success btn-sm ">Actualizar</button>
                    </div>
                </div>
                <br>
                <hr style="margin-top: 2px;">
                <div id="divDescActualizaClaseX" style="display: none;">
                    <h3>Por favor espere...<br> Se esta  actualizando las clases de los animales.<br> NO CIERRE La Ventana hasta llegar al 100%</h3>
                    <div class="progress progress-striped progress-lg  active">
                        <div style="width: 0%;font-weight: bold;" id="progressBar" class="progress-bar progress-bar-danger">0%</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="modal fade"   id="modalAddNewDescendencia" role="dialog" data-backdrop="static" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 80%" >
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" ><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="">Agregar Descendencia al CODIGO: <b id="txtTitleModalCodAnimalDesc"> </b>  </h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divAddNewDescendencia">
            </div>
        </div>
    </div>
</div>

<script type="text/template" id="tmpFormAddNewDesc" >
    <div class="row">
        <form id="formAddNewDescenAnimal">
        <input id="idCodAnimalMadre" name="idCodAnimalMadre" type="hidden" value="0">
        <input id="idTipoAnimalPadre" name="idTipoAnimalPadre"  type="hidden" value="0">
        <input id="issetInInventario" name="issetInInventario" type="hidden" value="99">
        <div class="col-sm-2">
            <div class="form-group">
                <label class="control-label" title="Codigo de identificación">Código de Ident. </label>
                <input style="text-align: right" type="text" name="codanimaldescen" id="codanimaldescen" class="form-control"  >
                <small class="help-block" style="" id="labelHelpAnimalIssetInventario" ></small>

            </div>
        </div>
        <div id="divIssetInRegAnimalHijo">


        </div>

        </form>
    </div>
</script>

<script type="text/template" id="tmpDivIssetInRegAnimalHijo">
    <div class="col-sm-1">
        <div class="form-group">
            <label class="control-label" title="Codigo de identificación">Padre </label>
            <input style="text-align: right" type="text" class="form-control" value="" id="codpadredescen" name="codpadredescen">
        </div>
    </div>
    <div class="col-sm-1">
        <div class="form-group">
            <label class="control-label">Madre</label>
            <input style="text-align: right" type="text" class="form-control" value="" id="codmadredescen" name="codmadredescen" readonly="readonly">
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-group">
            <label class="control-label">Raza</label>
            <input style="text-align: right;padding-left: 1px;padding-right: 1px;" type="text" class="form-control" value="" id="codrazadescen" name="codrazadescen" readonly="readonly">
        </div>
    </div>
    <div class="col-sm-1">
        <div class="form-group">
            <label class="control-label">Clase</label>
            <input style="text-align: right" type="text" class="form-control" value="" id="codclasedescen" name="codclasedescen" readonly="readonly">
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-group">
            <label class="control-label">Fecha Nacimiento</label><br>
            <input style="text-align: right;padding: 0px;line-height: 25px;" type="date" class="form-control"   id="fechanacedescen" name="fechanacedescen" readonly="readonly" >
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-group">
            <label class="control-label">.</label><br>
            <button type="button" class="btn btn-success" id="btnSaveAddNewDesc">Guardar</button>
        </div>
    </div>
</script>


<script type="text/template" id="tmpDivNOIssetInRegAnimalHijo">
    <div class="col-sm-1">
        <div class="form-group">
            <label class="control-label" title="Codigo de identificación">Padre </label>
            <input style="text-align: right" type="text" class="form-control" value="" id="codpadredescen" name="codpadredescen">
        </div>
    </div>
    <div class="col-sm-1">
        <div class="form-group">
            <label class="control-label">Madre</label>
            <input style="text-align: right" type="text" class="form-control" value="" id="codmadredescen" name="codmadredescen" readonly="readonly">
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-group">
            <label class="control-label">Raza</label>
            <input style="text-align: right;padding-left: 1px;padding-right: 1px;" type="text" class="form-control" value="" id="codrazadescen" name="codrazadescen" >
        </div>
    </div>
    <div class="col-sm-1">
        <div class="form-group">
            <label class="control-label">Sexo</label>
            <select style="padding-left: 2px; padding-right: 2px;" class="form-control" id="selSexoNewDescen" name="selSexoNewDescen">
                <option value="1">Macho</option>
                <option value="2">Hembra</option>
            </select>
        </div>
    </div>
    <div class="col-sm-1">
        <div class="form-group">
            <label class="control-label">Clase</label>
            <div id="divSelClaseAnimalNewDesc">
                Cargando...
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-group">
            <label class="control-label">Fecha Nacimiento</label><br>
            <input style="text-align: right;padding: 0px;line-height: 25px;" type="date" class="form-control"   id="fechanacedescen" name="fechanacedescen"  >
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-group">
            <label class="control-label">.</label><br>
            <button type="button" class="btn btn-success" id="btnSaveAddNewDesc">Guardar</button>
        </div>
    </div>
</script>

<script type="text/template" id="tmpSelSexoNewDescen">
    <select style="padding-left: 2px; padding-right: 2px;" class="form-control" id="selClaseAnimalNewDescen" name="selClaseAnimalNewDescen">
        <% _.each(data,function(i,k){%>
        <option value="<%=i.idclaseanimal%>"><%=i.claseanimal%> </option>
        <% })%>
    </select>
</script>