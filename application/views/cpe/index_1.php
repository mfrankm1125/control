<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div id="page-content" style="text-align: left">

    <div class="row ">
    <br>
    <h1 style="text-align: center;">Comprobante de pagos electrónicos</h1></div>
    <div class="row ">
        <div class="col-sm-1"></div>
        <div class="col-sm-3">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Buscar Comprobantes</h3>
                </div>

                <!--Block Styled Form -->
                <!--===================================================-->
                <form id="formComp">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">RUC EMISOR</label>
                                    <input disabled name="rucemisor" id="rucemisor"  type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Tipo Comprobante</label>
                                    <select name="tipocomprobante" id="tipocomprobante"  class="form-control">
                                        <option value="0">Seleccione...</option>
                                        <option value="03">Boleta</option>
                                        <option value="01">Factura</option>
                                        <option value="07">Nota de Crédito</option>
                                        <option value="08">Nota de Debito</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Serie-Número <span style="font-size: 11px;"> Ejem: (F001-00000000)</span></label>
                                    <div id="demo-dp-range">
                                        <div class="input-daterange input-group" id="datepicker">
                                            <input style="text-align: right;" name="serie" id="serie" type="text" minlength="4" maxlength="4" class="form-control"  >
                                            <span class="input-group-addon"><b style="font-size: 20px;">-</b></span>
                                            <input name="num" id="num" type="text" maxlength="8" class="form-control"  >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Total (S/)</label>
                                    <input name="total" id="total" type="number" style="text-align: right;" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-12" style="text-align: center;">
                                <div data-callback="recaptcha_callback" class="g-recaptcha" data-sitekey="6LfzntwUAAAAAEXxDxuJL9y_ZkVcAi2p4UKQWj6f"></div>
                            </div>
                            <div class="col-sm-12">
                                <br>
                                <button   id="btnBuscar" type="button" class="btn btn-mint btn-lg" style="width: 100%;">BUSCAR</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!--===================================================-->
                <!--End Block Styled Form -->

            </div>
        </div>
        <div class="col-sm-7">
            <div class="panel">

                <!--Horizontal Form-->
                <!--===================================================-->

                    <div class="panel-body" id="divResultado" style="height: 500px;">
                       ...
                    </div>

                <!--===================================================-->
                <!--End Horizontal Form-->

            </div>
        </div>
        <div class="col-sm-1"></div>
    </div>
</div>

<script type="text/javascript" >



    function recaptcha_callback(){
        $('#btnBuscar').prop("disabled", false);
    }


    $(document).on("click","#btnBuscar",function () {
        var ctx=$(this);
        let tipocomprobante=$("#tipocomprobante");
        let serie=$("#serie");
        let num=$("#num");
        let total=$("#total");
        let bol=true;

        bol=bol&&tipocomprobante.required();
        bol=bol&&serie.required();
        bol=bol&&num.required();
        bol=bol&&total.required();

        if(!bol){
            alert("Rellene los campos necesarios");
            return 0;
        }

        var response = grecaptcha.getResponse();
        /*if(response.length == 0){
            alert("Resuelve el capcha");
         }else{
            $.post("json");


         } */

        ctx.button("loading");
        var form=$("#formComp").serialize();
        var imgif="<img src='<?=base_url()?>assets/images/loading/load.gif'>";
        $("#divResultado").html(imgif);
        $.post("<?=base_url()?>cpe/getDataComprobante",form,function (data) {
             $("#divResultado").html(data);
            ctx.button("reset");
        },'html');

    });




    $(function() {
        $.fn.required = function() {
            if ( $(this).val() == ''   || $(this).val() == null || Number($(this).val()) == 0  ) {
                $(this).css('border','1px solid #c50606');
                $(this).focus();
                return false;
            }else {
                $(this).css('border','solid 1px #ccc');
                return true;
            }
        };
    });

</script>