 <body class="hold-transition login-page">
    <?= $this->getContent() ?>
    <?php $this->flashSession->output() ?>
    <!--<?= $this->tag->javascriptInclude('assets/jQuery/jQuery-2.1.4.min.js') ?>
    <?= $this->tag->javascriptInclude('assets/bootstrap/js/bootstrap.min.js') ?>     
    <?= $this->tag->javascriptInclude('assets/adminLTE/js/app.min.js') ?>-->
    
    <?= $this->tag->javascriptInclude('js/target.js') ?>
   
    <?php $this->assets->outputJs() ?>
</body>