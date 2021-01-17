<!DOCTYPE html>
<html lang="es">
     <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Sys</title>


        <!--STYLESHEET-->
        <!--=================================================-->

        <!--Open Sans Font [ OPTIONAL ]-->

        <!--jQuery [ REQUIRED ]-->


        <!--Bootstrap Stylesheet [ REQUIRED ]-->
        <link href="<?=base_url()?>assets/theme/css/bootstrap.min.css" rel="stylesheet">


        <!--Nifty Stylesheet [ REQUIRED ]-->
        <link href="<?=base_url()?>assets/theme/css/nifty.min.css" rel="stylesheet">


        <!--Nifty Premium Icon [ DEMONSTRATION ]-->
        <link href="<?=base_url()?>assets/theme/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
         <link href="<?=base_url()?>assets/theme/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">


        <!--=================================================-->



        <!--Pace - Page Load Progress Par [OPTIONAL]-->
        <link href="<?=base_url()?>assets/theme/plugins/pace/pace.min.css" rel="stylesheet">



        <!--Demo [ DEMONSTRATION ]-->
        <link href="<?=base_url()?>assets/theme/css/demo/nifty-demo.min.css" rel="stylesheet">



        <!--Custom scheme [ OPTIONAL ]-->
        <link href="<?=base_url()?>assets/theme/css/themes/type-c/theme-navy.min.css" rel="stylesheet">

        <script src="<?=base_url()?>assets/theme/js/jquery-2.2.4.min.js"></script>
        <!--BootstrapJS [ RECOMMENDED ]-->
        <script src="<?=base_url()?>assets/theme/js/bootstrap.min.js"></script>
        <script src="<?=base_url()?>assets/theme/plugins/pace/pace.min.js"></script>
        <!--NiftyJS [ RECOMMENDED ]-->
        <script src="<?=base_url()?>assets/theme/js/nifty.min.js"></script>
        <script src="<?=base_url()?>assets/scripts/funciones.js"></script>


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
        <script type="text/javascript">var url_base="<?=base_url()?>"; </script>
         <style>
             ul.ui-autocomplete {
                 z-index: 2100;
             }
             .modal { overflow: auto !important; }
         </style>
    </head>

    <!--TIPS-->
    <!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->
    <body  style="background-color: #ecf0f5;" >
    <div id="container" class="effect aside-float aside-bright mainnav-lg ">

        <!--NAVBAR-->
        <!--===================================================-->
        
        <header id="navbar" style="z-index: 1002">
            <div id="navbar-container" class="boxed">

                <!--Brand logo & name-->
                <!--================================-->
                <div class="navbar-header">
                    <div class="navbar-brand">
                        <img src="<?=base_url()?>" alt="" class="brand-icon">
                        <div class="brand-title">
                            <a href="<?=base_url()?>admin" style="color: white"; class="brand-text">Admin </a>
                        </div>
                    </div>
                </div>
                <!--================================-->
                <!--End brand logo & name-->


                <!--Navbar Dropdown-->
                <!--================================-->
                <div class="navbar-content">
                    <ul class="nav navbar-top-links">

                        <!--Navigation toogle button-->
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <li class="tgl-menu-btn">
                            <a class="mainnav-toggle" href="#">
                                <i class="demo-pli-list-view"></i>
                            </a>
                        </li>

                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <!--End Navigation toogle button-->

                    </ul>
                    <ul class="nav navbar-top-links">

                        <li id="dropdown-user" class="dropdown">
                            <a   href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                                <span class="ic-user pull-right">
                                    <i class="demo-pli-male"></i>
                                </span>
                            </a>


                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right panel-default">
                                <ul class="head-list">
                                    <li>
                                        <a href="#"><i class="demo-pli-male icon-lg icon-fw"></i> Profile</a>
                                    </li>
                                    <li>
                                        <a href="<?=base_url()?>users/logout"><i class="demo-pli-unlock icon-lg icon-fw"></i> Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <!--================================-->
                <!--End Navbar Dropdown-->

            </div>
        </header>
        <!--===================================================-->
        <!--END NAVBAR-->

        <div class="boxed">
            <!--MAIN NAVIGATION-->
            <!--===================================================-->
            <nav id="mainnav-container">
                <div id="mainnav">

                    <!--Menu-->
                    <!--================================-->
                    <div id="mainnav-menu-wrap">
                        <div class="nano">
                            <div class="nano-content">

                                <!--Profile Widget-->
                                <!--================================-->
                                <div id="mainnav-profile" class="mainnav-profile">
                                    <div class="profile-wrap text-center">
                                        <div class="pad-btm">
                                            <img class="img-circle img-md" src="img/profile-photos/1.png" alt="Profile Picture">
                                        </div>
                                        <a href="#profile-nav" class="box-block" data-toggle="collapse" aria-expanded="false">
                                            <span class="pull-right dropdown-toggle">
                                                <i class="dropdown-caret"></i>
                                            </span>
                                            <p class="mnp-name"><?= $this->session->userdata('usuario')?></p>
                                            <span class="mnp-desc"><?= $this->session->userdata('usuario')?></span>
                                        </a>
                                    </div>
                                </div>


                                <ul id="mainnav-menu" class="list-group">
                                    <!--Category name-->
                                    <li class="list-header">Acesso Rápido</li>

                                    <?php if($this->session->userdata('id_role') != 20){ ?>

                                        <li>
                                            <a href="<?=base_url()?>facturacion">
                                                <i class="fa fa-calculator"></i>
                                                <span class="menu-title">
                                            <strong>Facturación</strong>
                                        </span>
                                            </a>

                                        </li>

                                    <?php } ?>
                                    <!--Category name-->
                                    <li class="list-header">Menú</li>


                                    <?php foreach ($_menu_padre as $m_padre)  {      ?>

                                        <li>
                                            <a href="#">
                                                <i class="demo-psi-split-vertical-2"></i>
                                                <span class="menu-title">
												<strong><?=$m_padre['nombre']?> </strong><input type="hidden" value="<?=$m_padre['id_modulo']?>"/>
											</span>
                                                <i class="arrow"></i>
                                            </a>
                                            <!--Submenu-->
                                            <?php foreach ($_menu_hijo as $m_hijo):?>
                                                <?php if($m_padre['id_modulo'] == $m_hijo['id_modulo_padre'] ):?>
                                                    <ul class="collapse">
                                                        <li><a href="<?=base_url()?><?=$m_hijo['url']?>"><?=$m_hijo['nombre']?></a></li>
                                                    </ul>
                                                <?php endif;?>
                                            <?php endforeach;?>

                                        </li>

                                    <?php } ?>


                            </div>
                        </div>
                    </div>
                    <!--================================-->
                    <!--End menu-->

                </div>
            </nav>
            <!--===================================================-->
            <!--END MAIN NAVIGATION-->

            <!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container"  style="background-color: #ecf0f5;" >

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
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->


            <!--ASIDE-->
            <!--===================================================-->

            <!--===================================================-->
            <!--END ASIDE-->




        </div>



        <!-- FOOTER -->
        <!--===================================================-->
        <footer id="footer">

            <!-- Visible when footer positions are fixed -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="show-fixed pad-rgt pull-right">
                You have <a href="#" class="text-main"><span class="badge badge-danger">3</span> pending action.</a>
            </div>



            <!-- Visible when footer positions are static -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <!-- Remove the class "show-fixed" and "hide-fixed" to make the content always appears. -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

            <p class="pad-lft">&#0169;<?=date("Y")?> </p>



        </footer>
        <!--===================================================-->
        <!-- END FOOTER -->


        <!-- SCROLL PAGE BUTTON -->
        <!--===================================================-->
        <button class="scroll-top btn">
            <i class="pci-chevron chevron-up"></i>
        </button>
        <!--===================================================-->
    </div>
   </body>




     <div class="modal fade" id="modalNotify" data-backdrop="false" role="dialog"  tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content" style="-webkit-box-shadow: -12px 15px 31px -8px rgba(0,0,0,0.75);
