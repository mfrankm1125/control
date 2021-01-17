<div id="page-content">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel">
                <div class="panel-heading">

                    <div class="panel-control">
                        <button class="demo-panel-ref-btn btn btn-default" data-toggle="panel-overlay" onclick="reporte();"  ><i class="fa fa-bar-chart"></i> Reporte </button>

                        <button class="demo-panel-ref-btn btn btn-default" data-toggle="panel-overlay" onclick="refrescar();"  ><i class="demo-psi-repeat-2"></i></button>
                    </div>
                    <h3 class="panel-title"  ><b style="font-size: 18px;">Venta Dolares</b>  &nbsp; <button class="btn btn-mint btn-sm" id="btnAddNew">Nuevo+</button> </h3>
                    <hr style="margin-bottom: 0px;margin-top: 0px;">
                </div>

                <!--Data Table-->

                <!--===================================================-->
                <div class="panel-body">
                    <div class="row" >
                        <div class="col-lg-12">
                            <div class="col-sm-4 pad-top">
                                <div class="text-lg">
                                    <p class="text-2x text-thin text-main"><b style="font-size: 9px;">Stock: </b>$<b id="bglobalStock">...</b></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">


                        <table id="dtTable" class="table table-bordered table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>#</th>

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

    $(document).ready(function() {
        dtIni();
        getStockTotal();
    });

    function dtIni(){
        dtTable=$('#dtTable').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url(); ?>ventadolares/getData",
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
                        var id = full.idventad;
                        var dd=JSON.parse(full.detventaf);

                        let ttcccx=0;
                        let ttcpagx=0;
                        $.each(dd,function(kk,ii){
                            ttcccx=ttcccx+parseFloat(Number(ii.montoventa));
                            let sbcc=parseFloat(Number(ii.montoventa))*parseFloat(Number(ii.tasacambioventa));
                            ttcpagx=ttcpagx+sbcc;
                            ht+="<p>$ "+ii.montoventa+ " |TCV :S/"+ii.tasacambioventa+"| Pagado:S/"+sbcc.toFixed(2)+" </p>";
                        });
                        ht+="<b> Total $"+ttcccx+" => S/"+ttcpagx+"</b>";

                        return ht;
                    }
                },
                { "data": "fechaventa" },
                {   "sortable": false,
                    "searchable":false,
                    "render": function ( data, type, full, meta ) {
                        var id = full.idventad;
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
        $("#hModalTitle").html("Registrar Venta");
        var tmpFormCompra=_.template($("#tmpFormCompra").html());
        $("#bModalBody").html(tmpFormCompra);

        getProductosSearch(0,0,0);
        //getClientes(0);

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

        var tVentaRef=$("#tVentaRef");
        var cantCompra=$("#cantCompra");

        if(ntr == 0){
            alert_error("Ingrese el detalle de Venta");
            return 0;
        }
        var detV=[];
        for(var i=0;i<ntr ;i++){
            //let idMonedac=$(tr_[i]).find("input[name='idMonedac[]']").val();


            let iddetventad=$(tr_[i]).find("input[name='iddetventad[]']").val();
            let iddetproducto=$(tr_[i]).find("input[name='iddetproducto[]']").val();

           // let tasacambiocomprac=$(tr_[i]).find("input[name='tasacambiocomprac[]']").val();
            let tcVentaRefc=$(tr_[i]).find("input[name='tcVentaRefc[]']").val();
            let cantComprac=$(tr_[i]).find("input[name='cantComprac[]']").val();

            let contDataDetPago=$(tr_[i]).find(".contDataDetPago");

            let idcuentabancoreg=$(contDataDetPago).find("input[name='idcuentabancoreg[]']");
            let iddetpagocomprad=$(contDataDetPago).find("input[name='iddetpagocomprad[]']");
            let nopreg=$(contDataDetPago).find("input[name='nopreg[]']");
            let montopagoreg=$(contDataDetPago).find("input[name='montopagoreg[]']");
            var detPago=[];
           /* for( var j=0;j<idcuentabancoreg.length ;j++){
                let idcuentabanco= $(idcuentabancoreg[j]).val();
                let nopxx=$(nopreg[j]).val();
                let montoregxx=$(montopagoreg[j]).val();
                let _iddetpagocomprad=$(iddetpagocomprad[j]).val();
                detPago.push({
                    idcuentabanco:idcuentabanco,
                    nroop:nopxx,
                    monto:  montoregxx,
                    iddetpagoventad:_iddetpagocomprad
                });

            } */
            detV.push({
                //idmoneda:idMonedac ,
                iddetventa:iddetventad,
                iddetproducto:iddetproducto,
               // tcambio:tasacambiocomprac ,
                tcambioventa:tcVentaRefc ,
                cantcompra:cantComprac,
                detPago:detPago
            });

        }
        dataForm.push({
                idcliente:$("#selCliente").val(),
                fecha:$("#fecha").val(),
                detalleventa:detV,
                isEdit:$("#isEdit").val(),
                idEdit:$("#idEdit").val()
            }
        );

        //console.log(dataForm);
       /* var detV=[];

        detV.push({
            //idmoneda:idMonedac ,
            iddetventa:$("#iddetventad").val(),
            iddetproducto:$("#selProducto").val(),
            // tcambio:tasacambiocomprac ,
            tcambioventa:$("#tVentaRef").val() ,
            cantcompra:$("#cantCompra").val(),
            detPago:[]
        });
        dataForm.push({
                idcliente:$("#selCliente").val(),
                fecha:$("#fecha").val(),
                detalleventa:detV,
                isEdit:$("#isEdit").val(),
                idEdit:$("#idEdit").val()
            });  */

        var btnSaveForm=$("#btnSaveForm");
        btnSaveForm.button("loading");
        $.post(url_base+"ventadolares/setForm",{dataForm},function (data) {
            if(data.status == "ok"){

                alert_success("Correcto");
                if($("#isEdit").val() == 0){
                    var tmpFormCompra=_.template($("#tmpFormCompra").html());
                    $("#bModalBody").html(tmpFormCompra);
                   // getClientes();
                    getProductosSearch(0,0,0);
                }else{
                    $("#modalId").modal("hide");
                }
                refrescar();
                getStockTotal();
            }else{
                alert_success("Fallo");
            }
            btnSaveForm.button("reset");
        },'json');
    });

    function eliminar(id) {
        if(!confirm("¿Seguro de eliminar este registro?")){
            return 0;
        }
        $.post(url_base+"ventadolares/delete",{"id":id},function (data) {
            if(data.status == "ok"){

                alert_success("Correcto");
                getStockTotal();
            }else{
                alert_success("Fallo");
            }
            refrescar();
        },'json');
    }

    function editar(id) {
        $("#modalId").modal("show");
        $("#hModalTitle").html("Editar Compra Dolares");
        $("#bModalBody").html("Cargando...");
        $.post(url_base+"ventadolares/getDataEdit",{id:id},function (data) {
            if(data.status=="ok" && data.data.length > 0){
                var dtEdit=data.data[0];
                var tmpFormCompra=_.template($("#tmpFormCompra").html());
                $("#bModalBody").html(tmpFormCompra);
                $("#isEdit").val(1);
                $("#idEdit").val(id);

               // $("#wallet").val(dtEdit.wallet);
               // getClientes(dtEdit.idcliente);

                //getBanco();
                var mydate = new Date(dtEdit.fechaventa);
                var dia=mydate.getDate();
                var mes=(mydate.getMonth()+1);
                var anio=mydate.getFullYear();
                if (mes < 10) mes = '0' + mes;
                if (dia < 10) dia = '0' + dia;
                var ffxd=anio+"-"+mes+"-"+dia;
                var detalleventa=dtEdit.detalleventa;
                getProductosSearch(0,0,0);
                /*getProductosSearch(detalleventa[0].iddetproductod,detalleventa[0].montoventa,detalleventa[0].tasacambiocompra);
                 $("#tVentaRef").val(detalleventa[0].tasacambioventa);
                $("#cantCompra").val(detalleventa[0].montoventa);
                $("#tasacambiocompra").val(detalleventa[0].tasacambiocompra);
                $("#iddetventad").val(detalleventa[0].iddetventad); */

                calcSubTotalPagar();
               // $("#stock").val(detalleventa[0].montoventa);
               var tmpAddDetaillEdit=_.template($("#tmpAddDetaillEdit").html());
                $("#tBodyDetailVenta").html(tmpAddDetaillEdit({data:detalleventa}));
                $("#fecha").val(ffxd);

                //calSubtotalTotalsDetPago();
                calSubtotalTotals()
            }else{
                alert_error("Fallo");
            }
        },'json');
    }

    $(document).on("click","#btnAddNewProveedor",function () {
        $("#modalIdNewForm").modal("show");
        var tmpFormCliente=_.template($("#tmpFormCliente").html());
        $("#bModalBodyNewForm").html(tmpFormCliente);
    });

    $(document).on("click","#btnSaveFormCliente",function () {
        var btn=$(this);
        var form=$("#formRegDataCliente").serialize();
        btn.button("loading");
        $.post(url_base+"ventadolares/setFormCliente",form,function(data) {

            if(data.status == "ok"){

                alert_success("Correcto");
                $("#modalIdNewForm").modal("hide");
                getClientes(data.data[0].idcliente);

                /*if(data.data.length > 0){
                    let $option = $('<option />', {
                        text: data.data[0].nombre+" "+data.data[0].apellidos+"|"+data.data[0].razonsocial+" RUC:"+data.data[0].ruc,
                        value: data.data[0].idcliente
                    });
                    $('#cliente').prepend($option);
                    $("#cliente").val(data.data[0].idcliente);


                }else{

                }*/

            }else{
                alert_success("Fallo");
            }
            btn.button("reset");

        },'json');
    });



    $(document).on("click","#btnAddInDetail",function () {

        let selProducto=$("#selProducto");
        let selProductoText=$("#selProducto option:selected").attr("labeltxt");

        let  selProductoIdProveedor=$("#selProducto option:selected").attr("idprov");
        let selProductoProveedor=$("#selProducto option:selected").attr("prov");

        let monedacompra=$('#monedacompra option:selected').text();
        let idmoneda=$('#monedacompra').val();
        let tasacambiocompra=$("#tasacambiocompra");
        let cantCompra=$("#cantCompra");
        let xPagar=$("#xPagar");
        let tVentaRef=$("#tVentaRef");

        var iddetproducto_=$("input[name='iddetproducto[]']");
        console.log(iddetproducto_.length);
        var bolxxxxas=true;
        $.each(iddetproducto_,function(kkk,iii){
            if(selProducto.val() == $(iii).val() ){
                bolxxxxas=false;
            }


        });
        if(!bolxxxxas){
            alert_error("El lote de este stock ya se encuentra en el detalle");
            return 0;
        };

        if(selProducto.val()==0){
            alert_error("Ingrese stock moneda");
            return 0;
        }
        if( parseFloat(Number(cantCompra.val())) > parseFloat(Number($("#stock").val())) ){
            alert_error("Stock insuficiente");
            return 0;
        }else if(parseFloat(Number(cantCompra.val())) == 0){
            alert_error("Ingrese cantidad de moneda a comprar");
            return 0;
        }
        let tbodyDetailPagoBanco=$("#tbodyDetailPagoBanco");
        let tmpAddDetaill=_.template($("#tmpAddDetaill").html());
        /*
        let tablaDetailPagoBanco_=$("#tablaDetailPagoBanco_ >tbody >tr").length;
        if(tablaDetailPagoBanco_ == 0){
            alert_error("Agregue el detalle de pago por favor!!");
            return 0;
        } */

       /* let selBancoCuenta=$("select[name='selBancoCuenta']");
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

        detpagoHT+="<p style='margin-bottom: 4px;' ><b>TOTAL S/:"+totalx_+"<input type='hidden' name='totalx_detPagotr' value='"+totalx_+"'> </b></p>"; */

        $("#tBodyDetailVenta").append(tmpAddDetaill({prov:selProductoProveedor,idprov:selProductoIdProveedor,selProducto:selProducto.val(),selProductoText:selProductoText,tablaDetailPagoBanco:"",tVentaRef:tVentaRef.val(),idmoneda:idmoneda,monedacompra:monedacompra,tasacambiocompra:tasacambiocompra.val(),cantCompra:cantCompra.val(),xPagar:xPagar.val()}));


        tasacambiocompra.val("");
        cantCompra.val("") ;
        xPagar.val("") ;
        tVentaRef.val("") ;
        calSubtotalTotals();
        tbodyDetailPagoBanco.html("");
        getBanco();
        $('#selProducto').val(0);
        $('#selProducto').trigger("chosen:updated");
        $("#stock").val(0)
        $("totalPagoBanco").val(0);
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
        var ttDolares=$("input[name='cantComprac[]']");
        var stt=0;
        var sttx=0;
        var sttx2=0;
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
        let stock=parseFloat(Number($("#stock").val()));
        let valx=parseFloat(Number($(this).val()));
        console.log(stock,valx);
        if(stock >= valx){

        }else{
            alert_error("Stock insuficiente");
            $("#cantCompra").val("");
            return 0;
        }
        calcSubTotalPagar();
    });
    $(document).on("keyup","#tVentaRef",function () {
        calcSubTotalPagar();
    });

    function calcSubTotalPagar() {
        let tVentaRef=$("#tVentaRef");
        let cantCompra=$("#cantCompra");
        let xPagar=$("#xPagar");
        // let tVentaRef=$("#tVentaRef");
        xPagar.val((Number(tVentaRef.val())*Number(cantCompra.val())).toFixed(2));
    }

    $(document).on("click","#btnAddDetailPagoBanco",function () {
        getBanco();
    });


    function getBanco() {
        var tmpSelCuentaBancoPago=_.template($("#tmpSelCuentaBancoPago").html());
        $.post(url_base+"ventadolares/getBancoByMoneda",function (data) {
            $("#tbodyDetailPagoBanco").append(tmpSelCuentaBancoPago({data:data}));
        },'json');
    }

    function getClientes(id) {
        var tmpSelClientes=_.template($("#tmpSelClientes").html());
        $.post(url_base+"ventadolares/getClientes",function (data) {
            $("#divSelCliente").html(tmpSelClientes({data:data,id:id}));
            $('#selCliente').chosen({width:'100%'});
        },'json');
    }

    function getProductosSearch(idedit,stockedit,tc) {
        var tmpSelProducto=_.template($("#tmpSelProducto").html());
        $.post(url_base+"ventadolares/getProductos",function (data) {
            $("#divSelProductos").html(tmpSelProducto({data:data,idedit:idedit,stockedit:stockedit,tc:tc}));
            $('#selProducto').chosen({width:'100%'});
            if(idedit == 0){
                let tcc=$("#selProducto option:selected").attr("tcc");
                let tcv=$("#selProducto option:selected").attr("tcv");
                let stock=$("#selProducto option:selected").attr("stock");
                $("#tasacambiocompra").val(tcc);
                $("#tVentaRef").val(tcv);
                $("#stock").val(stock);

            }

        },'json');
    }

    $(document).on("change","#selProducto",function () {
        if($(this).val()==0){
            $("#tasacambiocompra").val("");
            $("#tVentaRef").val("");
            $("#stock").val("");
            return 0;
        }
        let tcc=$("#selProducto option:selected").attr("tcc");
        let tcv=$("#selProducto option:selected").attr("tcv");
        let stock=$("#selProducto option:selected").attr("stock");
        $("#tasacambiocompra").val(tcc);
        $("#tVentaRef").val(tcv);
        $("#stock").val(stock);

        //console.log(tcc);
    });

    $(document).on("keyup","input[name='tcVentaRefc[]']",function () {
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
        var pcomprac=tr.find("input[name='tcVentaRefc[]']").val();
        var sstotalxx=tr.find("input[name='ssubtotalc[]']");
        var trsub=parseFloat(vl)*parseFloat(pcomprac);
        sstotalxx.val(trsub);
        calSubtotalTotals();
    });

   function deleteDetVentad(ctx_,id) {
       var ctx=$(ctx_);
       var tr=ctx.closest("tr").remove();
       calSubtotalTotals();
       var subTotalDolares_=$("#subTotalDolares_").val();
       var subTotal=$("#subTotal").val();
       var idEdit=$("#idEdit").val();
       $.post(url_base+"ventadolares/deleteTRdetail",{iddetail:id,subdolares:subTotalDolares_,subTotal:subTotal,idEdit:idEdit},function (data) {
            if(data.status == "ok"){
                getProductosSearch(0,0,0);
            }
       },'json');

   }
