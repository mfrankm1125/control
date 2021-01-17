<style>


</style>
<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title" style="display: inline-block">Vigilancia Realizadas </h3>
                <h3 class="panel-title" style="display: inline-block;padding-right: 0px;">
                    <select id="anioVigiViReal" class="form-control input-sm" style="display: inline-block" >
                        <?php foreach($aniosregsvigilancia as $ik){?>
                        <option id="<?=$ik['aniosregs']?>"><?=$ik['aniosregs']?></option>
                        <?php }?>
                    </select>
                </h3>
                <h3 class="panel-title" style="display: inline-block;padding-left: 0px;">
                    <select id="provinciaVigiViReal" class="form-control input-sm" style="display: inline-block" >
                        <?php foreach($provinciasregvigilancia as $ik){?>
                            <option id="<?=$ik['provincia']?>"><?=$ik['provincia']?></option>
                        <?php }?>
                    </select>
                </h3>
                <h3 class="panel-title" style="display: inline-block;padding-left: 0px;">
                    <button type="button" class="btn btn-sm btn-primary" id="btnActualizaDtTable">Actualizar</button>
                </h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;">
                <br>
                <div class="table-toolbar-left">
                    <!--<button class="btn btn-purple" id="add"><i class="demo-pli-add icon-fw"></i> Agregar</button> -->
                    <!--<button class="btn btn-purple" id="btnAdd"><i class="demo-pli-add icon-fw"></i> Agregar Nuevo</button>-->
                    <button class="btn btn-purple" id="btnOpenModalAddCSV"><i class="demo-pli-add icon-fw"></i> Importar datos desde CSV</button>
                    <div class="btn-group">
                        <button class="btn btn-default" onclick="refrescar()"  ><i class="demo-pli-refresh" ></i>Refrescar</button>

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
                        <th title="Total Vivienda Inspeccioanadas">T.V.Ins </th>
                        <th title="Total Vivienda Tratadas">T.V.Tra </th>
                        <th title="Total Vivienda Positivas">T.V.Pos </th>
                        <th>Consumo Larvicidad(gr)</th>
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


<div class="modal fade"   id="modalVerDetaill" role="dialog"  data-backdrop="static" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 98%;">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id=""></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="modalBodyVerDetaill" >

            </div>
        </div>
    </div>
</div>


<div class="modal fade"   id="modalReportSemanas" role="dialog"  data-backdrop="static" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 90%">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id=""></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="idModalBodyReportSemanas">

            </div>
        </div>
    </div>
</div>

<div id="tmpTablesForExport" style="display: none">


</div>

<?php echo $_css?>
<?php echo $_js?>

