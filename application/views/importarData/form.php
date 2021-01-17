<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Importar Datos Producción de Leche</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;" >
                <br>
                <div class="table-toolbar-left">
                   <!-- <button class="btn btn-purple" id="btnImportarForModal"><i class="demo-pli-add icon-fw"></i> Importar Datos </button>-->

                    <button class="btn btn-purple" id="btnImportarProdLeche"><i class="demo-pli-add icon-fw"></i> Importar Datos Diarios Leche </button>

                </div>
                <br><br>
                <!--
                <h1></h1>
                <table id="tablaAreas" class="" cellspacing="0" width="100%">
                    <thead>
                    <tr><th>#</th>
                        <th>Área</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody id="tbodyAreas">
                    <tr>
                        <td colspan="3">Sin datos...</td>
                    </tr>
                    </tbody>
                </table>
                <br>-->
               <!-- <button class="btn btn-danger" id="btnExporExcelData"> Exportar</button>-->




            </div>
            <!--===================================================-->


        </div>
    </div>

</div>




<div class="modal fade"   id="modalIdProdLeche" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 90%;">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id=" ">Data Producción diaria  </h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divProdDiario">
                <form class="panel-body form-horizontal form-padding" id="formImportDiario">
                    <div class="form-group">
                        <label class="col-md-1 control-label"  >Archivo</label>
                        <div class="col-md-4">
                            <input type="file" id="fileCsvD" name="fileCsvD"    >
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-mint btn-sm" id="btnpreviewCsvDiario" >Previsualizar</button>
                        </div>
                    </div>

                    <div id="divTablePreViewDiario">

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<div class="modal fade"   id="modal_id" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 90%;">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"> <?=date('d M Y',strtotime('2016-10-02 Monday next week'))?> </h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="modalBodyImport">

            </div>

        </div>
    </div>
</div>




<script type="text/template" id="tmptablePreView">
    <hr>

    <button type="button" class="btn btn-success btn-sm" id="btnSavePreview">Guardar</button>
    <button type="button" class="btn btn-warning btn-sm" id="btnCancelPreview">Cancelar</button>
    <table id="" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
        <thead>
        <tr><th>#</th>
            <th>id</th>
            <th>fecha nace</th>
            <th>Raza</th>
        </tr>
        </thead>
        <tbody id="tbodyActOpsPreview">
        <tr>
            <td colspan="15">Sin datos...</td>
        </tr>
        </tbody>
    </table>
</script>


<script type="text/template" id="tmpTablePreViewDiario">
    <hr>
    <div id="divMensajesErrorInsertData">

    </div>
    <button type="button" class="btn btn-success btn-sm" id="btnSavePreviewDiario">Guardar</button>
    <button type="button" class="btn btn-warning btn-sm" id="btnCancelPreviewDiario">Cancelar</button>
    <table id="" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
        <thead>
        <tr><th>#</th>
            <th>CodVaca</th>
            <th>Prod Maniana</th>
            <th>Prod Tarde</th>
            <th>Recria</th>
            <th>Recria</th>
        </tr>
        </thead>
        <tbody id="tbodyActOpsPreviewDiario">
        <tr>
            <td colspan="15">Sin datos...</td>
        </tr>
        </tbody>
    </table>
</script>

<script type="text/template" id="tmpForm">
    <form class="panel-body form-horizontal form-padding" id="formImport">
        <div class="form-group">
            <label class="col-md-1 control-label"  >Archivo</label>
            <div class="col-md-4">
                <input type="file" id="fileCsv" name="fileCsv"    >
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-mint btn-sm" id="btnpreviewCsv" >Previsualizar</button>
            </div>
        </div>

        <div id="divTablePreView">

        </div>
    </form>
</script>

<script type="text/template" id="tmpTbodyAreas">
    <% _.each(data,function(i,k){ %>
    <tr>
        <td><%=(k+1)%></td>
        <td><a class="btn-link" href="javascript:void(0)"  onclick="verDataPOI('<%=i.id_user%>')"><%=i.nombrearearesponsable%> </a></td>
        <td><a class="btn btn-info btn-xs" href="javascript:void(0)"  onclick="verDataPOI('<%=i.id_user%>')">Ver</a></td>
    </tr>
    <% }); %>

