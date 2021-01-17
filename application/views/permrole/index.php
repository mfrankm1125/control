<style>
    .select-box {
        display: inline-block;
        list-style: none;
        margin: 0;
        background: #fff;
        padding: 5px;
        width: 40%;
        height: 200px!important;
        font-size: 12px
    }

    .select-box-option {
        position: absolute;
        left: 50%;
        top: 40%;
        margin-left: -50px;
        display: inline-block;
        height: 200px;
        width: 100px;
        text-align: center
    }

</style>

<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <?php //$edita=in_array('editar_modulos',$data["perm"]);
                //$delete=in_array('eliminar_modulos',$data["perm"]);
                ?>
                
                <h3 class="panel-title"><?=$data['titulo']?></h3>
            </div>

            <div class="panel-body">
                <div class="table-toolbar-left">
                    <?php $r_c=sizeof($data['rol_data']);
                        if($r_c==0){
                    ?>
                    <button class="btn btn-purple" id="addx" disabled><i class="demo-pli-add icon-fw"></i>Agregar</button>
                    <?php }else{?>
                            <button class="btn btn-purple" id="addx"  ><i class="demo-pli-add icon-fw"></i>Agregar</button>
                        <?php }?>
                    <button class="btn btn-default" onclick="refrescar()"><i class="demo-pli-refresh"></i>Refrescar</button>

                </div>
                <br><br> <br>


                <table id="tabla_grid" class="table table-hover table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr><th>#</th>
                        <th>Role</th>
                        <th>Permisos</th>
                        <th class="min-desktop">Acción</th>
                    </tr>
                    </thead>
                    <tbody id="table_body">
                      <?php $c=1 ;foreach ($data['d_role'] as $d_role):?>
                          <tr>
                              <td><?=$c++?></td>
                              <td><?=$d_role['role']?></td>
                              <td> <a href="javascript:void(0)" onclick="ver_permisos('<?=$d_role['id']?>');"  class="btn btn-info btn-icon btn-xs"><i class="fa fa-search"></i> Ver Permisos</a> </td>

                              <td><a href="javascript:void(0)"  onclick="editar('<?=$d_role['id']?>','<?=$d_role['role']?>');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>
                                  <!--<a href="javascript:void(0)" onclick="eliminar('<?=$d_role['id']?>');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-lg"></i></a>-->
                              </td>
                          </tr>

                       <?php endforeach;?>
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
            <div class="modal-header" style="background-color: #00b3ca;">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <input type="hidden" id="is_edita" name="is_edita" value="0" >
                <h4 class="modal-title" style="color: #FFFFFF;" id="titulo_mod"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body">
                <div class="bootbox-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-horizontal">
                                <input type="hidden" id="modulo_edita" name="modulo_edita" value="">
                                <div class="panel panel-default">
                                    <div class="panel-body relative" id="sel_role_div">
                                        <select   id="sel_r" name="sel_r" data-placeholder="Seleccione" title="Seleccione"     >
                                            <option value="">Seleccione...</option>
                                            <?php foreach($data['rol_data'] as $sel_r):?>
                                                <option value="<?=$sel_r['id']?>"><?=$sel_r['role']?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                    <div id="titulo_rol" style="margin-left: 10px;">

                                    </div>
                                    <div class="panel-body relative">
                                        Módulo/Url - Acción
                                        <div id="sel_mx">
                                            </div>
                                        <div class="select-box-option">
                                            <a class="btn btn-sm btn-default" id="btnRemove">
                                                <i class="fa fa-angle-left"></i>
                                            </a>
                                            <a class="btn btn-sm btn-default" id="btnSelect">
                                                <i class="fa fa-angle-right"></i>
                                            </a>
                                            <div class="seperator"></div>
                                            <a class="btn btn-sm btn-default" id="btnRemoveAll">
                                                <i class="fa fa-angle-double-left"></i>
                                            </a>
                                            <a class="btn btn-sm btn-default" id="btnSelectAll">
                                                <i class="fa fa-angle-double-right"></i>
                                            </a>
                                        </div>

                                        <select multiple="multiple" id="selectedBox2" name="sel_permrole" class="select-box pull-right form-control">
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
                <button class="btn btn-primary" onclick="guardar();">Guardar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade"   id="modal_ver" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header" style=" background-color: #26a69a;">
                <button type="button" class="close" data-dismiss="modal"  ><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="titulo_x" style="color: white;">  </h4>
                <input type="hidden" name="id_mod" id="id_mod" value="">
            </div>

            <div class="modal-body">
                <!--<div class="tab-content fadeIn" id="add_perm">
                    <div class="tab-pane pad-btm fade in active" id="add_sel">
                    </div>
                </div>-->
                <div class="tab-content">
                    <div class="tab-pane pad-btm fade in active" id="permrole_ver">
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
        $('#sel_r').chosen({width:'100%'});
        $('#tabla_grid').DataTable();
    });
   /* $(document).ready(function () {
        $('#sel_r').chosen({width:'100%'});

        $x=0;
        if($x ==0){

        var rowSelection = $('#tabla_grid').DataTable({
            "ajax": url_base+'permrole/sel_role_json',
            "columns": [
                { "data": "id_modulo" },
                { "data": "role" },
                { "data": "nombre" },
                { "data": "url" },
                { "render": function ( data, type, full, meta ) {
                    var id_m = full.id_modulo;
                    var id_r = full.id;
                    var html='';
                    html=html+'&nbsp; <a href="javascript:void(0)" onclick="ver_perm('+id_m +','+id_r+');"  class="btn btn-pink btn-icon btn-xs"><i class="demo-psi-information"></i> Ver Acciones</a>';
                    return html;
                     }
                },

                {  sortable: false,
                    "render": function ( data, type, full, meta ) {
                        var id_m = full.id_modulo;
                        var id_r = full.id;
                        var html='';

                        html='<a href="javascript:void(0)"  onclick="editar('+id_m +','+id_r+');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';


                        html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar('+id_m +','+id_r+');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-lg"></i></a>';


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
        }
    });*/


    $(document).on('click','#addx',function(){
        $('#titulo_mod').html('Agrear Permisos por Rol');
        $('#is_edita').val('0');
        $('#modulo_edita').val('');
        $('#titulo_rol').hide();
        $("#sel_r").val('');
        $('#sel_r').prop('disabled', false).trigger("chosen:updated");


        $('#selectedBox2').empty();
        $.post(url_base+'permrole/sel_accion_m',function(data){
            console.log(data);
            var html='';
            html+='<select multiple="multiple" id="selectedBox1" class="select-box pull-left form-control">';
                for(var i=0;i < data.length ; i++){

                    html+='<option value="'+data[i].id+'">'+data[i].modulo+'-'+data[i].accion+' </option>'
                }
            html+='</select>';

            $('#sel_mx').html(html);
        },'json');

        open_modal('modal_id');

    });

    function ver_perm(id_m,id_r){
        $('#titulo_x').html('Permisos por Rol');
        open_modal('modal_ver');
        var id_mm=parseInt(id_m);
        var id_rr=parseInt(id_r);
        $.post(url_base+'permrole/ver_perms','id_m='+id_mm +'&id_r='+id_rr,function (data) {
        console.log(data);
            t = _.template($("#t_body_table").html());
            $("#permrole_ver").html(t({data:data}));
        },'json');
    }

    function ver_permisos(id_r){
        var id=parseInt(id_r);
        $.post(url_base+'permrole/ver_permisos','id_r='+id,function(data){
            console.log(data);
            t = _.template($("#t_body_table").html());
            $("#permrole_ver").html(t({data:data}));
        },'json');
       open_modal('modal_ver');
   }

    function editar(id_r,r_name){

        $('#titulo_mod').html('Editar Permisos por Rol');
        //$('#modulo_edita').val(id_m);
        $('#is_edita').val('1');
        $('#sel_r').append($('<option>', {
            value: id_r,
            text: r_name
        }).attr("selected","selected"));

        $('#sel_r').prop('disabled', true).trigger("chosen:updated");

        var ht="<h4>Rol:"+r_name+"</h4>";
        $('#titulo_rol').html(ht);

        /*
        $("#sel_r option").each(function(){
            if($(this).val() == id_r){ // EDITED THIS LINE
                //alert(item.profile_id);
                $(this).attr("selected","selected");
                $('#sel_r').prop('disabled', true);
                $('#sel_r').trigger("chosen:updated");
            }
        });*/

        $('#selectedBox2').empty();

        open_modal('modal_id');

        $.post(url_base+'permrole/edit_sel_perm','id_r='+id_r,function (data) {
            console.log(data);
            $.each(data['data1'], function (index, value) {
                $('#selectedBox2').append($('<option>', {
                    value: value.id_perm,
                    text: value.nombre+'-'+value.accion
                }));
                // $('#selectedBox2').appendTo('<option>'+value.accion+'</option>');
            });
            var html='';
            html+='<select multiple="multiple" id="selectedBox1" class="select-box pull-left form-control">';
            for(var i=0;i < data['data2'].length ; i++){
                html+='<option value="'+data['data2'][i].id_perm+'">'+data['data2'][i].modulo+'-'+data['data2'][i].accion+' </option>'
            }
            html+='</select>';
            $('#sel_mx').html(html);

        },'json');


        /* $.post(url_base+'permrole/edit_selperm_sin','id_r='+id_r+'&id_m='+id_m,function (data) {
         console.log(data['data1']);
         },'json'); */

    }

    function guardar(){
        var sel_per=[];
        var c=0;
        var bol=true;
        var sel_r=parseInt($('#sel_r').val());
        var is_edita=parseInt($('#is_edita').val());
        var idmodulo_edita=parseInt($('#modulo_edita').val());

        $('#selectedBox2 option').each(function() {
            sel_per.push($(this).val());
        });
        c=sel_per.length;

        if(!isNaN(sel_r) && c > 0){

                 $.post(url_base+'permrole/ins_permrole','id_r='+sel_r+'&id_m='+sel_per+'&is_edita='+is_edita+'&idmodulo_edita='+idmodulo_edita,function(d){
                     console.log(d);
                },'json');

                    alert_success('Se ejecuto correctamente');
                    close_modal('modal_id');
                    window.location.reload(true);

        }else{
            alert_error('Rellene los campos');
        }

    }




</script>

<script type="text/template" id="t_body_table">
    <h1> </h1>
    <table class="table table-hover table-condensed" cellspacing="0" width="100%" >
        <thead>
        <tr><th>#</th>
            <th>Modulo</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <% _.each(data['data1'], function(i,k){  %>
        <tr>
            <td><%=k+1 %></td>
            <td><%=i.nombre %></td>
            <td>
                <% _.each(data['data2'], function(a,b){
                    if(a.id_modulo == i.id_modulo){
                %>
                <%=a.nombre%><br>

                <% }
                }) %>
            </td>


        </tr>
        <% }) %>
    </table>
</script>