-moz-box-shadow: -12px 15px 31px -8px rgba(0,0,0,0.75);
box-shadow: -12px 15px 31px -8px rgba(0,0,0,0.75);" >

                 <!--Modal header-->
                 <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                     <h4 style="font-size: 30px" class="modal-title">Notificación</h4>
                 </div>

                 <!--Modal body-->
                 <div class="modal-body">
                     <p class="text-semibold text-main">Asunto</p>
                     <p id="descAsunto"></p>
                     <br>
                     <p class="text-semibold text-main">Mensaje</p>
                     <p id="descMensaje">
                     </p>

                 </div>

                 <!--Modal footer-->
                 <div class="modal-footer">

                 </div>
             </div>
         </div>
     </div>

     <div class="modal fade" id="modalPerfil" data-backdrop="false" role="dialog"  tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
         <div class="modal-dialog modal-lg">
             <div class="modal-content" style="-webkit-box-shadow: -12px 15px 31px -8px rgba(0,0,0,0.75);
-moz-box-shadow: -12px 15px 31px -8px rgba(0,0,0,0.75);
box-shadow: -12px 15px 31px -8px rgba(0,0,0,0.75);" >

                 <!--Modal header-->
                 <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                     <h4   class="modal-title"> </h4>
                 </div>

                 <!--Modal body-->
                 <div class="modal-body">
                     <div class="eq-height clearfix">

                         <div class="col-md-9 eq-box-md eq-no-panel">

                             <!-- Main Form Wizard -->
                             <!--===================================================-->
                             <div id="demo-main-wz">
                                 <!--nav-->
                                 <ul class="row wz-step wz-icon-bw wz-nav-off   wz-steps">
                                     <li id="tabs1" class="col-xs-4 active">
                                         <a id="atabs1" data-toggle="tab" href="#demo-main-tab1" aria-expanded="true">
                                             <span class="text-danger"><i class="demo-pli-information icon-2x"></i></span>
                                             <p class="text-semibold mar-no">Mi Perfil</p>
                                         </a>
                                     </li>
                                 </ul>
                                 <!--progress bar-->
                                 <div class="progress progress-xs">
                                     <div class="progress-bar progress-bar-primary" style="width: 25%; left: 0%; position: relative; transition: all 0.5s;"></div>
                                 </div>
                                 <!--form-->
                                 <form class="form-horizontal" method="post" id="formPerfilUsuario">
                                     <div class="panel-body">
                                         <div class="tab-content">
                                             <!--First tab-->
                                             <div id="demo-main-tab1" class="tab-pane active in">

                                                 <div class="form-group">
                                                     <label class="col-lg-2 control-label">Descripción de usuario<span style="color:red">*</span></label>
                                                     <div class="col-lg-10 control-label" style="text-align: left;font-weight: bold;" id="descripcionuser">

                                                     </div>
                                                 </div>
                                                 <div class="form-group">
                                                     <label class="col-lg-2 control-label">Usuario <span style="color:red">*</span></label>
                                                     <div class="col-lg-8 control-label "  style="text-align: left;font-weight: bold;" id="usuariouser">

                                                     </div>
                                                 </div>
                                                 <div class="form-group">
                                                     <label class="col-lg-2 control-label">Nueva Contraseña <span style="color:red">*</span></label>
                                                     <div class="col-lg-9 pad-no">
                                                         <div class="clearfix">
                                                             <div class="col-lg-4">
                                                                 <input type="password" class="form-control mar-btm" id="passuser" name="passuser" placeholder="Nueva contraseña">
                                                             </div>
                                                             <div class="col-lg-4 text-lg-right"><label class="control-label">Repita contraseña <span style="color:red">*</span></label></div>
                                                             <div class="col-lg-4"><input type="password" class="form-control" id="passuser2" name="passuser2" placeholder="Repita contraseña"></div>
                                                         </div>
                                                         <small id="helpUserPerfil" class="help-block" style="color: red;display: none">Las contraseñas no coinciden</small>

                                                     </div>
                                                 </div>
                                                 <div class="form-group">
                                                     <label class="col-lg-2 control-label">Email</label>
                                                     <div class="col-lg-9">
                                                         <input type="email" class="form-control" name="emailuser" id="emailuser" placeholder="Email">
                                                     </div>
                                                 </div>

                                                 <div class="form-group">
                                                     <label class="col-lg-2 control-label">Rol <span style="color:red">*</span></label>
                                                     <div class="col-lg-9 control-label" style="text-align: left;font-weight: bold;" id="roleuser" >

                                                     </div>
                                                 </div>
                                                 <div class="form-group">
                                                     <label class="col-lg-2 control-label">Cel./Tel.</label>
                                                     <div class="col-lg-9">
                                                         <input type="text" placeholder="" name="telefonouser" id="telefonouser" class="form-control">
                                                     </div>
                                                 </div>
                                                 <div class="form-group center-block" style="">
                                                     <label class="col-lg-2 control-label"></label>
                                                     <div class="col-lg-9">
                                                         <button data-dismiss="modal" class="btn btn-default" type="button" id="">Cancelar</button>
                                                         <button type="button" class="btn btn-primary" id="guardarUser">Guardar</button>
                                                     </div>

                                                 </div>

                                             </div>

                                         </div>
                                     </div>
                                 </form>
                             </div>
                             <!--===================================================-->
                             <!-- End of Main Form Wizard -->
                         </div>
                     </div>
                 </div>

                 <!--Modal footer-->
                 <div class="modal-footer">

                 </div>
             </div>
         </div>
     </div>
</html>