</script>

<script type="text/template" id="tmpSelAreaResp">

    <select  class="form-control" name="selAreas" id="selAreas"     data-width="75%">
        <% _.each(data,function(i,k){ %>
        <option value="<%=i.id%>"><%=i.nombrearearesponsable%></option>
        <% } ); %>
        <option value="1">Hola</option>
    </select>

</script>

<script type="text/template" id="tmpSelAccEstra">
    <select style="color:#da1e1e;"  title="Accion Estrategica" class="form-control" name="selAccEstra[]"  id="selAccEstra"   data-width="75%">
        <% _.each(data,function(i,k){ %>
        <option value="<%=i.idactividad%>"><%=i.nombre%></option>
        <% } ); %>
    </select>
</script>

<script type="text/template" id="tmpSelActPre">
    <select title="Actividad Presupuestaria / Proyecto" class="form-control" name="selActPre[]" id="selActPre" style="color:darkred"   data-width="75%">
        <% _.each(data,function(i,k){ %>
        <option value="<%=i.id_actpresupuestariareal%>"><%=i.nombre%></option>
        <% }); %>
    </select>
</script>

<?php echo $_css?>
<?php echo $_js?>
<script type="text/javascript">
    var fileExtension = ['csv','CSV'];
    var maxSizeFile=parseInt("<?=$this->config->item('max_size_file');?>");
    var tmpTablePreView=_.template($("#tmptablePreView").html());
    var tmpTablePreViewDiario=_.template($("#tmpTablePreViewDiario").html());

    var nameFilePreviewDiario=null;
  $(document).on("click","#btnImportarProdLeche",function () {
      open_modal("modalIdProdLeche");

  });
  
  $(document).on("click","#btnpreviewCsvDiario",function () {
      nameFilePreviewDiario=null;
      uploadFileCsvD();
  });

  function uploadFileCsvD(){
      var btnPreview=$("#btnpreviewCsvDiario");
      var inputFile = $('input[name=fileCsvD]');
      var fileUpload = inputFile[0].files[0];
      // console.log(fileUpload);
      if (fileUpload != undefined) {
          btnPreview.button("loading");
          var formData = new FormData();
          formData.append("file", fileUpload);
          $.ajax({
              url: url_base + "ImportarData/uploadFileCSVDiaro",
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
                          alert_success("Se realizó correctamente!");
                          btnPreview.button("reset");
                          $("#fileCsvD").val("");
                          nameFilePreviewDiario=data.orig_name;
                          getDataPreviewDiario(data.orig_name);
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


  }

    function getDataPreviewDiario(nameFilePreviewDiario){
        $.post(url_base+"ImportarData/previewTestDiario",{"nameFile":nameFilePreviewDiario},function(data){
            console.log(data);
            $("#divTablePreViewDiario").html(tmpTablePreViewDiario);
            $("#tbodyActOpsPreviewDiario").html(data);
        },"html");
    }


    $(document).on("click","#btnSavePreviewDiario",function () {
        if(confirm("Esta Seguro de guardar?")) {
            if (nameFilePreviewDiario != null) {
                console.log(nameFilePreviewDiario);

                var bol=true;

                /* bol=bol&&selArea.requiredSelect();
                 bol=bol&&selAccEstra.requiredSelect();
                 bol=bol&&selActPre.requiredSelect();*/
                var dataSend=$("#formImportDiario").serialize();
                if(bol == true){
                    $.post(url_base + "ImportarData/saveImportDiario", dataSend, function (data) {
                        if(data.status == "ok"){
                            alert_success("Se realizo Correctamente");
                            var p="<b style='font-size: 22px;'>Número de registros insertados : <b> "+data.cuentaIns+"/"+data.cuenta+"</b> </b>";
                             p=p+"<br><b style='font-size: 18px;color:red;'>Número de registros no insertados: '"+(parseInt(data.cuenta)-parseInt(data.cuentaIns))+"' </b>";

                            if(data.fail.length > 0 ){
                                var xsss="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                p=p+"<br><b style='font-size: 18px;;'>Producción no registrada(COD ANIMAL): </b>";
                                $.each(data.fail,function (k,i) {
                                    p=p+"<br><b style='font-size:18px; color: red;'>"+xsss+xsss+xsss+xsss+i.idanimal+"</b>";
                                });
                            }
                            p=p+"<br>";
                            $("#divMensajesErrorInsertData").html(p);
                        }else{
                            alert_error("Ocurrio algo inesperado :(");
                        }
                    }, "json");
                }else{
                    alert_error("Rellene los campos requeridos");
                }

            } else {
                alert_error("Intentelo de Nuevo por favor");
            }
        }
    });

    $(document).on("click","#btnCancelPreviewDiario",function () {
        nameFilePreviewDiario=null;
        $("#divTablePreViewDiario").html("");
    });


</script>



<script type="text/javascript">

    var nameFilePreview=null;

    $(document).on("ready",function () {

    });


    function getDataPreview(nameFilePreview){
        $.post(url_base+"ImportarData/previewTest",{"nameFile":nameFilePreview},function(data){
            console.log(data);
           $("#divTablePreView").html(tmpTablePreView);
           $("#tbodyActOpsPreview").html(data);
        },"html");
    }


    $(document).on('change','input[name=fileCsv]',function () {
        var nameFile=$(this).val();
        nameFilePreview=null;
        if ($.inArray(nameFile.split('.').pop().toLowerCase(), fileExtension) == -1) {
            alert("El formato permitido para importar es : .csv");
            //e.preventDefault();
            $(this).val("");
        }

        if( $(this)[0].files[0] != undefined  ){
            console.log(this.files[0]);
            if((this.files[0].size /1024/1024) > maxSizeFile){
                alert_error("Tamaño de Archivo Supera los "+maxSizeFile+" MB , por favor comprimelo");
                $(this).val("");
            }
        }else{
            $(this).val("");
        }
    });

    $(document).on("click","#btnpreviewCsv",function () {
        nameFilePreview=null;
        uploadFileCsv();
    });


    function uploadFileCsv(){
        var btnPreview=$("#btnpreviewCsv");
        var inputFile = $('input[name=fileCsv]');
        var fileUpload = inputFile[0].files[0];
       // console.log(fileUpload);
        if (fileUpload != undefined) {
            btnPreview.button("loading");
            var formData = new FormData();
            formData.append("file", fileUpload);
            $.ajax({
                url: url_base + "ImportarData/uploadFileCSV",
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
                            alert_success("Se realizó correctamente!");
                            btnPreview.button("reset");
                            $("#fileCsv").val("");
                            nameFilePreview=data.orig_name;
                            getDataPreview(data.orig_name);
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


    }

    $(document).on("click","#btnSavePreview",function () {
        if(confirm("Esta Seguro de guardar?")) {
            if (nameFilePreview != null) {
                console.log(nameFilePreview);
                var nameFile = nameFilePreview;
                var selArea = $("#selAreas");
                var selAccEstra = $("#selAccEstra");
                var selActPre = $("#selActPre");
                var dataOut = {
                    "nameFile": nameFile,
                    "selArea": selArea.val(),
                    "selAccEstra": selAccEstra.val(),
                    "selActPre": selActPre.val(),
                    "idpoi": parseInt("")
                };
                var bol=true;

               /* bol=bol&&selArea.requiredSelect();
                bol=bol&&selAccEstra.requiredSelect();
                bol=bol&&selActPre.requiredSelect();*/
                var dataSend=$("#formImport").serialize();
                if(bol == true){
                    $.post(url_base + "ImportarData/saveImport", dataSend, function (data) {
                        if(data.status == "ok"){
                            alert_success("Se realizo Correctamente");
                        }else{
                            alert_error("Ocurrio algo inesperado :(");
                        }
                    }, "json");
                }else{
                    alert_error("Rellene los campos requeridos");
                }

            } else {
                alert_error("Intentelo de Nuevo por favor");
            }
        }
    });

    $(document).on("click","#btnCancelPreview",function () {
        nameFilePreview=null;
        $("#divTablePreView").html("");
    });

    $(document).on("click","#btnImportarForModal",function () {
        var tmpForm=_.template($("#tmpForm").html());
        open_modal("modal_id");
        $("#modalBodyImport").html(tmpForm);

    });




    function loadSelAreasResponsables(){
        var tmpSelAreasResp=_.template($("#tmpSelAreaResp").html());
        var idpoi=parseInt("");
        $.post(url_base+"Poi/getAreaRespbyPoi",{"idpoi":idpoi},function (data) {
            if(data.length > 0){
                $("#divSelAreaImportar").html(tmpSelAreasResp({data:data}));
            }else{
                $("#divSelAreaImportar").html(tmpSelAreasResp({data:data}));
            }
        },'json');

    }

    function loadSelAccEstra(){
        var tmpSelAccEstra=_.template($("#tmpSelAccEstra").html());
        var idpoi=parseInt("");
        $.post(url_base+"Poi/getActEstrategicas",{"idpoi":idpoi,'idarea':""},function (data) {
            if(data.length > 0){
                $("#divSelAccEstraImportar").html(tmpSelAccEstra({data:data}));
            }else{
                $("#divSelAccEstraImportar").html(tmpSelAccEstra({data:data}));
            }
        },'json');

    }
    function loadSelActPre(){
        var tmpSelActPre=_.template($("#tmpSelActPre").html());
        var idpoi=parseInt("");
        $.post(url_base+"Poi/getActPresupuestariaRealbyArea",{"idpoi":idpoi,'idarearesp':""},function (data) {
            if(data.length > 0){
                $("#divSelActPreImportar").html(tmpSelActPre({data:data}));
            }else{
                $("#divSelActPreImportar").html(tmpSelActPre({data:data}));
            }
        },'json');

    }


    var fechasReportView=<?php echo json_encode($dt["fechas"])?>;

    $(document).on("ready",function () {
       /*console.log(fechasReportView);

        $.each(fechasReportView,function (k,i) {
            var txtFechaIn=i.fechaprodleche;
            var sumManiana=0;
            var sumTarde=0;
            var sumRecria=0;
            var cuenta= $('input[name="fmaniana'+txtFechaIn+'"]').length;
            var inpuFmaniana=$('input[name="fmaniana'+txtFechaIn+'"]');
            var inpuFtarde=$('input[name="ftarde'+txtFechaIn+'"]');
            var inpuFrecria=$('input[name="frecria'+txtFechaIn+'"]');

            console.log(cuenta);

            for (var ix = 0; ix < cuenta; ix++) {

               sumManiana=parseFloat(sumManiana)+parseFloat($(inpuFmaniana[ix]).val());
                sumTarde=parseFloat(sumTarde)+parseFloat($(inpuFtarde[ix]).val());
                sumRecria=parseFloat(sumRecria)+parseFloat($(inpuFrecria[ix]).val());
            }

            console.log(sumManiana);

            var divSubTotalManiana="#divValManina"+txtFechaIn;
            var divSubTotalTarde="#divValTarde"+txtFechaIn;
            var divSubTotalRecria="#divValRecria"+txtFechaIn;
            $(divSubTotalManiana).html(sumManiana.toFixed(2));
            $(divSubTotalTarde).html(sumTarde.toFixed(2));
            $(divSubTotalRecria).html(sumRecria.toFixed(2));
        }); */
    });


$("#btnExporExcelData").on("click",function () {
    exportTableToExcel("tablaAreas","test");
});

    function exportTableToExcel(tableID, filename = ''){
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        // Specify file name
        filename = filename?filename+'.xls':'excel_data.xls';

        // Create download link element
        downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob( blob, filename);
        }else{
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

            // Setting the file name
            downloadLink.download = filename;

            //triggering the function
            downloadLink.click();
        }
    }


</script>


