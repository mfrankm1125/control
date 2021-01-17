<?php // print_r($data["perm"]);
$add=in_array('agregar',$data["perm"]);
$edit=in_array('editar',$data["perm"]);
$see=in_array('ver',$data["perm"]);
$delete=in_array('eliminar',$data["perm"]);
$export=in_array('exportar',$data["perm"]);
$refresh=in_array('refrescar_tabla',$data["perm"]);
?>
<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-control">
                    <button class="add-tooltip  btn btn-default btn-lg"  data-toggle="tooltip" data-container="body" data-placement="left" data-original-title="Representa los datos de analisis de suelos"  ><i class="fa fa-question-circle-o"></i></button>

                </div>
                <h3 class="panel-title"><?=$data['titulo']?></h3>
            </div>

            <div class="panel-body" style="padding-top: 5px;" >
                <div class="table-toolbar-left">
                    <?php if($add){ ?>
                    <button class="btn btn-purple" id="addx"><i class="demo-pli-add icon-fw"></i>Agregar</button>
                    <?php }
                    if($refresh){ ?>
                    <button class="btn btn-default" onclick="refrescar()"><i class="demo-pli-refresh"></i>Refrescar</button>
                    <?php }?>
                </div>
                <br><br> <br>


                        <table id="tabla_grid" class="table table-hover table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr><th>#</th>
                                <th>Módulo</th>
                                <th>Url</th>
                                <th class="min-tablet">Módulo Padre</th>
                                <th class="min-tablet">Ícono</th>
                                <th class="min-desktop">Orden</th>
                                <th class="min-desktop">Acción</th>
                            </tr>
                            </thead>
                            <tbody id="table_body">

                            </tbody>
                        </table>

            </div>
        </div>
    </div>


</div>

<!--MOdal -->
<div class="modal fade"   id="modal_id" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="titulo_mod"></h4>
            </div>


            <!--Modal body-->
            <div class="modal-body">
                <div class="bootbox-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-horizontal" id="formAddModulo">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="name">Módulo Padre</label>
                                    <input type="hidden" id="id_edit_m" name="id_edit_m" value="">
                                    <div class="col-md-4">
                                        <select   id="sel_m" name="sel_m" data-placeholder="Seleccione" title="Seleccione"     >
                                            <option value="">Seleccione...</option>
                                            <?php foreach($data['sel_modp'] as $sel_m):?>
                                            <option value="<?=$sel_m['id_modulo']?>"><?=$sel_m['nombre']?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="name">Nombre:</label>
                                    <div class="col-md-4">
                                        <input id="nombre" name="nombre" type="text" placeholder="nombre" class="form-control input-md">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="name">Url:</label>
                                    <div class="col-md-4">
                                        <input id="url" name="url" type="text" placeholder="url" class="form-control input-md">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="name">Ícono:</label>
                                    <div class="col-md-4">
                                        <input id="icono" name="icono" type="text" placeholder="ícono" class="form-control input-md" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="name">Orden:</label>
                                    <div class="col-md-4">
                                        <input id="orden" name="orden" type="number" placeholder="orden" class="form-control input-md">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="awesomeness">Seleccione acciones</label>

                                        <div class="col-md-4">
                                            <select class="selectpicker" id="sel_accion"  name="sel_accion" multiple title="Seleccione" data-width="100%">

                                                <?php foreach($data['sel_acciones'] as $sel_acc):?>
                                                    <option value="<?= $sel_acc['id_accion'] ?>"><?= $sel_acc['nombre'] ?></option>
                                                <?php endforeach;?>
                                            </select>
                                        
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            <!--Modal footer-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button" id="cancelar" >Cancelar</button>
                <button class="btn btn-primary" onclick="guardar_mod();">Guardar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade"   id="modal_ver" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="titulo_x">  </h4>
                <input type="hidden" name="id_mod" id="id_mod" value="">
            </div>

            <div class="modal-body">
                <!--<div class="tab-content fadeIn" id="add_perm">
                    <div class="tab-pane pad-btm fade in active" id="add_sel">
                    </div>
                </div>-->
                <div class="tab-content">
                    <div class="tab-pane pad-btm fade in active" id="perm_modul">
                    </div>
                </div>
            </div>
            <!--Modal footer-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button" id="cancelar" >Cancelar</button>
              </div>
        </div>
    </div>
