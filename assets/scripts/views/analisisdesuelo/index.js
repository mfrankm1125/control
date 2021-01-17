/**
 * Created by Frank on 02/05/2017.
 */
var titles={'addtitle':"Agregar Análisis de suelo " ,'updatetitle':"Editar Análisis de suelo"};
var isEdit=null,idEdit=null,idcamp=null,idlote=null,dtdet;
$(document).ready(function () {

    $('#date').datepicker({
        autoclose: true,
        format: "dd-mm-yyyy",
        language: 'es'
    });



    var rowSelection = $('#tabla_grid').DataTable({
        "ajax": url_base+'analisisdesuelo/getDataTable',
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
                var desc = full.descripcion;


                return desc;
                }
            },
            { "data": "fechaanalisis" },
            {  sortable: false,
                "render": function ( data, type, full, meta ) {
                    var id = full.id_analisisdesuelo;

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

    function hola(){
        alert("culo");
    }




});


function setBtnSave2() {
    var ht='<a href="javascript:void(0)" onclick="btnSave(2,\''+"btnSave2"+'\')" type="button"  id="btnSave2" class=" btn btn-primary" >';
    ht+='Guardar y agregar otro';
    ht+='</a>';
    return ht;
}

function refrescar () {
    var rowSelection = $('#tabla_grid').DataTable();
    rowSelection.ajax.reload();
}

function getCampos() {
    $.post(url_base+'analisisdesuelo/getCampos',function (data) {
        console.log(data);
        var ht="<option value=''>Seleccione ...</option>";
        $.each(data,function (key,item) {
            ht+="<option value='"+item.id_campo+"' >"+item.nombre+"</option>";
        });
        $("#camp").html(ht);

    },'json').always(function () {
        if(idcamp != null && idcamp >0){
        selectCombo('#camp',idcamp);
        }
    });
}

function getLotes() {
    $.post(url_base+'analisisdesuelo/getLotes','id='+idcamp,function (data) {
        console.log(data);
        var ht="<option value=''>Seleccione ...</option>";
        $.each(data,function (key,item) {
            ht+="<option value='"+item.id_lote+"' >"+item.nombre+"</option>";
        });
        $("#lote").html(ht);

    },'json').always(function () {
        if(idlote != null && idlote >0){
            selectCombo('#lote',idlote);
        }

    });
}

$(document).on('change','#camp',function () {
    idcamp=$(this).val();
    idlote=null;
    getLotes();
});

$(document).on('click','#btnAdd',function () {
    idcamp=null;
    idlote=null;

    $('#btn2').html(setBtnSave2());
    $("#isEdit").val(0);
    $("#idEdit").val(0);
    $("#formAnalisisdesuelo")[0].reset();
    $('#modalTitle').html(titles['addtitle']);
    getCampos();
    open_modal('modal_id2');
    $('#date').datepicker('update',new Date());
    $('#date2').val(new Date());
    

       //alert(isActividad);
    //console.log();
});






function validateForm() {
    var bol=true;
    bol=bol&& $('#camp').requiredSelect();
    bol=bol&& $('#lote').requiredSelect();
    bol=bol&& $('#date').required();
    return bol;
}
function valDataIsIgual(){
    var d=$('input[name="valparamas[]"]').serializeArray();
    var xt=dtdet;
    //console.log(d,xt);

}

function btnSave(id,idBtn){
    var idd=parseInt(id);
    var bol=validateForm();
    var status=null;
    var bt="#"+idBtn;
    console.log($('#formAnalisisdesuelo').serialize());

    if(bol){

        valDataIsIgual();

        var dataForm=$('#formAnalisisdesuelo').serialize();
        var btn1=$("#btnSave1").button('loading');
        var btn2=$("#btnSave2").button('loading');
        var cancel=$("#btnCancel").button('loading');



        $.post(url_base+'analisisdesuelo/setForm',dataForm,function (data) {
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
                close_modal('modal_id2');
                btn1.button('reset');
                btn2.button('reset');
                cancel.button('reset');

            }
            if(idd == 2 && status == true ){
                refrescar();
                $("#formAnalisisdesuelo")[0].reset();
                btn1.button('reset');
                btn2.button('reset');
                cancel.button('reset');

            }
        });


    }else{
        alert_error("Rellene los campos !!!");
    }
}




function  editar(id){
    $('#formAnalisisdesuelo')[0].reset();
    $('#btnSave2').remove();
    isEdit=1;
    var idd=parseInt(id);
    $.post(url_base+'analisisdesuelo/getDataEdit','id='+idd,function (data) {
            console.log(data);
            setForm(data);
            $('#modalTitle').html(titles['updatetitle']);
            open_modal('modal_id2');

        },'json')
        .always(function () {

        });

}

function setForm(data){
    var valData=Object.keys(data).length;
    if(valData > 0){
        idcamp=parseInt(data.dataCamp[0].id_campo);
        idlote=parseInt(data.dataAn[0].id_lote);
        var idana=parseInt(data.dataAn[0].id_analisisdesuelo);
        var name=data.dataAn[0].nombre;
        var dataForm=data.dataDet;
        dtdet=dataForm;
        var fecha=data.dataAn[0].fechaanalisis;
        var desc=data.dataAn[0].descripcion;
        var f=new Date(fecha);
        f=f.getUTCDate()+"-"+(f.getUTCMonth()+1)+"-"+f.getUTCFullYear();


        $('#date').datepicker('setDate',f);
        $('#desc').val(desc);
        $('#name').val(name);
        getCampos();
        getLotes();

        var op='input[name="valparamas[]"]';
        $(op).each(function(){
            var zz=$(this);
             var d=zz.attr('data-id');
            $.each(dataForm,function (key,item) {
                var idp=item.id_paramanalisisdesuelo;
                var val=item.valor;
                if(d ==idp ){
                    zz.val(val);
                }
            });
           // console.log(d);
        });

        $("#idEdit").val(idana);
        $("#isEdit").val(1);


    }
}



function ver(id){
    open_modal('divModal3');

}


function eliminar(id) {
    if(confirm('¿Esta seguro?')){
        var idd=parseInt(id);
        $.post(url_base+'analisisdesuelo/deleteData','id='+idd,function (data) {
            console.log(data);
        },'json').success(function () {
            alert_success('Se ejecuto Correctamente');
            refrescar();
        });
    }
}


