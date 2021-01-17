<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Configuración</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;" >
                 <br>
                <b style="font-size: 16px">Plazos</b><hr style="margin-top: 0px;">
                <div class="" style="">
                    <i style="color:#d28787;font-weight: bold;">
                        *<button type="button" class="btn btn-sm btn-success" id="btnSaveDiasUser" ><b>Guardar</b></button>
                        <input class="form-control" style="display:inline-block;color:black;font-size:16px;width: 100px;text-align: right;" type="text" name="valPlazoUser" id="valPlazoUser" >
                        días (hábiles) de plazo Terminado el mes, para adjuntar los medios de verificación- ESTO SE MUESTRA A LOS USUARIOS
                    </i>

                </div>
                <div style="margin-top: 5px;">
                    <i style="color:blue;font-weight: bold;">
                        *<button type="button" class="btn btn-sm btn-success" id="btnSaveDiasSis" ><b>Guardar</b></button>
                        <input   class="form-control" style=" display:inline-block;color:black;font-size:16px;width: 100px;text-align: right;" type="text" name="valPlazoSis" id="valPlazoSis" >
                        días - PARA EL USO INTERNO DEL SISTEMA
                    </i>

                </div>
                <br>
                <b style="font-size: 16px">Archivos</b><hr style="margin-top: 0px;">
                <div class="row">
                    <div class="col-sm-10">
                        <div class="form-group">
                            <table class="table table-condensed table-hover">
                                <thead>
                                    <tr>
                                       <th style="width: 350px;" >*Formato de Presentación de Informe de avance mensual POI</th>
                                        <th style="" >Última Actualización</th>
                                       <th style=""  >Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyFormatoInforme">
                                <tr>
                                    <td colspan="2"><b>Cargando...</b></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-10">
                        <div class="form-group">
                            <table class="table table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th style="width: 350px;" >*Formato de Presentación de Informe de Justiticación</th>
                                    <th style="" >Última Actualización</th>
                                    <th style=""  >Acción</th>
                                </tr>
                                </thead>
                                <tbody id="tbodyFormatoInformeJustify">
                                <tr>
                                    <td colspan="2"><b>Cargando...</b></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-10">
                        <div class="form-group">
                            <table class="table table-condensed table-hover ">
                                <thead>
                                <tr>
                                    <th style="width: 350px;" >Manual de usuario</th>
                                    <th style="" >Última Actualización</th>
                                    <th style="" >Acción</th>
                                </tr>
                                </thead>
                                <tbody id="tbodyManualUsuario">
                                <tr>
                                    <td colspan="2"><b>Cargando...</b></td>
                                 </tr>
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

<script type="text/template" id="tmpTbodyFormatoInforme">
    <tr>
        <td> <a href="<?=base_url()?>assets/uploads/<%=data.url%>" class="btn-link" target="_blank">Click para ver documento</a> </td>
        <td><%=data.fechaupdate%></td>
        <td> <input  style="display: inline-block" id="inFormatoInforme"  type="file" class="">
            <button style="display: inline-block" id="btnActFormatoInforme" type="button" class="btn btn-mint btn-sm" >
                Actualizar
            </button>
            <div class="form-group"  id="divprogressBar1" style="display: none;" >
                <label class="col-sm-2 control-label" style="text-align: right"></label>
                <div class="col-sm-5">
                    <div class="progress" >
                        <div id="progressBar"  style="width: 0%;font-size:12px" class="progress-bar progress-bar-info"></div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</script>


<script type="text/template" id="tmpTbodyFormatoInformeJustify">
    <tr>
        <td> <a href="<?=base_url()?>assets/uploads/<%=data.url%>" class="btn-link" target="_blank">Click para ver documento</a> </td>
        <td><%=data.fechaupdate%></td>
        <td> <input  style="display: inline-block" id="inFormatoInformeJustify"  type="file" class="">
            <button style="display: inline-block" id="btnActFormatoInformeJustify" type="button" class="btn btn-mint btn-sm" >
                Actualizar
            </button>
            <div class="form-group"  id="divprogressBar1" style="display: none;" >
                <label class="col-sm-2 control-label" style="text-align: right"></label>
                <div class="col-sm-5">
                    <div class="progress" >
                        <div id="progressBar"  style="width: 0%;font-size:12px" class="progress-bar progress-bar-info"></div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</script>

<script type="text/template" id="tmpTbodyManualUsuario">
    <tr>
        <td> <a href="<?=base_url()?>assets/uploads/<%=data.url%>" class="btn-link" target="_blank">Click para ver documento</a> </td>
        <td><%=data.fechaupdate%></td>
        <td> <input  style="display: inline-block" id="inManualUsuario"  type="file" class="">
            <button style="display: inline-block" id="btnActManualUsuario" type="button" class="btn btn-mint btn-sm" >
                Actualizar
            </button>
            <div class="form-group"  id="divprogressBar2" style="display: none;" >
                <label class="col-sm-2 control-label" style="text-align: right"></label>
                <div class="col-sm-5">
                    <div class="progress" >
                        <div id="progressBar"  style="width: 0%;font-size:12px" class="progress-bar progress-bar-info"></div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</script>

<?php echo $_css?>
<?php echo $_js?>

