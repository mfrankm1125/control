<div id="page-content">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel">
                <div class="panel-heading">

                    <div class="panel-control">
                        <button class="demo-panel-ref-btn btn btn-default" data-toggle="panel-overlay" onclick="refrescar();"  ><i class="demo-psi-repeat-2"></i></button>
                    </div>
                    <h3 class="panel-title"  ><b style="font-size: 18px;"><i class="fa fa-credit-card"></i> Cuentas bancarias</b>  &nbsp; <button class="btn btn-mint btn-sm" id="btnAddNew">Nuevo+</button> </h3>
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
                                <th>Banco</th>
                                <th>Nombre cuenta</th>
                                <th>Moneda</th>
                                <th>Nro Cuenta</th>
                                <th>CCI</th>
                                <th>Estado</th>
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
    var dataSelBancos=<?=json_encode($bancos)?>;
    var dataSelMonedas=<?=json_encode($monedas)?>;
    $(document).ready(function() {

        dtIni();
    });
function getSelBancos(idtosel) {
var dt=dataSelBancos;
var tmpSelBancos=_.template($("#tmpSelBancos").html());
    tmpSelBancos=tmpSelBancos({data:dt,idtosel:idtosel});
return tmpSelBancos;
}

function getSelMonedas(idtosel) {
    var dt=dataSelMonedas;
    var tmpSelMonedas=_.template($("#tmpSelMonedas").html());
    tmpSelMonedas=tmpSelMonedas({data:dt,idtosel:idtosel});
    return tmpSelMonedas;
}

function dtIni(){
    dtTable=$('#dtTable').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?= base_url(); ?>cuentasbancarias/getData",
            "type": "POST"
        },
        "buttons": [
            'pdf', 'print', 'excel', 'copy', 'csv',
        ],
        "columns": [
            { "data": null,"searchable":false },
            { "data": "nbanco" },
            { "data": "ncuentabanco" },
            { "data": "nmoneda" },
            { "data": "nro" },
            { "data": "cci" },
            {  "sortable": false,
                "searchable":false,
                "render": function ( data, type, full, meta ) {
                    var id = full.idcuentabanco;
                    var estado = full.estado;
                    var Act1="";
                    var Act2="";
                    if(estado == 2){
                        Act2="selected='selected'"
                    }else{
                        Act1="selected='selected'";
                    }
                    var html="";
                    html=html+' <select class="form-control input-sm" name="selActivoBanco" style="width: 89%;display: inline-block;">\n' +

                        '        <option value="1-'+id+'"  '+Act1+' >&#10003 Activo</option>\n' +
                        '        <option value="2-'+id+'" '+Act2+' >&#10007 Inactivo</option>\n' +

                        '    </select>' +
                        '<span class="help-inline textData" style="display:block;font-size: 12px;" ></span>';
                    return html;
                }
             },
            { "data": "fechareg" },

            {   "sortable": false,
                "searchable":false,
                "render": function ( data, type, full, meta ) {
                    var id = full.idcuentabanco;

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
        "pageLength": 50,
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
    } );


}

function refrescar () {
    dtTable.ajax.reload();
}

$(document).on("change","select[name='selActivoBanco']",function () {
    var ctx=$(this);
    var td=ctx.closest("td");
    var span=td.find(".textData");
    console.log(ctx.val());
    var dd=(ctx.val()).split("-") ;
    var estado=dd[0];
    var id=dd[1];
    span.html("Procesando...");
    span.css("display","block");
    $.post(url_base+"cuentasbancarias/setEstadoCuenta",{estado:estado,id:id},function (data) {
        if(data.status =="ok"){
        alert_success("Se Realizo correctamente");
        }
        refrescar();
    });
});

