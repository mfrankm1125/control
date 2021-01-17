<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Importar Datos-POI MPSM</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;" >


                <br><br>
                <!--CUerpot aqui -->
                <h1></h1>
                <table id="tabla_grid" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr><th>#</th>
                        <th>POI</th>
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



<div class="modal fade"   id="modal_id" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body">
                <form class="panel-body form-horizontal form-padding" id="formHerramienta">
                    <input type="hidden" name="isEdit" id="isEdit" value="0">
                    <input type="hidden" name="idEdit" id="idEdit" value="0">
                    <!--Text Input-->
                    <div class="form-group">
                        <label class="col-md-3 control-label"  >Nombre</label>
                        <div class="col-md-9">
                            <input id="name" name="name" class="form-control" placeholder="Ejemplo: Fumigadora Still 1" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"  ></label>
                        <div class="col-md-9">
                            <div class="radio">

                                <!-- Inline radio buttons -->
                                <input id="pro" class="magic-radio" type="radio" name="isPropio" value="1" checked="">
                                <label for="pro">Propio</label>
                                <input id="alq" class="magic-radio" type="radio" value="0" name="isPropio">
                                <label for="alq">Alquilado</label>

                            </div>
                        </div>
                    </div>


                    <div id="divisProp">

                    </div>




                    <div class="form-group">
                        <label class="col-md-3 control-label" for="demo-textarea-input">Descripción</label>
                        <div class="col-md-9">
                            <textarea id="desc" name="desc" rows="9" class="form-control" placeholder="¿Alguna descripcion?"></textarea>
                        </div>
                    </div>



                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-9">
                            <a href="javascript:void(0)" onclick="btnSave(1)" type="button"   class=" btn btn-success ">
                                Guardar y terminar
                            </a>

                            <label id="btn2"   >

                            </label>

                            <button type="button" id="btnCancel"  data-dismiss="modal" class=" btn btn-danger ">
                                Cancelar
                            </button>

                        </div>
                    </div>
                </form>


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
<?php echo $_css?>
<?php echo $_js?>

<script type="text/javascript">
    var fileExtension = ['csv'];
    var maxSizeFile=parseInt("<?=$this->config->item('max_size_file');?>");
    var tmpTablePreView=_.template($("#tmptablePreView").html());
    var nameFilePreview=null;

    $(document).on("ready",function () {

       // getDataPreview();
        tableGrid();
    });

    function tableGrid(){
        tableGrid= $('#tabla_grid').DataTable({
            "ajax": url_base+'Importar/getDataTable',
            "columns": [
                { "data": null },
                {  sortable: false,
                    "render": function ( data, type, full, meta ) {
                        var id = full.idactividad;
                        var periodo = full.periodo;
                        html='<a href="javascript:void(0)"  onclick="ver('+id+','+periodo+');" class="btn-link">'+full.nombre+'-'+full.periodo+'</a>';
                        //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                        return html;
                    }
                },

                {  sortable: false,
                    "render": function ( data, type, full, meta ) {
                        var id = full.idactividad;
                        var periodo = full.periodo;
                        html='<a href="javascript:void(0)"  onclick="ver('+id+','+periodo+');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> <b>Ver Actividades</b></a>';
                        //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
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
            "initComplete": function(settings, json) {
                var info = tableRevision.page.info();
                $("#labelPendientes").html(info.recordsTotal);
                // console.log(info.recordsTotal);
                //alert( 'DataTables has finished its initialisation.' );
            }
        });

        $('#tabla_grid').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                tableRevision.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        } );

        tableGrid.on( 'order.dt search.dt', function () {
            tableGrid.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

    }

    function ver(id,periodo) {
        window.location.href =url_base+"Importar/form/"+id+"/"+periodo;
    }

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
                var selArea = $("#selArea");
                var selAccEstra = $("#selAccEstra");
                var selActPre = $("#selActPre");
                var dataOut = {
                    "nameFile": nameFile,
                    "selArea": selArea.val(),
                    "selAccEstra": selAccEstra.val(),
                    "selActPre": selActPre.val()
                };
                $.post(url_base + "Importar/saveImport", dataOut, function (data) {

                }, "json");
            } else {
                alert_error("Intentelo de Nuevo por favor");
            }
        }
    });
    $(document).on("click","#btnCancelPreview",function () {
        nameFilePreview=null;
        $("#divTablePreView").html("");
    });

</script>


