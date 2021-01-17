<style type="text/css" >
    body {
        background: white;

    }
    #chart {
        width: 100%;
        height: 289px;
        margin: 30px auto 0;
        display: block;
    }
    #chart #numbers {
        width: 10%;
        height: 289px;
        margin: 0;
        padding: 0;
        display: inline-block;
        float: left;
    }
    #chart #numbers li {
        text-align: right;
        padding-right: 1em;
        list-style: none;
        height: 29px;
        border-bottom: 1px solid #444;
        position: relative;
        bottom: 30px;
    }
    #chart #numbers li:last-child {
        height: 30px;
    }
    #chart #numbers li span {
        color: black;
        position: absolute;
        bottom: 0;
        right: 10px;
    }
    #chart #bars {
        display: inline-block;
        background:white;
        width: 90%;
        height: 289px;
        padding: 0;
        margin: 0;
        box-shadow: 0 0 0 1px #444;
    }
    #chart #bars li {
        display: table-cell;
        width: 50px;
        height: 289px;
        margin: 0;
        text-align: center;
        position: relative;
    }
    #chart #bars li .bar {
        display: block;
        width: 80%;
        margin-left: 15px;
        background: #49E;
        position: absolute;
        bottom: 0;
    }
    #chart #bars li .bar:hover {
        background: #5AE;
        cursor: pointer;
    }

    #chart #bars li span {
        color: black;
        width: 100%;
        position: absolute;
        bottom: -2em;
        left: 0;
        text-align: center;

    }


</style>



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
                Cargando...  espere por favor ...
            </div>
        </div>
    </div>
</div>
<?php
$mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
echo $_css?>
<?php echo $_js?>
<script type="text/template" id="tmp">
    <h3><%=data[0].nombrearearesponsable%>-<b id="" > <%=redondea(ga*100)%> % </b></h3>

    <%
    var cantidadActOp=0;
    var sumatotalActOp=0;
    _.each(data,function(i,k){

    %>
         <h4>*<%=i.accestrategica%></h4><br>
              <% _.each(i.dataactpre,function(ii,kk){ %>
                    Act Pre :<%=ii.actpre%><br>
                        <%  _.each(ii.dataactop,function(iii,kkk){
                                cantidadActOp++;
                            %>


                                <table class="table table-bordered table-condensed" >
                                    <thead>
                                    <tr>
                                        <th >Act. Op </th>
                                        <td colspan="5"><%=iii.actop%></td>
                                    </tr>
                                    <tr>
                                        <th >#</th>

                                        <th>Tarea</th>     <!--<th>M. de <br>Verificacion</th>-->
                                        <th>UM</th>
                                        <th>Ejecutado</th>
                                        <th>Meta</th>
                                        <th align="center">%</th>
                                        <!--<th>Obs.</th> -->

                                    </tr>
                                    </thead>
                                    <%
                                        var sumatotaltareas=0;
                                        var counttareas=0;
                                    _.each(iii.datatarea,function(j,v){
                                    var gaini=j.ga[0].porcenajeFinalejecAll;//porcenajeFinal
                                    var ga=(parseFloat(gaini)*100).toFixed(2);
                                    counttareas++;
                                    sumatotaltareas=sumatotaltareas+gaini;
                                    %>
                                    <tr>
                                        <td style="text-align: left" ><%=(v+1)%></td>

                                        <td style="text-align: left" ><%=j.tarea%></td>
                                        <td style="text-align: left" ><%=j.um%></td>
                                        <td style="text-align: right" ><%=j.ga[0].ejecAll%></td>
                                        <td style="text-align: right" ><%=j.ga[0].metax%></td>
                                        <td style="text-align: right" ><%=ga%></td>
                                    </tr>
                                    <% });
                                    var TotalActOpTareas=parseFloat(sumatotaltareas/counttareas)||0;
                                        sumatotalActOp=sumatotalActOp+TotalActOpTareas;
                                    %>

                                    <tr>
                                        <td style="text-align: right" colspan="5" ><b>Total</b></td>
                                        <td style="text-align: right"  ><%=redondea(iii.gaop*100) %> %</td>
                                    </tr>

                                </table>

    <div id="chart">
        <ul id="numbers">
            <li><span>100%</span></li>
            <li><span>90%</span></li>
            <li><span>80%</span></li>
            <li><span>70%</span></li>
            <li><span>60%</span></li>
            <li><span>50%</span></li>
            <li><span>40%</span></li>
            <li><span>30%</span></li>
            <li><span>20%</span></li>
            <li><span>10%</span></li>
            <li><span>0%</span></li>
        </ul>

        <ul id="bars">
            <%

            _.each(iii.datatarea,function(j,v){
            var gaini=redondea(j.ga[0].porcenajeFinalejecAll*100);

            counttareas++;
            sumatotaltareas=sumatotaltareas+gaini;

            %>

            <li><div data-percentage="<%=gaini%>" class="bar" style="color: white;height: <%=gaini%>% ;"> <p style="color:black; position: relative; bottom: 15px;"><b><%=gaini%></b></p> </div><span style="text-align:right ;padding-right: 11px;"> <b><%=(v+1)%></b> </span></li>



            <%
            }); %>

        </ul>
    </div>
    <br>
    <br>
                        <% });%>

               <% });%>

    <% });
      var gradoavancearea= parseFloat(sumatotalActOp/cantidadActOp)||0;
    gradoavancearea=(parseFloat(gradoavancearea)*100).toFixed(2);

    %>

    <input type="hidden" id="avancearea" value="<%=gradoavancearea%>">


</script>
<script type="text/javascript">
    var optionsChart;
    var dataR=[<?=$dataR?>];
    var mes=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

    $(document).on("ready",function () {
        console.log(dataR);
        var t=_.template($("#tmp").html());
        $("#bodyReportTable").html(t({data:dataR[0].data,ga:dataR[0].gaArea }));
        var gatitle=$("#avancearea").val();
        $("#divGA").html(gatitle);
        //console.log(dataR[0].data);
        //Data=[['xxx', 23.7]];

    });
 </script>