<div id="page-content">
<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">

                <div class="panel-control">
                    <ul class="pager pager-rounded">
                        <li><a href="javascript:void(0);" onclick="verDirListFact()">Ver Archivos XML</a></li>
                    </ul>
                    <button class="demo-panel-ref-btn btn btn-default" data-toggle="panel-overlay" onclick="refrescar();"  ><i class="demo-psi-repeat-2"></i></button>
                </div>
                <h3 class="panel-title"  ><b style="font-size: 18px;"><i class="fa fa-address-card"></i>  Facturacíon</b>
                    <button class="btn btn-mint btn-sm" onclick="btnGenXmls(this)" ><b>1.Generar XMLs B/F/N </b> </button>
                    <button class="btn btn-success btn-sm" onclick="sendXmlsSunat(this)"  ><b>2.Enviar docs a SUNAT B/F/N  </b> </button>
                   <!-- <button class="btn btn-purple btn-sm" onclick="GeneraResumenDXmlsSunat(this)"  > <b>Genera Resumen Diario </b> </button>
                    <button class="btn btn-primary btn-sm" onclick="ComunicaBaja(this)"  > <b>Comunicación de Baja </b> </button> -->
                   <!-- <button class="btn btn-default btn-sm" onclick="creaNotaFact(this,'credito')" title="Nota de credito" ><b> Nota de credito </b> </button>
                   --> <button class="btn btn-pink btn-sm" onclick="reportFacturado(this)"  ><b> Reporte </b> </button>
                 
                </h3>

                <hr style="margin-bottom: 0px;margin-top: 0px;">
            </div>

            <!--Data Table-->

            <!--===================================================-->
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                         <div class="table-responsive">
                            <table id="dtTable" class="table table-bordered table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th>#
                                    </th>
                                    <th>Accion</th>
                                    <th>UBL/XML</th>
                                    <th>Estado <br> Sunat</th>
                                    <th>Respuesta Sunat</th>
                                    <th>CDR</th>
                                    <th>NRO Compro</th>
                                    <th>Bol/Fact/Etc</th>
                                    <th>Fecha Comprobante</th>

                                </tr>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>


                        </div>
                    </div>
                </div>

            </div>
            <!--===================================================-->
            <!--End Data Table-->

        </div>
    </div>
</div>
</div>

<div style="display: none;" id="tempTablaExcel" >
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

