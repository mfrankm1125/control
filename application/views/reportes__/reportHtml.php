<div class="row">
    <div class="col-xs-12">
        <div class="panel">

            <div class="panel-body">
                <div class="col-xs-12"  >
                    <h3 id="loadchart"></h3>
                    <div id="grafico" style="width: 100%">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-body" id="bodyReportTable">
                asdasdasddasaddsa
            </div>
        </div>
    </div>
</div>


<?php
 $mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
 echo $_css?>
<?php echo $_js?>
<script type="text/template" id="tmp" >
   <% var cantidadActOp=0;
    var sumGAxActOp=0;
   var finalGaArea=0;
    _.each(data,function(ix,k){
         _.each(ix.actvops,function(iix,kk){
               var gaini=parseFloat(iix.ga[0].porcenajeFinal);
               //var ga=(parseFloat(gaini)*100).toFixed(2);
               cantidadActOp=cantidadActOp+1;
               sumGAxActOp=parseFloat(sumGAxActOp+gaini);
            });
     });

   finalGaArea=parseFloat(sumGAxActOp/cantidadActOp)*100;
   finalGaArea=finalGaArea.toFixed(2);
    %>



    <h3>&Aacute;rea :
        <%= data[0].nombrearearesponsable %></h3>
    <p>Evaluaci&oacute;n de <b><?=$mes[$mesIni]?></b> a <b><?=$mes[$mesEnd]?></b> del <b><?=$periodo?></b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Grado de Avance:<%=finalGaArea%>  </p>

    <%



    _.each(data,function(i,k){

    %>

    <table class="table table-bordered table-condensed" >
        <thead>
        <tr>
            <th >Act.Pre. </th>
            <td colspan="3"><%=i.actpre%></td>

        </tr>
        <tr>
            <th >#</th>
            <th>UM</th>
            <th>Act.OP</th>       <!--<th>M. de <br>Verificacion</th>-->

            <th>%</th>
            <!--<th>Obs.</th> -->

        </tr>
        </thead>
        <%  var c=0;
        var sumGa=0;
        _.each(i.actvops,function(ii,kk){
            c=c+1;

             console.log("xxx",ii.ga[0].porcenajeFinal);
             var gaini=ii.ga[0].porcenajeFinal;
             var ga=(parseFloat(gaini)*100).toFixed(2);


        %>
            <tr>
                <td><%=c%></td> <td><%=ii.um %></td> <td><%= ii.nameactvop %></td>
                <td style="text-align: right" ><%=ga%> </td>
            </tr>

    <%    });

        %>
    </table>
    <br>
    <%
    });
    %>

</script>
<script type="text/template" id="tmpDataBodyReport" >


    <h3>&Aacute;rea :
        <%=data[0].nombrearearesponsable; %></h3>
    <p>Evaluaci&oacute;n de <b> </b> a <b> del  </b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Grado de Avance:  </p>






    <%
    var cantidadActOp=0;
    var sumGAxActOp=0;
    _.each(data,function(i,k){




        %>

        <table style=" border: 1px solid black;
    border-collapse: collapse;" border="1" width="1000"   cellpadding="5">
            <thead>
            <tr>
                <th >Act.Pre. </th>
                <td colspan="3"><%=i.actpre%></td>

            </tr>
            <tr>
                <th >#</th>
                <th>UM</th>
                <th>Act.OP</th>       <!--<th>M. de <br>Verificacion</th>-->

                <th>%</th>
                <!--<th>Obs.</th> -->

            </tr>
            </thead>
            <%  var c=0;
                var sumGa=0;

                _.each(i.actvops,function(ii,kk){

                    sumGa=sumGa+ parseFloat(ii.ga.[0].porcenajeFinal);
                    console.log("xxx",ii.length);
                    if(ii.length >0 ){
                    var gaini=ii.ga.[0].porcenajeFinal;
                    var ga=(parseFloat(gaini)*100).toFixed(2);
                    //$gaf=str_replace(".", ",", ga);
                    c=c+1;
                    cantidadActOp=cantidadActOp+1;
                    sumGAxActOp=sumGAxActOp+gaini;

                    %>
                    <tr>
                        <td><%=c%></td> <td><%=ii.um %></td> <td><%= ii.nameactvop %></td>
                        <td style="text-align: right" ><%=gaini%></td>
                    </tr>
                <%   }
                }); %>
            <!--<tr>
            <td colspan="3" >Total</td>
            <td><?php //str_replace(".", ",",round((floatval($sumGa/$c)*100),2)) ;?></td>
        </tr>-->
        </table>
        <br>
    <%
    });
    %>

