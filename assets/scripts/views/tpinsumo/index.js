
var nameModal={'title0':"Agregar Tipo de Insumo",'title1':"Editar Tipo de Insumo"};
var isEdit,idEdit;
$(document).on('ready',function () {

    var rowSelection = $('#tabla_grid').DataTable({
        "ajax": url_base+'tpinsumo/getDataTpinsumo',
        "columns": [
            { "data": null },
            { "data": "nombre" },
            { "data": "descripcion" },
            { "data": "fechacrea" },
            {  sortable: false,
                "render": function ( data, type, full, meta ) {
                    var id = full.id_tipoinsumo;

                    html='<a href="javascript:void(0)"  onclick="ver('+id+');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>';
                    //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                    html=html+'&nbsp; <a href="javascript:void(0)" onclick="editar('+id +');"  class="btn btn-mint btn-icon btn-xs"><i class="demo-psi-pen-5 icon-xs"></i> Editar</a>';
                    html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar('+id +');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-xs"></i> Eliminar</a>';
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

    
//console.log(nameModal);

});


function refrescar () {
    var rowSelection = $('#tabla_grid').DataTable();
    rowSelection.ajax.reload();
}

$(function () {

 // se ejecuta primero no espera el dom Ready




 });


 $(document).on('click','#addBtn',function () {
     $('#formTpinsumo')[0].reset();
     $('#isEdit').val(0);
     $('#modalTitle').html(nameModal['title0']);
        open_modal("modal_id");
 });

$(document).on('click','#btnSave',function () {

    var bol=validaForm();
    
    if(bol){
        var dataForm=$('#formTpinsumo').serialize();
        $.post(url_base+'tpinsumo/setForm',dataForm,function (data) {
            console.log(data);
            refrescar();
            alert_success('Se ejecuto correctamente ...')
        }).always(function (data) {

            close_modal("modal_id");
        });



    }


});

function validaForm(){
    var bol=true;
    bol=bol&&$('#ntipoinsumo').required();
    return bol;
}

function  editar(id){
    var idd=parseInt(id);
    $('#formTpinsumo')[0].reset();
    $.post(url_base+'tpinsumo/getDataTInsumo','id='+idd,function (data) {
        console.log(data);
        setForm(data[0]);
    },'json');

    $('#modalTitle').html(nameModal['title1']);
    open_modal('modal_id');
    
}

function setForm(data){
    $('#ntipoinsumo').val(data.nombre);
    $('#desc').val(data.descripcion);
    $('#isEdit').val(1);
    $('#idEdit').val(data.id_tipoinsumo);
}

function ver(id){
    
}

function eliminar(id) {
    if(confirm('¿Esta seguro?')){
    var idd=parseInt(id);
    $.post(url_base+'tpinsumo/deleteTpinsumo','id='+idd,function (data) {
        console.log(data);

    },'json').success(function () {
        alert_success('Se ejecuto Correctamente');
        refrescar();
    });
    }
}