<script type="text/javascript">
    var fileExtension = ['csv'];
    var maxSizeFile=parseInt("<?=$this->config->item('max_size_file');?>");
    var nameFilePreviewDiario=null;
    var dataTableIni =null;
    var iniAnio="";
    var iniProvincia="";
    arrayMesesFull
    function scrollHandle (e){
        var scrollTop = this.scrollTop;
        this.querySelector('thead').style.transform = 'translateY(' + scrollTop + 'px)';
    }

    $(document).ready(function () {
        iniDataTable();
    });

    $(document).on("click",'#btnActualizaDtTable',function () {
        var new_url=url_base+'insviviconsolidado/getDataTable/'+$("#anioVigiViReal").val()+"/"+$("#provinciaVigiViReal").val();
        dataTableIni.ajax.url(new_url).load();
    });

    function iniDataTable() {
        var urix=$("#anioVigiViReal").val()+"/"+$("#provinciaVigiViReal").val();
        dataTableIni= $('#tabla_grid').DataTable({
            "ajax": url_base+'insviviconsolidado/getDataTable/'+urix,
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
                        var html = full.sumviviendainsptotal;
                        //var lastname = full.apellidos;

                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                    var html = full.sumtotalviviendastratadas;
                    //var lastname = full.apellidos;

                    return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                    var html = full.sumtotalviviendaspositivas;
                    //var lastname = full.apellidos;

                    return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                    var html = full.sumconsumolarvicidagr;
                    //var lastname = full.apellidos;

                    return html;
                }
                },

                {  sortable: false,
                    "render": function ( data, type, full, meta ) {
                        var anio = full.anio;
                        var provincia = full.distrito;
                        var distrito = full.localidad;
                        var ipress = full.descipress;
                        var dtx=JSON.stringify(full);

                        //console.log(dtx);
                        html="";
                        //html='<a href="javascript:void(0)"  onclick="ver('+id+');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>';
                        html='<a href="javascript:void(0)"  onclick="ver(\''+anio +'\',\''+provincia +'\',\''+distrito +'\',\''+ipress+'\');" class="btn btn-mint btn-xs"><i class="fa fa-eye"></i> Ver</a>';
                        html=html+'<a href="javascript:void(0)"  onclick="verXsemana(\''+anio +'\',\''+provincia +'\',\''+distrito +'\',\''+ipress+'\');" class="btn btn-mint btn-xs"><i class="fa fa-eye"></i> Ver x Semana</a>';
                        html=html+"&nbsp;<a href='javascript:void(0);'  onclick='reporte("+dtx+",0);' class='btn btn-dark btn-xs' ><i class='fa fa-file-text-o' ></i> Reporte</a>";


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


        dataTableIni.on( 'order.dt search.dt', function () {
            dataTableIni.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    }
    function refrescar () {
        dataTableIni.ajax.reload();
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

    function ver(anio,prov,distrito,ipress) {
        open_modal("modalVerDetaill");
        iniVerDetaillByIpressAnio(anio,prov,distrito,ipress);
       // location.href=url_base+'insviviconsolidado/seeDetaill/1/'+ipress+'/'+fecha;
    }

    function iniVerDetaillByIpressAnio(anio,provincia,distrito,ipress) {
        var text={"anio":anio,"provincia":provincia,"distrito":distrito,"ipress":ipress};
        $("#modalBodyVerDetaill").html("<h3>Cargando...</h3>");
        var tmpVerDetaillVigilanciaMesesByIpressAnio=_.template($("#tmpVerDetaillVigilanciaMesesByIpressAnio").html());
        $.post(url_base+"insviviconsolidado/getDataMesVigbyAnioUbigeo",{"provincia":provincia,"distrito":distrito,"ipress":ipress,"anio":anio},function (data) {
            //console.log(data);
            $("#modalBodyVerDetaill").html(tmpVerDetaillVigilanciaMesesByIpressAnio({nivel:1,text:text,data:data}));
        },'json');
    }



    function deleteTr(thisx) {
        if(!confirm("¿Desea eliminar este registro?")){return 0;}
        var ctx=$(thisx);
        ctx.closest("tr").remove();
       // console.log();
    }

    function reporte(datax,meses) {
        open_modal("modalReport");
        var tmpModalBodyReportVigilancia=_.template($("#tmpModalBodyReportVigilancia").html());
        $.post(url_base+"insviviconsolidado/getReportVigilanciaByMesAnioIpress",{"anio":datax.anio,"provincia":datax.distrito,"distrito":datax.localidad,"ipress":datax.descipress,meses:meses},function (data) {

            $("#idModalBodyReport").html(tmpModalBodyReportVigilancia({data:data.data.result,meses:data.data.meses,dataini:datax}))
        },'json');
    }

    function reportModalExcel() {
        tableToExcel(["tbtitle","tb1","tb2","tb3","tb4"],"e.xls");
    }
    function exportExcel(tables,name) {
        console.log(tables);
       // $("#tablaMesIpress").find(".columAccion").remove();
        tableToExcel(tables,name);

    }

    var tableToExcel = (function() {

        var uri = 'data:application/vnd.ms-excel;base64,'
            , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body> {table} </body></html>'
            , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
            , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) };
        return function(tables, name) {
            console.log(tables);
            if(tables.length >0 ){
                var arrT="";
                var ttmp;
                for(var xi in tables){

                    //console.log(table[xi]);
                    if(document.getElementById(tables[xi])){


                        ttmp=document.getElementById(tables[xi]);
                        var tableTmp="<table border='1'>"+ttmp.innerHTML+"</table>";
                        $("#tmpTablesForExport").html(tableTmp);
                        $("#tmpTablesForExport").find(".columAccion").remove();
                        var tfinal=$("#tmpTablesForExport").html();
                         //var RR=$(tableTmp).find(".columAccion").remove(".columAccion");
                       // console.log(tfinal);
                        arrT=arrT+tfinal+"<br>";

                    }else{
                        console.log("ID table no existe:"+tables[xi]);
                    }
                    $("#tmpTablesForExport").html("");
                    //if (!table[xi].nodeType) table[xi] = document.getElementById(table[xi]);
                }
                /*console.log(arrT);
                console.log(table.innerHTML);*/
                var ctx = {worksheet: "ddd.xls" || 'Worksheet', table:arrT  };
                window.location.href = uri + base64(format(template, ctx));
            }

        }
    })();


    function verDetailDiasByAnioMesUbigeo(anio,provincia,distrito,ipress,mesanio) {
        var text={"anio":anio,"provincia":provincia,"distrito":distrito,"ipress":ipress,"mesanio":mesanio};
        $("#modalBodyVerDetaill").html("<h3>Cargando...</h3>");
        var tmpVerDetaillVigilanciaMesesByIpressAnio=_.template($("#tmpVerDetaillVigilanciaMesesByIpressAnio").html());
        $.post(url_base+"insviviconsolidado/getDataDiasVigbyAnioUbigeo",{"anio":anio,"provincia":provincia,"distrito":distrito,"ipress":ipress,"mesanio":mesanio},function (data) {
            $("#modalBodyVerDetaill").html(tmpVerDetaillVigilanciaMesesByIpressAnio({nivel:2,text:text,data:data}));
        },'json');

    }

    function verDetalleFormatRegistro(json) {
        var tmpVerDetaillVigilanciaDiasByIpressMesAnio=_.template($("#tmpVerDetaillVigilanciaDiasByIpressMesAnio").html());
        $("#divTableSeeFormatDetail").html("<h3>Cargando...</h3>");
        var hta='<a style="font-size: 12px;" href="javascript:void(0)" onclick="verDetailDiasByAnioMesUbigeo(\''+json.anio+'\',\''+json.provincia+'\',\''+json.distrito+'\',\''+json.ipress+'\',\''+json.mesanio+'\')" class="btn-link">Átras</a>';
        $("#divBtnVerDetalleFull").html("");
        $("#divBtnAtras").html(hta);
        $.post(url_base+"insviviconsolidado/getDataDetaillInpsVivienda/",{"provincia":json.provincia ,"distrito":json.distrito,"ipress":json.ipress,"mesanio":json.mesanio,tipo:"mesanio"},function (data) {
           // $("#tTbodyView").html(tmpTbodyDetail({data:data}));
            $("#divTableSeeFormatDetail").html(tmpVerDetaillVigilanciaDiasByIpressMesAnio({data:data}));
        },'json');
        console.log(json);
    }

    function verDetalleVigiForTipoByMesAnio(json,nivel) {

        var tmpVerDetaillVigilanciaDiasByIpressMesAnio=_.template($("#tmpVerDetaillVigilanciaDiasByIpressMesAnio").html());
        var tmpTableVerVigilanciaByTipoNivel=_.template($("#tmpTableVerVigilanciaByTipoNivel").html());

        $("#divTableSeeFormatDetail").html("<h3>Cargando...</h3>");
        var hta='<a style="font-size: 12px;" href="javascript:void(0)" onclick="verDetailDiasByAnioMesUbigeo(\''+json.anio+'\',\''+json.provincia+'\',\''+json.distrito+'\',\''+json.ipress+'\',\''+json.mesanio+'\')" class="btn-link">Átras</a>';
        $("#divBtnVerDetalleFull").html("");
        $("#divBtnAtras").html(hta);
        $.post(url_base+"insviviconsolidado/getDataDetaillInpsVivienda/",{"provincia":json.provincia ,"distrito":json.distrito,"ipress":json.ipress,"mesanio":json.mesanio,tipo:"mesanio",'nivel':nivel},function (data) {
            // $("#tTbodyView").html(tmpTbodyDetail({data:data}));
            if(nivel =="adetalle"){
                $("#divTableSeeFormatDetail").html(tmpVerDetaillVigilanciaDiasByIpressMesAnio({data:data}));
            }else {
                $("#divTableSeeFormatDetail").html(tmpTableVerVigilanciaByTipoNivel({data:data,nivel:nivel}))
            }

        },'json');
        //console.log(json);
    }

    function btnUpdateReport(dataini) {
        var checks=$("input[name='mesesReport[]']");
        var st="";
        if(checks.length > 0){
            $.each(checks,function (k,i) {
                if($(i).is(':checked')){
                    var coma=",";
                    if(st.length==0){
                        coma="";
                    }
                    st+=coma+$(i).val()
                }
            });
        }else{
            st=0;
        }
          var tmpdivRowReport=_.template($("#tmpdivRowReport").html());
        $.post(url_base+"insviviconsolidado/getReportVigilanciaByMesAnioIpress",{"anio":dataini.anio,"provincia":dataini.distrito,"distrito":dataini.localidad,"ipress":dataini.descipress,meses:st},function (data) {
            //$("#idModalBodyReport").html(tmpModalBodyReportVigilancia({data:data.data.result,meses:data.data.meses}))
              $("#divRowReport").html(tmpdivRowReport({data:data.data.result,meses:data.data.meses,dataini:dataini}));
        },'json');
       // console.log(st);
    }

    ////--------------------------------------------------------------

    function verXsemana(anio,provincia,distrito,ipress) {
     open_modal("modalReportSemanas");
     var tmpModalReportSemanas=_.template($("#tmpModalReportSemanas").html());
     $("#idModalBodyReportSemanas").html(tmpModalReportSemanas({"anio":anio,"provincia":provincia,"distrito":distrito,"ipress":ipress}));
    }

    function btnReportarByNivelVigilancia(anio,provincia,distrito,ipress) {
        var nivel=$("#NivelReport").val();
        $.post(url_base+"insviviconsolidado/getReportVigilanciaBySemanas",{"anio":anio,"provincia":provincia,"distrito":distrito,"ipress":ipress,"nivel":nivel},function (data) {


            var tmpDivDataByOpcionNivel=_.template($("#tmpDivDataByOpcionNivel").html());
            $("#divDataByOpcionNivel").html(tmpDivDataByOpcionNivel({data:data,nivel:nivel}));
            //return 0;
            $.each(data,function(k,i){
                $.each(i.semanas,function (kk,ii) {
                    var idtd=null;
                      if(nivel =="distrito"){
                        var provincia =i.iddistrito;
                        provincia=provincia.replace(/ /g, "");
                        var distrito =i.idlocalidad;
                        distrito=distrito.replace(/ /g, "");
                        idtd='#idTd-'+provincia+'-'+distrito+"-"+ii.semana   ;
                      }
                      if(nivel =="ipress"){
                        var provincia =i.iddistrito;
                        provincia=provincia.replace(/ /g, "");
                        var distrito =i.idlocalidad;
                        distrito=distrito.replace(/ /g, "");
                        var ipress =i.ipress;
                        ipress=ipress.replace(/ /g, "");
                          idtd='#idTd-'+provincia+'-'+distrito+'-'+ipress+"-"+ii.semana    ;
                     }
                     if(nivel =="sector"){
                        var provincia =i.iddistrito;
                        provincia=provincia.replace(/ /g, "");
                        var distrito =i.idlocalidad;
                        distrito=distrito.replace(/ /g, "");
                        var ipress =i.ipress;
                        ipress=ipress.replace(/ /g, "");
                         idtd='#idTd-'+provincia+'-'+distrito+'-'+ipress +'-'+ Number(i.sector)+"-"+ii.semana  ;
                      }
                      if(nivel =="manzana"){
                        var provincia =i.iddistrito;
                        provincia=provincia.replace(/ /g, "");
                        var distrito =i.idlocalidad;
                        distrito=distrito.replace(/ /g, "");
                        var ipress =i.ipress;
                        ipress=ipress.replace(/ /g, "");
                          idtd='#idTd-'+provincia+'-'+distrito+'-'+ipress +'-'+ Number(i.sector) +'-'+ Number(i.nmanzana)+"-"+ii.semana ;
                      }


                    var color="";
                    var label="";
                    var IndiAedico="-";
                    if(Number(ii.sumviviendainsptotal) > 0){
                        IndiAedico=(Number(ii.ia)*100).toFixed(2);
                        if(IndiAedico < 1){
                            color="#6fc36f";
                            label="Bajo riesgo";
                        }else if( IndiAedico >= 1 && IndiAedico < 2){
                            color="#f7ea75";
                            label="Mediano riesgo";
                        }else if(IndiAedico >= 2 ){
                            color="#f77777";
                            label="Alto riesgo";
                        }
                    }
                    $(idtd).html("<b title='"+label+"'>"+IndiAedico+"</b>");
                    $(idtd).css("background-color",color);
                });

            });
        },'json');
    }
    var optLineChartReport;
    var colorLine=["#6c757d","#f7a35c","#7cb5ec","#90ed7d","#28a745","#e0b020","#9c27b0","#e91e63"];
    function verGraficaBySemanas(datay,nivel) {
     var divGrafica=$("#divGrafica");
         divGrafica.css("display","block");
         var divDataByOpcionNivelMAPA=$("#divDataByOpcionNivelMAPA");
        divDataByOpcionNivelMAPA.css("height","400px");
        iniLineChartReport();
        var dataGraf=[];
        var titleChart="";
        $.each(datay,function(k,i){
            var dt=[];
            $.each(i.semanas,function (kk,ii) {
                if(Number(ii.sumviviendainsptotal) > 0){
                    var ia=Number(parseFloat(Number(ii.ia)*100).toFixed(2));
                    dt.push({"name":ii.semana,"y":ia});
                }

            });
            if(nivel == "distrito"){
                dataGraf.push({"name":""+i.idlocalidad,"data":dt,"color":getRandomColor()});
                titleChart=" "+i.idlocalidad;

            }else if(nivel == "ipress"){
                dataGraf.push({"name":""+i.ipress ,"data":dt,"color":getRandomColor()});
                titleChart=" "+i.idlocalidad+" "+i.ipress ;
            }else if(nivel == "sector"){
                dataGraf.push({"name":"Sector "+i.sector,"data":dt,"color":getRandomColor()});
                titleChart=" "+i.idlocalidad+" "+i.ipress+" x sector" ;
            }else if(nivel == "manzana"){
                dataGraf.push({"name":"Manzana "+i.nmanzana,"data":dt,"color":getRandomColor()});
                titleChart=" "+i.idlocalidad+" "+i.ipress+" x sector y manzana" ;
            }

        });

        optLineChartReport.chart.renderTo="divDataByOpcionNivelMAPA";
         optLineChartReport.series =dataGraf;
        optLineChartReport.yAxis.title.text="Indice Aédico";
        optLineChartReport.tooltip.pointFormat='Indice Aédico: <b>{point.y:.2f}</b>';

        var chart = new Highcharts.chart(optLineChartReport);
        chart.setTitle({ text:titleChart});
     //$("#divDataByOpcionNivel").html();

    }
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    function iniLineChartReport() {
        //alert("x");
        optLineChartReport ={
            chart: {
                renderTo: '',
                type: 'line'

            },
            exporting: {
                chartOptions: { // specific options for the exported image
                    plotOptions: {
                        series: {
                            dataLabels: {
                                enabled: true
                            }
                        }
                    }
                },
                fallbackToExportServer: false
            },
            title: {
                text: '.'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                },
                title: {
                    text: 'Semana'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Nro. Huevos'
                }
            },
            legend: {
                enabled: true
            },
            tooltip: {
                //pointFormat: '{point.x}Nro. Huevos: <b> {point.y:.2f}  </b>',
                shared:true,
                split: false,
                enabled: true
            },
            plotOptions: {
                line: {
                    events:{
                        legendItemClick: function(event) {

                            if (!this.visible)
                                return true;
                            var seriesIndex = this.index;
                            var series = this.chart.series;
                            for (var i = 0; i < series.length; i++)
                            {
                                if (series[i].index != seriesIndex)
                                {

                                    series[i].visible ? series[i].hide() : series[i].show();
                                }
                            }
                            return false;
                        }
                    },
                    dataLabels: {
                        enabled: true
                    }

                },
                series: {
                    cursor: 'pointer',
                    marker:{
                        radius:6
                    },
                    point: {
                        events: {
                            click: function () {
                                // console.log(this);
                            }
                        }
                    }

                }
            },
            series: [{
                name: 'Semana',
                dataLabels: {
                    allowOverlap: true,
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'left',
                    format: '{point.y:.2f}', // one decimal 1f
                    y:0, // 10 pixels down from the top
                    style: {
                        fontSize: '11px',
                        fontFamily: 'Verdana, sans-serif',
                        border:"bold"

                    },
                    crop:false,
                    overflow:"allow"
                }
            }],
            colors: ['#50B432']
        }
    }
    function closeGráfica() {
        $("#divGrafica").css("display","none");
        $("#divDataByOpcionNivelMAPA").html("");
    }

    function deleteDetailDiasByAnioMesUbigeo(thisx,anio,distrito,localidad,descipress,fecha){
        var btn=$(thisx);
        var tr=$(thisx).closest("tr");

        if(!confirm("¿Desea eliminar este registro?")){ return 0;}
        btn.button("loading");
        $.post(url_base+"insviviconsolidado/deteleteRegVigilanciaByDistritoIpresMesAnio",{"anio":anio,"distrito":distrito,"localidad":localidad,"descipress":descipress,"fecha":fecha},function (data) {
            //console.log(data);
            if(data.status == "ok"){
                alert_success("Se realizo correctamente");
                tr.remove();
            }else{
                alert_error("Error ups :(");
            }
            btn.button("reset");
            refrescar();
        },'json');

    }
    var dataChartByVigiOvi=[];
    function verOvitrampasVigilancia(thisx,anio,distrito,localidad,descipress,fecha) {
        dataChartByVigiOvi=[];
        var dataI={"anio":anio,"distrito":distrito,"localidad":localidad,"descipress":descipress,"fecha":fecha};
        var tmpVerVigilianciaByOvi=_.template($("#tmpVerVigilianciaByOvi").html());
        $.post(url_base+"insviviconsolidado/verOvitrampasVigilancia" ,{"anio":anio,"distrito":distrito,"localidad":localidad,"descipress":descipress,"fecha":fecha},function (data) {
            $("#modalBodyVerDetaill").html(tmpVerVigilianciaByOvi({dataini:dataI,data:data}));
        },'json');

    }

    function getLabelColorByIA(ia) {
        var IndiAedico=Number(ia);
        var color="";
        var label="";
        if(IndiAedico < 1){
            color="#6fc36f";
            label="Bajo riesgo";
        }else if( IndiAedico >= 1 && IndiAedico < 2){
            color="#f7ea75";
            label="Mediano riesgo";
        }else if(IndiAedico >= 2 ){
            color="#f77777";
            label="Alto riesgo";
        }

        return {"color":color,"label":label}
    }

    function verGraficaVigilanciaByOvitrampa() {
        console.log(dataChartByVigiOvi);
        var divContGrafVigOvi=$("#divContGrafVigOvi");
        divContGrafVigOvi.css("display","block");
        iniLineChartReport();
        optLineChartReport.chart.renderTo="divGrafVigOvi";
        optLineChartReport.series[0].data =dataChartByVigiOvi;
        optLineChartReport.yAxis.title.text="Indice Aédico";
        optLineChartReport.tooltip.pointFormat='Indice Aédico: <b>{point.y:.2f}</b>';

        var chart = new Highcharts.chart(optLineChartReport);
       // chart.setTitle({ text:titleChart});

    }
    function closeGraficaVigilanciaByOvitrampa() {
        var divContGrafVigOvi=$("#divContGrafVigOvi").css("display","none");
        var divGrafVigOvi=$("#divGrafVigOvi").html("");
    }
</script>

<script type="text/template" id="tmpVerVigilianciaByOvi">
    <div class="row">
        <div class="col-lg-12">
            <h4>
                 <%
                var txt= dataini.anio +" "+dataini.distrito+" "+dataini.localidad +" "+dataini.descipress +" "+dataini.fecha;
                print(txt);
                %>

                <span id="divBtnAtras">
                  <a style="font-size: 12px;" href="javascript:void(0)" onclick="iniVerDetaillByIpressAnio('<%= dataini.anio %>','<%= dataini.distrito %>','<%= dataini.localidad %>','<%= dataini.descipress %>')" class="btn-link" >Átras</a>
                </span>
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <button type="button" class="btn btn-xs btn-success" onclick="exportExcel(['tbxys'],'e.xls')" >Exportar</button>
            <button type="button" class="btn btn-xs btn-success" onclick="verGraficaVigilanciaByOvitrampa()" >Ver Gráfica</button>
            <div class="col-lg-12" id="divContGrafVigOvi" style="display: none;" >
                <button type="button" class="btn-link btn-xs" style="float: right" onclick="closeGraficaVigilanciaByOvitrampa()"> Cerrar Gráfica</button>
                <div class="col-lg-12"  id="divGrafVigOvi" style="height: 400px;">

                </div>
            </div>
            <div class="table table-responsive">
                 <table class="table table-bordered table-condensed table-hover" id="tbxys">
                     <thead>
                        <tr>
                            <th>#</th>
                            <th>Ovitrampa</th>
                            <th style="width: 101px">F.inter.</th>
                            <th style="width: 101px">T.V.Insp</th>
                            <th style="width: 101px">T.V.Positivas</th>
                            <th style="width: 151px">F - I.A</th>
                            <th style="width: 101px">Consumo Larvi</th>
                            <th style="width: 101px">I.A</th>
                        </tr>
                     </thead>
                     <tbody>
                     <% _.each(data,function(i,k){%>
                     <tr>
                         <td><%=(k+1)%></td>
                         <td><%=i.codconceroovi%></td>
                         <td style="padding: 0px;" colspan="5">
                             <table class="table table-bordered table-hover table-condensed" style="margin:0px;">
                                 <% var  xiia=0;
                                    var smvvins=0;
                                    var smvvpos=0;
                                 _.each(i.data,function(ii,kk){
                                    var vvins=0;
                                    var vvpos=0;
                                    var isumlarvi=0;
                                    var iia=0;
                                    var icolor="black;";
                                    var ilabel="";



                                 if(Number(ii.sviviendainsptotal) > 0){
                                   iia=Number(ii.stotalviviendaspositivas)/Number(ii.sviviendainsptotal)*100;
                                   iia= parseFloat(Number((iia).toFixed(2)));
                                    var colorLabel=getLabelColorByIA(iia);
                                     ilabel=colorLabel.label;
                                     icolor=colorLabel.color;
                                 }

                                 %>
                                 <tr>
                                     <td style="width: 100px" >
                                         <%= ii.fechaintervencion%>
                                     </td>
                                     <td style="width: 100px" >
                                         <%= ii.sviviendainsptotal %>
                                     </td>
                                     <td style="width: 100px" >
                                         <%= ii.stotalviviendaspositivas%>
                                     </td>
                                     <td style="width: 150px"  >
                                         <% if(Number(ii.sviviendainsptotal) > 0){

                                         smvvins=smvvins+Number(ii.sviviendainsptotal);
                                         smvvpos=smvvpos+Number(ii.stotalviviendaspositivas);

                                         %>
                                         <b style="color:<%=icolor%> ;"><%= iia %>% </b> <%=ilabel%>
                                         <% }else{ print("-"); } %>
                                     </td>
                                     <td style="width: 100px" >
                                         <%= ii.sconsumolarvicidagr %>
                                     </td>
                                 </tr>
                                 <% }); %>
                             </table>
                         </td>
                          <%
                          var clIA="black;";
                         var lbIA="";
                         var iiixia=0;
                         if(smvvins > 0){
                         xiia= parseFloat((smvvpos/smvvins)*100).toFixed(2);
                         var colorLabelIA=getLabelColorByIA(xiia);
                         clIA=colorLabelIA.color;
                         lbIA=colorLabelIA.label;
                         iiixia=xiia;
                         xiia=xiia+"%";

                         dataChartByVigiOvi.push({"name":i.codconceroovi,"y":Number(iiixia)});

                         }else{
                         xiia="-";
                         }

                         %>
                         <td style="width: 150px;"><b style="color:<%=clIA%>"><%=xiia%></b> <%=lbIA%> </td>
                     </tr>
                     <%
                     });%>

                     </tbody>
                 </table>
             </div>
        </div>
    </div>


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
            <h5 style="margin-bottom: 0px;">Meses</h5>
            <% _.each(meses,function(i,k){ %>
            <div class="checkbox" style="display: inline-block;">
                <input id="<%=i.mes%>" class="magic-checkbox" type="checkbox" checked name="mesesReport[]" value="<%= i.mes%>"  >
                <label for="<%=i.mes%>"><%=arrayMesesFull[Number(i.mes)]%></label>
            </div>
            <% }); %>
            <button type='button' class='btn btn-success btn-sm' onclick='btnUpdateReport(<%=JSON.stringify(dataini)%>)'>Actualizar</button>
        </div>
    </div>
    <div class="row">
       <hr style=" margin-bottom: 8px; margin-top: 5px;">
    </div>
    <div class="row">
        <div class="col-lg-5">
            <button type="button" style="margin-bottom: 8px;" onclick="reportModalExcel()" class="btn btn-primary btn-xs">Excel</button>
        </div>
    </div>
    <div class="row" id="divRowReport">
        <div class="col-lg-12">
            <table id="tbtitle"> <tr>
                    <th><%=dataini.anio%> <%=dataini.distrito%> <%=dataini.localidad%> <%=dataini.descipress%> |

                        <% _.each(meses,function(i,k){
                        print("&nbsp;"+arrayMesesFull[Number(i.mes)]);
                        });
                        %>

                    </th>
                </tr>
            </table>
        </div>
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
<script type="text/template" id="tmpdivRowReport">
    <div class="col-lg-12">
        <table id="tbtitle" > <tr>
                <th><%=dataini.anio%> <%=dataini.distrito%> <%=dataini.localidad%> <%=dataini.descipress%> |

                    <% _.each(meses,function(i,k){
                      print("&nbsp;"+arrayMesesFull[Number(i.mes)]);
                    });
                    %>

                </th>
            </tr>
        </table>
    </div>
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
</script>

<!----------------------------------------------------------------------------------------------------------------->

<script type="text/template" id="tmpVerDetaillVigilanciaMesesByIpressAnio">
<div class="row">
    <div class="col-lg-12">
        <h4>
            <% var lableFecha="Mes Intervención";
               var txt="";
                 if(nivel==1) {
                   txt=text.anio+" "+text.provincia+" "+text.distrito +" "+text.ipress;
                   print(txt);
                 }

              if(nivel==2) {
                lableFecha="Fecha Intervención";
                txt= text.provincia+" "+text.distrito +" "+text.ipress +" "+text.mesanio;
                print(txt);
               %>
                <span id="divBtnVerDetalleFull">
                     <div class="btn-group">
                           <button class=" btn-xs btn btn-default btn-active-pink dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button" aria-expanded="false">
                               Ver <i class="dropdown-caret"></i>
                           </button>
                           <ul class="dropdown-menu dropdown-menu-right">
                               <li><a href='javascript:void(0)' onclick='verDetalleVigiForTipoByMesAnio(<%=JSON.stringify(text)%>,"sector")' >Sector</a> </li>
                               <li><a href='javascript:void(0)' onclick='verDetalleVigiForTipoByMesAnio(<%=JSON.stringify(text)%>,"manzana")' >Manzana</a></li>
                               <li><a href='javascript:void(0)' onclick='verDetalleVigiForTipoByMesAnio(<%=JSON.stringify(text)%>,"adetalle")' >A Detalle</a></li>
                           </ul>
                       </div>
                    <!--<button type='button' class='btn btn-xs btn-primary' onclick='verDetalleFormatRegistro(<%=JSON.stringify(text)%>)' >Ver a detalle</button>-->
                </span>
                <span id="divBtnAtras">
                  <a style="font-size: 12px;" href="javascript:void(0)" onclick="iniVerDetaillByIpressAnio('<%= text.anio %>','<%= text.provincia %>','<%= text.distrito %>','<%= text.ipress %>')" class="btn-link" >Átras</a>
                </span>
             <%  } %>


        </h4>

    </div>
    <div class="col-lg-12"  >

       <div class="table-responsive" id="divTableSeeFormatDetail" style="min-height: 200px;max-height: 500px;">
           <button type="button" onclick="exportExcel(['tablaMesIpress'],'d.xls');"   class="btn btn-xs btn-success"><i class="fa fa-file-excel-o" ></i> Exportar</button>

           <table id="tablaMesIpress" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
               <thead>
               <tr style="text-align: right">
                   <th>#</th>
                   <th>IPRESS</th>
                   <th>Total viviendas Insp.</th>
                   <th>Total viviendas Cerradas</th>
                   <th>Total viviendas Deshabitada</th>
                   <th>Total viviendas Renuente</th>
                   <th>Total viviendas Positivas</th>
                   <th>Total viviendas tratadas</th>
                   <th>Consumo larvicida (gr)</th>
                   <th title="Índice Aédico">I.A %</th>

                   <th> <%=lableFecha%></th>
                   <% if(nivel==1) { %>
                   <th class="columAccion"  style="width: 115px;">Acción</th>
                   <% } %>

               </tr>
               </thead>
               <tbody id="tbodyMesIpress">
               <%

               var tVIVIIns=0;
               var tVIVICerrada=0;
               var tVIVIDeshabitada=0;
               var tVIVIRenuhente=0;
               var tVIVIpos=0;

               var tVIVItratados=0;
               var tVIViUsoLarvicida=0;

               _.each(data,function(i,k){
               var arrIA=["-","<span style='color:green'><b>Bajo Riesgo</b></span>","<span style='color:orangered'><b>Mediano Riesgo</b></span>","<span style='color:red'><b>Alto Riesgo</b></span>"];
               var nviviendasposi = parseFloat(Number(i.sumtotalviviendaspositivas));
               var nviviendascerrada = parseFloat(Number(i.sumcerrada));
               var nviviendasdeshabitada = parseFloat(Number(i.sumdeshabitada));
               var nviviendasrenuente = parseFloat(Number(i.sumrenuente));

               var nviviendasinsp =parseFloat( Number(i.sumviviendainsptotal));
               var nviviendastratadas = parseFloat(Number(i.sumtotalviviendastratadas));
               var sumUsoLarvicida = parseFloat(Number(i.sumconsumolarvicidagr));


               var r=0;
               var ht="-";
               var xxs=""

               tVIVIIns=tVIVIIns+nviviendasinsp;
               tVIVIpos=tVIVIpos+nviviendasposi;
               tVIVItratados=tVIVItratados+nviviendastratadas;
               tVIViUsoLarvicida=tVIViUsoLarvicida+sumUsoLarvicida;

               tVIVICerrada=tVIVICerrada+nviviendascerrada;
               tVIVIDeshabitada=tVIVIDeshabitada+nviviendasdeshabitada;
               tVIVIRenuhente=tVIVIRenuhente+nviviendasrenuente;


               if(nviviendasinsp > 0){
                   r=Number((nviviendasposi/nviviendasinsp)*100);
                   r=parseFloat(r).toFixed(2);
                   if(r >=0 && r < 1 ){
                        ht=arrIA[1];
                   }else if(r >=1 && r < 2 ){
                        ht=arrIA[2];
                   }else if(r >=3 ){
                        ht=arrIA[3];
                   }
               }
               xxs=r+"% - "+ht;

               %>
               <tr style="text-align: right" >
                   <td ><%= (k+1)%> </td>
                   <td ><%= i.descipress %>  </td>
                   <td ><%= i.sumviviendainsptotal %>  </td>
                   <td ><%= i.sumcerrada %>  </td>
                   <td ><%= i.sumdeshabitada %>  </td>
                   <td><%=i.sumrenuente%></td>
                   <td ><%= i.sumtotalviviendaspositivas %>  </td>
                   <td ><%= i.sumtotalviviendastratadas %>  </td>
                   <td ><%= i.sumconsumolarvicidagr %>  </td>
                   <td><%=xxs%></td>
                   <td><%=i.fecha%>

                   </td>
                   <% if(nivel==1) { %>
                   <td class="columAccion" >
                       <a href="javascript:void(0)" onclick="verDetailDiasByAnioMesUbigeo('<%= i.anio %>','<%= i.distrito %>','<%= i.localidad %>','<%= i.descipress %>','<%= i.fecha %>')" class="btn btn-xs btn-mint" > Ver </a>
                       <a href="javascript:void(0)" onclick="verOvitrampasVigilancia(this,'<%= i.anio %>','<%= i.distrito %>','<%= i.localidad %>','<%= i.descipress %>','<%= i.fecha %>')" class="btn btn-xs btn-info" title="Eliminar" >Ver x Ovi. </a>
                       <a href="javascript:void(0)" onclick="deleteDetailDiasByAnioMesUbigeo(this,'<%= i.anio %>','<%= i.distrito %>','<%= i.localidad %>','<%= i.descipress %>','<%= i.fecha %>')" class="btn btn-xs btn-danger" title="Eliminar" > Elim. </a>

                       <!--<a href="javascript:void(0)" onclick="ReporteDetailDiasByAnioMesUbigeo('<%= i.anio %>','<%= i.distrito %>','<%= i.localidad %>','<%= i.descipress %>','<%= i.fecha %>')"  class="btn btn-xs btn-mint"> Reporte </a>-->

                   </td>
                   <% } %>

               </tr>
               <% });%>
               </tbody>
               <tfoot>
               <tr style="text-align: right;font-weight: bold" >
                   <td></td>
                   <td>Total</td>
                   <td><%=tVIVIIns%></td>
                   <td><%=tVIVICerrada%></td>
                   <td><%=tVIVIDeshabitada%></td>
                   <td><%=tVIVIRenuhente%></td>

                   <td><%=tVIVIpos%></td>
                   <td><%=tVIVItratados%></td>
                   <td><%=tVIViUsoLarvicida.toFixed(2)%></td>
                   <td></td>
                   <td></td>

                   <% if(nivel==1) { %>
                   <td class="columAccion" ></td>
                   <% } %>
               </tr>
               </tfoot>
           </table>
       </div>
    </div>
</div>
</script>

<script type="text/template" id="tmpVerDetaillVigilanciaDiasByIpressMesAnio">
    <button type="button" onclick="exportExcel(['tbVigilanciaFullDetaill'],'d.xls');"   class="btn btn-xs btn-success"><i class="fa fa-file-excel-o" ></i> Exportar</button>
    <table style="border-collapse: separate !important;" class="table table-bordered table-hover table-condensed tx" id="tbVigilanciaFullDetaill" >
        <thead style="background-color: white;" >
        <tr style="font-size: 10px;">
            <th  >#</th>
            <th  ><span>DISTRITO</span><span style="visibility: hidden">_____</span> </th>
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
        <tbody id="tTbodyView" style="font-size: 12px;">
        <% _.each(data,function(i,k){ %>



        <tr>
            <td><%=(k+1)%></td>
            <td><%=i.distrito%></td>
            <td><%=i.localidad%></td>
            <td><%=i.descipress%></td>
            <td><%=i.sector%></td>
            <td><%=i.nmanzana%></td>
            <td><%=i.nroovitrampa%></td>
            <td><%=i.totalviviendas%></td>
            <td><%=i.lote%></td>
            <td><%=i.viviendainsptotal%></td>
            <td><%=i.cerrada%></td>

            <td><%=i.deshabitada%></td>
            <td><%=i.renuente%></td>

            <td><%=i.tanquealto_inspeccionado%></td>
            <td><%=i.tanquealto_positivo%></td>
            <td><%=i.tanquealto_tratados%></td>

            <td><%=i.barril_inspeccionado%></td>
            <td><%=i.barril_positivo%></td>
            <td><%=i.barril_tratado%></td>

            <td><%=i.balde_inspeccionado%></td>
            <td><%=i.balde_positivo%></td>
            <td><%=i.balde_tratado%></td>

            <td><%=i.olla_inspeccionado%></td>
            <td><%=i.olla_positivo%></td>
            <td><%=i.olla_tratado%></td>

            <td><%=i.florero_inspeccionado%></td>
            <td><%=i.florero_positivo%></td>
            <td><%=i.florero_tratado%></td>

            <td><%=i.llantas_inspeccionado%></td>
            <td><%=i.llantas_positivo%></td>
            <td><%=i.llantas_tratado%></td>

            <td><%=i.inservibles_inspeccionado%></td>
            <td><%=i.inservibles_positivo%></td>
            <td><%=i.inservibles_eliminado%></td>

            <td><%=i.otros_inspeccionado%></td>
            <td><%=i.otros_positivo%></td>
            <td><%=i.otros_tratados%></td>

            <td><%=i.tinspeccionados%></td>
            <td><%=i.tpositivos%></td>
            <td><%=i.ttratados%></td>

            <td><%=i.teliminados%></td>
            <td><%=i.totalviviendastratadas%></td>
            <td><%=i.totalviviendaspositivas%></td>

            <td><%=i.consumolarvicidagr%></td>
            <td><%=i.semanaentomologica%></td>
            <td><%=i.fechaintervencion%></td>
            <td><%=i.inspector%></td>
            <td><%=i.jefebrigada%></td>
        </tr>
        <% }); %>
        </tbody>
    </table>

</script>

<!----------------------------------------------------------------------------------------------->
<script type="text/template" id="tmpTableVerVigilanciaByTipoNivel">
     <%
        var labelTd="";
        var valueLabelTD="";
         if(nivel =="sector"){
           labelTd="<th>Sector</th>";
         }else if(nivel =="manzana"){
          labelTd="<th>Sector</th><th>Manzana</th>";
        }

     %>
     <button type="button" onclick="exportExcel(['tablaSectorManzanaIpress'],'d.xls');"   class="btn btn-xs btn-success"><i class="fa fa-file-excel-o" ></i> Exportar</button>

     <table id="tablaSectorManzanaIpress" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>#</th>
            <th>IPRESS</th>
            <%=labelTd%>
            <th>Total viviendas Insp.</th>
            <th>Total viviendas Cerradas</th>
            <th>Total viviendas Deshabitada</th>
            <th>Total viviendas Renuente</th>
            <th>Total viviendas Positivas</th>
            <th>Total viviendas tratadas</th>
            <th>Consumo larvicida (gr)</th>
            <th title="Índice Aédico">I.A %</th>

            <th>Fecha</th>
            <!--<th>Acción</th>-->
        </tr>
        </thead>
        <tbody id="tbodyMesIpress">
        <%
        var tVIVIIns=0;
        var tVIVICerrada=0;
        var tVIVIDeshabitada=0;
        var tVIVIRenuhente=0;
        var tVIVIpos=0;


          var tVIVItratados=0;
         var tVIViUsoLarvicida=0;

        _.each(data,function(i,k){
        var arrIA=["-","<span style='color:green'><b>Bajo Riesgo</b></span>","<span style='color:orangered'><b>Mediano Riesgo</b></span>","<span style='color:red'><b>Alto Riesgo</b></span>"];
        var nviviendasposi = parseFloat(Number(i.sumtotalviviendaspositivas));
        var nviviendascerrada = parseFloat(Number(i.sumcerrada));
        var nviviendasdeshabitada = parseFloat(Number(i.sumdeshabitada));
        var nviviendasrenuente = parseFloat(Number(i.sumrenuente));

        var nviviendasinsp = parseFloat(Number(i.sumviviendainsptotal));


        var nviviendastratadas = parseFloat(Number(i.sumtotalviviendastratadas));
        var sumUsoLarvicida = parseFloat(Number(i.sumconsumolarvicidagr));

        var r=0;
        var ht="-";
        var xxs=""
        tVIVIIns=tVIVIIns+nviviendasinsp;
        tVIVIpos=tVIVIpos+nviviendasposi;
        tVIVItratados=tVIVItratados+nviviendastratadas;
        tVIViUsoLarvicida=tVIViUsoLarvicida+sumUsoLarvicida;

        tVIVICerrada=tVIVICerrada+nviviendascerrada;
        tVIVIDeshabitada=tVIVIDeshabitada+nviviendasdeshabitada;
        tVIVIRenuhente=tVIVIRenuhente+nviviendasrenuente;

        if(nviviendasinsp > 0){
        r=Number((nviviendasposi/nviviendasinsp)*100);
        r=parseFloat(r).toFixed(2);
        if(r >=0 && r < 1 ){
        ht=arrIA[1];
        }else if(r >=1 && r < 2 ){
        ht=arrIA[2];
        }else if(r >=3 ){
        ht=arrIA[3];
        }
        }
        xxs=r+"% - "+ht;

        %>
        <tr>
            <td ><%= (k+1)%> </td>
            <td ><%= i.descipress %>  </td>
            <%
            if(nivel =="sector"){
              print("<td>"+i.sector+"</td>");
              }else if(nivel =="manzana"){
              print("<td>"+i.sector+"</td><td>"+i.nmanzana+"</td>");
            }

            %>

            <td ><%= i.sumviviendainsptotal %>  </td>
            <td ><%= i.sumcerrada %>  </td>
            <td ><%= i.sumdeshabitada %>  </td>
            <td ><%= i.sumrenuente %>  </td>

            <td ><%= i.sumtotalviviendaspositivas %>  </td>
            <td ><%= i.sumtotalviviendastratadas %>  </td>
            <td ><%= i.sumconsumolarvicidagr %>  </td>
            <td><%=xxs%></td>
            <td><%=i.fecha%>

            </td>
            <!--
            <td>
                <a href="javascript:void(0)" onclick="verDetailDiasByAnioMesUbigeo('<%= i.anio %>','<%= i.distrito %>','<%= i.localidad %>','<%= i.descipress %>','<%= i.fecha %>')" class="btn btn-xs btn-mint" > Ver </a>
                <a href="javascript:void(0)" onclick="ReporteDetailDiasByAnioMesUbigeo('<%= i.anio %>','<%= i.distrito %>','<%= i.localidad %>','<%= i.descipress %>','<%= i.fecha %>')"  class="btn btn-xs btn-mint"> Reporte </a>
                <div class="btn-group">
                    <button class=" btn-xs btn btn-default btn-active-pink dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button" aria-expanded="false">
                        Reporte <i class="dropdown-caret"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                    </ul>
                </div>
            </td>-->
        </tr>
        <% });%>
        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th>Total</th>
            <%
            if(nivel =="sector"){
             print("<th> </th>");
            }else if(nivel =="manzana"){
             print("<th> </th><th> </th>");
            }
            %>
            <td><%=tVIVIIns%></td>
            <td><%=tVIVICerrada%></td>
            <td><%=tVIVIDeshabitada%></td>
            <td><%=tVIVIRenuhente%></td>

            <th><%=tVIVIpos%></th>
            <th><%=tVIVItratados%></th>
            <th><%=tVIViUsoLarvicida.toFixed(2)%></th>
            <th></th>
            <th></th>
            <!--<th></th>-->

        </tr>
        </tfoot>
    </table>
</script>



<script type="text/template" id="tmpModalReportSemanas">
    <div class="row">
        <div class="col-lg-12">
            <div class="col-sm-1">
            </div>
            <div class="col-sm-1">
                <div class="form-group">
                    <label class="control-label" style="margin-bottom: 0px;">Año</label>
                    <input style="background-color: white; border: 0px;padding-left: 0px;font-weight: bold" type="text" value="<%=anio%>" readonly="readonly" class="form-control">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label class="control-label" style="margin-bottom: 0px;">Provincia</label>
                    <input style="background-color: white; border: 0px;padding-left: 0px;font-weight: bold" type="text" value="<%=provincia%>" readonly="readonly" class="form-control">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label" style="margin-bottom: 0px;">Distrito</label>
                    <input style="background-color: white; border: 0px;padding-left: 0px;font-weight: bold" type="text" value="<%=distrito%>" readonly="readonly" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            Reporte a nivel de:
            <select id="NivelReport" class="form-control"  style="width:200px;display: inline-block;">
                <option value="distrito">Distrito</option>
                <option value="ipress">Ipress</option>
                <option value="sector">Sector</option>
                <option value="manzana">Manzana</option>
            </select>
            <button style="display: inline-block;" type="button" class="btn btn-sm btn-mint" onclick="btnReportarByNivelVigilancia('<%=anio%>','<%=provincia%>','<%=distrito%>','<%=ipress%>')">Reportar</button>
        </div>
        <div class="col-lg-12" id="divGrafica" style="display: none;" >
            <button type="button" class="btn-link" style="text-align: right;float: right" onclick="closeGráfica()" >Cerrar Gráfica</button>
            <div class="col-lg-12" id="divDataByOpcionNivelMAPA">

            </div>
        </div>
        <div class="col-lg-12" id="divDataByOpcionNivel">


        </div>

    </div>
</script>
<script type="text/template" id="tmpDivDataByOpcionNivel">
    <div class="table-responsive" style="height: 500px;">
        <button type="button" class="btn btn-xs btn-success" onclick="exportExcel(['tbexport2'],'e.xls')" >Exportar</button>
        <% var dtxy=JSON.stringify(data);
           var axr = String(nivel);
        %>
        <button type='button' class='btn btn-xs btn-info' onclick='verGraficaBySemanas(<%=dtxy%>,"<%=axr%>")' >Ver Gráfica</button>
        <table class="table table-condensed table-bordered " id="tbexport2" >
            <thead>
            <tr>

                <% if(nivel =="distrito"){ %>
                <th>#</th>
                <th>Ubigeo</th>
                <% } %>
                <% if(nivel =="ipress"){ %>
                <th>#</th>
                <th>Ubigeo</th>
                <th>Ipress</th>
                <% } %>
                <% if(nivel =="sector"){ %>
                <th>#</th>
                <th>Ubigeo</th>
                <th>Ipress</th>
                <th>Sector</th>
                <% } %>
                <% if(nivel =="manzana"){ %>
                <th>#</th>
                <th>Ubigeo</th>
                <th>Ipress</th>
                <th>Sector</th>
                <th>Manzana</th>
                <% } %>

                <?php for($i=1; $i <= 53 ;$i++){?>
                    <th> <u>Fecha </u> <br> SE <?=$i?> </th>
                <?php }?>

            </tr>
            </thead>
            <tbody>
            <% _.each(data,function(i,k){
            var idXtd="";


            %>
            <tr>
                <% if(nivel =="distrito"){
                var provincia =i.iddistrito;
                provincia=provincia.replace(/ /g, "");
                var distrito =i.idlocalidad;
                distrito=distrito.replace(/ /g, "");
                idXtd='idTd-'+provincia+'-'+distrito   ;
                %>
                <td><%=(k+1)%></td>
                <td><%=i.iddistrito +" "+ i.idlocalidad %></td>
                <% } %>
                <% if(nivel =="ipress"){
                var provincia =i.iddistrito;
                provincia=provincia.replace(/ /g, "");
                var distrito =i.idlocalidad;
                distrito=distrito.replace(/ /g, "");
                var ipress =i.ipress;
                ipress=ipress.replace(/ /g, "");
                idXtd='idTd-'+provincia+'-'+distrito+'-'+ipress   ;
                %>
                <td><%=(k+1)%></td>
                <td><%=i.iddistrito +" "+ i.idlocalidad %></td>
                <td><%=i.ipress %></td>
                <% } %>
                <% if(nivel =="sector"){
                var provincia =i.iddistrito;
                provincia=provincia.replace(/ /g, "");
                var distrito =i.idlocalidad;
                distrito=distrito.replace(/ /g, "");
                var ipress =i.ipress;
                ipress=ipress.replace(/ /g, "");
                idXtd='idTd-'+provincia+'-'+distrito+'-'+ipress +'-'+ Number(i.sector) ;
                %>
                <td><%=(k+1)%></td>
                <td><%=i.iddistrito +" "+ i.idlocalidad %></td>
                <td><%=i.ipress %></td>
                <td><%=i.sector %></td>
                <% } %>
                <% if(nivel =="manzana"){
                var provincia =i.iddistrito;
                provincia=provincia.replace(/ /g, "");
                var distrito =i.idlocalidad;
                distrito=distrito.replace(/ /g, "");
                var ipress =i.ipress;
                ipress=ipress.replace(/ /g, "");
                idXtd='idTd-'+provincia+'-'+distrito+'-'+ipress +'-'+ Number(i.sector) +'-'+ Number(i.nmanzana);
                %>
                <td><%=(k+1)%></td>
                <td><%=i.iddistrito +" "+ i.idlocalidad %></td>
                <td><%=i.ipress %></td>
                <td><%=i.sector %></td>
                <td><%=i.nmanzana %></td>
                <% } %>


                <% for( var x=1;x<=53;x++){ %>
                <td style="text-align: center;background-color: #ebedf1;" id="<%=idXtd%>-<%=x%>" > </td>
                <% }  %>

            </tr>
           <% });%>

            </tbody>
        </table>
    </div>
</script>