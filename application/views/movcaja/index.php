<style>

    .tdEgreso{
        font-weight: bold;
        color: red;
        text-align: right;
    }
    .tdIngreso{
        font-weight: bold;
        color: darkgreen;
        text-align: right;
    }
    .tdInversion{
        font-weight: bold;
        color: #cc8900;
        text-align: right;
    }
</style>
<div id="page-content">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel">
                <div class="panel-heading">

                    <div class="panel-control">
                        <ul class="pager pager-rounded">
                            <li><a href="javascript:void(0)" onclick="newReporteCaja()" >Reporte</a></li>
                        </ul>
                        <button class="demo-panel-ref-btn btn btn-default" data-toggle="panel-overlay" onclick="refrescar();"  ><i class="demo-psi-repeat-2"></i></button>
                    </div>
                    <h3 class="panel-title"  ><b style="font-size: 18px;"><i class="fa fa-dollar"></i>  Moviemientos de caja</b>  &nbsp; <button class="btn btn-mint btn-sm" id="btnAddNew">Nuevo+</button> </h3>
                    <hr style="margin-bottom: 0px;margin-top: 0px;">
                </div>

                <!--Data Table-->

                <!--===================================================-->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-6" >
                            <div class="col-xs-3"></div>
                            <div class="col-xs-9 text-sm">
                                <table class="table">
                                    <thead>
                                    <tr>

                                        <th title="Saldo x Moneda" style="display: inline-block;text-align: right;" ><span> <b  style="visibility: hidden" >____________________________________________________</b> Saldos  </span></th>
                                        <th><b style="visibility: hidden" >_________<b></th>
                                        <th><b  style="visibility: hidden" >__________________</b></th>
                                        <th>Soles (S/)</th>
                                        <th>Dolares ($) </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>

                                        <th style="font-size:15px;text-align: right" >  <b>S/</b><b  id="spanTotalSaldoSoles" >0</b>  </th>
                                        <th>  </th>
                                        <th>
                                            <span class="labellabel-success ">
                                                <i style="color: green;"  class="pci-caret-up text-success"></i>
                                                <smal>Ingreso</smal>
					                        </span>

                                        </th>

                                        <th style="text-align: right" >  <span class="text-lg" style="color: darkgreen"  id="spanTotalIngresoSoles">0</span></th>
                                        <th  style="text-align: right" >  <span class="text-lg" style="color: darkgreen"  id="spanTotalIngresoDolares">0</span></th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: right;font-size:15px;border-bottom: 2px solid black;">
                                            <input type="text" id="tasacambioXsaldo" value="3.29" style="width: 40px;display: inline-block ;text-align: right;" >    <b style="display: inline-block" >x $</b><b style="display: inline-block"  id="spanTotalSaldoDolares" >0</b> <b style="display: inline-block;" id="monedaCambiada" ></b> </th>
                                        <th>  </th>
                                        <th><span class="labellabel-success ">
                                                <i style="color: saddlebrown;"  class="pci-caret-right text-success"></i>
                                                <smal>Inversion </smal>
					                        </span> </th>

                                        <th  style="text-align: right" >  <span class="text-lg" style="color: saddlebrown"  id="spanTotalInvertidoSoles">0</span></th>
                                        <th  style="text-align: right " >  <span class="text-lg" style="color: saddlebrown"  id="spanTotalInvertidoDolares">0</span></th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: right;font-size:22px;"><b>S/ <b id="saldoTotalXMonedaSoles"></b> </b>  </th>
                                        <th> </th>
                                        <th><span class="labellabel-success ">
                                                <i style="color: red;" class="pci-caret-down text-success"></i>
                                                <smal>Egresos </smal>
					                        </span> </th>
                                        <th  style="text-align: right" >  <span class="text-lg" style="color: red"  id="spanTotalEgresosSoles">0</span></th>
                                        <th  style="text-align: right" >  <span class="text-lg" style="color: red"  id="spanTotalEgresosDolares">0</span></th>
                                    </tr>
                                    </tbody>
                                </table>


                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <table id="dtTable" class="table table-bordered table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>*</th>
                                <th>Movimiento</th>
                                <th>Descripción</th>
                                <th>Fecha</th>
                                <th>Ingreso</th>
                                <th>Inversión</th>
                                <th>Egreso</th>
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
<script src="<?=base_url()?>assets/scripts/highcharts/highcharts.js"></script>


