<div id="page-content">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel">
                <div class="panel-heading">

                    <div class="panel-control">
                        <button class="demo-panel-ref-btn btn btn-default" data-toggle="panel-overlay" onclick="refrescar();"  ><i class="demo-psi-repeat-2"></i></button>
                    </div>
                    <h3 class="panel-title"  ><b style="font-size: 18px;">Compras</b>  &nbsp; <button class="btn btn-mint btn-sm" id="btnAddNew">Nuevo+</button> </h3>
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
                                <th>Proveedor</th>
                                <th>Wallet</th>
                                <th>Btc Comprado</th>
                                <th>Valor BTC</th>
                                <th>Precio Pagado</th>
                                <th>Precio Venta</th>
                                <th>Fecha</th>
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
    var proveedores=<?=json_encode($proveedores)?>;
    $(document).ready(function() {
        dtIni();
    });

function dtIni(){
    dtTable=$('#dtTable').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?= base_url(); ?>compras/getData",
            "type": "POST"
        },
        "buttons": [
            'pdf', 'print', 'excel', 'copy', 'csv',
        ],
        "columns": [
            { "data": null,"searchable":false },
            { "data": "nproveedor" },
            { "data": "wallet" },
            { "data": "cantbtc" },
            { "data": "preciounidadbtc" },
            {   "sortable": false,
                "searchable":false,
                "render": function ( data, type, full, meta ) {
                    var detpapgo = full.detallepago;
                    var ff="";
                    var html="";
                    var totalPagadoX=0;
                    if( detpapgo.length >0 ){
                        if(detpapgo.indexOf(',') != -1){
                            var arrCuentas=detpapgo.split(",");
                            $.each(arrCuentas,function (kk,ii) {
                                var arrF=ii.split("-");
                                var valMontoByCambio=0;

                                if(arrF[1] == "$"){
                                    valMontoByCambio= arrF[2] * parseFloat(Number(arrF[3]));
                                    totalPagadoX=totalPagadoX+valMontoByCambio;
                                    ff+="<p style='margin-bottom: 0px;'>"+arrF[0]+" "+arrF[4]+" :"+arrF[1]+""+arrF[2]+" S/"+valMontoByCambio+"</p>";
                                }else{
                                    totalPagadoX=totalPagadoX+ parseFloat(Number(arrF[2]));
                                    ff+="<p style='margin-bottom: 0px;' >"+arrF[0]+""+arrF[4]+" :"+arrF[1]+""+arrF[2]+"</p>";
                                }
                            });
                            ff+="<p style='margin-bottom: 0px;' ><b>Total S/"+totalPagadoX+" </b> </p>";
                        }else{
                            var arrF=detpapgo.split("-");
                            var valMontoByCambio=0;
                            if(arrF[1] == "$"){
                                valMontoByCambio= arrF[2] * parseFloat(Number(arrF[3]));
                                totalPagadoX=totalPagadoX+valMontoByCambio;
                                ff+="<p style='margin-bottom: 0px;' >"+arrF[0]+" "+arrF[4]+" :"+arrF[1]+""+arrF[2]+" S/"+valMontoByCambio+"</p>";
                            }else{
                                totalPagadoX=totalPagadoX+ parseFloat(Number(arrF[2]));
                                ff+="<p style='margin-bottom: 0px;'>"+arrF[0]+" "+arrF[4]+" :"+arrF[1]+""+arrF[2]+"</p>";
                            }
                            ff+="<p style='margin-bottom: 0px;' ><b>Total S/"+totalPagadoX+" </b> </p>";
                        }

                    }
                    html=""+ff;
                     return html;
                }
            },
            { "data": "preciobaseventa" },
            { "data": "fechacompra" },
            {   "sortable": false,
                "searchable":false,
                "render": function ( data, type, full, meta ) {
                    var id = full.idcompra;
                    html="";
                    //html='<a href="javascript:void(0)"  onclick="ver('+id+');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>';
                    //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                    html=html+"&nbsp; <a href='javascript:void(0)' onclick='editar("+id +");'  class='btn btn-mint  btn-xs'><i class='fa fa-file'></i> Editar</a>";

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
    } );


}

function refrescar () {
    dtTable.ajax.reload();
}

$(document).on("click","#btnAddNew",function () {
    $("#modalId").modal("show");
    $("#hModalTitle").html("Registrar Compra");
    var tmpFormCompra=_.template($("#tmpFormCompra").html());
    $("#bModalBody").html(tmpFormCompra);
    $('#proveedor').chosen({width:'80%'});
});

