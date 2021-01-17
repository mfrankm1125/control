<style>
    ul.ui-autocomplete {
        z-index: 2100;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Caja</h3>
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
                        <th title="Fecha Registro">F. Reg</th>
                        <th>Nro Doc</th>
                        <th>-</th>
                        <th>Cliente</th>
                        <th style="text-align: right">Monto(S/)</th>
                        <th>Acción</th>

                    </tr>
                    </thead>
                    <tbody id="tabla_body" style="">
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
    <div class="modal-dialog modal-lg" style="width: 80%;" >
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divModalBody">



            </div>

        </div>
    </div>
</div>


<link href="<?=base_url()?>assets/scripts/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<?php echo $_css?>
<?php echo $_js?>
<script src="<?=base_url()?>assets/scripts/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
    var titles={'addtitle':"Registrar Ingreso/Egreso" ,'updatetitle':"Editar  Ingreso/Egreso"};
    var isEdit=null,idEdit=null;

    var rowSelection=null;
    var fechaActual="<?=date('Y-m-d')?>";

    $(document).ready(function () {
        dataTableIni();

    });

    function dataTableIni() {
        rowSelection = $('#tabla_grid').DataTable({
            "ajax": url_base+'kardex/getDataTable',
            "columns": [
                { "data": null },
                {  "render": function ( data, type, full, meta ) {
                        var f = full.fechareg;
                        //var lastname = full.apellidos;
                        var html="<input  readonly='readonly' type='date' value='"+f+"' style='border:0' >";
                        return html;
                    }
                },
                { "data": "nrodoc" },
                { "data": "conceptoflujo" },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.nombre;
                        //var lastname = full.apellidos;
                        var html="<span  class='col-lg-12' style='text-align: left'>"+name+"</span>";
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var t = full.totaldet;
                        //var lastname = full.apellidos;
                        var html="<b  class='col-lg-12' style='text-align: right'>"+t+"</b>";
                        return html;
                    }
                },
                {  sortable: false,
                    "render": function ( data, type, full, meta ) {
                        var id = full.idkardex;
                        html="";
                        //html='<a href="javascript:void(0)"  onclick="ver('+id+');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>';
                        //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                        html=html+'&nbsp; <a href="javascript:void(0)" onclick="ver('+id +');"  class="btn btn-mint btn-icon btn-xs"><i class="fa fa-eye"></i> Ver</a>';
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
            }

        });

        $('#tabla_grid').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                rowSelection.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        } );

        rowSelection.on( 'order.dt search.dt', function () {
            rowSelection.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();


    }
    function refrescar () {
        var rowSelection = $('#tabla_grid').DataTable();
        rowSelection.ajax.reload();
    }

    $(document).on('click','#btnAdd',function () {
        $("#isEdit").val(0);
        $("#idEdit").val(0);
        var tmpDivModalBody=_.template($("#tmpDivModalBody").html());
        $("#divModalBody").html(tmpDivModalBody);

        //$("#formActop")[0].reset();
        $('#modalTitle').html(titles['addtitle']);
         open_modal('modal_id');
          //iniAutoCompleteCliente();
        iniAutoCompleteDistrito();


    });
    function iniAutoCompleteCliente() {
        $("#cliente").autocomplete({
            source: "<?= base_url(); ?>kardex/listClientes",
            minLength: 1,
            delay: 200,
            select: function( event, ui ) {
                console.log(ui.item);
                $("#dircli").val(ui.item.direccion);
                $("#telcli").val(ui.item.telefono);
                $("#idcliente").val(ui.item.idcliente);
                /*$("#dircliente").val(ui.item.direccion);
                $("#telcli").val(ui.item.telefono);
                $("#celcli").val(ui.item.celular);
                $("#nit").val(ui.item.nit); */
                //$("#contacto").val(ui.item.contacto);
                // $("#telefono2").val(ui.item.telcontacto);
                //$("#celular2").val(ui.item.celcontacto);
                //$('#cliente').attr('readonly', "readonly");
            }
        });
    }
    function iniSelectConcepto() {
        var tmpSelConceptoCaja=_.template($("#tmpSelConceptoCaja").html());
        var selTipoConcepto=$("#tipoconceptocaja").val();
        $("#divConceptoCaja").html(tmpSelConceptoCaja({data:conceptoKardex,tipo:selTipoConcepto,idselect:""}));
    }


    function btnSave(){
        var nFilas = $("#tbodyDetailVentaKardex tr").length;
        var nrodoc = $("#nrodoc");
        console.log(nFilas);
        if(nFilas == 0){alert_error("Registre al menos un producto en el detalle"); return 0 ; }
        if((nrodoc.val()).trim() == ""){alert_error("Ingrese el número del documento");nrodoc.focus(); return 0 ; }
        if(!($("#cliente").val()).trim()){alert_error("El cliente es requerido");$("#cliente").focus();return 0;}
        var form= $("#formKardex").serialize();
        $.post(url_base+'Kardex/setKardex',form,function (data) {
            console.log(data);
            if(data.status === "ok"){
                alert_success("Se ejecuto correctamente ...");
                //close_modal('modal_id');
                //refrescar();
                $("#cliente").val("");
                $("#dircli").val("");
                $("#telcli").val("");
                $("#nrodoc").val("");
                $("#tbodyDetailVentaKardex").html("");
                getProductosVenta();
                refrescar();
            }else{
                alert_error("Error");
            }
        },'json');
    }


    function ver(id){

        $.post(url_base+'Kardex/getDetKardex',{"id":id},function (data) {         //console.log(data);
            var tmpDivModalBody=_.template($("#tmpDivModalBody").html());
            if(data.length > 0){
                $("#divModalBody").html(tmpDivModalBody({data:data[0]}));
                getProductosVenta();
                iniAutoCompleteCliente();
                open_modal('modal_id');
                calTotals();
                iniSelectConcepto();
                $("#tipoconceptocaja").val(data[0].idtipoconceptocaja);
                $("#conceptocaja").val(data[0].idconceptocaja);
                $("#isEdit").val(1);
            }else{
                $("#divModalBody").html("Sin datos");
            }


        },'json');
    }

    function setBtnSave2() {
        var ht='<a href="javascript:void(0)" onclick="btnSave(2)" type="button"  id="btnSave2" class=" btn btn-primary" >';
        ht+='Guardar y agregar otro';
        ht+='</a>';
        return ht;
    }

    function eliminar(id) {
        if(confirm('¿Esta seguro de eliminar este registro?')){
            var idd=parseInt(id);
            $.post(url_base+'kardex/deleteData','id='+idd,function (data) {
                if(data.status == 'ok'){
                    alert_success('Se realizo correctamente');
                    refrescar();
                }else{ alert_error('Error');}
                // console.log(data);
            },'json');
        }
    }
    $(document).on("change","#tipoconceptocaja",function () {
        iniSelectConcepto();
    });
    $(document).on("change","#conceptocaja",function () {
        var txt=$("#conceptocaja option:selected").text();
        console.log(txt);
        getProductosVenta($("#conceptocaja").val());

    });

    function getProductosVenta(concept) {
        if(concept == "12"){
            var tmpSelProductoVenta=_.template($("#tmpSelProductoVenta").html());
            $.post(url_base+"Kardex/getProductosVenta",function (data) {
                var dt=tmpSelProductoVenta({data:data});
                $("#divSelProducto").html(dt);
            },'json');
        }
    }
    $(document).on("click","#btnAddDetaillVentaKardex",function () {
        var selProducto=$("#selProducto");
        var idProd=$("#selProducto").val();

        var cantU=$("#cantU");
        var precioU=$("#precioU") ;
        if(selProducto.val() == "0"){ alert_error("Seleccione un producto");selProducto.focus();return 0; }
        if(cantU.val() <= 0){ alert_error("Cantidad debe ser mayor a 0");cantU.focus();return 0; }
        if(precioU.val() <= 0 ){ alert_error("Precio debe ser mayor a 0 ");precioU.focus();return 0; }
        idProd=idProd.split("-");
        idProd=idProd[0];
        var ifExistProdInTable=checkProductInList(idProd);
        if(ifExistProdInTable == false ){ alert_error("El producto ya se encuentra en el detalle"); return 0;}
        console.log(ifExistProdInTable);
        var htsel=$("#selProducto option:selected").text();
        htsel=htsel.split("(");
        var ht="";
        var sb=(Number(cantU.val())*Number(precioU.val())).toFixed(2);
        ht+='<tr>' +
            ' <td class="text-center">-</td>' +
            ' <td><input type="hidden" name="producto[]" class="producto" value="'+idProd+'" >'+htsel[0]+'</td>' +
            ' <td><input type="hidden" name="cantidad[]" value="'+cantU.val()+'" >'+cantU.val()+'</td>' +
            '  <td><input type="hidden" name="precio[]" value="'+precioU.val()+'" >'+precioU.val()+'</td>' +
            '  <td><input type="hidden" name="subtotal[]" class="subtotalv" value="'+sb+'"  >'+sb+'</td>' +
            '  <td><button type="button" class="btn btn-link btn-xs eliminart"><i class="fa fa-trash" style="color:red"></i></button></td>' +
            '</tr>';
        $("#tbodyDetailVentaKardex").append(ht);
        calTotals();
        selProducto.val(0);
        cantU.val(0);
        precioU.val(0);
        $("#stockU").val("");
        $("#subtotalU").val("");

    });
    $(document).on("keyup","#cantU",function () {
        validadCant();
    });
    $(document).on("change","#cantU",function () {
        validadCant();
    });
    function checkProductInList(idproducto) {
        var iniTableProduct=$("input[name='producto[]']");
        var ret=true;
        for(var i=0 ; i< iniTableProduct.length ; i++){
            var valx=$($(iniTableProduct)[i]).val();
            if(valx == idproducto ){
                ret= ret && false;
            }
        }
        return ret;
        //console.log(iniTableProduct,iniTableProduct.length);
    }
    $(document).on('click', '.eliminart', function() {
        var objCuerpo = $(this).parents().get(2);
        if ($(objCuerpo).find('tr').length == 1) {
            if (!confirm('Esta es el única fila de la lista ¿Desea eliminarla?')) {return;  }
        }
        var objFila = $(this).parents().get(1);
        $(objFila).remove();
        calTotals();
        getProductosVenta();
    });

    function validadCant(){
        var cant=Number($("#cantU").val())||0;
        var stock=Number($("#stockU").val())||0;

        if(cant > stock ){
            alert_error("Stock insuficiente");
            $("#cantU").val(stock);
            return 0;
        }
        console.log(stock);
        calcSubtotals();
    }
    $(document).on("keyup","#precioU",function () {
        calcSubtotals();
    });
    $(document).on("change","#precioU",function () {
        calcSubtotals();
    });

    $(document).on("change","#selProducto",function (){
        var selprod=$(this).val();
        var stock=0;
        var precio="";
        if( selprod != "0" ){
            selprod=selprod.split("-");
            stock=selprod[1];
            precio=Number(selprod[2]);
            console.log(stock);
        }
        $("#stockU").val(stock);
        $("#cantU").val("");
        $("#precioU").val(precio);
        //console.log(stock);
        calcSubtotals();
    });
    function calcSubtotals() {
        var cc= Number($("#cantU").val());
        var pp=Number($("#precioU").val());
        var sb=cc*pp;
        $("#subtotalU").val(sb);
    }

    function calTotals(){
        var sum = 0;
        var sumM = 0;
        var sumF = 0;
        $(".subtotalv").each(function() {
            sum += Number($(this).val());
            console.log($(this).val());
        });
        $("#total").html(sum.toFixed(2));
        /*$("#montoPagarx").val(sum.toFixed(2));
        $("#totalPrendasMx").html(sumM);
        $("#totalPrendasFx").html(sumF);
        $("#totalPrendasx").html(sumM+sumF);
        $("#adelantominx").html(sum*0.5);*/
    }

    function deleteDetail(id) {
        if(confirm('¿Esta seguro de eliminar este registro?')){
            var idd=parseInt(id);
            $.post(url_base+'kardex/deleteDataDetail','id='+idd,function (data) {
                if(data.status == 'ok'){
                    alert_success('Se realizo correctamente');
                    //refrescar();
                }else{ alert_error('Error');}
                // console.log(data);
            },'json');
        }
    }

    function iniAutoCompleteDistrito() {
        $("#cliente").autocomplete({
            source: "<?= base_url(); ?>viviendas/getDepProvByDist",
            minLength: 1,// tamaño de cadena
            delay: 200,
            select: function( event, ui ) {
                console.log(ui.item);
                if(ui.item.length > 0){
                    var dt=ui[0].item
                    $("#departa").val(dt.DESC_DPTO);
                    $("#prov").val(dt.DESC_PROV);

                }


            }
        });
    }
</script>
<script type="text/template" id="tmpDivModalBody">

    <form class="panel-body form-horizontal form-padding" id="formKardex">
        <input type="hidden" name="isEdit" id="isEdit" value=''>
        <input type="hidden" name="idEdit" id="idEdit" value='<% if(typeof data !=="undefined"){ print(data.idkardex) }else{print("0")}%>'>
        <!--Text Input-->

        <div class="row">
            <div class="form-group">
                <label class="col-sm-2 control-label" for="demo-hor-inputemail">Tipo Concepto</label>
                <div class="col-sm-4">
                    <select id="tipoconceptocaja" name="tipoconceptocaja" class="form-control">
                        <?php foreach ($tipoconceptocaja as $i){?>
                            <option value="<?=$i["idtipoconceptocaja"]?>" ><?=$i["nombre"]?></option>
                        <?php }?>
                    </select>
                </div>
                <label class="col-sm-2 control-label" for="demo-hor-inputemail">Fecha Reg</label>
                <div class="col-sm-4" id="">
                    <input type="date" value='<% if(typeof data !=="undefined"){ print(data.fechareg) }else{print(fechaActual)}%>' style="line-height: 12px;" name="fechak" class="form-control" id="fechak">
                </div>

            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="demo-hor-inputemail">Concepto</label>
                <div class="col-sm-4" id="divConceptoCaja">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="demo-hor-inputemail">Nro Documento</label>
                <div class="col-sm-4" id="">
                    <input type="text"  name="nrodoc" class="form-control" id="nrodoc" value='<% if(typeof data !=="undefined"){ print(data.nrodoc) }else{print("")}%>'>
                </div>
            </div>

            <div id="isVenta">
                <div class="row">
                    <div class="col-sm-1"   >
                    </div>
                    <div class="col-sm-4"   >
                        <div class="form-group"  style="margin-right: 5px;" >
                            <label class="control-label">Cliente:</label>
                            <div class="input-group  ">
                                <input type="text" class="form-control" id="cliente" name="cliente" value='<% if(typeof data !=="undefined"){ print(data.nombre) }else{print("")}%>' placeholder="" autocomplete="on">
                                <input type="hidden"  id="idcliente" name="idcliente" placeholder="" value='<% if(typeof data !=="undefined"){ print(data.idcliente) }else{print("")}%>' >
                                <span class="input-group-btn">
					                    <button type="button" class="btn btn-success btn-sm"><i class="demo-pli-add"></i></button>
					                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3"   >
                        <div class="form-group" style="margin-right: 5px;" >
                            <label class="control-label">Dirección</label>
                            <input type="text" class="form-control" name="dircli" id="dircli">
                        </div>
                    </div>
                    <div class="col-sm-3"  >
                        <div class="form-group" style="margin-right: 5px;" >
                            <label class="control-label">Tel</label>
                            <input type="text" class="form-control" name="telcli" id="telcli" >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1"   >
                    </div>
                    <div class="col-sm-6"   >
                        <div class="form-group"  style="margin-right: 5px;" >
                            <label class="control-label">Producto:</label>
                            <div  id="divSelProducto">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1"   >
                        <div class="form-group" style="margin-right: 5px;" >
                            <label class="control-label">Stock</label>
                            <input type="text" readonly="readonly" class="form-control" id="stockU">
                        </div>
                    </div>
                    <div class="col-sm-1"  >
                        <div class="form-group" style="margin-right: 5px;" >
                            <label class="control-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantU" >
                        </div>
                    </div>
                    <div class="col-sm-1"  >
                        <div class="form-group" style="margin-right: 5px;" >
                            <label class="control-label">Precio</label>
                            <input type="number" class="form-control" id="precioU">
                        </div>
                    </div>
                    <div class="col-sm-1"  >
                        <div class="form-group" style="margin-right: 5px;" >
                            <label class="control-label">Subtotal</label>
                            <input type="number"  readonly="readonly" class="form-control" id="subtotalU">
                        </div>
                    </div>
                    <div class="col-sm-1"  >
                        <div class="form-group" style="margin-right: 5px;" >
                            <label class="control-label" style="visibility: hidden">________</label>
                            <button id="btnAddDetaillVentaKardex" type="button" class="btn btn-sm btn-mint">+</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="table-responsive ">
                        <table class="table table-bordered table-condensed table-hover" >
                            <thead>
                            <tr>
                                <th class="text-center"></th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Total</th>
                                <th>Acción</th>
                            </tr>
                            </thead>
                            <tbody id="tbodyDetailVentaKardex">
                            <% if( typeof data !== "undefined" ){

                            _.each(data.data,function(i,k){   console.log(i);
                            var c=Number(i.cantidad);
                            var p=Number(i.precio);
                            var sb=c*p;
                            var htprod=i.cultivo+"-"+i.clasecultivo+"-"+i.catcultivo+" | "+i.cultivar
                            %>
                            <tr>
                                <td class="text-center">-</td>
                                <td><input type="hidden" name="producto[]" class="producto" value="<%=i.idproductokardex%>" ><%=htprod%> </td>
                                <td><input type="hidden" name="cantidad[]" value="<%=i.cantidad%>" ><%=i.cantidad%> </td>
                                <td><input type="hidden" name="precio[]" value="<%=i.precio%>" ><%=i.precio%> </td>
                                <td><input type="hidden" name="subtotal[]" class="subtotalv" value="<%=sb.toFixed(2)%>"  ><%=sb.toFixed(2)%></td>
                                <td><button type="button" onclick="deleteDetail('<%=i.iddetallekardex%>')" class="btn btn-link btn-xs eliminart"><i class="fa fa-trash" style="color:red"></i></button></td>
                            </tr>

                            <%   }); %>
                            <%   }   %>

                            </tbody>
                            <tfoot >
                            <tr>
                                <th colspan="4" style="text-align: right;">Total</th>
                                <th><b id="total"></b></th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>


            <!--<div class="form-group" id="divMonto">
                <label class="col-sm-2 control-label" for="demo-hor-inputemail">Monto</label>
                <div class="col-sm-4" id="">
                    <input type="text" class="form-control" name="monto" id="monto">
                </div>
            </div>-->
        </div>
        <div class="row">

        </div>
        <div class="row">

        </div>

        <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-9">
                <a href="javascript:void(0)" onclick="btnSave()" type="button"   class=" btn btn-success ">
                    Guardar y terminar
                </a>
                <button type="button" id="btnCancel"  data-dismiss="modal" class=" btn btn-danger ">
                    Cerrar
                </button>

            </div>
        </div>
    </form>
</script>
<script type="text/template" id="tmpSelConceptoCaja">
    <% console.log(data,tipo);%>
    <select id="conceptocaja" name="conceptocaja" class="form-control" >
        <% _.each(data,function(i,k){
        if(i.idtipoconceptocaja == Number(tipo)){ %>
        <option value="<%=i.idconceptocaja%>" ><%=i.descripcion%></option>
        <%   }
        })%>
    </select>
</script>
<script type="text/template" id="tmpSelProductoVenta" >
    <select id="selProducto" name="selProducto" class="form-control" >
        <option value="0">Seleccione...</option>
        <% _.each(data,function(i,k){ %>
        <option value='<%= i.idproductokardex+"-"+i.stock+"-"+ Number(i.precio) %>' ><%=i.cultivo+"-"+i.clasecultivo+"-"+i.catcultivo+" | "+i.cultivar +" ("+i.stock+"kg)" %></option>
        <%  }); %>
    </select>
</script>