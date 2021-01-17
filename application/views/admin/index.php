<div id="page-content">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title"  ><b style="font-size: 18px;"><i class="fa fa-dollar"></i> Bienvenido</b>  </h3>
                    <hr style="margin-bottom: 0px;margin-top: 0px;">
                </div>
                <?php if($this->session->userdata('id_role') != 18){ ?>
                <!--Data Table-->

                <!--===================================================-->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12" style="text-align: left">
                            <b>Ganancia BTC:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
                            <select class="form-control" id="selAnio" style="width: 20%;display: inline-block;">
                                <?php foreach($anios as $i ){ ?>
                                    <option value="<?=$i["anio"]?>"><?=$i["anio"]?></option>
                               <?php }?>
                            </select>
                            <button style=" display: inline-block;" class="btn btn-sm btn-purple" type="button" id="verGanaciaBtc">Ver</button>
                        </div>
                        <div class="col-lg-12" id="chartMeses" style="height: 250px;display: none">
                            <h4>Cargando....</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="text-align: left;margin-top: 5px;">
                            <b>Ganancia Dolares:&nbsp;</b>
                            <select class="form-control" id="selAnio2" style="width: 20%;display: inline-block;">
                                <?php foreach($anios as $i ){ ?>
                                    <option value="<?=$i["anio"]?>"><?=$i["anio"]?></option>
                                <?php }?>
                            </select>
                            <button style=" display: inline-block;" class="btn btn-sm btn-purple" type="button" id="verGanaciaDolares">Ver</button>

                        </div>
                        <div class="col-lg-12" id="chartMesesDolares" style="height: 250px;display: none">
                            <h4>Cargando....</h4>
                        </div>
                    </div>

                </div>
                <!--===================================================-->
                <!--End Data Table-->
            <?php } ?>
            </div>
        </div>
    </div>
</div>


<?php echo $_js?>
<?php echo $_css?>
<script src="<?=base_url()?>assets/scripts/highcharts/highcharts.js"></script>
<script src="<?=base_url()?>assets/scripts/highcharts/modules/exporting.js"></script>

<script type="text/javascript">
var optLineChartReportGananciaByMes;
var optLineChartReportGananciaByMesDolares_;
<?php if($this->session->userdata('id_role') != 18){ ?>
$(document).on("ready",function () {
    /*iniDataChartGanaciaByMesDolares_();
    iniDataChartGanaciaByMes();*/
});
<?php } ?>

$(document).on("click","#verGanaciaBtc",function () {
    $("#chartMeses").css("display","block");
    iniDataChartGanaciaByMes();
});

$(document).on("click","#verGanaciaDolares",function () {
    $("#chartMesesDolares").css("display","block");
    iniDataChartGanaciaByMesDolares_();
});
function iniDataChartGanaciaByMes() {
    iniLineChartReportGanaciaByMes();


    $.post(url_base+"admin/chartGanaciaByMeses",{"anio":$("#selAnio").val()},function (data) {
        //console.log(data);
        var data=JSON.parse(data);
        optLineChartReportGananciaByMes.series[0].data =data;
        var chart = new Highcharts.chart(optLineChartReportGananciaByMes);
        chart.setTitle({ text:'Ganancia x meses - '+$("#selAnio").val()});
    });

}

function iniDataChartGanaciaByMesADia(mes) {
    $.post(url_base+"admin/chartGanaciaByMesesXDia",{"anio":$("#selAnio").val(),mes:mes},function (data) {

        //console.log(data);
        var data=JSON.parse(data);
        optLineChartReportGananciaByMes.series[0].data =data;
        optLineChartReportGananciaByMes.xAxis.title.text="Dia";
        var chart = new Highcharts.chart(optLineChartReportGananciaByMes);
        chart.setTitle({ text:'Ganancia del mes '+mes+'-'+$("#selAnio").val()+'<a href="javascript:void(0)" style="font-size: 9px;" onclick="iniDataChartGanaciaByMes()" class="btn btn-link"  >Atrás</a>' ,useHTML:true});
    });
}




function iniLineChartReportGanaciaByMes() {

    optLineChartReportGananciaByMes ={
        chart: {
            renderTo: 'chartMeses',
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
                text: 'Mes'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Soles'
            }
        },
        legend: {
            enabled: true
        },
        tooltip: {
             pointFormat: '<b> S/{point.y:.2f}  </b>',
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
                            // console.log(this.name);
                            iniDataChartGanaciaByMesADia(this.name);
                        }
                    }
                }
            }
        },
        series: [{
            name: 'Mes',
            dataLabels: {
                allowOverlap: true,
                enabled: true,
                //rotation: -90,
                color: '#FFFFFF',
                align: 'left',
                format: 'S/{point.y:.2f}', // one decimal 1f
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


function iniDataChartGanaciaByMesDolares_() {
    iniLineChartReportGanaciaByMesDolares_();


    $.post(url_base+"admin/chartGanaciaByMesesDolares",{"anio":$("#selAnio2").val()},function (data) {
        //console.log(data);
        var data=JSON.parse(data);
        optLineChartReportGananciaByMesDolares_.series[0].data =data;
        var chart = new Highcharts.chart(optLineChartReportGananciaByMesDolares_);
        chart.setTitle({ text:'Ganancia x meses Dolares - '+$("#selAnio2").val()});
    });

}


function iniDataChartGanaciaByMesADiaDolares_(mes) {
    $.post(url_base+"admin/chartGanaciaByMesesXDiaDolares",{"anio":$("#selAnio2").val(),mes:mes},function (data) {

        //console.log(data);
        var data=JSON.parse(data);
        optLineChartReportGananciaByMesDolares_.series[0].data =data;
        optLineChartReportGananciaByMesDolares_.xAxis.title.text="Dia";
        var chart = new Highcharts.chart(optLineChartReportGananciaByMesDolares_);
        chart.setTitle({ text:'Ganancia dolares del mes  '+mes+'-'+$("#selAnio2").val()+'<a href="javascript:void(0)" style="font-size: 9px;" onclick="iniDataChartGanaciaByMesDolares_()" class="btn btn-link"  >Atrás</a>' ,useHTML:true});
    });
}
function iniLineChartReportGanaciaByMesDolares_() {

    optLineChartReportGananciaByMesDolares_ ={
        chart: {
            renderTo: 'chartMesesDolares',
            type: 'line',

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
                text: 'Mes'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Soles'
            }
        },
        legend: {
            enabled: true
        },
        tooltip: {
            pointFormat: '<b> S/{point.y:.2f}  </b>',
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
                            // console.log(this.name);
                            iniDataChartGanaciaByMesADiaDolares_(this.name);
                        }
                    }
                }
            }
        },
        series: [{
            name: 'Mes',
            dataLabels: {
                allowOverlap: true,
                enabled: true,
                //rotation: -90,
                color: '#FFFFFF',
                align: 'left',
                format: 'S/{point.y:.2f}', // one decimal 1f
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
        colors: ['purple']
    }
}

</script>





<div class="modal fade" id="modalId" role="dialog" tabindex="-1"  data-backdrop="static"   aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="hModalTitle"> </h4>
            </div>

            <!--Modal body-->
            <div class="modal-body" id="bModalBody">


            </div>
        </div>
    </div>
</div>
