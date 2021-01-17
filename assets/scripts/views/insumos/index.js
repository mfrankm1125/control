
var nameModal={'title0':"Agregar Insumo",'title1':"Editar Insumo"};
var isEdit=null,idEdit=null;
var dataIniTPAQ=null;

$(document).on('ready',function () {
   //  $('#idtpinsumo').chosen({width:'100%'});
    $('[data-toggle="tooltip"]').tooltip();
    // $('#idtpinsumo').select2();
    var rowSelection = $('#tabla_grid').DataTable({
        "ajax": url_base+'insumos/getDataInsumoTable',
        "columns": [
            { "data": null },
            { "data": "nombre" },
            { "data": "ntinsumo" },
            { "data": "cantidad" },
            { "data": "precio" },
            { "data": "nunidad" },
            { "data": "nalmacen" },
            { "data": "fechacrea" },
            {  sortable: false,
                "render": function ( data, type, full, meta ) {
                    var id = full.id_insumo;

                    html='<button type="button" onclick="addStock('+id+');"   class="add-tooltip btn btn-pink btn-xs " data-toggle="tooltip" data-container="body" data-placement="right" data-original-title="Agregar Stock"><i class="fa fa-file-text-o"></i>Agregar Stock</button>';
                    html+='&nbsp; <a href="javascript:void(0)"  onclick="ver('+id+');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>';
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

    setSelectTPI(0);


});

function setSelectTPI(idd){
    var i=parseInt(idd);
    var html="";
    $.post(url_base+'insumos/getTipoInsumo',function (data) {
        //console.log(data);
        html=data;
        $("#idtpinsumo").html(html);
        if(i > 0){
            selectCombo("#idtpinsumo",i);
        }

    }).always(function () {

    });

}

$(function () {

    // se ejecuta primero no espera el dom Ready

});

function addStock(id) {
    open_modal("modal_stock");
    setBodyStock("#modalbody");
    $("#idStock").val(id);

}

function refrescar () {
    var rowSelection = $('#tabla_grid').DataTable();
    rowSelection.ajax.reload();
}


$(document).on('change',"#idtpinsumo",function () {
    var x=$(this).val();
    var valTI=$("#idtpinsumo option:selected").attr("data-id");
    setBodyFormInsum(valTI);
    setBodyStock("#bodyStock");
    if(x ==0  ){
        $("#bodyStock").html("");
    }


});

function setBodyStock(div){
   var tt = _.template($("#tmpBodyStock").html());
    $(div).html(tt);
}


function selectCombo(idcombo,id){
    var idcom=idcombo;
    var op=idcom+" option";
    $(op).each(function(){
        if($(this).val() == id){ // EDITED THIS LINE
            //alert(item.profile_id);
            $(this).attr("selected","selected");
            $(idcom).trigger('chosen:updated');
        }
    });
}


function setBodyFormInsum(id){

    var tt="";
    //var opx=id.split("-");
    var op =""+id;
    //alert(op);
    switch (op){
        case "fe" :
            tt = _.template($("#tmpFert").html());
            $("#bodyFormSelect").html(tt);
            if(isEdit == null ){
                getUnidadMedidas();
            }

            break;
        case "se":
            tt = _.template($("#tmpSemilla").html());
            $("#bodyFormSelect").html(tt);
            if(isEdit == null ) {
                getUnidadMedidas();
                getCultivo();
            }
            break;
        case "aq":
            tt = _.template($("#tmpAgroq").html());
            $("#bodyFormSelect").html(tt);
            if(isEdit == null ){
                getTipoAgroquimico();
                getUnidadMedidas();
            }
            break;
        case "ot":
            tt = _.template($("#tmpSemilla").html());
            $("#bodyFormSelect").html(tt);
            break;
        default:
            $("#bodyFormSelect").html("");
            break;
    }
}



function setForm(data){
    var op=data.tpiabreviatura;
    var i=parseInt(data.id_unidadmedida);
    var ii=parseInt(data.id_cultivo);
    var iii=parseInt(data.id_tipoinsumo);
    var iiii=parseInt(data.id_tipoagroquimico);
    var idProv=parseInt(data.id_proveedor);
    var idAlma=parseInt(data.id_almacen);
    var cant=parseInt(data.cantidad);
    var preci=parseInt(data.precio);
   // alert(data.id_tipoinsumo);
    switch (op){
        case "se":

            setSelectTPI(iii);
            //selectCombo('#idtpinsumo',data.id_tipoinsumo);
            setBodyFormInsum(op);
            getUnidadMedidas(i);
            getCultivo(ii);

            $('#ninsumo').val(data.nombre);
            $('#variedad').val(data.variedadsemilla);
            $('#pgermi').val(data.id_tipoinsumo);
            $('#desc').val(data.descripcion);

            break;
        case "aq":

            setSelectTPI(iii);
            setBodyFormInsum(op);
            getUnidadMedidas(i);
            getTipoAgroquimico(iiii);
            $('#ninsumo').val(data.nombre);
            $('#funcion').val(data.funcion);
            $('#composicion').val(data.composicion);
            $('#desc').val(data.descripcion);


            break;
        case "fe":
            setSelectTPI(iii);
            setBodyFormInsum(op);
            getUnidadMedidas(i);

            $('#ninsumo').val(data.nombre);
            $('#funcion').val(data.funcion);
            $('#composicion').val(data.composicion);
            $('#desc').val(data.descripcion);
            break;
        case "ot":

            break;
        default:break;
    }

    setBodyStock("#bodyStock");
    selectCombo("#proveedor",idProv);
    selectCombo("#almacen",idAlma);
    $("#cant").val(cant);
    $("#precio").val(preci);
    calcTotal();
    $('#isEdit').val(1);
    $('#idEdit').val(data.id_insumo);
}

function getUnidadMedidas(idd){
    var i=parseInt(idd);
    $.post(url_base+'insumos/getUnidadMedida','id=1',function (data) {
        $("#idumedida").html(data);
        if(i>0){
            selectCombo("#idumedida",i);
        }

    });

}

function getTipoAgroquimico(idd){
    var i=parseInt(idd);
    $.post(url_base+'insumos/getTipoAgroquimico','id=1',function (data) {
         $("#idtpagroquimico").html(data);
        if(i>0){
            selectCombo("#idtpagroquimico",i);
        }
    });
}

function getCultivo(idd){
    var i=parseInt(idd);
    $.post(url_base+'insumos/getCultivo','id=1',function (data) {
        $("#idcultivo").html(data);
        if(i>0){
            selectCombo("#idcultivo",i);
        }
    });
}



//validates
function validaForm(){
    var bol=true;
    bol=bol&&$('#ntipoinsumo').required();
    return bol;
}

function validateFormF(id){

    var bol=true;

    bol=bol &&$('#idtpinsumo').requiredSelect();
    if(id == "aq") bol=bol &&$('#idtpagroquimico').requiredSelect();
    if(id == "se") bol=bol &&$('#idcultivo').requiredSelect();
    bol=bol &&$('#ninsumo').required();
    if(id == "se") bol=bol &&$('#variedad').required();
    bol=bol &&$('#idumedida').requiredSelect();
    if(id =="aq" || id =="fe" ) bol=bol &&$('#funcion').required();
    //bol=bol &&$('#funcion').required();
    //bol=bol &&$('#composicion').required();


    return bol;

}

function validateStock() {
    var bol=true;


}



$(document).on('click','#addBtn',function () {
    isEdit=null;
    $('#idtpinsumo').prop('selectedIndex',0);
    $('#formInsumo')[0].reset();
    $('#bodyFormSelect').html("");
    $('#bodyStock').html("");
    $('#isEdit').val(0);
    $('#idEdit').val(0);
    $('#modalTitle').html(nameModal['title0']);
    setSelectTPI(0);
    open_modal("modal_id");
});

$(document).on('click','#btnSaveStock',function () {
    var dataStock=$("#formStock").serialize();
    alert(dataStock);

    $.post(url_base+'insumos/setStock',dataStock,function (data) {
        console.log(data);
    },'json');
});


$(document).on('click','#btnSave',function () {

   // var bol=validaForm();
    var idTPI=$("#idtpinsumo option:selected").attr("data-id");
    var bol=validateFormF(idTPI);
    //alert($('#formInsumo').serialize());
     alert(bol);
    if(bol){
        var dataForm=$('#formInsumo').serialize();
       // alert(dataForm);
      $.post(url_base+'insumos/setForm',dataForm+'&tagtpinsumo='+idTPI,function (data) {
         console.log(data);

          if( data === "ok"){
              refrescar();
              alert_success('Se ejecuto correctamente ...');
          }else{
              alert_error('Se produjo un error...');
          }

         }).always(function (data) {

          close_modal("modal_id");
         });
    }

});


function  editar(id){
    isEdit=1;
    var idd=parseInt(id);
        $.post(url_base+'insumos/getDataInsumo','id='+idd,function (data) {
            console.log(data);
            setForm(data[0]);
            open_modal('modal_id');

        },'json')
            .always(function () {

             });

}

function ver(id){

}

function eliminar(id) {
    if(confirm('¿Esta seguro?')){
        var idd=parseInt(id);
        $.post(url_base+'insumos/deleteInsumo','id='+idd,function (data) {
            console.log(data);
        },'json').success(function () {
            alert_success('Se ejecuto Correctamente');
            refrescar();
        });
    }
}


//Stock_________________________________

$(document).on('keyup','#cant',function () {
    calcTotal();
});
function calcTotal(){
    var total=null;
    var precio=parseFloat($("#cant").val()) || 0;
    var cant=parseFloat($("#precio").val())  || 0;
    total=parseFloat(cant*precio)|| 0;
    total=total.toFixed(2);
    $("#total").html(total);
}
$(document).on('keyup','#precio',function () {
    calcTotal();
});

