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

    .table-fixed thead {
        width: 97%;
    }
    #scrollx tbody {
        height: 100px;
        overflow-y: auto;
        width: 100%;
    }
    .table-fixed thead, .table-fixed tbody, .table-fixed tr, .table-fixed td, .table-fixed th {
        display: block;
    }
    .table-fixed tbody td, .table-fixed thead > tr> th {
        float: left;
        border-bottom-width: 0;
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
                    <button class="btn btn-purple" id="addx"><i class="demo-pli-add icon-fw"></i>Agregar</button>
                    <button class="btn btn-default" onclick="refrescar()"><i class="demo-pli-refresh"></i>Refrescar</button>

                </div>
                <br><br> <br>


                <table id="tabla_grid" class="table table-condensed table-hover table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr><th>#</th>
                        <th>Desc. Usuario</th>
                        <th>Usuario</th>

                        <th>Teléfono</th>
                        <th>email</th>
                        <th class="min-tablet">Rol</th>
                        <th class="min-tablet">--</th>
                        <th class="min-tablet">Acci. Permitidas</th>

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
<div class="modal fade "   id="modal_id" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header" style="background-color: #00b3ca;">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>

                <h4 class="modal-title" style="color: #FFFFFF;" id="titulo_mod"></h4>
            </div>


            <!--Modal body-->
            <div class="modal-body">
                <div class="eq-height clearfix">

                    <div class="col-md-9 eq-box-md eq-no-panel">

                        <!-- Main Form Wizard -->
                        <!--===================================================-->
                        <div id="demo-main-wz">
                            <!--nav-->
                            <ul class="row wz-step wz-icon-bw wz-nav-off   wz-steps">
                                <li id="tabs1"   class="col-xs-4 active">
                                    <a id="atabs1" data-toggle="tab"  href="#demo-main-tab1" aria-expanded="true">
                                        <span class="text-danger"><i class="demo-pli-information icon-2x"></i></span>
                                        <p class="text-semibold mar-no">Tú Cuenta</p>
                                    </a>
                                </li>
                            </ul>
                            <!--progress bar-->
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-primary" style="width: 25%; left: 0%; position: relative; transition: all 0.5s;"></div>
                            </div>
                            <!--form-->
                            <form class="form-horizontal" method="post" id="form1">
                              
                            </form>
                        </div>
                        <!--===================================================-->
                        <!-- End of Main Form Wizard -->
                    </div>
                </div>
            </div>

            <!--Modal footer-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button" id="cancelar" >Cancelar</button>
                <button class="btn btn-primary" id="guardar" >Guardar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade"   id="modal_ver" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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
                <div class="eq-height clearfix">
                    <div class="col-md-6 eq-box-md text-left box-vmiddle-wrap  " id="permrole_ver">
                            <!-- Contenido de talbas perm role-->

                    </div>
                    <div class="col-md-6 eq-box-md text-left box-vmiddle-wrap bord-lft" id="permuser_ver">


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

<div class="modal fade"   id="modal_add_perm_u" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <input type="hidden" id="iduser_p" name="iduser_p" value="">
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
                                <div class="panel panel-default">
                                    <div class="panel-body relative">


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
                    <div class="row">
                        <div class="col-md-12s">
                            <h4>Permisos heredados del rol:</h4>
                            <div id="tm_table">

                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <!--Modal footer-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button" id="cancelar" >Cancelar</button>
                <button class="btn btn-primary" onclick="guardar_u_perm();">Guardar</button>
            </div>
        </div>
    </div>
</div>



<?php echo $_css?>
<?php echo $_js?>
<script type="text/template" id="tmpBodyModalUser">
    <input type="hidden" id="is_edita" name="is_edita" value="0" >
    <input type="hidden" id="table_id" name="table_id" value="" >
    <div class="panel-body">
        <div class="tab-content">
            <!--First tab-->
            <div id="demo-main-tab1" class="tab-pane active in">
                <!--<div class="form-group">
                    <label class="col-lg-2 control-label">Nombres<span style="color:red">*</span></label>
                    <div class="col-lg-9 pad-no">
                        <div class="clearfix">
                            <div class="col-lg-4">
                                <input type="text" placeholder="nombres" id='nombres' name="nombres" class="form-control" autofocus>
                            </div>
                            <div class="col-lg-4 text-lg-right"><label class="control-label">Apellidos <span style="color:red">*</span></label></div>
                            <div class="col-lg-4"><input type="text" placeholder="apellidos" id="apellidos" name="apellidos" class="form-control"></div>
                        </div>
                    </div>
                </div> -->
                <div class="form-group">
                    <label class="col-lg-2 control-label">Dependencia<span style="color:red">*</span></label>
                    <div class="col-lg-10 pad-no">
                        <div class="clearfix">
                            <div class="col-lg-12" id="divSelDependecia">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Descripcion de usuario<span style="color:red">*</span></label>
                    <div class="col-lg-10 pad-no">
                        <div class="clearfix">
                            <div class="col-lg-12">
                                <input type="text" placeholder="Ejemplo:Alcaldia" id='nombrearearesp' name="nombrearearesp" class="form-control" autofocus>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Usuario <span style="color:red">*</span></label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" id="usuario" name="usuario" placeholder="usuario">
                        <input type="hidden" class="form-control" id="usuarioini" name="usuarioini"  >
                        <input type="hidden" value="0" id="exist_user">
                        <small class="help-block" id="exist_us" data-bv-validator="notEmpty" data-bv-for="username"   style="color:red;display: none;">Usuario ya existe</small>

                    </div>
                    <button class="btn btn-success btn-icon btn-circle btn-xs" id="btncheck" disabled style="display: none"><i class="fa fa-check"></i></button>
                    <button class="btn btn-danger btn-icon btn-circle btn-xs" id="btnclose" disabled  style="display: none" ><i class="fa fa-close"></i></button>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Contraseña <span style="color:red">*</span></label>
                    <div class="col-lg-9 pad-no">
                        <div class="clearfix">
                            <div class="col-lg-4">
                                <input type="password" class="form-control mar-btm"  id="pass" name="pass" placeholder="contraseña">
                            </div>
                            <div class="col-lg-4 text-lg-right"><label class="control-label">Repita contraseña <span style="color:red">*</span></label></div>
                            <div class="col-lg-4"><input type="password" class="form-control" id="pass2" name="pass2" placeholder="contraseña"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Email</label>
                    <div class="col-lg-9">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                    </div>
                </div>

                <!-- <div class="form-group">
                     <label class="col-lg-2 control-label">Dni<span style="color:red">*</span></label>
                     <div class="col-lg-9">
                         <input type="text" placeholder="Dni" id='dni' name="dni" class="form-control">
                     </div>
                 </div>-->
                <div class="form-group">
                    <label class="col-lg-2 control-label">Role <span style="color:red">*</span></label>
                    <div class="col-lg-9">

                        <select id="sel_r" name="sel_r" class="form-control" data-placeholder="Seleccione" title="Seleccione" >
                            <option value="" selected="selected">...</option>
                            <?php foreach($data['o_rol'] as $o_rol):?>
                                <option value="<?=$o_rol['id']?>" ><?= $o_rol['role']?></option>
                            <?php endforeach;?>
                        </select>
                        <small class="help-block" id="sel_r_v" data-bv-validator="notEmpty" data-bv-for="username"   style="color:red;display: none;">The  is required.</small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Cel./Tel.</label>
                    <div class="col-lg-9">
                        <input type="text" placeholder="" name="telefono" id="telefono" class="form-control">
                    </div>
                </div>
                <!--
                <div class="form-group">
                    <label class="col-lg-2 control-label">Dirección</label>
                    <div class="col-lg-9">
                        <input type="text" placeholder="dirección" name="direccion" id="dirección" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Ciudad</label>
                    <div class="col-lg-9 pad-no">
                        <div class="clearfix">
                            <div class="col-lg-5">
                                <input type="text" placeholder="ciudad" name="ciudad" id="ciudad" class="form-control">
                            </div>

                        </div>
                    </div>
                </div>-->
            </div>

        </div>
    </div>
</script>
<script type="text/template" id="tmpSelDependecia">
    <select class="form-control" name="selDependecia" id="selDependecia">
        <option value="0">Ninguna</option>
        <%_.each(data,function(i,k){;%>
        <option value="<%=i.id%>" <% if(idedit ==i.id ){print('selected="selected"');}%>    ><%=i.nombrearearesponsable%></option>
        <% });%>
    </select>
</script>

<script type="text/javascript">
     $(document).ready(function () {
         
        $('#sel_r').chosen({width:'100%'});
        $x=0;
        if($x ==0){
            var rowSelection = $('#tabla_grid').DataTable({
                "ajax": url_base+'user/out_data_user',
                "columns": [
                    { "data": null },
                    { "render": function ( data, type, full, meta ) {
                        var html="";
                        html=html +full.nombrearearesponsable;
                        return html;
                        }
                    },
                    { "data": "user" },
                    { "data": "telefono1" },
                    { "data": "email" },
                    { "data": "role" },
                    { "render": function ( data, type, full, meta ) {
                        var id = full.id;
                        var id_r = full.id_role;
                        var html='';
                        html=html+'&nbsp; <a href="javascript:void(0)" onclick="add_perm_u('+id+','+id_r+');"  class="btn btn-info btn-icon btn-xs"><i class="demo-psi-information"></i>Agre. Perm. U.</a>';
                        return html;
                        }
                    },
                    { "render": function ( data, type, full, meta ) {
                        var id = full.id;
                        var id_r = full.id_role;
                        var html='';
                        html=html+'&nbsp; <a href="javascript:void(0)" onclick="ver_perm('+id+','+id_r+');"  class="btn btn-pink btn-icon btn-xs"><i class="demo-psi-information"></i> Ver permisos</a>';
                        return html;
                        }
                    },

                    {  sortable: false,
                        "render": function ( data, type, full, meta ) {
                            var id = full.id;

                            var html='';

                            html+='<a href="javascript:void(0)"  title="Editar" onclick="editar('+id+');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';


                            html=html+'&nbsp; <a href="javascript:void(0)" title="Eliminar"  onclick="eliminar('+id+');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-lg"></i></a>';


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
            // index colum
            rowSelection.on( 'order.dt search.dt', function () {
                rowSelection.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();
            //

        }
    });


    function ver_perm(id,id_rr){
        $('#titulo_x').html('Permisos por Usuario');
        open_modal('modal_ver');
        var id_u=parseInt(id);
        var id_r=parseInt(id_rr);


        $.post(url_base+'user/ver_perm_u','id='+id_u+'&id_r='+id_r,function (data) {
            console.log(data);
              t = _.template($("#t_body_table").html());
            $("#permrole_ver").html(t({data:data}));

            tt = _.template($("#t_body_table2").html());
            $("#permuser_ver").html(tt({data:data}));


        },'json');
    }

     function add_perm_u(idd,id_rr){
         $('#selectedBox2').empty();
         var id=parseInt(idd);
         var id_r=parseInt(id_rr);
         $('#iduser_p').val(id);
         $.post(url_base+'user/sel_perm_u_d','id='+id+'&id_r='+id_r,function(data){
            console.log(data);
             var html='';
             // lena datos del select
             html+='<select multiple="multiple" id="selectedBox1" class="select-box pull-left form-control">';
             for(var i=0;i < data['data1'].length ; i++){
                 html+='<option value="'+data['data1'][i].id+'">'+data['data1'][i].modulo+'-'+data['data1'][i].nombre+' </option>'
             }
             html+='</select>';

             $.each(data['data3'], function (index, value) {
                 $('#selectedBox2').append($('<option>', {
                     value: value.id_perm,
                     text: value.modulo+'-'+value.nombre
                 }));

             });
             // llena datos en tabla
             t = _.template($("#t_body_table_i").html());
             $("#tm_table").html(t({data:data}));
             $('#sel_mx').html(html);
         },'json');
        open_modal('modal_add_perm_u');
     }

     function guardar_u_perm() {
         var sel_per=[];
         var id_u=parseInt($('#iduser_p').val());

         $('#selectedBox2 option').each(function() {
             sel_per.push($(this).val());
         });

         if(!isNaN(id_u)){
             $.post(url_base+'user/ins_perm_user','id_u='+id_u+'&id_perm='+sel_per,function(d){
                console.log(d);
             },'json');
         }
         alert_success('Se agrego correctamente');
        close_modal('modal_add_perm_u');
     }
    function loadDependecias(idedit){
        var t=_.template($("#tmpSelDependecia").html());
        $.post(url_base+"user/getDataSelDependecias",function (data) {
                console.log(data);
            $("#divSelDependecia").html(t({data:data,idedit:idedit}));
        },'json');
    }
     /*
    function editar(id_m,id_r){
        $('#titulo_mod').html('Editar Permisos por Rol');
        $('#is_edita').val('1');
        $("#sel_r option").each(function(){
            if($(this).val() == id_r){ // EDITED THIS LINE
                //alert(item.profile_id);
                $(this).attr("selected","selected");
                $('#sel_r').prop('disabled', true);
                $('#sel_r').trigger("chosen:updated");
            }
        });

        $('#selectedBox2').empty();

        open_modal('modal_id');

        $.post(url_base+'permrole/edit_sel_perm','id_r='+id_r+'&id_m='+id_m,function (data) {
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

   /* }

    function guardar(){
        var sel_per=[];
        var c=0;
        var bol=true;
        var sel_r=parseInt($('#sel_r').val());
        var is_edita=parseInt($('#is_edita').val());
        $('#selectedBox2 option').each(function() {
            sel_per.push($(this).val());
        });
        c=sel_per.length;

        if(!isNaN(sel_r) && c > 0){

            $.post(url_base+'permrole/ins_permrole','id_r='+sel_r+'&id_m='+sel_per+'&is_edita='+is_edita,function(d){
                console.log(d);
            },'json');

            alert_success('Se ejecuto correctamente');
            close_modal('modal_id');

        }else{
            alert_error('Rellene los campos');
        }

    }
 */



</script>

<script type="text/template" id="t_body_table">
    <h4>Permisos heredados del rol:</h4>
    <table class="table table-hover table-bordered" cellspacing="0" width="100%" >
        <thead>
        <tr><th>#</th>
            <th>Módulo</th>
            <th>url</th>
            <th>Permisos</th>

        </tr>
        </thead>
        <% _.each(data['data1'], function(i,k){  %>
        <tr>
            <td><%=k+1 %></td>
            <td><%=i.modulo %></td>
            <td>/<%=i.url %></td>
            <td>
                <% _.each(data['data2'], function(a,b){  %>
                       <% if(i.id_modulo == a.id_modulo){ %>
                                <%=a.nombre %><br>
                        <% } %>
                <% }) %>
            </td>

        </tr>
        <% }) %>
    </table>
</script>

<script type="text/template" id="t_body_table2">
    <h4>Permisos extras </h4>
    <table class="table table-hover table-bordered" cellspacing="0" width="100%" >
        <thead>
        <tr><th>#</th>
            <th>Módulo</th>
            <th>url</th>
            <th>Permisos</th>

        </tr>
        </thead>
        <% _.each(data['datau1'], function(i,k){  %>
        <tr>
            <td><%=k+1 %></td>
            <td><%=i.modulo %></td>
            <td>/<%=i.url %></td>
            <td>
                <% _.each(data['datau2'], function(a,b){  %>
                <% if(i.id_modulo == a.id_modulo){ %>
                <%=a.nombre %><br>
                <% } %>
                <% }) %>
            </td>

        </tr>
        <% }) %>
    </table>
</script>

<script type="text/template" id="t_body_table_i">

    <table id="scrollx" class="table table-hover table-condensed">
        <thead>
        <tr>
            <th>#</th>
            <th>Módulo</th>
            <th>Url</th>
            <th>Acción</th>
        </tr>
        </thead>

        <% _.each(data['data2'], function(i,k){  %>
        <tr>
            <td><%=k+1 %></td>
            <td><%=i.modulo %></td>
            <td>/<%=i.url %>/</td>
            <td><%=i.nombre %></td>
        </tr>
        <% }) %>

    </table>

</script>