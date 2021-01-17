$(document).ready(function () {

    var rowSelection = $('#tabla_grid').DataTable({
        "ajax": url_base+'perfiles/perfiles_json',
        "columns": [
            { "data": "id" },
            { "data": "role" },
            {  sortable: false,
                "render": function ( data, type, full, meta ) {
                    var id_x = full.id;
                    html='<a href="javascript:void(0)"  onclick="editar('+id_x +');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                    html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminar('+id_x +');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-lg"></i></a>';
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



});

function refrescar(){
    var rowSelection = $('#tabla_grid').DataTable();
    rowSelection.ajax.reload();
}



