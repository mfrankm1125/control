/**
 * Created by Frank on 22/05/2017.
 */
/**
 * Created by Frank on 02/05/2017.
 */
var titles={'addtitle':"Agregar Stock" ,'updatetitle':"Editar Stock"};
var isEdit=null,idEdit=null;
$(document).ready(function () {

    /*$('.selectpicker').selectpicker({
        liveSearch: true,
        showSubtext: true
    });*/

    var rowSelection = $('#tabla_grid').DataTable({
        "ajax": url_base+'stock/getDataTable',
        "columns": [
            { "data": null },
            {  "render": function ( data, type, full, meta ) {
                            var name = full.ninsumo;
                            //var lastname = full.apellidos;
                            var html=""+name;
                            return html;
                        }
            },
            { "data": "ntinsumo" },
            { "render": function ( data, type, full, meta ) {
                             return full.cantidad;
                        }
            },
            { "data": "nunidad" },
            { "data": "nalmacen" },

            { "data": "fechacrea" },
            {  sortable: false,
                "render": function ( data, type, full, meta ) {
                    var id = full.id_herramienta;

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




});

function refrescar () {
    var rowSelection = $('#tabla_grid').DataTable();
    rowSelection.ajax.reload();
}

$(document).on('click','#btnAdd',function () {
    var t=_.template($('#tmpForm').html());
    var tt=_.template($("#tmpTbody").html());

    $("#isEdit").val(0);
    $("#idEdit").val(0);
    $("#modalbody").html(t);
    $('#tmpTTbody').html(tt);
    //$(".selectpicker").select2();
    //$(".insum").select2();
    //$('.selectpicker').selectpicker({liveSearch: true, showSubtext: true });

    $('.selectpicker').chosen({width:'100%'});
    $('#btn2').html(setBtnSave2());
    $('#modalTitle').html(titles['addtitle']);
    open_modal('modal_id');

});

function btnX(thisx) {
    var bol=true;
   // valuevar= $(thisx).parent().find('input[name="namei"]').val();
    var parentTR= $(thisx).parent().parent();
    var insumoSelect=parentTR.find('select[name="insumox[]"]');
    var cantidad=parentTR.find('input[name="cantidadx[]"]');
    var precio=parentTR.find('input[name="preciox[]"]');
    var btnAdd=parentTR.find('button[name="btnx"]');
    bol = bol && insumoSelect.requiredSelect();
    bol = bol && cantidad.requiredSelect();


    if(bol){
        /*insumoSelect.attr('readonly', 'readonly');
        cantidad.attr('readonly', 'readonly');
        precio.prop( "disabled", true );*/
        btnAdd.remove();
        var tt=_.template($("#tmpTbody").html());
        $('#tmpTTbody').append(tt);
        //$(".selectpicker").select2();
        //$('.selectpicker').selectpicker({liveSearch: true,showSubtext: true});
        $('.selectpicker').chosen({width:'100%'});

    }else{
        alert_error("Complete los Campos necesarios");
    }


}


function calSub(thisx) {
    var parentTR= $(thisx).parent().parent();
    var cantidad=parseFloat(parentTR.find('input[name="cantidadx[]"]').val()) || 0;
    var precio= parseFloat(parentTR.find('input[name="preciox[]"]').val()) || 0;
    console.log(precio);
    var subtotal=parseFloat(cantidad*precio).toFixed(2) ||0;
    parentTR.find('p[id="subtotalx"]').html(subtotal);
    parentTR.find('input[name="subtotal[]"]').val(subtotal);
    calcTotal();

}

$(document).on('click', '.eliminart', function () {
     var objCuerpo = $(this).parents().get(2);
    if ($(objCuerpo).find('tr').length == 1) {
        if (!confirm('Esta es el única fila de la lista ¿Desea eliminarla?')) {
            return;
        }
    }
     var objFila = $(this).parents().get(1);
    $(objFila).remove();
    calcTotal();
});

function getProveedor(){
    $.post(url_base+'',function (data) {

    },'json');
}
function  getAlmacen() {
    $.post(url_base+'',function (data) {

    },'json');
}

function calcTotal() {
    var total=0;
    $(".subtotal").each(function () {
        total += parseFloat($(this).val())||0;
    });
    
    $("#total").html(total.toFixed(2))
}
 
function validateForm() {
    var bol=true;
    var prov=$('#proveedor');
    var alma=$('#almacen') ;
    prov.requiredSelect();
    alma.requiredSelect();
    bol= bol && prov.requiredSelect();
    bol= bol && alma.requiredSelect();

    $(".insum").each(function () {
        $(this).requiredSelect();
        bol=bol && $(this).requiredSelect();

    });
    $(".cant").each(function () {
        $(this).required();
        bol=bol && $(this).required();
    });

    return bol;
}



function btnSave(id){
    var idd=parseInt(id);
    var bol=validateForm();
    var status=null;

    console.log($('#formStock').serialize());
    alert(bol);
    if(bol){

        var dataForm=$('#formStock').serialize();
        $.post(url_base+'stock/setForm',dataForm,function (data) {
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
                $("#formStock")[0].reset();
            }
        });
    }else{
        alert_error("Complete los campos necesarios");
    }
}

function  editar(id){
    $('#btnSave2').remove();
    isEdit=1;
    var idd=parseInt(id);
    $.post(url_base+'stock/getData','id='+idd,function (data) {
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



        $("#model").val(data.modelo);
        $("#marca").val(data.marca);
        $("#desc").val(data.descripcion);
        $("#isEdit").val(1);
        $("#idEdit").val(data.id_cliente);
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
    if(confirm('¿Esta seguro?')){
        var idd=parseInt(id);
        $.post(url_base+'stock/deleteData','id='+idd,function (data) {
            console.log(data);
        },'json').success(function () {
            alert_success('Se ejecuto Correctamente');
            refrescar();
        });
    }
}

$(document).ready(function () {

    $('#btnAddxxx').on('click',function () {
        alert('culo');
    })
});

 