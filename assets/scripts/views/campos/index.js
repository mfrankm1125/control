$(document).ready(function () {

    var rowSelection = $('#tabla_grid').DataTable({
        "ajax": url_base+'campos/campos_json',
        "columns": [
            { "data": null },
            { "data": "nombre" },
            { "data": "descripcion" },
            { "data": "fecha_crea" },
            {  sortable: false,
                "render": function ( data, type, full, meta ) {
                    var id_x = full.id_campo;
                    var placeid = full.placeid;
                    html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>';
                    //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                    html=html+'&nbsp; <a href="javascript:void(0)" onclick="editar_n('+id_x +');"  class="btn btn-mint btn-icon btn-xs"><i class="demo-psi-pen-5 icon-xs"></i> Editar</a>';
                    html=html+'&nbsp; <a href="javascript:void(0)" onclick="eliminarx('+id_x +');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-xs"></i> Eliminar</a>';
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

});

    function refrescar () {
            var rowSelection = $('#tabla_grid').DataTable();
            rowSelection.ajax.reload();
    }

        function editar(id,placeid) {
            $("#id_up").val(id);
            $('#formcampo')[0].reset();
            $('#op').val(0);
            $("#titulo_id").html("Modiciar Campo");
            $("#loading").html('Cargando...');
            $("#modal_id").modal("show");
            $.post(url_base+'campos/dataup','id='+id+'&placeid='+placeid,function (data) {
               // console.log(data);
                $('#ncampo').val(data['data'][0]['nombre']);
                $('#autocomplete').val(data['place']['result']['formatted_address']);
                $('#placeid').val(placeid);
                $('#lat').val(data['place']['result']['geometry']['location']['lat']);
                $('#lng').val(data['place']['result']['geometry']['location']['lng']);
                $('#desc').val(data['data'][0]['descripcion']);
                $("#loading").html('');
            },'json');

        }

        function eliminarx(id){
            if(confirm("¿Esta seguro que desea eliminar este registrox?")){
                $.post(url_base+'campos/delete','id='+id,function (data) {
                    console.log(data);
                    if(data=="ok"){
                        alert_success('Se elimino correctamente');
                    }
                    refrescar();

                }).fail(function() {
                    alert( "error" );
                })

            }else{
               
            }

        }

        function editar_n(id){
            window.location.href=url_base+"campos/formcampos/"+id;
        }

        function vercamp(id){
            window.location.href=url_base+"campos/formcampos/"+id;
        }