<script type="text/javascript">
    var dtTable;
    $(document).ready(function() {
        dtIni();

    });

    function refrescar () {
        dtTable.ajax.reload();
    }

    function dtIni(){
        dtTable=$('#dtTable').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url(); ?>facturacion/getData",
                "type": "POST"
            },
            "columns": [
                { "data": null,"searchable":false },
                {   "sortable": false,
                    "searchable":false,
                    "render": function ( data, type, full, meta ) {
                        var idbandejafact = full.idbandejafacturacion;
                        var idreferencia = full.idreferencia;
                        var referencia = full.referencia;
                        var xmlf = full.xmlfile;
                        var ublvalido = Number(full.ublvalido);
                        var existcdr = Number(full.existcdr);
                        var forigen = full.fecharegistroorigen;
                        var idempresa=full.idempresa;
                        let html="";
                        if(ublvalido == 1 && existcdr ==0 ){
                            html+= '<button type="button"   onclick="enviarSunat(this,'+idempresa+','+idbandejafact+','+idreferencia+',\''+referencia+'\',\''+xmlf+'\');" class="btn btn-dark btn-xs"><i class="fa fa-file-text-o"></i> Enviar A SUNAT</button>';
                            html+= '<button type="button"   onclick="resetearXmlSunat(this,'+idbandejafact+','+idreferencia+',\''+referencia+'\');" class="btn btn-active-dark btn-xs"><i class="fa fa-file-text-o"></i> Reset</button>';
 html+= '<button type="button"  onclick="consultarTicketCompro(this,'+idbandejafact+');" class="btn btn-info btn-xs"><i class="fa fa-inbox"></i>  Ticket</button><br>';
                           
                        }else if(existcdr == 1){

                            //console.log(existcdr);
                            // html+= '<button type="button"   onclick="validarUBL(this,'+xmlf+');" class="btn btn-purple btn-xs"><i class="fa fa-file-text-o"></i>Validar UBL</button>';
                            html+= '<button type="button"   onclick="resetearXmlSunat(this,'+idbandejafact+','+idreferencia+',\''+referencia+'\');" class="btn btn-active-dark btn-xs"><i class="fa fa-file-text-o"></i> Reset</button>';

                        }else{
                            html+= '<button type="button"  onclick="generaXml(this,'+idempresa+','+idbandejafact+','+idbandejafact+',\''+referencia+'\' ,\''+forigen+'\' );" class="btn btn-mint btn-xs"><i class="fa fa-file-text-o"></i> Genera/valida XML</button><br>';
                            html+= '<button type="button"  onclick="editarCompro(this,'+idempresa+','+idbandejafact+','+idbandejafact+',\''+referencia+'\' ,\''+forigen+'\' );" class="btn btn-purple btn-xs"><i class="fa fa-file-text-o"></i> Editar</button><br>';
 
                                                       
							html+= '<button type="button"  onclick="eliminarCompro(this,'+idbandejafact+','+full.idempresa+',\''+full.idtipodoc+'\',\''+full.correlativonro+'\');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button"></i> Eliminar</button><br>';

                        }

                        return html;
                    }
                },
                { "data": "msjubl",
                    "sortable": false,
                    "searchable":false,
                    "render": function ( data, type, full, meta ) {
                        let ublvalido= Number(full.ublvalido) ;
                        let xmlfile= full.xmlfile;
                        let html="";
                        if(xmlfile){
                            if(ublvalido ==1){
                                html="<b>Xml Válido</b>";
                            }else{
                                html="<b style='color:red;'>Xml NO Válido</b>";
                            }
                            html+="<br>"+xmlfile+"";
                        }else{
                            html+="<br>XML No generado";
                        }




                        return html;
                    }
                },
                {   "sortable": false,
                    "searchable":false,
                    "render": function ( data, type, full, meta ) {
                        var isenviox=Number(full.isenviadosunat);
                        var isaceptadox=Number(full.codesunatrespuesta);
                        var existcdrx=Number(full.existcdr);

                        var ff=" ";
                        if(isenviox == 0){
                            ff="<b style='color:darkred;'>No enviado</b>";
                        }else if(isenviox == 1){
                            ff="<b style='color:darkgreen;'>Enviado</b>";
                            if(isaceptadox == 0){ // aceptado 0
                                ff+="<b style='color:darkgreen;'>->Aceptado</b>";
                                if(existcdrx == 1){
                                    ff+="<b style='color:darkgreen;'>->Existe Respuesta SUNAT </b>";
                                }else{
                                    ff+="<b style='color:red;'>->Sin Respuesta SUNAT </b>";
                                }
                            }else{
                                ff+="<b style='color:darkgreen;'>->No Aceptado/Observado </b>";
                            }
                        }else{

                        }

                        return ff;
                    }
                },
                { "data": "msjsunat" },
                {   "sortable": false,
                    "searchable":false,
                    "render": function ( data, type, full, meta ) {
                        let cdrr = full.respuestacdrfile;
                        let existcdr = Number(full.existcdr) ;
                        let ht="";
                        if(existcdr == 1){
                            ht=cdrr;
                        }else{
                            ht="-";
                        }

                        return ht;
                    }
                },
                { "data": "seriecorrelativo" },

                {   "sortable": true,
                    "searchable":true,
                    "data": "tipodoccompro",
                    "render": function ( data, type, full, meta ) {
                        var tipodoccompro = full.tipodoccompro;
                        var id = full.idreferencia;
                        var idbandfact = full.idbandejafacturacion;
                        var ref = full.referencia;
                        var forigen = full.fecharegistroorigen;
                        var idempresa = full.idempresa;

                        var idusuario="<?=$this->session->userdata('user_id')?>";
                        var idrole="<?=$this->session->userdata('id_role')?>";
                        var iduser=full.idusuario;
                        let col="";
                        switch (tipodoccompro) {
                            case 'Ninguno': col='font-weight: lighter;';break;
                            case 'Boleta': col='color:darkgreen;' ;break;
                            case 'Factura':col='color:purple;'; break;
                            default:break;

                        }
                        var html= "";
                        html=html+"<a href='javascript:void(0)' onclick='imprimir("+idempresa+",\""+ref+"\","+idbandfact +","+idbandfact+",\""+forigen+"\" );'  class='btn btn-default  btn-xs'><i class='fa fa-print'></i> <b style='"+col+"'>"+tipodoccompro+"</b></a>";



                        //html= '<b  style="'+col+'">'+tc+'</b>';
                        return html;
                    }
                },

                { "data": "fecharegistroorigen" }
            ],
            "responsive": true,
            "pageLength": 25,
            "searchDelay": 900,
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

    function eliminarCompro(_this,idcompro,idempresa,idtipodoc,correlativoactual){
        var config = prompt("Escribe 'Confirmar' para ELIMINAR ", "");

        if (config != null) {

            if(config.toLowerCase() == 'confirmar'){
                var btn=$(_this);
                btn.button("loading");
                $.post(url_base+"facturacion/deleteFact",{idfact:idcompro,idempresa:idempresa,idtipodoc:idtipodoc,correlativoactual:correlativoactual},function (r) {
                    if(r.status){
                        alert_success(r.msj);
                        refrescar();
                    }else{
                        alert_error(r.msj);
                    }
                    btn.button("reset");
                },'json');

            }else{
                alert_error("No escribio la palabra correcta");
            }

        }else{
            return 0;
        }
    }

    function creaFacturacion(tipo) {
        $.post(url_base+"facturacion/creaFacturacion",{tipo:tipo},function (r) {

        },'json');
    }


    function enviarSunat(_this, idempresa,idbandejafact,idreferencia,referencia,xmlf) {
        if(!confirm("Esta seguro de enviar a SUNAT")){
            return 0;
        }
        var btn=$(_this);
        btn.button("loading");
        $.post(url_base+"facturacion/enviaFacturacion",{idempresa:idempresa,idbandejafact:idbandejafact,idreferencia:idreferencia,referencia:referencia,xmlf:xmlf},function (r) {
            if(r.status =="ok"){
                btn.button("reset");
                refrescar();

            }
        },'json');
    }

    function validarUBL(_this,xmlfile) {
        var btn=$(_this);
        btn.button("loading");
        $.post(url_base+"facturacion/ublValidator",{xmlfile:xmlfile},function (r) {
            if(r.status =="ok"){
                alert_error("Formato XML Valido");
                refrescar();
            }else{
               alert_error(r.msj);
            }
            btn.button("reset");
        },'json');
    }

    function generaXml(_this,idempresa,id,idreferencia,referencia,fechaorigen) {
        var btn=$(_this);
        btn.button("loading");
        $.post(url_base+"facturacion/generaValidadXml",{idempresa:idempresa,id:id,tipo:"only",idreferencia:id,referencia:referencia,fechaorigen:fechaorigen},function (r) {
            alert_success("Formato XML creado");
            /*if(r.status =="ok"){


            }else{
                alert_error(r.msj);
            } */
            refrescar();
            btn.button("reset");
        },'json');
    }

     function imprimir(idempresa,ref,id,idbadejafact,forigen) {
         var urix=url_base+'/facturacion/reporteFactHtmlPDF/'+idempresa+'/'+ref+'/'+id+"/"+idbadejafact+"/"+forigen;
         var myWindow = window.open(urix, "xxxxx", "width=700,height=700");
        // myWindow.print();
    }

    function btnGenXmls(_this) {
        var btn=$(_this);
        btn.button("loading");
         $.post(url_base+"facturacion/generaValidadXml",{id:0,tipo:"all",referencia:'venta',idreferencia:0,fechaorigen:''},function (r) {
        
            if(r.status =="ok"){
                alert_success("Formato XML creado y validado");
                refrescar();
            }else{
                alert_error(r.msj);
            }
            btn.button("reset");
        },'json');
    }

    function sendXmlsSunat(_this) {
        var btn=$(_this);
        btn.button("loading");
        $.post(url_base+"facturacion/sendAllXML",{id:0,tipo:"all"},function (r) {
            if(r.status =="ok"){
                alert_success("Formato XML creado y validado");
                refrescar();
            }else{
                alert_error(r.msj);
            }
            btn.button("reset");
        },'json');
    }

    function resetearXmlSunat(_this,idbf,referencia){
        var config = prompt("Escribe 'Confirmar' para resetear", "");

        if (config != null) {

            if(config.toLowerCase() == 'confirmar'){
                $.post(url_base+"facturacion/resetearXmlSunat",{"idbf":idbf},function (r) {
                    if(r.status == "ok"){
                        alert_success("se reseteo Correctamente");
                    }
                    refrescar();
                },'json');
            }else{
                alert_error("No escribio la palabra correcta");
            }

        }else{
            return 0;
        }
    }
    
	
	 function consultarTicketCompro(_this,idcompro){
        $("#modalTicket").modal("show");
        var tmpBodyTicket=_.template($("#tmpBodyTicket").html());

        $("#modalBodyTicket").html("<h1>Cargnado....</h1>");
        $.post(url_base+"statuscdr/getStatusCdrJSON",{idfact:idcompro},function (r) {
            if(r.status){
                $("#modalBodyTicket").html(tmpBodyTicket({data:r.data}));
            }else{
                $("#modalBodyTicket").html("Error");
            }
            //btn.button("reset");
        },'json');
    }

    $(document).on("click","#btnConsultaTicket",function (){
        var form=$("#formTicket").serialize();
        $.post(url_base+"facturacion/getDataTicket",form,function (r) {
            console.log(r);
        },'json');
    });
	
	
	
    function verDirListFact() {
        $("#modalID").modal("show");

        $.post(url_base+"facturacion/getListFilesDirFact",{"id":1},function (r) {
            var hh="";
            $.each(r.data,function(k,i){
                hh+=`<tr>
                        <td>${k+1}</td>
                        <td><a target="blank" href="<?=base_url()?>filesfact/comprobantes/${i.filename}" >${i.filename}</a></td>
                        <td>${i.ultimamodificacion}</td>
                        <td> </td>
                    </tr>`;
            });
            $("#idTbodyListFilesDir").html(hh);
            $("#tableLisDirFact").DataTable();
        },'json');
    }

    function GeneraResumenDXmlsSunat(_t) {
        var hora="<?=date('H')?>";
        var d = new Date();
        var ctx=$(_t);
        if(hora <= 18){
            if(!confirm("Si genera el resumen diario ahora,los comprobantes creados luego no se emitiran en este... desea continuar?")){
                return 0;
            }
        }else{
            if(!confirm("Desea continuar la generación del resumen diario")){
                return 0;
            }
        }

        ctx.button("loading");
        $.post(url_base+"facturacion/generaResumenDiario",{d:"d"},function (r) {
            if(r.stado=="ok"){
                alert_success("Resumen Generado");
                refrescar();
            }
            refrescar();
            ctx.button("reset");

        },'json');
    }

    function ComunicaBaja(_t) {
        $("#modalIDMulti").modal("show");
        let tmpBodyComBaja=_.template($("#tmpBodyComBaja").html());
        $("#titleModalMulti").html("Comunicación de baja ");
        $("#bodyModalMulti").html(tmpBodyComBaja);
        iniAutoCompleteComBaja();
    }


    ////
    function iniAutoCompleteComBaja() {
        $("#inSearchBolFact").autocomplete({
            source: "<?= base_url(); ?>facturacion/getIniAutocompleteSearchBolFactNotes",
            minLength: 2,// tamaño de cadena
            delay: 250,
            search: function( event, ui ) {
                $("#idbandejafactx").val("");
            },
            select: function( event, ui ) {
                let dt=ui.item;
                //console.log(dt);
                $("#idbandejafactx").val(dt.idbandejafacturacion);
            }
        });
    }
    $(document).on("click","#btnAddInDetMotBaja",function () {
        let idbandejafact=$("#idbandejafactx").val();
        let motBajaIni=$("#motBaja").val();
        let motBaja=motBajaIni.replace(/\s*$/,"");
        if(idbandejafact !=="" && motBaja.length >0 ){
            if(validarSiExisteComBajaInTable()){
                alert_error("El comprobante ya fue agregado");
                return 0;
            }
            let vlIn=$("#inSearchBolFact").val();
            let trx=`<tr>
                    <td>*</td>
                    <td>${vlIn}<input type="hidden" name="idbanfact[]" value="${idbandejafact}" > </td>
                    <td>${motBajaIni} <input type="hidden" name="descbaja[]" value="${motBajaIni}"></td>
                    <td><button type="button" class="btn btn-xs btn-danger" >
                        <i class="fa fa-trash-o"></i></button>
                    </td>
                    </tr>`;
            $("#tbodyComBaja").append(trx);

            $("#inSearchBolFact").val("");
            $("#motBaja").val("");
            $("#idbandejafactx").val("");
        }else{
            alert_error("Ingrese el comprobante al detalle a la tabla")
            return 0;
        }
    });
    
    function validarSiExisteComBajaInTable() {
        let idbandejafact=$("#idbandejafactx").val();
        let dtidbanfact=$("input[name='idbanfact[]']");
        let bol=false;
        $.each(dtidbanfact,function(k,i){
             if( $(i).val() === idbandejafact ){
                 bol=true
             }
        });
        return bol;        
    }
    
   $(document).on("click","#btnSendForm",function () {
        var btn=$(this);
        if(!confirm("Seguro de realizar esta operación")){return 0;}
        var form=$("#formComBaja").serialize();
        let lenTb=$("#tbodyComBaja tr").length;
        if(lenTb === 0){ alert_error("Agregue comprobante en la tabla"); return 0;}
       btn.button("loading");
        $.post(url_base+"facturacion/setComunicaBaja",form,function (r) {
            if(r.status =="ok"){
                alert_success("Se realizó correctamente");
                $("#modalIDMulti").modal("hide");
            }else{
                alert_error("ERROr comunicar al desarrolador");
            }
            refrescar();
            btn.button("reset");
        },'json');

   });

    /////---------------------------------------------------

  function creaNotaFact(_t,tipo){
      $("#titleModalMulti").html("Regitra Nota de Credito");
      $("#bodyModalMulti").html("");
      $("#DivModalDialog").css("width","85%");
      $("#modalIDMulti").modal("show");
      let tmpDivNotaFact=_.template($("#tmpDivNotaFact").html());
      $("#bodyModalMulti").html(tmpDivNotaFact);
      iniAutoCompleteNotaCD();

  }


    function iniAutoCompleteNotaCD() {
        $("#ntcomprobante").autocomplete({
            source: "<?= base_url(); ?>facturacion/getIniAutocompleteSearchBolFactNotes/nota",
            minLength: 1,// tamaño de cadena
            delay: 550,
            search: function( event, ui ) {
                $("#idbandejafactx").val("");
            },
            select: function( event, ui ) {
                let dt=ui.item;
                //console.log(dt);
                $("#idbandejafactx").val(dt.idbandejafacturacion);
                $("#ntseldoc").val(dt.tipdocucliente);
                $("#ntdoc").val(dt.numdocucliente);
                $("#ntfventa").val(dt.fechaventa);
                $("#ntcliente").val(dt.razoncliente);
                $("#ntdircliente").val(dt.direccioncliente);
                $("#nttipodocref").val(dt.desccomprobante);
                $("#ntdocref").val(dt.value);
                $("#ntfecharecepcion").val(dt.fecharegistracdr);
                $("#ntDiasTrans").val(dt.diastranscurridos);
                $("#nttipocomprobante").val(dt.idtipodoc);
                $("#ntidreferenciaventa").val(dt.idreferencia);

                   var nttiponotacredito=$("#nttiponotacredito").val();

              getDetVentaNota(dt.idventa,nttiponotacredito);

            }
        });
    }

    function readonlyTodaNotaCredito() {
        var ntseldoc=$("#ntseldoc") ;

        var ntdoc= $("#ntdoc") ;
        var ntfventa= $("#ntfventa");
        var ntcliente=  $("#ntcliente");
        var ntdircliente=$("#ntdircliente");
        var nttipodocref= $("#nttipodocref") ;
        var ntdocref=$("#ntdocref") ;
        var ntfecharecepcion=$("#ntfecharecepcion");
        var ntDiasTrans=$("#ntDiasTrans") ;
        var nttipocomprobante= $("#nttipocomprobante") ;
        var ntidreferenciaventa= $("#ntidreferenciaventa");

        var cantidadc=$("input[name='cantidadc[]']");
        var cantidadcBF=$("input[name='cantidadcBF[]']");

        var pventa=$("input[name='pventa[]']");
        var pventaf=$("input[name='pventaf[]']");

        ntcliente.attr("readonly","readonly");
        ntseldoc.attr("readonly","readonly");
        ntdoc.attr("readonly","readonly");
        ntdircliente.attr("readonly","readonly");

        cantidadc.attr("readonly","readonly");
        cantidadcBF.attr("readonly","readonly");

        pventa.attr("readonly","readonly");
        pventaf.attr("readonly","readonly");


    }
    
    function validaCamposByTipoNota(nttiponotacredito) {

        var ntseldoc=$("#ntseldoc") ;

        var ntdoc= $("#ntdoc") ;
        var ntfventa= $("#ntfventa");
        var ntcliente=  $("#ntcliente");
        var ntdircliente=$("#ntdircliente");
        var nttipodocref= $("#nttipodocref") ;
        var ntdocref=$("#ntdocref") ;
        var ntfecharecepcion=$("#ntfecharecepcion");
        var ntDiasTrans=$("#ntDiasTrans") ;
        var nttipocomprobante= $("#nttipocomprobante") ;
        var ntidreferenciaventa= $("#ntidreferenciaventa");

        var cantidadc=$("input[name='cantidadc[]']");
        var cantidadcBF=$("input[name='cantidadcBF[]']");

        var pventa=$("input[name='pventa[]']");
        var pventaf=$("input[name='pventaf[]']");

        readonlyTodaNotaCredito();

        switch (nttiponotacredito) {
            case "01":
                break;
            case "02":
                break;
            case "03":
                break;
            case "04":
                break;
            case "05":
                break;
            case "06":
                break;
            case "07":
                break;
            case "08":
                break;
            case "09":
                break;
            case "10":
                break;
            default: break;

        }
    }


    function getDetVentaNota(id,nttiponotacredito){
      $.post(url_base+"facturacion/getDetalleVentaN",{id:id},function (r) {
         if(r.status=="ok"){
             let dataRes=r.data;
             let tmpDetailVentaEdit=_.template($("#tmpDetailVentaEdit").html());
             $("#detNotaDetVenta").html(tmpDetailVentaEdit({data:dataRes}));
             calSubtotalTotals();
             validaCamposByTipoNota(nttiponotacredito);
         }

      },'json');
    }

    function calSubtotalTotals() {
        var ssubtotal=$("input[name='ssubtotal[]']");
        var ssubtotalBF=$("input[name='ssubtotalBF[]']");
        var stt=0;
        var sttBf=0;
        $.each(ssubtotal,function(k,i){
            stt+=parseFloat(Number($(i).val()));
        });
        $.each(ssubtotalBF,function(k,i){
            sttBf+=parseFloat(Number($(i).val()));
        });
        var igv= $("#igv").val();
        var valttIgv=(parseFloat(Number(igv))*stt)/100;
        $("#ssbtotalxyIGV").val(valttIgv.toFixed(2));
        $("#ssbtotalxy").val(stt.toFixed(2));
        $("#totalcompra").val((parseFloat(stt)));

        $("#ssbtotalxyBF").val(sttBf.toFixed(2));
        $("#totalcompraBF").val((parseFloat(sttBf)));
    }

  function EditarItem(_t) {
    let ctx=$(_t);
    let tr=ctx.closest("tr");
    let inisForQuitar=tr.find("input[name='isForQuitar[]']");
    if(inisForQuitar.val()== 0){
        ctx.html("Cancelar");
        ctx.removeClass("btn-danger");
        ctx.addClass("btn-success");
        inisForQuitar.val(1);
        tr.css("color","red");
    }else{
        ctx.html("Editar");
        ctx.removeClass("btn-success");
        ctx.addClass("btn-danger");
        inisForQuitar.val(0);
        tr.css("color","black");
    }
  }

  $(document).on("click","#btnSaveNotaC",function () {
    var btn=$(this);
    var formNotaCredito=$("#formNotaCredito");
     var ntDiasTrans=$("#ntDiasTrans").val();
      ntDiasTrans=Number(ntDiasTrans);
    var bol=true;
    bol=bol&&$("#ntmotivo").required()

    if(!bol){
        alert_error("Ingrese los campos requeridos");
        return 0;
    }

    if(ntDiasTrans > 15){
        alert_error("El comprobante ya supero los 15 días desde su envío");
        return 0;
    }

    btn.button("loading");
    $.post(url_base+"facturacion/setNotaCredito",formNotaCredito.serialize(),function (r) {
        if(r.status =="ok"){
            alert_success("Se registro la nota de crédito");

        }else{
            alert_error("Error");
        }
        refrescar();
        btn.button("reset");
    },'json');
  });



 function reportFacturado(_t) {
        $("#modalFacturacion").modal("show");
        $("#divReporteFact").html("");
    }
	
    $(document).on("click","#btnGenerarReporteFacturacion",function () {
        var tmpReporteFacturacin=_.template($("#tmpReporteFacturacin").html());
        let fini=$("#fechainirep").val();
        let ffin=$("#fechafinrep").val();

        $.post(url_base+"facturacion/reporteFacturacionByDate",{fini:fini,ffin:ffin},function (r) {
            if(r.status){
                $("#divReporteFact").html(tmpReporteFacturacin({data:r.data}));

                //verComprasRealizadasByAnio();


            }else{
                $("#divReporteFact").html("<h1>Error</h1>");
            }
        },'json');
    });

    function verResumenComprobates() {
        var idanio=$("#selcomprasanios").val();
        var tmpTbodyComprasRealizadas=_.template($("#tmpTbodyComprasRealizadas").html())
        $.post(url_base+"facturacion/reporteFacturacionGroupCorrelativoRUcByDate",{idanio:idanio},function (r) {
            $("#tbodyComprasAnios").html(tmpTbodyComprasRealizadas({data:r.data}));

        },'json');
    }

 function verComprasRealizadasByAnio() {
     var idanio=$("#selcomprasanios").val();
     var tmpTbodyComprasRealizadas=_.template($("#tmpTbodyComprasRealizadas").html())
    $.post(url_base+"facturacion/getListaComprasby",{idanio:idanio},function (r) {
        $("#tbodyComprasAnios").html(tmpTbodyComprasRealizadas({data:r.data}));

    },'json');
 }

 $(document).on("change","#selcomprasanios",function () {
     verComprasRealizadasByAnio();
 });

 // INI

 function editarCompro(_this,idempresa,id,idreferencia,referencia,fechaorigen) {
     var btn=$(_this);
     btn.button("loading");
     $.post(url_base+"facturacion/getdataFactById",{id:id},function (r) {
        if(r.status){
            var dataR=r.data;
            if(dataR.length <=0){
                alert_error("Fallo")
                return 0;
            }
            var Dr=dataR[0];
            var DrDet=r.dataDet;
            var tmp=_.template($("#tmpGeneraComprobante").html());
            $("#modalGenerarComprobante").modal("show");

            $("#modalBodyGenerarComprobante").html(tmp({isForNew:"",dataDet:DrDet}));

            $("#isEditCompro").val(1);
            $("#idEditCompro").val(id);
            $("#pserieCorreCompro").html(Dr.seriecorrelativo);
            $("#bRucEdit").html(Dr.numruc);

            $("#usuariogenera").val(Dr.idempresa);
            $("#usuariogenera").attr("disabled", true);

            $("#tipocomprobante").val(Dr.idtipodoc);
            $("#tipocomprobante").attr("disabled", true);

            $("#tipodocclientecompro").val(Dr.tipdocucliente);
            $("#nroDocClienteCompro").val(Dr.numdocucliente);
            $("#clientecompro").val(Dr.razoncliente);
            $("#fechacompro").val(Dr.fecharegistroorigendate);
            $("#horacompro").val(Dr.horaregistroorigen);

            $("#dirclientecompro").val(Dr.direccioncliente);


            $("#depCompro").val(Dr.departamentocli);
            $("#provCompro").val(Dr.provinciacli);
            $("#distCompro").val(Dr.distritocli);

            $("#ubigeoCompro").val(Dr.ubigeocli);
            $("#bComproUbigeo").val(Dr.ubigeocli);

            $("#emailCompro").val(Dr.emailcliente);


            $("#tipomonedacompro").val(Dr.moneda);

            iniAutoCompleteUbigeoNat();
            $("#distCompro").attr("autocomplete","nope");
            calcTotalDetalleTableCompro();
            validaTipoDoc();
            getFinalfechaComproForRucTipoDoc();
        }else{
           alert_error("Fallo el servidor") ;
        }

         btn.button("reset");
     },'json');

 }

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
        $.post(url_base+"ventas/getUltimasTransacciones",{fini:$("#fechacompro").val()},function (r) {
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

        $("#usuariogenera").prop("disabled",false);
        $("#tipocomprobante").prop("disabled",false);
        var formCom=$("#formNewComprobante").serialize();
        btn.button("loading");
        $.post(url_base+"facturacion/setNewCompro",formCom+"&textRuc="+rucG,function (r) {
            if(r.status){
                alert_success("Se genero el comprobante ");
                // btn.button("reset");
                $("#comprobanteGeneradoIs").html("El comprobante fue generado...");
            }else{
                alert_error("Ocurrio un error");
                btn.button("reset");
                $("#comprobanteGeneradoIs").html("El comprobante NOOOO fue generado...");
            }

            setTimeout(function () {
                $("#modalGenerarComprobante").modal("hide");
                refrescar();
            },'1500')
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
        $.post(url_base+"ventas/getFinalfechaComproForRucTipoDoc",{idempresa:idempresa_,idtipodoc:idtipodoc_},function (r) {
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

                    <td style=" width: 50px;">
                    <input type="hidden" name="iddetallebandejafacturacion[]" value="0" >

                    <input class="form-control" style=" width: 50px;" type="number" name="cantItemCompro[]" value="1"> </td>
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


  $(document).on("click","#btnGenerarExportaReporteFacturacion",function (){
        let fini=$("#fechainirep").val();
        let ffin=$("#fechafinrep").val();

        $.post(url_base+"facturacion/getDataForReportExportaComprobantes",{fini:fini,fend:ffin},function (r){
            $("#tempTablaExcel").html(r);
            tableToExcel("tempTablaExcel","dd")
        },'html');
    });



    var tableToExcel = (function() {
        var uri = 'data:application/vnd.ms-excel;base64,'
            , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
            , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
            , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
        return function(table, name) {
            if (!table.nodeType) table = document.getElementById(table)
            var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
            window.location.href = uri + base64(format(template, ctx))
        }
    })();

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
        <input type="hidden" name="idEditCompro" id="idEditCompro" >
        <input type="hidden" name="isEditCompro" id="isEditCompro" >
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




                    let descItemC=i.descripcion;
                    let cantItem=i.cantidad;
                    let montoItem=i.precio;
                    let subTotalItem=cantItem*  montoItem;
                    console.log(cantItem);


                    var codRefItem=i.moneda+""+parseFloat(i.Totaloperacion).toFixed(2)+""+i.banco+""+montoItem;

                    %>

                    <tr>

                        <td  style=" width: 50px;" >
                            <input type="hidden" name="iddetallebandejafacturacion[]" value="<%=i.iddetallebandejafacturacion%>" >
                            <input class="form-control"   style=" width: 50px;"   type="number"    name="cantItemCompro[]" value="<%=cantItem%>" > </td>
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



<script type="text/template" id="tmpTbodyComprasRealizadas">

    <% _.each(data,function(i,k){ %>
    <tr>

        <td><b><%=arrayMesesFull[i.mes]%></b> </td>
        <td style="text-align: right;"><b><%=Number(i.monto).toFixed(2)%></b></td>
    </tr>
    <% }); %>

</script>

<script type="text/template" id="tmpReporteFacturacin">
    <div class="col-lg-12">

        <table class="table table-condensed table-bordered table-hover">
        <thead>

            <tr>
               <td>Ruc</td>
               <td>Moneda / Tipo Comprobante/ Monto x TC / Acumulado Moneda</td>

            </tr>
        </thead>
        <tbody>
        <% var totCompRep=0;
          _.each(data,function(ii,kk){  %>
        <tr class="trRUC">
            <td  ><%=ii.numruc%>
                <p><b><%=ii.razonsocial%></b> </p>
            </td>
            <td style="padding: 0px; ">
                <table class="table table-condensed table-hover " style="margin-bottom: 0px;">
                   <% _.each(ii.detalle,function(il,kl){ %>
                    <tr>
                        <td>
                            <%=il.moneda%>
                        </td>
                        <td style="padding: 0px; " >
                            <table  class="table table-condensed table-hover " style="margin-bottom: 0px;" >
                                <% var tForMoneda=0
                                    var labelMoneda="";
                                    if(il.moneda == 'USD'){
                                    labelMoneda="$";
                                    }
                                    if(il.moneda == 'PEN'){
                                    labelMoneda="S/";
                                    }
                                 _.each(il.detalle2,function(ii,kk){

                                tForMoneda=tForMoneda+Number(ii.cantprecio);
                                %>
                                <tr>
                                    <td><%=ii.tipocomprobante%></td>
                                    <td style="text-align: right;" ><b><%=labelMoneda%><%= Number(ii.cantprecio)%></b></td>
                                </tr>
                                <%   }); %>
                            </table>

                        </td>
                        <td  style="text-align: right;" >
                            <b><%=labelMoneda%> <%=Number(tForMoneda).toFixed(2)%></b>
                        </td>
                    </tr>

                    <%   }); %>
                </table>
            </td>

        </tr>
        <% }); %>
        </tbody>
            <!--<tfoot>
            <tr>
                <td colspan="2" style="text-align: right">Total (S/)</td>
                <td style="text-align: right;"><b> </b></td>
            </tr>
            </tfoot> -->

        </table>
    </div>

</script>


<script type="text/template" id="tmpReporteFacturacinxx">
    <div class="col-lg-12">
      <table class="table table-bordered table-hover">
          <thead>
          <tr>
              <th>#</th>
              <th>Comprobante</th>
              <th>Monto </th>
              <th>Fecha Registro</th>

          </tr>
          </thead>
          <tbody>
            <% var totalReportFact=0;
               var CantReportFact=0;
               var totalFacturasReporteFact=0;
               var totalBoletasReporteFact=0;
            var CantFacturasReporteFact=0;
            var CantBoletasReporteFact=0;
            _.each(data,function(i,k){
                 totalReportFact=totalReportFact+Number(i.exonerado)
                CantReportFact++;
                switch(i.idtipodoc){
                    case "01": totalFacturasReporteFact=totalFacturasReporteFact+ Number(i.exonerado);
                    CantFacturasReporteFact++;
                    break;
                    case "03":
                     CantBoletasReporteFact++;
                    totalBoletasReporteFact=totalBoletasReporteFact+ Number(i.exonerado);
                    break;
                    default:break;
                 }
            %>
            <tr>
                <td><%=(k+1)%></td>
                <td><%=i.serie+"-"+i.correlativo%></td>
                <td style="text-align: right"><%=Number(i.exonerado).toFixed(2)%></td>
                <td><%=i.fecharegistroorigen %></td>
            </tr>
            <%  }); %>
          </tbody>
          <tfoot>
            <tr>
                <th colspan="2">
                    Total
                </th>
                <th style="text-align: right">
                    <%=Number(totalReportFact).toFixed(2)%>
                </th>
                <th >

                </th>
            </tr>
          </tfoot>
      </table>
    </div>
    <div class="col-lg-6">
        <h3>Resumen Rucs   </h3>
        <table class="table table-bordered table-hover table-condensed">
            <tr>
                <th>*</th>
                <th>Ruc</th>
                <th>Series</th>
            </tr>

            <% _.each(dataR,function(ii,kk){ %>
            <tr>
                <td><%=(kk+1)%></td>
                <td><%=ii.numruc%></td>
                <td>
                    <% var SerieDt=(ii.totalseries).split(",");
                        console.log(SerieDt);
                        $.each(SerieDt,function(si,sk){
                           var ValSerieDt=(sk).split("|");


                           %>


                              <%=sk%><br>


                    <%    });   %>



                </td>
            </tr>
           <% }); %>
        </table>
    </div>
    <div class="col-lg-6">
        <h3>Resumen</h3>
        <table class="table table-bordered table-hover table-condensed">
            <tr>
                <th>*</th>
                <th>Cantidad</th>
                <th>Totales</th>

            </tr>
            <tr>
                <th>Boletas</th>
                <th style="text-align: right" ><%=CantBoletasReporteFact%></th>
                <th style="text-align: right" ><%=Number(totalBoletasReporteFact).toFixed(2)%></th>
            </tr>
            <tr>
                <th>Facturas</th>
                <th style="text-align: right" ><%=CantFacturasReporteFact%></th>
                <th style="text-align: right" ><%=Number(totalFacturasReporteFact).toFixed(2)%></th>
            </tr>
            <tr>
                <th>Totales</th>
                <th style="text-align: right" ><%=CantBoletasReporteFact+CantFacturasReporteFact%></th>
                <th style="text-align: right" ><%=Number(totalBoletasReporteFact+totalFacturasReporteFact).toFixed(2)%></th>
            </tr>
        </table>
    </div>
    <!--<div class="col-lg-6">
        <h3>Compras del
            <select id="selcomprasanios" class="form-control input-sm" style="width: 100px;display: inline-block;font-size: 1.5rem;"  >
                <?php foreach($anioscompras as $ac){ ?>
                    <option value="<?=$ac['anioscompra']?>"  ><?=$ac['anioscompra']?> </option>
                <?php }?>

            </select>
        </h3>
        <table class="table table-bordered table-hover table-condensed">
            <thead>
                <tr>

                    <th>Mes</th>
                    <th>Totales</th>

                </tr>
            </thead>
            <tbody id="tbodyComprasAnios">

            </tbody>
        </table>
    </div> -->
</script>


<div class="modal fade" id="modalFacturacion" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title">Reporte Facturación </h4>
            </div>

            <!--Modal body-->
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label text-bold ">Fecha Inicio</label>
                            <input type="date" id="fechainirep" class="form-control" value="<?= date("Y-m-01") ?>" >
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label text-bold ">Fecha Fin</label>
                            <input type="date" id="fechafinrep"  class="form-control" value="<?= date("Y-m-d") ?>" >
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                           <label class="control-label" style="visibility: hidden">_____________________________________</label>
                             <button style="display: inline-block" id="btnGenerarReporteFacturacion"  type="button" class="btn btn-mint btn-icon">Generar</button>
                            <button style="display: inline-block" id="btnGenerarExportaReporteFacturacion"  type="button" class="btn btn-purple btn-icon">Exportar Detalle</button>

                        </div>
                    </div>
                </div>
                <div class="row" id="divReporteFact">

                </div>

            </div>

            <!--Modal footer-->
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<script type="text/template" id="tmpDetailVentaEdit">
   <% _.each(data,function(i,k){
    var colorlabxxxxx="";
    var colorTrX=""
    var cantBoleteoxD=0;
    if(parseFloat(Number(i.isboleteo))!=1){
    colorTrX="background-color:#ff000033;";
    }else if(i.isforcambiobolfact == 1){
    colorTrX="background-color:#3c363633";

    }

    %>
    <tr  style="<%=colorTrX%>"  >
        <td style="padding: 2px;" >
            <%
             var __cantBolFact=0;
            var __cantBolReal=0;

            var __BolFactIsHabilitado="";
            var __BolRealIsHabilitado="";

            if(parseFloat(Number(i.isboleteo))!=1 ){
            __cantBolReal=i.cantidad;
            __cantBolFact=0;

            __BolRealIsHabilitado="";
            __BolFactIsHabilitado='readonly="readonly"';

            }else if(i.isforcambiobolfact == 1){
            __cantBolReal=0;
            __cantBolFact=i.cantbolfact;

            __BolRealIsHabilitado='readonly="readonly"' ;
            __BolFactIsHabilitado="";

             }else {
            __cantBolReal=i.cantidad;
            __cantBolFact=i.cantbolfact;

            __BolRealIsHabilitado="";
            __BolFactIsHabilitado='readonly="readonly"';
             }  %>

            <input <%=__BolRealIsHabilitado%> style="text-align: right;width: 60px;display: inline-block;padding-left: 1px;padding-right: 1px;"   class="form-control " type="text" name="cantidadc[]" value="<%=__cantBolReal%>" >
            <input <%=__BolFactIsHabilitado%>  style="text-align: right;width: 60px;display: inline-block;padding-left: 1px;padding-right: 1px;"  class="form-control " type="text" name="cantidadcBF[]" value="<%=__cantBolFact%>">


        </td>

        <td style="padding: 2px;" ><%=i.descripcion +" "+i.modelo +" "+i.marca +" "+i.ncat +"|"+i.um +" Lote:"+i.lote  %>
            <input style="text-align: right"  type="hidden" name="productoventa[]" value="<%=i.idproducto%>">
            <input style="text-align: right"  type="hidden" name="lote[]" value="<%=i.lote%>">
            <input style="text-align: right"  type="hidden" name="prodisboleteo[]" value="<%=i.isboleteo%>">
            <input style="text-align: right"  type="hidden" name="prodisforcambiobf[]" value="<%=i.isforcambiobolfact%>">
            <input type="hidden" name="isForQuitar[]" value="0">

        </td>
        <td style="padding: 2px;" > <%=i.um%>
            <input type="hidden" name="iddetventa[]" value="<%=i.iddetventa%>">
        </td>
        <td style="padding: 2px;" >
            <input style="text-align: right;width: 100px;" class="form-control " type="text" name="pventa[]" value="<%=i.precioventa%>">
        </td>
        <td style="padding: 2px;" >
            <input style="text-align: right;width: 100px;" class="form-control " type="text" name="pventaf[]" value="<%=i.precioventaf%>">
        </td>
        <td style="padding: 2px;" >
            <% if(parseFloat(Number(i.isboleteo))!=1 ){  %>

            <input type="text" class="form-control " style="width: 100px;text-align: right;display: inline-block;" readonly="readonly"  name="ssubtotal[]" value="<%=(parseFloat(Number(i.cantidad))*parseFloat(Number(i.precioventa)))%>">
            <input type="text" class="form-control " style="width: 100px;text-align: right;display: inline-block;" readonly="readonly"  name="ssubtotalBF[]" value="0">

            <% } else if(i.isforcambiobolfact == 1){  %>
            <input type="text" class="form-control " style="width: 100px;text-align: right;display: inline-block;" readonly="readonly"  name="ssubtotal[]" value="0">
            <input type="text" class="form-control " style="width: 100px;text-align: right;display: inline-block;" readonly="readonly"  name="ssubtotalBF[]" value="<%=(parseFloat(Number(i.cantbolfact))*parseFloat(Number(i.precioventa)))%>">

            <% }else {  %>
            <input type="text" class="form-control " style="width: 100px;text-align: right;display: inline-block;" readonly="readonly"  name="ssubtotal[]" value="<%=(parseFloat(Number(i.cantidad))*parseFloat(Number(i.precioventa)))%>">
            <input type="text" class="form-control " style="width: 100px;text-align: right;display: inline-block;" readonly="readonly"  name="ssubtotalBF[]" value="<%=(parseFloat(Number(i.cantidad))*parseFloat(Number(i.precioventa)))%>">

            <% }  %>
        </td>
        <td style="padding: 2px;" ><button class="btn btn-danger btn-xs" type="button"  onclick="EditarItem(this)" >Editar</button></td>
    </tr>
    <%  }); %>


</script>

<script type="text/template" id="tmpDivNotaFact">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel">
                <form id="formNotaCredito">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-9">
                                <b>*Comprobantes que fueron aceptados hace más de 15 días,no pueden ser emitidos como Notas </b>
                                <hr>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Correlativo</label>
                                    <input readonly="readonly" type="text" id="ncorrelativo" name="ncorrelativo" class="form-control" >
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group" style="margin-bottom: 1px;" >
                                    <label class="control-label" style="font-weight: bold;">Tipo Nota de crédito</label>
                                     <select class="form-control" id="nttiponotacredito" name="nttiponotacredito" style="font-weight: bold;">
                                        <?php foreach($tiponotacredito as $i){
                                            if($i["idtiponotacredito"] == "01" ){   ?>
                                                <option value="<?=$i["idtiponotacredito"]?>"><?=$i["descripcion"]?></option>

                                                <?php
                                             }

                                            } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group" style="margin-bottom: 1px;" >
                                    <label class="control-label">Factura/Boleta de Referencia</label>
                                    <input style=" " type="text" id="ntcomprobante" name="ntcomprobante" class="form-control">
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group" style="margin-bottom: 1px;" >
                                    <label class="control-label">F.Recepción Sunat</label>
                                    <input type="text" name="ntfecharecepcion" id="ntfecharecepcion" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group" style="margin-bottom: 1px;" >
                                    <label class="control-label">Días Transcurridos desde la recepción</label>
                                    <input type="text" readonly="readonly"   name="ntDiasTrans" id="ntDiasTrans" class="form-control text-center" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group" style="margin-bottom: 1px;"  >
                                    <label class="control-label"><b>Descripción Motivo</b></label>
                                    <input type="text" name="ntmotivo" id="ntmotivo" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <hr style="border-color: #4c42429c;margin-bottom: 12px;margin-top: 10px; ">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group" style="margin-bottom: 1px;" >
                                    <label class="control-label">Nombre /Razón Social</label>
                                    <input type="text" name="ntcliente" id="ntcliente" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group" style="margin-bottom: 1px;" >
                                    <label class="control-label">Documento</label><br>
                                    <select  class="form-control" id="ntseldoc" name="ntseldoc" style="display:inline-block;width: 20%">
                                        <option value="1" >DNI</option>
                                        <option value="6">RUC</option>
                                    </select>
                                    <input style="width: 75%;display:inline-block;" type="text" name="ntdoc" id="ntdoc" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group" style="margin-bottom: 1px;">
                                    <label class="control-label">Fecha venta</label>
                                    <input type="text" readonly="readonly" name="ntfventa" id="ntfventa" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group" style="margin-bottom: 1px;" >
                                    <label class="control-label">Fecha Nota Crédito</label>
                                    <input type="date" readonly="readonly"  value="<?=date("Y-m-d")?>" name="ntfguia" id="ntfguia" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group" style="margin-bottom: 1px;" >
                                    <label class="control-label">Dirección</label>
                                    <input type="text" name="ntdircliente" id="ntdircliente" class="form-control input-sm">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group" style="margin-bottom: 1px;" >
                                    <label class="control-label">Tipo Doc. Ref</label>
                                    <input type="text" readonly="readonly"  name="nttipodocref" id="nttipodocref" class="form-control">
                                    <input type="hidden" name="nttipocomprobante" id="nttipocomprobante">
                                    <input type="hidden" name="ntidreferenciaventa" id="ntidreferenciaventa">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group" style="margin-bottom: 1px;" >
                                    <label class="control-label">Documento Ref.</label>
                                    <input type="text"  readonly="readonly" name="ntdocref" id="ntdocref" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table table-responsive">
                                   <table class="table table-bordered  table-hover">
                                       <thead>
                                       <tr>
                                           <th title="Cantidad Producto Venta/Cantidad Boleta/factura">Cant.V/Cant B/F</th>
                                           <th>Producto</th>
                                           <th>Und.</th>
                                           <th>PV</th>
                                           <th>PVF</th>
                                           <th>SubTotal | Subtotal BF</th>
                                           <th>*</th>
                                       </tr>
                                       </thead>
                                       <tbody id="detNotaDetVenta">

                                       </tbody>
                                       <tfoot>
                                       <tr style="text-align: right" >
                                           <th colspan="5" style="text-align: right" >Sub Total</th>
                                           <th style="padding-top: 0px;padding-bottom: 0px;" >
                                               <input readonly="readonly"  type="text" class="form-control" id="ssbtotalxy" name="ssbtotalxy" style="width: 100px;text-align: right;display: inline-block;"  >
                                               <input readonly="readonly"  type="text" class="form-control" id="ssbtotalxyBF" name="ssbtotalxyBF" style="width: 100px;text-align: right;display: inline-block;"  >

                                           </th>
                                           <th>*</th>
                                       </tr>
                                       <tr style="text-align: right" >
                                           <th colspan="5" style="text-align: right" >I.G.V <input type="text" id="igv" name="igv" style="text-align:right;width: 50px;" value="0" > % </th>
                                           <th style="padding-top: 0px;padding-bottom: 0px;"  ><input  readonly="readonly" type="text" class="form-control" id="ssbtotalxyIGV" style="width: 100px;text-align: right;" ></th>
                                           <th>*</th>
                                       </tr>
                                       <tr style="text-align: right" >
                                           <th colspan="5" style="text-align: right" >Total</th>
                                           <th style=" padding-top: 0px;padding-bottom: 0px;"  >
                                               <input readonly="readonly" type="text" class="form-control " style="display:inline-block;width: 100px;text-align: right;" id="totalcompra" name="totalcompra">
                                               <input readonly="readonly" type="text" class="form-control " style="display:inline-block;width: 100px;text-align: right;" id="totalcompraBF" name="totalcompraBF">

                                           </th>

                                           <th>*</th>
                                       </tr>
                                       </tfoot>
                                   </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button  type="button" id="btnSaveNotaC" class="btn btn-mint">Guardar NOTA</button>
                    </div>
                </form>
                <!--===================================================-->
                <!--End Block Styled Form -->

            </div>
        </div>
    </div>

</script>
<script type="text/template" id="tmpBodyComBaja">
    <div class="row">
        <div class="col-lg-12" >
            <form id="formComBaja">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Fecha Generación</label>
                                <input type="date" id="fgene" name="fgene" class="form-control input-sm" value="<?=date("Y-m-d")?>" >
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Fecha Comunicación </label>
                                <input type="date" id="fcom" name="fcom" class="form-control input-sm" value="<?=date("Y-m-d")?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Factura/boleta</label>
                                <input type="text" id="inSearchBolFact" name="inSearchBolFact"  class="form-control">
                                <input type="hidden" id="idbandejafactx" name="idbandejafactx" VALUE="">
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label class="control-label">Motivo de baja</label>
                                <input type="text" id="motBaja" name="motBaja" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <label class="control-label" style="visibility: hidden;">Website</label>
                                <button type="button" class="btn btn-sm btn-warning" id="btnAddInDetMotBaja" ><i class="fa fa-arrow-circle-o-down"></i> Agregar</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" >
                            <table class="table table-bordered table-condensed " id="tableComBaja">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Comprobante</th>
                                    <th>Descripción</th>
                                    <th>*</th>
                                </tr>
                                </thead>
                                <tbody id="tbodyComBaja">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-right">
                    <button class="btn btn-success" type="button" id="btnSendForm">Comunicar Baja/s</button>
                </div>
            </form>
        </div>
    </div>
</script>



<div class="modal fade" id="modalID" role="dialog" tabindex="-1"    aria-hidden="true" >
    <div class="modal-dialog modal-lg"  >
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close"  data-dismiss="modal"  ><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="">Directorio de archivos-Facturación</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="">
                <div class="row">

                    <div class="col-lg-12 table-responsive" >
                        <table class="table table-condensed table-bordered table-hover" id="tableLisDirFact" >
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Archivo</th>
                                <th>Fecha</th>
                                <th>*</th>
                            </tr>
                            </thead>
                            <tbody id="idTbodyListFilesDir">

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="modalIDMulti" role="dialog" tabindex="-1"    aria-hidden="true" >
    <div class="modal-dialog modal-lg" id="DivModalDialog" >
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close"  data-dismiss="modal"  ><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="titleModalMulti"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="bodyModalMulti" style="padding-top: 0px;">
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalGenerarComprobante" role="dialog" tabindex="-1"  data-backdrop="static"   aria-hidden="true" >
    <div class="modal-dialog modal-lg" style="width: 80%;"  >
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="ModalTitleGenerarComprobante"> Generar comprobante</h4>
            </div>

            <!--Modal body-->
            <div class="modal-body" id="modalBodyGenerarComprobante" style="padding-top: 0px;">
                <h3>Cargando...</h3>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalTicket" role="dialog" tabindex="-1"  data-backdrop="static"   aria-hidden="true" >
    <div class="modal-dialog modal-lg" style="width: 80%;"  >
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="ModalTitleTicket">Consulta ticket</h4>
            </div>

            <!--Modal body-->
            <div class="modal-body" id="modalBodyTicket" style="padding-top: 0px;">
                <h3>Cargando...</h3>
            </div>
        </div>
    </div>
</div>

<script type="text/template" id="tmpBodyTicket">
    <% console.log(data); %>
    <div class="row">
        <div class="col-lg-12">
            <form id="formTicket">
            <div class="form-group">
                <label class="control-label text-bold" >Codigo</label>
                <p style="margin-bottom: 2px;" ><%=data.data.rsCodigo%></p>
            </div>
            <div class="form-group">
                <label class="control-label text-bold ">Descripción</label>
                <p style="margin-bottom: 2px;" ><%=data.data.rsDescripcion%></p>
            </div>
                <div class="form-group">
                    <label class="control-label text-bold ">Detalle</label>
                    <p style="margin-bottom: 2px;" ><b>Id:</b> <%=data.data.cdrResponse.id%></p>
                    <p style="margin-bottom: 2px;" ><b>Cod:</b> <%=data.data.cdrResponse.code%></p>
                    <p style="margin-bottom: 2px;" ><b>Desc:</b> <%=data.data.cdrResponse.description%></p>
                    <p style="margin-bottom: 2px;" ><b>Notas:</b> <%=data.data.cdrResponse.notes%></p>
                    <a target="_blank" class="btn-link" href="<?=base_url()?>filesfact/comprobantes/<%=data.data.rsFilename%>">Descargar archivo</a>

                </div>

            </form>
        </div>
    </div>
</script>