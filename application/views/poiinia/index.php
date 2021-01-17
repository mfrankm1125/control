<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Plan Operativo Institucional INIA</h3>
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

                        <th>Año</th>

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
    <div class="modal-dialog modal-lg"  style="width: 90%;">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divFormPoi">

            </div>
        </div>
    </div>
</div>

<?php echo $_js?>
<?php echo $_css?>
<script type="text/javascript">
    var titles={'addtitle':"Nuevo Plan Operativo" ,'updatetitle':"Editar tipo de animal"};
    var isEdit=null,idEdit=null;

    $(document).ready(function () {
        dataTableX();
    });

    function dataTableX() {
        var rowSelection = $('#tabla_grid').DataTable({
            "ajax": url_base+'poiinia/getDataTable',
            "columns": [
                { "data": null },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.anio;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  sortable: false,
                    "render": function ( data, type, full, meta ) {
                        var id = full.anio;
                        html="";
                        //html='<a href="javascript:void(0)"  onclick="ver('+id+');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>';
                        //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                        html=html+'&nbsp; <a href="javascript:void(0)" onclick="regejecucion('+id +');"  class="btn btn-dark btn-icon btn-xs"><i class="fa fa-eject"></i> Registrar Ejecución</a>';

                       // html=html+'&nbsp; <a href="javascript:void(0)" onclick="editar('+id +');"  class="btn btn-mint btn-icon btn-xs"><i class="demo-psi-pen-5 icon-xs"></i> Editar</a>';
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
        var tmpDivFormPoi=_.template($("#tmpDivFormPoi").html());
        $("#isEdit").val(0);
        $("#idEdit").val(0);

        $('#modalTitle').html("");
        open_modal('modal_id');
        $("#divFormPoi").html(tmpDivFormPoi);

        //alert(isActividad);
        //console.log();
    });

    function validateForm() {
        var bol=true;
        bol=bol&& $('#name').required();
        return bol;
    }




    function btnSave(id){
        var bol=true;
        bol=bol&&$("#aniopoi").required();
        var aniopoi= ($("#aniopoi").val()).trim();
        var cTrTable = $('#tbodyDetailPOI tr').length;
        var btn=$("#btnSaveRegPoi");
        if(bol == false){ return; }
        if(cTrTable == 0){
           alert_error("Ingrese las actividades en la tabla presione el boton '+'");
           return;
        }

        var form=$("#formPOI").serialize();
        console.log(form);
        btn.button("loading");
        $.post(url_base+'poiinia/setFormP',form,function (data) {

            if(data.status === "ok"){
                alert_success("Se ejecuto correctamente ...");
                close_modal('modal_id');
                refrescar();
            }else{
                alert_error("Error");
            }
            btn.button("reset");
        },'json');

        //bol=false;
       /* if(bol){
            var dataForm=$('#formActop').serialize();
            $.post(url_base+'tipoanimal/setForm',dataForm,function (data) {
                console.log(data);
                if(data.status === "ok"){
                    alert_success("Se ejecuto correctamente ...");
                    close_modal('modal_id');
                    refrescar();
                }else{
                    alert_error("Error");
                }
            },'json');
        }else{

        } */
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
    function ver(id){

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
            $.post(url_base+'poiinia/deleteData','id='+idd,function (data) {
                if(data.status == 'ok'){
                    alert_success('Se realizo correctamente');
                    refrescar();
                }else{
                    alert_error('Error');
                }
                console.log(data);
            },'json');
        }
    }


    $(document).on("click","#btnAddDetailPoi",function () {
        var actop=$("#descactop").val();
        var arearesp=$("#descarearesponsable").val();
        var um=$("#descum").val();
        var meta=$("#descmeta").val();
        var aniop=$("#aniopoi").val();
        var bol=true;
        var btn=$(this);
        bol=bol&&$("#descactop").required();
        bol=bol&&$("#descarearesponsable").required();
        bol=bol&&$("#descum").required();
        bol=bol&&$("#descmeta").required();
        if(bol){

            btn.button("loading");
            if($("#isEdit").val() == 1 ){
                var dt="actop="+actop+"&arearesp="+arearesp+"&um="+um+"&meta="+meta+"&anio="+aniop;
                $.post(url_base+'poiinia/setDataDetail',dt,function (data) {
                    if(data.status == "ok" ){
                        var tmpTrTbodyPOI =_.template($("#tmpTrTbodyPOI").html());
                        $("#divTableDetailPOI").html(tmpTrTbodyPOI({data:data.data}));
                    }else{
                        alert_error('Error');
                    }
                    btn.button("reset");
                    //console.log(data);
                },'json');
            }else{
                var trr=" ";
                trr=trr+"<tr><td> <input type='hidden' name='actop[]' value='"+actop+"' > "+actop+"</td>" +
                    "<td>  <input type='hidden' name='arearesp[]' value='"+arearesp+"' >    "+arearesp+"</td>" +
                    "<td> <input type='hidden' name='um[]' value='"+um+"' >  "+um+"</td>" +
                    "<td> <input type='hidden' name='meta[]' value='"+meta+"' >   "+meta+"</td>" +
                    "<td> <input type='text' class='form-control' name='metaalcanzada[]' value='0' >  </td>" +
                    "<td><button onclick='deleteDetailPOI(this,0,0)'  type='button' value='-' class='eliminart btn  btn-danger btn-xs' > <i class='fa fa-trash-o'></i></button></td></tr>";
                $("#tbodyDetailPOI").append(trr);
                btn.button("reset");
            }
            $("#descactop").val("");
            $("#descarearesponsable").val("");
            $("#descum").val("");
            $("#descmeta").val("");

        }else{
            return 0;
        }




    });

    $(document).on('click', '.eliminartdd', function() {
        var objCuerpo = $(this).parents().get(2);
        if ($(objCuerpo).find('tr').length == 1) {
            if (!confirm('Esta es el única fila de la lista ¿Desea eliminarla?')) {
                return;
            }
        }
        var objFila = $(this).parents().get(1);
        $(objFila).remove();
    });

    function regejecucion(id) {
        open_modal("modal_id");
        $("#divFormPoi").html("Cargando...");
        $.post(url_base+'poiinia/getData','id='+parseInt(id),function (data) {
            if(data.length > 0 ){
                var tmpDivFormPoi =_.template($("#tmpDivFormPoi").html());
                var tmpTrTbodyPOI =_.template($("#tmpTrTbodyPOI").html());
                $("#divFormPoi").html(tmpDivFormPoi);
                $("#divTableDetailPOI").html(tmpTrTbodyPOI({data:data}));
                $("#aniopoi").val(data[0].anio);
                $("#idEdit").val(data[0].anio);
                $("#isEdit").val(1);
            }else{
                alert_error('Error');
            }
            console.log(data);
        },'json');
    }
    
    function deleteDetailPOI(thisx,status,id) {
        if(!confirm("Esta seguro de eliminar este registro?")){
            return;
        }
        if(status > 0){
           // console.log(id);
            $.post(url_base+'poiinia/deleteDetailPOI','id='+parseInt(id),function (data) {
                if(data.status == "ok" ){
                    var objFila = $(thisx).parents().get(1);
                    $(objFila).remove();
                }else{
                    alert_error('Error');
                }
                console.log(data);
            },'json');
        }else{
            var objCuerpo = $(thisx).parents().get(2);
            if ($(objCuerpo).find('tr').length == 1) {
                if (!confirm('Esta es el única fila de la lista ¿Desea eliminarla?')) {
                    return;
                }
            }
            var objFila = $(thisx).parents().get(1);
            $(objFila).remove();
            console.log(objFila);
        }
    }


</script>
<script type="text/template" id="tmpTrTbodyPOI">
    <table class="table  table-condensed table-bordered table-responsive table-hover">
        <thead>
        <tr>
            <th>Actividad Operativa</th>
            <th>Area responsable</th>
            <th>U.M</th>
            <th>Meta Anual</th>
            <th>Meta Alcanzada</th>
            <th>*</th>
        </tr>
        </thead>
        <tbody id="tbodyDetailPOI">
        <% _.each(data,function(i,k){ %>
        <tr>
            <td><input type="hidden" name="idpoi[]" value="<%=i.idpoi%>"><%=i.actop%></td>
            <td><%=i.arearesponsable%></td>
            <td><%=i.um%></td>
            <td><%=i.metaanual%></td>
            <td><input class="form-control" type="text" style="text-align: right;margin: 0;" name="metaalcanzada[]" value="<%=i.metaalcanzada%>"> </td>
            <td> <button type='button' value='-' onclick="deleteDetailPOI(this,1,'<%=i.idpoi%>')" class='eliminart btn  btn-danger btn-xs' > <i class='fa fa-trash-o'></i></button></td>
        </tr>
        <% })%>
        </tbody>
    </table>
</script>


<script type="text/template" id="tmpDivFormPoi">
    <form id="formPOI">
        <input type="hidden" name="idEdit" id="idEdit" value="0">
        <input type="hidden" name="isEdit" id="isEdit"  value="0">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-4" style="">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="demo-hor-inputemail">Año: </label>
                        <div class="col-sm-9">
                            <input type="text"  id="aniopoi" name="aniopoi" class="form-control"><br>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label">Act. Operativa</label>
                        <input type="text" name="descactop" id="descactop" class="form-control">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label">Area Responsable</label>
                        <input type="text" name="descarearesponsable" id="descarearesponsable" class="form-control">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label">U.M</label>
                        <input type="text" name="descum" id="descum" class="form-control">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label">Meta Anual</label>
                        <input type="text" name="descmeta" id="descmeta" class="form-control">
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-group">
                        <br>
                        <button class="btn btn-success btn-rounded" id="btnAddDetailPoi" type="button">+</button>
                    </div>
                </div>
            </div>

        </div>
        <div class="row" id="divTableDetailPOI">
            <table class="table  table-condensed table-bordered table-responsive table-hover">
                <thead>
                <tr>
                    <th>Actividad Operativa</th>
                    <th>Area responsable</th>
                    <th>U.M</th>
                    <th>Meta Anual</th>
                    <th>Meta Alcanzada</th>
                    <th>*</th>
                </tr>
                </thead>
                <tbody id="tbodyDetailPOI">

                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="form-group">
                <br>
                <div class="col-md-12" style="text-align: center">
                    <a href="javascript:void(0)" onclick="btnSave(1)" type="button" id="btnSaveRegPoi"  class=" btn btn-success ">
                        Guardar y terminar
                    </a>
                    <button type="button" id="btnCancel"  data-dismiss="modal" class=" btn btn-danger ">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </form>
</script>