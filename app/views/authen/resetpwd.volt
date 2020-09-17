    {{ content() }}
<section class="content centered">

    <div class="col-md-3"></div>
    <div class="col-md-6">
        <?php $this->flashSession->output() ?>
                <div class="box box-widget widget-user">

                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-aqua-active">
                        <h3 class="widget-user-username">{{ trans['Change your password'] }}</h3>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" src="https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcTsvKOTalCq2dp0qObiwFbtC4efGduw2qaht7ZbSSwGTBY1WEgK_g" alt="Lock">
                    </div>
                    <div class="box-footer">
                        <br />
                        <div class="col-md-12">
                            <?php echo $this->tag->form(array("authen/resetpwd/".$token, "class" => "form-horizontal")); ?>

                                
                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <label for="inputName" class="control-label text-left">{{ trans['New password'] }}</label>
                                    </div>
                                    <div class="col-sm-8">
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
                                    <div class="col-sm-8 col-sm-offset-4">
                                        <div id="passwordStrength" class="strength0"></div>
                                        <div id="passwordDescription">{{ trans['No password enter'] }}</div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <label for="inputName" class="control-label text-left">{{ trans['Password Confirmation'] }}</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <?php echo $this->tag->passwordField(array("confirmPassword", "class" => "form-control", "placeholder" => $trans['Confirm your new password'], "autocomplete" => "off")); ?>
                                                <span class="input-group-addon  password_viewer"><i class="fa fa-fw fa-eye" id="eye-icon" style="color: #3c8dbc" title="Cliquez pour voir votre mot de passe"></i></span>

                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div id="passwordCheckInfo2"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-8 col-sm-offset-4">
                                        <button id="passwordSubmit" type="submit" class="btn btn-primary pull-right" title="{{ trans['Modify password'] }}">{{ trans['Modify password'] }}</button>
                                    </div>
                                </div>

                                </form>
                        </div>
                    </div>
                </div>
            </div>
 </section>
