<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-control">
                    <a href="<?=base_url()?>inspecciones/vermapa" class="btn btn-link">Ver</a>
                    <button class="add-tooltip  btn btn-default btn-lg"  data-toggle="tooltip" data-container="body" data-placement="left" data-original-title="Representa los datos de analisis de suelos"  ><i class="fa fa-question-circle-o"></i></button>
                </div>
                <h3 class="panel-title">Inspección a viviendas  Realizadas</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;">
                <br>
                <div class="table-toolbar-left">
                    <!--<button class="btn btn-purple" id="add"><i class="demo-pli-add icon-fw"></i> Agregar</button> -->
                    <button class="btn btn-purple" id="btnAdd"><i class="demo-pli-add icon-fw"></i> Agregar Nuevo</button>
                    <div class="btn-group">
                        <button class="btn btn-default" id="refresh"  ><i class="demo-pli-refresh" ></i>Refrescar</button>

                    </div>
                </div>
                <br><br>
                <!--CUerpot aqui -->
                <h1></h1>
                <table id="tabla_grid" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr><th>#</th>
                        <th>F. Intervencion</th>
                        <th>Ubigeo</th>
                        <th>Localidad </th>
                        <th>Red salud</th>
                        <th>Est. salud</th>
                        <th>Sector</th>
                        <th>Jefe brigada</th>
                        <th>Inspector</th>
                        <th>T. Actividad</th>
                        <th>N. Control</th>
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
                <div class="row" id="divFormRegInspVivienda">
                </div>
                <div class="row" id="divTableGInspeccionesRealizadas">
               </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade"   id="modalAddNewEmplo" role="dialog"  data-backdrop="static" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog "  >
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="mAddNewEmploTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divModalBodyAddNewEmplo" >

            </div>
        </div>
    </div>
</div>



<link href="<?=base_url()?>assets/scripts/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<?php echo $_css?>
<?php echo $_js?>
<script src="<?=base_url()?>assets/scripts/jquery-ui-1.12.1/jquery-ui.min.js"></script>