<script type="text/javascript">
    var dtTable;
    $(document).ready(function() {
        dtIni();
        getTotalMovs();

    });

    function newReporteCaja(){
        $("#modalIdReporte").modal("show");
    }


    $(document).on("keyup","#tasacambioXsaldo",function () {
      var vl=$(this).val();
      var spanTotalSaldoDolares=$("#spanTotalSaldoDolares").html();
      var spanTotalSaldoSoles=$("#spanTotalSaldoSoles").html();

      var saldoTotalXMonedaSoles=$("#saldoTotalXMonedaSoles");
      var monedaCambiada=$("#monedaCambiada");
      var vlmonedaCambiada=(parseFloat(spanTotalSaldoDolares)*parseFloat(vl)).toFixed(2);
        monedaCambiada.html(vlmonedaCambiada);
      var xx=parseFloat(spanTotalSaldoSoles) + parseFloat(vlmonedaCambiada);
        saldoTotalXMonedaSoles.html(xx);
    });
    function getTotalMovs() {
        $.post(url_base+"movcaja/getTotalMovs",function (data) {
            if(data.length > 0){
                var ingresoSoles=0;
                var egresoSoles=0;
                var inversionSoles=0;
                var ingresoDolares=0;
                var egresoDolares=0;
                var inversionDolares=0;
                var saldoSoles=0;
                var saldoDolares=0;
                $.each(data,function(k,i){
                    var wmoneda="";
                    if(i.nmoneda == "Soles") {
                        wmoneda="Soles";
                        ingresoSoles=parseFloat(i.Ingreso);
                        egresoSoles=parseFloat(i.Egreso);
                        inversionSoles=parseFloat(i.Inversion);
                        $("#spanTotalIngreso"+wmoneda).html(ingresoSoles);
                        $("#spanTotalInvertido"+wmoneda).html(inversionSoles);
                        $("#spanTotalEgresos"+wmoneda).html(egresoSoles);
                    }else if(i.nmoneda == "Dolares"){
                        wmoneda="Dolares";
                        ingresoDolares=parseFloat(i.Ingreso);
                        egresoDolares=parseFloat(i.Egreso);
                        inversionDolares=parseFloat(i.Inversion);
                        $("#spanTotalIngreso"+wmoneda).html(ingresoDolares);
                        $("#spanTotalInvertido"+wmoneda).html(inversionDolares);
                        $("#spanTotalEgresos"+wmoneda).html(egresoDolares);
                    }
                });
                saldoSoles=ingresoSoles-egresoSoles;
                saldoDolares=ingresoDolares-egresoDolares;
                var ts=$("#tasacambioXsaldo").val();
                var totalDolarEnsoles=parseFloat(saldoDolares)*parseFloat(ts);

                totalDolarEnsoles=totalDolarEnsoles.toFixed(2);
                $("#monedaCambiada").html(" = S/"+totalDolarEnsoles);
                var totaltotalxxxx=parseFloat(saldoSoles)+parseFloat(totalDolarEnsoles);

                $("#saldoTotalXMonedaSoles").html(totaltotalxxxx);
                $("#spanTotalSaldoSoles").html(saldoSoles);
                $("#spanTotalSaldoDolares").html(saldoDolares);
            }
        },'json');
    }

    function dtIni(){
        dtTable=$('#dtTable').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url(); ?>movcaja/getData",
                "type": "POST"
            },
            "buttons": [
                'pdf', 'print', 'excel', 'copy', 'csv',
            ],
            "columns": [
                { "data": null,"searchable":false },
                { "data": "ntipoconceptocaja" },
                { "data": "nconceptocaja" },
                { "data": "descmovcaja" },
                { "data": "fecharegistro" },
                { "data": "ingreso","className": "tdIngreso"  },
                { "data": "inversion","className": "tdInversion"  },
                { "data": "egreso","className": "tdEgreso" },
                {   "sortable": false,
                    "searchable":false,
                    "render": function ( data, type, full, meta ) {
                        var id = full.idmovimientoscaja;

                        var html="";
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

        console.log(dtTable);
    }

    function refrescar () {
        dtTable.ajax.reload();
    }

    $(document).on("click","#btnAddNew",function () {
        $("#modalId").modal("show");
        $("#hModalTitle").html("Registrar Movimiento de Caja");
        var tmpForm=_.template($("#tmpForm").html());
        $("#bModalBody").html(tmpForm);

    });


    $(document).on("click","#btnSaveForm",function () {
        var form=$("#formRegData").serialize();
        $.post(url_base+"movcaja/setForm",form,function (data) {
            if(data.status == "ok"){
              refrescar();
                alert_success("Correcto");
                $("#modalId").modal("hide");
            }else{
                alert_success("Fallo");
            }
            getTotalMovs();
        },'json');
    });

    function eliminar(id) {
        if(!confirm("¿Seguro de eliminar este registro?")){
            return 0;
        }
        $.post(url_base+"movcaja/delete",{"id":id},function (data) {
            if(data.status == "ok"){
                refrescar();
                alert_success("Correcto");
            }else{
                alert_success("Fallo");
            }
            getTotalMovs();
        },'json');
    }





    function editar(id,data) {

        $("#modalId").modal("show");
        $("#hModalTitle").html("Editar Movimiento de caja");
        var tmpForm=_.template($("#tmpForm").html());
        $("#bModalBody").html(tmpForm);

        $("#isEdit").val(1);
        $("#idEdit").val(data.idmovimientoscaja);


        $("#tconcepto").val(data.idtipoconceptocaja);

        $("#monto").val(data.monto);
        $("#moneda").val(data.idmoneda);
        getSelConceptoCaja(data.idtipoconceptocaja,data.idconceptocaja);
        $("#descripcion").val(data.descmovcaja);
        $("#fecharegistro").val(formatDateYMD(data.fecharegistro));

    }

    $(document).on("change","#tconcepto",function () {
        var vl=$(this).val();

        getSelConceptoCaja(vl,null);
    });

    function getSelConceptoCaja(id,idtipoconcepto) {
        var tmpSelConcepto=_.template($("#tmpSelConcepto").html());
        $.post(url_base+"movcaja/getConceptoCaja",{id:id},function (data) {
            if(data.status =="ok"){
                $("#divSelConceptoCaja").html(tmpSelConcepto({data:data.data,idtpconcepto:id}));
                if(idtipoconcepto){
                    $("#concepto").val(idtipoconcepto);
                }

            }
        },'json');
    }

    $(document).on("keyup","#monto",function () {
        var tcc=$("#tconcepto").val();
        if(tcc == 1){
          $(this).css({"color":"green"});
        }else if(tcc == 2){
            $(this).css({"color":"red"});
        }else if(tcc == 3){
            $(this).css({"color":"#ab6f16"});
        }
    });

    $(document).on("click","#btnAddNewConcepto",function () {
        $("#modalIdNewForm").modal("show");
        var tmpNewFormADD=_.template($("#tmpNewFormADD").html());
        $("#bModalBodyNewForm").html(tmpNewFormADD);
        var tconcepto=$("#tconcepto").val();
        $("#ttconcepto").val(tconcepto);
    });
    $(document).on("click","#btnSaveFormNewForm",function () {
        var form=$("#formRegNewForm").serialize();
        $.post(url_base+"movcaja/setConceptoCaja",form,function(data){
            if(data.status=="ok"){
                if(data.data.length>0){
                    let $option = $('<option />', {
                        text: data.data[0].nombre,
                        value: data.data[0].idconceptocaja
                    });
                    $('#concepto').prepend($option);
                    $("#concepto").val(data.data[0].idconceptocaja);
                    $("#modalIdNewForm").modal("hide");
                    $("#bModalBodyNewForm").html("");
                }

            }
        },'json');

    });

    var dataReporteBySoles=[];
    var dataReporteByDolares=[];
    function generarReporteCaja(id) {

        var fechaini=$("#dtfechaventaini").val();
        var fechaend=$("#dtfechaventaend").val();
        var tmpReporteCaja=_.template($("#tmpReporteCaja").html());

         dataReporteBySoles=[];
         dataReporteByDolares=[];
        $.post(url_base+"movcaja/reporte",{tipo:"d",fechaini:fechaini,fechaend:fechaend},function(data){
            console.log(data);
            if(data.status == "ok"){

                $("#divResultReport").html(tmpReporteCaja({data:data.data}));

                getChartPieXXX();
            }else{
                alert_error("fallo");
            }

        },'json');
    }

    $(document).on("change","#dtfechaventaini",function () {
        $("#dtfechaventaend").val($(this).val());
    });

    var optionsChartPieXXX;


    function getChartPieXXX() {

        iniChartPieXXX();
        optionsChartPieXXX.series[0].data =dataReporteBySoles;
        var chartxxasd = new Highcharts.chart(optionsChartPieXXX);

       // var tmptbodyDistribucionVacunos=_.template($("#tmptbodyDistribucionVacunos").html());

        /*$.post(url_base+"admin/getChartPiePoblacionVacas",function (data) {

            $("#tbodyDistribucionVacunos").html(tmptbodyDistribucionVacunos({datax12:data}));

            optionsChartPieXXX.series[0].data =data;
            var chartxxasd = new Highcharts.chart(optionsChartPieXXX);

        },'json');
        */
    }

    function iniChartPieXXX(){
        optionsChartPieXXX = {
            chart: {
                renderTo: 'chartPieXTipo',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Mov caja'
            },
            tooltip: {
                pointFormat: '{series.name}: S/{point.y}/<b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b> <b> S/ {point.y}</b> : {point.percentage:.2f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                name: '.',
                colorByPoint: true,
                point:{
                    events:{
                        click: function (event) {
                            var xnam=this.name;
                            verDetalleXTipoConcepto(xnam);
                            ;

                        }
                    }
                }
            }]
        }
    }

function verDetalleXTipoConcepto(tipo) {
    var fechaini=$("#dtfechaventaini").val();
    var fechaend=$("#dtfechaventaend").val();
    $.post(url_base+"movcaja/getReportPieByTipoConcepto",{fechaini:fechaini,fechaend:fechaend,tipo:tipo,tasacambio:$("#valTasaCambio").val()},function (data) {
            if(data.status =="ok"){
                optionsChartPieXXX.series[0].data = data.data;
                var chartxxasd = new Highcharts.chart(optionsChartPieXXX);

                chartxxasd.setTitle({ text:tipo+' <a href="javascript:void(0)" style="font-size: 9px;" onclick="getChartPieXXX()" class="btn-link"  >Atrás</a>',useHTML:true});
            }
        },'json');

}

$(document).on("keyup","#valTasaCambio",function () {
    var vltasa=parseFloat($(this).val());
    var xttDolaresI=$("#ttDolaresI").html();
    xttDolaresI=parseFloat(xttDolaresI.substring(1));
    var xttDolaresInv=$("#ttDolaresInv").html();
    xttDolaresInv=parseFloat(xttDolaresInv.substring(1));
    var xttDolaresE=$("#ttDolaresE").html();
    xttDolaresE=parseFloat(xttDolaresE.substring(1));

    var xttDolaresInSolesI=xttDolaresI*vltasa;
    var xttDolaresInSolesInv=xttDolaresInv*vltasa;
    var xttDolaresInSolesE=xttDolaresE*vltasa;
    $("#ttDolaresInSolesI").html("S/"+xttDolaresInSolesI);
    $("#ttDolaresInSolesInv").html("S/"+xttDolaresInSolesInv);
    $("#ttDolaresInSolesE").html("S/"+xttDolaresInSolesE);

   var ttIngresoSoles=parseFloat($("#ttIngresoSoles").val());
   var ttInversionSoles=parseFloat($("#ttInversionSoles").val());
   var ttEgresoSoles=parseFloat($("#ttEgresoSoles").val());

    var tFinalI=ttIngresoSoles+xttDolaresInSolesI;
    var tFinalInv=ttInversionSoles+xttDolaresInSolesInv;
    var tFinalE=ttEgresoSoles+xttDolaresInSolesE;

   $("#tFinalI").html("S/"+tFinalI);
   $("#tFinalInv").html("S/"+tFinalInv);
   $("#tFinalE").html("S/"+tFinalE);
    $("#Ingreso-Egreso").html("S/"+(tFinalI-tFinalE) );
    dataReporteBySoles=[
        {
            "name":"Ingreso",
            "y":parseFloat(tFinalI)
        },
        {
            "name":"Inversion",
            "y":parseFloat(tFinalInv)
        },
        {
            "name":"Egreso",
            "y":parseFloat(tFinalE)
        }];
});

    $(document).on("change","#valTasaCambio",function () {
        getChartPieXXX();
            });

</script>

<script type="text/template" id="tmpReporteCaja">

    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>*</th>
                <th>Tipo</th>
                <th>Mov</th>
                <th>Desc</th>
                <th>Fecha</th>
                <th style="text-align: right;">Ingreso</th>
                <th style="text-align: right;">Egreso</th>
                <th style="text-align: right;">Salida</th>
            </tr>
        </thead>
        <tbody>
        <%  var ttIngresoSoles=0;
            var ttIngresoDolares=0;

            var ttEgresoSoles=0;
            var ttEgresoDolares=0;

             var ttInversionSoles=0;
            var ttInversionDolares=0;

        _.each(data,function(i,k){
        if(i.nmoneda =="Soles" ){
        var ig= i.ingreso;
        if(ig != 0){
        ig=parseFloat(ig.substring(2));
        }else{
        ig=parseFloat(Number(ig));
        }
        var eg= i.egreso;
        if(eg != 0){
        eg=parseFloat(eg.substring(2));
        }else{
        eg=parseFloat(Number(eg));
        }

        var inv=i.inversion;
        if(inv != 0 ){
        inv=parseFloat(inv.substring(2));
        }else{
        inv=parseFloat(Number(inv));
        }

        ttIngresoSoles=ttIngresoSoles+parseFloat(Number(ig));
        ttEgresoSoles=ttEgresoSoles+parseFloat(Number(eg));
        ttInversionSoles=ttInversionSoles+parseFloat(Number(inv));

        }else if(i.nmoneda =="Dolares"){


        var ig= i.ingreso;
        if(ig != 0){
        ig=parseFloat(ig.substring(1));
        }else{
        ig=parseFloat(Number(ig));
        }
        var eg= i.egreso;
        if(eg != 0){
        eg=parseFloat(eg.substring(1));
        }else{
        eg=parseFloat(Number(eg));
        }
        var inv=i.inversion;

        if(inv != 0 ){
        inv=parseFloat(inv.substring(1));
        }else{
        inv=parseFloat(Number(inv));
        }

        ttIngresoDolares=ttIngresoDolares+parseFloat(Number(ig));
        ttEgresoDolares=ttEgresoDolares+parseFloat(Number(eg));
        ttInversionDolares=ttInversionDolares+parseFloat(Number(inv));

        }

        %>
        <tr>
            <td>*</td>
            <td><%=i.ntipoconceptocaja%></td>
            <td><%=i.nconceptocaja%></td>
            <td><%=i.descmovcaja%></td>
            <td><%=i.fecharegistro%></td>
            <td class="tdIngreso" ><%=i.ingreso%></td>
            <td class="tdInversion" ><%=i.inversion%></td>
            <td class="tdEgreso" ><%=i.egreso%></td>
        </tr>
        <% });





        var valTasaCambio=3.30;
        var ttDolaresInSolesI=0;
        var ttDolaresInSolesInv=0;
        var ttDolaresInSolesE=0;

        var ttFinalInSolesI=0;
        var ttFinalInSolesInv=0;
        var ttFinalInSolesE=0;

        %>
        </tbody>
        <tfoot style="border-top: 2px solid black;" >
        <tr>
            <td>*</td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td>Total S/</td>
            <td style="text-align: right;font-weight: bold; " >
                <input type="hidden" id="ttIngresoSoles" value="<%=ttIngresoSoles%>" >
                <input type="hidden" id="ttInversionSoles" value="<%=ttInversionSoles%>" >
                <input type="hidden" id="ttEgresoSoles" value="<%=ttEgresoSoles%>" >
                S/<%=ttIngresoSoles%></td>
            <td style="text-align: right;font-weight: bold; " >S/<%=ttInversionSoles%></td>
            <td style="text-align: right;font-weight: bold; " >S/<%=ttEgresoSoles%></td>
        </tr>
        <tr>
            <td>*</td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td>Total $ <input type="text" id="valTasaCambio" value="<%=valTasaCambio%>" >  </td>
            <td style="text-align: right;font-weight: bold; " >
                <p id="ttDolaresI" style="margin-bottom: 2px;" >$<%=ttIngresoDolares%></p>
                <p id="ttDolaresInSolesI" style="margin-bottom: 0px;" >S/<%=ttDolaresInSolesI=parseFloat((ttIngresoDolares * valTasaCambio)).toFixed(2) %></p>
            </td>
            <td style="text-align: right;font-weight: bold; " >
                <p id="ttDolaresInv" style="margin-bottom: 2px;" >$<%=ttInversionDolares%></p>
                <p id="ttDolaresInSolesInv" style="margin-bottom: 0px;" >S/<%= ttDolaresInSolesInv=parseFloat((ttInversionDolares * valTasaCambio)).toFixed(2)%></p>
            </td>
            <td style="text-align: right;font-weight: bold; " >
                <p id="ttDolaresE" style="margin-bottom: 2px;" >$<%=ttEgresoDolares%></p>
                <p id="ttDolaresInSolesE" style="margin-bottom: 0px;" >S/<%=ttDolaresInSolesE=parseFloat((ttEgresoDolares* valTasaCambio)).toFixed(2) %></p>
            </td>
        </tr>
        <tr>
            <td>*</td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td>Total en S/   </td>
            <td style="text-align: right;font-weight: bold; " id="tFinalI" >S/<%=ttFinalInSolesI= parseFloat(ttIngresoSoles) + parseFloat(ttDolaresInSolesI) %></td>
            <td style="text-align: right;font-weight: bold; " id="tFinalInv" >S/<%=ttFinalInSolesInv=parseFloat(ttInversionSoles) + parseFloat(ttDolaresInSolesInv) %></td>
            <td style="text-align: right;font-weight: bold; " id="tFinalE" >S/<%=ttFinalInSolesE=parseFloat(ttEgresoSoles) + parseFloat(ttDolaresInSolesE) %></td>
            <%

            dataReporteBySoles=[
            {
            "name":"Ingreso",
            "y":parseFloat(ttFinalInSolesI)
            },
            {
            "name":"Inversion",
            "y":parseFloat(ttFinalInSolesInv)
            },
            {
            "name":"Egreso",
            "y":parseFloat(ttFinalInSolesE)

            }];
            %>

        </tr>

        <tr>
            <td>*</td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td>INGRESO-EGRESO </td>
            <td style="text-align: right;font-weight: bold;" id="Ingreso-Egreso"  >S/ <%=ttFinalInSolesI - ttFinalInSolesE %></td>
            <td style="text-align: right;font-weight: bold;" > </td>
            <td style="text-align: right;font-weight: bold; " >  </td>


        </tr>
        </tfoot>
    </table>
</script>

<script type="text/template"  id="tmpNewFormADD">

    <div class="row">
        <div class="col-sm-12">
            <div class="panel">
                <form id="formRegNewForm" class="form-horizontal">
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="ncuenta">Tipo Concepto</label>
                            <div class="col-sm-6">
                                <select class="form-control" id="ttconcepto" name="ttconcepto"   >
                                    <option value="0">Seleccione...</option>
                                    <?php foreach($tipoConcepto as $k=>$i){ ?>
                                        <option value="<?=$i["idtipoconceptocaja"]?>" ><?=$i["nombre"]?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="ncuenta">Concepto</label>
                            <div class="col-sm-6" id="">
                                <input type="text" name="nconcepto" id="nconcepto" class="form-control" >
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="button" id="btnSaveFormNewForm">Guardar</button>
                        <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
                    </div>

                </form>
                <!--===================================================-->
                <!--End Block Styled Form -->

            </div>
        </div>
    </div>


</script>

<script type="text/template" id="tmpSelConcepto"  >
    <select class="form-control" id="concepto" name="concepto" style="display: inline-block;width: 89%;"  >
        <% _.each(data,function(i,k){  %>
        <option value="<%=i.idconceptocaja%>"><%=i.nombre%></option>
        <% }); %>
    </select>
    <button type="button" class="btn btn-mint" style="display: inline-block;width: 10%;" id="btnAddNewConcepto" ><i class="fa fa-plus"></i></button>
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
                            <label class="col-sm-3 control-label" for="ncuenta">Tipo Concepto</label>
                            <div class="col-sm-6">
                                <select class="form-control" id="tconcepto" name="tconcepto"   >
                                    <option value="0">Seleccione...</option>
                                   <?php foreach($tipoConcepto as $k=>$i){ ?>
                                        <option value="<?=$i["idtipoconceptocaja"]?>" ><?=$i["nombre"]?></option>
                                   <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="ncuenta">Concepto</label>
                            <div class="col-sm-6" id="divSelConceptoCaja">
                                <select class="form-control" id="concepto" name="concepto" disabled="disabled" >
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="ncuenta">Monto</label>
                            <div class="col-sm-6">
                                <select class="form-control" id="moneda" name="moneda" style="display: inline-block;width: 15%;" >
                                    <option value="1">S/</option>
                                    <option value="2">$</option>
                                </select>
                                <input style="display: inline-block;width: 83%;font-weight: bold;" type="text" name="monto"  id="monto" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="ncuenta">Descripcion</label>
                            <div class="col-sm-6">
                                <input type="text" name="descripcion"  id="descripcion" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="ncuenta">Fecha</label>
                            <div class="col-sm-6">
                                <input type="date" name="fecharegistro"  id="fecharegistro" value="<?=date("Y-m-d")?>" class="form-control" >
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

<div class="modal fade" id="modalIdNewForm" role="dialog" tabindex="-1"  data-backdrop="static"   aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="">Registrar Concepto de caja</h4>
            </div>

            <!--Modal body-->
            <div class="modal-body" id="bModalBodyNewForm">


            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalIdReporte" role="dialog" tabindex="-1"  data-backdrop="static"   aria-hidden="true" >
    <div class="modal-dialog modal-lg " style="width: 98%;">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="">Generar Reporte</h4>
            </div>

            <!--Modal body-->
            <div class="modal-body" id="bModalBodyReporte">
                <div class="panel-body">
                    <div class="row">
                        De-Hasta
                        <input type="date" id="dtfechaventaini" value="<?=date("Y-m-d")?>" >
                        <input type="date" id="dtfechaventaend" value="<?=date("Y-m-d")?>" >
                        <button id="" onclick="generarReporteCaja('caja')" type="button" class="btn btn-mint btn-sm">Reporte de Caja</button>
                        </div>
                    <br>
                    <div class="row" id="">
                        <div class="col-lg-7" id="divResultReport"></div>
                        <div class="col-lg-5" >
                            <div class="col-lg-12" id="chartPieXTipo" style="height: 400px;" >

                            </div>
                            <div class="col-lg-12" id="chartPieXConcepto" ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