</script>

<script type="text/template" id="tmpSelClientes">
    <select  style="padding-right:0px;padding-left:0px" class="form-control input-sm" name="selCliente" id="selCliente" >
        <% _.each(data,function(i,k){
        var tt="";
            if(id==i.idcliente){
        tt="selected='selected'";
            }
        %>
        <option value="<%=i.idcliente%>"  <%=tt%> ><%=i.nombre +" "+i.apellidos+" RUC:"+i.documento %> </option>
        <%  });%>
    </select>
</script>

<script type="text/template" id="tmpSelProducto">
    <select  style="padding-right:0px;padding-left:0px" class="form-control input-sm" name="selProducto" id="selProducto" >
        <% var existSel=0;
          _.each(data,function(i,k){
              let selxx__1="";
              let stockAdd=0;
              let label_1=""
             if(idedit !=0){
                if(i.iddetcomprad== idedit){
                  selxx__1="selected='selected'";
                  existSel++;
                  stockAdd=stockedit;
                 $("#stock").val(( parseFloat(Number(i.stock)) + parseFloat(Number(stockAdd))));

                  label_1=i.stock  +" + "+   stockAdd +"="+( parseFloat(Number(i.stock)) + parseFloat(Number(stockAdd)));
                 }else{
                 selxx__1="disabled";
                  label_1=i.stock;
                 }
            }else{
               label_1=i.stock;
            }


        %>
        <option  <%=selxx__1%> prov="<%=i.razonsocial+"-"+i.nombre%>" idprov="<%=i.idproveedor%>" labeltxt="<%=i.nmoneda%>" tcv="<%=i.tasacambioventa%>" stock="<%=( parseFloat(Number(i.stock)) + parseFloat(Number(stockAdd)))%>" tcc="<%=i.tasacambiocompra%>"
         value="<%=i.iddetcomprad%>"><%=i.abreviado+"  "+label_1+" | TcC:S/ "+i.tasacambiocompra+" |TcV:S/ "+i.tasacambioventa %>     </option>
        <%  });
        if(idedit !=0){
            if(existSel == 0){  %>
            <option selected="selected"      tcv="-" stock="<%=stockedit%>" tcc="" value="<%=idedit%>" >$<%=stockedit%> 
                     </option>


            <%  }
        } %>
    </select>
    <%  %>
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
    <tr class="dataTr">
        <td>
            <input readonly="readonly" class="form-control input-sm "  type="text" name="provx[]" value="<%=prov%>">
            <input name="idprovprod" type="hidden" value="<%=idprov+'-'+selProducto%>">
        </td>
        <td>
            <input type="hidden" name="iddetventad[]" value="0">
            <input type="hidden" name="iddetproducto[]" value="<%=selProducto%>">

            <%=selProductoText%>
        </td>
       <!-- <td><input type="text" readonly="readonly" class="form-control input-sm text-right" name="tasacambiocomprac[]" value="<%=tasacambiocompra%>">   </td>
       -->
        <td><input type="text"   class="form-control input-sm text-right" name="tcVentaRefc[]" value="<%=tVentaRef%>">   </td>
        <td><input type="text"   class="form-control input-sm text-right" name="cantComprac[]" value="<%=cantCompra%>">   </td>
        <!--<td><div class="contDataDetPago">
                <%=tablaDetailPagoBanco%>
            </div>
        </td>-->
        <td><input type="text" readonly="readonly" class="form-control input-sm text-right " name="ssubtotalc[]" value="<%=xPagar%>"> </td>
        <td> <button class="btn btn-danger btn-xs deletetr" type="button" ><i class="fa fa-trash"></i></button>  </td>
    </tr>
