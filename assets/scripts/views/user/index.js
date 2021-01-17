

$(document).on('click','#addx',function(){
    var tmpBodyModaluser=_.template($("#tmpBodyModalUser").html());
    $("#form1").html(tmpBodyModaluser);
    loadDependecias(null);
   // $('#sel_r').trigger("chosen:updated");
    open_modal('modal_id');

});

 

$(document).on('keyup','#usuario',function(){
    var user=$(this).val();
    var userini=$("#usuarioini").val();
    var isEdit=parseInt($("#is_edita").val());
    var idEdit=parseInt($("#table_id").val());
    if(isEdit == 0){
        ifexist_user(user,isEdit,idEdit);
    }else if(isEdit == 1){
        if(userini!=user){
            ifexist_user(user,isEdit,idEdit);
        }

    }


return false;

});
function ifexist_user(user,isEdit,idEdit){

    $.post(url_base+'user/exist_user','user='+user+'&isEdit='+isEdit+"&idEdit="+idEdit,function(data){
        console.log(data);
        if(data === 1){
            $('#btncheck').css('display','none');
            $('#btnclose').css('display','block');
            $('#exist_us').css('display','block');
            $('#exist_user').val(0);
        }
        if(data === 0){
            $('#btnclose').css('display','none');
            $('#exist_us').css('display','none');
            $('#btncheck').css('display','block');
            $('#exist_user').val(1);
        }

    },'json');
}
$(document).on('click','#guardar',function(){
    var table_id=parseInt($('#table_id').val());
    var form=$('#form1').serialize();

    var bol=true;
    bol=bol && $('#nombrearearesp').required();
    bol=bol && $('#usuario').required();
     if(!isNaN(table_id)){
         /*alert("culos agrega");
        alert(table_id); */
    }else{
        bol=bol && exist_user();
    }
    bol=bol && $('#pass').required();
    bol=bol && $('#pass2').required();

    if(bol){
        if($('#pass').val() != $('#pass2').val()){
            bol=bol && false;
            alert('Contraseñas no son iguales');
        }else{
            bol=bol && true;
        }
    }

    bol=bol && $('#sel_r').requiredSelect();
    bol=bol&& $('#telefono').required();
    if(bol){

        //console.log(form);
        $.post(url_base+'user/i_ins_user',form,function (data) {
            console.log(data);
            alert_success('Se realizó correctamente');
            close_modal('modal_id');
            refrescar_grid('tabla_grid');
        },'json');


    }else{
        alert_error('Complete los lotes requeridos');
    }


});

function exist_user() {
    var exist_u=$('#exist_user').val();
    if(exist_u == 1){
        return  true;
    }else{
         return  false;
    }
}
function editar(idd){
    var tmpBodyModaluser=_.template($("#tmpBodyModalUser").html());
    $("#form1").html(tmpBodyModaluser);
    var id=parseInt(idd);
    $('#table_id').val(id);
    $('#is_edita').val('1');
    $.post(url_base+'user/o_data_edit','id='+id,function (data) {
        console.log(data,"hola");
         $('#nombrearearesp').val(data[0].nombrearearesponsable);
         $('#usuario').val(data[0].user);
        $('#usuarioini').val(data[0].user);
         /*$.post(url_base+'user/o_data_edit_pass','passw='+data[0].password,function(dat){
                //console.log(dat);
             $('#pass').val(dat);
             $('#pass2').val(dat);
         },'json');*/
        $('#pass').val(data[0].password);
        $('#pass2').val(data[0].password);
        $('#sel_r').val(data[0].role);
        $('#telefono').val(data[0].telefono1);
        $('#email').val(data[0].email);
        loadDependecias(data[0].iddependeciauser);
         //$('#sel_r').trigger("chosen:updated");

    },'json');
    open_modal('modal_id');
}

function refrescar() {
    refrescar_grid('tabla_grid');
}

function eliminar(idd){
    var id=parseInt(idd);
    if(confirm("Esta seguro que desea eliminar este registro")) {
        $.post(url_base + 'user/eliminar', 'id=' + id, function (d) {
            console.log(d);
        });

    }else{
        return false;
    }
    refrescar_grid('tabla_grid');
    refrescar_grid('tabla_grid');
    return false;
}

/**
 * Created by Frank on 08/11/2016.
 */
$(function()	{
    // Chosen

    // Draggable Multiselect
    $('#btnSelect').click(function()	{

        $('#selectedBox1 option:selected').appendTo('#selectedBox2');
        return false;
    });

    $('#btnRemove').click(function()	{
        $('#selectedBox2 option:selected').appendTo('#selectedBox1');
        return false;
    });

    $('#btnSelectAll').click(function()	{

        $('#selectedBox1 option').each(function() {
            $(this).appendTo('#selectedBox2');
        });

        return false;
    });

    $('#btnRemoveAll').click(function()	{

        $('#selectedBox2 option').each(function() {
            $(this).appendTo('#selectedBox1');
        });

        return false;
    });
});
