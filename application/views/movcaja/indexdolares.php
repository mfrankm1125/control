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
                    <h3 class="panel-title"  ><b style="font-size: 18px;"><i class="fa fa-dollar"></i>  Moviemientos de caja - V/C DOLARES</b></h3>
                    <hr style="margin-bottom: 0px;margin-top: 0px;">
                </div>

                <!--Data Table-->

                <!--===================================================-->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12" >
                            <div class="col-lg-6">
                                <?php //if($this->session->userdata('id_role') != 18) {    ?>

                                    <input type="date" class="form-control input-sm" id="fechaInicioCaja"
                                           value="<?= date("Y-m-d"); ?>"
                                           style="width: 110px;padding-left: 0px;padding-right: 2px;display: inline-block">
                                    <button style="display: inline-block;" class="btn btn-sm btn-success"
                                            id="btnVerCajaByFecha">Ver
                                    </button>
                                <?php // }else { ?>
                                    <!--<input readonly="readonly" type="date" class="form-control input-sm" id="fechaInicioCaja"
                                           value="<?= date("Y-m-d"); ?>"
                                           style="width: 110px;padding-left: 0px;padding-right: 2px;display: inline-block">-->

                                <?php //}  ?>
                                <button style="display: inline-block;" class="btn btn-mint btn-sm" id="btnAddNew">Ingreso/Egreso</button>
                                <!--&nbsp;<button style="display: inline-block;" class="btn btn-purple btn-sm" id="btnAddNewVentaC">Venta</button>
                                &nbsp; <button style="display: inline-block;" class="btn btn-dark btn-sm" id="btnAddNewCompraC">Compra</button>
                                    -->
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6" >
                            <div class="col-xs-4">
                             </div>
                            <div class="col-xs-8   ">
                                <table class="table" >
                                    <thead>
                                    <tr>

                                        <th title="Saldo x Moneda" style=" text-align: right;" ><span> <b  style="visibility: hidden" >____________________________________________________</b> Saldos  </span></th>
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
                                                <i style="color: red;" class="pci-caret-down text-success"></i>
                                                <smal>Egresos </smal>
					                        </span> </th>
                                        <th  style="text-align: right;border-bottom: 2px solid black;" >  <span class="text-lg" style="color: red"  id="spanTotalEgresosSoles">0</span></th>
                                        <th  style="text-align: right;border-bottom: 2px solid black;" >  <span class="text-lg" style="color: red"  id="spanTotalEgresosDolares">0</span></th>

                                        <!-- <th><span class="labellabel-success ">
                                                <i style="color: saddlebrown;"  class="pci-caret-right text-success"></i>
                                                <smal>Inversion </smal>
                                            </span> </th>

                                        <th  style="text-align: right" >  <span class="text-lg" style="color: saddlebrown"  id="spanTotalInvertidoSoles">0</span></th>
                                        <th  style="text-align: right " >  <span class="text-lg" style="color: saddlebrown"  id="spanTotalInvertidoDolares">0</span></th>
                                    --></tr>
                                    <!--<tr>
                                        <th style="text-align: right;font-size:22px;"><b>S/ <b id="saldoTotalXMonedaSoles"></b> </b>  </th>
                                        <th> </th>
                                        <th><span class="labellabel-success ">
                                                <i style="color: red;" class="pci-caret-down text-success"></i>
                                                <smal>Egresos </smal>
					                        </span> </th>
                                        <th  style="text-align: right" >  <span class="text-lg" style="color: red"  id="spanTotalEgresosSoles">0</span></th>
                                        <th  style="text-align: right" >  <span class="text-lg" style="color: red"  id="spanTotalEgresosDolares">0</span></th>
                                    </tr>-->

                                    <tr>
                                        <th style="text-align: right;font-size:22px;"><b>S/ <b id="saldoTotalXMonedaSoles"></b> </b>  </th>
                                        <th> </th>
                                        <th> </th>
                                        <th  style="text-align: right" >  <span class="text-lg" style="color: black"  id="TotalSoles_1">0</span></th>
                                        <th  style="text-align: right" >  <span class="text-lg" style="color: black"  id="TotalDolares_1">0</span></th>
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
                                <th title="movimientos de stock">Movim.Stock</th>
                                <th>Fecha</th>
                                <th>Ingreso</th>
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
<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>

