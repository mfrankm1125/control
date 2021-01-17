<style>
    ul.ui-autocomplete {
        z-index: 2100;
    }
    .modal { overflow: auto !important; }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-control">
                    <a href="<?=base_url()?>insovitrampas/vermapa" class="btn btn-link">Reportes</a>
                  </div>
                <h3 class="panel-title">Inspección de Ovitrampas  </h3>
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
                    <tr>
                        <th>#</th>
                        <th>Ubigeo</th>
                        <th>IPRESS</th>
                        <th>Inspector</th>
                        <th title="Semana Entomológica">SE</th>
                        <th>Fecha Inspección</th>
                        <th>Accion</th>
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



<div class="modal fade"   id="modal_id" role="dialog" tabindex="-1" data-backdrop="static"  aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 97%">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divModalBody">

            </div>
        </div>
    </div>
</div>



<div class="modal fade"   id="modalNewInspector" role="dialog" data-backdrop="static"    tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog"  >
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="">Nuevo Inspector</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divModalBodyNewInspector">

            </div>
        </div>
    </div>
</div>


<div class="modal fade"   id="modalEditSetNewPointOvitrampa" role="dialog" data-backdrop="static"    tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog"  >
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id=""></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divModalEditSetNewPointOvitrampa"  >

            </div>
        </div>
    </div>
</div>

<link href="<?=base_url()?>assets/scripts/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<?php echo $_css?>
<?php echo $_js?>
<script src="<?=base_url()?>assets/scripts/jquery-ui-1.12.1/jquery-ui.min.js"></script>

