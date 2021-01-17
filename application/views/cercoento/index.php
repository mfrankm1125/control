<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">*</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 5px;" >
                <div class="row" style="margin-bottom: 0px;font-size: 11px">
                    <div class="col-lg-12"  >
                        <div class="col-sm-1">
                            <div class="form-group" style="margin-bottom: 4px;" >
                                <label class="control-label">Año</label>
                                <input style="font-size: 10px;padding-left: 0px;padding-right: 0px;" type="text" id="anio" name="anio"   value="<?=date("Y");?>"  class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group" style="margin-bottom: 4px;" >
                                <label class="control-label">Region</label>
                                <input style="font-size: 10px;padding-left: 0px;padding-right: 0px;" type="text" id="region" name="region" readonly="readonly"  value="SAN MARTIN"  class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group" style="margin-bottom: 4px;" >
                                <label class="control-label">Provincia</label>
                                <input  style="font-size: 10px;padding-left: 0px;padding-right: 0px;" type="text" id="provincia" name="provincia" readonly="readonly" value="SAN MARTIN"  class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group" style="margin-bottom: 4px;" >
                                <label class="control-label">Red Salud</label>
                                <div id="divRedSalud">

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group" style="margin-bottom: 4px;"  >
                                <label class="control-label">Distrito</label>
                                <input type="text"  style="font-size: 10px ;padding-left: 0px;padding-right: 0px;" id="distrito" name="distrito"  class="form-control" value="TARAPOTO">
                                <input type="hidden" id="idubigeo" name="idubigeo" value="220901">
                                <input type="hidden"  id="iddistrito" name="iddistrito"  value="01">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group" style="margin-bottom: 4px;" >
                                <label class="control-label">IPRESS </label>
                                <div id="divIPRESS">

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="btn-group">
                                <label class="control-label" style="visibility: hidden;" id=" " >__________</label>
                                <button class="btn btn-default btn-active-pink "  type="button" id="verMapa" >
                                    Ver
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row" style="margin-bottom: 0px;font-size: 11px">
                    <div class="col-lg-12"  >
                        <form id="formX">

                        <div class="col-sm-1">
                            <div class="form-group" style="margin-bottom: 4px;" >
                                <label class="control-label">Ovitrampa</label>
                                <input style="font-size: 10px;padding-left: 0px;padding-right: 0px;" type="text" id="ovix" name="ovix"   class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group" style="margin-bottom: 4px;" >
                                <label class="control-label">Vivienda</label>
                                <input style="font-size: 10px;padding-left: 0px;padding-right: 0px;" type="text" id="vivienda" name="vivienda"   class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group" style="margin-bottom: 4px;" >
                                <label class="control-label">Lat</label>
                                <input  style="font-size: 10px;padding-left: 0px;padding-right: 0px;" type="text" id="lat" name="lat"   class="form-control">
                            </div>
                        </div>
                            <div class="col-sm-2">
                                <div class="form-group" style="margin-bottom: 4px;" >
                                    <label class="control-label">Lng</label>
                                    <input  style="font-size: 10px;padding-left: 0px;padding-right: 0px;" type="text" id="lng" name="lng"    class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group" style="margin-bottom: 4px;" >
                                    <label class="control-label" style="visibility: hidden;">_____________________________________________</label>
                                    <button type="button" class="btn btn-mint" id="btnNewMarker" style="display: inline-block" >Nueva ovitrampa</button>
                                    <button type="button" class="btn btn-mint" id="savePunto" style="display: none">Guardar</button>
                                    <button type="button" class="btn btn-mint" id="deletePunto" style="display: none">Eliminar</button>

                                </div>
                            </div>
                            <input  type="hidden" id="isEdit" name="isEdit">
                            <input type="hidden" id="idEdit" name="idEdit" >
                      </form>
                    </div>
                </div>

                <div class="row">

                    <div class="col-lg-12">
                        <button type="button" class="btn btn-success" id="btnSaveMapa">Guardar Mapa</button>
                        <span>* radio de 100 metros</span>
                    </div>
                    <div class="col-lg-12" id="mapa" style="height: 500px;">

                    </div>
                </div>

            <!--===================================================-->
            <!--End Data Table-->
        </div>
    </div>
