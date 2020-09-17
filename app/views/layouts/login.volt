 <body class="hold-transition login-page">
    {{ content() }}
    <?php $this->flashSession->output() ?>
    <!--{{ javascript_include('assets/jQuery/jQuery-2.1.4.min.js') }}
    {{ javascript_include('assets/bootstrap/js/bootstrap.min.js') }}     
    {{ javascript_include('assets/adminLTE/js/app.min.js' ) }}-->
    
    {{ javascript_include('js/target.js') }}
   
    <?php $this->assets->outputJs() ?>
</body>