<script type="text/javascript">
    var tmpTbodyFormatoInforme=_.template($("#tmpTbodyFormatoInforme").html());
    var tmpTbodyManualUsuario=_.template($("#tmpTbodyManualUsuario").html());
    var tmpTbodyFormatoInformeJustify=_.template($("#tmpTbodyFormatoInformeJustify").html());
    var maxSizeFile=parseInt("<?=$this->config->item('max_size_file');?>");
    var fileExtension = ['pdf', 'rar', 'zip', 'gzip','docx','doc','xls','xlsx'];

    $(document).on("ready",function () {
        getValuePlazos();
        getDataArchivos();
    });

    function getValuePlazos(){
        $.post(url_base+"Configuracion/getDataPlazos",function (data) {
            if(data.length > 0){
                $("#valPlazoUser").val(data[0].valormuestra);
                $("#valPlazoSis").val(data[0].valor);
            }else{

            }
        },'json');
    }
    
    function getDataArchivos(){
        $.post(url_base+"Configuracion/getDataConfiguracion",function (data) {
            if(data.length > 0){
                for(var i in data){
                    if(data[i].tipo == "formatoinforme"){
                        $("#tbodyFormatoInforme").html(tmpTbodyFormatoInforme({data:data[i] }));
                    }else if(data[i].tipo == "manualusuario"){
                        $("#tbodyManualUsuario").html(tmpTbodyManualUsuario({data:data[i]}));
                    }else if(data[i].tipo == "formatoinformejustify"){
                        $("#tbodyFormatoInformeJustify").html(tmpTbodyFormatoInformeJustify({data:data[i]}));
                    }
                }
            }else{
                alert_error("Sin datos");
            }
        },'json');
    }
    $(document).on("click","#btnSaveDiasUser",function () {
        var btn=$(this);
        var valDiasUser=$("#valPlazoUser").val();
        var bol=true;
        bol=bol&& $("#valPlazoUser").required();
        if(bol){
            console.log(valDiasUser);
            $.post(url_base+"Configuracion/setValuePlazos",{"op":"user","value":valDiasUser},function (data) {
                if(data.status == "ok"){
                    alert_success("Se realizo Correctamente");
                    btn.button("reset");
                }else{
                    alert_error("Fallo intente nuevamente");
                    btn.button("reset");
                }
            },"json");
        }


    });
    
    $(document).on("click","#btnSaveDiasSis",function () {
        var btn=$(this);
        var valDiasSistema=$("#valPlazoSis").val();
        var bol=true;
        bol=bol&&$("#valPlazoSis").required();
        if(bol){
            //console.log(valDiasSistema);
            $.post(url_base+"Configuracion/setValuePlazos",{"op":"sis","value":valDiasSistema},function (data) {
                if(data.status == "ok"){
                    alert_success("Se realizo Correctamente");
                    btn.button("reset");
                }else{
                    alert_error("Fallo intente nuevamente");
                    btn.button("reset");
                }
            },"json");
        }
    });

    $(document).on("click","#btnActFormatoInforme",function () {
        uploadFile("formatoinforme",$(this),$("#inFormatoInforme"));
    });

    $(document).on("click","#btnActManualUsuario",function () {
        uploadFile("manualusuario",$(this),$("#inManualUsuario"));
    });

    $(document).on("click","#btnActFormatoInformeJustify",function () {
        uploadFile("formatoinformejustify",$(this),$("#inFormatoInformeJustify"));
    });


    $(document).on('change','#inFormatoInforme,#inManualUsuario,#inFormatoInformeJustify',function (e) {
        var inputFile = $(this);
        var fileUpload = inputFile[0].files[0];
        var nameFile=$(this).val();
        if ($.inArray(nameFile.split('.').pop().toLowerCase(), fileExtension) == -1) {
            alert("Por favor ingrese archivos tipo 'pdf',word ,excel, 'rar', 'zip', 'gzip' gracias.");
            //e.preventDefault();
            $(this).val("");
        }

        if( fileUpload != "undefined"  ){
            if((fileUpload.size/1024/1024) > maxSizeFile){
                alert_error("Tamaño de Archivo Supera los "+maxSizeFile+" MB , por favor comprimelo");
                $(this).val("");
            }
           // console.log(fileUpload.size);
        }
    });


    function uploadFile(op,thisx,inFile) {
        if(confirm("¿Esta seguro ?")) {

            let opt = op;
            var btn=thisx;
            var inputFile = inFile;
            var fileUpload = inputFile[0].files[0];
            var bol = true;

            if (bol) {
                if (fileUpload != undefined) {
                   // var fileExtension = ['pdf', 'rar', 'zip', 'gzip', 'docx', 'doc'];
                    var nameFile = fileUpload.name;
                    if ($.inArray(nameFile.split('.').pop().toLowerCase(), fileExtension) == -1) {
                        alert("Por favor ingrese archivos tipo  'pdf', 'rar', 'zip', 'gzip' gracias.");
                    } else {
                        btn.button("loading");
                        var formData = new FormData();
                        formData.append("file", fileUpload);
                        formData.append("op", opt);

                        //console.log(formData);

                        $.ajax({
                            url: url_base + "Configuracion/uploadFile",
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
                                        inputFile.val("");
                                        getDataArchivos();
                                        alert_success("Se realizó correctamente!");
                                        btn.button("reset");
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

                } else {
                    alert_error("Por Favor adjunte un archivo");
                }

            }
        }
    }


</script>


