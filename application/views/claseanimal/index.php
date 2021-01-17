<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Clase de Animales</h3>
            </div>

            <!--Data Table-->
            <!--===================================================-->
            <div class="panel-body" style="padding-top: 0px;" >
                <br>
                <div class="table-toolbar-left">
                    <!--<button class="btn btn-purple" id="add"><i class="demo-pli-add icon-fw"></i> Agregar</button> -->
                    <button class="btn btn-purple" id="btnAdd"><i class="demo-pli-add icon-fw"></i> Agregar Nuevo</button>
                    
                </div>
                <br><br>
                <!--CUerpot aqui -->
                <h1></h1>
                <table id="tabla_grid" class="table table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr><th>#</th>
                        <th>Tipo de animal</th>
                          <th>Clases de animales</th>
                        <th>Accion</th>

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




<div class="modal fade"   id="modal_id" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <!--Modal body-->
            <div class="modal-body" id="divmodalbody">
               


            </div>

        </div>
    </div>
</div>

<script type="text/template" id="tmpForm">
     <form class="panel-body form-horizontal form-padding" id="formForm">
                    <input type="hidden" name="isEdit" id="isEdit" value="0">
                    <input type="hidden" name="idEdit" id="idEdit" value="0">
                    <!--Text Input-->
                    <div class="form-group">
                        <label class="col-md-3 control-label">Tipo de animal</label>
                        <div class="col-md-9">
                            <input id="name" name="name" class="form-control" placeholder="" />
                        </div>
                    </div>
                    <div class="form-group">
                        
                        <div class="col-md-12">
                            <button class="btn btn-xs btn-warning " id="btnaddrowtable" type="button">Agregar +</button>
                             <table id="tablesubtipo" class="table table-hover table-bordered table-condensed">
                                 <thead>
                                     <tr>                                        
                                        <th>Clase</th>
                                         <th>Sexo</th>
                                         <th>Orden</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    <tr>                                       
                                        <td><input type="text"  name="subtipoequipo[]"> </td>
                                        <td>
                                            <select name="selSexo[]">
                                                <option value="1">Macho</option>
                                                <option value="2">Hembra</option>
                                            </select>
                                        </td>
                                        <td><input type="number" name="orden[]"></td>
                                        <td><button type="button" class="btn btn-xs btn-danger eliminarrow "><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                </tbody>
                               
                             </table>
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-9">
                            <a href="javascript:void(0)"  id="btnSave" type="button"   class=" btn btn-success ">
                                Guardar y terminar
                            </a>                           

                            <button type="button" id="btnCancel"  data-dismiss="modal" class=" btn btn-danger ">
                                Cancelar
                            </button>

                        </div>
                    </div>
                </form>
</script>
 
<script type="text/template" id="tmpForm2">
     <form class="panel-body form-horizontal form-padding" id="formForm">
                    <input type="hidden" name="isEdit" id="isEdit" value="<%=isEdit%>">
                    <input type="hidden" name="idEdit" id="idEdit" value="<%=data.idtipoanimal%>">
                    <!--Text Input-->
                    <div class="form-group">
                        <label class="col-md-3 control-label"  >Nombre</label>
                        <div class="col-md-9">
                            <input id="name" name="name" class="form-control" placeholder="" value="<%=data.nombre%>" />
                        </div>
                    </div>
                    <div class="form-group">
                        
                        <div class="col-md-12">
                             <button class="btn btn-xs btn-warning " id="btnaddrowtable" type="button">Agregar +</button>
                             <table id="tablesubtipo" class="table table-hover table-bordered table-condensed">
                                <thead>
                                     <tr>                                        
                                        <th>Clase</th>
                                         <th>Sexo</th>
                                         <th>Orden</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    <% _.each(data.data,function(i,k){ %>
                                    <tr>                                     
                                        <td>
                                            <input type="hidden"  name="idsubtipoequipo[]" value="<%=i.idclaseanimal%>">
                                            <input type="text"  name="subtipoequipoedit[]" value="<%=i.nombre%>">
                                        </td>
                                        <td>
                                            <select name="selSexo[]">
                                                <% var isMacho='';
                                                   var isHembra='';
                                                if(i.idsexo == 1){
                                                    isMacho='selected="selected"';
                                                }else{
                                                    isHembra='selected="selected"';
                                                }

                                                %>
                                                <option value="1" <%=isMacho%> >Macho</option>
                                                <option value="2"  <%=isHembra%> >Hembra</option>
                                            </select>
                                        </td>
                                        <td><input type="number" name="orden[]" value="<%=i.orden%>"></td>
                                        <td><button type="button" onclick="eliminarsubtipo(this,'<%=i.idclaseanimal%>')" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i></button></td>
                                    </tr>

                                <% });%>
                                    
                                </tbody>
                               
                             </table>
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-9">
                            <a href="javascript:void(0)"  id="btnSave" type="button"   class=" btn btn-success ">
                                Guardar y terminar
                            </a>                           

                            <button type="button" id="btnCancel"  data-dismiss="modal" class=" btn btn-danger ">
                                Cancelar
                            </button>

                        </div>
                    </div>
                </form>
