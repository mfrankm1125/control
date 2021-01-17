<style>
    ul.ui-autocomplete {
        z-index: 2100;
    }
    .controls {
        background-color: #fff;
        border-radius: 2px;
        border: 1px solid transparent;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        box-sizing: border-box;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        height: 29px;
        margin-left: 17px;
        margin-top: 10px;
        outline: none;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 400px;
    }
    .controls:focus {
        border-color: #4d90fe;s
    }

    #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 300px;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Lista de Ovitrampas</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;" >
                <br>
                <div class="table-toolbar-left">
                    <!--<button class="btn btn-purple" id="add"><i class="demo-pli-add icon-fw"></i> Agregar</button> -->
                    <button class="btn btn-purple" id="btnAdd"><i class="demo-pli-add icon-fw"></i> Agregar Nuevo</button>
                    <!--<div class="btn-group">
                      <button class="btn btn-default" id="refresh"  ><i class="demo-pli-refresh" ></i>Refrescar</button>

                  </div>-->
              </div>
              <br><br>
              <!--CUerpot aqui -->
                <h1></h1>
                <table id="tabla_grid" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr><th>#</th>
                        <th>Codigo</th>
                        <th>Fecha<br>Instalación</th>
                        <th>Ubigeo</th>
                        <th>Dirección</th>
                        <th>Ubicación <br>en vivienda</th>
                        <th>Lat<br>(° Decimales) </th>
                        <th>Lng<br>(° Decimales) </th>
                        <th>Sector</th>
                        <th>Mz</th>
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


<div class="modal fade"   id="modal_id" role="dialog" data-backdrop="static" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 90%;" >
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" >
                <div class="row" id="divFormRegViviendas" >

                </div>
            </div>
        </div>
    </div>
</div>

<link href="<?=base_url()?>assets/scripts/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<?php echo $_css?>
<?php echo $_js?>
<script src="<?=base_url()?>assets/scripts/jquery-ui-1.12.1/jquery-ui.min.js"></script>