function calcSubByCuenta(thisx){
    var ctx=$(thisx);
    var table=ctx.closest("table");
    var montos=table.find("input[name='monto[]']");
    var subTo=table.find("input[name='subTotal[]']");
    var tasa=table.find("input[name='tasaCambio']");
    var ssubTotalx=table.find("input[name='ssubTotal']");
    var ttasa=1;
    if(tasa.length>0){
        ttasa=tasa.val();
    }
    var subMonto=0;
    $.each(montos,function (k,i) {
        subMonto+=Number($(i).val());
    });
    ssubTotalx.val(subMonto);
    subTo.val(subMonto*ttasa);

    var dataSubTotal=$("input[name='subTotal[]']");
    var ttotal=0;
    $.each(dataSubTotal,function (k,i) {
        ttotal=ttotal+Number($(i).val());
        console.log(ttotal);
    });
    $("#total").html(ttotal);
}


$(document).on("keyup","#cantsoles",function () {
    var pcbtc=$("#pccom");
    var cantbtc=$("#cantbtc");


    var tt=Number(parseFloat($(this).val()))/Number(parseFloat(pcbtc.html()));
    cantbtc.val(tt.toFixed(8));
    $("#totalAPagar").html($(this).val());
});


    $(document).on("keyup","#cantbtc",function () {
        var pcbtc=$("#pccom"); //cabmio
        var cantsoles=$("#cantsoles");
        var tt=Number(parseFloat($(this).val()))*Number(parseFloat(pcbtc.html()));
        cantsoles.val(tt.toFixed(2));
        $("#totalAPagar").html(tt.toFixed(2));
    });

    $(document).on("keyup","#pcbtc,#pcbtcsincomision",function () {
        var pcbtc=$("#pcbtcsincomision");
        var pcomision=$("#pcomision");
        var pccom=$("#pccom");
        var pccom_=parseFloat($("#pcbtcsincomision").val())+(parseFloat(pcbtc.val()) * parseFloat(pcomision.val())/100);
        pccom.html(pccom_);
        $("#pcbtc").val(pccom_);
         $("#cantsoles").val(pccom_*$("#cantbtc").val())
        $("#totalAPagar").html(pccom_*$("#cantbtc").val());
    });

     $(document).on("keyup","#pcomision",function () {
        var pcbtc=$("#pcbtcsincomision");
        var pcomision=$("#pcomision");
        var pccom=$("#pccom");
        var pccom_=parseFloat($("#pcbtcsincomision").val())+(parseFloat(pcbtc.val()) * parseFloat(pcomision.val())/100);
        pccom.html(pccom_);
        $("#pcbtc").val(pccom_);

        $("#cantsoles").val(pccom_*$("#cantbtc").val())
        $("#totalAPagar").html(pccom_*$("#cantbtc").val());
    });

  $(document).on("click","#btnSaveForm",function () {
      var form=$("#formRegData").serialize();
      $.post(url_base+"compras/setForm",form,function (data) {
         if(data.status == "ok"){
             refrescar();
             alert_success("Correcto");
             if($("#isEdit").val() == 0){
                 var tmpFormCompra=_.template($("#tmpFormCompra").html());
                 $("#bModalBody").html(tmpFormCompra);
                 $('#proveedor').chosen({width:'80%'});
             }else{
                 $("#modalId").modal("hide");
             }
         }else{
             alert_success("Fallo");
         }

      },'json');
  });

  function eliminar(id) {
      if(!confirm("¿Seguro de eliminar este registro?")){
          return 0;
      }
      $.post(url_base+"compras/delete",{"id":id},function (data) {
          if(data.status == "ok"){
              refrescar();
              alert_success("Correcto");

          }else{
              alert_success("Fallo");
          }
      },'json');
  }

  
  function editar(id) {
      $("#modalId").modal("show");
      $("#hModalTitle").html("Editar Compra");
      $("#bModalBody").html("Cargando...");
      $.post(url_base+"compras/getDataEdit",{id:id},function (data) {
          if(data.status=="ok" && data.data.length > 0){
              var dtEdit=data.data[0];
              var tmpFormCompra=_.template($("#tmpFormCompra").html());
              $("#bModalBody").html(tmpFormCompra);
              $("#isEdit").val(1);
              $("#idEdit").val(id);
              $("#proveedor").val(dtEdit.proveedor);
              $("#wallet").val(dtEdit.wallet);
              var mydate = new Date(dtEdit.fechacompra);
              var dia=mydate.getDate();
              var mes=(mydate.getMonth()+1);
              var anio=mydate.getFullYear();
              if (mes < 10) mes = '0' + mes;
              if (dia < 10) dia = '0' + dia;
              var ffxd=anio+"-"+mes+"-"+dia;

              var dtDetail=dtEdit.detallecompra;
              if(dtDetail.length>0){
                  dtDetail=dtDetail[0];
                  $("#cantbtc").val(parseFloat(dtDetail.cantbtc));
                  $("#cantsoles").val(dtDetail.cantmoneda);

                  $("#pventa").val(dtDetail.preciobaseventa);
                  $("#totalAPagar").html(dtDetail.cantmoneda);

                  $("#pcomision").val(dtDetail.comision);


                  var pcbtcsincomision=parseFloat(dtDetail.preciounidadbtcsincomision);
                  if(isNaN(pcbtcsincomision)){
                      let pcbtc=parseFloat(dtDetail.preciounidadbtc);
                      let pcom=parseFloat(dtDetail.comision) ||0;
                      pcbtcsincomision=pcbtc- (pcbtc*pcom/100)
                  }
                  $("#pcbtc").val(dtDetail.preciounidadbtc);
                  $("#pcbtcsincomision").val(pcbtcsincomision);
                  $("#pccom").html( dtDetail.preciounidadbtc);

              }
              var dtDetailPago=dtEdit.detallepago;
              var ttotalxxx=0;
              var sbsoles=0;
              var sbdolares=0;
              if(dtDetailPago.length>0){
                  var idcuentabancoAnterior=0;
                  var tasaCambioX=1;
                  $.each(dtDetailPago,function(kk,ii){

                      if(idcuentabancoAnterior == ii.idcuentabanco ){
                          var tr;
                          if(ii.abreviadomoneda  == "$"){
                              tr=$(".mdolares"+ii.idcuentabanco);
                          }
                          if(ii.abreviadomoneda  == "S/"){
                              tr=$(".msoles"+ii.idcuentabanco);
                          }
                          var divDataOpExtra=tr.find(".divDataOpExtra");
                          var divMontoExtra=tr.find(".divMontoExtra");
                          var ht1='<div class="divDataOp">\n' +'                                                     <div class="input-group" style="margin-top: 5px;">\n' +
                              '                                                    <span class="input-group-btn">\n' +
                              '                                                        <button class="btn btn-mint btn-sm" type="button">Nro Op.</button>\n' +
                              '                                                    </span>\n' +
                              '                                                         <input type="text" name="nrooperacion[]" value="'+ii.nrooperacion+'" class="form-control text-right input-sm nrooperacion'+ii.idcuentabanco+'">\n' +
                              '                                                     </div>\n' +
                              '                                                     <input type="hidden" name="cuentabanco[]" value="'+ii.idcuentabanco+'">\n' +
                              '                                                 </div>';
                          var ht2='<input style="margin-top: 5px;" type="text" name="monto[]" onkeyup="calcSubByCuenta(this)"  class="form-control text-right input-sm cuenta'+ii.idcuentabanco+'" value="'+ii.monto+'">';

                          divDataOpExtra.append(ht1);
                          divMontoExtra.append(ht2);

                      }else{
                          $(".cuenta"+ii.idcuentabanco).val(ii.monto);
                          $(".nrooperacion"+ii.idcuentabanco).val(ii.nrooperacion);
                      }
                      idcuentabancoAnterior=ii.idcuentabanco;
                      $("#tasaCambio").val(ii.tasacambio);
                      if(ii.abreviadomoneda == "$"){
                          sbdolares+=(parseFloat(ii.monto) );
                          tasaCambioX=parseFloat(ii.tasacambio);
                      }else if(ii.abreviadomoneda == "S/"){
                          sbsoles+=parseFloat(ii.monto);
                      }
                  });

                  $("#ssubTotal").val(sbdolares);
                  sbdolares=sbdolares*tasaCambioX;
                  $("#subTotalSoles").val(sbsoles);
                  $("#subTotalDolares").val(sbdolares);
              }
                calcSubByCuenta();
              $("#fecha").val(ffxd);
              $('#proveedor').chosen({width:'80%'});
          }else{
              alert_error("Fallo");
          }
      },'json');
  }


    $(document).on("click","#btnAddNewProveedor",function () {
        $("#modalIdNewForm").modal("show");
        var tmpFormProv=_.template($("#tmpFormProv").html());
        $("#bModalBodyNewForm").html(tmpFormProv);
    });

    $(document).on("click","#btnSaveFormProv",function () {
        var form=$("#formRegDataProv").serialize();
        $.post(url_base+"compras/setFormProv",form,function (data) {
            if(data.status == "ok"){
                refrescar();
                alert_success("Correcto");
                $("#modalIdNewForm").modal("hide");
                if(data.data.length > 0){
                    let $option = $('<option />', {
                        text: data.data[0].nombre,
                        value: data.data[0].idproveedor
                    });
                    $('#proveedor').prepend($option);
                    $("#proveedor").val(data.data[0].idproveedor);

                    $("#proveedor").trigger("chosen:updated");
                }else{

                }

            }else{
                alert_success("Fallo");
            }
        },'json');
    });
    
    function addNewOp(thx) {
        var ctx=$(thx);
        var tr=$(ctx).closest("tr");
        var div=tr.find(".divDataOp");
        var divDataOpExtra=tr.find(".divDataOpExtra");
        var divMonto=tr.find(".divMonto");
        var divMontoExtra=tr.find(".divMontoExtra");
        divDataOpExtra.append(div.html());
        divMontoExtra.append(divMonto.html());
    }

