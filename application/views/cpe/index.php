<script src="https://www.google.com/recaptcha/api.js" async defer></script>


<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script type="text/javascript" src='<?=base_url()?>assets/scripts/jspdf.debug.js'></script>

<div id="page-content" style="text-align: left">

    <div class="row ">
    <br>
        <h1 style="text-align: center;"><u>Comprobante de pagos electrónicos</u></h1></div>
    <br>
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
                            <div class="col-sm-12" style="display: none">
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
                                <div data-callback="recaptcha_callback" class="g-recaptcha" data-sitekey="6LfJc6MZAAAAAOPryQQda6meuIIPboY9nMIiFtWi"></div>
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

                <div class="panel-heading">
                    <div id="divButtonsDD"  ></div>
                </div>
                <div class="panel-body" id="" style="min-height: 500pt;">

                </div>
                <div id="divResultado"  style='transform: scale(0.7);position: absolute; left:-100pt; top: -50pt; bottom: 0;   width: 650pt ;'  >

                </div>




                <!--===================================================-->
                <!--End Horizontal Form-->

            </div>
        </div>
        <div class="col-sm-1"></div>
    </div>
</div>

<script type="text/javascript" >
    var scale = 'scale(1)';
    document.body.style.webkitTransform =       // Chrome, Opera, Safari
        document.body.style.msTransform =          // IE 9
            document.body.style.transform = scale;     // General


    var onloadCallback = function() {
        grecaptcha.render('html_element', {
            'sitekey' : 'your_site_key'
        });
    };

    function recaptcha_callback(){
        $('#btnBuscar').prop("disabled", false);
    }


    $(document).on("click","#btnBuscar",function () {

        $("#divButtonsDD").html("");
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
        if(response.length == 0){
            alert("Resuelve el capcha");
            return 0;
         }else{



         }
        $("#divResultado").html("Buscando...");
        ctx.button("loading");
        var form=$("#formComp").serialize();
        var imgif="<img src='<?=base_url()?>assets/images/loading/load.gif'>";
        $("#divResultado").html(imgif);
        $.post("<?=base_url()?>cpe/getDataComprobante",form,function (data) {
             $("#divResultado").html(data.dtHtml);
             $("#divButtonsDD").html(data.dtHtmlButtons);
              ctx.button("reset");
            grecaptcha.reset();
        },'json');

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

    $(document).on("change","#num",function () {
    var ct=$(this);

    let nvl=(ct.val()).padStart(8 ,"0");

        ct.val(nvl);
    });

   function generate(ctx){
        var btn=$(ctx);
        btn.button("loading");
        var legend=document.getElementById('divResultado');
      
        /*var pdf = new jsPDF('p', 'pt', 'a4');
        pdf.setFontSize(18);
        pdf.Html(document.getElementById('html-2-pdfwrapper'),
            margins.left, // x coord
            margins.top,
            {
                // y coord
                width: margins.width// max width of content on PDF
            },function(dispose) {
                headerFooterFormatting(pdf, pdf.internal.getNumberOfPages());
            },
            margins);

        var iframe = document.createElement('iframe');
        iframe.setAttribute('style','position:absolute;right:0; top:0; bottom:0; height:100%; width:650px; padding:20px;');
        document.body.appendChild(iframe);

        iframe.src = pdf.output('datauristring'); */
        var options = {  'background': '#fff' };
        var pdf = new jsPDF('p', 'pt', 'a4');
        //pdf.setFontSize(5);


      //  pdf.setDisplayMode("220%","continuous","UseOutlines");
        pdf.html(legend,  {
            html2canvas:{
              width:200,
              fontsize:5
            },
            callback: function (pdf) {
                pdf.save("comprobante");
                btn.button("reset");
            }
        });
    };

</script>