<script type="text/javascript">
    var viviendas=<?=$viviendas?>;
    var jefebrigada=<?= json_encode($jefebrigada)?>;
    var inspector=<?= json_encode($inspector)?>;
    var estadovivienda=<?= json_encode($estadovivienda)?>;
    $(document).on("ready",function () {
        iniDataTable();
    });

    $(document).on("keyup",".vali",function () {
        var tt=$(".vali");
        var s=0;
        for( var i=0; i< tt.length; i++ ){
            s=s+Number($(tt[i]).val());
        }
        $("#btotali").html(s);
    });
    $(document).on("keyup",".valp",function () {
        var tt=$(".valp");
        var s=0;
        for( var i=0; i< tt.length; i++ ){
            s=s+Number($(tt[i]).val());
        }
        $("#btotalp").html(s);
    });
    $(document).on("keyup",".valt",function () {
        var tt=$(".valt");
        var s=0;
        for( var i=0; i< tt.length; i++ ){
            s=s+Number($(tt[i]).val());
        }
        $("#btotalt").html(s);
    });
    $(document).on("keyup",".vale",function () {
        var tt=$(".vale");
        var s=0;
        for( var i=0; i< tt.length; i++ ){
            s=s+Number($(tt[i]).val());
        }
        $("#btotale").html(s);
    });

    $(document).on("click","#btnAdd" ,function () {
        open_modal("modal_id");
        var tmpFormComienzaInspeccion=_.template($("#tmpFormComienzaInspeccion").html());
        $("#divFormComienzaInspeccion").html(tmpFormComienzaInspeccion);
        iniAutoCompleteDistrito();
        getSelJefeBrigada();
        getSelInspector();
        iniAutoCompleteViviendas();
        getEstadosViviendaInspeccion();
        $("#divFormRegInspVivienda").html("");
        $("#divTableGInspeccionesRealizadas").html("");
    });

    function iniDataTable() {
        rowSelection = $('#tabla_grid').DataTable({
            "ajax": url_base+'inspecciones/getDataTable',
            "columns": [
                { "data": null },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.fechaintervencion;
                        //var lastname = full.apellidos;
                        //var lastname = full.apellidos;
                        var html="<input style='width: 125px;border: 0;background-color: transparent;' type='date' disabled  value='"+name+"'><span style='visibility: hidden;font-size: 1px;'>"+name+"</span> ";

                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.DESC_DPTO+"-"+full.DESC_PROV+"-"+full.DESC_DIST ;
                        var html=name;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {

                        var pp =  full.localidad ;
                        var html=pp;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var dd = ""+full.DESC_RED+"";


                        //var lastname = full.apellidos;
                        var html=dd;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var pp = full.DESC_ESTAB ;
                        //var lastname = full.apellidos;
                        var html= pp;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                      //var lastname = full.apellidos;
                        var html=""+full.sector;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        //var lastname = full.apellidos;
                        var html=""+full.jefebrigada;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        //var lastname = full.apellidos;
                        var html=""+full.inspector;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        //var lastname = full.apellidos;
                        var html=""+full.tipoactividad;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        //var lastname = full.apellidos;
                        var html=""+full.nrocontrol;
                        return html;
                    }
                },
                {  sortable: false,
                    "render": function ( data, type, full, meta ) {
                        var id = full.idinspeccion;
                        html="";
                        //html='<a href="javascript:void(0)"  onclick="ver('+id+');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>';
                        //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                        html=html+"&nbsp; <a href='javascript:void(0)' onclick='ver("+id +","+JSON.stringify(full)+");'  class='btn btn-mint  btn-xs'><i class='fa fa-file'></i> Ver</a>";
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


    function iniViviendas(){
        var tmpSelViviendas=_.template($("#tmpSelViviendas").html());
        $("#").html(tmpSelViviendas({data:viviendas}));
    }

    function eliminar(id) {
        $.post(url_base+"inspecciones/deleteInsp",{"id":id},function (data) {
            if(data.status=="ok"){
                refrescar();
                alert_success("Se elimino correctamente");
            }else{
                alert_error("Error");
            }
        },'json');
    }

    function iniAutoCompleteDistrito() {
        $("#distrito").autocomplete({
            source: "<?= base_url(); ?>inspecciones/getDataDepProvbyDistri",
            minLength: 1,// tamaño de cadena
            delay: 250,
            search: function( event, ui ) {
                $("#iddistrito").val("");
                $("#divRedSalud").html("...");
                $("#divEstablecimientosalud").html("...");
                $("#sector").val("");
                $("#idubigeo").val("");
            },
            select: function( event, ui ) {
                var dt=ui.item;
                $("#region").val(dt.DESC_DPTO);
                $("#provincia").val(dt.DESC_PROV);
                $("#iddistrito").val(dt.COD_DIST);
                $("#idubigeo").val(dt.UBIGEO);
                getDataSelRed(0);
                //$("#lat").removeAttr("readonly","readonly");
                //$("#lng").removeAttr("readonly","readonly");
                //$("#codOvi").removeAttr("readonly","readonly");

            }
        });
    }
    
    function getDataSelRed(idestablecimiento) {
        $("#divRedSalud").html("Cargando...");
        var region= $("#region").val();
        var tmpSelRedSalud=_.template($("#tmpSelRedSalud").html());
        $.post(url_base+"inspecciones/getRedSalud",{"region":region},function (data) {
            //console.log(data);
            $("#divRedSalud").html(tmpSelRedSalud({data:data,region:region}));
            $("#divEstablecimientosalud").html("Cargando...");
            getEstablecimientoSalud(idestablecimiento);
        },'json');
    }
    $(document).on("change","#selRedSalud",function () {
        getEstablecimientoSalud(0);
    });

    function getEstablecimientoSalud(idestablecimiento) {
        var selRedSalud= $("#selRedSalud").val();
        var Cods = selRedSalud.split('-');
        var coddisa=Cods[0];
        var codred=Cods[1];
        var iddistrito=$("#iddistrito").val();
     //   console.log(selRedSalud);return 0;
        var tmpSelEstablecimientoSalud=_.template($("#tmpSelEstablecimientoSalud").html());
        $.post(url_base+"inspecciones/getEstablecimientoSalud",{"coddisa":coddisa,"codred":codred,"iddistrito":iddistrito},function (data) {

            $("#divEstablecimientosalud").html(tmpSelEstablecimientoSalud({data:data}));
            $("#selEstablecimientoSalud").val(idestablecimiento);

        },'json');
    }
    
    function getSelJefeBrigada() {
        var tmpSelJefeBrigada=_.template($("#tmpSelJefeBrigada").html());
        $("#divSelJefeBrigada").html(tmpSelJefeBrigada({data:jefebrigada}));
    }
    function getSelInspector() {
        var tmpSelInspector=_.template($("#tmpSelInspector").html());
        $("#divSelInspector").html(tmpSelInspector({data:inspector}));
    }

    $(document).on("click","#btnComenzarInspeccion",function () {
        var iddistrito=$("#iddistrito");
        var localidad=$("#localidad");
        var selRedSalud=$("#selRedSalud");
        var selEstablecimientoSalud=$("#selEstablecimientoSalud");
        var sector=$("#sector");
        var selJefeBrigada=$("#selJefeBrigada");
        var selInspector=$("#selInspector");
        var selTipoVigilancia=$("#selTipoVigilancia");
        var selControl=$("#selControl");
        var fintervencion=$("#fintervencion");
        var bol=true;
        if(iddistrito.val()==""){
            alert_error("seleccione/vuelva a seleccionar el distrito")
            bol=bol&&false;
        }
        //bol=bol && localidad.required();
        bol=bol && selRedSalud.requiredSelect();
        bol=bol && selEstablecimientoSalud.requiredSelect();
        bol=bol && sector.required();
        bol=bol && fintervencion.required();
        bol=bol && selJefeBrigada.requiredSelect();
        bol=bol && selInspector.requiredSelect();
        bol=bol && selTipoVigilancia.requiredSelect();
        bol=bol && selControl.requiredSelect();
        if(!bol){ return 0;}
        var form=$("#formComienzaInspeccion").serialize();
        $.post(url_base+"inspecciones/setComienzaInspeccion",form,function (data) {
            if(data.status=="ok"){
                alert_success("Se registro correctamente");
                $("#idinspeccion").val(data.id);
                var tmpDivFormRegInspVivienda=_.template($("#tmpDivFormRegInspVivienda").html());
                $("#divFormRegInspVivienda").html(tmpDivFormRegInspVivienda);
                getEstadosViviendaInspeccion();
                iniAutoCompleteViviendas();
                var tmpDivTableGInspeccionesRealizadaas=_.template($("#tmpDivTableGInspeccionesRealizadaas").html());
                $("#divTableGInspeccionesRealizadas").html(tmpDivTableGInspeccionesRealizadaas);
                $("#divBtnComenzarInspeccion").html("");
                refrescar();
            }
        },'json');

    });

    function ver(id,data) {
        open_modal("modal_id");
        var tmpFormComienzaInspeccion=_.template($("#tmpFormComienzaInspeccion").html());
        $("#divFormComienzaInspeccion").html(tmpFormComienzaInspeccion);
        $("#divBtnComenzarInspeccion").remove();
        $("#idinspeccion").val(data.idinspeccion);
        iniAutoCompleteDistrito();
        getSelJefeBrigada();
        getSelInspector();
        $("#region").val(data.DESC_DPTO);
        $("#provincia").val(data.DESC_PROV);
        $("#distrito").val(data.DESC_DIST);
        $("#iddistrito").val(data.COD_DIST);
        $("#idubigeo").val(data.idubigeo);
        $("#localidad").val(data.localidad);


        $("#sector").val(data.sector);
        $("#fintervencion").val(data.fechaintervencion);
        $("#selJefeBrigada").val(data.idjefebrigada);
        $("#selInspector").val(data.idinspector);
        $("#selTipoVigilancia").val(data.tipoactividad);
        $("#selControl").val(data.nrocontrol);
        getDataSelRed(data.idestablecimientosalud);
        var tmpDivFormRegInspVivienda=_.template($("#tmpDivFormRegInspVivienda").html());
        $("#divFormRegInspVivienda").html(tmpDivFormRegInspVivienda);
        getEstadosViviendaInspeccion();
        iniAutoCompleteViviendas();
        var tmpDivTableGInspeccionesRealizadaas=_.template($("#tmpDivTableGInspeccionesRealizadaas").html());
        $("#divTableGInspeccionesRealizadas").html(tmpDivTableGInspeccionesRealizadaas);
        getDetailInspeccionViviendas();
    }

    function getDetailInspeccionViviendas() {
        var id=$("#idinspeccion").val();
        var tmpTbodyTableDetailInspeccionVivienda=_.template($("#tmpTbodyTableDetailInspeccionVivienda").html());
        $.post(url_base+"inspecciones/getDetailInspeccionesVivienda",{"idinpseccion":id},function (data) {
            $("#tbodyDetailInspeccionVivienda").html(tmpTbodyTableDetailInspeccionVivienda({data:data}));
        },'json');
    }

    function iniAutoCompleteViviendas() {
        $("#vivienda").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?= base_url(); ?>inspecciones/getViviendas",
                    dataType: "json",
                    method: "POST",
                    data: { term : request.term, iddistri :$("#idubigeo").val() },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 1,// tamaño de cadena
            delay: 250,
            search: function( event, ui ) {
                $("#idvivienda").val("0");
            },
            select: function( event, ui ) {
                $("#idvivienda").val(ui.item.idvivienda);

            }
        });
    }
    function getEstadosViviendaInspeccion() {
        var tmpSelEstadosViviendaInsp=_.template($("#tmpSelEstadosViviendaInsp").html());
        $("#divEstadoViviendaInsp").html(tmpSelEstadosViviendaInsp({data:estadovivienda}));
        $("#selEstadosViviendaInsp").val(1);
        iniChangeEstadoVibienda();
    }
    
    $(document).on("change","#selEstadosViviendaInsp",function () {

        iniChangeEstadoVibienda();
    });
    function iniChangeEstadoVibienda() {
        var sel=$("#selEstadosViviendaInsp");
        var tmpDivTablaCriaderosIsinspeccion=_.template($("#tmpDivTablaCriaderosIsinspeccion").html());
        var divTablaCriaderosIsInspeccionda=$("#divTablaCriaderosIsInspeccionda");
        switch (sel.val()){
            case "1":divTablaCriaderosIsInspeccionda.html(tmpDivTablaCriaderosIsinspeccion) ;break;
            case "2":divTablaCriaderosIsInspeccionda.html("") ;break;
            case "3":divTablaCriaderosIsInspeccionda.html("") ;break;
            case "4":divTablaCriaderosIsInspeccionda.html("") ;break;

            default:break;
        }
    }
    $(document).on("click","#btnRegistrarInspeccionVivienda",function() {
        var btn=$(this);
        var bol=true;
        var idvivienda=$("#idvivienda");
        var vivienda=$("#vivienda");
        var larvicida=$("#larvicida");
        var selEstadosViviendaInsp=$("#selEstadosViviendaInsp");
        if(idvivienda.val() == 0){vivienda.focus(); bol=bol&&false; alert_error("seleccione la vivienda") }
        bol=bol && selEstadosViviendaInsp.requiredSelect();
        if(selEstadosViviendaInsp.val()=="1"){// isInspeccionada;
            bol=bol&&larvicida.required();
        }
        if(!bol){ return 0;}
        btn.button("loading");
       var form=$("#formRegInspVivienda").serialize();
        $.post(url_base+"inspecciones/setDetailInspeccion",form+"&idinspeccion="+$("#idinspeccion").val(),function (data) {
            if(data.status =="ok"){
                alert_success("Se realizó correctamente");
                getDetailInspeccionViviendas();
                $("#formRegInspVivienda")[0].reset();
                $("#idvivienda").val("0");
                $("#btotali").html("");
                $("#btotalp").html("");
                $("#btotalt").html("");
                $("#btotale").html("");
            }else{
                alert_error("Ocurrio Algo inesperado");
            }
            btn.button("reset");
        },'json');

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


    function soloNumeros(event){
        var evt =   event; //Validar la existencia del objeto event
        var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode : ((evt.which) ? evt.which : 0)); //Extraer el codigo del caracter de uno de los diferentes grupos de codigos
        var respuesta = true; //Predefinir como valido
        console.log(charCode);
        if(charCode>31&&(charCode<48||charCode>57)){respuesta = false;}
        if(charCode==189){respuesta = false;}
        return respuesta;
    }

    function deleteDetailInspVivienda(id) {
        if(!confirm("¿Esta seguro de eliminar este registro?")){ return 0;}
        $.post(url_base+"inspecciones/deleteDetailIns",{"id":id},function (data) {
           // console.log(data);
            if(data.status == "ok"){
                alert_success("Se elimino correctamente");
                getDetailInspeccionViviendas();
            }else{
                alert_error("Error");
            }
        },'json');
    }

    $(document).on("click","#btnSaveNewEmployee",function() {
        var bol=true;
        var btn=$(this);
        bol=bol&&$("#nombreins").required();
        bol=bol&&$("#apellidosins").required();
        var tipo=$("#tipo").val();
        if(!bol){return 0;}
        btn.button("loading");
        var form=$("#formNewEmployee").serialize();
        $.post(url_base+"inspecciones/setNewEmployee",form,function (data) {
            if(data.status=="ok"){
                alert_success("Se registro el inspector correctamente");
                if(tipo == "jefebrigada"){
                    var tmpSelJefeBrigada=_.template($("#tmpSelJefeBrigada").html());
                    $("#divSelJefeBrigada").html(tmpSelJefeBrigada({data:data.data}));
                    $("#selJefeBrigada").val(data.id);
                }else{
                    var tmpSelInspector=_.template($("#tmpSelInspector").html());
                    $("#divSelInspector").html(tmpSelInspector({data:data.data}));
                    $("#selInspector").val(data.id);
                }
            }else{
                alert_error("Error :(");
            }
            close_modal("modalAddNewEmplo");
            $("#divModalBodyAddNewEmplo").html("");

        },'json');
    });

    function btnAddNewEmployee(tipo) {
        open_modal("modalAddNewEmplo");
        var tmpModalBodyNewEmployee=_.template($("#tmpModalBodyNewEmployee").html());
        $("#divModalBodyAddNewEmplo").html(tmpModalBodyNewEmployee);
        $("#tipo").val(tipo);
        if(tipo =="jefebrigada"){
           $("#mAddNewEmploTitle").html("<b>Nuevo <u style='font-size: 19px;'>Jefe de brigada</u></b>");
        }else{
            $("#mAddNewEmploTitle").html("<b>Nuevo <u style='font-size: 19px;' > Inspector</u></b>");
        }
    }

</script>

<script type="text/template" id="tmpModalBodyNewEmployee">
    <div class="row">
        <div class="col-lg-12">
            <form class="form-horizontal" id="formNewEmployee">
                <input type="hidden" id="tipo" name="tipo" value="">
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="demo-hor-inputemail">Nombre*</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" id="nombreins" name="nombreins" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="demo-hor-inputpass">Apellidos*</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" id="apellidosins" name="apellidosins" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="demo-hor-inputpass">DNI*</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" id="dniins" name="dniins" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="demo-hor-inputpass">Email</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" id="emailins" name="emailins"  class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="demo-hor-inputpass">Tel./Cel</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" id="telcelins" name="telcelins" class="form-control">
                        </div>
                    </div>

                </div>
                <div class="panel-footer text-right">
                    <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
                    <button class="btn btn-success" id="btnSaveNewEmployee" type="button">Guardar</button>
                </div>
            </form>
        </div>
    </div>

</script>

<script type="text/template" id="tmpSelEstadosViviendaInsp">
    <select class="form-control" id="selEstadosViviendaInsp" name="selEstadosViviendaInsp">
        <option value="0">Seleccione...</option>
        <% _.each(data,function(i,k){ %>
        <option value="<%= i.idestadoinspeccion %>"><%= i.nombre%></option>
        <%  })%>
    </select>
</script>


<script type="text/template" id="tmpSelEstablecimientoSalud">
    <select class="form-control" id="selEstablecimientoSalud" name="selEstablecimientoSalud">
        <option value="0">Seleccione...</option>
        <% _.each(data,function(i,k){ %>
        <option value="<%= i.COD_ESTAB %>"><%= i.DESC_ESTAB%></option>
        <%  })%>
    </select>
</script>

<script type="text/template" id="tmpSelRedSalud">
    <select class="form-control" name="selRedSalud" id="selRedSalud" >
        <option value="0">Seleccione...</option>
        <% _.each(data,function(i,k){
        var lb='';
            if(i.value == region){lb='selected="selected"'}
        %>
        <option value="<%= i.COD_DISA+'-'+i.COD_RED %>"  <%=lb%>  ><%= i.value%></option>
        <%  })%>
    </select>
</script>

<script type="text/template" id="tmpSelJefeBrigada">
    <select class="form-control" name="selJefeBrigada" id="selJefeBrigada" style="width: 80%;display: inline-block" >
        <option value="0">Seleccione...</option>
        <% _.each(data,function(i,k){%>
        <option value="<%= i.id%>"><%= i.nombrearearesponsable%></option>
        <%  })%>
    </select>
    <button type="button" class="btn btn-sm btn-dark" onclick="btnAddNewEmployee('jefebrigada')" style="display: inline-block;"><i class="fa fa-plus"></i></button>
</script>

<script type="text/template" id="tmpSelInspector">
    <select class="form-control" name="selInspector"  id="selInspector" style="width: 80%;display: inline-block" >
        <option value="0">Seleccione...</option>
        <% _.each(data,function(i,k){%>
        <option value="<%= i.id%>"><%= i.nombrearearesponsable%></option>
        <%  })%>
    </select>
    <button type="button" class="btn btn-sm btn-dark" style="display: inline-block;" onclick="btnAddNewEmployee('inspector')" ><i class="fa fa-plus"></i></button>
</script>

<script type="text/template" id="tmpTbodyTableDetailInspeccionVivienda">
    <% _.each(data,function(i,k){
        var totalix= Number(i.ic1) + Number(i.ic2) + Number(i.ic3) + Number(i.i4) + Number(i.i5) + Number(i.i6) + Number(i.i7) + Number(i.i8);
        var totalpx=Number(i.pc1) + Number(i.pc2) + Number(i.pc3) + Number(i.p4) + Number(i.p5) + Number(i.p6) + Number(i.p7) + Number(i.p8);
        var totaltx=Number(i.tc1) + Number(i.tc2) + Number(i.tc3) + Number(i.t4) + Number(i.t5) + Number(i.t6)  + Number(i.t8);
         var totalex=Number(i.e7);
    %>
    <tr>
        <td><%=(k+1)%></td>
        <td ><%= i.idmanzana %> </td>
        <td ><%= i.direccion+" "+i.nro %>  </td>
        <td ><%if(i.idestadoinspeccion == "1"){ print("X");} %> </td>
        <td ><%if(i.idestadoinspeccion == "2"){ print("X");} %> </td>
        <td ><%if(i.idestadoinspeccion == "3"){ print("X");} %>  </td>
        <td ><%if(i.idestadoinspeccion == "4"){ print("X");} %>  </td>
        <td style="background: #e6e4e4;" ><%= i.ic1  %></td>
        <td ><%= i.pc1  %> </td>
        <td ><%= i.tc1  %> </td>

        <td style="background: #e6e4e4;" ><%= i.ic2  %> </td>
        <td ><%= i.pc2  %></td>
        <td ><%= i.tc2  %></td>

        <td  style="background: #e6e4e4;"><%= i.ic3  %></td>
        <td ><%= i.pc3  %></td>
        <td ><%= i.tc3  %></td>

        <td style="background: #e6e4e4;" ><%= i.i4  %></td>
        <td ><%= i.p4  %></td>
        <td ><%= i.t4  %></td>

        <td style="background: #e6e4e4;" ><%= i.i5  %></td>
        <td ><%= i.p5  %></td>
        <td ><%= i.t5  %></td>

        <td  style="background: #e6e4e4;" ><%= i.i6 %></td>
        <td ><%= i.p6  %></td>
        <td ><%= i.t6  %></td>

        <td style="background: #e6e4e4;" ><%= i.i7  %></td>
        <td ><%= i.p7  %></td>
        <td ><%= i.e7  %></td>

        <td style="background: #e6e4e4;" ><%= i.i8  %></td>
        <td ><%= i.p8  %></td>
        <td ><%= i.t8  %></td>

        <td style="background: #e6e4e4;" ><%= totalix  %></td>
        <td ><%= totalpx  %></td>
        <td ><%= totaltx  %></td>
        <td ><%= totalex %></td>
        <td style="text-align: right;" ><%= i.larvicidadaplicada%></td>
        <td >
            <a title="Eliminar" href="javascript:void(0)" class="btn btn-link btn-sm" onclick="deleteDetailInspVivienda('<%= i.iddetinspeccion %>')"><i style="font-size: 15px;color: red"  class="fa fa-trash"></i></a>
        </td>
    </tr>

    <% }); %>

</script>

<script type="text/template">

</script>

<script type="text/template" id="tmpDivTablaCriaderosIsinspeccion" >
    <div class="col-sm-6">
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table style="margin-bottom: 0px;" class="table table-condensed table-bordered table-hover">
                    <thead>
                    <tr>
                        <td></td>
                        <td><b style="visibility: hidden">__________________________</b></td>
                        <td>I</td>
                        <td>P</td>
                        <td>T</td>
                        <td>E</td>
                    </tr>
                    </thead>
                    <tbody >
                    <tr>
                        <td>C1</td>
                        <td style="font-size: 12px;padding: 0px" >Tanque Alto, Tanque Bajo, Pozos</td>
                        <td style="padding: 0px"><input type="text" id="c1i" name="c1i" onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);" class="form-control vali" value="" style="text-align: right;padding-right: 4px; " ></td>
                        <td style="padding: 0px"><input type="text" id="c1p" name="c1p"   onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control valp" value="" style="text-align: right;padding-right: 4px;"></td>
                        <td style="padding: 0px"><input type="text" id="c1t" name="c1t"  onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control valt" value="" style="text-align: right;padding-right: 4px;"></td>
                        <td style="background-color: #eaeaea" >-</td>
                    </tr>
                    <tr>
                        <td>C2</td>
                        <td style="padding: 0px">
                            Barril, Cilindro,Sansón</td>
                        <td style="padding: 0px"><input type="text" id="c2i" name="c2i" onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control vali" value="" style="text-align: right;padding-right: 4px;"> </td>
                        <td style="padding: 0px"><input type="text" id="c2p"  name="c2p" onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control valp " value="" style="text-align: right;padding-right: 4px;"></td>
                        <td style="padding: 0px"><input type="text" id="c2t"   name="c2t"  onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control valt" value="" style="text-align: right;padding-right: 4px;"></td>
                        <td style="background-color: #eaeaea" >-</td>
                    </tr>
                    <tr>
                        <td>C3
                        </td>
                        <td style="padding: 0px">Balde, Batea, Tina</td>
                        <td style="padding: 0px"><input type="text" id="c3i" name="c3i"  onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control vali" style="text-align: right;padding-right: 4px;"></td>
                        <td style="padding: 0px"><input type="text" id="c3p"  name="c3p"   onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"   class="form-control valp " style="text-align: right;padding-right: 4px;"></td>
                        <td style="padding: 0px"><input type="text" id="c3t" name="c3t"  onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control valt " style="text-align: right;padding-right: 4px;"></td>
                        <td style="background-color: #eaeaea" >-</td>
                    </tr>
                    <tr>
                        <td>C4</td>
                        <td style="padding: 0px">Ollas, Cántaro de barro</td>
                        <td style="padding: 0px"><input type="text" id="c4i" name="c4i"    onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control vali" style="text-align: right;padding-right: 4px;" ></td>
                        <td style="padding: 0px"><input type="text" id="c4p"  name="c4p"   onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control valp" style="text-align: right;padding-right: 4px;"></td>
                        <td style="padding: 0px"><input type="text" id="c4t"  name="c4t"   onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control valt" style="text-align: right;padding-right: 4px;"></td>
                        <td style="background-color: #eaeaea" >-</td>
                    </tr>
                    <tr>
                        <td>C5</td>
                        <td style="padding: 0px">Florero, Maceta</td>
                        <td style="padding: 0px"><input type="text" id="c5i"  name="c5i"  onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control vali" style="text-align: right;padding-right: 4px;"></td>
                        <td style="padding: 0px"><input type="text" id="c5p"  name="c5p"  onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"   class="form-control valp" style="text-align: right;padding-right: 4px;" ></td>
                        <td style="padding: 0px"><input type="text" id="c5t"  name="c5t"   onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control valt" style="text-align: right;padding-right: 4px;"></td>
                        <td style="background-color: #eaeaea" >-</td>
                    </tr>
                    <tr>
                        <td>C6</td>
                        <td style="padding: 0px">Llantas</td>
                        <td style="padding: 0px"><input type="text" id="c6i" name="c6i"  onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control vali" style="text-align: right;padding-right: 4px;" ></td>
                        <td style="padding: 0px"><input type="text" id="c6p" name="c6p"   onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control valp " style="text-align: right;padding-right: 4px;"></td>
                        <td style="padding: 0px"><input type="text" id="c6t" name="c6t"  onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control valt" style="text-align: right;padding-right: 4px;"></td>
                        <td style="background-color: #eaeaea" >-</td>
                    </tr>
                    <tr>
                        <td>C7</td>
                        <td style="padding: 0px">Inservibles que son criaderos</td>
                        <td style="padding: 0px"><input type="text" id="c7i" name="c7i"   onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);" class="form-control vali" style="text-align: right;padding-right: 4px;"></td>
                        <td style="padding: 0px"><input type="text" id="c7p"  name="c7p"  onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control valp" style="text-align: right;padding-right: 4px;"></td>
                        <td style="background-color: #eaeaea" >-</td>
                        <td style="padding: 0px"><input type="text" id="c7e" name="c7e"   onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control vale" style="text-align: right;padding-right: 4px;"></td>
                    </tr>
                    <tr>
                        <td>C8</td>
                        <td style="padding: 0px">Otros Criaderos</td>
                        <td style="padding: 0px"><input type="text" id="c8i" name="c8i"   onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);" class="form-control vali" style="text-align: right;padding-right: 4px;" ></td>
                        <td style="padding: 0px"><input type="text" id="c8p" name="c8p"  onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control valp" style="text-align: right;padding-right: 4px;"></td>
                        <td style="padding: 0px"><input type="text" id="c8t"  name="c8t"  onkeydown="return soloNumeros(event);" onchange="return soloNumeros(event);"  class="form-control valt" style="text-align: right;padding-right: 4px;"></td>
                        <td style="background-color: #eaeaea" >-</td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td> </td>
                        <td>Totales</td>
                        <td style="text-align: right;" ><b  id="btotali"></b> </td>
                        <td style="text-align: right;"><b  id="btotalp"></b></td>
                        <td style="text-align: right;"><b   id="btotalt"></b> </td>
                        <td style="text-align: right;"> <b  id="btotale"></b> </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    </div>
    <div class="col-sm-2">
        <div class="form-group" style="width: 97%;">
            <label class="control-label">Larvicida (Gr)</label>
            <input type="text" id="larvicida" name="larvicida" onkeydown="return soloNumeros(event);" class="form-control" value="">
        </div>
    </div>
</script>

<script type="text/template" id="tmpDivFormRegInspVivienda">
    <form id="formRegInspVivienda">

        <div class="panel-body" style="padding-bottom: 0px;padding-top: 0px;">
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group" style="width: 97%;">
                        <label class="control-label">Vivienda</label>
                        <input type="text" id="vivienda" name="vivienda" class="form-control" value="">
                        <input type="hidden" id="idvivienda" name="idvivienda" class="form-control" value="0">
                    </div>
                    <div class="form-group"  style="width: 95%;" >
                        <label class="control-label">Estado</label>
                        <div id="divEstadoViviendaInsp">

                        </div>
                    </div>
                </div>
                <div id="divTablaCriaderosIsInspeccionda" class=""  style="margin-left: 10px">

                </div>
                <div class="col-sm-1">
                    <div class="form-group"  style="width: 95%;" >
                        <label class="control-label" style="visibility: hidden;"> _____________</label>
                        <button id="btnRegistrarInspeccionVivienda" type="button" class="btn btn-mint"><b>Registrar</b></button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</script>
<script type="text/template" id="tmpDivTableGInspeccionesRealizadaas">
    <b style="font-size: 15px;"> &nbsp; &nbsp; &nbsp;Inspecciones Realizadas</b>
    <div class="table-responsive">
        <table class="table table-bordered table-condensed table-hover"  style="border-color: #1a2c3f">
            <thead style="font-size: 12px;">
            <tr>
                <th rowspan="3" style="text-align: right;"> <p> #</p> </th>
                <th rowspan="3" style="text-align: right;"> <p style=" writing-mode: vertical-lr; transform: rotate(180deg)" > N°.Manzana</p> </th>
                <th rowspan="3" style="text-align: center" ><b>Dirección <spam style="visibility: hidden;">_______________</spam> </b></th>
                <th colspan="4"  style="text-align: center">Vivienda</th>
                <th colspan="28" style="text-align: center" >Criaderos</th>
                <th rowspan="3" style="text-align: center" >Larvicida (gr)</th>
                <th rowspan="3" style="text-align: center" >*</th>
            </tr>
            <tr  >
                <th rowspan="2"  ><p style=" writing-mode: vertical-lr; transform: rotate(180deg)" >Inspeccionada</p></th>
                <th rowspan="2"    ><p style=" writing-mode: vertical-lr; transform: rotate(180deg)" >Cerrada</p></th>
                <th rowspan="2"   ><p style=" writing-mode: vertical-lr; transform: rotate(180deg)" >Deshabitada</p> </th>
                <th rowspan="2"    ><p style=" writing-mode: vertical-lr; transform: rotate(180deg)" >Renuente</p> </th>
                <th colspan="3" style="text-align: center" >C1 <br> Tanque Alto, Tanque Bajo, Pozos </th>
                <th colspan="3" style="text-align: center"  >C2 <br> Barril, Cilindro,Sansón </th>
                <th colspan="3" style="text-align: center" >C3 <br> Balde, Batea, Tina </th>
                <th colspan="3" style="text-align: center" >C4 <br>Ollas, Cántaro de barro</th>
                <th colspan="3" style="text-align: center" >C5 <br>Florero, Maceta </th>
                <th colspan="3" style="text-align: center" >C6 <br>Llantas</th>
                <th colspan="3" style="text-align: center" >C7 <br>Inservibles que son criaderos</th>
                <th colspan="3" style="text-align: center" >C8 <br> Otros Criaderos </th>
                <th colspan="4" style="text-align: center" >Total de Criaderos</th>
            </tr>
            <tr  >
                <th style="background: #e6e4e4;"  >I</th>
                <th>P</th>
                <th>T</th>
                <th style="background: #e6e4e4;"  >I</th>
                <th>P</th>
                <th>T</th>
                <th style="background: #e6e4e4;"  >I</th>
                <th>P</th>
                <th>T</th>
                <th style="background: #e6e4e4;"  >I</th>
                <th>P</th>
                <th>T</th>
                <th style="background: #e6e4e4;"  >I</th>
                <th>P</th>
                <th>T</th>
                <th style="background: #e6e4e4;"  >I</th>
                <th>P</th>
                <th>T</th>
                <th style="background: #e6e4e4;"  >I</th>
                <th>P</th>
                <th>E</th>
                <th style="background: #e6e4e4;"  >I</th>
                <th>P</th>
                <th>T</th>
                <th style="background: #e6e4e4;"  >I</th>
                <th>P</th>
                <th>T</th>
                <th>E</th>

            </tr>
            </thead>
            <tbody id="tbodyDetailInspeccionVivienda">
            </tbody>
        </table>
    </div>
</script>
<script type="text/template" id="tmpFormComienzaInspeccion">
    <input id="idinspeccion" name="idinspeccion" value="0" type="hidden">
    <form class="form-horizontal" id="formComienzaInspeccion">
        <div class="panel-body" style="padding-bottom: 0px;padding-top: 0px;"  >
            <div class="form-group" style="margin-bottom: 5px;" >
                <label class="col-sm-1 control-label" >Region: </label>
                <div class="col-sm-2">
                    <input type="text"  id="region" name="region" class="form-control" readonly="readonly" >
                </div>
                <label class="col-sm-2 control-label" for="demo-hor-inputemail">Red Salud</label>
                <div class="col-sm-2"id="divRedSalud">
                    ...
                </div>
                <label class="col-sm-2 control-label"  >Jefe de brigada: </label>
                <div class="col-sm-3" id="divSelJefeBrigada">
                    <select id="selJefeBrigada" name="selJefeBrigada" class="form-control" style="display: inline-block;width: 80%">
                        <option>Seleccione...</option>
                    </select>
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 5px;" >
                <label class="col-sm-1 control-label" >Provincia: </label>
                <div class="col-sm-2">
                    <input type="text" readonly="readonly" id="provincia" name="provincia" class="form-control">
                </div>
                <label class="col-sm-2 control-label" >IPRESS: </label>
                <div class="col-sm-2" id="divEstablecimientosalud">
                   ...
                </div>
                <label class="col-sm-2 control-label">Nombre del inspector: </label>
                <div class="col-sm-3" id="divSelInspector">
                    <select id="selInspector" name="selInspector" class="form-control">
                        <option>Seleccione...</option>
                    </select>
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 5px;" >
                <label class="col-sm-1 control-label"  >Distrito: </label>
                <div class="col-sm-2">
                    <input type="text"  id="distrito" name="distrito" class="form-control">
                    <input type="hidden"  id="iddistrito" name="iddistrito" class="form-control" value="">
                    <input type="hidden"  id="idubigeo" name="idubigeo" class="form-control" value="">
                </div>
                <label class="col-sm-2 control-label"  >Sector: </label>
                <div class="col-sm-2">
                    <input type="text"   id="sector" name="sector" class="form-control">
                </div>
                <label class="col-sm-2 control-label" for="demo-hor-inputpass">Tipo de Vigilancia: </label>
                <div class="col-sm-2">
                    <select id="selTipoVigilancia" name="selTipoVigilancia" class="form-control">
                        <option value="">Seleccione...</option>
                        <option value="pre">PRE</option>
                        <option value="post">POST</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label" style="visibility: hidden" for="demo-hor-inputpass">Localidad: </label>
                <div class="col-sm-2" style="visibility: hidden">
                    <input type="text"     id="localidad" name="localidad" class="form-control">
                </div>
                <label class="col-sm-2 control-label" for="demo-hor-inputpass">F. Intervención: </label>
                <div class="col-sm-2">
                    <input type="date"  style="line-height: 10px;" id="fintervencion" name="fintervencion" class="form-control">
                </div>
                <label class="col-sm-2 control-label" for="demo-hor-inputpass">Control: </label>
                <div class="col-sm-2">
                    <select id="selControl" name="selControl" class="form-control" >
                        <option value="">Seleccione...</option>
                        <option value="1">I</option>
                        <option value="2">II</option>
                        <option value="3">III</option>
                        <option value="4">IV</option>
                        <option value="5">V</option>
                        <option value="6">VI</option>
                        <option value="7">VII</option>
                        <option value="8">VIII</option>
                        <option value="9">IX</option>
                        <option value="10">X</option>
                    </select>
                </div>
            </div>
            <div class="form-group" id="divBtnComenzarInspeccion">
                <div class="col-lg-10" style="text-align: right">
                    <button type="button" class="btn btn-mint" id="btnComenzarInspeccion" ><b>Comenzar!</b></button>
                </div>
            </div>
            <div class="form-group">
                <hr style="margin: 0px;">
            </div>
        </div>
    </form>
</script>
