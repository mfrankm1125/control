/**
 * Created by Frank on 02/05/2017.
 */
var titles={'addtitle':"Nueva Unidad de medida" ,'updatetitle':"Editar Unidad de medida"};
var isEdit=null,idEdit=null;
var btn = $("#btnSave");
$(document).ready(function () {

    var rowSelection = $('#tabla_grid').DataTable({
        "ajax": url_base+'um/getDataTable',
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
                    var f = full.fechacrea;
                    fechaFormat=formatDateDMY(f);
                    return fechaFormat;
                 }
            },
            {  sortable: false,
                "render": function ( data, type, full, meta ) {
                    var id = full.id_unidadmedida;
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
    btn.button('reset');
    $("#isEdit").val(0);
    $("#idEdit").val(0);
    $("#formUm")[0].reset();
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
function btnSavexssss(r) {
    alert("culo");
}



function btnSavex(id){
    btn.button('loading');
    var idd=parseInt(id);
    var bol=validateForm();
    var status=null;

    console.log($('#formUm').serialize());

    //bol=false;
    if(bol){

        var dataForm=$('#formUm').serialize();
        $.post(url_base+'um/setForm',dataForm,function (data) {
            console.log(data);
            if(data === "ok"){
                status=true;
                alert_success("Se ejecuto correctamente ...");
            }else{
                alert_error("Error");
            }
        }).always(function () {
            if(idd == 1 && status == true){
                refrescar();
                close_modal('modal_id');
            }
            if(idd == 2 && status == true ){
                refrescar();
                $("#formHerramienta")[0].reset();
            }
        });
    }else{

    }
}

function  editar(id){
    $('#btnSave2').remove();
    isEdit=1;

    var idd=parseInt(id);
    $.post(url_base+'um/getData','id='+idd,function (data) {
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
        $("#idEdit").val(data.id_unidadmedida);
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
        $.post(url_base+'um/deleteData','id='+idd,function (data) {
            console.log(data);
        },'json').success(function () {
            alert_success('Se ejecuto Correctamente');
            refrescar();
        });
    }
}