<script type="text/javascript">
    var dtTable;

    $(document).on("click","#btnVerCajaByFecha",function () {

        dtTable.ajax.url("<?= base_url(); ?>movcajadolares/getData/"+$("#fechaInicioCaja").val()).load();
        getTotalMovs();
    });
    $(document).ready(function() {
        dtIni($("#fechaInicioCaja").val());
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
        var finix= $("#fechaInicioCaja").val();
        $.post(url_base+"Movcajadolares/getTotalMovs",{fini:finix,fend:finix},function (data) {
            if(data.data.length > 0){
                var ingresoSoles=0;
                var egresoSoles=0;
                var inversionSoles=0;
                var ingresoDolares=0;
                var egresoDolares=0;
                var inversionDolares=0;
                var saldoSoles=0;
                var saldoDolares=0;
                $.each(data.data,function(k,i){
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

                    }
                });
                var wmoneda="";
                var datadolaresX= data.data2;
                wmoneda="Dolares";
                ingresoDolares=parseFloat(datadolaresX.ingresodolares);
                egresoDolares=parseFloat(datadolaresX.egresodolares);
                inversionDolares=parseFloat(0);
                $("#spanTotalIngreso"+wmoneda).html(ingresoDolares);
                $("#spanTotalInvertido"+wmoneda).html(inversionDolares);
                $("#spanTotalEgresos"+wmoneda).html(egresoDolares);



                saldoSoles=ingresoSoles-egresoSoles;
                saldoDolares=ingresoDolares-egresoDolares;
                var ts=$("#tasacambioXsaldo").val();
                var totalDolarEnsoles=parseFloat(saldoDolares)*parseFloat(ts);

                totalDolarEnsoles=totalDolarEnsoles.toFixed(2);
                $("#monedaCambiada").html(" = S/"+totalDolarEnsoles);
                var totaltotalxxxx=parseFloat(saldoSoles)+parseFloat(totalDolarEnsoles);

                $("#saldoTotalXMonedaSoles").html(totaltotalxxxx.toFixed(2));
                $("#spanTotalSaldoSoles,#TotalSoles_1").html(saldoSoles.toFixed(2));
                $("#spanTotalSaldoDolares,#TotalDolares_1").html(saldoDolares.toFixed(2));
            }else{

                let wmoneda="Soles";

                $("#spanTotalIngreso"+wmoneda).html(0);
                $("#spanTotalInvertido"+wmoneda).html(0);
                $("#spanTotalEgresos"+wmoneda).html(0);
                  wmoneda="Dolares";
                $("#spanTotalIngreso"+wmoneda).html(0);
                $("#spanTotalInvertido"+wmoneda).html(0);
                $("#spanTotalEgresos"+wmoneda).html(0);
                $("#monedaCambiada").html(" = S/"+0);
                $("#saldoTotalXMonedaSoles").html(0);
                $("#spanTotalSaldoSoles,#TotalSoles_1").html(0);
                $("#spanTotalSaldoDolares,#TotalDolares_1").html(0);
            }
        },'json');
    }

    function dtIni(fini){
        dtTable=$('#dtTable').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url(); ?>movcajadolares/getData/"+fini,
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
                {   "sortable": false,
                    "searchable":false,
                    "render": function ( data, type, full, meta ) {
                        var html="";
                        if(full.idtipoconceptocaja == "1"){

                                if(full.idconceptocaja == 1 || full.idconceptocaja == 5){
                                    html="<b style='color:green;text-align: right;' >+ $"+full.montodolar+"</b>";
                                }else if(full.idconceptocaja == 3){
                                    html="<b style='color:red;text-align: right;' >- $"+full.montodolar+"</b>";
                                }


                        }else {
                            if(full.idconceptocaja == 4){
                                html="<b style='color:green;text-align: right;' >+ $"+full.montodolar+"</b>";
                            }
                        }
                        return html;
                    }
                },
                { "data": "fecharegistro" },
                { "data": "ingreso","className": "tdIngreso"  },
                { "data": "egreso","className": "tdEgreso" },
                {   "sortable": false,
                    "searchable":false,
                    "render": function ( data, type, full, meta ) {
                        var id = full.idmovimientoscajad;
                        var tablareferencia = full.tablareferencia;
                        var idreferencia = full.idreferencia   ;

                        var html="";
                        //html='<a href="javascript:void(0)"  onclick="ver('+id+');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>';
                        //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                       // html=html+"&nbsp; <a href='javascript:void(0)' onclick='editar("+id +","+JSON.stringify(full)+");'  class='btn btn-mint  btn-xs'><i class='fa fa-file'></i> Editar</a>";
                        if(full.idtipoconceptocaja == "1"){
                            if(full.idconceptocaja == 1 || full.idconceptocaja == 5){
                                if(Number(full.montodolar) == 0){
                                    html=html+"&nbsp; <a href='javascript:void(0)' onclick='editar("+id +","+JSON.stringify(full)+");'  class='btn btn-mint  btn-xs'><i class='fa fa-file'></i> Editar</a>";
                                    html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar('+id +',\''+tablareferencia+'\',\''+idreferencia+'\',\'sol\',0);"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-xs"></i> Eliminar</a>';

                                }else{
                                    html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar('+id +',\''+tablareferencia+'\',\''+idreferencia+'\',\'dol\',\''+Number(full.montodolar)+'\');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-xs"></i> Eliminar</a>';

                                }


                            }else if(full.idconceptocaja == 3){//Ingreso x venta de dolares
                                html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar('+id +',\''+tablareferencia+'\',\''+idreferencia+'\',\'dol\',0);"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-xs"></i> Eliminar</a>';

                            }

                        }else {
                            if(full.idconceptocaja == 2){//Salida de efectivo
                             html=html+"&nbsp; <a href='javascript:void(0)' onclick='editar("+id +","+JSON.stringify(full)+");'  class='btn btn-mint  btn-xs'><i class='fa fa-file'></i> Editar</a>";
                             html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar('+id +',\''+tablareferencia+'\',\''+idreferencia+'\',\'sol\',0);"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-xs"></i> Eliminar</a>';
                            }else if(full.idconceptocaja == 4){
                                html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar('+id +',\''+tablareferencia+'\',\''+idreferencia+'\',\'dol\',\''+Number(full.montodolar)+'\');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-xs"></i> Eliminar</a>';

                            }
                        }

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
        });


    }

    function refrescar () {
        dtTable.ajax.reload();
    }

    $(document).on("click","#btnAddNew",function () {
        $("#modalId").modal("show");
        $("#hModalTitle").html("Registrar Movimiento de Caja");
        var tmpForm=_.template($("#tmpForm").html());
        $("#bModalBody").html(tmpForm);
        getProductosSearch(0,0,0);

    });


    $(document).on("click","#btnSaveForm",function () {
    	var btn=$(this);
    	btn.button("loading");
        var form=$("#formRegData").serialize();
        $.post(url_base+"Movcajadolares/setForm",form,function (data) {
            if(data.status == "ok"){
              refrescar();
                alert_success("Correcto");
                $("#modalId").modal("hide");
            }else{
                alert_success("Fallo");
            }
            btn.button("reset");
            getTotalMovs();
        },'json');
    });

    function eliminar(id,tabla,idx,mon,montoquita) {
        if(!confirm("¿Seguro de eliminar este registro?")){
            return 0;
        }
        $.post(url_base+"Movcajadolares/delete",{"id":id,tabla:tabla,idref:idx,moneda:mon,montoquita:montoquita},function (data) {
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
        $("#idEdit").val(data.idmovimientoscajad);


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
        if(vl == 2){
            $('#moneda option:not(:selected)').attr('disabled', true);
        }else{
            $('#moneda option:not(:selected)').removeAttr('disabled');
        }

    });

    function getSelConceptoCaja(id,idtipoconcepto) {
        var tmpSelConcepto=_.template($("#tmpSelConcepto").html());
        $.post(url_base+"Movcajadolares/getConceptoCaja",{id:id},function (data) {
            if(data.status =="ok"){
                $("#divSelConceptoCaja").html(tmpSelConcepto({data:data.data,idtpconcepto:id}));
                if(idtipoconcepto){
                    $("#concepto").val(idtipoconcepto);
                }
                if($("#tconcepto").val() == 2){
                    $('#moneda option:not(:selected)').attr('disabled', true);
                }else{
                    $('#moneda option:not(:selected)').removeAttr('disabled');
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
        $.post(url_base+"Movcajadolares/setConceptoCaja",form,function(data){
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
        $.post(url_base+"Movcajadolares/reporte",{tipo:"d",fechaini:fechaini,fechaend:fechaend},function(data){
            console.log(data);
            if(data.status == "ok"){

                $("#divResultReport").html(tmpReporteCaja({data:data.data,dataStockDolares:data.dataStockDolares,dataStockSoles:data.dataStockSoles}));

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
    $.post(url_base+"Movcajadolares/getReportPieByTipoConcepto",{fechaini:fechaini,fechaend:fechaend,tipo:tipo,tasacambio:$("#valTasaCambio").val()},function (data) {
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




    function exportReportCaja() {
        var fechaini=$("#dtfechaventaini").val();
        var fechaend=$("#dtfechaventaend").val();
        var urix=url_base+'/movcajadolares/printReportCaja1/'+fechaini+'/'+fechaend;
        window.open(urix, '_blank');
    }

    $(document).on("change","#moneda",function () {
        if($(this).val() == 1){
            $("#divIsDolar").css("display","none");
           // $("#divIsDolar").html("");
            //$("#divIsDolarX").css("display","block");
        }else{
            $("#divIsDolar").css("display","block");

            //var tmpIsDolar=_.template($("#tmpIsDolar").html());
           // $("#divIsDolar").html(tmpIsDolar);
           // $("#divIsDolarX").css("display","none");
        }

    });

    function getProductosSearch(idedit,stockedit,tc) {
        var tmpSelProducto=_.template($("#tmpSelProducto").html());
        $.post(url_base+"compradolares/getProductos",function (data) {
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
        if($(this).val()=="0-0"){
            $("#tcc").val("");
            $("#tcv").val("");
            $("#tcc").removeAttr("readonly");
            $("#tcv").removeAttr("readonly");

            return 0;
        }

        $("#tcc").attr("readonly","readonly");
        $("#tcv").attr("readonly","readonly");


        let tcc=$("#selProducto option:selected").attr("tcc");
        let tcv=$("#selProducto option:selected").attr("tcv");

        $("#tcc").val(tcc);
        $("#tcv").val(tcv);


        //console.log(tcc);
    });

</script>


<script type="text/template" id="tmpSelProducto">
    <select  style="padding-right:0px;padding-left:0px" class="form-control  " name="selProducto" id="selProducto" >
        <option  prov="ninguno" idprov="0" labeltxt="0" tcv="" stock="" tcc=""
                 value="0-0">Ninguno</option>

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
        let dddxx_=( parseFloat(Number(i.stock)) - parseFloat(Number(stockAdd)));
        label_1=dddxx_  +" + "+   stockAdd +"="+( parseFloat(Number(dddxx_)) + parseFloat(Number(stockAdd)));
        }else{
        selxx__1="disabled";
        label_1=i.stock;
        }
        }else{
        label_1=i.stock;
        }


        %>
        <option  <%=selxx__1%> prov="<%=i.razonsocial+"-"+i.nombre%>" idprov="<%=i.idproveedor%>" labeltxt="<%=i.nmoneda%>" tcv="<%=i.tasacambioventa%>" stock="<%=( parseFloat(Number(i.stock)) + parseFloat(Number(stockAdd)))%>" tcc="<%=i.tasacambiocompra%>"
        value="<%=i.iddetcomprad+"-"+i.idcomprad%>"><%=i.abreviado+"  "+label_1+" | TcC:S/ "+i.tasacambiocompra+" |TcV:S/ "+i.tasacambioventa %>     </option>
        <%  });
        if(idedit !=0){
        if(existSel == 0){  %>
        <option selected="selected"      tcv="-" stock="<%=stockedit%>" tcc="" value="<%=idedit%>" >$<%=stockedit%>
        </option>


        <%  }
        } %>
    </select>
    <br>
    <%  %>
</script>

<script type="text/template" id="tmpReporteCaja">
    <button class="btn btn-xs btn-purple" type="button" onclick="exportReportCaja()" >Exportar</button>
    <div id="tablaReportCaja_">
    <table   border="1" class="table table-bordered table-condensed" >
        <thead>
            <tr>
                <th>*</th>
                <th>Tipo</th>
                <th>Mov</th>
                <th>Desc</th>
                <th>Mov.Dol</th>
                <th>Fecha</th>
                <th style="text-align: right;">Ingreso</th>
                <th style="text-align: right;">Egreso</th>
                <!--<th style="text-align: right;">Salida</th>-->
            </tr>
        </thead>
        <tbody>
        <%  var movdolaresxxxxx_=0;
            var ttIngresoSoles=0;
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
            <td style="text-align: right;">
                <% let sinbol="";
                   let color_="";
                if(i.idtipoconceptocaja == "1"){
                    if(i.idconceptocaja == 1 || i.idconceptocaja == 5){
                    sinbol="+";
                    color_="green;";
                    movdolaresxxxxx_=movdolaresxxxxx_+ parseFloat(Number(i.montodolar));

                    }else if(i.idconceptocaja == 3){
                    sinbol="-";
                    color_="red;";
                    movdolaresxxxxx_=movdolaresxxxxx_- parseFloat(Number(i.montodolar));

                    }



                }else{

                    if(i.idconceptocaja == 4){
                    sinbol="+";
                    color_="green";
                    movdolaresxxxxx_=movdolaresxxxxx_ + parseFloat(Number(i.montodolar));
                    }

                }
                 %>
                <% if( Number(i.montodolar) > 0   ){%>
                <b style='color:<%=color_%>;text-align: right;' ><%=sinbol+" $"+i.montodolar %> </b>
                <%}else{ print(0);}%>
                 </td>
            <td><%=i.fecharegistro%></td>
            <td class="tdIngreso" ><%=i.ingreso%></td>
            <!--<td class="tdInversion" ><%=i.inversion%></td>-->
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
            <td>Total: </td>
            <td>$<%=movdolaresxxxxx_%> </td>
            <td>Total S/</td>
            <td style="text-align: right;font-weight: bold; " >
                <input type="hidden" id="ttIngresoSoles" value="<%=ttIngresoSoles%>" >
              <input type="hidden" id="ttInversionSoles" value="<%=ttInversionSoles%>" >
                <input type="hidden" id="ttEgresoSoles" value="<%=ttEgresoSoles%>" >
                S/<%=ttIngresoSoles%></td>
            <!--<td style="text-align: right;font-weight: bold; " >S/<%=ttInversionSoles%></td>-->
            <td style="text-align: right;font-weight: bold; " >S/<%=ttEgresoSoles%></td>
        </tr>
        <!--<tr>
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
        </tr>-->
        <!--<tr>
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

        </tr>-->

        <tr>
            <td>*</td>
            <td> </td>
            <td> </td>
            <td>  </td>
            <td> </td>
            <td>INGRESO-EGRESO </td>
            <td style="text-align: right;font-weight: bold;" id="Ingreso-Egreso"  >S/ <%=ttFinalInSolesI - ttFinalInSolesE %></td>
            <td style="text-align: right;font-weight: bold;" > </td>



        </tr>

        <tr>
            <td>*</td>
            <td> </td>
            <td> </td>
            <td>Stock Histórico ($) </td>
            <td>$<%=dataStockDolares%> </td>
            <td>Stock Histórico (S/)  </td>
            <td style="text-align: right;font-weight: bold;"  >S/ <%= dataStockSoles %></td>
            <td style="text-align: right;font-weight: bold;" > </td>



        </tr>
        <tr>
            <td>*</td>
            <td> </td>
            <td> </td>
            <td><b>Stock Dolares</b> </td>
            <td><b>$<%=(Number(dataStockDolares)+ Number(movdolaresxxxxx_))%> </b></td>
            <td><b>Stock Soles </b></td>
            <td style="text-align: right;font-weight: bold;" id="Ingreso-Egreso"  ><b>S/ <%= (ttFinalInSolesI - ttFinalInSolesE)+(Number(dataStockSoles)) %> </b></td>
            <td style="text-align: right;font-weight: bold;" > </td>



        </tr>
        </tfoot>
    </table>
    </div>
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
    <!--<button type="button" class="btn btn-mint" style="display: inline-block;width: 10%;" id="btnAddNewConcepto" ><i class="fa fa-plus"></i></button>
      -->
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
                        <div class="form-group" id="divIsDolar" style="display: none" >
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <label class="col-sm-3 control-label" for="tcc">Sumar stock a:</label>
                                    <div class="col-sm-6" style="padding-left: 0px;padding-right: 0px;" id="divSelProductos">
                                        <input type="hidden" name="selProducto" value="">
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-12">
                                <div class="col-lg-6">
                                    <label class="col-sm-6 control-label" for="tcc">T.Cambio</label>
                                    <div class="col-sm-5">
                                        <input type="number" name="tcc"  id="tcc" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="col-sm-2 control-label" for="tcv">T.C.Venta</label>
                                    <div class="col-sm-4">
                                        <input type="number" name="tcv"  id="tcv" class="form-control" >
                                    </div>
                                </div>
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


<script type="text/template" id="tmpIsDolar">

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
