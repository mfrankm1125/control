<?php
$monedaxxx="USDT";
?>
<div id="page-content">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel">
                <div class="panel-heading">

                    <div class="panel-control">
                    	 <div class="input-group-wrap">
                            <div class="input-group">
                                <input type="text" id="nroOpSearch" placeholder="Buscar Nro Op" class="form-control">
                                <span class="input-group-btn">
					               <button class="btn btn-purple" id="btnSearchNroOperación" type="button">Buscar</button>
					            </span>
                            </div>
                        </div>
                        <ul class="pager pager-rounded">
                            <li><button type="button"   class="btn btn-dark" onclick="generarComprobante('','','','S/','',1,[])"> Nuevo Comprobante  </button></li>
                            <li><a href="javascript:void(0)" id="btnReporteVentas" > Reporte</a></li>

                        </ul>
                        <button class="demo-panel-ref-btn btn btn-default" data-toggle="panel-overlay" onclick="refrescar();"  ><i class="demo-psi-repeat-2"></i></button>
                    </div>
                    <h3 class="panel-title"  ><b style="font-size: 18px;">Ventas</b>  &nbsp; <button class="btn btn-mint btn-sm" id="btnAddNew">Nuevo+</button> </h3>
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
                                <th>ID Ref</th>
                                <th>Cliente</th>
                                <th>Wallet</th>
                                <th><?=$monedaxxx?> Vendido</th>
                                <th>Cantidad Recibida </th>
                                <th>Fecha venta</th>
                                <th>Acción</th>
                            </tr>
                            </thead>
                            <tbody >

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
<style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
<link href="<?=base_url()?>assets/scripts/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<?php echo $_css?>
<?php echo $_js?>
<script src="<?=base_url()?>assets/scripts/jquery-ui-1.12.1/jquery-ui.min.js"></script>

<script type="text/template" id="tmpSearchNroOp">
  <table class="table table-condensed table-bordered">
      <thead>
      <tr>
          <th>Cliente </th>
          <th>Banco/Cuenta</th>
          <th>Monto</th>
          <th>F.Reg</th>
      </tr>
      </thead>
      <tbody>
      <% _.each(data,function(i,k){%>
      <tr>
          <td><%=i.nombre +" "+ i.apellidos+" #"+i.wallet %> </td>

          <td><%=i.nbancoabreviatura +"|"+ i.ncuenta %></td>
          <td><%=i.abreviado +" "+ i.monto %></td>
          <td><%=i.fechaventa%></td>
      </tr>

      <%  }) %>

      </tbody>
  </table>
</script>


<script type="text/javascript">


    var dtTable;
    $(document).ready(function() {

        dtIni();

    });


    $(document).on("click","#btnSearchNroOperación",function(){
        var btn=$(this);
        $("#modalIdSearchNOP").modal("show");
        var inputSearchNoOP=$("#nroOpSearch").val();
        $("#nroOperacionLabel").html(inputSearchNoOP);
        btn.button("loading");
        var tmpSearchNOP=_.template($("#tmpSearchNroOp").html());
         $("#bModalSearchNOP").html("<h3>Cargando...</h3>");
        $.post(url_base+"ventas/searchNroOperacion",{nop:inputSearchNoOP},function (data) {
          $("#bModalSearchNOP").html(tmpSearchNOP({data:data.data}));
            btn.button("reset");
        },'json');
    });

