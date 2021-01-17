


<!-- BACKGROUND IMAGE -->
<!--===================================================-->
<div class="row">

    <div class="col-lg-12">
        <div id="bg-overlay"></div>


        <!-- LOGIN FORM -->
        <!--===================================================-->

        <div class="cls-content" style="padding-top: 36px;">
            <div class="cls-content-sm panel pull-center">
                <div class="panel-body">


                    <?php //validation_errors('<div>','</div>') muesta todos los errres por haber ?>

                    <?= form_open('',['id'=>'form_x'],['login'=>1]) //campooculto?>
                    <?= form_hidden('loginx',2)?>


                    <div class="form-group">
                        <h2 style=" ">Inicie Sesión</h2>
                        <?=form_input(['id'=>'user','name'=> 'user','class'=>'form-control','placeholder'=>'usuario'],set_value('user'))?>
                        <?=form_error('user') ?>
                    </div>


                    <p>

                        <?=form_password(['id'=>'password','name'=> 'password','class'=>'form-control','placeholder'=>'password'])?>
                        <?= form_error('password') ?>

                    </p>
                    <?php //form_submit(['value'=>$this->lang->line('cms_general_label_button_access'),'class'=>'btn btn-primary btn-lg btn-block']) ?>

                    <?=form_submit(['value'=>"Ingrese",'class'=>'btn btn-mint btn-lg btn-block'])?>


                    <?=form_fieldset_close()?>

                    <?=form_close()?>



                </div>

                <div class="pad-all">

                    <div class="media pad-top bord-top">
                        <div class="pull-right">

                        </div>
                        <div class="media-body text-left">

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