</script>
 

<?php echo $_js?>
<?php echo $_css?>
<script type="text/javascript">
var tmpForm=_.template($("#tmpForm").html());    
var tmpForm2=_.template($("#tmpForm2").html()); 
$(document).ready(function () {

    var rowSelection = $('#tabla_grid').DataTable({
        "ajax": url_base+'ClaseAnimal/getDataTable',
        "columns": [
            { "data": null },
            {  "render": function ( data, type, full, meta ) {
                var name = full.nombre;
                //var lastname = full.apellidos;
                var html=""+name;
                return html;
                }
            },
             {  "render": function ( data, type, full, meta ) {
                var datad = full.data;
                //console.log(datad);
                //var lastname = full.apellidos;
                  var html="";
                $.each(datad,function(i,k){
                    html=html+"-"+k.nombre+"<br>";
                });
              
                return html;
                }
            },
             
           
            {  sortable: false,
                "render": function ( data, type, full, meta ) {
                    var id = full.idtipoanimal;

                    html='';
                    //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                    html=html+'&nbsp; <a href="javascript:void(0)" onclick="editar('+id +');"  class="btn btn-mint btn-icon btn-xs"><i class="demo-psi-pen-5 icon-xs"></i> Editar</a>';
                    html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar('+id +');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-xs"></i> Eliminar</a>';
                    return html;
                }
            }

        ],
        "responsive": true,
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
     //$('#formForm')[0].reset();
     $('#isEdit').val(0);
     $('#modalTitle').html("Nuevo");
     $("#divmodalbody").html(tmpForm);
        open_modal("modal_id");
 });

$(document).on('click',"#btnaddrowtable",function(){
    var ht='<tr>                                       \n' +
        '                                        <td><input type="text"  name="subtipoequipo[]"> </td>\n' +
        '                                        <td>\n' +
        '                                            <select name="selSexo[]">\n' +
        '                                                <option value="1">Macho</option>\n' +
        '                                                <option value="2">Hembra</option>\n' +
        '                                            </select>\n' +
        '                                        </td>\n' +
        '                                        <td><input type="number" name="orden[]"></td>\n' +
        '                                        <td><button type="button" class="btn btn-xs btn-danger eliminarrow "><i class="fa fa-trash"></i></button></td>\n' +
        '                                    </tr>';
    $("#tbody").append(ht);
});

$(document).on('click','#btnSave',function () {

    var bol=validaForm();
    
    if(bol){
        var dataForm=$('#formForm').serialize();
        $.post(url_base+'claseanimal/setForm',dataForm,function (data) {
            console.log(data);
            refrescar();
            alert_success('Se ejecuto correctamente ...');
               close_modal("modal_id");
        },'json');



    }


});

function validaForm(){
    var bol=true;
    bol=bol&&$('#name').required();
    return bol;
}

$(document).on('click', '.eliminarrow', function () {
        if(confirm("¿Esta Seguro de eliminar este elemento?")){
            var objCuerpo = $(this).parents().get(2);
            if ($(objCuerpo).find('tr').length > 1) {
                var objFila = $(this).parents().get(1);
                $(objFila).remove();
            }else{
                alert_error("No se puede eliminar.Debe tener minimo 1 registro")
            } 
        }
    });

function eliminarsubtipo(thisx,id){
    if(confirm("¿Esta Seguro de eliminar este elemento?")){
            var objCuerpo = $(thisx).parents().get(2);
            if ($(objCuerpo).find('tr').length > 1) {
                var objFila = $(thisx).parents().get(1);
                $(objFila).remove();

                $.post(url_base+"ClaseAnimal/deleteDataSubtipo",{"id":id},function(data){
                        console.log(data);
                        refrescar();
                },"json");    


            }else{
                alert_error("No se puede eliminar.Debe tener minimo 1 registro")
            } 
        }
}


function  editar(id){
    var idd=parseInt(id);
  
    $.post(url_base+'ClaseAnimal/getData','id='+idd,function (data) {
        console.log(data);
        setForm(data[0]);
    },'json');

    $('#modalTitle').html("Editar");
    open_modal('modal_id');
    
}

function setForm(data){
    $("#divmodalbody").html(tmpForm2({isEdit:1,data:data}));
     
}

function ver(id){
    
}

function eliminar(id) {
    if(confirm('¿Esta seguro?')){
    var idd=parseInt(id);
    $.post(url_base+'ClaseAnimal/deleteData','id='+idd,function (data) {
        console.log(data);
        alert_success('Se ejecuto Correctamente');
        refrescar();
    },'json');
    
    }
}
</script>