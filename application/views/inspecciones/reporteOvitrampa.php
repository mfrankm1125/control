<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">*</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 5px;" >
                <div class="row" style="margin-bottom: 0px;">
                    <div class="col-lg-12"  >
                        <div class="col-sm-2">
                            <div class="form-group" style="margin-bottom: 4px;" >
                                <label class="control-label">Region</label>
                                <input type="text" id="region" name="region" readonly="readonly"  value="SAN MARTIN"  class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group" style="margin-bottom: 4px;" >
                                <label class="control-label">Provincia</label>
                                <input type="text" id="provincia" name="provincia" readonly="readonly" value="SAN MARTIN"  class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group" style="margin-bottom: 4px;" >
                                <label class="control-label">Distrito</label>
                                <input type="text" id="distrito" name="distrito"  class="form-control" value="TARAPOTO">
                                <input type="hidden" id="idubigeo" name="idubigeo" value="220901">
                                <input type="hidden"  id="iddistrito" name="iddistrito"  value="01">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group" style="margin-bottom: 4px;" >
                                <label class="control-label">Red Salud</label>
                                <div id="divRedSalud">

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group" style="margin-bottom: 4px;" >
                                <label class="control-label">IPRESS </label>
                                <div id="divIPRESS">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12"  >
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Año</label>
                                <select id="anio" name="anio" class="form-control">
                                    <?php foreach($anio as $v){
                                        $lb="";
                                        if($v["aniosreg"] == date("Y")){
                                            $lb="selected='selected'";
                                        }
                                        ?>
                                        <option value="<?=$v["aniosreg"]?>" <?=$lb?> ><?=$v["aniosreg"]?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Semana</label>
                                <input type="text" class="form-control" id="semana" name="semana" >
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Sector</label>
                                <input type="text" class="form-control" id="sector" name="sector" >
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Estado</label>
                               <select id="selEstadoVisita" name="selEstadoVisita" class="form-control">
                                   <option value="0" selected="selected">Todo</option>
                                   <option value="1"  >Inspeccionadas</option>
                                   <option value="2">Cerradas</option>
                                   <option value="5">Manipulada</option>
                               </select>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="btn-group">
                                <label class="control-label" style="visibility: hidden;" id=" " >__________</label>
                                <button class="btn btn-default btn-active-pink dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button" aria-expanded="false">
                                    Ver   <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-left">
                                    <li><a href="javascript:void(0)" onclick="reportInMapFor('calor')" >Mapa Calor</a></li>
                                    <li><a href="javascript:void(0)"  onclick="reportInMapFor('markers')"  >Maca x Marcadores</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="btn-group">
                                <label class="control-label" style="visibility: hidden;" id=" " >__________</label>
                                <button id="btnGroupConsolidadoOvi" class="btn btn-default btn-active-pink dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button" aria-expanded="false">
                                    Consolidado <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-left">
                                    <li><a href="javascript:void(0)" onclick="reporteConsolidadoxTipo(this,'ovitrampa')" >Ovitrampas</a></li>
                                    <li><a href="javascript:void(0)"  onclick="reporteConsolidadoxTipo(this,'sector')"  >Sector</a></li>
                                    <li><a href="javascript:void(0)"  onclick="reporteConsolidadoxTipo(this,'ipress')"  >IPRESS</a></li>
                                    <li><a href="javascript:void(0)"  onclick="reporteConsolidadoxTipo(this,'distrito')"  >Distrito</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12" id="mapa" style="height: 500px;display:none">

                    </div>
                </div>
                <hr style="margin-top: 0px; margin-bottom: 10px;" >
                <div class="row">
                    <div class="col-lg-12"   >
                        <div class="col-lg-5">
                            <label class="control-label"><u style="font-weight: bold;"> Índice de positividad de ovitrampa:</u>   <label class="control-label" style="font-weight: bold;font-size: 15px;" id="idIPO"  ></label>  </label><br>
                            <label class="control-label"><u style="font-weight: bold;"> Índice de Densidad de Huevos:</u>   <label class="control-label" style="font-weight: bold;font-size: 15px;" id="idIDH"  ></label>  </label><br>

                            <label class="control-label" ><b id="idIPONivel" ></b></label><br>
                             <label class="control-label" ><b style="font-size: 13px;">Acción a realizar: </b> <span id="idIPOAccion"></span>  </label>
                        </div>
                        <div class="col-lg-5">
                            <table class="table table-bordered table-condensed" >
                                 <tbody>
                                 <tr >
                                    <td colspan="5" >Indicadores</td>
                                    <td colspan="3" *</td>
                                </tr>
                                <tr >
                                    <td >Neutro <i style="font-size: 18px;color:#03a9f4  " class="fa fa-circle"></i> </td>
                                    <td >Bajo <i style="font-size: 18px;color:#60C248 " class="fa fa-circle"></i></td>
                                    <td >Medio <i style="font-size: 18px;color:#ffde17  " class="fa fa-circle"></i> </td>
                                    <td >Alto <i style="font-size: 18px;color:#F57E1F " class="fa fa-circle"></i> </td>
                                    <td >Muy Alto <i style="font-size: 18px;color:#FF6C69  " class="fa fa-circle"></i> </td>
                                    <td title="Vivienda Inspeccionada" >VI</td>
                                    <td title="Vivienda Cerrada">VC</td>
                                    <td   title="Ovitrampa Manipulada" >OM</td>
                                </tr>
                               
                                <tr >
                                    <td><b id="bTotalNeutro"></b>%</td>
                                    <td><b id="bTotalBajo" ></b>%</td>
                                    <td><b id="bTotalMedio" ></b>%</td>
                                    <td><b id="bTotalAlto" ></b>%</td>
                                    <td><b id="bTotalMalto" ></b>%</td>
                                    <td><b id="bTotalVI" ></b></td>
                                    <td><b id="bTotalVC" ></b></td>
                                    <td><b id="bTotalOM"></b></td>
                                </tr>
                           
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-12"   >
                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Ovitrampa</th>
                                    <th>Ubigeo</th>
                                    <th>Dirección</th>
                                    <th>Sector</th>
                                    <th>Manzana</th>
                                    <th>Nro Huevos</th>
                                    <th>Fecha Insp</th>
                                    <th>Indicador</th>
                                </tr>
                                </thead>
                                <tbody id="tbodyDetailOvitrampas">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row" id="idRowVEntoMOvi">



                </div>
            </div>
            <!--===================================================-->
            <!--End Data Table-->
        </div>
    </div>
</div>


<div class="modal fade"   id="modalChartLine" role="dialog"  data-backdrop="static" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 100%">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id=""></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="">
                <div class="row" id="divModalBodyLineChart">

                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade"   id="modalChartLineReport" role="dialog"  data-backdrop="static" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 85%">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id=""></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divModalBodyLineChartReport">


            </div>
        </div>
    </div>
</div>

<!-- AIzaSyAUViXncAh7wtzgHVEUtLUbmldisa9KF1M
 AIzaSyDwUEEScclciTSQo5SART1F6KTxIBrWoPU-->
<style>
    .highcharts-data-label span {
        text-align: center;
    }
</style>
<link href="<?=base_url()?>assets/scripts/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<?php echo $_css?>
<?php echo $_js?>
<script src="<?=base_url()?>assets/scripts/highcharts/modules/exporting.js"></script>
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
    var map,heatmapx,marker;

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

    var contentString = '<div id="div_ejemplo">'+
        '<p>Este es un ejemplo de <b>InfoWindow</b>, ' +
        'como puedes ver puedes agregar cualquier cosa, ' +
        'cualquier <em>HTML</em>.</p>' +
        '<p>Este InfoWindow tiene un ancho de 200px ' +
        'y autom&aacute;ticamente da los saltos de l&iacute;nea.</p>'+
        '<p>Para ver el tutorial <a href="http://jafrancov.com/2010/09/info-window-gmaps-api-v3/">'+
        'clickea aqu&iacute;</a>.</p>'+
        '</div>';


    
    $(document).on("ready",function () {
        iniMaps();
        iniAutoCompleteDistrito();
        getDataSelRed(0);
       // initialize();
    });

     function iniMaps(){
         map = new google.maps.Map(document.getElementById('mapa'),OptionsMap);
         bounds=new google.maps.LatLngBounds();
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

        /* map.addListener('click', function(event) {
             var lat = event.latLng.lat();
             var lng = event.latLng.lng();
             //setInputsLatLng(lat,lng);
             var latlng= {lat:lat , lng:lng};
             marker.setPosition(latlng);
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
         });*/

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

    function setLabelsByIPO(IPO) {
         var IPO=Number(IPO);
        var ret={"color":"","accion":"","nivel":""};
        if(IPO == 0){
            ret.color="#03a9f4";
            ret.accion="Ninguna";
            ret.nivel="Neutro";
        }else if(IPO >= 0.1 && IPO < 5 ){
            ret.color="#60C248";
            ret.accion="Monitoreo de las acciones de vigilancia y control para evitar la proliferación de mosquitos";
            ret.nivel="Nivel 1";
        }else if(IPO >= 5 && IPO < 20 ){
            ret.color="#ffde17";
            ret.accion="Realizar la inspección semanal para idenfiticar los criaderos y/o criaderos potenciales para su eliminación";
            ret.nivel="Nivel 2";
        }else if(IPO >= 20 && IPO < 40 ){
            ret.color="#F57E1F";
            ret.accion="Se deben realiar actividades especiales(inspeccion adicional a lo programado, personal para recuperación de viviendas),además de la programación regular(Semanal) para eliminar todos los criaderos potenciales";
            ret.nivel="Nivel 3";
        }else if(IPO >= 40 ){
            ret.color="#FF6C69";
            ret.accion="Se deben realizar otras medidas de control mediante el uso de larvicidas o adulticidas";
            ret.nivel="Nivel 4";
        }
        return ret;
    }

    function setColorKP(valKP) {
         var valKP=Number(valKP);
         var ret={"color":"","urlicon":"","label":""};
        if(valKP == 0){
            ret.color="#03a9f4";
            ret.urlicon="assets/images/markers/neutro.png";
            ret.label="Neutro";
        }else if(valKP > 0 && valKP <= 60 ){
            ret.color="#60C248";
            ret.urlicon="assets/images/markers/green.png";
            ret.label="Bajo";
        }else if(valKP > 60 && valKP <= 120 ){
            ret.color="#ffde17";
            ret.urlicon="assets/images/markers/yellowx.png";
            ret.label="Medio";
        }else if(valKP > 120 && valKP <= 150 ){
            ret.color="#F57E1F";
            ret.urlicon="assets/images/markers/orange.png";
            ret.label="Alto";
        }else if(valKP > 150 ){
            ret.color="#FF6C69";
            ret.urlicon="assets/images/markers/red.png";
            ret.label="Muy Alto";
        }

        return ret;

    }

    function setColorCountMark(valKP) {
        var valKP=Number(valKP);
        var ret={"color":"","urlicon":"","label":""};
        if(valKP == 0){
            ret.color="#03a9f4";
            ret.urlicon="assets/images/markers/ineutro.png";
            ret.label="Neutro";
        }else if(valKP == 1 ){
            ret.color="#60C248";
            ret.urlicon="assets/images/markers/igreen.png";
            ret.label="Bajo";
        }else if(valKP == 2 ){
            ret.color="#ffde17";
            ret.urlicon="assets/images/markers/iyellow.png";
            ret.label="Medio";
        }else if(valKP == 3 ){
            ret.color="#F57E1F";
            ret.urlicon="assets/images/markers/iorange.png";
            ret.label="Alto";
        }else if(valKP  == 4 ){
            ret.color="#FF6C69";
            ret.urlicon="assets/images/markers/ired.png";
            ret.label="Muy Alto";
        }

        return ret;

    }

    function getPoints(){
        $.post(url_base+"insovitrampas/getPoints",{"id":2},function (data) {
            //console.log(data);
            var cordPath=[];
            $.each(data,function (k,v) {
                var lat=parseFloat(v.lat);
                var lng=parseFloat(v.lng );
                var tt=Number(v.nrohuevosovitrampa);
                // console.log(lat,lng);

                var latLong = new google.maps.LatLng(lat,lng);

                var marca=new google.maps.Marker({// props del marcador
                    nhuevos:v.nrohuevosovitrampa,
                    codovi:v.codigoovitrampa,
                    position:latLong,
                    label:{text:tt+"", color: "black",fontWeight:"bold",fontSize:"16px"},
                    icon:url_base+"/assets/images/markers/green.png"
                });



                google.maps.event.addListener(marca,'click',function(){
                    if (activeInfoWindow) { activeInfoWindow.close();}
                   console.log(marca.dtintera);
                   var ht="<p style='margin-bottom: 3px;'><b>Ovitrampa:</b> "+marca.codovi+"</p>";
                       ht=ht+"<p style='margin-bottom: 3px;' ><b>Nro huevos:</b> "+marca.nhuevos+"</p>";
                    infowindow = new google.maps.InfoWindow({
                        content: ht,
                        maxWidth: 200
                    });

                    infowindow.open(map,marca);
                    activeInfoWindow = infowindow;
                });

                bounds.extend(marca.position);
                //cordPath.push(latLong);
                marca.setMap(map);
                map.fitBounds(bounds);
            });
            //console.log(cordPath);
            //heatmap.setData(cordPath);
        },"json");
    }

    /*var MIN_NO_ACC = 520;
    var MAX_NO_ACC = 6119;
    function initialize() {
        geocoder = new google.maps.Geocoder();
        var mapProp = {
            center:new google.maps.LatLng(40.785091,-73.968285),
            zoom:11,
            mapTypeId:google.maps.MapTypeId.ROADMAP
        };
        var map=new google.maps.Map(document.getElementById("mapa"),mapProp);
        codeAddress("10001", 6119);
        codeAddress("10002", 5180);
        codeAddress("10003", 4110);
        codeAddress("10004", 899);
        codeAddress("10005", 520);
        codeAddress("10006", 599);

        function codeAddress(zip, noAccidents) {
            //var address = document.getElementById("address").value;
            geocoder.geocode( { 'address': zip}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    var hotSpot = results[0].geometry.location;
                    console.log(hotSpot + " " + noAccidents);
                    var heatMapZip = [
                        {location: hotSpot, weight: noAccidents}
                    ];
                    var color =[
                        "#ff0000",
                        "#00ff00"
                    ];
                    var heatmap = new google.maps.visualization.HeatmapLayer({
                        data: heatMapZip,
                        radius: 50,
                        dissapating: false
                    });
                    var rate = (noAccidents-MIN_NO_ACC)/(MAX_NO_ACC-MIN_NO_ACC+1);
                    console.log(rate);
                    var gradient = [
                        'rgba('+Math.round(255*rate)+', '+Math.round(255*(1-rate))+', 0, 0)',
                        'rgba('+Math.round(255*rate)+', '+Math.round(255*(1-rate))+', 0, 1)'];
                    heatmap.set('gradient', gradient);
                    heatmap.setMap(map);

                } else {
                    alert("Geocode was not successful for the following reason: " + status);
                }
            });
        }


    }*/


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
        },'json');
    }
    
    $(document).on("click","#btnReport",function () {
        var distrito=$("#distrito");
        var idubigeo=$("#idubigeo");
        var selRedSalud=$("#selRedSalud");
        var selEstablecimientoSalud=$("#selEstablecimientoSalud");
        var anio=$("#anio");
        var semana=$("#semana");
        var sector=$("#sector");
        var selEstadoVisita=$("#selEstadoVisita");
        var bol=true;

        bol=bol&&distrito.required();
        if(idubigeo.val() == "0"){  bol=bol&&false;alert_error("Seleccione el Distrito"); }
        bol=bol&&selRedSalud.requiredSelect();
        bol=bol&&selEstablecimientoSalud.requiredSelect();
        bol=bol&&semana.required();
        //bol=bol&&sector.required();
        if(!bol){ return 0;}

        $.post(url_base+"insovitrampas/getReportMap",{"idubigeo":idubigeo.val(),"selEstablecimientoSalud":selEstablecimientoSalud.val(),"anio":anio.val(),"semana":semana.val(),"sector":sector.val(),"selEstadoVisita":selEstadoVisita.val()},function (data) {
            console.log(data);
            setPointsInMap(data);
        },'json');

    });

    function reportInMapFor(tiporeport){
        $("#mapa").css("display","block");
        var distrito=$("#distrito");
        var idubigeo=$("#idubigeo");
        var selRedSalud=$("#selRedSalud");
        var selEstablecimientoSalud=$("#selEstablecimientoSalud");
        var anio=$("#anio");
        var semana=$("#semana");
        var sector=$("#sector");
        var selEstadoVisita=$("#selEstadoVisita");
        var bol=true;

        bol=bol&&distrito.required();
        if(idubigeo.val() == "0"){  bol=bol&&false;alert_error("Seleccione el Distrito"); }
        bol=bol&&selRedSalud.requiredSelect();
        bol=bol&&selEstablecimientoSalud.requiredSelect();
        bol=bol&&semana.required();
        //bol=bol&&sector.required();
        if(!bol){ return 0;}
        $.post(url_base+"insovitrampas/getReportMap",{"idubigeo":idubigeo.val(),"selEstablecimientoSalud":selEstablecimientoSalud.val(),"anio":anio.val(),"semana":semana.val(),"sector":sector.val(),"selEstadoVisita":selEstadoVisita.val()},function (data) {
            //console.log(data);
            setPointsInMap(data,tiporeport);
        },'json');

    }

    $(document).on("click","#btnReportConsolidado",function () {
        reporteConsolidadoxTipo($(this),"ovitrampa");
    });
    function reporteConsolidadoxTipo(btnx,nivel){
        $("#mapa").css("display","none");
        var distrito=$("#distrito");
        var btn=$("#btnGroupConsolidadoOvi");
        var idubigeo=$("#idubigeo");
        var selRedSalud=$("#selRedSalud");
        var selEstablecimientoSalud=$("#selEstablecimientoSalud");
        var anio=$("#anio");
        var sector=$("#sector");
        var selEstadoVisita=$("#selEstadoVisita");
        var bol=true;
        bol=bol&&distrito.required();
        if(idubigeo.val() == "0"){  bol=bol&&false;alert_error("Seleccione el Distrito"); }
        bol=bol&&selRedSalud.requiredSelect();
        if(nivel =="ovitrampa"  ){
            bol=bol&&selEstablecimientoSalud.requiredSelect();
        }

        if(!bol){ return 0;}
        $("#idRowVEntoMOvi").append("<tr><td colspan='10'><b> Procesando, Espere por favor...</b></td></tr>");
        btn.button("loading");
        var tmpTbodyConcolidadoVigilanciaEntoOvi=_.template($("#tmpTbodyConcolidadoVigilanciaEntoOvi").html());

        $.post(url_base+"Insovitrampas/getDataCompendioVigilanciaOvi",{"anio":anio.val(),"idubigeo":idubigeo.val(),"idipress":selEstablecimientoSalud.val(),'nivel':nivel},function (data) {
           console.log(data);

            if(nivel=="ovitrampa"){
                $("#idRowVEntoMOvi").html(tmpTbodyConcolidadoVigilanciaEntoOvi({data:data}));

                $.each(data,function (k,i) {
                    if((i.dt).length > 0) {
                        $.each(i.dt, function (kk, ii) {
                           // console.log(ii);
                            $.each(ii.semanal,function (kkk,iii) {
                                var idx="#idTd"+i.codigoovitrampa+"-"+iii.semana+"-"+ii.idvivienda;
                                if(iii.estadoviviendainspeccion == 1){
                                    var strxxxx="";
                                    if((iii.datavigilancia).length > 0 ){
                                        strxxxx="<b title='Ovitrampa: "+i.codigoovitrampa+" '><u>"+iii.nrohuevosovitrampa+"</u></b>"
                                    }else{
                                        strxxxx="<b title='Ovitrampa: "+i.codigoovitrampa+" '>"+iii.nrohuevosovitrampa+"</b>";
                                    }
                                    //console.log(idx);
                                    $(idx).html(strxxxx);
                                    var nh=setColorKP(iii.nrohuevosovitrampa);
                                    $(idx).css("background-color",nh.color);
                                }else if(iii.estadoviviendainspeccion == 2){
                                    $(idx).html("<b title='Ovitrampa: "+i.codigoovitrampa+"-Vivienda Cerrada'>V.C</b>" );
                                    $(idx).css("background-color","#ffffff00");
                                }else if(iii.estadoviviendainspeccion == 5){
                                    $(idx).html("<b title='Ovitrampa: "+i.codigoovitrampa+"-Manipulada '>O.M</b>");
                                    $(idx).css("background-color","#ffffff00");
                                }

                            });
                        });
                    }
                });

            }else if(nivel=="sector"){
                var tmpTableConsolidadoOviBySector=_.template($("#tmpTableConsolidadoOviBySector").html());
                $("#idRowVEntoMOvi").html(tmpTableConsolidadoOviBySector({data:data}));
                $.each(data,function(k,i){
                    if((i.semanas).length > 0 ){
                        $.each(i.semanas,function(kk,ii){
                            var idxIpo="#idTd-IPO"+i.idubigeo+"-"+i.idipress+"-"+i.sectorovitrampa+"-"+ii.semana;
                            var idxIDH="#idTd-IDH"+i.idubigeo+"-"+i.idipress+"-"+i.sectorovitrampa+"-"+ii.semana;
                           // console.log(idx);
                            var IPO = parseFloat(Number(ii.ipo))*100;
                            var lb=setLabelsByIPO(IPO.toFixed(2));
                            $(idxIpo).html("<b title=' "+i.sectorovitrampa+" '>"+IPO.toFixed(2)+"</b>");
                            $(idxIpo).css("background-color",lb.color);

                            var Idh=parseFloat(Number(ii.idh));
                            var lbIdh=setLabelsByIPO(Idh.toFixed(2));
                            $(idxIDH).html("<b title=' "+i.sectorovitrampa+" '>"+Idh.toFixed(2)+"</b>");
                            $(idxIDH).css("background-color",lbIdh.color);



                        });


                    }
                });
            }else if(nivel=="ipress"){
                var tmpTableConsolidadoOviByIPRESS=_.template($("#tmpTableConsolidadoOviByIPRESS").html());
                $("#idRowVEntoMOvi").html(tmpTableConsolidadoOviByIPRESS({data:data}));
                $.each(data,function(k,i){
                    if((i.semanas).length > 0 ){
                        $.each(i.semanas,function(kk,ii){
                            var idxipo="#idTd-IPO"+i.idubigeo+"-"+i.idipress+"-"+ii.semana;
                            var idxidh="#idTd-IDH"+i.idubigeo+"-"+i.idipress+"-"+ii.semana;
                           // console.log(idx);
                            var IPO = (parseFloat(Number(ii.ipo))*100).toFixed(2);
                            var lb=setLabelsByIPO(IPO);
                            var IDH = (parseFloat(Number(ii.idh))).toFixed(2);
                            var lbIdh=setLabelsByIPO(IPO);
                            $(idxipo).html("<b title=''>"+IPO+"</b>");
                            $(idxipo).css("background-color",lb.color);

                            $(idxidh).html("<b title=''>"+IDH+"</b>");
                            $(idxidh).css("background-color",lbIdh.color);

                        });
                    }
                });
            }else if(nivel=="distrito"){
                var tmpTableConsolidadoOviByDistrito=_.template($("#tmpTableConsolidadoOviByDistrito").html());
                $("#idRowVEntoMOvi").html(tmpTableConsolidadoOviByDistrito({data:data}));
                $.each(data,function(k,i){
                    if((i.semanas).length > 0 ){
                        $.each(i.semanas,function(kk,ii){
                            var idTDTOP="#idTd"+i.idubigeo+"-"+ii.semana+"-top";
                            var idTDTOE="#idTd"+i.idubigeo+"-"+ii.semana+"-toe";
                            var idTDTHR="#idTd"+i.idubigeo+"-"+ii.semana+"-thr";
                            var idTDIPO="#idTd"+i.idubigeo+"-"+ii.semana+"-ipo";
                            var idTDIDH="#idTd"+i.idubigeo+"-"+ii.semana+"-idh";

                            var IPO = (parseFloat(Number(ii.ipo))*100).toFixed(2);
                            var lb=setLabelsByIPO(IPO);
                            $(idTDTOP).html("<b title=''>"+ii.cantpositives+"</b>");
                            $(idTDTOE).html("<b title=''>"+ii.cantinspeccionados+"</b>");
                            $(idTDTHR).html("<b title=''>"+ii.sumnhuevos+"</b>");
                            $(idTDIPO).html("<b title=''>"+IPO+"</b>");
                            $(idTDIPO).css("background-color",lb.color);
                            $(idTDIDH).html("<b title=''>"+parseFloat(Number(ii.idh)).toFixed(2)+"</b>");

                        });
                    }
                });
            }


            $('html, body').animate({
                scrollTop: $("#idRowVEntoMOvi").offset().top
            }, 500);

            /*$.each(data,function (k,i) {
                if((i.semanal).length > 0){
                    $.each(i.semanal,function (kk,ii) {
                       var idx="#idTd"+i.codigoovitrampa+"-"+ii.semana;
                        $(idx).html("<b>"+ii.nrohuevosovitrampa+"</b>");
                        var nh=setColorKP(ii.nrohuevosovitrampa);
                        $(idx).css("background-color",nh.color);
                    });
                }
            });*/

            btn.button("reset");

        },'json');
        
    }

    $(document).on("click","#btnPrintVigiEntoOvi",function () {
        var ht=$("#tbodyConsolidadoVigilanciaOvi").html();
        console.log(ht);
        /*$.post(url_base+"Insovitrampas/getReportCompendioVigilanciaOvi",{"ht":ht},function (data) {
            console.log(data);
        },"html");*/
        tableToExcel("tableVigiEntoOvi","nn");
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


    $(document).on("click","#btnReportMapaCalor",function () {
        var distrito=$("#distrito");
        var idubigeo=$("#idubigeo");
        var selRedSalud=$("#selRedSalud");
        var selEstablecimientoSalud=$("#selEstablecimientoSalud");
        var anio=$("#anio");
        var semana=$("#semana");
        var sector=$("#sector");
        var selEstadoVisita=$("#selEstadoVisita");
        var bol=true;

        bol=bol&&distrito.required();
        if(idubigeo.val() == "0"){  bol=bol&&false;alert_error("Seleccione el Distrito"); }
        bol=bol&&selRedSalud.requiredSelect();
        bol=bol&&selEstablecimientoSalud.requiredSelect();
        bol=bol&&semana.required();
        //bol=bol&&sector.required();
        if(!bol){ return 0;}


         var myLatlng = new google.maps.LatLng( -6.4914193,-76.3596651);
// map options,
        var myOptions = {
            center: myLatlng,
            zoom: 16,
            options: { mapTypeId: google.maps.MapTypeId.TERRAIN }
        };
// standard map
        map = new google.maps.Map(document.getElementById("mapa"), myOptions);
// heatmap layer
        heatmap = new HeatmapOverlay(map,
            {
                // radius should be small ONLY if scaleRadius is true (or small radius is intended)
                radius: 0.0007,
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
        $.post(url_base+"insovitrampas/getReportMap",{"idubigeo":idubigeo.val(),"selEstablecimientoSalud":selEstablecimientoSalud.val(),"anio":anio.val(),"semana":semana.val(),"sector":sector.val(),"selEstadoVisita":selEstadoVisita.val()},function (data) {

            var dt=[];
                $.each(data,function (k,v) {
                    var lat = parseFloat(v.lat);
                    var lng = parseFloat(v.lng);
                    var nhuevos = Number(v.nrohuevosovitrampa);
                    var count = Number(v.count);
                    var latLong = new google.maps.LatLng(lat,lng);
                    var marca=new google.maps.Marker({// props del marcador
                        nhuevos:v.nrohuevosovitrampa,
                        codovi:v.codigoovitrampa,
                        position:latLong,
                        icon:url_base+"/assets/images/markers/igreen.png"
                    });
                    marca.setMap(map);
                    dt.push({lat:lat,lng:lng,count:count});
                });
            //console.log(dt);
            var testData = {
                max: 4,
                data: dt
            };
            heatmap.setData(testData);

            },'json');
    });

    var optLineChartHistoOvi=null;
    function seeHistoricoOviInLineChart(anior,idubigeor,ipressr,codovir,descipress) {
        var tmpDivModalLineChart=_.template($("#tmpDivModalLineChart").html());
        open_modal("modalChartLine");
        $("#divModalBodyLineChart").html(tmpDivModalLineChart);
        var anio=$("#anio option:selected").text();
        var region=$("#region").val();
        var provincia=$("#provincia").val();
        var distrito=$("#distrito").val();
        var red=$("#selRedSalud option:selected").text();
        $("#labelAnio").val(anio);
        $("#labelUbigeo").val(region+" "+provincia+" "+distrito);
        $("#labelRedSalud").val(red);
        $("#labelIpress").val(descipress);

        iniLineChartHistoricoOvi();
        $.post(url_base+"insovitrampas/seeLineChartHistoOvi",{"anio":anior,"idubigeo":idubigeor,"ipress":ipressr,"codovi":codovir},function (data) {
            //console.log(data);
            var data=JSON.parse(data);
            optLineChartHistoOvi.series[0].data =data;
            var chart = new Highcharts.chart(optLineChartHistoOvi);
            chart.setTitle({ text:'Ovitrampa: <b>'+codovir+'</b> -'+anio});
        });

        //optionsChart.title="POI-MPSM grado de avance por Áreas ";

    }

    function traey(d) {
        return 1;
    }
    function iniLineChartHistoricoOvi() {
        //alert("x");
        optLineChartHistoOvi ={
            chart: {
                renderTo: 'divChartLineHistoOvi',
                type: 'line'

            },
            exporting: {
                chartOptions: { // specific options for the exported image
                    plotOptions: {
                        series: {
                            dataLabels: {
                                enabled: true,
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
                enabled: false
            },
            tooltip: {
                pointFormat: 'Nro. Huevos: <b> {point.y:.2f}  </b>'
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        useHtml:true,
                        enabled: true,
                        align: 'center',
                        shape: 'callout',
                        borderColor: '#000',
                       // color: 'rgba(255,255,255,0.75)',
                        borderWidth: .5,
                        borderRadius: 5,
                        inside: false,
                        crop:false,
                        overflow:"allow",
                        y: -10,
                        style: {
                            fontFamily: 'Helvetica, sans-serif',
                            fontSize: '8px',
                            fontWeight: 'normal',
                            textShadow: 'none'
                        },
                        formatter: function (e) {

                            var datavigi=this.point.datavigi;
                            var htdet="";
                            if(datavigi.length>0){
                                $.each(datavigi,function (k,i) {
                                    if(k>0){
                                      htdet+="<br>";
                                    }
                                    htdet+="<b >"+i.jefebrigada+"<br>*"+ i.fechaintervencion+"</b>";
                                });
                                htdet+="<br>";
                            }else{
                                //this.point.dataLabel.alignOptions.style.fontSize="5px";

                            }

                            return  htdet+'<b style="center">'+ Highcharts.numberFormat(this.y, 0) +'</b>';
                        }

                    }

                },
                series: {
                    cursor: 'pointer',
                    marker:{
                        radius:6,
                        enabled: true
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
                name: 'Semana'
            }],
            colors: ['#50B432']
        }
    }
var optLineChartReport;

    function iniLineChartReport() {
        //alert("x");
        optLineChartReport ={
            chart: {
                renderTo: 'divChartLineReport',
                type: 'line'

            },
            exporting: {
                chartOptions: { // specific options for the exported image
                    plotOptions: {
                        series: {
                            dataLabels: {
                                enabled: true,
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
                    //rotation: -90,
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
///////////----------------------------------------------------------

function verGrafica(nivel,idubigeo,tipo,data) {
    open_modal("modalChartLineReport");
    var anio=$("#anio").val();
    var redsalud=$("#selRedSalud").val();
    var i;
    for(i = 0; i < data.length; i++){
        var valy;
        data[i].name = data[i]['semana'];
        if(tipo == "top"){
            valy=Number((parseFloat(Number(data[i]['cantpositives']))).toFixed(2));
            delete data[i].cantpositives;
        }else if(tipo == "toe"){
            valy=Number((parseFloat(Number(data[i]['cantinspeccionados']))).toFixed(2));
            delete data[i].cantinspeccionados;
        }else if(tipo == "thr"){
            valy=Number((parseFloat(Number(data[i]['sumnhuevos']))).toFixed(2));
            delete data[i].sumnhuevos;
        }else if(tipo == "ipo"){
            valy=Number((parseFloat(Number(data[i]['ipo']))*100).toFixed(2));
            delete data[i].ipo;
        }else if(tipo == "idh"){
            valy=Number((parseFloat(Number(data[i]['idh']))).toFixed(2));
            delete data[i].idh;
        }
        data[i].y = valy;
        delete data[i].semana;

    }
    var titleGraf="";
    var yAxisTitle="";
    var yAxisTitleAbreviado="";
    if(tipo == "top"){
        titleGraf="Ovitrampas Positivas";
        yAxisTitle="Nro Ovitrampas Positivas ";
        yAxisTitleAbreviado="Nro Ovi. Pos. ";
    }else if(tipo == "toe"){
        titleGraf="Ovitrampas Examinadas";
        yAxisTitle="Nro Ovitrampas Examinadas ";
        yAxisTitleAbreviado="Nro Ovi. Exa. ";
    }else if(tipo == "thr"){
        titleGraf="Huevos Recolectados";
        yAxisTitle="Nro Huevos Recolectados ";
        yAxisTitleAbreviado="Nro H. Reco. ";
    }else if(tipo == "ipo"){
        titleGraf="Índice Positividad de Ovitrampa";
        yAxisTitle="Índice Positividad de Ovitrampa";
        yAxisTitleAbreviado="IPO";
    }else if(tipo == "idh"){
        titleGraf="Índice densidad de Huevos";
        yAxisTitle="Índice densidad de Huevos";
        yAxisTitleAbreviado="IDH";
    }
    console.log(data);
    var tmpDivModalLineChartReport=_.template($("#tmpDivModalLineChartReport").html());
    $("#divModalBodyLineChartReport").html(tmpDivModalLineChartReport({anio:anio,redsalud:redsalud,distrito: data[0].DESC_DIST }));
    iniLineChartReport();
    optLineChartReport.series[0].data =data;
    optLineChartReport.yAxis.title.text=yAxisTitle;
    optLineChartReport.tooltip.pointFormat=yAxisTitleAbreviado+': <b>{point.y:.2f}</b>';
    var chart = new Highcharts.chart(optLineChartReport);
    chart.setTitle({ text:titleGraf});
}
    var colorLine=["#6c757d","#f7a35c","#7cb5ec","#90ed7d","#28a745","#e0b020","#9c27b0","#e91e63"];
function verGraficaMultilinea(nivel,data) {
   // console.log(data);
    open_modal("modalChartLineReport");
    var anio=$("#anio").val();
    var redsalud=$("#selRedSalud option:selected").text();
    var ipress= "";
    var distrito= "";
    if(data.length > 0){
        ipress=data[0].DESC_ESTAB;
        distrito= data[0].DESC_DIST;
    }


    var tmpDivModalLineChartReport=_.template($("#tmpDivModalLineChartReport").html());
    $("#divModalBodyLineChartReport").html(tmpDivModalLineChartReport({anio:anio,redsalud:redsalud,distrito:distrito }));
    iniLineChartReport();
    var d= [{"name":"1","y":33,"color":"#60C248"},{"name":"2","y":34,"color":"#60C248"},{"name":"3","y":57,"color":"#60C248"},{"name":"4","y":0,"color":"#03a9f4"},{"name":"5","y":173,"color":"#FF6C69"},{"name":"14","y":2,"color":"#60C248"}];
    var d2=[{"name":"1","y":323,"color":"#60C248"},{"name":"2","y":2,"color":"#60C248"},{"name":"3","y":1,"color":"#60C248"},{"name":"4","y":0,"color":"#03a9f4"},{"name":"5","y":173,"color":"#FF6C69"},{"name":"14","y":2,"color":"#60C248"}];
    var c=[{
        name: 'Sector 2',
        data: [{"name":"Jan","y":33,"color":"#60C248"},{"name":"Dec","y":34,"color":"#60C248"}]
         }
    ];
    var DataGraf=[];
    $.each(data,function(k,i){
        var dt=[];
        $.each(i.semanas,function (kk,ii) {
            var ipox=Number(parseFloat(Number(ii.ipo)*100).toFixed(2));
            dt.push({"name":ii.semana,"y":ipox});
        });
        DataGraf.push({"name":"Sector "+i.sectorovitrampa,"data":dt,"color":colorLine[k]});
    });
    console.log(DataGraf);
   // optLineChartReport.xAxis.categories=['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    optLineChartReport.series=DataGraf;
    optLineChartReport.yAxis.title.text="Índice Positividad de Ovitrampa";
    //optLineChartReport.series[1].data=d2;
    var chart = new Highcharts.chart(optLineChartReport);
}

    function seeLineGrafOviByIPOIDH(nivel,datax) {
        console.log(datax);
        open_modal("modalChartLineReport");
        var tmpDivModalLineChartReport=_.template($("#tmpDivModalLineChartReport").html());
        var anio=$("#anio").val();
        var redsalud=$("#selRedSalud option:selected").text();
        var distrito=datax.DESC_DIST;
        var ipress=datax.DESC_ESTAB
        $("#divModalBodyLineChartReport").html(tmpDivModalLineChartReport({anio:anio,redsalud:redsalud,distrito:distrito,"ipress":ipress }));
        iniLineChartReport();
        var DataGraf=[];
        var dtIpo=[];
        var dtIDH=[];
        if(nivel =="sector"){
            $.each(datax.semanas,function (kk,ii) {
                var ipox=Number(parseFloat(Number(ii.ipo)*100).toFixed(2));
                var idhx=Number(parseFloat(Number(ii.idh)).toFixed(2));
                dtIpo.push({"name":ii.semana,"y":ipox});
                dtIDH.push({"name":ii.semana,"y":idhx});
            });

            DataGraf.push({"name":"IPO","data":dtIpo,"color":colorLine[1]});
            DataGraf.push({"name":"IDH","data":dtIDH,"color":colorLine[2]});
            // console.log(DataGraf);
            optLineChartReport.series=DataGraf;
            optLineChartReport.yAxis.title.text="IPO/IDH";
            //optLineChartReport.series[1].data=d2;
            var chart = new Highcharts.chart(optLineChartReport);
            chart.setTitle({ text:"Sector "+datax.sectorovitrampa });
        }
        if(nivel =="ipress"){
            $.each(datax.semanas,function (kk,ii) {
                var ipox=Number(parseFloat(Number(ii.ipo)*100).toFixed(2));
                var idhx=Number(parseFloat(Number(ii.idh)).toFixed(2));
                dtIpo.push({"name":ii.semana,"y":ipox});
                dtIDH.push({"name":ii.semana,"y":idhx});
            });

            DataGraf.push({"name":"IPO","data":dtIpo,"color":colorLine[1]});
            DataGraf.push({"name":"IDH","data":dtIDH,"color":colorLine[2]});
            // console.log(DataGraf);
            optLineChartReport.series=DataGraf;
            optLineChartReport.yAxis.title.text="IPO/IDH";
            //optLineChartReport.series[1].data=d2;
            var chart = new Highcharts.chart(optLineChartReport);
            chart.setTitle({ text:"IPRESS "+datax.DESC_ESTAB });
        }


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
<script type="text/template" id="tmpTbodyMaps">
    <tr>
        <td class="text-center">#</td>
        <td>Cod.Ovitrampa</td>
        <td>Ubigeo</td>
        <td>Sector</td>
        <td>Manzana</td>
        <td>Nro Huevos</td>
        <td>Fecha Insp</td>
    </tr>
</script>

<script type="text/template" id="tmpTbodyConcolidadoVigilanciaEntoOvi">
    <h4>VIGILANCIA ENTOMOLÓGICA MEDIANTE OVITRAMPAS   <button class="btn btn-mint btn-xs" type="button" id="btnPrintVigiEntoOvi">Imprimir</button> </h4>

    <!-- <button class="btn btn-mint btn-xs" type="button" onclick="seeHistoricoOviInLineChart('H01')"  >Test LineChart</button>-->
    <div class="col-lg-12"   >
        <div class="table-responsive"style="height: 500px !important;overflow: scroll;" >
            <table border="1" class="table table-condensed table-hover table-bordered" id="tableVigiEntoOvi"   >
                <thead>
                <tr  >
                    <th   >DISTRITO</th>
                    <th  >LOCALIDAD (EE.SS)</th>
                    <th  >CODIGO OVITRAMPA</th>
                    <th    >DIRECCIÓN DE VIVIENDA</th>
                    <th   >UBICACIÓN OVITRAMPA</th>
                    <th   >SECTOR</th>
                    <th   >LONGITUD (W)</th>
                    <th  >LATITUD (S)</th>
                    <th >ALTITUD (msnm)</th>
                    <?php for($i=1; $i <= 53 ;$i++){?>
                        <th> <u>Fecha </u> <br> SE <?=$i?> </th>
                    <?php }?>
                </tr>

                </thead>
                <tbody id="tbodyConsolidadoVigilanciaOvi">
                <% _.each(data,function(i,k){ %>
                    <% _.each(i.dt,function(ii,kk){ %>
                    <tr>
                        <td  > <%=ii.DESC_DIST%> </td>
                        <td   >  <%=ii.DESC_ESTAB%> </td>
                        <td style="text-align: center" ><a href="javascript:void(0)" class="btn btn-link" onclick="seeHistoricoOviInLineChart('<%=i.anio%>','<%=i.idubigeo%>','<%=i.ipress%>','<%= i.codigoovitrampa %>','<%=ii.DESC_ESTAB%>')"><%= i.codigoovitrampa %></a> </td>
                        <td  ><%= ii.direccion %> <%=ii.nro%> </td>
                        <td   >  <%=ii.ubicacionovitrampa%> </td>
                        <td   >  <%=ii.sectorovitrampa%> </td>
                        <td   >  <%=ii.latovitrampa%> </td>
                        <td   >  <%=ii.lngovitrampa%> </td>
                        <td >-</td>
                        <% for( var x=1;x<=53;x++){

                        %>
                        <td style="text-align: center;background-color: #ebedf1;" id="idTd<%= i.codigoovitrampa %>-<%=x%>-<%=ii.idvivienda%>" > </td>
                        <% }
                        %>
                    </tr>
                    <% }); %>
                <% }); %>
                </tbody>
            </table>
        </div>
    </div>
</script>

<script type="text/template" id="tmpTableConsolidadoOviBySector">
    <% var dataString=JSON.stringify(data)%>
    <h4>VIGILANCIA ENTOMOLÓGICA MEDIANTE OVITRAMPAS- IPO x Sector
        <button class="btn btn-mint btn-xs" type="button" id="btnPrintVigiEntoOvi">Imprimir</button>
        <button class='btn btn-primary btn-xs' type='button' onclick='verGraficaMultilinea("sector",<%=dataString%>)' >Ver Gráfica</button>
    </h4>

    <!-- <button class="btn btn-mint btn-xs" type="button" onclick="seeHistoricoOviInLineChart('H01')"  >Test LineChart</button>-->
    <div class="col-lg-12"   >
        <div class="table-responsive"style="height: 500px !important;overflow: scroll;" >
        <table border="1" class="table table-condensed table-hover table-bordered" id="tableVigiEntoOvi" >
           <thead>
            <tr >
                <th  >Distrito</th>
                <th  >LOCALIDAD (EE.SS)</th>
                <th >SECTOR</th>
                <th >*</th>
                <?php for($i=1; $i <= 53 ;$i++){?>
                    <th> <u>Fecha </u> <br> SE <?=$i?> </th>
                <?php }?>
            </tr>
           </thead>
            <tbody>
            <% _.each(data,function(i,k){ %>
            <tr  >
                <td rowspan="2"  ><%= i.DESC_DIST %></td>
                <td rowspan="2" ><%= i.DESC_ESTAB %></td>
                <td rowspan="2" ><a href='javascript:void(0);' class="btn-link" onclick='seeLineGrafOviByIPOIDH("sector",<%=JSON.stringify(i)%>)'><%= i.sectorovitrampa %></a></td>
                <td>IPO</td>
                <% for( var x=1;x<=53;x++){
                %>
                <td style="text-align: center;background-color: #ebedf1;" id="idTd-IPO<%=i.idubigeo %>-<%=i.idipress%>-<%=i.sectorovitrampa%>-<%=x%>" > </td>
                <% }
                %>

            </tr>
            <tr  >
                <td  >IDH</td>
                <% for( var x=1;x<=53;x++){
                %>
                <td style="text-align: center;background-color: #ebedf1;" id="idTd-IDH<%=i.idubigeo %>-<%=i.idipress%>-<%=i.sectorovitrampa%>-<%=x%>" > </td>
                <% }
                %>
            </tr>
            <% });%>
            
            
            </tbody>
        </table>
        </div>
    </div>
</script>


<script type="text/template" id="tmpTableConsolidadoOviByIPRESS">
    <h4>VIGILANCIA ENTOMOLÓGICA MEDIANTE OVITRAMPAS- IPO x IPRESS <button class="btn btn-mint btn-xs" type="button" id="btnPrintVigiEntoOvi">Imprimir</button> </h4>

    <!-- <button class="btn btn-mint btn-xs" type="button" onclick="seeHistoricoOviInLineChart('H01')"  >Test LineChart</button>-->
    <div class="col-lg-12"   >
        <div class="table-responsive"style="height: 500px !important;overflow: scroll;" >
            <table border="1" class="table table-condensed table-hover table-bordered" id="tableVigiEntoOvi" >
                <thead>
                <tr >
                    <th  >Distrito</th>
                    <th  >LOCALIDAD (EE.SS)</th>
                    <th >*</th>
                    <?php for($i=1; $i <= 53 ;$i++){?>
                        <th> <u>Fecha </u> <br> SE <?=$i?> </th>
                    <?php }?>
                </tr>
                </thead>
                <tbody>
                <% _.each(data,function(i,k){ %>
                <tr  >
                    <td rowspan="2"  ><%= i.DESC_DIST %></td>
                    <td rowspan="2" ><a href='javascript:void(0);' class="btn-link" onclick='seeLineGrafOviByIPOIDH("ipress",<%=JSON.stringify(i)%>)'><%= i.DESC_ESTAB %></a></td>

                    <td>IPO</td>
                    <% for( var x=1;x<=53;x++){
                    %>
                    <td style="text-align: center;background-color: #ebedf1;" id="idTd-IPO<%=i.idubigeo %>-<%=i.idipress%>-<%=x%>" > </td>
                    <% }
                    %>

                </tr>
                <tr  >
                    <td  >IDH</td>
                    <% for( var x=1;x<=53;x++){
                    %>
                    <td style="text-align: center;background-color: #ebedf1;" id="idTd-IDH<%=i.idubigeo %>-<%=i.idipress%>-<%=x%>" > </td>
                    <% }
                    %>
                </tr>
                <% });%>


                </tbody>
            </table>
        </div>
    </div>
</script>



<script type="text/template" id="tmpTableConsolidadoOviByDistrito">
    <h4>VIGILANCIA ENTOMOLÓGICA MEDIANTE OVITRAMPAS- IPO x Distrito <button class="btn btn-mint btn-xs" type="button" id="btnPrintVigiEntoOvi">Imprimir</button> </h4>

    <!-- <button class="btn btn-mint btn-xs" type="button" onclick="seeHistoricoOviInLineChart('H01')"  >Test LineChart</button>-->
    <div class="col-lg-12"   >
        <div class="table-responsive"style="height: 500px !important;overflow: scroll;" >
            <table border="1" class="table table-condensed table-hover table-bordered" id="tableVigiEntoOvi"  >
              <thead>
              <tr>
                  <th >Distri</th>
                  <th >AÑO
                      (SEMANA ENTOMOLOGICA)</th>
                  <th >*</th>
                  <?php for($i=1; $i <= 53 ;$i++){?>
                      <th> <u>Fecha </u> <br>SE <?=$i?> </th>
                  <?php }?>
              </tr>
              </thead>

                <tbody>
                <% _.each(data,function(i,k){
                    var semanalData=JSON.stringify(i.semanas);
                %>
                <tr >
                    <td rowspan="5"><%= i.DESC_DIST %> </td>
                    <td >TOTAL OVITRAMPAS
                        POSITIVAS</td>
                    <td ><a href='javascript:void(0)' onclick='verGrafica("distrito","<%=i.idubigeo %>","top",<%=semanalData%>)' >Ver</a> </td>
                    <% for( var x=1;x<=53;x++){
                    %>
                    <td style="text-align: center;background-color: #ebedf1;" id="idTd<%=i.idubigeo %>-<%=x%>-top" > </td>
                    <% }
                    %>
                </tr>
                <tr>
                    <td >TOTAL DE OVITRAMPAS EXAMINADAS</td>
                    <td ><a href='javascript:void(0)' onclick='verGrafica("distrito","<%=i.idubigeo %>","toe",<%=semanalData%>)' >Ver</a> </td>

                    <% for( var x=1;x<=53;x++){
                    %>
                    <td style="text-align: center;background-color: #ebedf1;" id="idTd<%=i.idubigeo %>-<%=x%>-toe" > </td>
                    <% }
                    %>
                </tr>
                <tr>
                    <td  >TOTAL DE HUEVOS RECOLECTADOS<span style="mso-spacerun:yes">&nbsp;</span></td>
                    <td ><a href='javascript:void(0)' onclick='verGrafica("distrito","<%=i.idubigeo %>","thr",<%=semanalData%>)' >Ver</a> </td>

                    <% for( var x=1;x<=53;x++){
                    %>
                    <td style="text-align: center;background-color: #ebedf1;" id="idTd<%=i.idubigeo %>-<%=x%>-thr" > </td>
                    <% }
                    %>
                </tr>
                <tr>
                    <td  >INDICE DE POSITIVIDAD DE OVOPOSICIÓN (IPO)</td>
                    <td ><a href='javascript:void(0)' onclick='verGrafica("distrito","<%=i.idubigeo %>","ipo",<%=semanalData%>)' >Ver</a> </td>


                    <% for( var x=1;x<=53;x++){
                    %>
                    <td style="text-align: center;background-color: #ebedf1;" id="idTd<%=i.idubigeo %>-<%=x%>-ipo" > </td>
                    <% }
                    %>
                </tr>
                <tr >
                    <td >INDICE DE DENSIDAD DE HUEVOS (IDH)</td>
                    <td ><a href='javascript:void(0)' onclick='verGrafica("distrito","<%=i.idubigeo %>","idh",<%=semanalData%>)' >Ver</a> </td>


                    <% for( var x=1;x<=53;x++){
                    %>
                    <td style="text-align: center;background-color: #ebedf1;" id="idTd<%=i.idubigeo %>-<%=x%>-idh" > </td>
                    <% }
                    %>
                </tr>
                <% });%>

               </tbody>
            </table>
        </div>
    </div>
</script>

<script type="text/template" id="tmpTbodyConcolidadoVigilanciaEntoOviRespaldo">
    <% _.each(data,function(i,k){ %>
    <tr>
        <td ></td>
        <td ></td>
        <td style="text-align: center" ><%= i.codigoovitrampa %> </td>
        <td ></td>
        <td ></td>
        <td ></td>
        <td ></td>
        <td ></td>
        <td >Lat</td>
        <% for( var ii=1;ii<=53;ii++){

        %>
        <td style="text-align: center" id="idTd<%= i.codigoovitrampa %>-<%=ii%>" > </td>
        <% }
        %>
    </tr>
    <% }); %>
</script>

<script type="text/template" id="tmpDivModalLineChart">
    <div class="row">
        <div class="col-lg-12">
            <div class="col-sm-1">

            </div>
            <div class="col-sm-1">
                <div class="form-group">
                    <label class="control-label" style="margin-bottom: 0px;" >Año</label>
                    <input style="background-color: white; border: 0px;padding-left: 0px;font-weight: bold" type="text" id="labelAnio" readonly="readonly" class="form-control" >
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label" style="margin-bottom: 0px;">Ubigeo</label>
                    <input style="background-color: white; border: 0px;padding-left: 0px;font-weight: bold" type="text" id="labelUbigeo" readonly="readonly" class="form-control" >
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label class="control-label" style="margin-bottom: 0px;" >Red Salud</label>
                    <input style="background-color: white; border: 0px;padding-left: 0px;font-weight: bold" type="text" id="labelRedSalud" readonly="readonly" class="form-control">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" style="margin-bottom: 0px;" >IPRESS</label>
                    <input style="background-color: white; border: 0px;padding-left: 0px;font-weight: bold" type="text" id="labelIpress" readonly="readonly" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12"  >
            <div class="col-lg-12" id="divChartLineHistoOvi" style="height: 550px; " >

            </div>
        </div>
    </div>

</script>



<script type="text/template" id="tmpDivModalLineChartReport">
    <div class="row">
        <div class="col-lg-9">
            <div class="col-sm-1">

            </div>
            <% if(typeof anio !=="undefined"){ %>
            <div class="col-sm-1">
                <div class="form-group">
                    <label class="control-label" style="margin-bottom: 0px;" >Año</label>
                    <input style="background-color: white; border: 0px;padding-left: 0px;font-weight: bold" type="text" value="<%=anio%>"  readonly="readonly" class="form-control" >
                </div>
            </div>
            <% } %>
            <% if(typeof redsalud !=="undefined"){ %>
            <div class="col-sm-2">
                <div class="form-group">
                    <label class="control-label" style="margin-bottom: 0px;" >Red Salud</label>
                    <input style="background-color: white; border: 0px;padding-left: 0px;font-weight: bold" type="text" value="<%=redsalud%>"   readonly="readonly" class="form-control">
                </div>
            </div>
            <% } %>
            <% if(typeof distrito !=="undefined"){ %>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label" style="margin-bottom: 0px;">Ubigeo</label>
                    <input style="background-color: white; border: 0px;padding-left: 0px;font-weight: bold" type="text" value="<%=distrito%>"   readonly="readonly" class="form-control" >
                </div>
            </div>
            <% } %>
            <% if(typeof ipress !=="undefined"){ %>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" style="margin-bottom: 0px;" >IPRESS</label>
                    <input style="background-color: white; border: 0px;padding-left: 0px;font-weight: bold"  value="<%=ipress%>" type="text" readonly="readonly" class="form-control">
                </div>
            </div>
            <% } %>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9" id="divChartLineReport" style="height: 500px; " >

        </div>
    </div>

</script>