<script type="text/javascript">
    var iniLatLng= {lat: -6.4914193, lng: -76.3596651};
    var inspectores=JSON.parse('<?=json_encode($inspectores)?>');
    var estadosviviendaeninspeccion=JSON.parse('<?=json_encode($estadosviviendaeninspeccion)?>');
    var tmpSelInspectores=null;
    var rowSelection=null;
    var map,marker;


    function iniMaps(OptionsMap){
        map = new google.maps.Map(document.getElementById('map'),OptionsMap);
        bounds=new google.maps.LatLngBounds();
        marker = new google.maps.Marker({
            position: iniLatLng,
            map: map,
            draggable:true,
            //label:{text: "Hola", color: "black",fontWeight:"bold",fontSize:"16px"},
            title: 'Hello World!'
        });
        map.addListener('click', function(event) {
            var lat = event.latLng.lat();
            var lng = event.latLng.lng();
            //setInputsLatLng(lat,lng);
            var latlng= {lat:lat , lng:lng};
            marker.setPosition(latlng);
            $("#valLatEdit").val(lat);
            $("#valLngEdit").val(lng);
        });

        google.maps.event.addListener(marker, 'dragend', function(event)
        {
            var lat = event.latLng.lat();
            var lng = event.latLng.lng();
            //setInputsLatLng(lat,lng);
            var latlng= {lat:lat , lng:lng};
            $("#valLatEdit").val(lat);
            $("#valLngEdit").val(lng);

            // console.log(lat,lng,event);
        });

    }
    $(document).ready(function () {
        iniDataTable();
        //   iniAutoCompleteDistrito();
    });

    function iniDataTable() {
        rowSelection = $('#tabla_grid').DataTable({
            "ajax": url_base+'insovitrampas/getDataTable',
            "columns": [
                { "data": null },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.DESC_DPTO+"-"+full.DESC_PROV+"-"+full.DESC_DIST;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.DESC_ESTAB;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.inspector;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                { "data": "semanainsp" },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.finspeccion;

                        //var lastname = full.apellidos;
                        var html="<input style='width: 125px;border: 0;background-color: transparent;' type='date' disabled  value='"+name+"'><span style='visibility: hidden;font-size: 1px;'>"+name+"</span> ";
                        return html;
                    }
                },
                {  sortable: false,
                    "render": function ( data, type, full, meta ) {
                        var id = full.idinspeccion;
                        var html="";
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

    function iniSelInspectores() {
        tmpSelInspectores=_.template($("#tmpSelInspectores").html());
        $("#divSelInspector").html(tmpSelInspectores({data:inspectores}));
    }

    function iniSelEstadoViviendaInspeccion() {
         var tmpSelEstadoViviendaEnInspeccion=_.template($("#tmpSelEstadoViviendaEnInspeccion").html());
        $("#divEstadoVivienda").html(tmpSelEstadoViviendaEnInspeccion({data:estadosviviendaeninspeccion}));
    }

    function numerarTabla(idTable){
        var table = document.getElementById(idTable);
        var trs = table.querySelectorAll('tr');
        var counter = 1;
        Array.prototype.forEach.call(trs, function(x,i){
            var firstChild = x.children[0];
            firstChild.textContent=counter;
            counter ++
        });
    }

    $(document).on("click","#btnAdd" ,function () {
        var tmpDivModalBody=_.template($("#tmpDivModalBody").html());
        $("#divModalBody").html(tmpDivModalBody);
        open_modal("modal_id");
        iniSelInspectores();
        iniAutoCompleteDistrito();
        getDataSelRed(0);
    });

    function iniViviendas(){
        var tmpSelViviendas=_.template($("#tmpSelViviendas").html());
        $("#").html(tmpSelViviendas({data:viviendas}));
    }
    
    $(document).on("click","#btnRegInTableInsOvi",function () {
        var finspeccion=$("#finspeccion");
        var codovitrampa=$("#codovitrampa");
        var idcodovitrampa=$("#idcodovitrampa");
        var dirvivienda=$("#dirvivienda");
        var idvivienda=$("#idvivienda");
        var ubicacion=$("#ubicacion");
        var sector=$("#sector");
        var mz=$("#mz");
        var lat=$("#lat");
        var lng=$("#lng");
        var alt=$("#alt");
        var nhuevos=$("#nhuevos");
        var bol=true;
        bol=bol && finspeccion.required();
        bol=bol && $("#selEstadoVivienda").requiredSelect();
        bol=bol && codovitrampa.required();
        bol=bol && dirvivienda.required();
        if( Number(nhuevos.val()) < 0){
            bol=bol && false;
            alert_error("Complete los campos requeridos");
        }

        if(bol == false ){ return 0;}

        var dataOut={finspeccion:finspeccion.val(),
            codovitrampa:codovitrampa.val(),
            idcodovitrampa:idcodovitrampa.val(),
            dirvivienda:dirvivienda.val(),
            idvivienda:idvivienda.val(),
            ubicacion:ubicacion.val(),
            sector:sector.val(),
            mz:mz.val(),
            lat:lat.val(),
            lng:lng.val(),
            alt:alt.val(),
            nhuevos:nhuevos.val(),
            idinspeccion:$("#idinspeccion").val(),
            selEstadoVivienda:$("#selEstadoVivienda").val()
        };

        $.post(url_base+"insovitrampas/setDetalleInsOvitrampa",dataOut,function (data) {
           // console.log(data);
              codovitrampa.val("");
              idcodovitrampa.val("");
              dirvivienda.val("");
              idvivienda.val("");
              ubicacion.val("");
              sector.val("");
              mz.val("");
              lat.val("");
              lng.val("");
              alt.val("");
              nhuevos.val("");
            $("#selEstadoVivienda").val(0);
            alert_success("Se registro correctamente");
            getDetalleInspeccionOvitrampa();
        },'json');


        //numerarTabla("tbodyTableListInspOvi");
    });



    $(document).on('click', '.eliminart', function() {
        var objCuerpo = $(this).parents().get(2);
        if ($(objCuerpo).find('tr').length == 1) {
            if (!confirm('Esta es el única fila de la lista ¿Desea eliminarla?')) {
                return;          }
        }
        var objFila = $(this).parents().get(1);
        $(objFila).remove();

        numerarTabla("tbodyTableListInspOvi");
    });

    $(document).on("click","#btnComenzarOvitrampas",function () {
        var divbtnComenzarOvitrampas=$("#divbtnComenzarOvitrampas");
        var btn=$(this);
        var  region = $("#region");
        var  provincia = $("#provincia");
        var  distrito = $("#distrito");
        var  finspeccion = $("#finspeccion");
        var selEstablecimientoSalud=$("#selEstablecimientoSalud");
        var bol=true;
        bol=bol&&distrito.required();
        bol=bol&& selEstablecimientoSalud.requiredSelect();
        bol=bol&&$('#selInspector').requiredSelect();
        bol=bol&&finspeccion.required();
        if($("#iddistrito").val() ==""){
            bol=bol&&false;
            alert_error("Seleccione un distrito");
        }
        if(!bol){return 0;}
        btn.button("loading");

        var form=$("#formRegInspOvi").serialize();
        $.post(url_base+"insovitrampas/setIniRegInsOvi",form,function (data) {
            if(data.status == "ok"){
                divbtnComenzarOvitrampas.remove();
                // console.log(data);
                $("#idinspeccion").val(data.idinspecion);
                refrescar();
                var tmpDivRegInsOvi=_.template($("#tmpDivRegInsOvi").html());
                $("#divRegInspeccionOvi").html(tmpDivRegInsOvi);
               // iniAutoCompleteBuscarOvitrampaByDistrito();
                //iniSelEstadoViviendaInspeccion();
                getListOvitrampasByIpress();
            }else{
                if(data.status=="failfecha"){
                    alert_error("Ya se registro la inspección con esa fecha");
                }else{
                    alert_error();
                }
                btn.button("reset");
            }
        },'json');
    });

    function iniAutoCompleteDistrito() {
        $("#distrito").autocomplete({
            source: "<?= base_url(); ?>insovitrampas/getDepProvByDist",
            minLength: 1,// tamaño de cadena
            delay: 250,
            search: function( event, ui ) {
                $("#iddistrito").val("");
                $("#idubigeo").val("");
                $("#region").val("");
                $("#provincia").val("");
                $("#divRedSalud").html("...");
                $("#divIPRESS").html("...");
            },
            select: function( event, ui ) {
                var dt=ui.item;
                $("#region").val(dt.DESC_DPTO);
                $("#provincia").val(dt.DESC_PROV);
                $("#iddistrito").val(dt.UBIGEO);
                //$("#lat").removeAttr("readonly","readonly");
                //$("#lng").removeAttr("readonly","readonly");
                //$("#codOvi").removeAttr("readonly","readonly");

            }
        });
    }

    function iniAutoCompleteBuscarOvitrampaByDistrito() {
        $("#codovitrampa").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?= base_url(); ?>insovitrampas/getOvitrampasByDistrito",
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

            },
            select: function( event, ui ) {
                $("#idcodovitrampa").val(ui.item.idovitrampa);
                $("#dirvivienda").val(ui.item.direccion+" "+ui.item.nro);
                $("#idvivienda").val(ui.item.idvivienda);
                $("#ubicacion").val(ui.item.ubicacionenvivienda);
                $("#sector").val(ui.item.idsector);
                $("#mz").val(ui.item.idmanzana);
                $("#lat").val(ui.item.lat);
                $("#lng").val(ui.item.lng);
               // $("#alt").val(ui.item.idovitrampa);
                //$("#lat").removeAttr("readonly","readonly");
                //$("#lng").removeAttr("readonly","readonly");
                //$("#codOvi").removeAttr("readonly","readonly");
            }
        });
    }

    function ver(id,data) {
        var data=data;
        console.log(data);
        var tmpDivModalBody=_.template($("#tmpDivModalBody").html());
        $("#divModalBody").html(tmpDivModalBody);
        $("#btnComenzarOvitrampas").remove();
        iniSelInspectores();
        open_modal("modal_id");

        $("#region").val(data.DESC_DPTO);
        $("#provincia").val(data.DESC_PROV);
        $("#distrito").val(data.DESC_DIST);
        $("#idubigeo").val(data.idubigeo);
        $("#iddistrito").val(data.iddistrito);
        $("#localidad").val(data.localidad);
        $("#finspeccion").val(data.finspeccion);
        var nweek=getWeekNumber(data.finspeccion);
        $("#nsemana").val(nweek);
        $("#selInspector").val(data.id);
        $("#idinspeccion").val(data.idinspeccion);
        getDataSelRed(data.idipress);
        ////////
        var tmpDivRegInsOvi=_.template($("#tmpDivRegInsOvi").html());
        $("#divRegInspeccionOvi").html(tmpDivRegInsOvi({dataini:data}));
       // iniAutoCompleteBuscarOvitrampaByDistrito();
       // iniSelEstadoViviendaInspeccion();
       // getDetalleInspeccionOvitrampa();
        $("#finspeccion2").val(data.finspeccion);

    }
    
    function getListOvitrampasByIpress() {
        var ipress=$("#selEstablecimientoSalud").val();
        var idinspeccion=$("#idinspeccion").val();
        var finspeccion=$("#finspeccion").val();
        var tmpTbodyTableListInspOvi2=_.template($("#tmpTbodyTableListInspOvi2").html());
        $.post(url_base+"insovitrampas/getListOvitrampasByIpress",{"ipress":ipress,"idinspeccion":idinspeccion,"finspeccion":finspeccion},function (data) {
            // console.log(data);
            $("#tbodyTableListInspOvi").html(tmpTbodyTableListInspOvi2({data:data,withtr:1}));
        },'json');
    }

    function getDetalleInspeccionOvitrampa() {
        var tmpTbodyTableListInspOvi =_.template($("#tmpTbodyTableListInspOvi").html());
        $.post(url_base+"insovitrampas/getDetalleInspeccionOvitrampa",{"idinspeccion": $("#idinspeccion").val()},function (data) {
            $("#tbodyTableListInspOvi").html(tmpTbodyTableListInspOvi({data:data}));
        },'json');
    }

    function eliminaDetalleInspeccion(id) {
        if(!confirm("Esta seguro de eliminar el registro")){return 0;}
        $.post(url_base+"insovitrampas/deleteDetalleInspeccionOvitrampa",{"iddetinspeccion": id},function (data) {
            if(data.status=="ok"){
               alert_success("Se eliminó correctamente");
                getListOvitrampasByIpress();
            }else{
               alert_error("Error");
            }
        },'json');
    }

    function getDataSelRed(idestablecimiento) {
        $("#divRedSalud").html("Cargando...");
        var region= $("#region").val();
        var tmpSelRedSalud=_.template($("#tmpSelRedSalud").html());
        $.post(url_base+"ubigeo/getRedSalud",{"region":region},function (data) {
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
        $.post(url_base+"ubigeo/getEstablecimientoSalud",{"coddisa":coddisa,"codred":codred,"iddistrito":iddistrito},function (data) {
            $("#divIPRESS").html(tmpSelEstablecimientoSalud({data:data}));
            $("#selEstablecimientoSalud").val(idestablecimiento);
            if(idestablecimiento > 0){
                getListOvitrampasByIpress();
            }
        },'json');
    }

   function getWeekNumber (valx) {
        var d = new Date(valx+"T00:00:00");  //Creamos un nuevo Date con la fecha de "this".
        d.setHours(0, 0, 0, 0);   //Nos aseguramos de limpiar la hora.
        d.setDate(d.getDate() + 4 - (d.getDay() || 7)); // Recorremos los días para asegurarnos de estar "dentro de la semana"
        //Finalmente, calculamos redondeando y ajustando por la naturaleza de los números en JS:
        return Math.ceil((((d - new Date(d.getFullYear(), 0, 1)) / 8.64e7) + 1) / 7);
    }

    $(document).on("change","#finspeccion",function () {
        var ctx=$(this);
        var val=ctx.val();
       var x= getWeekNumber(val);
       $("#nsemana").val(x);
    });
    $(document).on("change","#nhuevos",function () {
        if( $(this).val() > 0 ){
            $("#selEstadoVivienda").val(1);
        }
    });

    function saveDetailInsp(thisx,isEdit,idEdit) {
     var ctx=$(thisx);

     var tr=ctx.closest("tr");
     var codovitrampa=tr.find("input[name='detcodigoovitrampa']");
     var idcodovitrampa=tr.find("input[name='detidovitrampa']");
     var idvivienda=tr.find("input[name='detidvivienda']");
        var sector=tr.find("input[name='detidsector']");
        var mz=tr.find("input[name='detidmanzana']");
        var selEstadoVivienda=tr.find("select[name='detselEstadoVivienda']");
        var ubicacion=tr.find("input[name='detubicacionvivienda']");
        var lat=tr.find("input[name='detlat']");
        var lng=tr.find("input[name='detlng']");
        var nhuevos=tr.find("input[name='detnrohuevo']");
        var ipress=$("#selEstablecimientoSalud").val();


        var dataOut={finspeccion:$("#finspeccion").val(),
            codovitrampa:codovitrampa.val(),
            idcodovitrampa:idcodovitrampa.val(),
            idvivienda:idvivienda.val(),
            ubicacion:ubicacion.val(),
            sector:sector.val(),
            mz:mz.val(),
            lat:lat.val(),
            lng:lng.val(),
            alt:"",
            nhuevos:nhuevos.val(),
            idinspeccion:$("#idinspeccion").val(),
            selEstadoVivienda:selEstadoVivienda.val(),
            isEdit:isEdit,
            idEdit:idEdit,
            ipress:ipress
        };
     console.log(dataOut);
       // return 0;
        ctx.button("loading");
        var tmpTbodyTableListInspOvi2=_.template($("#tmpTbodyTableListInspOvi2").html());
        $.post(url_base+"insovitrampas/setDetalleInsOvitrampa",dataOut,function (data) {           // console.log(data);
            if(data.status=="ok"){
                var f=tmpTbodyTableListInspOvi2({data:data.data,withtr:0})
                ///console.log(f);
                tr.html(f);
                alert_success("Se registro correctamente");
            }else{
                alert_error("Se registro correctamente");
            }
            ctx.button("reset");
            //getListOvitrampasByIpress();
        },'json');
    }

    $(document).on("change","select[name='detselEstadoVivienda']",function () {
     var ctx=$(this);
     var valsel=ctx.val();
     var  tr=ctx.closest("tr");
     var inpNH=tr.find("input[name='detnrohuevo']");
     if(valsel != "1"){
         inpNH.attr("readonly","readonly");
         inpNH.val("");
     }else{
         inpNH.removeAttr("readonly");
     }
     console.log(valsel,inpNH.val());
    });

    $(document).on("click","#btnAddNewInspector",function () {
        open_modal("modalNewInspector");
        var tmpModalBodyNewInspector=_.template($("#tmpModalBodyNewInspector").html());
        $("#divModalBodyNewInspector").html(tmpModalBodyNewInspector);
    });
    
    $(document).on("click","#btnSaveNewInspector",function () {
        var bol=true;
        var btn=$(this);
        bol=bol&&$("#nombreins").required();
        bol=bol&&$("#apellidosins").required();
        if(!bol){return 0;}
        btn.button("loading");
        var form=$("#formNewInspector").serialize();
        $.post(url_base+"insovitrampas/setNewInspector",form,function (data) {
            if(data.status=="ok"){
                alert_success("Se registro el inspector correctamente");
                var tmpSelInspectores=_.template($("#tmpSelInspectores").html());
                $("#divSelInspector").html(tmpSelInspectores({data:data.data}));
                $("#selInspector").val(data.id);
            }else{

            }
            close_modal("modalNewInspector");
            $("#divModalBodyNewInspector").html("");

        },'json')
    });

    var iniSrcMaps="";
    var inputLat,inputLng;

    function setNewPointOvitrampa(thisx,codovi,vivienda,lat,lng,fecha,iddetinspeccion) {
        inputLat=null;
        inputLng=null;
        open_modal("modalEditSetNewPointOvitrampa");
        var regionM,provinciaM,distritoM,redsaludM,ipressM;
        regionM=$("#region").val();
        provinciaM=$("#provincia").val();
        distritoM=$("#distrito").val();
        redsaludM=$("#selRedSalud option:selected").text();
        ipressM=$("#selEstablecimientoSalud option:selected").text();
        var ddx={"region":regionM,"provincia":provinciaM,"distrito":distritoM,"redsalud":redsaludM,"ipress":ipressM};
        var tmpModalEditSetPointOvitrampa=_.template($("#tmpModalEditSetPointOvitrampa").html());
        var trCtx=$(thisx).closest("tr");
           inputLat=trCtx.find("input[name='detlat']");
           inputLng=trCtx.find("input[name='detlng']");
        $("#divModalEditSetNewPointOvitrampa").html(tmpModalEditSetPointOvitrampa({data:ddx,ovitrampa:codovi,vivienda:vivienda,lat:lat,lng:lng,fecha:fecha,ctx:inputLat,iddetinspeccion:iddetinspeccion}));
       // console.log(trCtx,inputLat);
        if(iniSrcMaps == ""){
            var s = document.createElement("script");
            s.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyAzHReXbQ145y0mfQzSdTnoiav9HOuWTaU&libraries=visualization,places,geometry";
            document.querySelector("head").appendChild(s);
            s.onload = function (ev) {
                var OptionsMap={
                    center:iniLatLng,
                    mapTypeId:google.maps.MapTypeId.roadmap,
                    Zoom: 15,
                    styles: [
                        {
                            featureType: 'poi',
                            stylers: [{ visibility: "off"}]
                        }
                    ]
                    // ,options: { mapTypeId: google.maps.MapTypeId.R }
                };
                iniMaps(OptionsMap);
                var pt = new google.maps.LatLng(Number(lat),  Number(lng));
                marker.setPosition({lat: Number(lat), lng: Number(lng)});
                map.setCenter(pt);
            };
            iniSrcMaps=1;
        }else{
            var OptionsMap={
                center:iniLatLng,
                mapTypeId:google.maps.MapTypeId.roadmap,
                Zoom: 15,
                styles: [
                    {
                        featureType: 'poi',
                        stylers: [{ visibility: "off"}]
                    }
                ]
                // ,options: { mapTypeId: google.maps.MapTypeId.R }
            };
            iniMaps(OptionsMap);
            var pt = new google.maps.LatLng(Number(lat),  Number(lng));
            marker.setPosition({lat: Number(lat), lng: Number(lng)});
            map.setCenter(pt);

        }

    }

    function setNewPointsOviReg(ctxx,ovi,vivienda,fecha,iddetinspeccion) {
        var vlat= $("#valLatEdit").val();
        var vlng= $("#valLngEdit").val();
        inputLat.val(vlat);
        inputLng.val(vlng);
        var btn=$(ctxx);
        btn.button("loading");
        $.post(url_base+"insovitrampas/updLatLngByIdDetInspeccion",{lat:vlat,lng:vlng,iddetinspeccion:iddetinspeccion},function (data) {
            if(data.status =="ok"){
                alert_success();
            }else{
                alert_error();
            }
            btn.button("reset");
            inputLat=null;
            inputLng=null;
            close_modal("modalEditSetNewPointOvitrampa");
        },'json');
         var xddd="setNewPointOvitrampa(this,'"+ovi+"','"+vivienda+"','"+vlat+"','"+vlng+"','"+fecha+"','"+iddetinspeccion+"')" ;

         $("#btnId"+iddetinspeccion).attr('onClick',xddd);

    }

    function eliminar(id) {
        if(!confirm("¿Confirma para eliminar?")){
            return 0;
        }
        $.post(url_base+"insovitrampas/deleteInsp",{id:id},function (data) {
            if(data.status =="ok"){
                alert_success("Se realizó correctamente");
            }else{
                alert_error("Falló");
            }
            refrescar();
        },'json');
    }
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

<script type="text/template" id="tmpDivRegInsOvi">
    <input type="date"   style="height: 30px;padding-left: 0px;padding-right: 0px; visibility: hidden;"  class="form-control text-right" id="finspeccion2" name="finspeccion2">

    <div class="row" style="display: none;">
        <div class="col-sm-1">
            <div class="form-group" style="width: 98%;font-weight: bold; "  >
                <label class="control-label" >Cod. Ovitrampa</label>
                <input type="text" class="form-control" id="codovitrampa" name="codovitrampa" >
                <input type="hidden" class="form-control" id="idcodovitrampa" name="idcodovitrampa" >
            </div>
        </div>

        <div class="col-sm-2" style="visibility: hidden">
            <div class="form-group" style="width: 100%;margin-bottom: 0px;" >
                <label class="control-label">F.Inspección</label>
                <input type="date"  style="height: 30px;padding-left: 0px;padding-right: 0px;"  class="form-control text-right" id="finspeccion2" name="finspeccion2">
            </div>
        </div>
    </div>
    <div class="row" style="display: none">
        <div class="col-sm-2">
            <div class="form-group" style="width: 98%" >
                <label class="control-label">Dir. Vivienda</label>
                <input type="text" class="form-control" id="dirvivienda"  name="dirvivienda">
                <input type="hidden" class="form-control" id="idvivienda"  name="idvivienda">
            </div>
        </div>
        <div class="col-sm-1">
            <div class="form-group" style="width: 98%" >
                <label class="control-label">Ubicación</label>
                <input type="text" class="form-control" id="ubicacion"  name="ubicacion" >
            </div>
        </div>
        <div class="col-sm-1">
            <div class="form-group" style="width: 98%" >
                <label class="control-label">Sector</label>
                <input type="text" class="form-control" id="sector" name="sector" >
            </div>
        </div>
        <div class="col-sm-1">
            <div class="form-group" style="width: 98%" >
                <label class="control-label">Mz</label>
                <input type="text" class="form-control" id="mz" name="mz">
            </div>
        </div>
        <div class="col-sm-1">
            <div class="form-group" style="width: 98%" >
                <label class="control-label">Latitud(W)</label>
                <input type="text" class="form-control" id="lat" name="lat" >
            </div>
        </div>
        <div class="col-sm-1">
            <div class="form-group" style="width: 98%" >
                <label class="control-label">Longitud(S)</label>
                <input type="text" class="form-control" id="lng" name="lng"  >
            </div>
        </div>
        <div class="col-sm-1">
            <div class="form-group" style="width: 98%" >
                <label class="control-label">Altitud(msnm)</label>
                <input type="text" class="form-control" id="alt"  name="alt"  >
            </div>
        </div>
        <div class="col-sm-1">
            <div class="form-group" style="width: 98%" >
                <label class="control-label">Nro. Huevos</label>
                <input type="text" class="form-control" id="nhuevos" name="nhuevos"  >
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group" style="width: 98%" >
                <label class="control-label">Estado de vivienda</label>
                <div id="divEstadoVivienda"></div>
            </div>
        </div>
        <div class="col-sm-1">
            <div class="form-group" style="width: 98%" >
                <label class="control-label">______</label>
                <button type="button" id="btnRegInTableInsOvi" class="btn  btn-mint">
                    <b> Registrar</b>
                </button>
            </div>
        </div>
    </div>
    <div class="row" >
        <div class="table-responsive">
            <table class="table table-condensed table-bordered table-hover"    >
                <thead>
                <tr  >
                    <th colspan="2"   >Ovitrampa</th>
                    <th rowspan="2" >Dirección de la vivienda </th>
                    <th rowspan="2" >Estado de la vivienda, </th>
                    <th rowspan="2"  >Ubicación</th>
                    <th rowspan="2"  >Sec<br>tor</th>
                    <th rowspan="2"  >Mz</th>
                    <th  >Longitud (W)</th>
                    <th >Latitud (S)</th>
                    <th  >Altitud<span style="mso-spacerun:yes">&nbsp;</span></th>
                    <th  >Fecha de
                        inspección</th>
                    <th  >Nro Huevos</th>
                    <th  ></th>
                </tr>
                <tr  >
                    <th  >Nro</th>
                    <th  >Codigo</th>
                    <th >Grados Decimales</th>
                    <th  >Grados Decimales</th>
                    <th  >(msnm)</th>
                    <th  >&nbsp;</th>
                    <th  >&nbsp;</th>
                    <th  >&nbsp;</th>
                </tr>
                </thead>
                <tbody id="tbodyTableListInspOvi">

                </tbody>
            </table>
        </div>
    </div>

</script>

<script type="text/template" id="tmpTbodyTableListInspOvi">
    <% _.each(data,function(i,k){%>
    <tr>
        <td><%=(k+1)%></td>
        <td><%=i.codigoovitrampa%></td>
        <td><%=i.direccion +" "+i.nro%> </td>
        <td><%= i.estadoviviendaeninspeccion %> </td>
        <td><%=i.ubicacionovitrampa%> </td>
        <td><%=i.sectorovitrampa%> </td>
        <td><%=i.mzovitrampa%>  </td>
        <td><%=i.latovitrampa%> </td>
        <td><%=i.lngovitrampa%> </td>
        <td><%=i.altovitrampa%> </td>
        <td><%=i.fechainspeccionovitrampa%> </td>
        <td><input type="text" class="form-control"  value="<%=i.nrohuevosovitrampa%>" style="width: 100px;display: inline-block"> &nbsp;<button type="button" class="btn btn-xs btn-mint" style="display: inline-block;" >Guardar</button> </td>
        <td><button class=" btn btn-xs btn-danger" onclick="eliminaDetalleInspeccion('<%=i.iddetinspeccion%>')" type="button" ><i class="fa fa-trash"></i></button>  </td>

    </tr>
    <% }) %>
</script>


<script type="text/template" id="tmpTbodyTableListInspOvi2">
    <%  _.each(data,function(i,k){
      var isDisabled=""  %>
    <%  if(i.isregistradoins == "noregistradorins"){ isDisabled="disabled='disabled'"; }else{isDisabled="";}  %>
    <% if(withtr == 1){ %>
    <tr>
    <% }%>

        <td><%=(k+1)%></td>
        <td><b><%=i.codigoovitrampa%></b>
            <% if(i.isregistered == "1"){
               print('<i class="fa fa-check-circle" title="Registrado" style="color: green;"></i>');
            }else{  print('<i class="fa fa-times-circle" title="No registrado" style="color: red;"></i>');  }%>

        <input type="hidden" name="detcodigoovitrampa" value="<%=i.codigoovitrampa%>" >
        <input type="hidden" name="detidovitrampa" value="<%=i.idovitrampa%>" >
        <input type="hidden" name="detidvivienda" value="<%=i.idvivienda%>" >
            <input type="hidden" name="detidsector" value="<%=i.idsector%>" >
            <input type="hidden" name="detidmanzana" value="<%=i.idmanzana%>" >
        </td>
        <td><%=i.direccion +" "+i.nro%> </td>
        <td style="padding-left: 1px;padding-right: 1px;" >
            <select class="form-control" <%=isDisabled%> name="detselEstadoVivienda" style="padding-left: 2px;padding-right: 1px;" >
            <% _.each(estadosviviendaeninspeccion,function(ii,kk){%>
                <%
                 var isSelected="";
                if(i.isregistered == "1"){
                   if(ii.idestadoinspeccion == i.estadoviviendainspeccion){
                       isSelected="selected='selected'";
                       }
                }%>
                <option value="<%=ii.idestadoinspeccion%>" <%=isSelected%> ><%=ii.nombre%></option>
            <% }) %>
            </select>
        </td>
        <td style="padding-left: 1px;padding-right: 1px;" > <input style="padding-left: 2px;padding-right: 2px;" type="text" <%=isDisabled%>  class="form-control" name="detubicacionvivienda"  value="<%= i.ubicacionenvivienda %>"  >  </td>
        <td   ><%=i.idsector%> </td>
        <td  ><%=i.idmanzana%>  </td>
        <td style="padding-left: 1px;padding-right: 1px;">   <input style="padding-left: 2px;padding-right: 2px;" type="text" <%=isDisabled%>  class="form-control" name="detlat"  value="<%=i.lat%>"  </td>
        <td style="padding-left: 1px;padding-right: 1px;">   <input  style="padding-left: 2px;padding-right: 2px;width: 80%;display: inline-block;" type="text" <%=isDisabled%>  class="form-control" name="detlng"  value="<%=i.lng%>"  >
            <%
            if(i.isregistered == "1"){ %>
            <% if(i.isregistradoins == "registradorins"){%>
            <span style="display: inline-block;">  <button type="button" style="padding-left: 3px;padding-right: 3px;" class="btn-link" id="btnId<%=i.iddetinspeccion%>" onclick="setNewPointOvitrampa(this,'<%=i.codigoovitrampa%>','<%=i.direccion +i.nro%>','<%=i.lat%>','<%=i.lng%>','<%=i.fechainspeccionovitrampa%>','<%=i.iddetinspeccion%>')" ><i class="fa fa-map-marker"></i></button></span>
            <% }} %>
        </td>
        <td style="padding-left: 1px;padding-right: 1px;" > </td>
        <td style="padding-left: 1px;padding-right: 1px;"> <%= i.fechainspeccionovitrampa %></td>
        <td style="padding-left: 1px;padding-right: 1px;" >
            <%
            var readonly="";
            if(i.isregistered == "1"){
                if(i.estadoviviendainspeccion != "1" ){
                   readonly="readonly='readonly'";
                }
            } %>

            <input type="text" class="form-control" <%=isDisabled%>  name="detnrohuevo" <%=readonly%> value="<%=i.nrohuevosovitrampa%>" style="padding-left: 2px;padding-right: 2px;text-align: right;" ></td>
            <td style="width:100px; " >
                <%
                var readonly="";
                if(i.isregistered == "1"){ %>
                   <% if(i.isregistradoins == "registradorins"){%>
                <button type="button" class="btn btn-xs btn-mint" style="display: inline-block;" onclick="saveDetailInsp(this,1,'<%= i.iddetinspeccion %>')" >Guardar</button>
                <button class=" btn btn-xs btn-danger"  style="display: inline-block;" onclick="eliminaDetalleInspeccion('<%= i.iddetinspeccion %>')" type="button" ><i class="fa fa-trash"></i></button>
                    <% } %>
                <% }else{ %>
                <button type="button" class="btn btn-xs btn-mint" style="display: inline-block;" onclick="saveDetailInsp(this,0,0)" >Guardar</button>

                <% } %>

            </td>

        <% if(withtr == 1){ %>
         </tr>
        <% }%>
    <% }) %>
</script>



<script type="text/template" id="tmpSelInspectores">
  <select class="form-control" id="selInspector" name="selInspector" style=" display:inline-block;width: 90%">
      <option value="0" >Seleccione...</option>
      <% _.each(data,function(i,k){%>
      <option value="<%=i.id%>"><%=i.nombrearearesponsable%></option>
      <% })%>
  </select>
  <button title="Agregar nuevo inspector" type="button" id="btnAddNewInspector"  style="display:inline-block;" class="btn btn-sm btn-dark"><i class="fa fa-plus"></i></button>
</script>

<script type="text/template" id="tmpSelEstadoViviendaEnInspeccion">
    <select class="form-control" id="selEstadoVivienda" name="selEstadoVivienda" >
        <option value="0" >Seleccione...</option>
        <% _.each(data,function(i,k){%>
        <option value="<%=i.idestadoinspeccion%>"><%=i.nombre%></option>
        <% })%>
    </select>
</script>

<script type="text/template" id="tmpDivModalBody">
    <form class="form-horizontal" id="formRegInspOvi">
        <input id="idinspeccion" name="idinspeccion" type="hidden" value="">
        <div class="panel-body" style="padding-bottom: 0px;">

            <div class="row">
                <div class="col-sm-2"   >
                    <div class="form-group" style="width: 100%;" >
                        <label class="control-label">Region</label>
                        <input type="text" readonly="readonly"  id="region" name="region" class="form-control"  value="SAN MARTIN" >

                    </div>
                </div>
                <div class="col-sm-2"  >
                    <div class="form-group" style="width: 100%;">
                        <label class="control-label">Provincia</label>
                        <input type="text" readonly="readonly"   id="provincia" name="provincia" class="form-control" value="SAN MARTIN" >
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group"  style="width: 100%;">
                        <label class="control-label">Distrito</label>
                        <input type="text" placeholder=""  id="distrito"  name="distrito" class="form-control" value="TARAPOTO">
                        <input type="hidden" placeholder="" id="iddistrito"  name="iddistrito" class="form-control" value="01">
                        <input type="hidden" placeholder="" id="idubigeo"  name="idubigeo" class="form-control" value="220901">

                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group"  style="width: 100%;">
                        <label class="control-label">Red Salud</label>
                        <div id="divRedSalud" >
                            ....
                        </div>
                    </div>
                </div>
                <div class="col-sm-2"  >
                    <div class="form-group" style="width: 100%;">
                        <label class="control-label">IPRESS</label>
                        <div  id="divIPRESS">
                            ...
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4"   >
                    <div class="form-group" style="width: 100%;" >
                        <label class="control-label">Inspector</label>
                        <div  id="divSelInspector">

                        </div>
                    </div>
                </div>
                <div class="col-sm-2"   >
                    <div class="form-group" style="width: 100%;" >
                        <label class="control-label">Fecha Inspección</label>
                        <input type="date" placeholder=""  id="finspeccion"  style="height: 30px;"  name="finspeccion" class="form-control" value="" >
                    </div>
                </div>
                <div class="col-sm-2"   >
                    <div class="form-group" style="width: 100%;" >
                        <label class="control-label">Número de Semana</label>
                        <input type="text" placeholder=""  id="nsemana"   name="nsemana" class="form-control" value="" >
                    </div>
                </div>
                <div class="col-sm-2"   >
                    <div class="form-group" style="width: 100%;" id="divbtnComenzarOvitrampas" >
                        <label class="control-label" style="visibility: hidden">__________________</label>
                        <button type="button" id="btnComenzarOvitrampas" class="btn  btn-mint">
                            <b> Comenzar!</b>
                        </button>
                    </div>
                </div>
            </div>



            <div class="form-group" style="margin-bottom: 0px;">
                <hr style="margin: 0px;">
            </div>
        </div>
        <div class="panel-body " style="padding-top: 0px;" id="divRegInspeccionOvi"  >

        </div>

    </form>
</script>
<script type="text/template" id="tmpModalBodyNewInspector">
    <div class="row">
        <div class="col-lg-12">
            <form class="form-horizontal" id="formNewInspector">
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
                    <button class="btn btn-success" id="btnSaveNewInspector" type="button">Guardar</button>
                </div>
            </form>
        </div>
    </div>

</script>

<script type="text/template" id="tmpModalEditSetPointOvitrampa">
    <div class="row">
        <div class="col-lg-12" >
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group" style="width: 100%;margin-bottom: 5px;">
                        <label class="control-label">Region</label><br>
                        <label class="control-label"><b><%=data.region%></b></label>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group" style="width: 100%;margin-bottom: 5px;">
                        <label class="control-label">Provincia</label><br>
                        <label class="control-label"><b><%=data.provincia%></b></label>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group" style="width: 100%;margin-bottom: 5px;">
                        <label class="control-label">Distrito</label><br>
                        <label class="control-label"><b><%=data.distrito%></b></label>

                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group" style="width: 100%;margin-bottom: 5px;">
                        <label class="control-label">Red Salud</label><br>
                        <label class="control-label"><b><%=data.redsalud%></b></label>

                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group" style="width: 100%;margin-bottom: 5px;">
                        <label class="control-label">IPRESS</label><br>
                        <label class="control-label"><b><%=data.ipress%></b></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <hr style="margin-bottom: 5px;margin-top: 0px;">
        </div>
        <div class="col-lg-12">
            <div class="col-sm-3">
                <div class="form-group" style="width: 100%;margin-bottom: 0px;">
                    <label class="control-label" style="margin-bottom: 0px" ><u>Ovitrampa<u></label><br>
                    <label class="control-label"><b><%=ovitrampa%></b></label>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group" style="width: 100%;margin-bottom: 0px;">
                    <label class="control-label"  style="margin-bottom: 0px"><u>Vivienda<u></label><br>
                    <label class="control-label"><b><%=vivienda%></b></label>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group" style="width: 100%;margin-bottom: 0px;">
                    <label class="control-label"  style="margin-bottom: 0px"><u>Fecha Reg.<u></label><br>
                    <label class="control-label"><b><input type="date" value="<%=fecha%>" readonly="readonly" disabled ></b></label>
                </div>
            </div>

        </div>
        <div class="col-lg-12">
            <div class="col-sm-3">
                <div class="form-group" style="width: 100%;">
                    <label class="control-label" style="margin-bottom: 0px;"><u>Lat</u></label>
                    <input style="font-size: 11px;padding-left: 2px;padding-right: 2px;" type="text" class="form-control" id="valLatEdit" value="<%=lat%>">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group" style="width: 100%;">
                    <label class="control-label" style="margin-bottom: 0px;"><u>Lng</u></label>
                    <input style="font-size: 11px;padding-left: 2px;padding-right: 2px;" type="text" class="form-control"  id="valLngEdit" value="<%=lat%>">
                </div>
            </div>
            <div class="col-sm-1">
                <div class="form-group" style="width: 100%;">
                    <label class="control-label" style="margin-bottom: 0px;" >__</label>
                    <button type='button' onclick='setNewPointsOviReg(this,"<%=ovitrampa%>","<%=vivienda%>","<%=fecha%>",<%=iddetinspeccion%>)' class='btn btn-success btn-sm'><b>Actualizar Cordenadas</b></button>
                </div>
            </div>
        </div>
        <div class="col-lg-12" id="map" style="height: 400px;">

        </div>
    </div>

</script>

