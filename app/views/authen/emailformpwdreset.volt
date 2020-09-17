    {{ content() }}    
    <div class="login-box" style="margin: 3% auto;">
    <div class="login-logo">
      <img src="{{ url("img/logo.png") }}" class="img-responsive">
    </div><!-- /.login-logo -->
  </div>
  
  <div class="col-md-4"></div>
  <div class="col-md-4">
      <?php $this->flashSession->output() ?>
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-envelope"></i>&nbsp;&nbsp;{{ trans['Enter your email to reset your password'] }}</h3>
        

        
      </div>
      <!-- /.box-header -->
      <!-- form start --><br>
      <form action="{{ url("authen/sendmailpwdreset") }}" method="post">
        <div class="box-body">
          <div class="form-group">

            <input type="email" class="form-control input-lg" id="exampleInputEmail1" placeholder="Email" name="email" id="email">
          </div>
          <button type="submit" class="btn btn-primary pull-right" title="{{ trans['Reset'] }}">{{ trans['Reset'] }}</button>
        </div>
      </form>
    </div>
  </div>