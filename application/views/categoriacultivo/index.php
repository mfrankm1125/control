<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Categoria de Cultivo</h3>
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

                        <th>Nombre</th>

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
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body">
                <form class="panel-body form-horizontal form-padding" id="formActop">
                    <input type="hidden" name="isEdit" id="isEdit" value="0">
                    <input type="hidden" name="idEdit" id="idEdit" value="0">
                    <!--Text Input-->

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Categoria de Cultivo</label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-9">
                            <a href="javascript:void(0)" onclick="btnSave(1)" type="button"   class=" btn btn-success ">
                                Guardar y terminar
                            </a>

                            <button type="button" id="btnCancel"  data-dismiss="modal" class=" btn btn-danger ">
                                Cancelar
                            </button>

                        </div>
                    </div>
                </form>


            </div>

        </div>
    </div>
</div>

<?php echo $_js?>
<?php echo $_css?>
<script type="text/javascript">
    var titles={'addtitle':"Nueva Actividad Operativa" ,'updatetitle':"Editar Actividad Operativa"};
    var isEdit=null,idEdit=null;
    $(document).ready(function () {

        var rowSelection = $('#tabla_grid').DataTable({
            "ajax": url_base+'categoriacultivo/getDataTable',
            "columns": [
                { "data": null },
                {  "render": function ( data, type, full, meta ) {
                        var name = full.nombre;
                        //var lastname = full.apellidos;
                        var html=""+name;
                        return html;
                    }
                },
                {  sortable: false,
                    "render": function ( data, type, full, meta ) {
                        var id = full.idcategoriacultivo;
                        html="";
                        //html='<a href="javascript:void(0)"  onclick="ver('+id+');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>';
                        //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                        html=html+'&nbsp; <a href="javascript:void(0)" onclick="editar('+id +');"  class="btn btn-mint btn-icon btn-xs"><i class="demo-psi-pen-5 icon-xs"></i> Editar</a>';
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


    });
    function refrescar () {
        var rowSelection = $('#tabla_grid').DataTable();
        rowSelection.ajax.reload();
    }

    $(document).on('click','#btnAdd',function () {
        $("#isEdit").val(0);
        $("#idEdit").val(0);
        $("#formActop")[0].reset();
        $('#modalTitle').html(titles['addtitle']);
        open_modal('modal_id');


        //alert(isActividad);
        //console.log();
    });

    function validateForm() {
        var bol=true;
        bol=bol&& $('#name').required();
        return bol;
    }




    function btnSave(id){
        var idd=parseInt(id);
        var bol=validateForm();
        var status=null;

        console.log($('#formActop').serialize());

        //bol=false;
        if(bol){

            var dataForm=$('#formActop').serialize();
            $.post(url_base+'categoriacultivo/setForm',dataForm,function (data) {
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

        }
    }

    function  editar(id){
        $('#btnSave2').remove();
        isEdit=1;

        var idd=parseInt(id);
        $.post(url_base+'categoriacultivo/getData','id='+idd,function (data) {
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
            $("#idEdit").val(data.idcategoriacultivo);
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
            $.post(url_base+'categoriacultivo/deleteData','id='+idd,function (data) {
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

</script>
