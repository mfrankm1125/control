<div id="page-content">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel">
                <div class="panel-heading">

                    <div class="panel-control">
                        <button class="demo-panel-ref-btn btn btn-default" data-toggle="panel-overlay" onclick="refrescar();"  ><i class="demo-psi-repeat-2"></i></button>
                    </div>
                    <h3 class="panel-title"  ><b style="font-size: 18px;">Compra Dolares</b>  &nbsp; <button class="btn btn-mint btn-sm" id="btnAddNew">Nuevo+</button> </h3>
                    <hr style="margin-bottom: 0px;margin-top: 0px;">
                </div>

                <!--Data Table-->

                <!--===================================================-->
                <div class="panel-body">
                    <div class="row" >
                        <div class="col-lg-12">
                            <div class="col-sm-4 pad-top">
                                <div class="text-lg">
                                    <p class="text-2x text-thin text-main"><b style="font-size: 9px;">Stock: </b>S/<b id="bglobalStock">...</b></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <table id="dtTable" class="table table-bordered table-hover table-condensed">
                            <thead>
                            <tr> <th>#</th>
                                <th>Detalle</th>
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
        getStockTotal();
    });

    function getStockTotal() {
        var fini='<?=date("Y-m-d")?>';
        $.post(url_base+"compradolares/getStockSoles",{fini:fini},function (data) {
           $("#bglobalStock,#StockSolesReg").html(data.stock);

        },'json');
    }

