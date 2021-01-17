$('#tabla_grid').on( 'click', 'tr', function () {
    var rowSelection = $('#tabla_grid').DataTable();
    if ( $(this).hasClass('selected') ) {
        $(this).removeClass('selected');
    }
    else {
        rowSelection.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
    }
} );


/*$(window).load(function() {
    // executes when complete page is fully loaded, including all frames, objects and images
    alert("window is loaded");
});*/

$(document).on('click','#addx',function () {
    $('#formAddModulo')[0].reset();
    $('#sel_m').trigger("chosen:updated");
    $('#sel_accion').selectpicker('refresh');
    $('#titulo_mod').html('Agregar Módulo');

    $('#modal_id').modal('show');

});

function datos_modulos(){
    $.post(url_base+'modulos/modulos_json',function(data){
        console.log(data);
        var html='';
        for(var i=0;i<data.length ; i++){
            html+='<tr>';
            html+='<td>'+i+'</td>';
            html+='<td>'+data[i].modulo_hijo+'</td>';
            html+='<td>'+data[i].url+'</td>';
            html+='<td>'+data[i].modulo_padre+'</td>';
            html+='<td>'+data[i].icono+'</td>';
            html+='<td>'+data[i].orden+'</td>';
            html+='<td>x</td>';
            html+='</tr>';
        }
        //t = _.template($("#t_body_table").html());
        //$("#table_body").html(t({data:data}));

        $("#table_body").html(html);
    },'json')
}

function refrescar(){
    var rowSelection = $('#tabla_grid').DataTable();
    rowSelection.ajax.reload();
}

$(document).on('click','#add_permx',function(){
    var valMod=$('#id_mod').val();
    var bol=requiredSelect("sel_perm");
    if(bol){
        var valSel=$('#sel_perm').val();
        //alert(valSel);
        $.post(url_base+'modulos/add_perm_modul','id='+valMod+'&perm='+valSel,function(data){console.log('ok');},'json ');
    }else{
        alert('Seleccione uno');
    }
    //muestra_sel_perm(valMod);
    muestra_tabla_perm(valMod);

});

function permisos(id,modul){
    var m='Permisos- '+String(modul);
    $('#id_mod').val(id);
    $('#modal_ver').modal('show');
    $('#titulo_x').html(m);

    muestra_sel_perm(id);
    muestra_tabla_perm(id);

}

function muestra_sel_perm(id){
    $.post(url_base+'modulos/sel_perm','id='+id,function(data2){

        sel_p = _.template($("#t_sel_perm").html());
        $("#add_sel").html(sel_p({data:data2.data}));
        $('#sel_perm').chosen({width:'100%'});
    },'json');
}

function muestra_tabla_perm(id){
    $.post(url_base+'modulos/perm_modul','id='+id,function (data) {
        if(data.status =="ok"){
            t = _.template($("#t_body_table").html());
            $("#perm_modul").html(t({data:data.data}));
        }else{
            alert_error();
        }
    },'json');
}

function eli_perm(id){
    var id_modulo= $('#id_mod').val();
    if(confirm('seguro?')){
        alert(id_modulo);
        $.post(url_base+'modulos/del_perm','id='+id ,function (data) {

        },'json');
        muestra_sel_perm(id_modulo);
        muestra_tabla_perm(id_modulo);
    }else{

    }
}

function guardar_mod() {
    var bol=true;
    bol= bol && $('#sel_m').requiredSelect();
    bol=bol && $('#nombre').required();
    bol=bol && $('#url').required();
    bol=bol && $('#sel_accion').requiredSelect();
    if(bol){

        var mod_p=$('#sel_m').val();
        var nombre=$('#nombre').val();
        var url=$('#url').val();
        var orden=$('#orden').val();
        var icono=$('#icono').val();
        var accion=$('#sel_accion').val();
        // var acciones=$('select[name="sel_accion"]').serialize();
        var id_e_m=parseInt($('#id_edit_m').val());
        if(isNaN(id_e_m)){

            id_e_m=null;
            $.post(url_base+'modulos/ins_mod','id_m='+mod_p+'&nombre='+nombre+'&url='+url+'&orden='+orden+'&icono='+icono+'&accion='+accion+'&id_edit_m='+id_e_m ,function(d){console.log(d);alert_success('Se Realizo Correcctamente ...'); refrescar();});

        }else{
            $.post(url_base+'modulos/ins_mod','id_m='+mod_p+'&nombre='+nombre+'&url='+url+'&orden='+orden+'&icono='+icono+'&accion='+accion +'&id_edit_m='+id_e_m ,function(d){console.log(d); refrescar();});
        }


         $("#modal_id").modal('hide');
    }else{
        alert_error('Ingrese los lotes necesarios');
    }

}