</script>

<script type="text/javascript">
    var optionsChart;
    var dataR=[<?=$dataR?>];
    var mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

    $(document).on("ready",function () {
        initChart();
        var t=_.template($("#tmp").html());
        $("#bodyReportTable").html(t({data:dataR[0].data }));
        //console.log(dataR[0].data);
        loadDataChart(dataR[0].data);

        //Data=[['xxx', 23.7]];

    });

    function loadDataChart(dataChart){
        Data=[];
        var cod="";
        var c=0;
        namearea=dataChart[0].nombrearearesponsable;
        mesperiodo="De "+"<?=$mes[$mesIni]?>"+" a <?=$mes[$mesEnd]?>"+" del <?=$periodo?>";
        $.each(dataChart,function (key1,item1) {
            cod="actpre"+(key1+1);
            $.each(item1.actvops,function (key2,item2) {
                var codchart=cod+"actop"+(key2+1);
                //console.log(codchart,item2.ga[0].porcenajeFinal);
                gaActOp=parseFloat((parseFloat(item2.ga[0].porcenajeFinal)*100).toFixed(2));
                Data.push([codchart,gaActOp]);
            });
        });

        console.log(Data);
        optionsChart.series[0].data =Data;
        var chartx = new Highcharts.chart(optionsChart);
        chartx.setTitle({text: namearea});
        chartx.setSubtitle({text: mesperiodo});
    }

    function initChart() {
        optionsChart ={
            chart: {
                renderTo: 'grafico',
                type: 'column'
            },
            exporting: {
                chartOptions: { // specific options for the exported image
                    plotOptions: {
                        series: {
                            dataLabels: {
                                enabled: true
                            }
                        }
                    }
                },
                fallbackToExportServer: false,
                filename:"filexxx"
            },
            title: {
                text: 'cargando...'
            },
            subtitle: {
                text: 'cargando...'
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                max:100,
                title: {
                    text: 'Porcentaje'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Grado de Avance: <b>{point.y:.2f} % </b>'
            },
            series: [{
                name: 'Population',
                dataLabels: {
                    enabled: true,
                    color: '#FFFFFF',
                    align: 'center',
                    format: '{point.y:.2f}', // one decimal
                    y: 10, // 10 pixels down from the top
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }]


        }


    }
/*
    var chart = new Highcharts.chart('grafico', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'World\'s largest cities per 2014'
        },
        subtitle: {
            text: 'Source: <a href="http://en.wikipedia.org/wiki/List_of_cities_proper_by_population">Wikipedia</a>'
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Population (millions)'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Population in 2008: <b>{point.y:.1f} millions</b>'
        },
        series: [{
            name: 'Population',
            data: [
                ['Shanghai', 23.7],
                ['Lagos', 16.1],
                ['Istanbul', 14.2],
                ['Karachi', 14.0],
                ['Mumbai', 12.5],
                ['Moscow', 12.1],
                ['São Paulo', 11.8],
                ['Beijing', 11.7],
                ['Guangzhou', 11.1],
                ['Delhi', 11.1],
                ['Shenzhen', 10.5],
                ['Seoul', 10.4],
                ['Jakarta', 10.0],
                ['Kinshasa', 9.3],
                ['Tianjin', 9.3],
                ['Tokyo', 9.0],
                ['Cairo', 8.9],
                ['Dhaka', 8.9],
                ['Mexico City', 8.9],
                ['Lima', 8.9]
            ],

            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y:.1f}', // one decimal
                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }

        }]
    });*/
</script>