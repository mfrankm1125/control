/**
 * Created by Frank on 02/05/2017.
 */
var titles={'addtitle':"Agregar Parametro de Análisis de Suelo" ,'updatetitle':"Editar Parametro de Análisis de Suelo"};
var titles2={'addtitle':"Agregar Tipo Parametro de Análisis de Suelo" ,'updatetitle':"Editar Tipo Parametro de Análisis de Suelo"};

var isEdit=null,idEdit=null;
var idcomb=null;
var iniTab2=0,tabSelect=1;
$(document).ready(function () {

    var rowSelection = $('#tabla_grid').DataTable({
        "ajax": url_base+'paramanalisisdesuelo/getDataTable',
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

                return full.abreviatura;
                }
            },
            { "data": "descripcion" },
            { "data": "fechacrea" },
            {  sortable: false,
                "render": function ( data, type, full, meta ) {
                    var id = full.id_paramanalisisdesuelo;

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
        },
        "pageLength": 25
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


function refrescar() {
    var rowSelection;
    if(tabSelect== 1){
       rowSelection  = $('#tabla_grid').DataTable();
    }
    if(tabSelect ==2){
        rowSelection  = $('#tabla_grid2').DataTable();
    }

    rowSelection.ajax.reload();
}


$(document).on('click','#btnAdd',function () {
    var t;
    var titulo;
    $('#btn2').html(setBtnSave2());
    $("#isEdit").val(0);
    $("#idEdit").val(0);
    //$("#formPAS")[0].reset();
    if(tabSelect ==1 ){
        t= _.template($("#tmpFormPAS").html()) ;
        titulo=titles['addtitle'];
        getTpAS();
    }
    if(tabSelect == 2){
        t= _.template($("#tmpFormTPPAS").html()) ;
        titulo=titles2['addtitle'];
    }

    $("#modalbody").html(t);
    $('#modalTitle').html(titulo);

    open_modal('modal_id');

       //alert(isActividad);
    //console.log();
});


function validateFormAS() {
    var bol=true;
    bol=bol&& $('#name').required();
    if(tabSelect == 1){
        bol=bol && $("#idtipoparamas").requiredSelect();
    }


    return bol;
}


function btnSave(id){
    var idd=parseInt(id);
    var bol=validateFormAS();
    var status=null;
    console.log($('#formPar').serialize());

    //bol=false;
    if(bol){

        var dataForm=$('#formPar').serialize();

        $.post(url_base+'paramanalisisdesuelo/setForm',dataForm,function (data) {
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
                $("#formPar")[0].reset();
            }
        });
    }else{

    }
}

function selectCombo(idcombo,id){
    var idcom=idcombo;
    var op=idcom+" option";
    $(op).each(function(){
        if($(this).val() == id){ // EDITED THIS LINE
            //alert(item.profile_id);
            $(this).attr("selected","selected");

        }
    });
}

function getTpAS(){
    $.post(url_base+'Paramanalisisdesuelo/getTpAS',"id=1",function (data) {
        console.log(data);
        var ht="<option value=''>Seleccione ...</option>";
        $.each(data,function (key,item) {
            ht+="<option value='"+item.id_tipoparamanalisisdesuelo+"' >"+item.nombre+"</option>";
        });
        $("#idtipoparamas").html(ht);

    },'json').always(function () {
        if(idcomb != null && idcomb >0){
            console.log(idcomb);
            selectCombo('#idtipoparamas',idcomb);
        }
    });
}

function  editar(id){
    $('#btnSave2').remove();
    isEdit=1;
    alert(tabSelect);
    var idd=parseInt(id);
        $.post(url_base+'paramanalisisdesuelo/getData','id='+idd+"&tab="+tabSelect ,function (data) {
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
        var idEdit=null;
        var t=null;

        if(tabSelect== 1){
            t=_.template($("#tmpFormPAS").html());
            $("#modalbody").html(t);
            idcomb=parseInt(data.id_tipoparamanalisisdesuelo);
            getTpAS();
            $("#abreviatura").val(data.abreviatura);
            $("#orden").val(data.orden);
            idEdit=data.id_paramanalisisdesuelo;
        }
        if(tabSelect == 2){
            t=_.template($("#tmpFormTPPAS").html());
            $("#modalbody").html(t);

            idEdit=data.id_tipoparamanalisisdesuelo;
        }
        $("#name").val(data.nombre);
        $("#desc").val(data.descripcion);
        $("#isEdit").val(1);
        $("#idEdit").val(idEdit);

    }else {
        alert_error("Ocurrio un error")
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
        $.post(url_base+'paramanalisisdesuelo/deleteData','id='+idd+'&tab='+tabSelect,function (data) {
            console.log(data);
        },'json').success(function () {
            alert_success('Se ejecuto Correctamente');
            refrescar();
        });
    }
}

$(document).on('click','#tab1',function () {
    tabSelect=1;
});

$(document).on('click','#tab2',function () {
    if(iniTab2==0) {
        iniTab2=1;
        tabSelect=2;
        var rowSelection2 = $('#tabla_grid2').DataTable({
            "ajax": url_base + 'paramanalisisdesuelo/getDataTable2',
            "columns": [
                {"data": null},
                {
                    "render": function (data, type, full, meta) {
                        var name = full.nombre;
                        //var lastname = full.apellidos;
                        var html = "" + name;
                        return html;
                    }
                },
                {
                    "render": function (data, type, full, meta) {
                        var isp = full.ispropietario;

                        return "s";
                    }
                },
                {"data": "fechacrea"},
                {
                    sortable: false,
                    "render": function (data, type, full, meta) {
                        var id = full.id_tipoparamanalisisdesuelo;

                        html = '<a href="javascript:void(0)"  onclick="ver(' + id + ');" class="btn btn-info btn-xs"><i class="fa fa-file-text-o"></i> Ver</a>';
                        //html='<a href="javascript:void(0)"  onclick="vercamp('+id_x +',\''+placeid+'\');" class="btn btn-mint btn-xs"><i class="demo-psi-pen-5 icon-lg"></i></a>';
                        html = html + '&nbsp; <a href="javascript:void(0)" onclick="editar(' + id + ');"  class="btn btn-mint btn-icon btn-xs"><i class="demo-psi-pen-5 icon-xs"></i> Editar</a>';
                        html = html + '&nbsp; <a href="javascript:void(0)" onclick="eliminar(' + id + ');"  class="btn btn-danger btn-icon btn-xs"><i class="demo-psi-recycling icon-xs"></i> Eliminar</a>';
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

        $('#tabla_grid2').on('click', 'tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            }
            else {
                rowSelection2.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });

        rowSelection2.on('order.dt search.dt', function () {
            rowSelection2.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

    }
});



