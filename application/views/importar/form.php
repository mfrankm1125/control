<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Importar Datos - POI <?=$periodo?></h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;" >
                <br>
                <div class="table-toolbar-left">
                    <button class="btn btn-purple" id="btnImportarForModal"><i class="demo-pli-add icon-fw"></i> Importar Datos </button>

                </div>
                <br><br>
                <!--CUerpot aqui -->
                <h1></h1>
                <table id="tablaAreas" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
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
            </div>
            <!--===================================================-->
            <!--End Data Table-->

        </div>
    </div>

</div>




<div class="modal fade"   id="modal_id" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 90%;">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle">Importar Datos - POI MPSM <?=$periodo?> </h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="modalBodyImport">

            </div>

        </div>
    </div>
</div>


<script type="text/template" id="tmpDivIsProp">
    <div class="form-group">
        <label class="col-md-3 control-label"  >Modelo</label>
        <div class="col-md-9">
            <input id="model" name="model"  class="form-control" placeholder="Ejemplo: Xy-22" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label"  >Marca</label>
        <div class="col-md-9">
            <input id="marca" name="marca" class="form-control" placeholder="Ejemplo: Toyota" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label"  >Ubicación</label>
        <div class="col-md-9">
            <input id="almacen" name="almacen" class="form-control" placeholder="Ejemplo: Alamacen 1" />
        </div>
    </div>

</script>


<script type="text/template" id="tmpDivIsntProp">

    <div class="form-group">
        <label class="col-md-3 control-label"  >Coste  Alquiler Referencial S/.</label>
        <div class="col-md-9">
            <input id="costealq" name="costealq"  class="form-control" placeholder="Ejemplo: 500.00" />
        </div>
    </div>


</script>

<script type="text/template" id="tmptablePreView">
    <hr>
    <button type="button" class="btn btn-success btn-sm" id="btnSavePreview">Guardar</button>
    <button type="button" class="btn btn-warning btn-sm" id="btnCancelPreview">Cancelar</button>
    <table id="" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
        <thead>
        <tr><th>#</th>
            <th>Actividad</th>
            <th>UM</th>
            <th>Meta</th>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
            <th>8</th>
            <th>9</th>
            <th>10</th>
            <th>11</th>
            <th>12</th>
        </tr>
        </thead>
        <tbody id="tbodyActOpsPreview">
        <tr>
            <td colspan="15">Sin datos...</td>
        </tr>
        </tbody>
    </table>
</script>

<script type="text/template" id="tmpForm">
    <form class="panel-body form-horizontal form-padding">

        <div class="form-group">
            <label class="col-md-1 control-label" title="Área / Oficina" >Área:</label>
            <div class="col-md-9" id="divSelAreaImportar">
               Cargando...
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-1 control-label" title="Acción Estratégica" >Acc. Estra:</label>
            <div class="col-md-9" id="divSelAccEstraImportar">
                 Cargando...
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-1 control-label" title="Actividad Presupuestaria"  >Act. Pre:</label>
            <div class="col-md-9" id="divSelActPreImportar">
                 Cargando...
            </div>
        </div>
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
    var fileExtension = ['csv'];
    var maxSizeFile=parseInt("<?=$this->config->item('max_size_file');?>");
    var tmpTablePreView=_.template($("#tmptablePreView").html());
    var nameFilePreview=null;

    $(document).on("ready",function () {
        getAreasByIdpoi();
    });


    function getDataPreview(nameFilePreview){
        $.post(url_base+"Importar/previewTest",{"nameFile":nameFilePreview},function(data){
            //console.log(data);
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
                url: url_base + "Importar/uploadFileCSV",
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
                    "idpoi": parseInt("<?=$idpoi?>")
                };
                var bol=true;

                bol=bol&&selArea.requiredSelect();
                bol=bol&&selAccEstra.requiredSelect();
                bol=bol&&selActPre.requiredSelect();
                if(bol == true){
                    $.post(url_base + "Importar/saveImport", dataOut, function (data) {
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
        loadSelAreasResponsables();
        loadSelAccEstra();
        loadSelActPre();
    });

    
    function getAreasByIdpoi(){
        var idpoi="<?=$idpoi?>";
        var tmpTbodyAreas=_.template($("#tmpTbodyAreas").html());
        $.post(url_base+"Importar/getAreasByidPoi",{"idPoi":idpoi},function (data) {
            //console.log(data);
            if(data.length > 0){
                $("#tbodyAreas").html(tmpTbodyAreas({data:data}));
            }else{

            }
        },'json');
    }


    function loadSelAreasResponsables(){
        var tmpSelAreasResp=_.template($("#tmpSelAreaResp").html());
        var idpoi=parseInt("<?=$idpoi?>");
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
        var idpoi=parseInt("<?=$idpoi?>");
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
        var idpoi=parseInt("<?=$idpoi?>");
        $.post(url_base+"Poi/getActPresupuestariaRealbyArea",{"idpoi":idpoi,'idarearesp':""},function (data) {
            if(data.length > 0){
                $("#divSelActPreImportar").html(tmpSelActPre({data:data}));
            }else{
                $("#divSelActPreImportar").html(tmpSelActPre({data:data}));
            }
        },'json');

    }


</script>