$(document).on("click","#btnAddNew",function () {
    $("#modalId").modal("show");
    $("#hModalTitle").html("Registrar Cuenta Bancaria");
    var tmpForm=_.template($("#tmpForm").html());
    $("#bModalBody").html(tmpForm);
    $("#divSelBanco").html(getSelBancos(0));
    $("#divSelMoneda").html(getSelMonedas(0));
});


  $(document).on("click","#btnSaveForm",function () {
      var form=$("#formRegData").serialize();
      $.post(url_base+"cuentasbancarias/setForm",form,function (data) {
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
      $.post(url_base+"cuentasbancarias/delete",{"id":id},function (data) {
          if(data.status == "ok"){
              refrescar();
              alert_success("Correcto");

          }else{
              alert_success("Fallo");
          }
      },'json');
  }

$(document).on("click","#btnNewBanco",function () {
    var tmpFormNewBanco=_.template($("#tmpFormNewBanco").html());
    $("#modalIdAlter").modal("show");
    $("#bModalBodyAlter").html(tmpFormNewBanco);
    $("#hModalTitleAlter").html("Nuevo banco");
});

$(document).on("click","#btnNewMoneda",function () {
     var tmpFormNewMoneda=_.template($("#tmpFormNewMoneda").html());
    $("#modalIdAlter").modal("show");
    $("#bModalBodyAlter").html(tmpFormNewMoneda);
    $("#hModalTitleAlter").html("Nueva moneda");
});


    $(document).on("click","#btnSaveBanco",function () {
       var formBanco=$("#formRegBanco").serialize();
       $.post(url_base+"cuentasbancarias/setFormRegBanco",formBanco,function (data) {
            if(data.status == "ok"){
                if((data.data).length > 0){
                    let $option = $('<option />', {
                        text: data.data[0].nombre+" ("+data.data[0].abreviatura+")",
                        value: data.data[0].idbanco
                    });
                    $('#selBancos').prepend($option);
                    $('#selBancos').val(data.data[0].idbanco);
                    alert_success("Correcto");
                }else{
                    alert_error("Error");
                }
            }else{
                alert_error("Error");
            }
           $("#modalIdAlter").modal("hide");
       },'json');
    });

    $(document).on("click","#btnSaveMoneda",function () {
        var formRegMoneda=$("#formRegMoneda").serialize();
        $.post(url_base+"cuentasbancarias/setFormRegMoneda",formRegMoneda,function (data) {
            if(data.status == "ok"){

                if((data.data).length > 0){
                    let $option = $('<option />', {
                        text: data.data[0].nombre+" ("+data.data[0].abreviado+")",
                        value: data.data[0].idmoneda
                    });
                    $('#selMonedas').prepend($option);
                    $('#selMonedas').val(data.data[0].idmoneda);
                    alert_success("Correcto");
                }else{
                    alert_error("Error");
                }
            }else{
                alert_error("Error");
            }

            $("#modalIdAlter").modal("hide");
        },'json');
    });

  function editar(id,data) {
      $("#modalId").modal("show");
      $("#hModalTitle").html("Editar Cuenta Bancaria");
      var tmpForm=_.template($("#tmpForm").html());
      $("#bModalBody").html(tmpForm);
      $("#divSelBanco").html(getSelBancos(0));
      $("#divSelMoneda").html(getSelMonedas(0));

      $("#isEdit").val(1);
      $("#idEdit").val(data.idcuentabanco);

      $("#selBancos").val(data.idbanco);
      $("#selMonedas").val(data.idmoneda);

      $("#ncuenta").val(data.ncuentabanco);
      $("#nrocuenta").val(data.nro);
      $("#cci").val(data.cci);


  }

</script>

<script type="text/template" id="tmpFormNewBanco">
    <form class="form-horizontal" id="formRegBanco">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="demo-hor-inputemail">Banco</label>
                <div class="col-sm-6">
                    <input type="text" id="banco" name="banco" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="demo-hor-inputemail">Abreviatura</label>
                <div class="col-sm-6">
                    <input type="text" id="abreviado" name="abreviado" class="form-control">
                </div>
            </div>

        </div>
        <div class="panel-footer text-center">
            <button class="btn btn-success" id="btnSaveBanco" type="button">Guardar</button>
            <button class="btn btn-danger" data-dismiss="modal" type="button">Cancel</button>
        </div>
    </form>
</script>
<script type="text/template" id="tmpFormNewMoneda">
    <form class="form-horizontal" id="formRegMoneda">
    <div class="panel-body">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="demo-hor-inputemail">Moneda</label>
            <div class="col-sm-6">
                <input type="text"  id="moneda" name="moneda" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="demo-hor-inputemail">Abreviatura(S/,$)</label>
            <div class="col-sm-6">
                <input type="text"  id="abreviatura" name="abreviatura" class="form-control">
            </div>
        </div>
    </div>
        <div class="panel-footer text-center">
            <button class="btn btn-success" id="btnSaveMoneda" type="button">Guardar</button>
            <button class="btn btn-danger  " data-dismiss="modal" type="button">Cancel</button>
        </div>
    </form>
</script>


<script type="text/template" id="tmpSelBancos">
    <select id="selBancos" class="form-control" name="selBancos" style="width: 89%;display: inline-block;">
        <%   _.each(data,function(i,k){ %>
        <option value="<%=i.idbanco%>"><%=i.nombre%>  (<%= i.abreviatura%>)</option>
       <% });%>

    </select>
    <button type="button" id="btnNewBanco" class="btn btn-info" style="width: 10%;display: inline-block;" ><i class="fa fa-plus"></i></button>
</script>


<script type="text/template" id="tmpSelMonedas">
    <select id="selMonedas" class="form-control" name="selMonedas"  style="width: 89%;display: inline-block;" >
        <%   _.each(data,function(i,k){ %>
        <option value="<%=i.idmoneda%>"><%=i.nombre%> (<%= i.abreviado%>)</option>
        <% });%>
    </select>
    <button type="button" class="btn btn-info" id="btnNewMoneda"  style="width: 10%;display: inline-block;" ><i class="fa fa-plus"></i></button>
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
                            <label class="col-sm-3 control-label" for="demo-hor-inputemail">Banco</label>
                            <div class="col-sm-6" id="divSelBanco">
                             </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="demo-hor-inputpass">Moneda</label>
                            <div class="col-sm-6" id="divSelMoneda">
                             </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="ncuenta">Nombre Cuenta</label>
                            <div class="col-sm-6">
                                <input type="text" name="ncuenta"  id="ncuenta" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="demo-hor-inputpass">Nro Cuenta</label>
                            <div class="col-sm-6">
                                <input type="text"   id="nrocuenta" name="nrocuenta" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="demo-hor-inputpass">CCI</label>
                            <div class="col-sm-6">
                                <input type="text" id="cci" name="cci" class="form-control">
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

<div class="modal fade" id="modalIdAlter" role="dialog" tabindex="-1"  data-backdrop="static"   aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="hModalTitleAlter"> </h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="bModalBodyAlter">
            </div>
        </div>
    </div>
</div>