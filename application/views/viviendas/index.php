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
    .pac-container {
        z-index: 10000 !important;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Lista de viviendas </h3>
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
                        <th>Ubigeo</th>
                        <th>Dirección</th>
                        <th>Lat<br>(° Decimales) </th>
                        <th>Lng<br>(° Decimales) </th>
                        <th>Sector</th>
                        <th>Manza</th>
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
    <div class="modal-dialog modal-lg" style="width: 80%;" >
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" >
                <div class="row">
                    <div class="col-lg-6" id="divFormRegViviendas" > </div>
                    <div class="col-lg-6">
                     <div class="row">
                         <h4>Ubicar marcador de la dirección</h4>
                         <input id="pac-input" class="controls" type="text" style="z-index: 1; position: absolute; left: 117px;  " placeholder="Buscar...">
                         <div class="col-lg-12"  id="mapa" style="height: 400px" ></div>
                     </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link href="<?=base_url()?>assets/scripts/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<?php echo $_css?>
<?php echo $_js?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzHReXbQ145y0mfQzSdTnoiav9HOuWTaU&libraries=visualization,places,geometry"    ></script>
<script src="<?=base_url()?>assets/scripts/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
    var titles={'addtitle':"Nueva Vivienda" ,'updatetitle':"Editar Vivienda"};
    var isEdit=null,idEdit=null;
    var map,heatmap,marker;
    var iniLatLng= {lat: -6.4914193, lng: -76.3596651};
    var OptionsMap={
        center:iniLatLng,
        mapTypeId:google.maps.MapTypeId.roadmap,
        Zoom: 15,
        //maxZoom:19,
        styles: [
            {
                featureType: 'poi',
                stylers: [{ visibility: "off"}]
            }
        ]
    };
    var service,bounds;
    function iniMaps(){
        map = new google.maps.Map(document.getElementById('mapa'),OptionsMap);
        bounds=new google.maps.LatLngBounds();

        /*var request = {
            query: 'Jiron Lima 734,Tarapoto,Perú',
            fields: ['name', 'geometry'],
        };

        service = new google.maps.places.PlacesService(map);

        service.findPlaceFromQuery(request, function(results, status) {
            if (status === google.maps.places.PlacesServiceStatus.OK) {
                console.log(results);
                for (var i = 0; i < results.length; i++) {
                    createMarker(results[i]);
                }
                map.setCenter(results[0].geometry.location);
            }
            console.log("1",results);
        }); */
         marker = new google.maps.Marker({
            position: iniLatLng,
            map: map,
            draggable:true,
            //label:{text: "Hola", color: "black",fontWeight:"bold",fontSize:"16px"},
            title: 'Hello World!'
        });
         /*
        heatmap = new google.maps.visualization.HeatmapLayer({
            //data:getP(),
            map: map,
            radius:7,
            opacity:0.5
        });*/

         var input = document.getElementById('pac-input');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.bindTo('bounds', map);
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            if(place.geometry != undefined){
                var latx=place.geometry.location.lat();
                var lngx=place.geometry.location.lng();
                map.setCenter({lat: latx, lng: lngx});
                marker.setPosition({lat: latx, lng: lngx});
                map.setZoom(15);
                $("#lat").val(latx);
                $("#lng").val(lngx);
            }
        });

        map.addListener('click', function(event) {
             var lat = event.latLng.lat();
             var lng = event.latLng.lng();
             //setInputsLatLng(lat,lng);
             var latlng= {lat:lat , lng:lng};
             marker.setPosition(latlng);
            $("#lat").val(lat);
            $("#lng").val(lng);
         });

      google.maps.event.addListener(marker, 'dragend', function(event)
         {
             var lat = event.latLng.lat();
             var lng = event.latLng.lng();
            // setInputsLatLng(lat,lng);
             var latlng= {lat:lat , lng:lng};
             $("#lat").val(lat);
             $("#lng").val(lng);

             // console.log(lat,lng,event);
         });

        //marker.setMap(map);
        // heatmap.setMap(map);
        //getPoints();
        // heatmap.setData(getP());
    }

    var rowSelection=null;
    $(document).ready(function () {
        iniDataTable();
        iniAutoCompleteDistrito();
    });

    function iniDataTable() {
          rowSelection = $('#tabla_grid').DataTable({
            "ajax": url_base+'viviendas/getDataTable',
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
                        var name = full.direccion+" "+full.nro;
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
                        var id = full.idvivienda;
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
        getDataSelRed(0);
        iniMaps();
    });

    function iniDatatModal() {
        var tmpFormRegVivienda=_.template($("#tmpFormRegVivienda").html());
        $("#divFormRegViviendas").html(tmpFormRegVivienda);
        iniAutoCompleteDistrito();
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

        //console.log(data);
        open_modal('modal_id');
        iniDatatModal();
        iniMaps();
        var pt = new google.maps.LatLng(Number(data.lat),  Number(data.lng));
        marker.setPosition({lat: Number(data.lat), lng: Number(data.lng)});
        map.setCenter(pt);
        map.setZoom(18);
       // map.setZoom(your desired zoom);
        /*bounds.extend(marker.position);
        //cordPath.push(latLong);
        map.fitBounds(bounds);
        map.setZoom(8);*/

        $("#isEdit").val(1);
        $("#idEdit").val(id);
        $("#region").val(data.DESC_DPTO);
        $("#prov").val(data.DESC_PROV);
        $("#distrito").val(data.DESC_DIST);
        $("#iddistrito").val(data.iddistrito);
        $("#idubigeo").val(data.ubigeo);
        $("#barrio").val(data.idbarrio);
        $("#sector").val(data.idsector);
        $("#manzana").val(data.idmanzana);
        $("#dir").val(data.direccion);
        $("#dirnro").val(data.nro);
        $("#lat").val(data.lat);
        $("#lng").val(data.lng);
        getDataSelRed(data.idipress);

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
            $.post(url_base+'viviendas/deleteData','id='+idd,function (data) {
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
       // bol= bol&& $("#barrio").required();
        bol=bol & $("#selEstablecimientoSalud").requiredSelect();
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

    $(document).on("keyup","#dir,#dirnro",function () {
        var distritox=$("#distrito").val();
        var dirx=$("#dir").val();
        var dirnro=$("#dirnro").val();
        var p="Perú";
        var tt=dirx+" "+dirnro+" "+distritox+", "+p;

        $("#smallDirCompletoX").html(tt);
        $("#pac-input").val(tt);
    });

    function iniAutoCompleteDistrito() {
        $("#distrito").autocomplete({
            source: "<?= base_url(); ?>ubigeo/getDepProvByDist",
            minLength: 1,// tamaño de cadena
            delay: 200,
            search:function( event, ui ){
                $("#departa").val("");
                $("#prov").val("");
                $("#iddistrito").val(0);
                $("#idubigeo").val(0);
                $("#divRedSalud").html("");
                $("#divIPRESS").html("");
            },
            select: function( event, ui ) {
                    var dt=ui.item;
                    $("#region").val(dt.DESC_DPTO);
                    $("#prov").val(dt.DESC_PROV);
                    $("#iddistrito").val(dt.COD_DIST);
                    $("#idubigeo").val(dt.UBIGEO);
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

<script type="text/template" id="tmpFormRegVivienda">
    <form class="form-horizontal" id="formReg">
        <input type="hidden" name="isEdit" id="isEdit" value="0">
        <input type="hidden" name="idEdit" id="idEdit" value="0">

        <div class="form-group">
            <label class="col-sm-2 control-label" for="demo-is-inputsmall">Region</label>
            <div class="col-sm-8" >
                <input type="text" readonly="readonly" class="form-control input-sm" id="region" name="region" value="SAN MARTIN">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="demo-is-inputsmall">Provinicia</label>
            <div class="col-sm-8">
                <input type="text" readonly="readonly"   class="form-control input-sm" id="prov" value="SAN MARTIN">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"  >Distrito</label>
            <div class="col-sm-8">
                <input type="text"  class="form-control" id="distrito" name="distrito" value="TARAPOTO" >
                <input type="hidden" placeholder="" class="form-control input-sm" id="iddistrito" name="iddistrito" value="01">
                <input type="hidden" placeholder="" class="form-control input-sm" id="idubigeo" name="idubigeo" value="220901">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"  >Red Salud</label>
            <div class="col-sm-8" id="divRedSalud">
                ...
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"  >IPRESS</label>
            <div class="col-sm-8" id="divIPRESS" >
                 ...
            </div>
        </div>
        <div class="form-group" style="display: none;">
            <label class="col-sm-2 control-label"  >Barrio</label>
            <div class="col-sm-8">
                <input type="text"  class="form-control" id="barrio" name="barrio" >
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="demo-is-inputsmall">Sector</label>
            <div class="col-sm-8">
                <input type="text" placeholder="" class="form-control input-sm" id="sector" name="sector">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="demo-is-inputsmall">Manzana</label>
            <div class="col-sm-8">
                <input type="text" placeholder="" class="form-control input-sm" id="manzana" name="manzana">
            </div>
        </div>
        <div class="form-group" style="margin-bottom: 8px;">
            <label class="col-sm-2 control-label" for="demo-is-inputsmall">Dirección</label>
            <div class="col-sm-5">
                <input type="text" placeholder="" class="form-control input-sm" id="dir" name="dir">
                <small style="margin-bottom: 0px"  class="help-block " id="smallDirCompletoX" ></small>
            </div>
            <label class="col-sm-1 control-label" for="demo-is-inputsmall">Nro</label>
            <div class="col-sm-2">
                <input type="text" placeholder="" class="form-control input-sm" id="dirnro" name="dirnro">
                <input type="hidden" id="dircompleto" name="dircompleto"   >
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="demo-is-inputsmall">Lat</label>
            <div class="col-sm-6">
                <input type="text" placeholder="" class="form-control input-sm" id="lat" name="lat">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="demo-is-inputsmall">Lng</label>
            <div class="col-sm-6">
                <input type="text" placeholder="" class="form-control input-sm" id="lng" name="lng">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-9 col-sm-offset-3">
                <button class="btn btn-mint" type="button" id="btnSaveVivienda">Guardar</button>
                <button class="btn btn-warning" type="reset">Cancelar</button>
            </div>
        </div>
    </form>
</script>




