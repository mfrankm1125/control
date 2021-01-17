<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><?=$data['titulo']?></h3> 
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body">
                <br>
                <div class="table-toolbar-left">
                    <button class="btn btn-purple" id="add"><i class="demo-pli-add icon-fw"></i> Agregar</button>

                    <div class="btn-group">
                        <button class="btn btn-default" id="refresh"  ><i class="demo-pli-refresh" ></i>Refrescar</button>
                     
                    </div>
                </div>
                <br><br> <br>
                <!--CUerpot aqui -->
                <h1></h1>
                <table id="tabla_grid" class="table table-hover table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr><th>#</th>
                        <th>Perfil</th>
                        <th>accion</th>

                    </tr>
                    </thead>
                    <tbody id="tabla_body">

                    </tbody>
                </table>
            </div>
            <!--===================================================-->
            <!--End Data Table-->

        </div>
    </div>

</div>

<div class="modal fade"   id="modal_id" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="titulo_id"></h4>
            </div>


            <!--Modal body-->
            <div class="modal-body">
                <div class="tab-content">
                    <div class="tab-pane pad-btm fade in active" id="formx">
                        <input type="hidden" id="op" name="op" value="0" />
                        <input type="hidden" id="id_up" name="id_up" value="" />
                        <div class="form-group has-feedback">
                            <label class="col-lg-3 control-label">Descripción :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" name="perfil" id="perfil" placeholder="perfil" data-bv-field="fullName" autofocus><i class="form-control-feedback" data-bv-icon-for="fullName" style="display: none;"></i>
                                <small class="help-block" data-bv-validator="notEmpty" data-bv-for="fullName" data-bv-result="NOT_VALIDATED" style="display: none;">El perfil es requerido</small></div>
                        </div>
                        <!--<div class="form-group has-feedback">
                            <label class="col-lg-3 control-label">Company</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" name="company" placeholder="Company" data-bv-field="company"><i class="form-control-feedback" data-bv-icon-for="company" style="display: none;"></i>
                                <small class="help-block" data-bv-validator="notEmpty" data-bv-for="company" data-bv-result="NOT_VALIDATED" style="display: none;">The company name is required</small></div>
                        </div>-->

                    </div>


                </div>
            </div>


            <!--Modal footer-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button" id="cancelar" >Cancelar</button>
                <button class="btn btn-primary" id="guardar">Guardar</button>
            </div>
        </div>
    </div>
</div>

<?php echo $_js?>
<?php echo $_css?>
<script type="text/template" id="cuerpo_template">
    <% _.each(data, function(i,k){  %>
    <tr>
        <td><%=k+1%></td>
        <td><%=i.role%></td>
        <td><a href="javascript:void(0)"  onclick="editar('<%=i.id%>');" class="btn btn-mint btn-xs">
                <i class="demo-psi-pen-5 icon-lg"></i>
            </a>
        </td>
    </tr>
    <% }) %>
</script>

<script type="text/javascript">
    function load_perfil(){
        //$.post(url_base+'perfiles/perfiles_json',function (data) {
        //console.log(data);

        //t = _.template($("#cuerpo_template").html());
        //$("#tabla_body").html(t({data:data}));

        //},'json');
    }

    $(document).ready(function () {
        //load_perfil();

    });





    $(document).on('click','#refresh',function () {
        //load_perfil();
        refrescar();
       // alert('hola');
    });

    $(document).on('click','#add',function () {
        $('#op,#id_up,#perfil').val("");
        $("#titulo_id").html("Agregar Perfíl");
        $("#modal_id").modal("show");
    });

    $(document).on('click','#guardar',function () {
        var id=$("#id_up").val().trim();
        var desc=$("#perfil").val().trim();
        var op=$("#op").val().trim();
        var val=true;
        val = val && $("#perfil").required();
        var datos ={'id':id,'op':op,'desc':desc};

       // alert(datos['desc']);
        if(val){
                if(op == 0 || op == ''){ save_p(datos); }
                if(op == 1){ save_p(datos); }
                $('#modal_id').modal('hide');

        }else{
            alert_error('Rellene los camposs');
        }
    });

    function editar(id){
        var op=$("#op").val();
            $('#modal_id').modal('show');
            $.post(url_base + 'perfiles/update_data', 'id=' + id, function (data) {
                $('#op').val('1');
                $('#id_up').val(data[0].id);
                $('#perfil').val(data[0].role);
            }, 'json');
    }

    function eliminar(id){
        if(confirm("Esta seguro que desea eliminar este registro")){
            $.post(url_base+'perfiles/delete','id='+id,function(data){},'json');
            alert_success('Se elimino correctamente');
            refrescar();
        }
        else{return false;}
    }

    function save_p(datos){
        $.post(url_base+'perfiles/add','descripcion='+datos['desc']+'&id='+datos['id']+'&op='+datos['op'],function(data){},'json');
        alert_success('Se inserto correctamente');
        refrescar();
    }


</script>