function dtIni(){
    dtTable=$('#dtTable').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?= base_url(); ?>compradolares/getData",
            "type": "POST"
        },
        "buttons": [
            'pdf', 'print', 'excel', 'copy', 'csv',
        ],
        "columns": [
            { "data": null,"searchable":false },
            {   "sortable": false,
                "searchable":false,
                "render": function ( data, type, full, meta ) {
                    var ht="";
                    var id = full.idcomprad;
                    var dd=JSON.parse(full.detcompraf);
                    console.log(dd);
                    let ttcccx=0;
                    let ttcpagx=0;
                    $.each(dd,function(kk,ii){
                        ttcccx=ttcccx+parseFloat(Number(ii.montocompra));
                        let sbcc=parseFloat(Number(ii.montocompra))*parseFloat(Number(ii.tasacambiocompra));
                        ttcpagx=ttcpagx+sbcc;
                        ht+="<p>"+ii.monedaav +" "+ii.montocompra+ " |TC:S/"+ii.tasacambiocompra+"| Inv: S/"+sbcc.toFixed(2)+" </p>";
                    });
                    ht+="<b> Total $"+ttcccx+" => S/"+ttcpagx+"</b>";

                    return ht;
                }
            },
            { "data": "fechacompra" },
            {   "sortable": false,
                "searchable":false,
                "render": function ( data, type, full, meta ) {
                    var id = full.idcomprad;
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
    $("#monedacompra").val(2);
    $("#monedacompra option:not(:selected)").attr('disabled', true);
    getStockTotal();
   // getProveedores(0);
    //getBanco();
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
    var pcbtc=$("#pcbtc");
    var cantbtc=$("#cantbtc");


    var tt=Number(parseFloat($(this).val()))/Number(parseFloat(pcbtc.val()));
    cantbtc.val(tt.toFixed(8));
    $("#totalAPagar").html($(this).val());
});


    $(document).on("keyup","#cantbtc",function () {
        var pcbtc=$("#pcbtc");
        var cantsoles=$("#cantsoles");
        var tt=Number(parseFloat($(this).val()))*Number(parseFloat(pcbtc.val()));
        cantsoles.val(tt.toFixed(2));
        $("#totalAPagar").html(tt.toFixed(2));
    });

  $(document).on("click","#btnSaveForm",function () {
      var dataForm=[];
      var tr_=$(".dataTr");
      var ntr=tr_.length;

      var tasacambiocompra=$("#tasacambiocompra");
      var tVentaRef=$("#tVentaRef");
      var cantCompra=$("#cantCompra");
      var bol=true;

      bol=bol&&tasacambiocompra.required();
      bol=bol&&tVentaRef.required();
      bol=bol&&cantCompra.required();
      if(!bol){
          alert_error("Ingrese los campos requeridos");
          return 0;
      }


      /*if(ntr == 0){
          alert_error("Ingrese el detalle de compra");
          return 0;
      }*/
      /*if( Number($("#proveedor").val())==0 ){
          alert_error("Ingrese el proveedor");
          return 0;
      }*/

      /* var detV=[];
      for(var i=0;i<ntr ;i++){
         let idMonedac=$(tr_[i]).find("input[name='idMonedac[]']").val();
          let iddetcompra=$(tr_[i]).find("input[name='iddetcompra[]']").val();
         let tasacambiocomprac=$(tr_[i]).find("input[name='tasacambiocomprac[]']").val();
         let tcVentaRefc=$(tr_[i]).find("input[name='tcVentaRefc[]']").val();
         let cantComprac=$(tr_[i]).find("input[name='cantComprac[]']").val();

          let isVisibleVenta_=$(tr_[i]).find("select[name='isVisibleVenta_[]']").val();

          let contDataDetPago=$(tr_[i]).find(".contDataDetPago");

          let idcuentabancoreg=$(contDataDetPago).find("input[name='idcuentabancoreg[]']");
          let iddetpagocomprad=$(contDataDetPago).find("input[name='iddetpagocomprad[]']");
          let nopreg=$(contDataDetPago).find("input[name='nopreg[]']");
          let montopagoreg=$(contDataDetPago).find("input[name='montopagoreg[]']");
            var detPago=[];
          for( var j=0;j<idcuentabancoreg.length ;j++){
            let idcuentabanco= $(idcuentabancoreg[j]).val();
            let nopxx=$(nopreg[j]).val();
              let montoregxx=$(montopagoreg[j]).val();
              let _iddetpagocomprad=$(iddetpagocomprad[j]).val();
              detPago.push({
                  idcuentabanco:idcuentabanco,
                  nroop:nopxx,
                  monto:  montoregxx,
                  iddetpagocomprad:_iddetpagocomprad
              });

          }
          detV.push({
              idmoneda:idMonedac ,
              iddetcompra:iddetcompra,
              tcambio:tasacambiocomprac ,
              tcambioventa:tcVentaRefc ,
              cantcompra:cantComprac,
              isVisibleVenta_:isVisibleVenta_,
              detPago:detPago
          });

      }
      dataForm.push({
          proveedor:$("#proveedor").val(),
          fecha:$("#fecha").val(),
          detallecompra:detV,
          isEdit:$("#isEdit").val(),
          idEdit:$("#idEdit").val()
         }
      );

      //console.log(dataForm);
      */

      var detV=[];
      detV.push({
          idmoneda:$("#monedacompra").val() ,
          iddetcompra:$("#iddetcompra").val(),
          tcambio:$("#tasacambiocompra").val()  ,
          tcambioventa:$("#tVentaRef").val()  ,
          cantcompra:$("#cantCompra").val() ,
          isVisibleVenta_:$("#isVisibleVenta").val() ,
          isRegEgreso:$("#isRegEgreso").val() ,
          detPago:[]
      });
      dataForm.push({
              proveedor:$("#proveedor").val(),
              fecha:$("#fecha").val(),
              detallecompra:detV,
              isEdit:$("#isEdit").val(),
              idEdit:$("#idEdit").val()
          }
      );

      var btnSaveForm=$("#btnSaveForm");
      btnSaveForm.button("loading");
      $.post(url_base+"compradolares/setForm",{dataForm},function (data) {
         if(data.status == "ok"){
             alert_success("Correcto");
             if($("#isEdit").val() == 0){
                 var tmpFormCompra=_.template($("#tmpFormCompra").html());
                 $("#bModalBody").html(tmpFormCompra);

                // getProveedores(0);
                 //getBanco();
             }else{
                 $("#modalId").modal("hide");
             }

         }else{
             alert_success("Fallo");
         }
          getStockTotal();
          refrescar();
          btnSaveForm.button("reset");

      },'json');
  });

  function eliminar(id) {
      if(!confirm("¿Seguro de eliminar este registro?")){
          return 0;
      }
      $.post(url_base+"compradolares/delete",{"id":id},function (data) {
          getStockTotal();
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
      $("#hModalTitle").html("Editar Compra Dolares");
      $("#bModalBody").html("Cargando...");
      $.post(url_base+"compradolares/getDataEdit",{id:id},function (data) {
          if(data.status=="ok" && data.data.length > 0){
              var dtEdit=data.data[0];
              var tmpFormCompra=_.template($("#tmpFormCompra").html());
              $("#bModalBody").html(tmpFormCompra);
              $("#isEdit").val(1);
              $("#idEdit").val(id);
              //getProveedores(dtEdit.idproveedor);
              //getBanco();
              var mydate = new Date(dtEdit.fechacompra);
              var dia=mydate.getDate();
              var mes=(mydate.getMonth()+1);
              var anio=mydate.getFullYear();
              if (mes < 10) mes = '0' + mes;
              if (dia < 10) dia = '0' + dia;
              var ffxd=anio+"-"+mes+"-"+dia;

               var detallecompra=dtEdit.detallecompra;
                console.log(detallecompra[0]);
              $("#tasacambiocompra").val(detallecompra[0].tasacambiocompra);
               $("#tVentaRef").val(detallecompra[0].tasacambioventa);
               $("#cantCompra").val(detallecompra[0].montocompra);
              $("#monedacompra").val(detallecompra[0].idmoneda);
              $("#iddetcompra").val(detallecompra[0].iddetcomprad);


              calcSubTotalPagar();
             //  var tmpAddDetaillEdit=_.template($("#tmpAddDetaillEdit").html());
              // $("#tBodyDetailVenta").html(tmpAddDetaillEdit({data:detallecompra}));

              $("#fecha").val(ffxd);

              //calSubtotalTotalsDetPago();
              //calSubtotalTotals()
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
        var btn=$(this);
        var form=$("#formRegDataProv").serialize();
        btn.button("loading");
        $.post(url_base+"compras/setFormProv",form,function (data) {
            if(data.status == "ok"){
                refrescar();
                alert_success("Correcto");
                $("#modalIdNewForm").modal("hide");
                if(data.data.length > 0){
                    getProveedores(data.data[0].idproveedor);
                   /* let $option = $('<option />', {
                        text: data.data[0].nombre,
                        value: data.data[0].idproveedor
                    });
                    $('#proveedor').prepend($option);
                    $("#proveedor").val(data.data[0].idproveedor);*/
                }else{
                }
            }else{
                alert_success("Fallo");
            }
            btn.button("reset");
        },'json');
    });
    


    $(document).on("click","#btnAddInDetail",function () {

        let monedacompra=$('#monedacompra option:selected').text();
        let idmoneda=$('#monedacompra').val();
        let tasacambiocompra=$("#tasacambiocompra");
        let cantCompra=$("#cantCompra");
        let xPagar=$("#xPagar");
        let tVentaRef=$("#tVentaRef");
        let isVisibleVenta=$("#isVisibleVenta");

        let tablaDetailPagoBanco=$("#tablaDetailPagoBanco");


        let tbodyDetailPagoBanco=$("#tbodyDetailPagoBanco");
        let tmpAddDetaill=_.template($("#tmpAddDetaill").html());
        let tablaDetailPagoBanco_=$("#tablaDetailPagoBanco_ >tbody >tr").length;
        if(tablaDetailPagoBanco_ == 0){
            alert_error("Agregue el detalle de pago por favor!!");
            return 0;
        }
        let selBancoCuenta=$("select[name='selBancoCuenta']");
        let nOper=$("input[name='nOper']");
        let montoPago=$("input[name='montoPago']");

        var detpagoHT="";
        //sconsole.log(selBancoCuenta.length);
        var totalx_=0;
        var bolSelBanco=true;
        var bolSelMonto=true;
         for(var ii=0; ii < selBancoCuenta.length;ii++){
             let bnidcuenta=$(selBancoCuenta[ii]).val();
             let txtbncuenta=selBancoCuenta[ii].options[selBancoCuenta[ii].selectedIndex].text;
             let bnOper=$(nOper[ii]).val();
             let bmontoPago=$(montoPago[ii]).val();
             if(parseFloat(Number(bmontoPago)) == 0){
                 bolSelMonto=bolSelMonto&&false;
             }else{
                 bolSelMonto=bolSelMonto&&true;
             }
             totalx_=totalx_+parseFloat(Number(bmontoPago));
             detpagoHT+="<p style='margin-bottom: 1px;' ><b>Cuenta:</b>"+txtbncuenta+" <b>NroOp:</b> "+ bnOper +" <b>Monto:S/</b>"+bmontoPago+"  </p>";
             detpagoHT+="<input type='hidden' name='idcuentabancoreg[]' value='"+bnidcuenta+"' >";
             detpagoHT+="<input type='hidden' name='nopreg[]' value='"+bnOper+"'>";
             detpagoHT+="<input type='hidden' name='montopagoreg[]' value='"+bmontoPago+"'>";
             detpagoHT+="<input type='hidden' name='iddetpagocomprad[]' value=0>";

         }

         if((totalx_ == 0) || (bolSelMonto == false)){
             alert_error("Por favor verifique el monto de pago");
             return 0;
         }
        detpagoHT+="<p style='margin-bottom: 4px;' ><b>TOTAL S/:"+totalx_+"<input type='hidden' name='totalx_detPagotr' value='"+totalx_+"'> </b></p>";

        $("#tBodyDetailVenta").append(tmpAddDetaill({isVisibleVenta:isVisibleVenta.val(),tablaDetailPagoBanco:detpagoHT,tVentaRef:tVentaRef.val(),idmoneda:idmoneda,monedacompra:monedacompra,tasacambiocompra:tasacambiocompra.val(),cantCompra:cantCompra.val(),xPagar:xPagar.val()}));


          tasacambiocompra.val("");
          cantCompra.val("") ;
          xPagar.val("") ;
          tVentaRef.val("") ;
        calSubtotalTotals();
        tbodyDetailPagoBanco.html("");
        getBanco();
        $("#totalPagoBanco").val("");
    });

    $(document).on("click",".deletetr",function () {
        var ctx=$(this);
        var tr=ctx.closest("tr").remove();
      calSubtotalTotals();
        calSubtotalTotalsDetPago();
    });

    function calSubtotalTotals() {
        var ssubtotal=$("input[name='ssubtotalc[]']");
        var totalx_detPagotr=$("input[name='totalx_detPagotr']");
        var stt=0;
        var sttx=0;
        var sttx2=0;
        var ttDolares=$("input[name='cantComprac[]']");
        $.each(ssubtotal,function(k,i){
            stt+=parseFloat(Number($(i).val()));
        });
        $.each(totalx_detPagotr,function(kk,ii){
            sttx+=parseFloat(Number($(ii).val()));
        });
        $.each(ttDolares,function(kkk,iii){
            sttx2+=parseFloat(Number($(iii).val()));
        });

        $("#subTotal").val(stt.toFixed(2));
        $("#subTotalDetPago_").val(sttx.toFixed(2));
        $("#subTotalDolares_").val(sttx2.toFixed(2));
       // $("#totalcompra").val((parseFloat(stt)));
    }

    $(document).on("keyup","input[name='montoPago']",function () {
        calSubtotalTotalsDetPago();
    });

    function calSubtotalTotalsDetPago() {
        var ssubtotal=$("input[name='montoPago']");
        var stt=0;
        $.each(ssubtotal,function(k,i){
            stt+=parseFloat(Number($(i).val()));
        });

        $("#totalPagoBanco").val(stt.toFixed(2));

        // $("#totalcompra").val((parseFloat(stt)));
    }


    
    $(document).on("keyup","#cantCompra",function () {
        calcSubTotalPagar();
    });
    $(document).on("keyup","#tasacambiocompra",function () {
        calcSubTotalPagar();
    });
    function calcSubTotalPagar() {
        let tasacambiocompra=$("#tasacambiocompra");
        let cantCompra=$("#cantCompra");
        let xPagar=$("#xPagar");
       // let tVentaRef=$("#tVentaRef");
        xPagar.val((Number(tasacambiocompra.val())*Number(cantCompra.val())).toFixed(2));
    }
    $(document).on("click","#btnAddDetailPagoBanco",function () {
        getBanco();
    });


    function getBanco() {
        var tmpSelCuentaBancoPago=_.template($("#tmpSelCuentaBancoPago").html());
        $.post(url_base+"compradolares/getBancoByMoneda",function (data) {
            $("#tbodyDetailPagoBanco").append(tmpSelCuentaBancoPago({data:data}));
        },'json');
    }


    function getProveedores(id) {
        var tmpSelProv=_.template($("#tmpSelProv").html());
        $.post(url_base+"compradolares/getProv",function (data) {
            if(data.status=="ok"){
                $("#divSelProv").html(tmpSelProv({data:data.data}));

                $('#proveedor').chosen({width:'100%'});
                $('#proveedor').val(id);
                $("#proveedor").trigger("chosen:updated");
            }
        },'json');
    }

    $(document).on("keyup","input[name='tasacambiocomprac[]']",function () {
        var ctx=$(this);
        var vl=$(this).val();
        var tr=ctx.closest("tr");
        var cant=tr.find("input[name='cantComprac[]']").val();
        var sstotalxx=tr.find("input[name='ssubtotalc[]']");
        var trsub=parseFloat(vl)*parseFloat(cant);
        sstotalxx.val(trsub);
        calSubtotalTotals();
    });
    $(document).on("keyup","input[name='cantComprac[]']",function () {
        var ctx=$(this);
        var vl=$(this).val();
        var tr=ctx.closest("tr");
        var pcomprac=tr.find("input[name='tasacambiocomprac[]']").val();
        var sstotalxx=tr.find("input[name='ssubtotalc[]']");
        var trsub=parseFloat(vl)*parseFloat(pcomprac);
        sstotalxx.val(trsub);
        calSubtotalTotals();
    });

    $(document).on("change","select[name='isVisibleVenta_[]']",function () {
        var ctx=$(this);
        var tr=ctx.closest("tr");
        var isVisibleVenta=ctx.val();
        console.log(isVisibleVenta);
        let color_Tr="";
        if(isVisibleVenta == 1){
            color_Tr="";
        }else{
            color_Tr="#ff000029";
        }
         $(tr)["0"].style.backgroundColor =color_Tr;
    });

</script>

<script type="text/template" id="tmpSelProv">
    <select data-placeholder="Seleccione..." id="proveedor" name="proveedor" tabindex="2">
        <option value="99999">Proveedor Varios</option>
        <% _.each(data,function(i,k){ %>
        <option value="<%= i.idproveedor %>"><%=i.nombre %></option>
        <% }); %>
    </select>
</script>

<script type="text/template" id="tmpSelCuentaBancoPago">
    <tr>
        <td><select  style="padding-right:0px;padding-left:0px;width:55%;display: inline-block" class="form-control input-sm" name="selBancoCuenta" >
            <% _.each(data,function(i,k){%>
                <option value="<%= i.idcuentabanco%>"><%=i.abreviado +" "+ i.nbanco +" "+ i.ncuentabanco%></option>
           <%  });%>
            </select>
            <input type="text" class="form-control text-right" name="nOper" style="width:43%;display: inline-block" >
        </td>
        <td><input type="text" class="form-control input-sm text-right" name="montoPago" value="" ></td>

        <td> <button class="btn btn-danger btn-xs deletetr" type="button" ><i class="fa fa-trash"></i></button>  </td>
    </tr>
</script>



<script type="text/template" id="tmpAddDetaill">
    <% let checkSI="";
    let checkNO="";
    let color_Tr="";
    if(isVisibleVenta == 1){
    checkSI="selected='selected'";
    color_Tr="";
    }else{
    checkNO="selected='selected'";
    color_Tr="#ff000029";
    }

    %>
    <tr class="dataTr" style="background-color: <%=color_Tr%>" >
        <td>
            <input type="hidden" name="iddetcompra[]" value="0">
            <input type="hidden" name="idMonedac[]" value="<%=idmoneda%>">
        <%=monedacompra%>
        </td>
        <td>

            <select name="isVisibleVenta_[]" class="form-control input-sm" >
                <option value="1"  <%=checkSI%> >SI</option>
                <option value="2"  <%=checkNO%> >NO</option>
            </select>
        </td>
        <td><input type="text"  class="form-control input-sm text-right" name="tasacambiocomprac[]" value="<%=tasacambiocompra%>">   </td>
        <td><input type="text"   class="form-control input-sm text-right" name="tcVentaRefc[]" value="<%=tVentaRef%>">   </td>
        <td><input type="text"   class="form-control input-sm text-right" name="cantComprac[]" value="<%=cantCompra%>">   </td>
        <td><div class="contDataDetPago">
                <%=tablaDetailPagoBanco%>
            </div>
        </td>
        <td><input type="text" readonly="readonly" class="form-control input-sm text-right " name="ssubtotalc[]" value="<%=xPagar %>"> </td>
        <td> <button class="btn btn-danger btn-xs deletetr" type="button" ><i class="fa fa-trash"></i></button>  </td>
    </tr>
</script>


<script type="text/template" id="tmpAddDetaillEdit">
    <% _.each(data,function(i,k){%>

    <% let checkSI="";
    let checkNO="";
    let color_Tr="";
    if(i.isvisible == 1){
    checkSI="selected='selected'";
    color_Tr="";
    }else{
    checkNO="selected='selected'";
    color_Tr="#ff000029";
    }

    %>
    <tr class="dataTr" style="background-color: <%=color_Tr%>"  >
        <td>
            <input type="hidden" name="iddetcompra[]" value="<%=i.iddetcomprad%>">
            <input type="hidden" name="idMonedac[]" value="<%=i.idmoneda%>">
            <%=i.monedax%>
        </td>
        <td>

            <select name="isVisibleVenta_[]" class="form-control input-sm" >
                <option value="1"  <%=checkSI%> >SI</option>
                <option value="2"  <%=checkNO%> >NO</option>
            </select>
        </td>
        <td><input type="text"   class="form-control input-sm text-right" name="tasacambiocomprac[]" value="<%=i.tasacambiocompra%>">   </td>
        <td><input type="text"   class="form-control input-sm text-right" name="tcVentaRefc[]" value="<%=i.tasacambioventa%>">   </td>
        <td><input type="text"   class="form-control input-sm text-right" name="cantComprac[]" value="<%=i.montocompra%>">   </td>
        <td><div class="contDataDetPago">

                <%  var _ttx=0;
                    var dtPagoxxx= JSON.parse(i.detallepagox);
                     _.each(dtPagoxxx,function(ii,kk){
                _ttx+=parseFloat(Number(ii.monto));
                %>

                    <p style='margin-bottom: 1px;' ><b>Cuenta:</b><%=ii.moneda+" "+ii.banco+" "+ii.cuentabanco%> <b>NroOp:</b><%=ii.nroop%>   <b>Monto:S/</b> <%=ii.monto%>  </p>
                    <input type='hidden' name='idcuentabancoreg[]' value='<%=ii.idcuentabanco%>' >
                    <input type='hidden' name='nopreg[]' value='<%=ii.nroop%>'>
                    <input type='hidden' name='montopagoreg[]' value='<%=ii.monto%>'>
                   <input type='hidden' name='iddetpagocomprad[]' value='<%=ii.iddetpagocomprad%>'>
                <%   });      %>
                <p style='margin-bottom: 4px;' ><b>TOTAL S/:<%=_ttx%> <input type='hidden' name='totalx_detPagotr' value='<%=_ttx%>'> </b></p>


            </div>
        </td>
        <td><input type="text" readonly="readonly" class="form-control input-sm text-right " name="ssubtotalc[]" value="<%= parseFloat(Number(i.montocompra) * Number(i.tasacambiocompra)).toFixed(2) %>"> </td>
        <td> <button class="btn btn-danger btn-xs deletetr" type="button" ><i class="fa fa-trash"></i></button>  </td>
    </tr>
    <% })%>
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


                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Fecha</label>
                                    <input type="date" id="fecha" name="fecha" value="<?=date("Y-m-d")?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Nota:</label>
                                    <input type="text" id="nota" name="nota" value="" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-2" style="visibility: hidden">

                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label text-2x">Stock S/:<b id="StockSolesReg"></b></label>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="visibility: hidden;" >
                            <div class="col-sm-6">
                                <div class="form-group">

                                    <input type="hidden" id="wallet" name="wallet" class="form-control">
                                    <input type="hidden" id="iddetcompra"  value="0" class="form-control text-right ">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group" style="margin-bottom: 0px;"  >
                                    <label class="control-label" title="Precio Compra">Moneda C.</label>
                                    <select style="padding-right: 1px;padding-left: 1px;" name="monedacompra" id="monedacompra"  class="form-control">
                                        <?php foreach($monedas as $i){ ?>
                                            <option value="<?=$i["idmoneda"]?>"><?=$i["moneda"]?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="control-label " style="font-size: 10px">T Cambio C.</label>
                                    <input type="number" id="tasacambiocompra" name="tasacambiocompra" class="form-control text-right">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group" style="margin-bottom: 0px;" >
                                    <label class="control-label" title="Tasa de cambio referencial para venta">TCRV</label>
                                    <input  type="number" id="tVentaRef" name="tVentaRef"  class="form-control text-right ">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="control-label">Cant Compra</label>
                                    <input type="number" id="cantCompra" name="cantCompra" class="form-control text-right ">
                                </div>
                            </div>

                            <input type="hidden" id="isVisibleVenta" name="isVisibleVenta" value="1">
                            <input type="hidden"  id="isRegEgreso" name="isRegEgreso" value="1">

                            <!--<div class="col-sm-1">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="control-label" style="text-align: center;font-size: 10px;">Visible para venta</label>
                                    <select id="isVisibleVenta" class="form-control input-sm" >
                                        <option value="1">SI</option>
                                        <option value="2" selected="selected">NO</option>
                                    </select>
                                </div>
                            </div>-->
                            <!--<div class="col-sm-5">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="control-label">Detalle de pago <button type="button" id="btnAddDetailPagoBanco" class="btn btn-xs btn-dark">+</button></label>
                                    <div id="tablaDetailPagoBanco">
                                        <table id="tablaDetailPagoBanco_" class="table table-condensed table-hover table-bordered">
                                            <thead>
                                            <tr style="font-size: 10px;">
                                                <th>Cuenta/Nop</th>
                                                <th>Monto</th>
                                                <th>*</th>
                                            </tr>
                                            </thead>
                                            <tbody id="tbodyDetailPagoBanco">

                                            </tbody>
                                            <tfoot  >
                                            <tr style="font-size: 10px;">

                                                <th  style="text-align: right;" >Total</th>
                                                <th><input id="totalPagoBanco" type="text" class="form-control input-sm text-right" readonly="readonly" > </th>
                                                <th>*</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                </div>
                            </div>-->


                            <!--<div class="col-sm-1">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="control-label" style="text-align: center;font-size: 10px;">Registra Egreso?(S/)</label>
                                    <select id="isRegEgreso" class="form-control input-sm" >
                                        <option value="1">SI</option>
                                        <option value="2" selected="selected">NO</option>
                                    </select>
                                </div>
                            </div>-->
                            <div class="col-sm-2">
                                <div class="form-group" style="margin-bottom: 0px;" >
                                    <label class="control-label">Por Pagar(S/)</label>
                                    <input readonly="readonly" type="text" id="xPagar" name="xPagar"  class="form-control text-right ">
                                </div>
                            </div>

                            <!--<div class="col-sm-1">
                                <div class="form-group" style="margin-bottom: 0px;" >
                                    <label class="control-label" style="visibility: hidden">________________________</label>
                                    <button type="button" id="btnAddInDetail" class="btn btn-sm btn-warning"><i class="fa fa-arrow-down"></i></button>
                                </div>
                            </div>
                            -->
                        </div>
                        <!--
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    DETALLE
                                    <table class="table table-bordered table-hover table-condensed">
                                        <thead>
                                         <tr>
                                             <th>Moneda</th>
                                             <th>Para Venta</th>
                                             <th>Tasa Cambio</th>
                                             <th title="tasa de cambio referencial pa venta">TCRV</th>
                                             <th>Cant Compra</th>
                                             <th>Det Pago</th>
                                             <th>Por Pagar</th>
                                             <th  >*</th>
                                         </tr>
                                        </thead>
                                        <tbody id="tBodyDetailVenta">

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align: right"> Total </th>
                                            <th><input style="font-size: 17px;" class="form-control input-sm text-right" id="subTotalDolares_" type="text" readonly="readonly" > </th>

                                            <th><input class="form-control input-sm text-right" id="subTotalDetPago_" type="text" readonly="readonly" > </th>

                                            <th><input class="form-control input-sm text-right " id="subTotal" type="text" readonly="readonly" > </th>
                                            <th ></th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div> -->

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="button" id="btnSaveForm">Guardar</button>
                        <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                    </div>

                    <div class="col-sm-6" style="visibility: hidden;">
                        <div class="form-group">
                            <label class="control-label">Proveedor</label><br>
                            <input type="hidden" name="proveedor" id="proveedor" value="99999">
                            <button  style="display:inline-block;" id="btnAddNewProveedor" type="button" class="btn btn-sm btn-mint"  ><i class="fa fa-plus"></i></button>
                        </div>
                    </div>

                </form>
                <!--===================================================-->
                <!--End Block Styled Form -->

            </div>
        </div>
    </div>


</script>





<div class="modal fade" id="modalId" role="dialog" tabindex="-1"  data-backdrop="static"   aria-hidden="true" >
    <div class="modal-dialog modal-lg" style="width: 85%;">
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