</script>



<script type="text/template" id="tmpFormProv">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel">

                <form id="formRegDataProv" class="form-horizontal">

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
                        <button class="btn btn-primary" type="button" id="btnSaveFormProv">Guardar</button>
                        <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                    </div>

                </form>
                <!--===================================================-->
                <!--End Block Styled Form -->

            </div>
        </div>
    </div>


</script>


<script type="text/template" id="tmpFormCompra">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel">

                <form id="formRegData">

                    <input type="hidden" id="isEdit" name="isEdit" value="0">
                    <input type="hidden" id="idEdit" name="idEdit" value="0" >
                    <div class="panel-body" style="padding-top: 0px;padding-bottom: 10px;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Proveedor</label><br>
                                    <select data-placeholder="Seleccione..." id="proveedor" name="proveedor" tabindex="2">
                                         <option value="99999">Proveedor Varios</option>
                                        <?php foreach($proveedores as $k=>$i){  ?>
                                            <option value="<?=$i["idproveedor"]?>"><?=$i["nombre"]?></option>
                                        <?php }?>
                                    </select>
                                    <button  style="display:inline-block;" id="btnAddNewProveedor" type="button" class="btn btn-sm btn-mint"  ><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Fecha</label>
                                    <input type="date" id="fecha" name="fecha" value="<?=date("Y-m-d")?>" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Wallet</label>
                                    <input type="text" id="wallet" name="wallet" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="control-label" >Detalle</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                             <div class="col-sm-4">
                                <div class="form-group" style="margin-bottom: 0px;"  >
                                    <label class="control-label" title="Precio Compra">Precio Compra BTC / FEE* /FINAL</label><br>
                                    <input style="width: 40%;display: inline-block;text-align: right;" type="text" id="pcbtcsincomision" name="pcbtcsincomision" class="form-control" value="0">
                                    <input style="width: 25%;display: inline-block;text-align: right;"  class="form-control" type="text" id="pcomision" name="pcomision" value="0">
                                    =<span id="pccom"></span>
                                    <input type="hidden" name="pcbtc" id="pcbtc">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="control-label">Cant BTC</label>
                                    <input type="text" id="cantbtc" name="cantbtc" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="control-label">Cant Soles</label>
                                    <input type="text" id="cantsoles" name="cantsoles" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group" style="margin-bottom: 0px;" >
                                    <label class="control-label">Precio para Venta</label>
                                    <input type="text" id="pventa" name="pventa"  class="form-control">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group center-block  " style="text-align: center">
                                <label class=" control-label" for="demo-hor-inputemail"><b>Cuenta en Soles</b></label>
                            </div>

                            <table class="table table-bordered table-hover table-condensed">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Banco</th>
                                        <th>Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($bancos as $kk=>$ii){

                                    if(strtolower($ii["moneda"]) == "soles"){
                                        ?>
                                         <tr   class="msoles<?=$ii["idcuentabanco"]?>" data-trid="<?=$ii["idcuentabanco"]?>" >
                                             <td></td>
                                             <td ><b ><?=$ii["abreviatura"]?>: <?=$ii["ncuenta"]?> <button type="button" class="btn btn-xs btn-dark" onclick="addNewOp(this)" ><i class="fa fa-plus" ></i></button> </b>
                                                 <div class="divDataOp">
                                                     <div class="input-group" style="margin-top: 5px;">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-mint btn-sm" type="button"  >Nro Op.</button>
                                                    </span>
                                                         <input type="text" name="nrooperacion[]"   class="form-control text-right input-sm nrooperacion<?=$ii["idcuentabanco"]?>" >
                                                     </div>
                                                     <input type="hidden" name="cuentabanco[]" value="<?=$ii["idcuentabanco"]?>">
                                                 </div>
                                                <div class="divDataOpExtra"></div>

                                             </td>
                                            <td>
                                                <br>
                                                <div class="divMonto">
                                                    <input style="margin-top: 5px;" type="text" name="monto[]"   onkeyup="calcSubByCuenta(this)" class="form-control text-right input-sm cuenta<?=$ii["idcuentabanco"]?> " value="">
                                                </div>
                                                <div class="divMontoExtra"></div>

                                            </td>

                                      </tr>
                                    <?php }
                                } ?>
                                <tr>
                                    <td></td>
                                    <td><b>Sub total</b></td>
                                    <td>
                                        <input  type="text" id="subTotalSoles" name="subTotal[]"  readonly="readonly" class="form-control text-right input-sm " placeholder="0" >
                                    </td>

                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group center-block  " style="text-align: center">
                                <label class=" control-label" for="demo-hor-inputemail"><b>Cuenta en Dolares</b></label>
                            </div>
                            <table class="table table-bordered table-hover table-condensed ">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Banco</th>
                                    <th>Monto</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($bancos as $kk=>$ii){
                                    if(strtolower($ii["moneda"]) == "dolares"){
                                        ?>
                                        <tr class="mdolares<?=$ii["idcuentabanco"]?>" data-trid="<?=$ii["idcuentabanco"]?>" >
                                            <td></td>
                                            <td style="width: 50%"><b ><?=$ii["abreviatura"]?>: <?=$ii["ncuenta"]?> <button type="button" class="btn btn-xs btn-dark" onclick="addNewOp(this)" ><i class="fa fa-plus" ></i></button> </b>
                                                <div class="divDataOp">
                                                    <div class="input-group" style="margin-top: 5px;">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-mint btn-sm" type="button"  >Nro Op.</button>
                                                    </span>
                                                        <input type="text" name="nrooperacion[]"   class="form-control text-right input-sm nrooperacion<?=$ii["idcuentabanco"]?>" >
                                                    </div>
                                                    <input type="hidden" name="cuentabanco[]" value="<?=$ii["idcuentabanco"]?>">
                                                </div>
                                                <div class="divDataOpExtra"></div>

                                            </td>


                                            <td style="width: 50%" ><br>

                                                <div class="divMonto">
                                                    <input style="margin-top: 5px;" type="text" name="monto[]"   onkeyup="calcSubByCuenta(this)" class="form-control text-right input-sm cuenta<?=$ii["idcuentabanco"]?> " value="">
                                                </div>
                                                <div class="divMontoExtra"></div>
                                           </td>

                                        </tr>
                                    <?php }
                                } ?>
                                <tr>
                                    <td></td>
                                    <td><b>Tasa de Cambio S/</b></td>
                                    <td style="display: inline-block;text-align: right;">
                                        <input style="display: inline-block;width: 30%;" type="text" name="tasaCambio" id="tasaCambio"  onkeyup="calcSubByCuenta(this);"  class="form-control text-right input-sm " value="0">
                                        <input style="display: inline-block;width: 15%;font-weight: bold;" type="text"  value="$"  class="form-control text-right input-sm "  disabled="disabled">
                                        <input style="display: inline-block;width: 50%;" type="text" id="ssubTotal" name="ssubTotal"  class="form-control text-right input-sm "  readonly="readonly">
                                    </td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td><b> Sub total</b></td>
                                    <td>
                                        <input type="text" id="subTotalDolares" name="subTotal[]"  readonly="readonly" class="form-control text-right input-sm " placeholder="0" >
                                    </td>

                                </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>
                    <div class="row" style="text-align: center">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-6">
                            <b>Total A pagar S/ <b id="totalAPagar" >0</b> </b><br>
                            <b>Total pagado S/ <b id="total" >0</b> </b>
                        </div>
                        <div class="col-sm-3">
                        </div>
                    </div>

                   </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id="btnSaveForm">Guardar</button>
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



<div class="modal fade" id="modalIdNewForm" role="dialog" tabindex="-1"  data-backdrop="static"   aria-hidden="true" >
    <div class="modal-dialog ">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="">Registrar Proveedor</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="bModalBodyNewForm">


            </div>
        </div>
    </div>
</div>