</div>





<?php echo $_js?>
<?php echo $_css?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#sel_m').chosen({width:'100%'});
        var rowSelection = $('#tabla_grid').DataTable({
            "ajax": url_base+'modulos/getDataTable',
            "columns": [
                { "data": "id_modulo" },
                { "data": "modulo_hijo" },
                { "data": "url" },
                { "data": "modulo_padre" },
                { "data": "icono" },
                { "data": "orden" },
                {  sortable: false,
                    "render": function ( data, type, full, meta ) {
                        var id_x = full.id_modulo;
                        var namex = full.url;
                        var html='';
                        <?php if($edit){ ?>
                        html='<a href="javascript:void(0)"  onclick="editar('+id_x +');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                        <?php } if($delete){ ?>

                        html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar('+id_x +');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-lg"></i></a>';
                        <?php } ?>
                        html=html+'&nbsp; <a href="javascript:void(0)" onclick="permisos('+id_x +',\''+namex+'\');"  class="btn btn-warning btn-icon btn-xs"><i class="demo-psi-consulting icon-lg"></i>ver Acciones</a>';

                        return html;
                    }
                }

            ],
            "responsive": true,
            "language": {
                "search": "Buscar:",
                "paginate": {
                    "previous": '<i class="demo-psi-arrow-left"></i>',
                    "next": '<i class="demo-psi-arrow-right"></i>'
                }
            }
        });

    });

    function editar(id){
        $.post(url_base+'modulos/datos_edit','id='+id,function (data) {
                //alert(data[0].id_modulo_padre);
                $("#sel_m option").each(function(){
                    if($(this).val() == data[0].id_modulo_padre){ // EDITED THIS LINE
                        //alert(item.profile_id);
                        $(this).attr("selected","selected");
                        $('#sel_m').trigger("chosen:updated");
                    }
                });
            
                $('#nombre').val(data[0].nombre);
                $('#url').val(data[0].url);
                $('#orden').val(data[0].orden);
                $('#icono').val(data[0].icono);
                $('#id_edit_m').val(data[0].id_modulo);


                $.post(url_base+'modulos/datos_edit_acc','id='+data[0].id_modulo,function(datax){
                console.log(datax);
                    $.each(datax,function (i, item) {
                        $("#sel_accion option").each(function(){
                            if($(this).val() == item.id_accion){ // EDITED THIS LINE
                                //alert(item.profile_id);
                                $(this).attr("selected","selected");
                            $('#sel_accion').selectpicker('refresh');
                            }
                        });
                    })
                },'json');

        },'json');

        $('#modal_id').modal('show');
    }

    function eliminar(id){
        var idx=parseInt(id);
        alert(idx);
        $.post(url_base+'modulos/elimina','id='+idx,function (data) {
            console.log(data);
            refrescar();
        });

    }

</script>


<script type="text/template" id="t_body_table">
    <table class="table table-hover table-bordered" cellspacing="0" width="100%" >
     <thead>
        <tr><th>#</th>
            <th>Permiso</th>
            <th>Permisox</th>
            <th>estado</th>
            <th class="min-tablet">Accion</th>

        </tr>
    </thead>
    <% _.each(data, function(i,k){  %>
    <tr>
        <td><%=k+1 %></td>
        <td><%=i.nombre %></td>
        <td><%=i.url %></td>
        <td><%if(i.estado == 1){ %><span class="badge badge-success badge-icon badge-fw pull-left"></span><% } %></td>

        <td> <a href="javascript:void(0)" onclick="eli_perm('<%=i.id%>');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-lg"></i></a>
        </td>
    </tr>
    <% }) %>
</table>
</script>

<script type="text/template" id="t_sel_perm">

    <select id="sel_perm" data-placeholder="Agregar Permisos" multiple tabindex="4" >
        <% _.each(data, function(i,k){  %>
        <option value="<%=i.name%>"><%=i.name%></option>
        <%})%>
    </select>
    <button class="btn btn-success" id="add_permx">Agregar</button>

</script>