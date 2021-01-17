<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-control">
                    <button class="add-tooltip  btn btn-default btn-lg"  data-toggle="tooltip" data-container="body" data-placement="left" data-original-title="Representa los datos de analisis de suelos"  ><i class="fa fa-question-circle-o"></i></button>
                </div>
                <h3 class="panel-title">*</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 5px;" >
                <div class="row">
                    <div class="col-lg-12"  >
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Fecha</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label">Sector</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Estado</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <label class="control-label">__________</label>
                                <button type="button" id="btnReport" class="btn btn-mint"><b>Generar</b></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12" id="mapa" style="height: 500px;">

                    </div>
                </div>
                <div class="row">
                    <br>
                    <div class="col-lg-12"   >
                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>User</th>
                                    <th>Plan</th>
                                </tr>
                                </thead>
                                <tbody>

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



<?php echo $_css?>
<?php echo $_js?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzHReXbQ145y0mfQzSdTnoiav9HOuWTaU&libraries=visualization,places,geometry"  ></script>

<script type="text/javascript">
    var titles={'addtitle':"Nuevo tipo de animal" ,'updatetitle':"Editar tipo de animal"};
    var isEdit=null,idEdit=null;
    var bounds ;
  var iniLatLng= {lat: -6.4914193, lng: -76.3596651};
    var p1={lat:-6.492144352034575,lng:-76.36369990768162};
    var p2={lat:-6.494617474305945,lng:-76.36103915633885};
    var p3={lat:-6.505831652049439,lng:-76.3655881828281};
    var p4={lat:-6.500544390280335,lng:-76.37189673843113};

    var px={lat:-6.498241855678474,lng:-76.36606025161473};
    var map,heatmap,marker;

    var OptionsMap={
        center:iniLatLng,
        mapTypeId:google.maps.MapTypeId.roadmap,
        Zoom: 15
    };
    
    $(document).on("ready",function () {
          iniMaps();
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
         getPoints();
         // heatmap.setData(getP());
     }

    function getPoints(){
        $.post(url_base+"inspecciones/getPoints",{"id":2},function (data) {
            //console.log(data);
            var cordPath=[];
            $.each(data,function (k,v) {
                var lat=parseFloat(v.lat);
                var lng=parseFloat(v.lng );
                var tt=Number(v.ti);
                // console.log(lat,lng);
                var latLong = new google.maps.LatLng(lat,lng);
                var marca=new google.maps.Marker({// props del marcador
                    position:latLong,
                    label:{text: tt+"", color: "black",fontWeight:"bold",fontSize:"16px"}
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


</script>
