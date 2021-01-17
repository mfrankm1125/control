<style>


</style>
<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title" style="display: inline-block">Inspección a viviendas  Realizadas</h3>
                <h3 class="panel-title" style="display: inline-block">
                    <select id="anioVigiViiReal" class="form-control input-sm" style="display: inline-block" >
                        <option> 5</option>
                    </select>
                </h3>

            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;">
                <br>
                <div class="table-toolbar-left">
                    <!--<button class="btn btn-purple" id="add"><i class="demo-pli-add icon-fw"></i> Agregar</button> -->
                    <button class="btn btn-purple" id="btnAdd"><i class="demo-pli-add icon-fw"></i> Agregar Nuevo</button>
                    <button class="btn btn-purple" id="btnOpenModalAddCSV"><i class="demo-pli-add icon-fw"></i> Importar datos desde CSV</button>
                    <div class="btn-group">
                        <button class="btn btn-default" id="refresh"  ><i class="demo-pli-refresh" ></i>Refrescar</button>

                    </div>
                </div>
                <br><br>
                <!--CUerpot aqui -->
                <h1></h1>
                <table id="tabla_grid" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Provincia</th>
                        <th>Distrito</th>
                        <th>IPRESS </th>
                        <th>Mes-Anio</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody id="tabla_body">
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


<div class="modal fade"   id="modal_id" role="dialog"  data-backdrop="static" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 95%">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="">
                <div class="row" id="divFormComienzaInspeccion">
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade"   id="modalReport" role="dialog"  data-backdrop="static" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 90%">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id=""></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="idModalBodyReport">

            </div>
        </div>
    </div>
</div>



<?php echo $_css?>
<?php echo $_js?>

