/**
 * Created by Frank on 02/05/2017.
 */
var titles={'addtitle':"Agregar Herramienta" ,'updatetitle':"Editar Herramienta"};
var isEdit=null,idEdit=null;
$(document).ready(function () {

    var rowSelection = $('#tabla_grid').DataTable({
        "ajax": url_base+'herramienta/getDataTable',
        "columns": [
            { "data": null },
            {  "render": function ( data, type, full, meta ) {
                var name = full.nombre;
                //var lastname = full.apellidos;
                var html=""+name;
                return html;
            }
            },
            { "render": function ( data, type, full, meta ) {
                var isp = full.ispropietario;
                var html="";
                if(isp == 1){
                     html="Propio";
                }if(isp == 0){
                    html="Alquilado";
                }
                //var lastname = full.apellidos;

                return html;
                }
            },
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
    $('#btn2').html(setBtnSave2());
    $("#isEdit").val(0);
    $("#idEdit").val(0);
    $("#formHerramienta")[0].reset();

    $("#alq").removeAttr("checked");
    $("#pro").prop("checked", true);
    $('#modalTitle').html(titles['addtitle']);
    open_modal('modal_id');
    setDivIsProp();

       //alert(isActividad);
    //console.log();
});

function  setDivIsProp() {
    var tt ="";
    var isPropio=$('input:radio[name=isPropio]:checked').val();
    if(isPropio == 1){
        tt=_.template($("#tmpDivIsProp").html());
    }
    if(isPropio == 0 ){
        tt=_.template($("#tmpDivIsntProp").html());
    }

    $("#divisProp").html(tt);

}

$(document).on('change','input:radio[name=isPropio]',function () {
    var isPropio=$(this).val();
    setDivIsProp();
});

function validateForm() {
    var bol=true;
    bol=bol&& $('#name').required();
    return bol;
}

$(document).on('click','#btnSave1',function () {
    var bol=validateForm();
    if(bol){

    }
});

$(document).on('click','#btnSave2',function () {

});

function btnSave(id){
    var idd=parseInt(id);
    var bol=validateForm();
    var status=null;

    console.log($('#formHerramienta').serialize());

    //bol=false;
    if(bol){

        var dataForm=$('#formHerramienta').serialize();
        $.post(url_base+'herramienta/setForm',dataForm,function (data) {
            console.log(data);
            if(data === "ok"){
                status=true;
                alert_success("Se ejecuto correctamente ...");
            }else{
                alert_success("Error");
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
    $.post(url_base+'herramienta/getData','id='+idd,function (data) {
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
        var isProp=parseInt(data.ispropietario);
        $("#name").val(data.nombre);
        if(isProp == 1){
            $("#alq").removeAttr("checked");
            $("#pro").prop("checked", true);
            setDivIsProp();

        }
        if(isProp == 0){
            $("#pro").removeAttr("checked");
            $("#alq").prop("checked", true);
            setDivIsProp();
            $("#costealq").val(data.costerefuso);
        }

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
        $.post(url_base+'herramienta/deleteData','id='+idd,function (data) {
            console.log(data);
        },'json').success(function () {
            alert_success('Se ejecuto Correctamente');
            refrescar();
        });
    }
}