function dtIni(){
    dtTable=$('#dtTable').DataTable( {
        "searchDelay": 850,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?= base_url(); ?>ventasusdt/getData",
            "type": "POST"
        },
        "columns": [
            { "data": null,"searchable":false },
            { "data": "idreferencia" },
            { "data": "clientenombre" },
            { "data": "wallet" },
            {   "sortable": false,
                "searchable":false,
                "render": function ( data, type, full, meta ) {
                    var id = full.idventa;
                    html="";
                    html='<a href="javascript:void(0)"  onclick="verDetalleVentaX('+id+',this);" class="btn btn-default btn-hover-purple btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>'+
                    '<div class="divClassVerDetalle"></div>';
                    //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                   // html=html+"&nbsp; <a href='javascript:void(0)' onclick='editar("+id +","+JSON.stringify(full)+");'  class='btn btn-mint  btn-xs'><i class='fa fa-file'></i> Editar</a>";
                    // html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar('+id +');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-xs"></i> Eliminar</a>';
                    return html;
                }
            },
            {   "sortable": false,
                "searchable":false,
                "render": function ( data, type, full, meta ) {
                    var id = full.idventa;
                    html="";
                    html='<a href="javascript:void(0)"  onclick="verDetallePagoX('+id+',this);" class="btn btn-default btn-hover-purple btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>' +
                        '<div class="divClassVerDetallePago"></div>';
                    //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                    // html=html+"&nbsp; <a href='javascript:void(0)' onclick='editar("+id +","+JSON.stringify(full)+");'  class='btn btn-mint  btn-xs'><i class='fa fa-file'></i> Editar</a>";
                    // html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar('+id +');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-xs"></i> Eliminar</a>';
                    return html;
                }
            },
            { "data": "fechaventa" },
            {   "sortable": false,
                "searchable":false,
                "render": function ( data, type, full, meta ) {
                    var id = full.idventa;
                    html="";
                       <?php if($this->session->userdata('id_role') != 18){ ?>
                    //html='<a href="javascript:void(0)"  onclick="ver('+id+');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>';
                    //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                    html=html+"&nbsp; <a href='javascript:void(0)' onclick='editar("+id +","+JSON.stringify(full)+");'  class='btn btn-mint  btn-xs'><i class='fa fa-file'></i> Editar</a>";
                    html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar('+id +');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-xs"></i> Eliminar</a>';

                     <?php } ?>
                    return html;
                }
            }
        ],
        "responsive": true,
        "pageLength": 25,
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


function verDetalleVentaX(id,this_){
    var td=$(this_).closest("td");
    var div=td.find(".divClassVerDetalle");
    $.post(url_base+"ventasusdt/getDetVenta_",{idventa:id},function (data) {
        var arrVenta=data;
        var sumx=0;
        var ht="";
        var totVentaSoles=0;
        var totVentaBtc=0;
        $.each(arrVenta,function (kx,ix) {
            totVentaSoles+=parseFloat(ix.montosoles);
            totVentaBtc+=parseFloat(ix.montobtc);
            ht+="<p style='margin-bottom: 0px;' >Ref:"+ix.idrefdetcompra+"</p>";
            ht+="<p style='margin-bottom: 0px;'> "+ix.montobtc+" USDT =  S/ "+ix.montosoles+"</p>";

        });
        ht+="<b style='margin-bottom: 0px;' >Total S/ "+parseFloat(parseFloat(totVentaBtc).toFixed(8))+" USDT  = S/ "+ parseFloat(totVentaSoles).toFixed(2)+" </b>";
        div.html(ht);
    },'json');

  //  console.log(td);

}


    function verDetallePagoX(id,this_){
        var td=$(this_).closest("td");
        var div=td.find(".divClassVerDetallePago");
        $.post(url_base+"ventasusdt/getDetPago_",{idventa:id},function (data) {
            var arrPago=data;
            var totalACobrar=0;

            var ht="";
            $.each(arrPago,function (kx,ix) {
                if(ix.nmoneda =="Soles"){
                    totalACobrar+=parseFloat(ix.monto);
                    ht+="<p style='margin-bottom: 0px;'> "+ix.abrebanco+" "+ix.ncuenta+ ":S/ "+ix.monto+ "   </p>";
                }else{
                    var cambiox=parseFloat(ix.tasacambio)* parseFloat(ix.monto);
                    ht+="<p style='margin-bottom: 0px;' > "+ix.abrebanco+" "+ix.ncuenta+ ":$ "+ix.monto+ " = S/"+cambiox+"  <span>*TC= S/ "+ix.tasacambio+"</span>    </p>";
                    totalACobrar+=parseFloat(cambiox);
                }

            });
            ht+="<b> Total S/ :"+totalACobrar+ "   </b>";
            div.html(ht);
        },'json');

        //  console.log(td);

    }


function refrescar () {
    dtTable.ajax.reload();
}

$(document).on("click","#btnAddNew",function () {
    $("#modalId").modal("show");
    $("#hModalTitle").html("Registrar Venta");
    iniFormReg();
    $('#cliente').chosen({width:'80%'});
});

function iniFormReg() {
    var tmpFormCompra=_.template($("#tmpFormCompra").html());
    $("#bModalBody").html(tmpFormCompra);
    loadTrForTbody(0);
}

function calcSubByCuenta(thisx){
    var ctx=$(thisx);
    var table=ctx.closest("table");
    var montos=table.find("input[name='monto[]']");
    var subTo=table.find("input[name='subTotalPago[]']");
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

    var dataSubTotal=$("input[name='subTotalPago[]']");
    var ttotal=0;
    $.each(dataSubTotal,function (k,i) {
        ttotal=ttotal+Number($(i).val());

    });
    $("#total").html(ttotal);

    calcResto();


}
function testxxxx() {
    $.post(url_base+"ventasusdt/datax",{},function (data) {
        var x=data[0].detalleVenta;
        var y=JSON.parse(x);

    },'json');
}

function calcResto(){
    var ttarecibir=parseFloat($("#totalARecibir").html());
    var ttotal=parseFloat($("#total").html());
    var resto=0;
    resto=parseFloat(ttarecibir-ttotal).toFixed(2);
    $("#resto").html(resto);
}

  $(document).on("click","#btnSaveForm",function () {
      var form=$("#formRegData").serialize();
      var btn=$(this);
      var ddd=$("#total").html();
      ddd=parseFloat(Number(ddd));
      if(ddd == 0){
        alert_error("Por favor ingrese los pagos");
        return 0;
      }
      btn.button("loading");
      $.post(url_base+"ventasusdt/setForm",form,function (data) {
         if(data.status == "ok"){
             refrescar();
             alert_success("Correcto");
             iniFormReg();
             slideTo("hModalTitle");
             $('#cliente').chosen({width:'80%'});
         }else{
             alert_success("Fallo");
         }
          btn.button("reset");
      },'json');
  });

  function eliminar(id) {
      if(!confirm("¿Seguro de eliminar este registro?")){
          return 0;
      }
      $.post(url_base+"ventasusdt/delete",{"id":id},function (data) {
          if(data.status == "ok"){
              refrescar();
              alert_success("Correcto");

          }else{
              alert_success("Fallo");
          }
      },'json');
  }
  function loadTrForTbody(onlyData) {
     var xd=$.ajax({
          async: false,
          type: 'post',
          url: url_base+'ventasusdt/getProdStock',
          data:{"id":1},
          dataType:"json",
          success: function (data) {

              if (data.status == "ok") {
                  if(onlyData == 0){
                      var tmpTrTbodyDetalleVenta=_.template($("#tmpTrTbodyDetalleVenta").html());
                      $("#tbodyDetalleVenta").append(tmpTrTbodyDetalleVenta({data:data.data}));
                  }else{
                      return data.data;
                  }


              }else{

              }
              //calcResto();
          },
          error: function (request, status, error) {
              alert(jQuery.parseJSON(request.responseText).Message);
          }
      });
     return xd;
  }
  $(document).on("change","select[name='prodVenta[]']",function () {
      var ctx=$(this );
      var valxx= (ctx.val()).split("-");
      var stock=valxx[1];
      var pventa=valxx[2];
      var pCompraxw=valxx[3];
      var tr=ctx.closest("tr");
      var inputStockActual=tr.find("input[name='stockHt[]']");
      var inputPrecioVenta=tr.find("input[name='pventa[]']");
      var inputPventaSoles=tr.find("input[name='cantSoles[]']").val("");
      var inputSSsubTotal=tr.find("input[name='subTotal[]']").val("");
      var inputPventaBTC=tr.find("input[name='cantBtc[]']").val("");

      var spanPrecioBaseVenta=tr.find(".spanPrecioBaseVenta");
      spanPrecioBaseVenta.html("*"+pCompraxw);
      inputPrecioVenta.val(pventa);
      inputStockActual.val(stock);
      var table=ctx.closest("table");

      calcTotalDetalleTable();
  });

    $(document).on("keyup","input[name='cantBtc[]']",function () {
        var ctx=$(this );
        var valcantventa=$(this).val();
        var tr=ctx.closest("tr");
        var inputStockActual=(tr.find("input[name='stockHt[]']")).val();
        var inputPBaseventa=(tr.find("input[name='pventa[]']")).val();
        var inputPventaSoles=tr.find("input[name='cantSoles[]']");
        var inputSSsubTotal=tr.find("input[name='subTotal[]']");

        var ssstotal=0;

           if(parseFloat(valcantventa) <= parseFloat(inputStockActual)){
               ssstotal=valcantventa*inputPBaseventa;
               ssstotal=parseFloat(ssstotal.toFixed(2));
           }else{
               alert_error("Stock insuficiente");
               ctx.val();
           }
        inputPventaSoles.val(ssstotal);
        inputSSsubTotal.val(ssstotal);
        calcTotalDetalleTable();
    });
    $(document).on("click","input[name='isCheck[]']",function () {
        var ctx=$(this);

        console.log(ctx.val());

    });

    $(document).on("keyup","input[name='cantSoles[]']",function () {
        var ctx=$(this );
        var valcantSolesventa=parseFloat($(this).val());
        var tr=ctx.closest("tr");
        var inputStockActual=(tr.find("input[name='stockHt[]']")).val();
        var inputPBaseventa=(tr.find("input[name='pventa[]']")).val();
        var inputSSsubTotal=tr.find("input[name='subTotal[]']");

        var inputPventaBTC=tr.find("input[name='cantBtc[]']");
        var isCheckAutomatic=tr.find("input[name='isCheck[]']");
        var ssstotal=0;
        var valBtcBysoles=0;
        if(isCheckAutomatic.is(':checked')){
            valBtcBysoles=valcantSolesventa/parseFloat(inputPBaseventa);
            valBtcBysoles=parseFloat(valBtcBysoles.toFixed(8));

            if(parseFloat(valBtcBysoles) <= parseFloat(inputStockActual)){
                inputPventaBTC.val(valBtcBysoles);
            }else{
                alert_error("Stock insuficiente");
                ctx.val();
                valcantSolesventa=0;
            }
        }else{

        }




        inputSSsubTotal.val(valcantSolesventa);

        calcTotalDetalleTable();
    });

    function calcTotalDetalleTable() {
        var totalDetalleVenta=0;
        var inSbTotalGeneral=$("input[name='subTotal[]']");
        $.each(inSbTotalGeneral,function (kkk,iii) {
            totalDetalleVenta=totalDetalleVenta+Number($(iii).val());
            $("#totalAPagarVenta,#totalARecibir").html(totalDetalleVenta);
            calcResto();
            //console.log(totalDetalleVenta);
        });
    }
    function removeTr(t) {
        if(!confirm("¿Esta seguro?")){
            return 0;
        }
        var ctx=$(t);
        var tab=ctx.closest("table");
        var tr=ctx.closest("tr");
        var nFilas =tab[0].tBodies[0].rows.length;
        if(nFilas > 1){
            tr.remove();
        }else{
            alert_error("No puede eliminar el ultimo elemento");
        }
    }
    
    $(document).on("click","#btnReporteVentas",function () {
        $("#modalReport").modal("show");
    });

    $(document).on("click","#btnGenerarDtVenta",function () {
        var fechaini=$("#dtfechaventaini").val();
        var fechaend=$("#dtfechaventaend").val();
        $.post(url_base+"ventasusdt/reporte",{fechaini:fechaini,fechaend:fechaend},function (data) {
            if(data.status == "ok"){
                var tmpTableVenta=_.template($("#tmpTableVenta").html());
                $("#divResultReport").html(tmpTableVenta({data:data.data}));
                $("#tableReporteVentas").DataTable();
            }else{
                alert_error("Fail");
            }

        },'json');

    });

    function editar(id,data) {
        var dtEdit=data;
        console.log(dtEdit);
        $("#modalId").modal("show");
        $("#hModalTitle").html("Editar Venta");
        iniFormReg();

        $("#isEdit").val(1);
        $("#idEdit").val(id);
        $.post(url_base+"ventasusdt/getDataDetalleVenta",{id:id},function (data) {
            if(data.status =="ok"){
                var dataRes=data.data;
                if(dataRes.length> 0){
                    $("#bModalBodyNewForm").html("");
                    dataRes=dataRes[0];
                    $("#cliente").val(dataRes.cliente);
                    $("#wallet").val(dataRes.wallet);
                    $("#fecha").val(formatDateYMD(dataRes.fechaventa));
                    $("#idreffecha").val(dataRes.idreferencia);
                    var detPago= dataRes.detPago;
                    var detVenta= dataRes.detVenta;
                    var subSolesX=0;
                    var subDolares=0;
                    var ssTotalDolarSoles=0;
                    var totalApagarxy=0;
                    var tasacambioXY=1;
                    var idcuentabancoAnterior=0;
                    $.each(detPago,function (k,i) {
                        var tr;
                        if(idcuentabancoAnterior == i.idcuentabanco ){
                            if(i.abremoneda == "$"){
                                tr=$(".mdolares"+i.idcuentabanco);
                            }
                            if(i.abremoneda == "S/"){
                                tr=$(".msoles"+i.idcuentabanco);
                            }

                            var divDataOpExtra=tr.find(".divDataOpExtra");
                            var divMontoExtra=tr.find(".divMontoExtra");
                            var ht1='<div class="divDataOp">\n' +'                                                     <div class="input-group" style="margin-top: 5px;">\n' +
                                '                                                    <span class="input-group-btn">\n' +
                                '                                                        <button class="btn btn-mint btn-sm" type="button">Nro Op.</button>\n' +
                                '                                                    </span>\n' +
                                '                                                         <input type="text" name="nrooperacion[]" value="'+i.nrooperacion+'" class="form-control text-right input-sm nrooperacion'+i.idcuentabanco+'">\n' +
                                '                                                     </div>\n' +
                                '                                                     <input type="hidden" name="cuentabanco[]" value="'+i.idcuentabanco+'">\n' +
                                '                                                 </div>';
                            var ht2='<input style="margin-top: 5px;" type="text" name="monto[]" onkeyup="calcSubByCuenta(this)"  class="form-control text-right input-sm montocuenta'+i.idcuentabanco+'" value="'+i.monto+'">';

                            divDataOpExtra.append(ht1);
                            divMontoExtra.append(ht2);

                        }else{
                            $(".montocuenta"+i.idcuentabanco).val(i.monto);
                            $(".nrooperacion"+i.idcuentabanco).val(i.nrooperacion);
                        }

                        idcuentabancoAnterior=i.idcuentabanco;

                        if(i.abremoneda == "$"){
                            subDolares+=parseFloat(i.monto);
                        }
                        if(i.abremoneda == "S/"){
                            subSolesX+=parseFloat(i.monto);
                        }
                        tasacambioXY=i.tasacambio;
                    });

                    $("#tasaCambio").val(tasacambioXY);
                    $("#ssubTotal").val(subDolares);
                    ssTotalDolarSoles=subDolares*tasacambioXY;
                    totalApagarxy=subSolesX+ssTotalDolarSoles;
                    $("#subTDolares").val(ssTotalDolarSoles);
                    $("#subTSoles").val(subSolesX);
                    $("#total").html(totalApagarxy);

                    var tmpTrTbodyDetalleVentaEdit=_.template($("#tmpTrTbodyDetalleVentaEdit").html());

                    var prodStocksActual=loadTrForTbody(1);
                    var dtPrdoF=prodStocksActual.responseJSON;

                    if(dtPrdoF.status=="ok"){
                        var htDetail=tmpTrTbodyDetalleVentaEdit({dtprodStock:dtPrdoF.data,data:detVenta});
                        $("#tbodyDetalleVenta").html(htDetail);
                    }
                    calcTotalDetalleTable();
                    console.log(dataRes);
                    $("#cliente").val(dataRes.idcliente);
                    $('#cliente').chosen({width:'80%'});

                }else{
                    console.log("Errr");
                }
            }else{
                console.log("Errr");
            }

        },"json");



    }

    $(document).on("change","#idreffecha",function () {
        if($("#isEdit").val() == 1){
            return 0;
        }

        var vl=$(this).val();
        $("#spanIdRefExiste").css("display","block");
        $("#spanIdRefExiste").html("Validando...");
        $.post(url_base+"ventasusdt/validarIdRef",{"id":vl},function (data) {
            if(data.status == "ok"){
                if(data.data.c == "0" ){
                    $("#spanIdRefExiste").css("display","none");
                }else{
                    $("#spanIdRefExiste").html("ID existe");
                    $("#spanIdRefExiste").css("color","red");
                }
            }else{

            }

        },'json');
    });

    $(document).on("change","input[name='nrooperacion[]']",function () {
        var vl=$(this).val();
        console.log(vl);
        var ctx=$(this).closest(".tdNop");
        var spanExist=ctx.find(".classNroOPeracion") ;

       $.post(url_base+"ventasusdt/validarNroOperacion",{"id":vl},function (data) {
            if(data.status == "ok"){
                if(data.data.c == "0" ){
                    spanExist.css("display","none");
                }else{
                    spanExist.css("display","block");
                }
            }else{

            }

        },'json');
    });


     function slideTo(id){
        $('html,body').animate({
                scrollTop: ($("#"+id).offset().top)},
            'slow');
    }
    $(document).on("change","#dtfechaventaini",function () {
        $("#dtfechaventaend").val($(this).val());
    });


$(document).on("click","#btnGenerarDtVentaXBanco",function () {
    var fechaini=$("#dtfechaventaini").val();
    var fechaend=$("#dtfechaventaend").val();
    $.post(url_base+"ventasusdt/reporteByBanco",{fechaini:fechaini,fechaend:fechaend},function (data) {
        if(data.status == "ok"){
            var tmpBodyReportVentasBybanco=_.template($("#tmpBodyReportVentasBybanco").html());
            $("#divResultReport").html(tmpBodyReportVentasBybanco({data:data.data}));
            //$("#tableDetalleVentasBanco").DataTable();
        }else{
            alert_error("Fail");
        }

    },'json');
});

    $(document).on("click","#btnAddNewCliente",function () {
        $("#modalIdNewForm").modal("show");
        var tmpFormCliente=_.template($("#tmpFormCliente").html());
        $("#bModalBodyNewForm").html(tmpFormCliente);

    });

    $(document).on("click","#btnSaveFormCliente",function () {
        var form=$("#formRegDataCliente").serialize();
        $.post(url_base+"ventasusdt/setFormCliente",form,function (data) {
            if(data.status == "ok"){
                refrescar();
                alert_success("Correcto");
                $("#modalIdNewForm").modal("hide");
                if(data.data.length > 0){
                    let $option = $('<option />', {
                        text: data.data[0].nombre,
                        value: data.data[0].idcliente
                    });
                    $('#cliente').prepend($option);
                    $("#cliente").val(data.data[0].idcliente);

                    $("#cliente").trigger("chosen:updated");
                }else{

                }

            }else{
                alert_success("Fallo");
            }
        },'json');
    });

    $(document).on("change","#cliente",function () {
        var idcliente=$("#cliente").val();

        var xxx=$("#cliente option:selected").attr("wallet");
        var htt=$("#cliente option:selected").html();
        var x=($("#fecha").val()).toString();
        x=x.split("-")
        console.log(x);
        var ff="";
        if(x.length == 3){
              ff=x[2]+x[1]+x[0];
        }
        $.post(url_base+"ventasusdt/searchVenta",{idcliente:idcliente,fecha:$("#fecha").val()},function(data){
            $("#idreffecha").val(htt+"("+ff+"-"+data.data+")");
        },'json');
        $("#wallet").val(xxx);

    });

function verDetalleReporteBanco(thisx,data) {
    var ctx=$(thisx);
    var td=ctx.closest("td");
    var div=td.find(".tdClDet");
    var p=td.find(".pdetOp");
    console.log(p.length);
    var ht="";
    if(p.length == 0){
        $.each(data,function (k,i) {
        	var calXXXTotalCambio=0;
        	var tasacambio=parseFloat(Number(i.tasacambio));
        	var sumGanBanco= parseFloat(Number(i.sumganbanco)) ;
        	if(i.nmonedaabreviado == "$"){
			calXXXTotalCambio=parseFloat(sumGanBanco/tasacambio).toFixed(2);
        	}else{
        		calXXXTotalCambio=sumGanBanco.toFixed(2);
        	}
        	ht+="<p class='pdetOp'>Nr. Op:"+ i.nrooperacion+"->"+i.nmonedaabreviado+" "+i.monto+ "= <b>Monto:</b> "+i.nmonedaabreviado+" "+calXXXTotalCambio +"</p>";
        
           // ht+="<p class='pdetOp'>Nr. Op:"+ i.nrooperacion+"->"+i.abremoneda+" "+i.monto+ "</p>";
        });
        div.html(ht);
        //$("#tableDetalleVentasBanco").DataTable();
    }else{
        div.html("");
    }
}

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

    function registrarEnCaja(monto) {

        $("#modalIdEnvioCaja").modal("show");
        $("#montoencaja").val(parseFloat(monto).toFixed(2));
        $("#fechaenviocaja").val($("#dtfechaventaini").val());
    }

    $(document).on("click","#btnSaveEnviarACaja",function () {
        var btn=$(this);
         btn.button("loading");
        var form=$("#formRegEnvioCaja").serialize();
        $.post(url_base+"ventasusdt/enviocaja",form,function(data){
            if(data.status == "ok"){
                $("#modalIdEnvioCaja").modal("hide");
                alert_success("Se Envio la ganacia a caja correctamente");
            }else{
                alert_error("fallo");
            }
            btn.button("reset");
        },'json');
    });

    $(document).on("click","#btnGenerarDtVentaXCliente",function () {
        var btn=$(this);
        btn.button("loading");

        var fechaini=$("#dtfechaventaini").val();
        var fechaend=$("#dtfechaventaend").val();
        var tmpReporteByCliente=_.template($("#tmpReporteByCliente").html());


        $.post(url_base+"ventasusdt/reportedeventaporcliente",{fechaini:fechaini,fechaend:fechaend},function(data){
            console.log(data);
            if(data.status == "ok"){
                $("#divResultReport").html(tmpReporteByCliente({data:data.data}));

            }else{
                alert_error("fallo");
            }
            btn.button("reset");
        },'json');
    });

    function updateTableBancos(moneda) {
        var tmpBancosByMoneda=_.template($("#tmpBancosByMoneda").html());
        var  tableBancosSoles=$("#tBodyBancosSoles");
        var  tableBancosDolares=$("#tBodyBancosDolares");
        $.post(url_base+"ventasusdt/getXupdateListBancos",{moneda:moneda},function (data) {
            if(data.status=="ok"){
                tmpBancosByMoneda=tmpBancosByMoneda({data:data.data});
                if(moneda=="soles"){
                    tableBancosSoles.html(tmpBancosByMoneda);
                }else if(moneda=="dolares"){
                    tableBancosDolares.html(tmpBancosByMoneda);
                }
                alert_success("Se actualizo correctamente");
            }

        },'json');


    }

 ///--------------------------
   
    $(document).on("click","#btnGenerarDtVentaXProveedor",function () {
        var fechaini=$("#dtfechaventaini").val();
        var fechaend=$("#dtfechaventaend").val();
        var tmpBodyReportVentasByProveedor=_.template($("#tmpBodyReportVentasByProveedor").html());
        $("#divResultReport").html("<h3>Cargando...</h3>");
        $.post(url_base+"ventasusdt/reporteByProveedores",{fechaini:fechaini,fechaend:fechaend},function (data) {
            if(data.status == "ok"){
                $("#divResultReport").html(tmpBodyReportVentasByProveedor({data:data.data}));
                $("#tableReporteVentasxProveedores").DataTable({
                    "responsive": true,
                    "pageLength": 100
                });
            }else{
                alert_error("Fail");
            }

        },'json');
    });


    //----------------------INIchange

    function generarComprobante(_t,totalOperacion,bancoX,monedaX,montoX,isForNew,dataCheks) {
        var DataDet=[{
            "moneda":monedaX,
            "banco":bancoX,
            "Totaloperacion":totalOperacion,
            "Tganancia":montoX,

        }]
        console.log(dataCheks);
        if(dataCheks.length>0){
            DataDet=dataCheks;
        }


        var tmp=_.template($("#tmpGeneraComprobante").html());
        $("#modalGenerarComprobante").modal("show");
        $("#modalBodyGenerarComprobante").html(tmp({isForNew:isForNew,dataDet:DataDet}))

        if(monedaX == "S/"){
            $("#tipomonedacompro").val("PEN");
        }else if(monedaX == "$"){
            $("#tipomonedacompro").val("USD");
        }

        calcTotalDetalleTableCompro();
        iniAutoCompleteUbigeoNat();
        $("#distCompro").attr("autocomplete","nope");
        validaTipoDoc();
        getFinalfechaComproForRucTipoDoc();

        getUltimasTransacciones();
    }

    function getUltimasTransacciones() {
        $("#divUltimasTransaccionesBol").html("<h2>Cargando...</h2>");
        var tmp=_.template($("#tmpGetUltimasTransacciones").html());
        $.post(url_base+"ventasusdt/getUltimasTransacciones",{fini:$("#fechacompro").val()},function (r) {
            $("#divUltimasTransaccionesBol").html(tmp({data:r.data}));
        },'json');


    }


    function calcTotalDetalleTableCompro() {
        var totalDetalleVenta=0;
        var inSbTotalGeneral=$("input[name='subTotalUItemCompro']");

        $.each(inSbTotalGeneral,function (kkk,iii) {
            totalDetalleVenta=totalDetalleVenta+Number($(iii).val());
        });
        $("#totalVentaCompro").val(totalDetalleVenta.toFixed(2));
    }

    function iniAutoCompleteUbigeoNat() {
        $("#distCompro").autocomplete({
            source: "<?= base_url(); ?>ubigeo/getUbigeo",
            minLength: 2,// tamaño de cadena
            delay: 250,
            search: function( event, ui ) {
                $("#depCompro").val("");
                $("#provCompro").val("");
                $("#ubigeoComrpo").val("");
                $("#bComproUbigeo").html("");
            },
            select: function( event, ui ) {
                var dt=ui.item;
                let arr=(dt.label).split("-");
                console.log(dt.UBIGEO);
                $("#depCompro").val(arr[0]);
                $("#provCompro").val(arr[1]);
                $("#ubigeoCompro").val(dt.UBIGEO);
                $("#bComproUbigeo").html(dt.UBIGEO);

            }
        });
    }
    
    
    function searchDniOrRuc(_t) {
        let valSelTipoDoc=$("#tipodocclientecompro").val();
        let uriSend="";
        let dataSend;
        if(valSelTipoDoc == "1"){
            uriSend="consultaDNI";
            dataSend={documento:$("#nroDocClienteCompro").val()};
        }else if(valSelTipoDoc == "6"){
            uriSend="consultaRUC";
            dataSend={ruc:$("#nroDocClienteCompro").val()};
        }
        $("#txtLoadigSearchCliente").html("Buscando...");
        $.post(url_base+"ConsultaRucDni/"+uriSend, dataSend,function (r) {
            if(valSelTipoDoc == "1"){
                if(r.status=="ok"){
                    let cliente="";
                    let RR=r.result;
                    cliente=RR.nombres+" "+RR.apellidoPaterno+" "+ RR.apellidoMaterno;
                    $("#clientecompro").val(cliente);
                }
            }else if(valSelTipoDoc == "6"){
                if(r.status=="ok"){
                    let cliente="";
                    let RR=r.result;
                    cliente=RR.razonSocial;
                    $("#clientecompro").val(cliente);

                    $("#dirclientecompro").val(RR.direccion);
                    if((r.ubigeo).length > 0){
                      let ubsx=r.ubigeo[0];
                        $("#depCompro").val(ubsx.DESC_DPTO);
                        $("#provCompro").val(ubsx.DESC_PROV);
                        $("#distCompro").val(ubsx.DESC_DIST);
                        $("#ubigeoCompro").val(ubsx.UBIGEO);
                        $("#bComproUbigeo").html(ubsx.UBIGEO);
                    }
                }
            }
            $("#txtLoadigSearchCliente").html("");
        },'json');
    }
    $(document).on("change","#tipodocclientecompro",function () {
        $("#nroDocClienteCompro").val("");
        $("#clientecompro").val("");
    });

    $(document).on("click","#btnGeneraComprobante",function () {

        var btn=$(this);

        var rucG=$("#usuariogenera option:selected").text();

        let tipodocclientecompro=$("#tipodocclientecompro");
        let nroDocClienteCompro=$("#nroDocClienteCompro");
        nroDocClienteCompro= (nroDocClienteCompro.val()).replace(/ /g, "");



        let clientecompro=$("#clientecompro");
        let nroStrcliente=(clientecompro.val()).replace(/ /g, "");

        let dirclientecompro=$("#dirclientecompro");
        let ndircli=(dirclientecompro.val()).replace(/ /g, "");
        let distCompro=$("#distCompro");

        let ubigeoCompro=$("#ubigeoCompro");

        let emailCompro=$("#emailCompro");

        let totalVentaCompro=$("#totalVentaCompro");
        totalVentaCompro=parseFloat(totalVentaCompro.val());

        let tipomonedacompro=$("#tipomonedacompro");


        var preciomoneda=1;//inisoles

        if(tipomonedacompro.val() ==="PEN" ){
            preciomoneda=1;
        }else if(tipomonedacompro.val() ==="USD" ){
            preciomoneda=3.5;
        }
        totalVentaCompro=totalVentaCompro*preciomoneda;

        if(totalVentaCompro === 0){
            alert_error("La venta no puede ser 0");
            return  0;
        }


        var tipocomprobante=$("#tipocomprobante").val();
        switch (tipocomprobante) {
            case "03" :
                if(tipodocclientecompro.val() == "1"){
                    let lenDoc=nroDocClienteCompro.length;
                    let lenNCliente=nroStrcliente.length;
                    if(lenDoc >8 || lenDoc <8 ){
                        alert_error(" DNI No valido");
                        return  0;
                    }
                    if(lenNCliente==0){
                        alert_error("Debe consignar nombres / Razón social");
                        return  0;
                    }

                    if(totalVentaCompro > 700){
                        if(nroDocClienteCompro === "00000000"){
                            alert_error("Monto mayor a S/700 debe consigar Consignar DNI y Nombre Apellidos  del cliente");
                            return  0;
                        }

                        if(clientecompro.val() ==="Cliente Varios"){
                            alert_error("Monto mayor a S/700 debe consigar Consignar DNI y Nombre Apellidos  del cliente");
                            return  0;
                        }

                    }

                }else if(tipodocclientecompro.val() == "6"){

                    let lenDoc=nroDocClienteCompro.length;
                    let lenNCliente=nroStrcliente.length;

                    if(lenDoc >11 || lenDoc <11 ){
                        alert_error(" RUC No valido");
                        return  0;
                    }
                    if(lenNCliente==0){
                        alert_error("Debe consignar Razón social");
                        return  0;
                    }
                    if(ndircli==0 || ubigeoCompro.val()=="" ){
                        alert_error("Debe consignar Dirección y Ubigeo");
                        return  0;
                    }


                }

                break;
            case "01" : // factura
                if(tipodocclientecompro.val() == "6"){

                    let lenDoc=nroDocClienteCompro.length;
                    let lenNCliente=nroStrcliente.length;

                    console.log(lenDoc);
                    if(lenDoc >11 || lenDoc <11 ){
                        alert_error(" RUC No valido");
                        return  0;
                    }
                    if(lenNCliente==0){
                        alert_error("Debe consignar Razón social");
                        return  0;
                    }
                    console.log(ndircli.length);
                    if(ndircli.length==0 || ubigeoCompro.val()=="" ){
                        alert_error("Debe consignar Dirección y Ubigeo");
                        return  0;
                    }


                }
                break;
            default:break;
        }

        // buscar si ya existe registro"
        var ArrayRegListaTrans=$("input[name='nameRefTrans']");
        var tbodyDet=$("#tbodyDetComprox >tr");
        var bolYaregistrado=false;
        $.each(tbodyDet,function (k,i) {
            var cntTr=$(i).find("input[name='cantItemCompro[]']").val();
            var descTr=$(i).find("input[name='descItemCompro[]']").val();
            var precioTr=$(i).find("input[name='precioUItemCompro[]']").val();
            var conTrvals=Number(cntTr).toFixed(2)+""+descTr+""+Number(precioTr).toFixed(2)+""+$("#tipomonedacompro").val();
            conTrvals=conTrvals.replace(/ /g, "");

            $.each(ArrayRegListaTrans,function (kxk,ixi) {
                let vvAlRegTra=$(ixi).val()
                if(vvAlRegTra == conTrvals ){
                    bolYaregistrado=true;
                   var trxxxxi=$(ixi).closest("tr");
                   trxxxxi.css("background","#b50a0a73")
                }
            });

        });
        //console.log(tbodyDet);
        if(bolYaregistrado){
            if(!confirm("Se encontro una coincidencia en el historial de transacciones desea continuar?")){
                return  0;
            }
        }
        //return  0;

        var formCom=$("#formNewComprobante").serialize();
        btn.button("loading");
        $.post(url_base+"ventasusdt/setNewCompro",formCom+"&textRuc="+rucG,function (r) {
            if(r.status){
                alert_success("Se genero el comprobante ");
               // btn.button("reset");
                $("#comprobanteGeneradoIs").html("El comprobante fue generado...");
            }else{
                alert_error("Ocurrio un error");
                btn.button("reset");
                $("#comprobanteGeneradoIs").html("El comprobante NOOOO fue generado...");
            }

        },'json');
    });

    $(document).on("change","#tipocomprobante",function () {
        validaTipoDoc();
        getFinalfechaComproForRucTipoDoc();
        $("#fechacompro").val("<?=date('Y-m-d')?>");
        $("#horacompro").val("");
    });



    function validaTipoDoc(){
        var tpc=$("#tipocomprobante");
        var tipodocclientecompro=$("#tipodocclientecompro");
        if(tpc.val()=="01"){
            $("#tipodocclientecompro option[value='1']").attr("disabled",true);
            $("#tipodocclientecompro option[value='6']").attr("disabled",false);
            tipodocclientecompro.val(6);

            $("#nroDocClienteCompro").val("");
            $("#clientecompro").val("");

        }else if(tpc.val()=="03"){
            $("#tipodocclientecompro option[value='1']").attr("disabled",false);

            $("#tipodocclientecompro option[value='6']").attr("disabled",true);
            tipodocclientecompro.val(1);

            $("#nroDocClienteCompro").val("00000000");
            $("#clientecompro").val("Cliente Varios");
        }
    }


    $(document).on("keyup","input[name='cantItemCompro[]']",function () {
        var ts=$(this);
        calcMontTr(ts);
    });
    $(document).on("keyup","input[name='precioUItemCompro[]']",function () {
        var ts=$(this);
        calcMontTr(ts);
    });

    function calcMontTr(ts) {
        var tr=ts.closest("tr");
        var inV=(tr.find("input[name='precioUItemCompro[]']")).val();
        var cant=(tr.find("input[name='cantItemCompro[]']")).val();
        var InvsubTotalUItemCompro=(tr.find("input[name='subTotalUItemCompro']"));
        var sb=Number(inV)*Number(cant);
        InvsubTotalUItemCompro.val(sb);
        calcTotalDetalleTableCompro();
    }

    function getFinalfechaComproForRucTipoDoc(){
        var idempresa_=$("#usuariogenera").val();
        var idtipodoc_=$("#tipocomprobante").val();
        $("#bultimoComprobante").html("");
        $("#bultimoComprobante").attr("data-fecha","");
        $("#bultimoComprobante").attr("data-hora","");
        $.post(url_base+"ventasusdt/getFinalfechaComproForRucTipoDoc",{idempresa:idempresa_,idtipodoc:idtipodoc_},function (r) {
            if(r.status){
                if(r.data.fechaf){
                    $("#bultimoComprobante").html(r.data.fechaf+" "+r.data.hora);
                }
               if(r.data.fecha){
                   $("#bultimoComprobante").attr("data-fecha",r.data.fecha);
               }
                if(r.data.horamasunminuto){
                    $("#bultimoComprobante").attr("data-hora",r.data.horamasunminuto);
                }

            }
        },'json') ;
    }

    function colocarFechafinalBd(_t) {
        var tt=$(_t);
        var fechaUl=tt.attr("data-fecha");
        var horaUl=tt.attr("data-hora");
        if(fechaUl){
            $("#fechacompro").val(fechaUl);
        }
        if(horaUl){
            $("#horacompro").val(horaUl);
        }


    }


    $(document).on("change","#usuariogenera",function () {
        getFinalfechaComproForRucTipoDoc();
        $("#fechacompro").val("<?=date('Y-m-d')?>");
        $("#horacompro").val("");
    });

    $(document).on("change","#fechacompro",function () {
        var finputcompro=$(this).val();
        var fechafinalComprox=$("#bultimoComprobante").attr("data-fecha");
        var _fechafinalComprox=new Date(fechafinalComprox) ;
        var  _finputcompro=new Date(finputcompro);
        if( _finputcompro < _fechafinalComprox   ){
            $(this).val(fechafinalComprox);
            alert_error("La fecha no puede ser menor a la última registrada..");
            return 0;
        }

        getUltimasTransacciones();
    });

    $(document).on("change","#horacompro",function () {
        var finputCompro=$("#fechacompro").val();
        var finputHoracompro=$(this).val();
        var fInputR=finputCompro+" "+finputHoracompro;

        var fechafinalComprox=$("#bultimoComprobante").attr("data-fecha");
        var HorafinalComprox=$("#bultimoComprobante").attr("data-hora");

        var fUltimoFinal=fechafinalComprox+" "+HorafinalComprox;


        var _fechafinalComprox=new Date(fUltimoFinal) ;
        var  _finputcompro=new Date(fInputR);

        if( _finputcompro < _fechafinalComprox   ){
            $(this).val(HorafinalComprox);
            alert_error("La Hora no puede ser menor a la última registrada..");
            return 0;
        }
    });

    function checksGeneraCompro(_t) {
        var listCheckeds=$("input[name='checkForCompros']");
        var arrProds=[];
        var monedaxxx="";
        $.each(listCheckeds,function(k,i){
            var isChk=$(i).is(':checked') ;
            if(isChk){
                let vMon=$(i).attr("datamoneda");
                let vBanco=$(i).attr("databanco");
                let vTopera=$(i).attr("datatopera");
                let vGan=$(i).attr("datatgan");
                arrProds.push({
                    "moneda":vMon,
                    "banco":vBanco,
                    "Totaloperacion":vTopera,
                    "Tganancia":vGan
                });
                monedaxxx=vMon;
            }
        });

        if(arrProds.length == 0){
            alert_error("Debe dar Check a un item");
            return 0;
        }

        generarComprobante("","","",monedaxxx,"",0,arrProds);

    }

    $(document).on("click","input[name='checkForCompros']",function(){
        var tx=$(this);
        var listCheckeds=$("input[name='checkForCompros']");
        if(tx.is(':checked') ){
            var moneda=tx.attr("datamoneda");
            $.each(listCheckeds,function(k,i){
                 var isChk=$(i).is(':checked') ;
                 if(isChk){
                     var vMon=$(i).attr("datamoneda");
                     var vGan=$(i).attr("datatgan");
                     vGan=Number(vGan);


                     if(moneda != vMon ){
                         tx.prop('checked', false);
                         alert_error("Moneda seleccionada no es igual");
                     }

                     if(vGan<=0){
                         tx.prop('checked', false);
                         alert_error("No puede seleccionar este monto es 0 o menor que 0");
                     }
                 }
            });
        }
    });

    $(document).on("click","#addNewTrCompro",function () {
     var tr=`<tr>

                    <td style=" width: 50px;"><input class="form-control" style=" width: 50px;" type="number" name="cantItemCompro[]" value="1"> </td>
                    <td style=" width: 70px;">
                        <select style=" width: 70px;padding-left: 0px;padding-right: 0px;" class="form-control" name="unidadItemCompro[]">
                            <option value="NIU" selected="selected">Und.</option>
                           <!-- <option value="ZZ">Servicio.</option> -->
                        </select>
                    </td>
                    <td style="width: 500px;">
                        <input type="hidden" name="codrefx[]" value="S/NaN0">
                        <input type="text" class="form-control" name="descItemCompro[]" value="Comisiones ">
                    </td>
                    <td><input style="text-align: right;padding-left: 0px;padding-right: 3px;" type="number" class="form-control" name="precioUItemCompro[]" value="1"> </td>
                    <td><input style="text-align: right; " type="number" class="form-control" name="subTotalUItemCompro" readonly="readonly" value="1"> </td>
                    <td><button type="button" class="btn btn-xs btn-danger nBorrar"><i class="fa fa-trash"></i></button></td>
                </tr>`;

     $("#tbodyDetComprox").append(tr);
        calcTotalDetalleTableCompro();
    });

    $(document).on("click",".nBorrar",function () {
        var ctx=$(this);
        var tr=ctx.closest("tr").remove();
        calcTotalDetalleTableCompro();
    });

</script>
<script type="text/template" id="tmpGetUltimasTransacciones">
 <br>
 <h4>Últimos comprobantes registrados</h4>
 <div class="table-responsive">
     <table class="table table-condensed table-bordered" >
         <thead>
            <tr>
                <th>#</th>
                <th>Correlativo</th>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Sub</th>
            </tr>
         </thead>
         <tbody>
            <%
            _.each(data,function(i,k){
            var cconct=i.cantidad+""+i.descripcion+""+i.precio+""+i.moneda;
            cconct=cconct.replace(/ /g, "")
            var nnMoneda="S/";
            if(i.moneda=="USD"){
            nnMoneda="$";
            }
            %>
            <tr>
                <td><%=(k+1)%>
                <input type="hidden" name="nameRefTrans" value="<%=cconct%>">
                </td>
                <td><%=i.seriecorrelativo %></td>
                <td><%=i.cantidad %></td>
                <td><%=i.idunidadmedida %></td>
                <td><%=i.descripcion %></td>
                <td><%=i.nnMoneda %><%=i.precio %></td>
                <td><%=i.precio %></td>

            </tr>


            <%   });
            %>
         </tbody>
     </table>
 </div>
</script>
<script type="text/template" id="tmpGeneraComprobante">
<form id="formNewComprobante">
    <div class="row">
        <div class="col-lg-12">
            <div class="col-lg-2">RUC que genera</div>
            <div class="col-lg-4">
                <select  class="form-control" id="usuariogenera" name="usuariogenera">
                    <?php foreach ($listaempresas as $k=>$i){ ?>
                        <option value="<?=$i["idempresa"]?>"><?=$i["ruc"]?>-<?=$i["razonsocial"]?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-lg-2">Tipo comprobante</div>
            <div class="col-lg-4">
                <select style="font-weight: bolder;font-size: 1.5rem;" class="form-control" id="tipocomprobante" name="tipocomprobante" >
                    <option value="03" selected="selected">Boleta</option>
                    <option value="01">Factura</option>
                </select>
            </div>
            <hr>
        </div>
        <div class="col-lg-12">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group" style="margin-bottom: 5px;" >
                        <label class="control-label" style="margin-bottom: 2px;">Documento <button type="button" onclick="searchDniOrRuc(this)" style="padding: 0px;" class="btn-link"> <i class="fa fa-search"></i><b id="txtLoadigSearchCliente"></b>  </button></label>
                        <div class="input-group  ">
                        <span class="input-group-addon" style="padding: 0px;">
                            <select style="width: 70px;padding-right: 0px;padding-left: 0px;" class="form-control" id="tipodocclientecompro" name="tipodocclientecompro">
                                <option value="1" selected="selected">DNI</option>
                                <option value="6"   >RUC</option>
                            </select>
                        </span>
                            <input value="00000000" type="text" minlength="7" maxlength="11" class="form-control" id="nroDocClienteCompro" name="nroDocClienteCompro" >
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group" style="margin-bottom: 5px;"  >
                        <label class="control-label">Cliente(Nombres /Razón social)</label>
                        <input value="Cliente Varios" type="text" class="form-control" name="clientecompro" id="clientecompro" >
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group" style="margin-bottom: 5px;display: inline-block"  >
                        <label class="control-label">Fecha Reg</label><br>
                        <input onkeypress="return false;" type="date" value="<?=date('Y-m-d')?>"  class="form-control" name="fechacompro" id="fechacompro" style="padding-right: 0px;padding-left: 0px;display: inline-block;width: 55%;" >
                        <input onkeypress="return false;" class="form-control"   type="time" value="" id="horacompro" name="horacompro" style="display: inline-block;width: 40%;">
                        <span title="Último comprobante">UC:<a class="btn-link" href="javascript:void(0)" data-fecha=""  data-hora="" onclick="colocarFechafinalBd(this)" id="bultimoComprobante"></a> </span>
                    </div>
                </div>
                <!--<div class="col-sm-1">
                    <div class="form-group">
                        <label class="control-label">__________</label>
                        <button class="btn btn-warning btn-sm" onclick="addDetailInCompro()"><i class="fa fa-plus"></i></button>
                    </div>
                </div> -->
            </div>
            <div class="row">
                <div class="col-sm-5">
                    <div class="form-group" style="margin-bottom: 0px;"  >
                        <label class="control-label">Dirección</label>
                        <input type="text" class="form-control" name="dirclientecompro" id="dirclientecompro" >
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group"  style="display: inline-block ;margin-bottom: 0px;" >
                        <label class="control-label" style="display: inline-block" >Ciudad</label><br>
                        <input readonly="readonly" style="display: inline-block;width: 30%;" type="text" class="form-control" name="depCompro" id="depCompro" >
                        <input readonly="readonly" style="display: inline-block;width: 30%;" type="text" class="form-control" name="provCompro" id="provCompro" >
                        <input  style="display: inline-block;width: 30%;" type="text" class="form-control" name="distCompro" id="distCompro" >
                        <input type="hidden" id="ubigeoCompro" name="ubigeoCompro" value="">
                        <span style="font-size: 10px;">Ubigeo:<b id="bComproUbigeo"></b></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5">
                    <div class="form-group" style="margin-bottom: 3px;" >
                        <label class="control-label">Email</label>
                        <input type="text" class="form-control" name="emailCompro" id="emailCompro" >
                    </div>
                </div>
                <div class="col-sm-6">

                </div>
                <div class="col-sm-1">
                    <div class="form-group" style="margin-bottom: 3px;" >
                        <label class="control-label">__________</label>
                        <button id="addNewTrCompro" type="button" class="btn btn-warning btn-sm"><i class="fa fa-plus"></i></button>
                    </div>
                </div>

            </div>

            <table class="table table-bordered table-hover table-condensed">
                <thead>
                <tr>
                    <th   >Cant.</th>
                    <th>Und.</th>
                    <th>Descripción</th>
                    <th>Monto U</th>
                    <th>Sub total</th>
                    <th>*</th>
                </tr>
                </thead>
                <tbody id="tbodyDetComprox">

                <%
                _.each(dataDet,function(i,k){




                let descItemC=" Comisiones por el monto recibido de "+i.moneda+""+parseFloat(i.Totaloperacion).toFixed(2)+"  en "+i.banco;
                let cantItem=1;
                let montoItem=i.Tganancia;
                let subTotalItem=cantItem*  montoItem;

                if(isForNew == 1){
                descItemC="Comisiones ";
                cantItem=1;
                montoItem=1;
                subTotalItem=cantItem*  montoItem;
                }

                var codRefItem=i.moneda+""+parseFloat(i.Totaloperacion).toFixed(2)+""+i.banco+""+montoItem;

                %>

                <tr>

                    <td  style=" width: 50px;" ><input class="form-control"   style=" width: 50px;"   type="number"    name="cantItemCompro[]" value="<%=cantItem%>" > </td>
                    <td style=" width: 70px;" >
                        <select style=" width: 70px;padding-left: 0px;padding-right: 0px;" class="form-control" name="unidadItemCompro[]" >
                            <option value="NIU" selected="selected">Und.</option>
                           <!-- <option value="ZZ">Servicio.</option> -->
                        </select>
                    </td>
                    <td style="width: 500px;">
                        <input type="hidden" name="codrefx[]" value="<%=codRefItem%>">
                        <input type="text"  class="form-control" name="descItemCompro[]" value="<%=descItemC%>" >
                    </td>
                    <td><input style="text-align: right;padding-left: 0px;padding-right: 3px;" type="number"  class="form-control"  name="precioUItemCompro[]" value="<%=montoItem%>" > </td>
                    <td><input style="text-align: right; " type="number"  class="form-control" name="subTotalUItemCompro"   readonly="readonly" value="<%=subTotalItem%>" > </td>
                    <td><button type="button"  class="btn btn-xs btn-danger nBorrar"><i class="fa fa-trash"></i></button></td>
                </tr>
                <%  });  %>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3" style="text-align: right;">Total</th>
                    <th  >
                        <select style="padding-right: 0px;padding-left: 0px;font-size: 12px;"  id="tipomonedacompro" name="tipomonedacompro" class="form-control" >
                            <option value="PEN">Soles(S/)</option>
                            <option value="USD">Dolar($)</option>
                        </select>
                    </th>
                    <th><input readonly="readonly" style="text-align: right;" type="number" class="form-control" value="" id="totalVentaCompro" name="totalVentaCompro"> </th>
                </tr>
                </tfoot>
            </table>

            <div class="row">
                <div class="col-lg-12" style="text-align: center">
                    <button type="button" style="text-align: center" class="btn btn-mint" id="btnGeneraComprobante">Generar Comprobante</button>
                    <button type="button" style="text-align: center" class="btn btn-danger" data-dismiss="modal" >Cancelar</button>
                </div>
            </div>
        </div>

        <div class="col-lg-12" id="divUltimasTransaccionesBol">

        </div>
    </div>
</form>


</script>


<script type="text/template" id="tmpDetComproItems">

</script>

<script type="text/template" id="tmpBodyReportVentasByProveedor">
    <div class="table-responsive">
        <br>
        <table class="table table-striped" id="tableReporteVentasxProveedores">
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th><?=$monedaxxx?> Comprado</th>
                <th>Monto x compra</th>
                <th><?=$monedaxxx?> Vendido</th>
                <th>S/ x venta</th>
                <th>Precio venta</th>
            </tr>
            </thead>
            <tbody>

            <%
              var sumaIsMIsmoProve=0;
            var sumIsmImsoTotalSum=0;
             var idIsMIsmoProv=0;
             var idIsMIsmoVal=0;
            console.log(data.length);
            var preciounidadbtcxx=0;
            var totalcomprabtc=0;
            _.each(data,function(i,k){

            if(idIsMIsmoProv == 0){
             idIsMIsmoProv=i.idproveedor;
             idIsMIsmoVal=i.cantbtc;
            }


            %>

            <%



            if(idIsMIsmoProv == i.idproveedor && idIsMIsmoVal==i.cantbtc ){
            sumaIsMIsmoProve=sumaIsMIsmoProve+Number(parseFloat(i.montobtc));
            sumIsmImsoTotalSum=sumIsmImsoTotalSum+Number(parseFloat(i.montosoles));
              preciounidadbtcxx= parseFloat(i.preciounidadbtc);
              totalcomprabtc= parseFloat(i.cantbtc);
            }else{
            var ttxxx=preciounidadbtcxx*totalcomprabtc;
            ttxxx=parseFloat(ttxxx.toFixed(2));
            var ganx=parseFloat(Number(sumIsmImsoTotalSum).toFixed(2))-ttxxx;
            ganx=ganx.toFixed(2);
            print("<tr><th style='text-align: right;' colspan='3' >Total</th><th>"+ttxxx+"</th><th >"+parseFloat(Number(sumaIsMIsmoProve).toFixed(8))+"</th> <th style='text-align: right;' >"+parseFloat(Number(sumIsmImsoTotalSum).toFixed(2))+"</th><th>Ganancia:&nbsp;&nbsp; "+ganx+"</th></tr>");
            sumaIsMIsmoProve=0;
            sumaIsMIsmoProve=sumaIsMIsmoProve+Number(parseFloat(i.montobtc));
            sumIsmImsoTotalSum=0;
            sumIsmImsoTotalSum=sumIsmImsoTotalSum+Number(parseFloat(i.montosoles));
            preciounidadbtcxx= parseFloat(i.preciounidadbtc);
            totalcomprabtc= parseFloat(i.cantbtc);
            }


            idIsMIsmoProv=i.idproveedor;
           idIsMIsmoVal=i.cantbtc

            %>

            <tr>
                <td><%=i.fechaventa%></td>
                <td><%=i.nombre+" "+i.apellidos+" "+ i.razonsocial+" "+ i.ruc+" "+ i.documento+"|"+ i.pais  %></td>
                <td><%= i.cantbtc %></td>
                <td><%= i.preciounidadbtc %></td>
                <td><%= i.montobtc %></td>
                <td style="text-align: right;" ><%= i.montosoles %></td>
                <td style="text-align: right;" ><%= i.precioventa %></td>
            </tr>

            <%
            console.log((k+1),data.length);
            if((k+1) == data.length ){
             preciounidadbtcxx= parseFloat(i.preciounidadbtc);
              totalcomprabtc= parseFloat(i.cantbtc);
            var ttxxx=preciounidadbtcxx*totalcomprabtc;
            ttxxx=parseFloat(ttxxx.toFixed(2));
            var ganx=parseFloat(Number(sumIsmImsoTotalSum).toFixed(2))-ttxxx;
            ganx=ganx.toFixed(2);
            print("<tr><th style='text-align: right;' colspan='3' >Total</th><th>"+ttxxx+"</th><th   >"+parseFloat(Number(sumaIsMIsmoProve).toFixed(8))+"</th><th style='text-align: right;' >"+parseFloat(Number(sumIsmImsoTotalSum).toFixed(2))+"</th><th>Ganancia::&nbsp;&nbsp; "+ganx+"</th></tr>");
            sumaIsMIsmoProve=0;
            sumaIsMIsmoProve=sumaIsMIsmoProve+Number(parseFloat(i.montobtc));
            sumIsmImsoTotalSum=0;
            sumIsmImsoTotalSum=sumIsmImsoTotalSum+Number(parseFloat(i.montosoles));
            }
            %>
            <%    });  %>

            </tbody>
            <tfoot>

            </tfoot>
        </table>
    </div>
</script>

<script type="text/template" id="tmpBancosByMoneda">
    <% _.each(data ,function(i,k){

            %>
            <tr class="mdolares<%=i.idcuentabanco%>" data-trid="<%=i.idcuentabanco%>" >
                <td></td>
                <td class="tdNop"  style="width: 50%"><b><%=i.abreviatura%>: <%=i.ncuenta%> <button type="button" class="btn btn-xs btn-dark" onclick="addNewOp(this)" ><i class="fa fa-plus" ></i></button> </b>
                    <!--<div class="input-group" style="margin-top: 5px;">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-mint btn-sm" type="button"  >Nro Op.</button>
                                                    </span>
                                                    <input type="text" name="nrooperacion[]"   class="form-control text-right input-sm nrooperacion<?=$ii["idcuentabanco"]?> ">
                                                 </div>
                                                <small class="help-block classNroOPeracion" style="color: darkred; display: none;"  >Nro Operación ya fue registrado</small>
                                                <input type="hidden" name="cuentabanco[]" value="<?=$ii["idcuentabanco"]?>">-->

                    <div class="divDataOp">
                        <div class="input-group" style="margin-top: 5px;">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-mint btn-sm" type="button"  >Nro Op.</button>
                                                    </span>
                            <input type="text" name="nrooperacion[]"   class="form-control text-right input-sm nrooperacion<%=i.idcuentabanco%>" >
                        </div>
                        <small class="help-block classNroOPeracion" style="color: darkred; display: none;"  >Nro Operación ya fue registrado</small>
                        <input type="hidden" name="cuentabanco[]" value="<%=i.idcuentabanco%>">
                    </div>
                    <div class="divDataOpExtra"></div>


                </td>

                <td style="width: 50%" >
                    <br>
                    <div class="divMonto">
                        <input style="margin-top: 5px;" type="text" name="monto[]"   onkeyup="calcSubByCuenta(this)" class="form-control text-right input-sm montocuenta<%=i.idcuentabanco%> " value="">
                    </div>
                    <div class="divMontoExtra"></div>
                </td>

            </tr>
        <%   }); %>
</script>

<script type="text/template" id="tmpReporteByCliente" >
    <div class="table-responsive">
        <br>
        <table class="table table-striped" id="tableDetalleVentasBanco">
            <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Cuenta Banco/Monto</th>
            </tr>
            </thead>
            <tbody>
            <% _.each(data,function(i,k){  %>
            <tr>
                <td><%=(k+1)%></td>
                <td><%= i.nombre+" "+i.apellidos %></td>
                <td><% _.each(i.detallePago,function(ii,kk){ %>
                    <p><%=ii.nbanco%> <%=ii.ncuentabanco%>  <%=ii.nmonedaabreviado%> <%=ii.montototal%> </p>
                    <% }); %>
                </td>

            </tr>
            <% }); %>

            </tbody>
        </table>
    </div>
</script>

<script type="text/template" id="tmpFormCliente">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel">

                <form id="formRegDataCliente" class="form-horizontal">
                    <div class="panel-body">
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
                            <label class="col-sm-3 control-label" for="ncuenta">Wallet</label>
                            <div class="col-sm-6">
                                <input type="text" name="wallet"  id="wallet" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="demo-hor-inputpass">Documento</label>
                            <div class="col-sm-6">
                                <input type="text"   id="documento" name="documento" class="form-control">
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
                        <button class="btn btn-primary" type="button" id="btnSaveFormCliente">Guardar</button>
                        <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                    </div>

                </form>
                <!--===================================================-->
                <!--End Block Styled Form -->

            </div>
        </div>
    </div>


</script>


<script type="text/template" id="tmpBodyReportVentasBybanco">
    <div class="table-responsive">
        <br>
        <table class="table table-striped" id="tableDetalleVentasBanco">
            <thead>
            <tr   >

                <th colspan="3" style="text-align: right;" ><button type="button" onclick="checksGeneraCompro(this)" class="btn btn-purple btn-sm"  >Generar Comprobante(Checks) </button></th>
            </tr>
            <tr>
                <th>Banco</th>
                <th>Cuenta Banco</th>
                <th>Monto</th>
            </tr>
            </thead>
            <tbody>
            <%
            var totalSoles=0;
            var totalDolares=0;
             var totalGanSoles_=0;
            var totalGanDolares_=0;
            _.each(data,function(i,k){%>
            <tr>
                <td><%=i.abreviatura%></td>
                <td colspan="2">
                    <table class="table table-condensed" style="margin-bottom: 0px;">
                 <%
                    _.each(i.detCuentaBanco,function(ii,kk){  %>

                        <tr>
                            <td style="width: 30%;">
                                <%=ii.ncuentabanco%> <%=ii.nmoneda%> (<%=ii.abremoneda%>)
                            </td>
                            <td style="text-align: right;width: 40%;" >
                                <!--<table class="table table-condensed" >-->
                                 <% /*  var totalXOperacion=0;
                                    var labelMoneda="";
                                    _.each(ii.detPagoVenta,function(ix,kx){
                                    totalXOperacion+= parseFloat(ix.monto);
                                    if(ix.abremoneda == "$" ){
                                    totalDolares+=parseFloat(ix.monto);
                                    }
                                    if(ix.abremoneda == "S/"){
                                    totalSoles+=parseFloat(ix.monto);
                                    } */
                                var totalXOperacion=0;
                                var labelMoneda="";
                                var   gananciaBancoOp=0;
                                _.each(ii.detPagoVentaGanancia,function(ix,kx){
                                	 totalXOperacion+= parseFloat(ix.monto);
									
						            var calXXXTotalCambio_1=0;
						        	var tasacambio_1=parseFloat(Number(ix.tasacambio));
						        	var sumGanBanco_1= parseFloat(Number(ix.sumganbanco)) ;
						        	if(ix.nmonedaabreviado == "$"){
									calXXXTotalCambio_1=parseFloat(sumGanBanco_1/tasacambio_1).toFixed(2);
 									gananciaBancoOp+=Number(calXXXTotalCambio_1);
 									totalDolares+=parseFloat(ix.monto);
									totalGanDolares_+=Number(calXXXTotalCambio_1);
						        	}else{
						        		calXXXTotalCambio_1=sumGanBanco_1.toFixed(2);
						        		gananciaBancoOp+=Number(calXXXTotalCambio_1);
						        		  totalSoles+=parseFloat(ix.monto);
						        		  totalGanSoles_+=Number(calXXXTotalCambio_1);
						        	}
		

 									console.log(calXXXTotalCambio_1);
                               
                                


                                    %>
                                    <!--<tr>
                                        <td>
                                        NroOpe:<%=ix.nrooperacion%> ->  <%=ix.abremoneda%><%= ix.monto%>
                                        </td>
                                    </tr>-->
                                 <% });%>
                               <!-- </table> -->
                                <div class="tdClDet" style="padding-right: 25px;">

                                </div>
                                <b ><%=ii.abremoneda%> <%= parseFloat(totalXOperacion).toFixed(2) %>->M: <%=ii.abremoneda%> <%=Number(gananciaBancoOp).toFixed(2)%>
                                    <input type="checkbox" dataBanco="<%=i.abreviatura%>" dataTopera="<%=totalXOperacion%>" dataTGan="<%=Number(gananciaBancoOp).toFixed(2)%>" dataMoneda="<%=ii.abremoneda%>" name="checkForCompros" class="checkForCompros" >
                                    <button type='button' onclick='verDetalleReporteBanco(this,<%=JSON.stringify(ii.detPagoVentaGanancia)%>);' class='btn btn-xs btn-info'><i class="fa fa-eye"></i></button>
                                    <button type='button' onclick='generarComprobante(this,<%=totalXOperacion%>,"<%=i.abreviatura%>","<%=ii.abremoneda%>","<%=Number(gananciaBancoOp).toFixed(2)%>",0,[]);' class='btn btn-xs btn-dark'><i class="fa fa-calculator"></i> Generar comprobante</button>

                                </b>


                            </td>
                            <td></td>
                        </tr>

                    <%  }); %>
                    </table>
                </td>


            </tr>

            <% }); %>

            </tbody>
            <tfoot>
            <tr>

                <th> </th>
                <th style="text-align: right">
                    Total S/<%=parseFloat(totalSoles.toFixed(2))%>  |M S/<%=parseFloat(totalGanSoles_.toFixed(2))%> <br>
                    Total $<%=parseFloat(totalDolares.toFixed(2))%> |M $<%=parseFloat(totalGanDolares_.toFixed(2))%> 
                </th>
                <th>
                </th>
            </tr>
            </tfoot>
        </table>
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
                                    <label class="control-label">Cliente</label><br>
                                    <select data-placeholder="Seleccione..." id="cliente" name="cliente" tabindex="2">
                                        <option value="999999">Cliente Varios</option>
                                        <?php foreach($clientes as $k=>$i){  ?>
                                            <option value="<?=$i["idcliente"]?>" wallet="<?=$i["wallet"]?>"  ><?=$i["nombre"]?></option>
                                        <?php }?>
                                    </select>
                                    <button  style="display:inline-block;" id="btnAddNewCliente" type="button" class="btn btn-sm btn-mint"  ><i class="fa fa-plus"></i></button>

                                </div>
                            </div>
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Fecha</label>
                                    <input type="date" name="fecha" id="fecha" value="<?=date("Y-m-d")?>" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Wallet</label>
                                    <input type="text"  name="wallet" id="wallet" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">ID ref</label>
                                    <input type="text" name="idreffecha" id="idreffecha"   class="form-control">
                                    <small class="help-block"  style="color: darkred; display: none;" id="spanIdRefExiste" >ID ya se encuentra registrado</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="control-label" ><b>Detalle</b> <button type="button" class="btn btn-success btn-sm  " onclick="loadTrForTbody(0)" id=""> <i class="fa fa-plus"></i></button></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table   table-hover">
                                    <thead>
                                    <tr>
                                        <th>IDP<?=$monedaxxx?></th>
                                        <th>Stock</th>
                                        <th>Precio venta</th>
                                        <th>Cant.Venta <?=$monedaxxx?></th>
                                        <th>Cant.Venta S/</th>
                                        <th>SubTotal</th>
                                        <th>Acción</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbodyDetalleVenta">

                                    </tbody>
                                    <tfoot>
                                     <tr>
                                         <td colspan="5" style="text-align: right">Total S/</td>
                                         <td><b id="totalAPagarVenta"></b></td>
                                     </tr>
                                    </tfoot>
                                </table>
                            </div>


                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group center-block  " style="text-align: center">
                                <label class=" control-label" for="demo-hor-inputemail"><b>Cuenta en Soles <button type="button" class="btn-link" onclick="updateTableBancos('soles')"  title="Actualizar bancos"><i class="fa fa-refresh"></i> </button></b> </label>
                            </div>
                            <div id="tableBancosSoles">
                                <table class="table table-bordered table-hover table-condensed">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Banco</th>
                                        <th>Monto</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tBodyBancosSoles">
                                    <?php foreach($bancos as $kk=>$ii){

                                        if(strtolower($ii["moneda"]) == "soles" && $ii["estado"]==1   ){
                                            ?>
                                            <tr class="msoles<?=$ii["idcuentabanco"]?>" data-trid="<?=$ii["idcuentabanco"]?>" >
                                                <td></td>
                                                <td  class="tdNop"  ><b  >  <?=$ii["abreviatura"]?>: <?=$ii["ncuenta"]?>  <button type="button" class="btn btn-xs btn-dark" onclick="addNewOp(this)" ><i class="fa fa-plus" ></i></button> </b>

                                                    <!--<div class="input-group" style="margin-top: 5px;">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-mint btn-sm" type="button"  >Nro Op.</button>
                                                    </span>
                                                     <input type="text" name="nrooperacion[]"   class="form-control text-right input-sm nrooperacion<?=$ii["idcuentabanco"]?>" >
                                                 </div>
                                                 <small class="help-block classNroOPeracion" style="color: darkred; display: none;"  >Nro Operación ya fue registrado</small>
                                                 <input type="hidden" name="cuentabanco[]" value="<?=$ii["idcuentabanco"]?>">-->

                                                    <div class="divDataOp">
                                                        <div class="input-group" style="margin-top: 5px;">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-mint btn-sm" type="button"  >Nro Op.</button>
                                                    </span>
                                                            <input type="text" name="nrooperacion[]"   class="form-control text-right input-sm nrooperacion<?=$ii["idcuentabanco"]?>" >
                                                        </div>
                                                        <small class="help-block classNroOPeracion" style="color: darkred; display: none;"  >Nro Operación ya fue registrado</small>
                                                        <input type="hidden" name="cuentabanco[]" value="<?=$ii["idcuentabanco"]?>">
                                                    </div>
                                                    <div class="divDataOpExtra"></div>


                                                </td>
                                                <td>
                                                    <br>
                                                    <div class="divMonto">
                                                        <input style="margin-top: 5px;" type="text" name="monto[]"   onkeyup="calcSubByCuenta(this)" class="form-control text-right input-sm montocuenta<?=$ii["idcuentabanco"]?> " value="">
                                                    </div>
                                                    <div class="divMontoExtra"></div>
                                                </td>

                                            </tr>
                                        <?php }
                                    } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td></td>
                                        <td><b>Sub total</b></td>
                                        <td>
                                            <input type="text" id="subTSoles" name="subTotalPago[]"  readonly="readonly" class="form-control text-right input-sm " placeholder="0" >
                                        </td>

                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <div class="form-group center-block  " style="text-align: center">
                                <label class=" control-label" for="demo-hor-inputemail"><b>Cuenta en Dolares <button type="button" onclick="updateTableBancos('dolares')" class="btn-link" title="Actualizar bancos" ><i class="fa fa-refresh"></i> </button> </b></label>
                            </div>
                            <div id="tableBancosDolares">
                                <table class="table table-bordered table-hover table-condensed ">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Banco</th>
                                        <th>Monto</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tBodyBancosDolares">
                                    <?php foreach($bancos as $kk=>$ii){
                                        if(strtolower($ii["moneda"]) == "dolares"  && $ii["estado"]==1   ){
                                            ?>
                                            <tr class="mdolares<?=$ii["idcuentabanco"]?>" data-trid="<?=$ii["idcuentabanco"]?>" >
                                                <td></td>
                                                <td class="tdNop"  style="width: 50%"><b><?=$ii["abreviatura"]?>: <?=$ii["ncuenta"]?> <button type="button" class="btn btn-xs btn-dark" onclick="addNewOp(this)" ><i class="fa fa-plus" ></i></button> </b>
                                                    <!--<div class="input-group" style="margin-top: 5px;">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-mint btn-sm" type="button"  >Nro Op.</button>
                                                    </span>
                                                    <input type="text" name="nrooperacion[]"   class="form-control text-right input-sm nrooperacion<?=$ii["idcuentabanco"]?> ">
                                                 </div>
                                                <small class="help-block classNroOPeracion" style="color: darkred; display: none;"  >Nro Operación ya fue registrado</small>
                                                <input type="hidden" name="cuentabanco[]" value="<?=$ii["idcuentabanco"]?>">-->

                                                    <div class="divDataOp">
                                                        <div class="input-group" style="margin-top: 5px;">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-mint btn-sm" type="button"  >Nro Op.</button>
                                                    </span>
                                                            <input type="text" name="nrooperacion[]"   class="form-control text-right input-sm nrooperacion<?=$ii["idcuentabanco"]?>" >
                                                        </div>
                                                        <small class="help-block classNroOPeracion" style="color: darkred; display: none;"  >Nro Operación ya fue registrado</small>
                                                        <input type="hidden" name="cuentabanco[]" value="<?=$ii["idcuentabanco"]?>">
                                                    </div>
                                                    <div class="divDataOpExtra"></div>


                                                </td>

                                                <td style="width: 50%" >
                                                    <br>
                                                    <div class="divMonto">
                                                        <input style="margin-top: 5px;" type="text" name="monto[]"   onkeyup="calcSubByCuenta(this)" class="form-control text-right input-sm montocuenta<?=$ii["idcuentabanco"]?> " value="">
                                                    </div>
                                                    <div class="divMontoExtra"></div>
                                                </td>

                                            </tr>
                                        <?php }
                                    } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td></td>
                                        <td><b>Tasa de Cambio S/</b></td>
                                        <td style="display: inline-block;text-align: right;">
                                            <input style="display: inline-block;width: 30%;" type="text" id="tasaCambio" name="tasaCambio" onkeyup="calcSubByCuenta(this);"  class="form-control text-right input-sm " value="0">
                                            <input style="display: inline-block;width: 15%;font-weight: bold;" type="text"  value="$"  class="form-control text-right input-sm "  disabled="disabled">
                                            <input style="display: inline-block;width: 50%;" id="ssubTotal" type="text" name="ssubTotal"  class="form-control text-right input-sm "  readonly="readonly">
                                        </td>

                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><b> Sub total</b></td>
                                        <td>
                                            <input type="text" id="subTDolares"  name="subTotalPago[]"  readonly="readonly" class="form-control text-right input-sm " placeholder="0" >
                                        </td>

                                    </tr>
                                    </tfoot>

                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="text-align: center">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-6">
                            <table class="table">
                                <tr>
                                    <td>Total A Recibir S/</td>
                                    <td>

                                        <b id="totalARecibir" >0</b> </td>
                                </tr>
                                <tr>
                                    <td>Total pagado S/ </td>
                                    <td><b id="total" >0</b></td>
                                </tr>
                                <tr>
                                    <td>Resto S/</td>
                                    <td> <b id="resto" >0</b> </td>
                                </tr>
                            </table>

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


<script type="text/template" id="tmpTrTbodyDetalleVenta" >

    <tr>
        <td>
            <select name="prodVenta[]" class="form-control" >
                <option value="0-0-0-0" >Seleccione</option>
                <% _.each(data,function(i,k){  %>
                <option value="<%=i.iddetcompra%>-<%=parseFloat(i.stock)%>-<%=parseFloat(i.preciobaseventa)%>-<%=parseFloat(i.preciounidadbtc)%>"  ><%=i.proveedor%> (<%=(parseFloat(i.stock))%> <?=$monedaxxx?> )</option>
                <% }); %>
            </select>

        </td>
        <td>
            <input type="text" class="form-control"  name="stockHt[]" readonly="readonly" style="width: 90px;text-align: right;padding-left: 1px;padding-right: 1px;" value="">

        </td>

        <td>
            <input type="text" class="form-control" name="pventa[]"   style="width: 80px;text-align: right" >
            <span class="spanPrecioBaseVenta"> *0000</span>

        </td>
        <td><input type="text"  class="form-control" name="cantBtc[]"   style="width: 100px; text-align: right" > </td>
        <td style="display: inline-block" ><input type="text" class="form-control" name="cantSoles[]"   style="display: inline-block;width: 100px;text-align: right" ><input type="checkbox" name="isCheck[]"  checked="checked"  style="margin-left:3px;display: inline-block"> </td>
        <td><input type="text" class="form-control" name="subTotal[]" readonly="readonly"   style="width: 100px; text-align: right" >  </td>
        <td style="display: inline-block;width:90px;" >
            <button type="button" class="btn btn-danger btn-sm   " onclick="removeTr(this)" id=""> <i class="fa fa-minus"> </i></button>
              </td>
    </tr>
</script>



<script type="text/template" id="tmpTrTbodyDetalleVentaEdit" >
 <% _.each(data,function(i,k){  %>
     <tr>
         <td>
             <select name="prodVenta[]" class="form-control" >
                 <option value="0-0-0-0" >Seleccione</option>
                 <% var existSelect=false;
                    var stockrealXY=0;
                        var valCompraStock=0;
                   _.each(dtprodStock,function(ii,kk){

                  %>
                 <% if(ii.iddetcompra==i.idrefdetcompra ){
                    existSelect=true;
                   stockrealXY=parseFloat(ii.stock)+parseFloat(i.montobtc);
                 valCompraStock=parseFloat(ii.preciounidadbtc);
                   %>
                 <option selected="selected" value="<%=ii.iddetcompra%>-<%=parseFloat(ii.stock)%>-<%=parseFloat(ii.preciobaseventa)%>-<%=parseFloat(ii.preciounidadbtc)%>"  ><%=ii.proveedor%> (<%=(parseFloat(ii.stock))%> <?=$monedaxxx?> )</option>

                 <% }else{
                    if(existSelect == true ){
                     existSelect=true;
                    }else{
                    existSelect=false;
                    }

                 %>
                 <option value="<%=ii.iddetcompra%>-<%=parseFloat(ii.stock)%>-<%=parseFloat(ii.preciobaseventa)%>-<%=parseFloat(ii.preciounidadbtc)%>"  ><%=ii.proveedor%> (<%=(parseFloat(ii.stock))%> <?=$monedaxxx?> )</option>

                 <% } %>
                 <% });

                 %>
                 <% if(existSelect == false ){
                 stockrealXY=parseFloat(i.montobtc);
                 valCompraStock=parseFloat(i.preciounidadbtccomprado);
                 %>
                 <option selected="selected"  value="<%=i.idrefdetcompra%>-<%=i.montobtc%>-<%=i.preciobaseventa%>-<%=parseFloat(i.preciounidadbtccomprado)%>" ><%=i.proveedor%> (<%=i.montobtc%> <?=$monedaxxx?> ) </option>
                 <%  }   %>
             </select>

         </td>
         <td>
             <input type="text" class="form-control"  name="stockHt[]" readonly="readonly" style="width: 90px;text-align: right;padding-left: 1px;padding-right: 1px;" value="<%=parseFloat(stockrealXY)%>">

         </td>

         <td>
             <input type="text" class="form-control" name="pventa[]" value="<%=i.precioventa%>"   style="width: 80px;text-align: right" >
             <span class="spanPrecioBaseVenta"> *<%=valCompraStock%></span>
         </td>
         <td><input type="text"  class="form-control" name="cantBtc[]" value="<%=i.montobtc%>"  style="width: 100px; text-align: right" ><input type="checkbox" name="isCheck[]"  checked="checked"  style="margin-left:3px;display: inline-block">  </td>



         <td><input type="text" class="form-control" name="cantSoles[]" value="<%=i.montosoles%>"    style=" width: 100px;text-align: right" > </td>
         <% var ssstotal= (parseFloat(i.montobtc) * parseFloat(i.precioventa));
         ssstotal=ssstotal.toFixed(2);
         ssstotal=parseFloat(ssstotal);
         %>
         <td><input type="text" class="form-control" name="subTotal[]" readonly="readonly"  value="<%=ssstotal%>"  style="width: 100px; text-align: right" >  </td>
         <td style="display: inline-block;width:90px;" >
             <button type="button" class="btn btn-danger btn-sm   " onclick="removeTr(this)" id=""> <i class="fa fa-minus"> </i></button>
          </td>
     </tr>
 <% }); %>

</script>





<div class="modal fade" id="modalId" role="dialog" tabindex="-1"  data-backdrop="static"   aria-hidden="true" >
    <div class="modal-dialog modal-lg" style="width: 75%">
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


<div class="modal fade" id="modalReport" role="dialog" tabindex="-1"  data-backdrop="static"   aria-hidden="true" >
    <div class="modal-dialog modal-lg " style="width: 90%;" >
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id=" ">Reporte ventas</h4>
            </div>

            <!--Modal body-->
            <div class="modal-body" id="">
                <div class="panel-body">
                    <div class="row">
                         De-Hasta
                        <input type="date" id="dtfechaventaini" value="<?=date("Y-m-d")?>" >
                        <input type="date" id="dtfechaventaend" value="<?=date("Y-m-d")?>" >
                        <button id="btnGenerarDtVenta" type="button" class="btn btn-mint btn-sm">Reporte de ventas</button>
                        <button id="btnGenerarDtVentaXBanco" type="button" class="btn btn-mint btn-sm">Reporte de ventas x Banco</button>
                        <button id="btnGenerarDtVentaXCliente" type="button" class="btn btn-mint btn-sm">Reporte de ventas x Cliente</button>
						 <button id="btnGenerarDtVentaXProveedor" type="button" class="btn btn-mint btn-sm">Reporte de ventas x Proveedor</button>

                    </div>
                    <div class="row" id="divResultReport">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/template" id="tmpTableVenta">
    <div class="table-responsive">
        <br>
        <table class="table table-striped" id="tableReporteVentas">
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Wallet</th>
                <th><?=$monedaxxx?> Vendido</th>
                <th>Ganancia</th>
                <th>Detalle de pagos</th>
                <th>*</th>
            </tr>
            </thead>
            <tbody>
            <% var TotalGananciaGeneral=0;
            var totalBtcVendido=0;
            var totalDetallePago=0;
            var bancosT=[];
             _.each(data,function(i,k){ %>
                <tr>
                    <td><%=formatDateYMD(i.fechaventa) %></td>
                    <td><%=i.nombres %> <%=i.apellidos%></td>
                    <td><%=i.wallet%></td>
                    <td>
                        <%
                        var ttbtc=0,ttsoles=0;
                        _.each(i.detVenta,function(ii,kk){
                        ttbtc+=parseFloat(ii.montobtc);
                        ttsoles+=parseFloat(ii.montosoles);
                        %>
                        <p style="margin-bottom: 0px;" > <%=ii.montobtc%> USDT* = S/<%=ii.montosoles%>    / <%=ii.preciounidadbtccomprado%> </p>
                        <% }); %>
                        <b>Total:<%= ttbtc%> USDT* = S/<%= ttsoles%>   </b>

                    </td>

                    <td>
                         <% var totalGanaciaTr=0;


                          _.each(i.detVenta,function(ix,kx){
                        totalBtcVendido+=parseFloat(ix.montobtc);
                         %>
                        <p><%=parseFloat(ix.montobtc)%><?=$monedaxxx?> = Venta:<%=ix.precioventa%> Compra: <%=ix.preciounidadbtccomprado%>
                           <%  var montoSolesVenta1=parseFloat(ix.montobtc)*parseFloat(ix.precioventa);
                            var montoSolesVenta2=parseFloat(ix.montobtc)*parseFloat(ix.preciounidadbtccomprado);
                            var ganancia=montoSolesVenta1-montoSolesVenta2;
                            TotalGananciaGeneral+=ganancia;
                            %>
                            <br>
                            <b>Gananacia=(<%=montoSolesVenta1.toFixed(2)%> -<%=montoSolesVenta2.toFixed(2)%> )=<%=ganancia.toFixed(2) %> </b>
                        </p>
                       <%  });     %>

                    </td>
                    <td><% var totalPagadoXy=0;
                        _.each(i.detPago,function(iii,kkk){
                         if(iii.abremoneda == "S/"){ totalPagadoXy+=parseFloat(iii.monto);  %>
                        <p style="margin-bottom: 0px;" ><%=iii.abrebanco %> : <%=iii.ncuenta %> <%=iii.abremoneda %><%=iii.monto %> </p>

                        <%  }else{
                        var sbtc=parseFloat(iii.monto) * parseFloat(iii.tasacambio)
                        totalPagadoXy+=parseFloat(sbtc);  %>
                        <p style="margin-bottom: 0px;" ><%=iii.abrebanco %> : <%=iii.ncuenta %> <%=iii.abremoneda %><%=iii.monto %> = S/<%=sbtc %> </p>

                        <% }
                       }); totalDetallePago+=totalPagadoXy; %>
                        <b>   S/<%= totalPagadoXy %>   </b>

                    </td>
                    <td>*</td>
                </tr>
            <%  }); %>

            </tbody>
            <tfoot>
            <tr>
                <th> </th>
                <th> </th>
                <th> </th>
                <th><%= parseFloat(parseFloat(totalBtcVendido).toFixed(8)) %> <?=$monedaxxx?>  </th>
                <th>S/<%=TotalGananciaGeneral.toFixed(2)%> <button onclick="registrarEnCaja('<%=TotalGananciaGeneral%>')" type="button" class="btn btn-link btn-xs">Registrar en Caja</button>   </th>
                <th>S/<%=totalDetallePago.toFixed(2)%> </th>
                <th>*</th>
            </tr>
            </tfoot>
        </table>
    </div>
</script>


<div class="modal fade" id="modalIdNewForm" role="dialog" tabindex="-1"  data-backdrop="static"   aria-hidden="true" >
    <div class="modal-dialog ">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="">Registrar Cliente</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="bModalBodyNewForm">


            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalIdEnvioCaja" role="dialog" tabindex="-1"  data-backdrop="static"   aria-hidden="true" >
    <div class="modal-dialog ">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" ><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="">Enviar A Caja</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="bModalBodyEnvioCaja">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel">

                            <form id="formRegEnvioCaja" class="form-horizontal">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="ncuenta">Monto</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="montoencaja"  id="montoencaja" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="ncuenta">Fecha</label>
                                        <div class="col-sm-6">
                                            <input type="date" name="fechaenviocaja"  id="fechaenviocaja" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="ncuenta">Descripción</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="descripcionenviocaja"  id="descripcionenviocaja" class="form-control" >
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <button class="btn btn-mint" type="button" id="btnSaveEnviarACaja">Enviar a Caja</button>
                                    <button class="btn btn-warning "  data-dismiss="modal"  type="button">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalIdSearchNOP" role="dialog" tabindex="-1"  data-backdrop="static"   aria-hidden="true" >
    <div class="modal-dialog modal-lg"  >
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id=" ">Resultados de busqueda de Nro de Operación #<b id="nroOperacionLabel"></b> </h4>
            </div>

            <!--Modal body-->
            <div class="modal-body" id="bModalSearchNOP">
                <h3>Cargando...</h3>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalGenerarComprobante" role="dialog" tabindex="-1"  data-backdrop="static"   aria-hidden="true" >
    <div class="modal-dialog modal-lg" style="width: 80%;" >
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="ModalTitleGenerarComprobante"> Generar comprobante</h4>
            </div>

            <!--Modal body-->
            <div class="modal-body" id="modalBodyGenerarComprobante">
                <h3>Cargando...</h3>
            </div>
        </div>
    </div>
</div>