</div>


<!-- AIzaSyAUViXncAh7wtzgHVEUtLUbmldisa9KF1M
 AIzaSyDwUEEScclciTSQo5SART1F6KTxIBrWoPU-->

<link href="<?=base_url()?>assets/scripts/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<?php echo $_css?>
<?php echo $_js?>

<script src="<?=base_url()?>assets/scripts/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzHReXbQ145y0mfQzSdTnoiav9HOuWTaU&libraries=visualization,places,geometry"  ></script>
<script src="<?=base_url()?>assets/scripts/heatmap/heatmap.js" ></script>
<script src="<?=base_url()?>assets/scripts/heatmap/gmap-heatmap.js" ></script>

<script type="text/javascript">
    var titles={'addtitle':"Nuevo tipo de animal" ,'updatetitle':"Editar tipo de animal"};
    var isEdit=null,idEdit=null;
    var bounds ;
    var listMarcadores=[];
    var iniLatLng= {lat: -6.4914193, lng: -76.3596651};
    var p1={lat:-6.492144352034575,lng:-76.36369990768162};
    var p2={lat:-6.494617474305945,lng:-76.36103915633885};
    var p3={lat:-6.505831652049439,lng:-76.3655881828281};
    var p4={lat:-6.500544390280335,lng:-76.37189673843113};

    var px={lat:-6.498241855678474,lng:-76.36606025161473};
    var map,heatmapx,marker,marca;

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


    $(document).on("click","#verMapa",function () {
        iniPointsVals();
    });

    function iniPointsVals(){
        $.post(url_base+"cercoento/queryResult",function (data) {
            setPointsOvi(data);

        },'json');
    }
    $(document).on("click","#saveMarcador",function () {

        console.log(dataHeatMapx);
        /*$.post(url_base+"cercoento/queryResult",function (data) {
            setPointsOvi(data);
        },'json');*/
    });

    var dataHeatMapx=[];
    function setPointsOvi(data) {
        dataHeatMapx=[];
        $.each(data,function (k,i) {
            var lat=parseFloat(i.lat);
            var lng=parseFloat(i.lng );
            var latLong = new google.maps.LatLng(lat,lng);

            var marca = new google.maps.Marker({
                position: latLong,
                map: map,
                draggable:true,
                label:{text: ""+i.codovi, color: "black",fontWeight:"bold",fontSize:"10px"},
                title: 'Hello World!',
                icon:url_base+"assets/images/markers/ired.png",
                codovi:i.codovi,
                idmark:i.iddetcercoento,
                vivienda:i.vivienda
            });

            listMarcadores.push(marca);
            google.maps.event.addListener(marca, 'dragend', function(event) {
                var lat = event.latLng.lat();
                var lng = event.latLng.lng();
                var valMark=marca.codovi ;
                console.log(valMark);
                if(dataHeatMapx.length > 0){
                    var dtTemp=[];
                    $.each(dataHeatMapx,function (k,i) {
                        if(i.codovi == valMark){
                            dtTemp.push({lat:lat,lng:lng,count:i.count,codovi:i.codovi,edit:1,iddetcercoento:i.iddetcercoento});
                        }else{
                            dtTemp.push({lat:i.lat,lng:i.lng,count:i.count,codovi:i.codovi,iddetcercoento:i.iddetcercoento});
                        }
                    });

                    dataHeatMapx=dtTemp;

                    var DataX = {
                        max: 100,
                        min: 0,
                        data: dtTemp
                    };
                    console.log(DataX);
                    heatmapx.setData(DataX);
                }
            });

            google.maps.event.addListener(marca, 'click', function(event){
                var lat = event.latLng.lat();
                var lng = event.latLng.lng();
                $("#isEdit").val(1);
                $("#idEdit").val(marca.idmark);
                $("#lat").val(lat);
                $("#lng").val(lng);
                $("#ovix").val(marca.codovi);
                $("#vivienda").val(marca.vivienda);
                $("#btnNewMarker").css("display","none");
                $("#savePunto").css("display","inline-block");
                $("#deletePunto").css("display","inline-block");
                selectMarker=marca;
                console.log(marca);
            });

            dataHeatMapx.push({lat:lat,lng:lng,count:1,codovi:i.codovi,iddetcercoento:i.iddetcercoento});
            bounds.extend(marca.position);
            //cordPath.push(latLong);
            marca.setMap(map);

        });

        map.fitBounds(bounds);
        var testData = {
            max: 100,
            min: 0,
            data: dataHeatMapx
        };

        heatmapx.setData(testData);
    }


    var dtHeat=[];
    $(document).on("click","#btnNewMarker",function () {
        $(this).css("display","none");
        $("#savePunto").css("display","inline-block");
        $("#deletePunto").css("display","none");
        if(marker){
           return 0;
        }
        $("#lat").val(iniLatLng.lat);
        $("#lng").val(iniLatLng.lng);

        marker = new google.maps.Marker({
            position: iniLatLng,
            map: map,
            draggable:true,
            label:{text: "N", color: "black",fontWeight:"bold",fontSize:"10px"},
            title: 'Hello World!',
            icon:url_base+"assets/images/markers/ineutro.png",
            codovi:"N"
        });
        google.maps.event.addListener(marker, 'dragend', function(event) {

            var lat = event.latLng.lat();
            var lng = event.latLng.lng();

            $("#lat").val(lat);
            $("#lng").val(lng);
            var valMark=marker.codovi ;
            console.log(valMark);
            if(dataHeatMapx.length > 0){
                var dtTemp=[];
                $.each(dataHeatMapx,function (k,i) {
                    if(i.codovi == valMark){
                        dtTemp.push({lat:lat,lng:lng,count:i.count,codovi:i.codovi,iddetcercoento:i.iddetcercoento});
                    }else{
                        dtTemp.push({lat:i.lat,lng:i.lng,count:i.count,codovi:i.codovi,iddetcercoento:i.iddetcercoento});
                    }
                });

               dataHeatMapx=dtTemp;

                var DataX = {
                    max: 100,
                    min: 0,
                    data: dtTemp
                };
                console.log(DataX);
                heatmapx.setData(DataX);
            }
        });
         google.maps.event.addListener(marker, 'click', function(event){
            var lat = event.latLng.lat();
            var lng = event.latLng.lng();
            console.log(lat,lng);
            $("#lat").val(lat);
            $("#lng").val(lng);
        });

        bounds.extend(marker.position);
        //cordPath.push(latLong);
        marker.setMap(map);
       // map.fitBounds(bounds);
        var listener = google.maps.event.addListener(map, "idle", function() {
            map.setZoom(18);
            google.maps.event.removeListener(listener);
        });

        dataHeatMapx.push({lat:iniLatLng.lat,lng:iniLatLng.lng,count:1,codovi:"N",iddetcercoento:0});
        var Data2 = {
            max: 100,
            min: 0,
            data: dataHeatMapx
        };
        console.log(Data2);
        heatmapx.setData(Data2);

    });










 ///////////////////////////////////////////////////////////////////////////////////////
    $(document).on("ready",function () {
         iniMaps();
        iniAutoCompleteDistrito();
        getDataSelRed(0);
       // initialize();
    });

     function iniMaps(){
         if(!map){
             map = new google.maps.Map(document.getElementById('mapa'),OptionsMap);
             bounds=new google.maps.LatLngBounds();
         }
         heatmapx = new HeatmapOverlay(map,{
                 // radius should be small ONLY if scaleRadius is true (or small radius is intended)
                 radius: 0.00065,//100meters
                 minOpacity: .2,
                 maxOpacity: .5,
                 // scales the radius based on map zoom
                 scaleRadius: true,
                 // if set to false the heatmap uses the global maximum for colorization
                 // if activated: uses the data maximum within the current map boundaries
                 //   (there will always be a red spot with useLocalExtremas true)
                 "useLocalExtrema": true,
                 // which field name in your data represents the latitude - default "lat"
                 latField: 'lat',
                 // which field name in your data represents the longitude - default "lng"
                 lngField: 'lng',
                 // which field name in your data represents the data value - default "value"
                 valueField: 'count',
                 gradient: {
                     '0': '#2c83b9',
                     '.3': '#33a02b',
                     '.6': '#f3ea0f',
                     '.75': '#f38b2a',
                     '1': '#d7191b'
                 }
             }
         );
         /*marker = new google.maps.Marker({
             position: iniLatLng,
             map: map,
             draggable:true,
             label:{text: "Hola", color: "black",fontWeight:"bold",fontSize:"16px"},
             title: 'Hello World!'
         });
         heatmap = new google.maps.visualization.HeatmapLayer({
             //data:getP(),
             map: map,
             radius:7,
             opacity:0.5
         });*/

         /*var input = document.getElementById('pac-input');
         var autocomplete = new google.maps.places.Autocomplete(input);
         autocomplete.bindTo('bounds', map);
         autocomplete.addListener('place_changed', function() {
             var place = autocomplete.getPlace();
             var latx=place.geometry.location.lat();
             var lngx=place.geometry.location.lng();
             map.setCenter({lat: latx, lng: lngx});
             marker.setPosition({lat: latx, lng: lngx});
             map.setZoom(16);
         });*/

        map.addListener('click', function(event) {
            if(!marker){return 0;}
             var lat = event.latLng.lat();
             var lng = event.latLng.lng();
             //setInputsLatLng(lat,lng);
            $("#lat").val(lat);
            $("#lng").val(lng);
             var latlng= {lat:lat , lng:lng};

             marker.setPosition(latlng);
            var valMark=marker.codovi ;
            console.log(valMark);
            if(dataHeatMapx.length > 0){
                var dtTemp=[];
                $.each(dataHeatMapx,function (k,i) {
                    if(i.codovi == valMark){
                        dtTemp.push({lat:lat,lng:lng,count:i.count,codovi:i.codovi,edit:1});
                    }else{
                        dtTemp.push({lat:i.lat,lng:i.lng,count:i.count,codovi:i.codovi});
                    }
                });

                dataHeatMapx=dtTemp;

                var DataX = {
                    max: 100,
                    min: 0,
                    data: dtTemp
                };
                console.log(DataX);
                heatmapx.setData(DataX);
            }
         });

        /* google.maps.event.addListener(marker, 'dragend', function(event)
         {
             var lat = event.latLng.lat();
             var lng = event.latLng.lng();
             setInputsLatLng(lat,lng);
             var latlng= {lat:lat , lng:lng};
             var geocoder = new google.maps.Geocoder;

             geocoder.geocode({'location': latlng}, function(results, status) {
                 if (status === 'OK') {
                     if (results[1]) {
                         console.log(results[1]);
                     } else {
                         window.alert('No results found');
                     }
                 } else {
                     window.alert('Geocoder failed due to: ' + status);
                 }
             });
             // console.log(lat,lng,event);
         });*/

         //marker.setMap(map);
         // heatmap.setMap(map);
         //getPoints();
         // heatmap.setData(getP());
     }

    function deleteMarkers() {
      for (var i = 0; i < listMarcadores.length; i++) {
          listMarcadores[i].setMap(null);
      }
       listMarcadores = [];
    }
    var infowindow;
    var activeInfoWindow;
    var  heatmapx;
    var selectMarker=null;
     function setPointsInMap(data,tiporeport) {
         iniMaps();
         var cordPath=[];
         var ht="";
         var novipositivas=0;
         var noviexaminadas=0;
         var IPO=0;
         var IDH=0;
         var sumNHuevos=0;
         var dtHeat=[];
         deleteMarkers();
         var btotalNeutro=0;
         var btotalBajo=0;
         var btotalMedio=0;
         var btotalAlto=0;
         var btotalMuyAlto=0;
         var btotalVC=0;
         var btotalMO=0;

         if( tiporeport =="calor" ){

            // console.log(heatmapx);

             heatmapx = new HeatmapOverlay(map,
             {
                 // radius should be small ONLY if scaleRadius is true (or small radius is intended)
                 radius: 0.00065,//100meters
                 minOpacity: .2,
                 maxOpacity: .5,
                 // scales the radius based on map zoom
                 scaleRadius: true,
                 // if set to false the heatmap uses the global maximum for colorization
                 // if activated: uses the data maximum within the current map boundaries
                 //   (there will always be a red spot with useLocalExtremas true)
                 "useLocalExtrema": true,
                 // which field name in your data represents the latitude - default "lat"
                 latField: 'lat',
                 // which field name in your data represents the longitude - default "lng"
                 lngField: 'lng',
                 // which field name in your data represents the data value - default "value"
                 valueField: 'count',
                 gradient: {
                     '0': '#2c83b9',
                     '.3': '#33a02b',
                     '.6': '#f3ea0f',
                     '.75': '#f38b2a',
                     '1': '#d7191b'
                 }
               }
            );
         }

         $.each(data,function (k,v) {

             var lat=parseFloat(v.lat);
             var lng=parseFloat(v.lng );
             var nhuevos=Number(v.nrohuevosovitrampa);

             var count=Number(v.count);
             // console.log(lat,lng);

             var latLong = new google.maps.LatLng(lat,lng);
             if(tiporeport =="markers"){
                 var r=setColorKP(nhuevos);
                 var marca=new google.maps.Marker({// props del marcador
                     nhuevos:v.nrohuevosovitrampa,
                     codovi:v.codigoovitrampa,
                     draggable:true,
                     position:latLong,
                     label:{text:nhuevos+"", color: "black",fontWeight:"bold",fontSize:"16px"},
                     icon:url_base+r.urlicon
                 });
             }else{
                 var r=setColorCountMark(count);
                 var marca=new google.maps.Marker({// props del marcador
                     nhuevos:v.nrohuevosovitrampa,
                     codovi:v.codigoovitrampa,
                     position:latLong,
                     draggable:true,
                     //label:{text:nhuevos+"", color: "black",fontWeight:"bold",fontSize:"16px"},
                     icon:url_base+r.urlicon
                 });

                 dtHeat.push({lat:lat,lng:lng,count:count,codovi:v.codigoovitrampa});
             }

             listMarcadores.push(marca);
             google.maps.event.addListener(marca,'click',function(){
                 if (infowindow) {
                     infowindow.close();
                 }
                 var ht="<p style='margin-bottom: 3px;'><b>Ovitrampa:</b> "+marca.codovi+"</p>";
                 ht=ht+"<p style='margin-bottom: 3px;' ><b>Nro huevos:</b> "+marca.nhuevos+"</p>";
                   infowindow = new google.maps.InfoWindow({
                     content: ht,
                     maxWidth: 200
                 });
                 infowindow.open(map,marca);
             });
             /* google.maps.event.addListener(marca, 'dragend', function(event)
              {
                   var lat = event.latLng.lat();
                   var lng = event.latLng.lng();
                  var codovi = marca.codovi;
                   //  var latlng= {lat:lat , lng:lng};
                     console.log(heatmapx,codovi);
                     var sdsdn=[];
                      $.each(dtHeat,function (k,i) {
                          if(codovi == i.codovi){
                              sdsdn.push({lat:lat,lng:lng,count:3,codovi:codovi});
                          }else{
                              sdsdn.push({lat:i.lat,lng:i.lng,count:i.count,codovi:i.codovi});
                          }

                      });

                      var testData = {
                          max: 100,
                          min: 0,
                          data:sdsdn
                      };
                      console.log(sdsdn);
                    heatmapx.setData(testData);

               });*/



             bounds.extend(marca.position);
             //cordPath.push(latLong);
             marca.setMap(map);
             map.fitBounds(bounds);
             ht=ht+"<tr>" +
                 "<td>"+(k+1)+"</td>" +
                 "<td>"+v.codigoovitrampa+"</td>" +
                 "<td>"+v.ubigeo+"</td>" +
                 "<td>"+v.direccion+" "+v.nro+  "</td>" +
                 "<td>"+v.sectorovitrampa+"</td>" +
                 "<td>"+v.mzovitrampa+"</td>" +
                 "<td>"+v.nrohuevosovitrampa+"</td>" +
                 "<td><input style='background-color:transparent;width: 140px;padding:0px;height: 15px;border: 0px' readonly='readonly' class='form-control' type='date' value='"+v.fechainspeccionovitrampa+"'></td>";
             if(v.estadoviviendainspeccion == 1){
                 sumNHuevos=sumNHuevos+nhuevos;
                 if(nhuevos == 0){
                     btotalNeutro++;
                 }else if(nhuevos > 0 && nhuevos <= 60 ){
                     btotalBajo++;
                 }else if(nhuevos > 60 && nhuevos <= 120 ){
                     btotalMedio++;
                 }else if(nhuevos > 120 && nhuevos <= 150 ){
                     btotalAlto++;
                 }else if(nhuevos > 150 ){
                     btotalMuyAlto++;
                 }

                 if(nhuevos > 0 ){
                     novipositivas++;
                 }
                 noviexaminadas++;
                 ht+="<td><i style='font-size: 18px;color:"+r.color+" ' class='fa fa-circle'></i> "+r.label+"  </td>";
             }else if(v.estadoviviendainspeccion == 2){
                 ht+="<td> <b title='Vivienda Cerrada'> V.C</b></td>";
                   btotalVC++;
             }else if(v.estadoviviendainspeccion == 5){
                 ht+="<td '> <b title='Ovitrampa Manipulada' >O.M</b></td>";
                   btotalMO++;
             }
             ht=ht+"</tr>";
         });

         if( tiporeport =="calor" ){

             var testData = {
                 max: 100,
                 min: 0,
                 data: dtHeat
             };
             heatmapx.setData(testData);
         }
        // console.log(novipositivas);
         IPO=(novipositivas/noviexaminadas)*100;
          var ripo=setLabelsByIPO(IPO);
         $("#idIPO").html(IPO.toFixed(2));
         $("#idIPONivel").html(ripo.nivel);
         $("#idIPOAccion").html(ripo.accion);
        $("#tbodyDetailOvitrampas").html(ht);

         $("#bTotalNeutro").html(parseFloat(btotalNeutro/noviexaminadas*100).toFixed(2));
         $("#bTotalBajo").html(parseFloat(btotalBajo/noviexaminadas*100).toFixed(2));
         $("#bTotalMedio").html(parseFloat(btotalMedio/noviexaminadas*100).toFixed(2));
         $("#bTotalAlto").html(parseFloat(btotalAlto/noviexaminadas*100).toFixed(2));
         $("#bTotalMalto").html(parseFloat(btotalMuyAlto/noviexaminadas*100).toFixed(2));

         $("#bTotalVI").html(noviexaminadas);
         $("#bTotalVC").html(btotalVC);
         $("#bTotalOM").html(btotalMO);
         $("#idIDH").html((sumNHuevos/novipositivas).toFixed(2));
      //  console.log(listMarcadores);
     }


    function iniAutoCompleteDistrito(){
        $("#distrito").autocomplete({
            source: "<?= base_url(); ?>inspecciones/getDataDepProvbyDistri",
            minLength: 1,// tamaño de cadena
            delay: 250,
            search: function( event, ui ) {
                $("#iddistrito").val("0");
                $("#divRedSalud").html("...");
                $("#divIPRESS").html("...");
                $("#sector").val("");
                $("#idubigeo").val("0");
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
        $.post(url_base+"Insovitrampas/getRedSalud",{"region":region},function (data) {
            //console.log(data);
            $("#divRedSalud").html(tmpSelRedSalud({data:data,region:region}));
            $("#divIPRESS").html("Cargando...");
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
        $.post(url_base+"Insovitrampas/getEstablecimientoSalud",{"coddisa":coddisa,"codred":codred,"iddistrito":iddistrito},function (data) {
            $("#divIPRESS").html(tmpSelEstablecimientoSalud({data:data}));
            $("#selEstablecimientoSalud").val(idestablecimiento);
            $("#selEstablecimientoSalud").chosen({width:'100%'});
        },'json');
    }

     $(document).on("click","#savePunto",function () {
        var form=$("#formX");
        var anio=$("#anio").val();
         var selEstablecimientoSalud=$("#selEstablecimientoSalud").val();
        var btn=$(this);

        var ovix=$("#ovix") ;
         var vivienda=$("#vivienda") ;
         var lat=$("#lat") ;
         var lng=$("#lng") ;
         var bol=true;
         bol=bol&&ovix.required();
         bol=bol&&vivienda.required();
         bol=bol&&lat.required();
         bol=bol&&lng.required();
        if(!bol){return 0;}

        btn.button("loading");
        $.post(url_base+"Cercoento/setMarker",form.serialize()+"&anio="+anio+"&ipress="+selEstablecimientoSalud,function (data) {
            if(data.status=="ok"){
                alert_success("Se registro la ubicación de la ovitrampa");
                setPointsOvi(data.data);
                if(marker){
                    marker.setMap(null)
                }

            }else{
                alert_error();
            }
            form[0].reset();
            $("#btnNewMarker").css("display","inline-block");
            $("#savePunto").css("display","none");
            $("#deletePunto").css("display","none");

            btn.button("reset");
        },'json');
     });
     $(document).on("click","#deletePunto",function () {
         var id=$("#idEdit").val();
         console.log(selectMarker);
         var btn=$(this);
         if(!confirm("Desea eliminar?")){
             return 0;
         }
         btn.button("loading");
         $.post(url_base+"Cercoento/deleteData",{id:id},function (data) {
            if(data.status == "ok"){
                alert_success();
                deleteMarkers();
                iniPointsVals();
                $("#formX")[0].reset();
                $("#btnNewMarker").css("display","inline-block");
                $("#savePunto").css("display","none");
                $("#deletePunto").css("display","none");
            }else{
                alert_error()
            }
             btn.button("reset");
         },'json');

     });

    $(document).on("click","#btnSaveMapa",function () {
        var btn=$(this);
        if(dataHeatMapx.length==0){return 0;}
        btn.button("loading");

        $.ajax({
            url: url_base+"cercoento/setMapa"
            ,   type: 'POST'
            ,   contentType: 'application/json'
            ,   data: JSON.stringify(dataHeatMapx) //stringify is important
            ,   success:function (res) {
                console.log(res);
            }
        });

       /* $.post(url_base+"cercoento/setMapa",dataHeatMapx,function (data) {
                console.log(data);
        },'json');*/
        console.log(dataHeatMapx);
        btn.button("reset");
    });


</script>

<script type="text/template" id="tmpSelEstablecimientoSalud">
    <select style="font-size: 10px;padding-left: 0px;padding-right: 0px;" class="form-control" data-placeholder="Seleccione" multiple id="selEstablecimientoSalud" name="selEstablecimientoSalud">

        <% _.each(data,function(i,k){ %>
        <option value="<%= i.COD_ESTAB %>"><%= i.DESC_ESTAB%></option>
        <%  })%>
    </select>
</script>

<script type="text/template" id="tmpSelRedSalud">
    <select style="font-size: 10px;padding-left: 0px;padding-right: 0px;" class="form-control"  name="selRedSalud" id="selRedSalud" >
        <option value="0">Seleccione...</option>
        <% _.each(data,function(i,k){
        var lb='';
        if(i.value == region){lb='selected="selected"'}
        %>
        <option value="<%= i.COD_DISA+'-'+i.COD_RED %>"  <%=lb%>  ><%= i.value%></option>
        <%  })%>
    </select>
</script>

