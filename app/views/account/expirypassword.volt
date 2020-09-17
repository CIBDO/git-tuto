<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    {{ stylesheet_link('css/opsise.css') }}
    <meta name="author" content="OpSiSe Team">
    <link rel="icon" type="image/png" href="{{ url("img/opsise.png") }}" />
</head>

<body class="login-page">
    <section class="content centered">
       <div class="col-md-4 col-md-offset-4">
            <?php $this->flashSession->output() ?>
        </div>
        <div class="col-md-4 col-md-offset-4">
            <div class="box box-widget widget-user">

                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-aqua-active">
                    <h3 class="widget-user-username" style="font-weight: bold">{{ trans['Password about to expired'] }}</h3>
                </div>
                <!--<div class="widget-user-image">
                    <img class="img-circle" src="https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcTsvKOTalCq2dp0qObiwFbtC4efGduw2qaht7ZbSSwGTBY1WEgK_g" alt="Lock">
                </div>-->
                <div class="box-footer">
                    <div class="col-md-12">
                        <!--<form action="account/editPassword" class="form-horizontal">-->
                        <?php echo $this->tag->form(array("account/editPassword", "class" => "form-horizontal", "name" => "updatePwd")); ?>

                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="inputEmail" class="control-label text-left">{{ trans['Password'] }}</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <?php echo $this->tag->passwordField(array("password",
                                                                                   "class" => "form-control",
                                                                                   "placeholder" => $trans['Enter your current password'],
                                                                                   "autocomplete" => "off")); ?>
                                        <span class="input-group-addon password_viewer"><i class="fa fa-fw fa-eye" id="eye-icon" style="color: #3c8dbc" title="Cliquez pour voir votre mot de passe"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="inputName" class="control-label text-left">{{ trans['New password'] }}</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <?php echo $this->tag->passwordField(array("newPassword",
                                                                                   "class" => "form-control passwordStrength",
                                                                                   "placeholder" => $trans['Enter your new password'],
                                                                                   "autocomplete" => "off",
                                                                                   "data-toggle" => "popover",
                                                                                   "data-trigger" => "focus",
                                                                                   "data-placement" => "top",
                                                                                   "title" => $trans['Password security'],
                                                                                   "data-content" => $trans['The password must contain 1 digit, 1 letter and 7 caracters minimum'])); ?>
                                        <span class="input-group-addon password_viewer"><i class="fa fa-fw fa-eye" id="eye-icon" style="color: #3c8dbc" title="Cliquez pour voir votre mot de passe"></i></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Password Strength -->
                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <div id="passwordStrength" class="strength0"></div>
                                    <div id="passwordDescription">{{ trans['No password enter'] }}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="inputName" class="control-label text-left">{{ trans['Password Confirmation'] }}</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <?php echo $this->tag->passwordField(array("confirmPassword",
                                                                                   "class" => "form-control",
                                                                                   "placeholder" => $trans['Confirm your new password'],
                                                                                   "autocomplete" => "off")); ?>
                                        <span class="input-group-addon  password_viewer"><i class="fa fa-fw fa-eye" id="eye-icon" style="color: #3c8dbc" title="Cliquez pour voir votre mot de passe"></i></span>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button id="passwordSubmit" type="submit" class="btn btn-primary pull-right">{{ trans['Modify password'] }}</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Include the JS file associated -->
    {{ javascript_include('assets/jQuery/jQuery-2.1.4.min.js') }}
    {{ javascript_include('js/opsise.js') }}
    {{ javascript_include('js/pages/account.js') }}
</body>

</html>
