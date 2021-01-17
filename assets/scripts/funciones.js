/**
 * Created by Frank on 26/10/2016.
 */
$(document).on("ready",function () {

    
    //$('.modal-dialog').draggable();
});

function redondea(value) {
    var decimals=2;
    return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}
function menudata(url){
    $("#page-content").html('cargando...');

    $.post(url_base+'Testpdf/test',function (dataHtml) {
        window.history.replaceState({"id":1}, "", '/ci/stock');
        $("#page-content").html(dataHtml);
    });
    //$("#page-content").html(url);

}
var arrayMesesAbre=["","Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"] ;
var arrayMesesFull=["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"] ;
function quitarSaltoLinea(campo){
    //var res=campo.replace("\n"," ");
    var sin_salto = campo.split("\n").join("");
    return sin_salto
}


$(function() {
    $.fn.required = function() {
        if ( $(this).val() == '' || $(this).val() == 0 || $(this).val() == null  ) {
            $(this).css('border','1px solid #c50606');
            $('#msg').html('<label class="lbl_msg">Debes llenar todos los lotes necesarios</label>');
            alert_error("Campo requerido");
            $(this).focus();
            return false;
        }else {
            $(this).css('border','solid 1px #ccc');
            $('#msg').html('');
            return true;
        }
    };
});



function objectLen(data) {
    var tamanioObject = parseInt(Object.keys(data).length);
    return tamanioObject;
}

function onlyPositives(thisx){
    var ctx=thisx;
    var val=parseFloat(ctx.val()) || 0;
    var bol=true;
    if(val <= 0 ){
        ctx.val(0);
        alert_error("Ingrese números positivos por favor.");
        bol=false;
    }else{
        ctx.val(val);
    }
    //console.log(val);
    return bol
}

function arrayLen(data) {
    var tamanioObject = parseInt(data.length);
    return tamanioObject;
}

$(function() {
    $.fn.requiredFile = function() {
        if ( $(this).val() == '' || $(this).val() == 0 || $(this).val() == null || $(this).val() == undefined ) {
            $(this).css('border','1px solid #c50606');
            $('#msg').html('<label class="lbl_msg">Debes llenar todos los lotes necesarios</label>');
            alert_error("Campo requerido");
            $(this).focus();
            return false;
        }else {
            $(this).css('border','solid 1px #ccc');
            $('#msg').html('');
            return true;
        }
    };
});


function alert_error($mensaje){
    if(typeof mensaje ==="undefined"){
        mensaje="Ocurrio algo Inesperado";
    }
    $.niftyNoty({
        type: 'danger',
        title: 'Error.',
        message: $mensaje,
        container: 'floating',
        timer: 6000
    });

}
function formatDateDMY(f) {
    var d = new Date(f);
    //console.log(d,f);
    var dia=d.getUTCDate();
    var mes=(d.getUTCMonth()+1);
    var anio=d.getUTCFullYear();
    if (mes < 10) mes = '0' + mes;
    if (dia < 10) dia = '0' + dia;
    var fechaFormat=dia+"-"+mes+"-"+anio;
    return fechaFormat;
}

function formatDateYMD(f) {
    var mydate = new Date(f);
    var dia=mydate.getDate();
    var mes=(mydate.getMonth()+1);
    var anio=mydate.getFullYear();
    if (mes < 10) mes = '0' + mes;
    if (dia < 10) dia = '0' + dia;
    var ffxd=anio+"-"+mes+"-"+dia;
    return ffxd;
}

function formatDateDMYText(f) {
    var d = new Date(f);
    //console.log(d,f);
    var dia=d.getUTCDate();
    var mes=(d.getUTCMonth()+1);
    var anio=d.getUTCFullYear();
    if (dia < 10) dia = '0' + dia;
    var fechaFormat=dia+" de "+arrayMesesFull[mes]+" del "+anio;
    return fechaFormat;
}

function formatDate(date,withTime) {
    var strTime="";
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    if(withTime == 1){
        strTime= hours + ':' + minutes + ' ' + ampm;
    }

    return  date.getDate() + "-" +(date.getMonth()+1)+ "-"+ date.getFullYear() + "  " + strTime;
}

function alert_success($mensaje){
    if(typeof mensaje ==="undefined"){
        mensaje="Se realizó correctamente";
    }
    $.niftyNoty({
        type: 'success',
        title: 'Bien.',
        message: $mensaje,
        container: 'floating',
        timer: 6000
    });

}

(function($){
    $.open_modal=function(str){
        var str1="#"+str;
        $(str1).modal('show');
        }
})(jQuery);

(function($){
    $.close_modal=function(str){
        var str1="#"+str;
        $(str1).modal('hide');
    }
})(jQuery);



function open_modal(str){
    var str1="#"+str;
    $(str1).modal('show');
}
function close_modal(str){
    var str1="#"+str;
    $(str1).modal('hide');
}

function loadingGif(str){
    var str1="#"+str;
    $(str1).html('<div class="align-center"><img src="'+url_base+'assets/images/loading/loading.gif" /></div>');

}

function refrescar_grid(str){
    var str1="#"+str;
    var rowSelection = $(str1).DataTable();
    rowSelection.draw();
    rowSelection.ajax.reload();

    return false;
}

$(function(){

  $.fn.requiredSelect=function(){
      if ($(this).val() === '' || $(this).val() === 0 || $(this).val() === null ||  $(this).val() == "0") {
          $(this).css('border','solid 2px red');
          $(this).focus();
          $(this).parent().find('.chosen-container').css('border','solid 2px red');
          $("#sel_r_v").css('display', 'block');
          alert_error("El campo es requerido");
          //$(".chosen-container").css('border','solid 2px red');
          return false;
      } else {
          $(this).css('border','solid 1px #ccc');
          $(this).parent().find('.chosen-container').css('border','');
          $("#sel_r_v").css('display', 'none');
          //$(".chosen-container").css('border','');
          return true;
      }

  };


});

/*
(function($) {
    $.fn.required = function() {
        if ( $(this).val() == '' || $(this).val() == 0 ) {
            $(this).css('border','solid 2px red');
            $(this).focus();
            alert_error("Campo requerido");
            return false;
        }else {
            $(this).css('border','solid 1px #ccc');
            $('#msg').html('');
            return true;
        }
    };
})(jQuery);
*/


$(document).on('show.bs.modal', '.modal', function () {
    var zIndex = 1040 + (10 * $('.modal:visible').length);
    $(this).css('z-index', zIndex);
    setTimeout(function() {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
    }, 0);
});

function validaLatLng(latLng) {

    var bol=true;
    if( $.isNumeric(latLng)) {
        bol=true
    } else {
        bol=false;
    }
    return bol;
}