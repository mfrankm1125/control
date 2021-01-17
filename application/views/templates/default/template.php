<!DOCTYPE html>
<html lang="">

 <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina de Inicio de Session </title>


    <!--STYLESHEET-->
    <!--=================================================-->

    <!--Open Sans Font [ OPTIONAL ]-->
    <!--<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>-->


    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="<?=base_url()?>assets/theme/css/bootstrap.min.css" rel="stylesheet">


    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="<?=base_url()?>assets/theme/css/nifty.min.css" rel="stylesheet">


    <!--Nifty Premium Icon [ DEMONSTRATION ]-->
    <link href="<?=base_url()?>assets/theme/css/demo/nifty-demo-icons.min.css" rel="stylesheet">



    <!--Demo [ DEMONSTRATION ]-->
    <link href="<?=base_url()?>assets/theme/css/demo/nifty-demo.min.css" rel="stylesheet">


    <!--Magic Checkbox [ OPTIONAL ]-->
    <link href="<?=base_url()?>assets/theme/plugins/magic-check/css/magic-check.min.css" rel="stylesheet">






    <!--JAVASCRIPT-->
    <!--=================================================-->

    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="<?=base_url()?>assets/theme/plugins/pace/pace.min.css" rel="stylesheet">
    <script src="<?=base_url()?>assets/theme/plugins/pace/pace.min.js"></script>


    <!--jQuery [ REQUIRED ]-->
    <script src="<?=base_url()?>assets/theme/js/jquery-2.2.4.min.js"></script>


    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="<?=base_url()?>assets/theme/js/bootstrap.min.js"></script>


    <!--NiftyJS [ RECOMMENDED ]-->
    <script src="<?=base_url()?>assets/theme/js/nifty.min.js"></script>






    <!--=================================================-->

    <!--Background Image [ DEMONSTRATION ]-->
    <script src="<?=base_url()?>assets/theme/js/demo/bg-images.js"></script>





    <!--=================================================

    REQUIRED
    You must include this in your project.


    RECOMMENDED
    This category must be included but you may modify which plugins or components which should be included in your project.


    OPTIONAL
    Optional plugins. You may choose whether to include it in your project or not.


    DEMONSTRATION
    This is to be removed, used for demonstration purposes only. This category must not be included in your project.


    SAMPLE
    Some script samples which explain how to initialize plugins or components. This category should not be included in your project.


    Detailed information and more samples can be found in the document.

    =================================================-->


</head>

<!--TIPS-->
<!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->

<body>
<div id="container" class="cls-container">

    <!-- BACKGROUND IMAGE -->
    <!--===================================================-->
    <div class="row">
        
        <div class="col-lg-12">
            <div id="bg-overlay"></div>


            <!-- LOGIN FORM -->
            <!--===================================================-->
            <div id="container" class="cls-container">
                <?php foreach ($_warning as $_msg1): ?>
                    <div class="alert alert-warning "><?=$_msg1?></div>
                <?php endforeach;?>
                <?php foreach ($_success as $_msg2): ?>
                    <div class="alert alert-success "><?=$_msg2?></div>
                <?php endforeach;?>
                <?php foreach ($_error as $_msg3): ?>
                    <div class="alert alert-danger "><?=$_msg3?></div>
                <?php endforeach;?>
                <?php foreach ($_info as $_msg4): ?>
                    <div class="alert alert-info "><?=$_msg4?></div>
                <?php endforeach;?>


                <?php foreach ($_content as $_view): ?>
                    <?php include $_view;?>
                <?php endforeach;?>

            </div>

        </div>

    </div>

</div>
<!--===================================================-->
<!-- END OF CONTAINER -->


</body>
 <footer id="footer">

     <!-- Visible when footer positions are fixed -->
     <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->




     <!-- Visible when footer positions are static -->
     <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
     <div class="hide-fixed pull-right pad-rgt">
         <a style="padding: 0px" class="btn btn-link" target="_blank" href="<?=base_url()?>assets/uploads/manualUsuarioPOImonitoreoPerfilAreaResp.pdf"></a><strong>Perú</strong> Reservado todos los derechos.<img  style="height: 14px ;margin-bottom: 2px" src="<?=base_url()?>assets/images/iconsBanderas/banderaperu.ico" >

     </div>



     <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
     <!-- Remove the class "show-fixed" and "hide-fixed" to make the content always appears. -->
     <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->




 </footer>
<!-- Mirrored from www.themeon.net/nifty/v2.5/pages-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 10 Oct 2016 17:49:50 GMT -->
</html>