</script>


<script type="text/template" id="tmpAddDetaillEdit">

    <% console.log(data); _.each(data,function(i,k){%>
    <tr class="dataTr">
        <td>

            <input readonly="readonly" class="form-control input-sm "  type="text" name="provx[]" value="<%= i.nombre+'-'+i.razonsocial %>">
            <input name="idprovprod" type="hidden" value="<%=i.idproveedor+'-'+i.iddetproductod%>">
        </td>

        <td>
            <input type="hidden" name="iddetventad[]" value="<%=i.iddetventad%>">
            <input type="hidden" name="iddetproducto[]" value="<%=i.iddetproductod%>">
           <%=i.detmoneda%>
        </td>

        <td><input type="text"   class="form-control input-sm text-right" name="tcVentaRefc[]" value="<%=i.tasacambioventa%>">   </td>
        <td><input type="text"   class="form-control input-sm text-right" name="cantComprac[]" value="<%=i.montoventa%>">   </td>

        <td><input type="text" readonly="readonly" class="form-control input-sm text-right " name="ssubtotalc[]" value="<%= parseFloat(Number(i.montoventa) * Number(i.tasacambioventa)).toFixed(2) %>"> </td>
        <td> <button class="btn btn-danger btn-xs " onclick="deleteDetVentad(this,'<%=i.iddetventad%>')" type="button" ><i class="fa fa-trash"></i></button>  </td>
    </tr>
    <% })%>
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
                            <label class="col-sm-3 control-label" for="demo-hor-inputpass">DNI</label>
                            <div class="col-sm-6">
                                <input type="text"   id="documento" name="documento" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="ncuenta">Razon Social</label>
                            <div class="col-sm-6">
                                <input type="text" name="razsocial"  id="razsocial" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="ncuenta">Direccion</label>
                            <div class="col-sm-6">
                                <input type="text" name="direccion"  id="direccion" class="form-control" >
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


