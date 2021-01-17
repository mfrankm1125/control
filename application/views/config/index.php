<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Configuración</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="" >
                <div class="row" >
                    <div class="col-lg-12">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="demo-is-inputsmall">Valor Recria</label>
                                <div class="col-sm-4">
                                    <input type="text"  style="text-align: right" class="form-control input-sm" id="inrecria">
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-sm btn-success" onclick="saveVal('recria')" ><b>Guardar</b></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="demo-is-inputsmall">Precio Litro Leche:</label>
                                <div class="col-sm-4">
                                    <input type="text"  style="text-align: right"  class="form-control input-sm" id="inprecioleche">
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-sm btn-success" onclick="saveVal('precioleche')" ><b>Guardar</b></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--
                <b style="font-size: 16px">Archivos</b><hr style="margin-top: 0px;">

                <div class="row">
                    <div class="col-sm-10">
                        <div class="form-group">
                            <table class="table table-condensed table-hover ">
                                <thead>
                                <tr>
                                    <th style="width: 350px;" >Manual de usuario</th>
                                    <th style="" >Última Actualización</th>
                                    <th style="" >Acción</th>
                                </tr>
                                </thead>
                                <tbody id="tbodyManualUsuario">
                                <tr>
                                    <td colspan="2"><b>Cargando...</b></td>
                                 </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                -->
            </div>
            <!--===================================================-->
            <!--End Data Table-->

        </div>
    </div>

</div>

<script type="text/template" id="tmpTbodyFormatoInforme">
    <tr>
        <td> <a href="<?=base_url()?>assets/uploads/<%=data.url%>" class="btn-link" target="_blank">Click para ver documento</a> </td>
        <td><%=data.fechaupdate%></td>
        <td> <input  style="display: inline-block" id="inFormatoInforme"  type="file" class="">
            <button style="display: inline-block" id="btnActFormatoInforme" type="button" class="btn btn-mint btn-sm" >
                Actualizar
            </button>
            <div class="form-group"  id="divprogressBar1" style="display: none;" >
                <label class="col-sm-2 control-label" style="text-align: right"></label>
                <div class="col-sm-5">
                    <div class="progress" >
                        <div id="progressBar"  style="width: 0%;font-size:12px" class="progress-bar progress-bar-info"></div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</script>


<script type="text/template" id="tmpTbodyFormatoInformeJustify">
    <tr>
        <td> <a href="<?=base_url()?>assets/uploads/<%=data.url%>" class="btn-link" target="_blank">Click para ver documento</a> </td>
        <td><%=data.fechaupdate%></td>
        <td> <input  style="display: inline-block" id="inFormatoInformeJustify"  type="file" class="">
            <button style="display: inline-block" id="btnActFormatoInformeJustify" type="button" class="btn btn-mint btn-sm" >
                Actualizar
            </button>
            <div class="form-group"  id="divprogressBar1" style="display: none;" >
                <label class="col-sm-2 control-label" style="text-align: right"></label>
                <div class="col-sm-5">
                    <div class="progress" >
                        <div id="progressBar"  style="width: 0%;font-size:12px" class="progress-bar progress-bar-info"></div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</script>

<script type="text/template" id="tmpTbodyManualUsuario">
    <tr>
        <td> <a href="<?=base_url()?>assets/uploads/<%=data.url%>" class="btn-link" target="_blank">Click para ver documento</a> </td>
        <td><%=data.fechaupdate%></td>
        <td> <input  style="display: inline-block" id="inManualUsuario"  type="file" class="">
            <button style="display: inline-block" id="btnActManualUsuario" type="button" class="btn btn-mint btn-sm" >
                Actualizar
            </button>
            <div class="form-group"  id="divprogressBar2" style="display: none;" >
                <label class="col-sm-2 control-label" style="text-align: right"></label>
                <div class="col-sm-5">
                    <div class="progress" >
                        <div id="progressBar"  style="width: 0%;font-size:12px" class="progress-bar progress-bar-info"></div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</script>

<?php echo $_css?>
<?php echo $_js?>

<script type="text/javascript">
$(document).on("ready",function () {
    getValPrecioLeche();
    getValRecria();
});
function getValPrecioLeche(){
    $.post(url_base+"Config/getValPrecioleche",function (data) {
        //console.log(data);
        $("#inprecioleche").val(data);
    },'json');
}
function getValRecria(){
    $.post(url_base+"Config/getValRecria",function (data) {
        //console.log(data);
        $("#inrecria").val(data);
    },'json');
}
function saveVal(op){
    if(op =="recria"){
        var opx=op;
        var val=$("#inrecria").val() || 0;
        var datasend={"op":opx,"val":val};
        setvalue(datasend);
    }else if(op == "precioleche"){
        var opx=op;
        var val=$("#inprecioleche").val() || 0;
        var datasend={"op":opx,"val":val};
        setvalue(datasend);
    }else{
        return false;
    }
}

function setvalue(datasend) {
    $.post(url_base+"Config/setvalue",datasend,function (data) {
        //console.log(data);
        if(data.status == "ok"){
            alert_success("Se Realizo correctmante");
            if(datasend.op == "recria" ){
                getValRecria();
            }else if(datasend.op == "precioleche"){
                getValPrecioLeche();
            }
        }
    },'json');

}
</script>