<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAzHReXbQ145y0mfQzSdTnoiav9HOuWTaU"></script>
<script type="text/javascript">
    var titles={'addtitle':"Nueva Ovitrampa" ,'updatetitle':"Editar Ovitrampa"};
    var isEdit=null,idEdit=null;

    var rowSelection=null;
    $(document).ready(function () {
        iniDataTable();
     //   iniAutoCompleteDistrito();
    });

    function iniDataTable() {
          rowSelection = $('#tabla_grid').DataTable({
            "ajax": url_base+'ovitrampas/getDataTable',
            "columns": [
                { "data": null },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.codigoovitrampa;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.fechainstalacion;
                        //var lastname = full.apellidos;
                        var html="<input style='width: 125px;border: 0;background-color: transparent;' type='date' disabled  value='"+name+"'><span style='visibility: hidden;font-size: 1px;'>"+name+"</span> ";

                        //var lastname = full.apellidos;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.DESC_DPTO+"-"+full.DESC_PROV+"-"+full.DESC_DIST;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.direccion+" "+full.nro;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.ubicacionenvivienda ;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.lat;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.lng;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.idsector;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.idmanzana;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  sortable: false,
                    "render": function ( data, type, full, meta ) {
                        var id = full.idovitrampa;
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

    $(document).on('click','#btnAdd',function () {

        $('#modalTitle').html(titles['addtitle']);
        open_modal('modal_id');
        iniDatatModal();
        initMap(-6.4858776,-76.3658243);
        getDataSelRed(0);
        iniAutoUbicacionOvitrampaVivienda();
    });

    function iniDatatModal() {
        var tmpFormRegVivienda=_.template($("#tmpFormRegVivienda").html());
        $("#divFormRegViviendas").html(tmpFormRegVivienda);
        iniAutoIssetCodOvitrampa();
        iniAutoCompleteDistrito();
        iniAutoCompleteDirViviendaByDistri();
    }



    function  editar(id){
        $('#btnSave2').remove();
        isEdit=1;

        var idd=parseInt(id);
        $.post(url_base+'tipoanimal/getData','id='+idd,function (data) {
            console.log(data);
            setForm(data[0]);
            $('#modalTitle').html(titles['updatetitle']);
            open_modal('modal_id');
        },'json')
            .always(function () {
            });

    }

    function setForm(data){
        var valData=Object.keys(data).length;
        if(valData > 0){
            $("#name").val(data.nombre);
            $("#isEdit").val(1);
            $("#idEdit").val(data.idtipoanimal);
        }else{
            alert_error("Error");
        }
    }
    function ver(id,data){
      //  console.log(data);
        open_modal('modal_id');
        iniDatatModal();
        $("#isEdit").val(1);
        $("#idEdit").val(id);
        $("#region").val(data.DESC_DPTO);
        $("#prov").val(data.DESC_PROV);
        $("#distrito").val(data.DESC_DIST);
        $("#iddistrito").val(data.COD_DIST);
        $("#idubigeo").val(data.UBIGEO);
        $("#barrio").val(data.idbarrio);
        $("#sector").val(data.idsector);
        $("#manzana").val(data.idmanzana);
        $("#dir").val(data.direccion+" "+data.nro);
        $("#dirnro").val(data.nro);
        $("#lat").val(data.lat);
        $("#lng").val(data.lng);
        $("#codOvi").val(data.codigoovitrampa);
        $("#codOvi").attr("readonly","readonly");
        $("#idCodOviExist").val(data.idovitrampa);
        $("#ubicacionovi").val(data.ubicacionenvivienda);
        $("#ubicacionovi").removeAttr("readonly","readonly");
        $("#idvivienda").val(data.idvivienda);
        $("#idDetEdit").val(data.iddetovitrampasvivienda);
        $("#finstala").val(data.fechainstalacion);
        var lnt=Number(data.lat)||0;
        var lng=Number(data.lng)||0;
        initMap(lnt,lng);
        getHistoricoCodOvi(data.idovitrampa);
        getDataSelRed(data.idipress);
        iniAutoUbicacionOvitrampaVivienda();
    }

    function setBtnSave2() {
        var ht='<a href="javascript:void(0)" onclick="btnSave(2)" type="button"  id="btnSave2" class=" btn btn-primary" >';
        ht+='Guardar y agregar otro';
        ht+='</a>';
        return ht;
    }

    function eliminar(id) {
        if(confirm('¿Esta seguro de eliminar este registro?')){
            var idd=parseInt(id);
            $.post(url_base+'ovitrampas/deleteData','id='+idd,function (data) {
                if(data.status == 'ok'){
                    alert_success('Se realizo correctamente');
                    refrescar();
                }else{
                    alert_error('Error');
                }
            },'json');
        }
    }

    $(document).on("click","#btnSaveVivienda",function () {
        var bol=true;
        bol= bol&& $("#distrito").required();
        bol= bol&& $("#barrio").required();
        bol= bol&& $("#sector").required();
        bol= bol&& $("#manzana").required();
        bol= bol&& $("#dir").required();
        bol= bol&& $("#dirnro").required();

        if($("#iddistrito").val() ==""){
            bol= bol&& false;
            alert_error("Seleccione distrito");
        }

        if(!bol){ return 0;}

        var formReg=$("#formReg").serialize();
        $.post(url_base+"viviendas/setDataFormVivivenda",formReg,function (data) {
           if(data.status =="ok"){
               refrescar();
               alert_success("Se realizó correctamente");
               close_modal("modal_id");
           }
        },'json');
    });


    function iniAutoCompleteDistrito() {
        $("#distrito").autocomplete({
            source: "<?= base_url(); ?>ovitrampas/getDepProvByDist",
            minLength: 1,// tamaño de cadena
            delay: 250,
            search: function( event, ui ) {
                $("#region").val("");
                $("#provincia").val("");
                $("#iddistrito").val("0");
                $("#idubigeo").val("0");
            },
            select: function( event, ui ) {
                    var dt=ui.item;
                    $("#region").val(dt.DESC_DPTO);
                    $("#provincia").val(dt.DESC_PROV);
                    $("#iddistrito").val(dt.COD_DIST);
                    $("#idubigeo").val(dt.UBIGEO);
                    $("#distrito").attr("readonly","readonly");
                    $("#dir").removeAttr("readonly");
                    //$("#lat").removeAttr("readonly","readonly");
                    //$("#lng").removeAttr("readonly","readonly");
                    //$("#codOvi").removeAttr("readonly","readonly");
                getDataSelRed(0);
            }
        });
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


        },'json');
    }


    function iniAutoIssetCodOvitrampa() {
        $("#codOvi").autocomplete({
            source: "<?= base_url(); ?>ovitrampas/getIssetOvitrampa",
            minLength: 1,// tamaño de cadena
            delay: 250,
            search:function(event, ui){
                $("#idCodOviExist").val("");
                $("#spanIfExistCodOvi").css("display","none");
                $("#divIfExistCodOvi").html("");
            },
            select: function( event, ui ) {
                var dt=ui.item;
                $("#idCodOviExist").val(dt.idovitrampa);
                $("#spanIfExistCodOvi").css("display","block");
                getHistoricoCodOvi(dt.idovitrampa)
                //$("#lat").removeAttr("readonly","readonly");
                //$("#lng").removeAttr("readonly","readonly");
                //$("#codOvi").removeAttr("readonly","readonly");

            }
        });
    }


    function iniAutoUbicacionOvitrampaVivienda() {
        $("#ubicacionovi").autocomplete({
            source: "<?= base_url(); ?>ovitrampas/getUbicacionOvitrampaInVivienda",
            minLength: 1,// tamaño de cadena
            delay: 250,
            search:function(event, ui){

            },
            select: function( event, ui ) {

            }
        });
    }

    function getHistoricoCodOvi(id) {
        var tmpIfExistCodOvi=_.template($("#tmpIfExistCodOvi").html());
        $.post(url_base+"ovitrampas/getDataO",{"id":id},function (data) {
            $("#divIfExistCodOvi").html(tmpIfExistCodOvi({data:data}));
        },'json');

    }
    function iniAutoCompleteDirViviendaByDistri() {
        $("#dir").autocomplete({
            source: function(request, response) {
                    $.ajax({
                        url: "<?= base_url(); ?>ovitrampas/getDirViviendaByDistri",
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
                $("#barrio").val("");
                $("#sector").val("");
                $("#manzana").val("");
                $("#lat").val("");
                $("#lng").val("");
                $("#ubicacionovi").val("");
            },
            select: function( event, ui ) {
                var dt=ui.item;
                $("#barrio").val(dt.idbarrio);
                $("#sector").val(dt.idsector);
                $("#manzana").val(dt.idmanzana);
                $("#lat").removeAttr("readonly","readonly");
                $("#lng").removeAttr("readonly","readonly");
                $("#lat").val(dt.lat);
                $("#lng").val(dt.lng);
                $("#codOvi").removeAttr("readonly","readonly");
                $("#idvivienda").val(dt.idvivienda);
                $("#ubicacionovi").removeAttr("readonly","readonly");
                var latv = parseFloat(dt.lat);
                var lonv = parseFloat(dt.lng);
                var myLatlng = new google.maps.LatLng(latv,lonv);
                marker.setPosition(myLatlng);
                bounds.extend(marker.position);
                map.fitBounds(bounds);
                var listener = google.maps.event.addListener(map, "idle", function() {
                    map.setZoom(18);
                    google.maps.event.removeListener(listener);
                });
            }
        });
    }

    $(document).on("click","#btnChangeDistri",function () {
        $("#distrito").removeAttr("readonly","readonly");
        $("#region").val("");
        $("#prov").val("");
        $("#iddistrito").val("");
        $("#dir").attr("readonly","readonly");
        $("#lat").attr("readonly","readonly");
        $("#lng").attr("readonly","readonly");
        $("#distrito").val("");
        $("#dir").val("");
        $("#lat").val("");
        $("#lng").val("");
        $("#barrio").val("");
        $("#sector").val("");
        $("#manzana").val("");
        $("#idvivienda").val("");
        $("#ubicacionovi").attr("readonly","readonly");

    });
    $(document).on("click","#btnChangeVivienda",function () {
        $("#dir").removeAttr("readonly","readonly");
        $("#idvivienda").val("");
        $("#barrio").val("");
        $("#sector").val("");
        $("#manzana").val("");
        $("#dir").val("");
        $("#lat").val("");
        $("#lng").val("");

        $("#ubicacionovi").val("");
    });

    $(document).on("click","#btnSaveOvi",function () {
        var codOvi=$("#codOvi");
        var finstala=$("#finstala");
        var iddistrito=$("#iddistrito");
        var idvivienda=$("#idvivienda");
        var lat=$("#lat");
        var lng=$("#lng");
        var form=$("#formReg").serialize();
        var bol=true;
        bol=bol&&codOvi.required();
        bol=bol&&finstala.required();
        if(iddistrito.val() ==""){
            bol=bol&&false;
            alert_error("Se requiere el distrito");

        }
        if(idvivienda.val() ==""){
            bol=bol&&false;
            alert_error("Se requiere una vivienda registrada");

        }
        bol=bol && $("#ubicacionovi").required();
        if(bol){
            var btn=$(this);
            $.post(url_base+"ovitrampas/setForm",form,function (data) {
                if(data.status == "ok"){
                    alert_success("Se realizó correctamente");
                    refrescar();
                    close_modal("modal_id");
                }else{
                    alert_error("Ocurrio algo inesperado");
                }
            },'json');
        }
    });
    var map,  marker,bounds;
    function initMap(latx,lngx) {
        var latDefault=-6.4858776;
        var lngDefault=-76.3658243;
        var isValidLat=validaLatLng(latx);
        var isValidLng=validaLatLng(lngx);
        if(isValidLat && (latx !=0) )latDefault=latx;
        if(isValidLng && (lngx !=0) )lngDefault=lngx;
        console.log(latDefault,lngDefault);
        bounds=new google.maps.LatLngBounds();
        var myLatlng = new google.maps.LatLng(latDefault,lngDefault);
        var mapOptions = {
            zoom: 15,
            center: myLatlng,
            styles: [
                {
                    featureType: 'poi',
                    stylers: [{ visibility: "off"}]
                }
            ]
        };
        map = new google.maps.Map(document.getElementById("map"), mapOptions);

        marker = new google.maps.Marker({
            position: myLatlng,
            title:"Hello World!",
            draggable:true
        });

        google.maps.event.addListener(marker, 'dragend', function(event)
        {
            var lat = event.latLng.lat();
            var lng = event.latLng.lng();
            $("#lat").val(lat);
            $("#lng").val(lng);
            console.log(lat,lng);
        });
        /*google.maps.event.addListener(marker, 'click', function(event)
        {
            var lat = event.latLng.lat();
            var lng = event.latLng.lng();
            $("#lat").val(lat);
            $("#lng").val(lng);
            console.log(lat,lng);
        });*/
        map.addListener('click', function(event) {
            var lat = event.latLng.lat();
            var lng = event.latLng.lng();
            var latlng= {lat:lat , lng:lng};
            marker.setPosition(latlng);
            $("#lat").val(lat);
            $("#lng").val(lng);
            console.log(lat,lng);
        });

        // To add the marker to the map, call setMap();
        marker.setMap(map);
    }

</script>
<script type="text/template" id="tmpFormRegVivienda">
<div class="col-lg-7">
    <form class="form-horizontal" id="formReg">
        <input type="hidden" name="isEdit" id="isEdit" value="">
        <input type="hidden" name="idEdit" id="idEdit" value="">
        <input type="hidden" name="idDetEdit" id="idDetEdit" value="">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="demo-is-inputsmall" style="font-size: 16px;" ><b>Codigo Ovitrampa:</b></label>
            <div class="col-sm-3">
                <input type="text" placeholder=""  class="form-control" id="codOvi" name="codOvi">
                <input type="hidden" placeholder=""  class="form-control" id="idCodOviExist" name="idCodOviExist">
                <small class="help-block" id="spanIfExistCodOvi" style="color:darkred;display: none;">
                    El código de esta ovitrampa ya se encuentra registrada en una vivienda.
                    <br>Si continua, se asignara la ovitrampa, a la ubicación y vivienda de este formulario*</small>
            </div>
            <label class="col-sm-2 control-label" for="demo-is-inputsmall" style="font-size: 12px;" ><b>F. Instalacion</b></label>
            <div class="col-sm-3">
                <input type="date" style="line-height: 12px;" placeholder=""  class="form-control" id="finstala" name="finstala">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="demo-is-inputsmall">Region</label>
            <div class="col-sm-7">
                <input type="text" readonly="readonly" class="form-control input-sm" id="region" VALUE="SAN MARTIN" >
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="demo-is-inputsmall">Provinicia</label>
            <div class="col-sm-7">
                <input type="text" readonly="readonly"   class="form-control input-sm" id="provincia" VALUE="SAN MARTIN">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Red Salud</label>
            <div class="col-sm-7" id="divRedSalud" >

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Distrito</label>
            <div class="col-sm-7">
                <input type="text"  class="form-control" id="distrito" name="distrito" style="display:inline-block;width: 80%" value="TARAPOTO" >
                <button id="btnChangeDistri" type="button" class="btn btn-sm btn-dark" style="display:inline-block;"><b>Cambiar</b></button>
                <input type="hidden" placeholder="" class="form-control input-sm" id="iddistrito" name="iddistrito" value="01">
                <input type="hidden" placeholder="" class="form-control input-sm" id="idubigeo" name="idubigeo" value="220901">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">IPRESS</label>
            <div class="col-sm-7" id="divIPRESS">
            </div>
        </div>
        <div class="form-group" style="margin-bottom: 0px;">
            <label class="col-sm-3 control-label" for="demo-is-inputsmall">Vivienda</label>
            <div class="col-sm-7">
                <input type="text" placeholder=""   style="display:inline-block;width: 80%" class="form-control input-sm" id="dir" name="dir">

                <input type="hidden" placeholder="" readonly="readonly" class="form-control input-sm" id="idvivienda" name="idvivienda">
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
            </div>
            <div class="col-sm-3">
                <div class="form-group" style="width: 95%;padding-left: 10px;">
                    <label class="control-label">Barrio</label>
                    <input type="text" readonly="readonly" class="form-control" id="barrio" name="barrio" >
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group" style="width: 95%" >
                    <label class="control-label">Sector</label>
                    <input type="text"   placeholder="" class="form-control input-sm" id="sector" name="sector" >
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group" style="width: 95%" >
                    <label class="control-label">Manzana</label>
                    <input type="text"  placeholder="" class="form-control input-sm" id="manzana" name="manzana" >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
            </div>
            <div class="col-sm-5">
                <div class="form-group" style="width: 95%;padding-left: 10px;">
                    <label class="control-label">Ubicación de la ovitrampa en vivienda</label>
                    <input type="text"  placeholder="Ejemplo: Patio, Jardin" class="form-control" id="ubicacionovi" name="ubicacionovi" >
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="demo-is-inputsmall">Lat</label>
            <div class="col-sm-7">
                <input type="text" placeholder=""    class="form-control input-sm" id="lat" name="lat">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="demo-is-inputsmall">Lng</label>
            <div class="col-sm-7">
                <input type="text" placeholder=""   class="form-control input-sm" id="lng" name="lng">
            </div>
        </div>

    </form>
  </div>
    <div class="col-lg-5"  >
        <h4> Geolocalización de la vivienda/ovitrampa</h4>
        <div class="col-lg-12" id="map" style="height: 400px" >

        </div>
    </div>
    <div class="row" id="divIfExistCodOvi">

    </div>

<div class="row">
    <div class="col-sm-9 col-sm-offset-3">
        <button class="btn btn-mint" type="button" id="btnSaveOvi">Guardar</button>
        <button class="btn btn-warning " data-dismiss="modal"   type="button">Cancelar</button>
    </div>
</div>

</script>

<script type="text/template" id="tmpIfExistCodOvi">
    <div class="col-sm-12 ">
        <h4>Histórico de ubicación de la ovitrampa</h4>
        <table style="font-size: 10px;" id="tableIfExistCodOvi" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th>Codigo</th>
                <th>Fecha<br>Instalación</th>
                <th>Ubigeo</th>
                <th>Dirección</th>
                <th>Ubicación <br>en vivienda</th>
                <th>Lat<br>(° Decimales) </th>
                <th>Lng<br>(° Decimales) </th>
                <th>Sector</th>
                <th>Mz</th>
                <th>Estado</th>
            </tr>
            </thead>
            <tbody id="tbodyIfExistCodOvi">
            <% _.each(data,function(i,k){ %>
            <tr>
                <td><%=(k+1) %></td>
                <td><%= i.codigoovitrampa %></td>
                <td>
                     <input style='width: 125px;border: 0;background-color: transparent;' type='date' disabled  value='<%= i.fechainstalacion %>'> ;
                 </td>
                <td><%= i.DESC_DPTO+" "+i.DESC_PROV+" "+i.DESC_DIST %></td>
                <td><%= i.direccion+" "+i.nro %></td>
                <td><%= i.ubicacionenvivienda %> </td>
                <td><%= i.lat %> </td>
                <td><%= i.lng %> </td>
                <td><%= i.idsector %> </td>
                <td><%= i.idmanzana %> </td>
                <td><% if(i.isactual == 1){ %>
                    <button type="button" class="btn btn-xs btn-success"><b>Activo/Actual</b></button>
                    <%  }else{   %>
                    <button type="button"  class="btn btn-xs btn-dark"><b>Histórico</b></button>
                    <%    }  %>
                </td>
            </tr>

            <%  }) %>
            </tbody>
        </table>
    </div>
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