<script type="text/template" id="tmpFormCompra">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel">

                <form id="formRegData">
                    <input type="hidden" id="iddetventad" name="iddetventad" value="0">
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
                            <div class="col-sm-3" style="display: none">
                                <div class="form-group">
                                    <label class="control-label">Nota:</label>
                                    <input type="text" id="nota" name="nota" value="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="visibility: hidden;" >
                            <div class="col-sm-6">
                                <div class="form-group">

                                    <input type="hidden" id="wallet" name="wallet" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group" style="margin-bottom: 0px;"  >
                                    <label class="control-label" title="Precio Compra">Stock Monedas</label>
                                    <div id="divSelProductos">

                                    </div>
                                    <input type="hidden" id="stock" name="stock" value="0">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="control-label " style="font-size: 10px">T Cambio C.</label>
                                    <input readonly="readonly" type="text" id="tasacambiocompra" name="tasacambiocompra" class="form-control text-right">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group" style="margin-bottom: 0px;" >
                                    <label class="control-label" title="Tasa de cambio referencial para venta">TCRV</label>
                                    <input  type="text" id="tVentaRef" name="tVentaRef"  class="form-control text-right ">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label class="control-label">Cant Venta</label>
                                    <input type="text" id="cantCompra" name="cantCompra" class="form-control text-right ">
                                </div>
                            </div>
                            <!--<div class="col-sm-4">
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
                            <div class="col-sm-1">
                                <div class="form-group" style="margin-bottom: 0px;" >
                                    <label class="control-label">SubTotal(S/)</label>
                                    <input readonly="readonly" type="text" id="xPagar" name="xPagar"  class="form-control text-right ">
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group" style="margin-bottom: 0px;" >
                                    <label class="control-label" style="visibility: hidden">________________________</label>
                                    <button type="button" id="btnAddInDetail" class="btn btn-sm btn-warning"><i class="fa fa-arrow-down"></i></button>
                                </div>
                            </div> 
                        </div>
                         <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">

                                    DETALLE
                                    <table class="table table-bordered table-hover table-condensed">
                                        <thead>
                                        <tr>
                                            <th>Proveedor</th>
                                            <th>Moneda/Cant</th>
                                             <!--<th>Tasa Cambio</th> -->
                                            <th title="tasa de cambio pa venta">Tasa Cambio V</th>
                                            <th>Cant vender</th>
                                            <!--<th>Det Pago</th>-->
                                            <th>SubTotal(S/)</th>
                                            <th  >*</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tBodyDetailVenta">

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="3" style="text-align: right"> Total </th>
                                            <th><input style="font-size: 17px;" class="form-control input-sm text-right" id="subTotalDolares_" type="text" readonly="readonly" > </th>
                                            

                                           <!-- <th><input class="form-control input-sm text-right" id="subTotalDetPago_" type="text" readonly="readonly" > </th>-->

                                            <th><input class="form-control input-sm text-right " id="subTotal" type="text" readonly="readonly" > </th>
                                            <th ></th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div> 

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="button" id="btnSaveForm">Guardar</button>
                        <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                    </div>

                    <div class="col-sm-6" style="visibility: hidden">
                        <div class="form-group">
                            <label class="control-label">Cliente</label><br>
                            <input type="hidden"  value="999999" name="selCliente" id="selCliente">
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
    <div class="modal-dialog modal-lg" style="width: 80%;">
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
                <h4 class="modal-title" id="">Registrar Cliente</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="bModalBodyNewForm">


            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    function reporte() {
        $("#modalReport").modal("show");
    }

    function getStockTotal() {
        $.post(url_base+"ventadolares/getStockTotal",function (data) {
            $("#bglobalStock").html(data.stockTotal);
        },'json');
    }

    $(document).on("click","#btnGenerarDtVenta",function () {
        var fechaini=$("#dtfechaventaini").val();
        var fechaend=$("#dtfechaventaend").val();
        var btn=$(this);
        btn.button("loading");
        $("#divResultReport").html("Cargando...");
        $.post(url_base+"ventadolares/getVentaR",{fini:fechaini,fend:fechaend},function (data) {
            if(data.status == "ok"){
                var tmpTableVentaR=_.template($("#tmpTableVentaR").html());
                $("#divResultReport").html(tmpTableVentaR({data:data.data}));
                $("#tableReporteVentas").DataTable();
            }else{
                alert_error("Fail");
            }
            btn.button("reset");
        },'json');
    });
