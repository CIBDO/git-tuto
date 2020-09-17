<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
    {{ trans['My account'] }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url("index") }}"><i class="fa fa-dashboard"></i> {{ trans['Dashboard']}}</a></li>
        <li class="active">{{ trans['My profile'] }}</li>
    </ol>
</section>
<?php $this->flash->output() ?>
<!-- Main content -->
<section class="content">
    <div class="row">
     <div class="col-md-6">
        <div class="box box-widget widget-user">
        
        <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-maroon-gradient">
                <h3 class="widget-user-username">{{ trans['My profile'] }}</h3>
            </div>

                    <div class="widget-user-image">
                        <img class="img-circle" src="{{url('img/user.jpg')}}" alt="User Avatar">
                    </div>

                    <div class="box-footer">
                        <br />
                        <div class="col-md-12">
                            <?php echo $this->tag->form(array("account/editInfos", "class" => "form-horizontal")); ?>
                                <div class="form-group">
                                    
                                    <div class="col-sm-12">
                                        <label>Prénom</label>
                                        <?php echo $this->tag->textField(array("firstname", "class" => "form-control", "placeholder" => $trans['Name'])); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    
                                    <div class="col-sm-12">
                                        <label>Nom</label>
                                        <?php echo $this->tag->textField(array("lastname", "class" => "form-control", "placeholder" => $trans['Lastname'])); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <br><button type="submit" class="btn btn-lg btn-primary btn-block pull-right">{{ trans['Save my profile'] }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-widget widget-user">

                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-aqua-active">
                        <h3 class="widget-user-username">{{ trans['Connection and security'] }}</h3>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" src="{{url('img/cadena.png')}}" alt="Lock">
                    </div>
                    <div class="box-footer">
                        <br />
                        <div class="col-md-12">
                            <?php echo $this->tag->form(array("account/editPassword", "class" => "form-horizontal", "id" => "passwordEditForm")); ?>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <?php echo $this->tag->passwordField(array("password", "class" => "form-control", "placeholder" => $trans['Enter your current password'], "autocomplete" => "off")); ?>
                                                <span class="input-group-addon password_viewer"><i class="fa fa-fw fa-eye" style="color: #3c8dbc" title="Cliquez pour voir votre mot de passe"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <?php echo $this->tag->passwordField(array("newPassword",
                                                                                       "autocomplete" => "new-password",
                                                                                       "class" => "form-control passwordStrength",
                                                                                       "placeholder" => $trans['Enter your new password'],
                                                                                       "autocomplete" => "off",
                                                                                       "data-toggle" => "popover",
                                                                                       "data-trigger" => "focus",
                                                                                       "data-placement" => "top",
                                                                                       "title" => $trans['Password security'],
                                                                                       "data-content" => $trans['The password must contain 1 digit, 1 letter and 7 caracters minimum'])); ?>
                                                <span class="input-group-addon password_viewer"><i class="fa fa-fw fa-eye" style="color: #3c8dbc" title="Cliquez pour voir votre mot de passe"></i></span>
                                        </div>
                                    </div>
                                    <!--<div class="col-sm-2">
                                        <div id="passwordCheckInfo"></div>
                                    </div>-->
                                </div>

                                <!-- Password Strength -->
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div id="passwordStrength" class="strength0"></div>
                                        <div id="passwordDescription">{{ trans['No password enter'] }}</div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <?php echo $this->tag->passwordField(array("confirmPassword", "class" => "form-control", "placeholder" => $trans['Confirm your new password'], "autocomplete" => "new-password")); ?>
                                                <span class="input-group-addon  password_viewer"><i class="fa fa-fw fa-eye" style="color: #3c8dbc" title="Cliquez pour voir votre mot de passe"></i></span>

                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div id="passwordCheckInfo2"></div>
                                    </div>
                                </div>

                                <div class="form-group" id="submitContainer" style="cursor-pointer: not-allowed;">
                                     <div class="col-sm-12">
                                        <button id="passwordSubmit" style="" type="submit" class="btn btn-lg btn-primary pull-right ajax-navigation btn-block" data-placement="top" data-content="{{trans['password_differ']}}">{{ trans['Save password'] }}</button>
                                     </div>
                                </div>

                                </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>

</div>