<script type="text/javascript">
    var fileExtension = ['csv'];
    var maxSizeFile=parseInt("<?=$this->config->item('max_size_file');?>");
    var nameFilePreviewDiario=null;
    window.onload = function(){

    }
    function scrollHandle (e){
        var scrollTop = this.scrollTop;
        this.querySelector('thead').style.transform = 'translateY(' + scrollTop + 'px)';       }

    $(document).ready(function () {

        var rowSelection = $('#tabla_grid').DataTable({
            "ajax": url_base+'insviviconsolidado/getDataTable',
            "columns": [
                { "data": null },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.distrito;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.localidad;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.descipress;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.mesanio;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  sortable: false,
                    "render": function ( data, type, full, meta ) {
                        var mesanio = full.mesanio;
                        var ipress = full.descipress;
                        html="";
                        //html='<a href="javascript:void(0)"  onclick="ver('+id+');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>';
                        //html='<a href="javascript:void(0)"  onclick="verXsemanas(\''+ipress +'\',\''+mesanio+'\');" class="btn btn-mint btn-xs"><i class="fa fa-eye"></i> Ver x Semana</a>';

                        html=html+'<a href="javascript:void(0)"  onclick="ver(\''+ipress +'\',\''+mesanio+'\');" class="btn btn-mint btn-xs"><i class="fa fa-eye"></i> Ver</a>';

                        html=html+'&nbsp;<a href="javascript:void(0)"  onclick="reporte(\''+ipress +'\',\''+mesanio+'\');" class="btn btn-dark btn-xs"><i class="fa fa-file-text-o"></i> Reporte</a>';


                        //html=html+"&nbsp; <a href='javascript:void(0)' onclick='ver(\""+ipress+"\",\""+mesanio+"\");'  class='btn btn-mint btn-icon btn-xs'><i class='fa fa-eye'></i> Ver</a>";
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


    });
    function refrescar () {
        var rowSelection = $('#tabla_grid').DataTable();
        rowSelection.ajax.reload();
    }
    $(document).on("click","#btnOpenModalAddCSV",function () {
        open_modal("modal_id");
        var tmpModalBodyInsCSV=_.template($("#tmpModalBodyInsCSV").html());
        $("#divFormComienzaInspeccion").html(tmpModalBodyInsCSV);
        var tableCont = document.querySelector('#table-cont');
        tableCont.addEventListener('scroll',scrollHandle);
    });

    $(document).on("click","#btnPreViewCSV",function () {
        nameFilePreviewDiario=null;
        uploadFileCsvD();
    });


    function uploadFileCsvD(){
        nameFilePreviewDiario=null;
        var btnPreview=$("#btnPreViewCSV");
        var inputFile = $('input[name=fileCsvD]');
        var fileUpload = inputFile[0].files[0];
        // console.log(fileUpload);
        if (fileUpload == undefined) {return 0;}
            btnPreview.button("loading");
            var formData = new FormData();
            formData.append("file", fileUpload);
            $.ajax({
                url: url_base + "Insviviconsolidado/uploadFileCSV",
                type: 'post',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (data) {                          //alert("echo");
                    //console.log(data);
                    var objLength = Object.keys(data).length;
                    if(objLength>0){
                        if(data.status == "ok") {
                            nameFilePreviewDiario=data.orig_name;
                            getDataPreviewDiario(btnPreview,data.orig_name);
                        } else {
                            alert_error("Se realizó correctamente!");
                        }
                    }
                },
                error: function (e) {
                    console.log(e + 'error');

                },
                progress:function (e){
                    if(e.lengthComputable) {
                        var pct = (e.loaded / e.total) * 100;
                        $("#divprogressBar").css("display","block");
                        var pgbar=$('#progressBar');
                        pgbar.css('width', pct.toPrecision(3) + '%');
                        pgbar.html(pct.toPrecision(3) + '%');
                        if(pct == 100){

                            $("#divprogressBar").fadeOut(3000);
                        }
                    } else {
                        console.log('Content Length not reported!');
                    }
                }

            });
    }

    function getDataPreviewDiario(btn,nameFilePreviewDiario){
        if(!nameFilePreviewDiario){return 0;}
        $("#tTbodyPreView").html("<tr><td colspan='54'><b>Cargando...</b></td></tr>");
        $.post(url_base+"Insviviconsolidado/preViewFileCSV",{"nameFile":nameFilePreviewDiario},function(data){
           // console.log(data);
           // $("#divTablePreViewDiario").html(tmpTablePreViewDiario);
            //$("#tbodyActOpsPreviewDiario").html(data);
            $("#tTbodyPreView").html(data);
            btn.button("reset");
            $("#fileCsvD").val("");
            alert_success("Se subio el archivo correctamente!");
        },"html");
    }

    $(document).on("click","#btnPreViewCSVTest",function () {
        getDataPreviewDiario('1554912672ENE_HUAYCO_BASE_DE_DATOS_2019.csv');
    });
    $(document).on("click","#btnSaveTablePreviewCSV",function () {
        var mesreg=$("#mesreg").val();
        if(!$("#mesreg").required()){ return 0;}
        var lenTableData=$("input[name='zonasector[]']");
        var btn=$(this);
        //console.log(lX.length);
        var isFechaXReg=validaIpressFechasReg();//return 0;
        if(!isFechaXReg){return 0;}
        if(lenTableData.length == 0){alert_error("Previsualice el archivo a registrar");}
        btn.button("loading");
        var form=$("#formTableData").serialize();
        $.post(url_base+"Insviviconsolidado/setForm",form,function (data) {
            //console.log(data);
            if(data.status=="ok"){
                $("#tTbodyPreView").html("");

                alert_success("Se Registro Correctamente");
            }else{
                alert_error("Upss");
            }
            btn.button("reset");
        },'json');
    });

    function validaIpressFechasReg() {
        var inFechas=$("input[name='fintervencion[]']");
        var inIpress=$("input[name='establecimiento[]']");
        var iniIpress=null;
        if(inIpress.length > 0 ){
             iniIpress= $(inIpress[0]).val();
        }

        var mesAnioreg=$("#mesreg").val();
        var isFechasForReg=true;
        for(var ix=0; ix < inFechas.length ;ix++){
            var dd=$(inFechas[ix]).val();
            var arr=dd.split("-");
            if(arr.length > 0){
              var fin=arr[0]+"-"+arr[1];
              var ctx=$(inFechas[ix]).closest("tr");
              if(fin != mesAnioreg){
                 ctx.css("background-color","#ffc7c7c7");
                 isFechasForReg=isFechasForReg&&false;
              }else{
                  isFechasForReg=isFechasForReg&&true;
                  ctx.css("background-color","#fdfdfd2e");
              }
            }
        }
        if(!isFechasForReg){
            alert_error("Se identificaron fechas que no pertenecen al registro");
        }

        $.ajax({
            url: url_base + "insviviconsolidado/issetMesAnioForReg",
            async:false,
            type: 'post',
            data: {"aniomes":mesAnioreg,"ipress":iniIpress},
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if(data.status=="ok"){
                    if( Number(data.c) > 0){
                        isFechasForReg=isFechasForReg&&false;
                        alert_error("Ya se registro esta Ipress en este mes, elimine el anterior y ingrese nuevamente");
                    }
                }
            }
        });

      return isFechasForReg;
    }

    function ver(ipress,fecha) {
        location.href=url_base+'insviviconsolidado/seeDetaill/1/'+ipress+'/'+fecha;
    }

    function deleteTr(thisx) {
        if(!confirm("¿Desea eliminar este registro?")){return 0;};
        var ctx=$(thisx);
        ctx.closest("tr").remove();
       // console.log();
    }
    function reporte(ipress,fecha) {
        open_modal("modalReport");
        var tmpModalBodyReportVigilancia=_.template($("#tmpModalBodyReportVigilancia").html());
        $.post(url_base+"insviviconsolidado/getReportVigilanciaByMesAnioIpress",{"aniomes":fecha,"ipress":ipress},function (data) {

            $("#idModalBodyReport").html(tmpModalBodyReportVigilancia({data:data.data}))
        },'json');
    }
    function reportModalExcel() {
        tableToExcel(["tb1","tb2","tb3","tb4"],"e.xls");
    }

    var tableToExcel = (function() {
        var uri = 'data:application/vnd.ms-excel;base64,'
            , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body> {table} </body></html>'
            , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
            , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) };
        return function(tables, name) {
            if(tables.length >0 ){
                var arrT="";
                var ttmp;
                for(var xi in tables){
                    //console.log(table[xi]);
                    if(document.getElementById(tables[xi])){
                        ttmp=document.getElementById(tables[xi]);
                        arrT=arrT+"<table border='1'>"+ttmp.innerHTML+"</table><br>";

                    }else{
                        console.log("ID table no existe:"+tables[xi]);
                    }

                    //if (!table[xi].nodeType) table[xi] = document.getElementById(table[xi]);
                }
                /*console.log(arrT);
                console.log(table.innerHTML);*/
                var ctx = {worksheet: name || 'Worksheet', table:arrT  };
                window.location.href = uri + base64(format(template, ctx));
            }

        }
    })();
</script>

<script type="text/template" id="tmpModalBodyInsCSV">
    <div class="col-lg-12">
        <form class="panel-body form-horizontal form-padding" id="formImportDiario">
            <div class="form-group">
                <label class="col-md-2 control-label"  >Archivo</label>
                <div class="col-md-4">
                    <input type="file" accept=".csv" class="control-label" id="fileCsvD" name="fileCsvD"    >
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-mint btn-sm" id="btnPreViewCSV" >Previsualizar Archivo CSV</button>
                    <button type="button" class="btn btn-mint btn-sm" id="btnPreViewCSVTest" >Previsualizar Archivo CSV</button>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Mes del registro</label>
                <div class="col-md-2">
                    <input type="month" class="control-label" id="mesreg" name="mesreg">
                </div>

            </div>
        </form>
    </div>
    <div class="col-lg-12">
        <div>
            <button class="btn btn-success" id="btnSaveTablePreviewCSV" >Guardar</button>
        </div>

        <div class="table-responsive"  id='table-cont' style="height: 500px">
            <form id="formTableData">
                <table  style="border-collapse: separate !important;"  class="table table-bordered table-hover table-condensed header-fixed" >
                    <thead style="background-color: white;" >
                    <tr style="font-size: 10px;">
                        <th  >*</th>
                        <th  >#</th>
                        <th  ><span>PROVINCIA</span><span style="visibility: hidden">_____</span> </th>
                        <th  >LOCALIDAD</th>
                        <th  >ESTABLECIMIENTO</th>
                        <th style="height: 180px;"  ><p style=" writing-mode: vertical-rl; transform: rotate(180deg)">ZONA O SECTOR</p></th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">Nº MANZANA</p> </th>
                        <th  style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">CERCO ENTOMOLOGICO Nº DE OVITRAMPA</p>
                        </th>
                        <th  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">TOTAL DE VIVIENDAS</p> </th>
                        <th  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">LOTE</p> </th>
                        <th style="height: 180px;"  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">VIVIENDA INSPECC. TOTAL</p> </th>
                        <th  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">CERRADA </p></th>
                        <th  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">DESHABITADA </p></th>
                        <th  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">RENUENTE </p></th>
                        <th style="height: 180px;"  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">TANQUE ALTO, BAJO, POZOS INSPEC.</p> </th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">TANQUE ALTO, BAJO, POZOS POSITIVOS </p></th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">TANQUE ALTO, BAJO, POZOS  TRATADOS </p></th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">BARRIL, CILINDRO, SANSON  INSPEC. </p></th>
                        <th  style="height: 180px;"> <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">BARRIL, CILINDRO, SANSON POSITIVO </p></th>
                        <th style="height: 180px;"> <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">BARRIL, CILINDRO, SANSON TRATADO </p></th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">BALDE, BATEA, TINA  INSPEC.</p> </th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">BALDE, BATEA, TINA POSITIVO </p></th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">BALDE, BATEA, TINA  TRATADO </p></th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">OLLAS, CANTAROS DE BARRO
                                INSPEC.</p> </th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">OLLAS, CANTAROS DE
                                BARRO POSITIVOS </p> </th>
                        <th style="height: 180px;"  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">OLLAS, CANTAROS DE
                                BARRO TRATADOS </p> </th>
                        <th style="height: 180px;"> <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">FLOREROS, MACETAS  INSPEC </p></th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">FLOREROS, MACETAS
                                POSITIVO </p> </th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">FLOREROS, MACETAS  TRATADO</p> </th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">LLANTAS  INSPEC.</p> </th>
                        <th style="height: 180px;"  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">LLANTAS POSITIVAS </p></th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">LLANTAS  TRATADOS</p> </th>
                        <th  style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">INSERVIBLES QUE SON
                                CRIADEROS</p>
                        </th>
                        <th  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">INSERVIBLES QUE SON
                                CRIADEROS POSITIVOS</p>
                        </th>
                        <th style="height: 180px;" >
                            <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">INSERVIBLES QUE SON
                                CRIADEROS  ELIMINADOS </p>
                        </th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">OTROS RECIPIENTES  INSPEC.</p> </th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">OTROS RECIPIENTES POSITIVOS </p></th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">OTROS RECIPIENTES  TRATDOS </p> </th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)"> INSPECCIONADOS </p> </th>
                        <th style="height: 180px;"  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">TOTAL POSITIVOS </p> </th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">TRATADOS</p> </th>
                        <th style="height: 180px;"  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">ELIMINADOS</p> </th>
                        <th style="height: 180px;"  > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">TOTAL DE VIVIENDAS
                                TRATADAS</p> </th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">TOTAL DE VIVIENDAS POSITIVAS </p> </th>
                        <th style="height: 180px;" > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">CONSUMO DE PYRIPROXYFEN (Gr.) </p> </th>
                        <th   > <p style=" writing-mode: vertical-rl; transform: rotate(180deg)">SEMANA ENTOMOLOGICA</p> </th>
                        <th  >FECHA DE INTERVENCIÓN</th>
                        <th  ><span>INSPECTOR</span><span style="visibility: hidden" >__________________________________________</span></th>
                        <th  ><span>JEFE DE BRIGADA</span><span style="visibility: hidden" >__________________________________________</span></th>
                    </tr>
                    </thead>
                    <tbody id="tTbodyPreView" style="font-size: 12px;">

                    </tbody>
                </table>
            </form>
        </div>
    </div>
</script>


<script type="text/template" id="tmpModalBodyReportVigilancia">
    <div class="row">
        <div class="col-lg-5">
            <button type="button" onclick="reportModalExcel()" class="btn btn-primary btn-xs">Excel</button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-5">
            <h4></h4>
            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-bordered" id="tb1"  >
                    <thead style="font-size: 12px;">
                    <tr>
                        <th> SECTORES </th>
                        <th>VIVIENDAS INSPECCION TOTAL</th>
                        <th> CERRADAS TOTAL </th>
                        <th>DESHABITADAS TOTAL</th>
                        <th>RENUENTES TOTAL</th>
                    </tr>
                    </thead>
                    <tbody>
                        <%
                            var Tsumtotalviviendasinsp=0;
                            var Tsumtotalviviendascerradas=0;
                            var Tsumtotalviviendasdeshabitadas=0;
                            var Tsumtotalviviendasrenuente=0;
                        _.each(data,function(i,k){
                        Tsumtotalviviendasinsp=Tsumtotalviviendasinsp+ parseFloat(Number(i.sumtotalviviendasinsp))
                        Tsumtotalviviendascerradas=Tsumtotalviviendascerradas+ parseFloat(Number(i.sumtotalviviendascerradas))
                        Tsumtotalviviendasdeshabitadas=Tsumtotalviviendasdeshabitadas+ parseFloat(Number(i.sumtotalviviendasdeshabitadas))
                        Tsumtotalviviendasrenuente=Tsumtotalviviendasrenuente+ parseFloat(Number(i.sumtotalviviendasrenuente))
                        %>
                        <tr style="text-align: right">
                            <td><%=i.sector%> </td>
                            <td><%=i.sumtotalviviendasinsp %> </td>
                            <td><%=i.sumtotalviviendascerradas%> </td>
                            <td><%=i.sumtotalviviendasdeshabitadas%> </td>
                            <td><%=i.sumtotalviviendasrenuente%> </td>
                        </tr>
                        <% })%>
                        <tr style="text-align: right" >
                            <td>Total</td>
                            <td><%=Tsumtotalviviendasinsp%></td>
                            <td><%=Tsumtotalviviendascerradas%> </td>
                            <td><%=Tsumtotalviviendasdeshabitadas%>  </td>
                            <td><%=Tsumtotalviviendasrenuente%> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-4">
            <h4></h4>
            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-bordered" id="tb2"  >
                    <thead style="font-size: 12px;">
                    <tr>
                        <th> SECTORES </th>
                        <th>Suma de TOTAL DE VIVIENDAS TRATADAS</th>
                        <th> Suma de TOTAL DE VIVIENDAS POSITIVAS </th>
                    </tr>
                    </thead>
                    <tbody>
                    <% var Tsumtotalviviendastratadas=0;
                       var Tsumtotalviviendaspositivas=0;
                    _.each(data,function(i,k){
                    Tsumtotalviviendastratadas=Tsumtotalviviendastratadas+parseFloat(Number(i.sumtotalviviendastratadas));
                    Tsumtotalviviendaspositivas=Tsumtotalviviendaspositivas+parseFloat(Number(i.sumtotalviviendaspositivas));
                    %>
                    <tr style="text-align: right">
                        <td><%=i.sector%> </td>
                        <td><%=i.sumtotalviviendastratadas%> </td>
                        <td><%=i.sumtotalviviendaspositivas%> </td>
                    </tr>
                    <% })%>
                    <tr style="text-align: right" >
                        <td>Total </td>
                        <td><%=Tsumtotalviviendastratadas %> </td>
                        <td><%=Tsumtotalviviendaspositivas %> </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-3">
            <h4></h4>
            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-bordered" id="tb3" >
                    <thead style="font-size: 12px;">
                    <tr>
                        <th> SECTORES </th>
                        <th>Suma de CONSUMO DE PYRIPROXYFEN (Gr.)</th>
                    </tr>
                    </thead>
                    <tbody>
                        <%
                        var Tsumconsumolarvicidagr=0;
                        _.each(data,function(i,k){
                        Tsumconsumolarvicidagr=Tsumconsumolarvicidagr+parseFloat(Number(i.sumconsumolarvicidagr));
                        %>
                        <tr style="text-align: right" >
                            <td><%=i.sector%> </td>
                            <td><%=i.sumconsumolarvicidagr%> </td>
                        </tr>
                        <% })%>
                        <tr style="text-align: right" >
                            <td>Total </td>
                            <td><%=Tsumconsumolarvicidagr%> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-12">
            <h4></h4>
            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-bordered" id="tb4">
                    <thead style="font-size: 12px;">
                    <tr  >
                        <th >SECTORES</th>
                        <th >Suma de TANQUE ALTO, BAJO, POZOS INSPEC.</th>
                        <th >Suma de TANQUE ALTO, BAJO, POZOS POSITIVOS</th>
                        <th >Suma de TANQUE ALTO, BAJO, POZOS TRATADOS</th>
                        <th >Suma de BARRIL, CILINDRO, SANSON INSPEC.</th>
                        <th >Suma de BARRIL, CILINDRO, SANSON POSITIVO</th>
                        <th >Suma de BARRIL, CILINDRO, SANSON TRATADO</th>
                        <th >Suma de BALDE,BATEA, TINA INSPEC.</th>
                        <th >Suma de BALDE,BATEA, TINAPOSITIVO</th>
                        <th >Suma de BALDE,BATEA, TINATRATADO</th>
                        <th >Suma de OLLAS, CANTAROS DE BARRO INSPEC.</th>
                        <th >Suma de OLLAS, CANTAROS DE BARROPOSITIVOS</th>
                        <th >Suma de OLLAS, CANTAROS DE BARROTRATADOS</th>
                        <th >Suma de FLOREROS, MACETAS INSPEC.</th>
                        <th >Suma de FLOREROS, MACETAS POSITIVO</th>
                        <th >Suma de FLOREROS, MACETAS TRATADO</th>
                        <th >Suma de LLANTAS INSPEC.</th>
                        <th >Suma de LLANTAS  POSITIVAS</th>
                        <th >Suma de LLANTAS TRATADOS</th>
                        <th >Suma de INSERVIBLES QUE SON CRIADEROS</th>
                        <th >Suma de INSERVIBLES QUE SON CRIADEROS POSITIVOS</th>
                        <th >Suma de INSERVIBLES QUE SON CRIADEROS ELIMINADOS</th>
                        <th >Suma de OTROS ECIPIENTES INSPEC.</th>
                        <th >Suma de OTROS ECIPIENTES POSITIVOS</th>
                        <th >Suma de OTROS RECIPIENTES TRATDOS</th>
                        <th >Suma de TOTALINSPECCIONADOS</th>
                        <th >Suma de TOTALPOSITIVOS</th>
                        <th >Suma de TOTALTRATADOS</th>
                        <th >Suma de TOTALELIMINADOS</th>
                    </tr>
                    </thead>
                    <tbody>
                    <%
                    var tAltIns=0;
                    var tAltPos=0;
                    var tAltTrat=0;

                    var barrIns=0;
                    var barrPos=0;
                    var barrTrat=0;

                    var balIns=0;
                    var balPos=0;
                    var balTrat=0;

                    var ollaIns=0;
                    var ollaPos=0;
                    var ollaTrat=0;

                    var florIns=0;
                    var florPos=0;
                    var florTrat=0;

                    var llaIns=0;
                    var llaPos=0;
                    var llaTrat=0;

                    var insIns=0;
                    var insPos=0;
                    var insElim=0;

                    var otrosIns=0;
                    var otrosPos=0;
                    var otrosTrat=0;

                    var TIns=0;
                    var TPos=0;
                    var TElim=0;
                    var TTrat=0;

                    _.each(data,function(i,k){
                      tAltIns=tAltIns+ parseFloat(Number(i.sumtanquealto_inspeccionado));
                      tAltPos=tAltPos + parseFloat(Number(i.sumtanquealto_positivo));
                      tAltTrat=tAltTrat + parseFloat(Number(i.sumtanquealto_tratados));

                      barrIns=barrIns + parseFloat(Number(i.sumbarril_inspeccionado));
                      barrPos=barrPos + parseFloat(Number(i.sumbarril_positivo));
                      barrTrat=barrTrat + parseFloat(Number(i.sumbarril_tratado));

                      balIns=balIns + parseFloat(Number(i.sumbalde_inspeccionado));
                      balPos=balPos + parseFloat(Number(i.sumbalde_positivo));
                      balTrat=balTrat + parseFloat(Number(i.sumbalde_tratado));


                      ollaIns=ollaIns + parseFloat(Number(i.sumolla_inspeccionado));
                      ollaPos=ollaPos + parseFloat(Number(i.sumolla_positivo));
                      ollaTrat=ollaTrat + parseFloat(Number(i.sumolla_tratado));


                      florIns=florIns + parseFloat(Number(i.sumflorero_inspeccionado));
                      florPos=florPos + parseFloat(Number(i.sumflorero_positivo));
                      florTrat=florTrat + parseFloat(Number(i.sumflorero_tratado));

                      llaIns=llaIns + parseFloat(Number(i.sumllantas_inspeccionado));
                      llaPos=llaPos + parseFloat(Number(i.sumllantas_positivo));
                      llaTrat=llaTrat + parseFloat(Number(i.sumllantas_tratado));


                      insIns=insIns + parseFloat(Number(i.suminservibles_inspeccionado));
                      insPos=insPos + parseFloat(Number(i.suminservibles_positivo));
                      insElim=insElim + parseFloat(Number(i.suminservibles_eliminado));

                      otrosIns=otrosIns + parseFloat(Number(i.sumotros_inspeccionado));
                      otrosPos=otrosPos + parseFloat(Number(i.sumotros_positivo));
                      otrosTrat=otrosTrat + parseFloat(Number(i.sumotros_tratados));


                      TIns=TIns +parseFloat(Number(i.sumtinspeccionados));
                      TPos=TPos +parseFloat(Number(i.sumtpositivos));
                      TElim=TElim +parseFloat(Number(i.sumttratados));
                      TTrat=TTrat +parseFloat(Number(i.sumteliminados));

                    %>
                    <tr style="text-align: right" >
                        <th ><%= i.sector%> </th>

                        <th ><%= i.sumtanquealto_inspeccionado%> </th>
                        <th ><%= i.sumtanquealto_positivo%> </th>
                        <th ><%= i.sumtanquealto_tratados%> </th>

                        <th ><%= i.sumbarril_inspeccionado%> </th>
                        <th ><%= i.sumbarril_positivo%> </th>
                        <th ><%= i.sumbarril_tratado%> </th>

                        <th ><%= i.sumbalde_inspeccionado%> </th>
                        <th ><%= i.sumbalde_positivo%> </th>
                        <th ><%= i.sumbalde_tratado%> </th>

                        <th ><%= i.sumolla_inspeccionado%> </th>
                        <th ><%= i.sumolla_positivo%> </th>
                        <th ><%= i.sumolla_tratado%> </th>

                        <th ><%= i.sumflorero_inspeccionado%> </th>
                        <th ><%= i.sumflorero_positivo%> </th>
                        <th ><%= i.sumflorero_tratado%> </th>

                        <th ><%= i.sumllantas_inspeccionado%> </th>
                        <th ><%= i.sumllantas_positivo%> </th>
                        <th ><%= i.sumllantas_tratado%> </th>

                        <th ><%= i.suminservibles_inspeccionado%> </th>
                        <th ><%= i.suminservibles_positivo%> </th>
                        <th ><%= i.suminservibles_eliminado%> </th>

                        <th ><%= i.sumotros_inspeccionado%> </th>
                        <th ><%= i.sumotros_positivo%> </th>
                        <th ><%= i.sumotros_tratados%> </th>

                        <th ><%= i.sumtinspeccionados%> </th>
                        <th ><%= i.sumtpositivos%> </th>
                        <th ><%= i.sumttratados%> </th>
                        <th ><%= i.sumteliminados%> </th>
                    </tr>
                    <% });%>

                    <tr style="text-align: right" >
                        <th > Total</th>

                        <th ><%= tAltIns%> </th>
                        <th ><%= tAltPos %> </th>
                        <th ><%= tAltTrat %> </th>

                        <th ><%=barrIns  %> </th>
                        <th ><%=barrPos %> </th>
                        <th ><%= barrTrat %> </th>

                        <th ><%= balIns%> </th>
                        <th ><%= balPos %> </th>
                        <th ><%= balTrat%> </th>

                        <th ><%= ollaIns%> </th>
                        <th ><%= ollaPos %> </th>
                        <th ><%= ollaTrat %> </th>


                        <th ><%=florIns %> </th>
                        <th ><%=florPos  %> </th>
                        <th ><%= florTrat %> </th>

                        <th ><%=llaIns  %> </th>
                        <th ><%= llaPos %> </th>
                        <th ><%= llaTrat %> </th>

                        <th ><%= insIns %> </th>
                        <th ><%= insPos%> </th>
                        <th ><%= insElim %> </th>

                        <th ><%=otrosIns %> </th>
                        <th ><%= otrosPos %> </th>
                        <th ><%= otrosTrat %> </th>

                        <th ><%= TIns %>  </th>
                        <th ><%= TPos %>  </th>
                        <th ><%= TElim %>  </th>
                        <th ><%= TTrat %> </th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</script>


