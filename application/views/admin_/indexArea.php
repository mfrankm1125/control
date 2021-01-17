<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title" style="font-size: 18px" >Planes Operativos Institucionales </h3>
            </div>
            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-bottom: 15px;" id="divBodyListPoi">
                <!--CUerpot aqui -->


                Cargando espere por favor...

            </div>
            <!--===================================================-->
            <!--End Data Table-->

        </div>
    </div>

</div>


<div   id="modalAlert" class="modal fade" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <div class="panel-control">
                        <button class="btn btn-default" data-dismiss="modal"><i class="demo-pli-cross"></i></button>
                    </div>

                    <h1 class="panel-title" style="font-size: 18px;text-align: center;" >Nuevo Boletin</h1>
                </div>
                <div class="panel-body">
                    <b id="titleAltert" style="font-size: 20px;" >...</b>
                    <p id="msgAlert" style="font-size: 17px">...</p>
                </div>
            </div>
        

            <div class="modal-footer">
                <button  type="button" data-dismiss="modal" class="btn btn-danger" >Cerrar</button>
            </div>
        </div>
    </div>
</div>
<?=$_css?>
<?=$_js?>

<script type="text/template" id="tmpPoi">
    <%  _.each(data,function(i,k){ %>


    <div class="col-sm-6 col-lg-2">
        <!--Tile-->
        <!--===================================================-->
        <div class="panel">
            <%
            var btn="";
            var ht="";
            if(i.isvisible == 1 ) {
            btn="bg-danger";
            ht="verxArea("+i.idactividad+","+i.periodo+")";
            }else{
            btn="bg-dark";
            }%>
            <div style="cursor:pointer" onclick="<%=ht%>"  class="panel-body text-center <%=btn%>"   >
                <p class="" style="color: white"><u>Periodo</u></p>
                <p class="h1  text-semibold mar-no" style="color: white"><%=i.periodo%></p>
            </div>
            <div class="pad-all text-center">
                <p class="text-semibold text-lg mar-no text-main">
                    Estado:
                    <%
                    var label="";
                    var spantitle="";
                    if(i.isvisible == 1 ) {
                    label="label-success";
                    spantitle="Visible"
                    }else{
                    label="label-dark";
                    spantitle="No visible"
                    }%>
                    <a  href="javascript:void(0)" class="label <%=label%>">
                        <%=spantitle%>
                    </a>
                </p>
                <p class="h3 text-thin mar-no"></p>
                     
                    <!--<p class="text-sm text-overflow pad-top">
                        <a onclick="editar('<%=i.idactividad%>')" class="btn btn-sm btn-mint btn-rounded"><i class="fa fa-pencil"></i> Editar</a>
                        <a onclick="eliminar('<%=i.idactividad%>')" class="btn btn-sm btn-danger btn-rounded"><i class="fa fa-trash-o"></i> Eliminar</a>
                    </p>-->
            </div>
        </div>
        <!--===================================================-->

    </div>

    <%  });  %>
</script>

<script type="text/template" id="tmpEvalua">

</script>

<script type="text/javascript">
    $(document).on("ready",function () {
        $.post(url_base+"admin/loadBoletin",function (data) {
            if(data.length > 0){
                $("#titleAltert").html(""+data[0].titulo);
                $("#msgAlert").html(data[0].descripcion);
                open_modal("modalAlert"); // console.log(data);
            }
        },'json');
        loadListPoi();
    });

    function loadListPoi() {
        var tmpPoi=_.template($("#tmpPoi").html());
       $.post(url_base+"Actasig/indexActAsing",function (datax) {
           $("#divBodyListPoi").html(tmpPoi({data:datax.data}));

       },'json');
    }


    function eliminar(id) {
        if(confirm('¿Esta seguro de eliminar el Plan operativo Se perdera toda la informacion?')){
            var idd=parseInt(id);
            $.post(url_base+'poi/deleteData','id='+idd,function (data) {
                console.log(data);
            },'json').success(function () {
                alert_success('Se ejecuto Correctamente');
                refrescar();
            });
        }
    }

    function  editar(id){
        $('#btnSave2').remove();
        isEdit=1;

        var idd=parseInt(id);
        $.post(url_base+'poi/getData','id='+idd,function (data) {
                console.log(data);
                setForm(data[0]);
                //$('#modalTitle').html();
            },'json')
            .always(function () {

            });

    }

    function changeStatus(id,status) {

        $.post(url_base+'Poi/changeStatus',{"id":id,"status":status},function (data) {
                console.log(data);
                loadListPoi();
            },'json')
            .always(function () {

            });
    }
    function verxArea(id,periodo) {
        window.location.href =url_base+"actasig/form/"+id+"/"+periodo;
    }

</script>