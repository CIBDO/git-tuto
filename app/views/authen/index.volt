  <style type="text/css">
    input[id="password"] { -webkit-text-security: none !important; }
  </style>
  <script type="text/javascript">

  </script>
  {{ content() }}

  <div class="login-box" style="margin: 3% auto;">
    <div class="login-logo">
      <!-- <img src="{{ url("img/logo.png") }}" class="img-responsive"> -->
      {{stucture_name_config}}
    </div><!-- /.login-logo -->
  </div>
 
  <div class="col-md-4"></div>
  <div class="col-md-4">
       <?php $this->flash->output() ?>
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-lock"></i>&nbsp;&nbsp;
          {{ trans['Sign in to start your session'] }}
          </h3>
      </div>
      <!-- /.box-header -->
      <!-- form start --><br>
      <form action="{{ url("authen/login") }}" method="post">
        <div class="box-body">
          <div class="form-group">
           
            <input type="login" class="form-control input-lg" id="" placeholder="Nom d'utilisateur" name="login" id="login">
          </div>
          <div class="form-group">
           
            <div class="input-group">
              <input type="password" class="form-control password input-lg" id="exampleInputPassword1" placeholder="{{ trans['Password'] }}" id="password" name="password">
              <span class="input-group-addon  password_viewer"><i class="fa fa-fw fa-eye" id="eye-icon" style="color: #3c8dbc" title="Cliquez pour voir votre mot de passe"></i></span>
            </div>
          </div>
          <button type="submit" class="btn btn-primary pull-right" title="{{trans['Sign In']}}">{{trans['Sign In']}}</button>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">

          <!--<a href="{{ url("authen/emailformpwdreset") }}">{{ trans['I forgot my password'] }}</a><br>-->

        </div>
      </form>
    </div>
  </div>
<div class="col-md-4"></div>
<script type="text/javascript">
// When eye icon is hover
$('.password_viewer').on('mouseover', function () {
      // On mouse click up
      $('.password_viewer').on('mousedown', function () {
        $(this).prev().attr('type', 'text');
        $(this).children(':first').attr("style", 'color:green');
      });
    // On mouse click down
    $('.password_viewer').on('mouseup', function () {
      $(this).prev().attr('type', 'password');
      $(this).children(':first').attr("style", 'color:#3c8dbc');
    });
  });


</script>
