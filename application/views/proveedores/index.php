<div id="page-content">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel">
                <div class="panel-heading">

                    <div class="panel-control">
                        <button class="demo-panel-ref-btn btn btn-default" data-toggle="panel-overlay" onclick="refrescar();"  ><i class="demo-psi-repeat-2"></i></button>
                    </div>
                    <h3 class="panel-title"  ><b style="font-size: 18px;"><i class="fa fa-address-card-o"></i> Proveedores </b>  &nbsp; <button class="btn btn-mint btn-sm" id="btnAddNew">Nuevo+</button> </h3>
                    <hr style="margin-bottom: 0px;margin-top: 0px;">
                </div>

                <!--Data Table-->

                <!--===================================================-->
                <div class="panel-body">
                    <div class="row">
                        <table id="dtTable" class="table table-bordered table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>País</th>
                                <th>Nombres</th>
                                <th>Apellidos </th>
                                <th>Documento</th>
                                <th>Razon Social</th>
                                <th>Ruc</th>
                                <th>Fecha registro</th>
                                <th>Acción</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                </div>
                <!--===================================================-->
                <!--End Data Table-->

            </div>
        </div>
    </div>
</div>

<?php echo $_js?>
<?php echo $_css?>
<script type="text/javascript">
    var dtTable;
    var nRegistsro="Proveedor";

    $(document).ready(function() {
        dtIni();
    });

    function dtIni(){
        dtTable=$('#dtTable').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url(); ?>proveedores/getData",
                "type": "POST"
            },
            "buttons": [
                'pdf', 'print', 'excel', 'copy', 'csv',
            ],
            "columns": [
                { "data": null,"searchable":false },
                { "data": "pais" },
                { "data": "nombre" },
                { "data": "apellidos" },
                { "data": "documento" },
                { "data": "razonsocial" },
                { "data": "ruc" },
                { "data": "fechareg" },

                {   "sortable": false,
                    "searchable":false,
                    "render": function ( data, type, full, meta ) {
                        var id = full.idproveedor;

                        html="";
                        //html='<a href="javascript:void(0)"  onclick="ver('+id+');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>';
                        //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                        html=html+"&nbsp; <a href='javascript:void(0)' onclick='editar("+id +","+JSON.stringify(full)+");'  class='btn btn-mint  btn-xs'><i class='fa fa-file'></i> Editar</a>";

                        html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar('+id +');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-xs"></i> Eliminar</a>';
                        return html;
                    }
                }
            ],
            "responsive": true,
            "pageLength": 10,
            "language": {
                "lengthMenu":     "Mostrar _MENU_ registros",
                "emptyTable":     "<b>Ningún dato disponible en esta tabla</b>",
                "zeroRecords":    "No se encontraron resultados",
                "info":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "search":       "Buscar:",
                "paginate": {
                    "previous": '<i class="demo-psi-arrow-left"></i>',
                    "next": '<i class="demo-psi-arrow-right"></i>'
                }
            },
            "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                var info = $(this).DataTable().page.info();
                $("td:nth-child(1)", nRow).html(info.start + iDisplayIndex + 1);
                return nRow;
            }
        });
    }

    function refrescar () {
        dtTable.ajax.reload();
    }

    $(document).on("click","#btnAddNew",function () {
        $("#modalId").modal("show");
        $("#hModalTitle").html("Registrar "+nRegistsro);
        var tmpForm=_.template($("#tmpForm").html());
        $("#bModalBody").html(tmpForm);

    });


    $(document).on("click","#btnSaveForm",function () {
        var form=$("#formRegData").serialize();
        $.post(url_base+"proveedores/setForm",form,function (data) {
            if(data.status == "ok"){
                refrescar();
                alert_success("Correcto");
                $("#modalId").modal("hide");
            }else{
                alert_success("Fallo");
            }
        },'json');
    });

    function eliminar(id) {
        if(!confirm("¿Seguro de eliminar este registro?")){
            return 0;
        }
        $.post(url_base+"proveedores/delete",{"id":id},function (data) {
            if(data.status == "ok"){
                refrescar();
                alert_success("Correcto");
            }else{
                alert_success("Fallo");
            }
        },'json');
    }





    function editar(id,data) {
        $("#modalId").modal("show");
        $("#hModalTitle").html("Editar "+nRegistsro);
        var tmpForm=_.template($("#tmpForm").html());
        $("#bModalBody").html(tmpForm);

        $("#isEdit").val(1);
        $("#idEdit").val(data.idproveedor);
        $("#pais").val(data.pais);
        $("#nombres").val(data.nombre);
        $("#apellidos").val(data.apellidos);
        $("#documento").val(data.documento);
        $("#rsocial").val(data.razonsocial);
        $("#ruc").val(data.ruc);
        $("#fregistro").val(formatDateYMD(data.fechareg));


    }


</script>



<script type="text/template" id="tmpForm">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel">

                <form id="formRegData" class="form-horizontal">

                    <input type="hidden" id="isEdit" name="isEdit" value="0">
                    <input type="hidden" id="idEdit" name="idEdit" value="0" >

                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="ncuenta">País</label>
                            <div class="col-sm-6">
                                <input type="text" name="pais"  id="pais" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="ncuenta">Nombres</label>
                            <div class="col-sm-6">
                                <input type="text" name="nombres"  id="nombres" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="ncuenta">Apellidos</label>
                            <div class="col-sm-6">
                                <input type="text" name="apellidos"  id="apellidos" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="demo-hor-inputpass">Documento</label>
                            <div class="col-sm-6">
                                <input type="text"   id="documento" name="documento" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="ncuenta">Razon Social</label>
                            <div class="col-sm-6">
                                <input type="text" name="rsocial"  id="rsocial" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="ncuenta">RUC</label>
                            <div class="col-sm-6">
                                <input type="text" name="ruc"  id="ruc" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="demo-hor-inputpass">Fecha Registro</label>
                            <div class="col-sm-6">
                                <input type="date" value="<?=date("Y-m-d")?>"  id="fregistro" name="fregistro" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="button" id="btnSaveForm">Guardar</button>
                        <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                    </div>

                </form>
                <!--===================================================-->
                <!--End Block Styled Form -->

            </div>
        </div>
    </div>


</script>


<div class="modal fade" id="modalId" role="dialog" tabindex="-1"  data-backdrop="static"   aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="hModalTitle"> </h4>
            </div>

            <!--Modal body-->
            <div class="modal-body" id="bModalBody">


            </div>
        </div>
    </div>
</div>