</script>


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
                        <!--<button id="btnGenerarDtVentaXBanco" type="button" class="btn btn-mint btn-sm">Reporte de ventas x Banco</button>
                        <button id="btnGenerarDtVentaXCliente" type="button" class="btn btn-mint btn-sm">Reporte de ventas x Cliente</button>
                        <button id="btnGenerarDtVentaXProveedor" type="button" class="btn btn-mint btn-sm">Reporte de ventas x Proveedor</button>
-->
                    </div>
                    <div class="row" id="divResultReport">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/template" id="tmpTableVentaR">
    <div class="table-responsive">
        <br>
        <table class="table table-striped table-hover table-condensed" id="tableReporteVentas">
            <thead>
            <tr>
                <th>Fecha</th>
                <!--<th>Cliente</th>-->
                <th>Det Venta</th>

                <th>*</th>
            </tr>
            </thead>
            <tbody>
            <% var totalMontoF=0;
                var totalG=0;
            _.each(data,function(i,k){ %>
            <tr>
                <td><%= i.fechaventa %> </td>
               <!-- <td> <%= i.nombre+" "+i.documento %> </td> -->
                <td>
                    <% var totalMontoDet=0;
                       var totalGDet=0;
                     _.each(i.detventa,function(ii,kk){
                       totalMontoDet=totalMontoDet+parseFloat(Number(ii.montoventa));
                      totalGDet=totalGDet+parseFloat(Number(ii.ganancia));
                    %>
                      <p><%=ii.abreviado +" "+ii.montoventa +"| TCV:S/ "+ii.tasacambioventa+" | TCC:S/"+ii.tasacambiocompra+" Monto:S/"+ Number(ii.ganancia).toFixed(2) %></p>
                    <% });
                    totalMontoF=totalMontoF+totalMontoDet;
                    totalG=totalG+totalGDet
                    %>
                    <b>Total:$<%=Number(totalMontoDet).toFixed(2) %> => Monto:S/<%= Number(totalGDet).toFixed(2)%> </b>
                </td>

                <td>*</td>
            </tr>
            <% }); %>
            </tbody>
            <tfoot>
                <tr >
                    <th></th>
                   <!-- <th></th>-->
                    <th style="font-size: 17px;">Total$<%=totalMontoF%> --Monto S/<%= Number(totalG).toFixed(2) %></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</script>