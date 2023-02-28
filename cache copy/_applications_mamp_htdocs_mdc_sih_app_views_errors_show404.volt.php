
<?= $this->getContent() ?>
<div class="content-wrapper">
<div class="container">
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
	<div class="error-page">
        <h2 class="headline text-yellow"> 404</h2>

        <div class="error-content">
          <h3><i class="fa fa-warning text-yellow"></i> <?= $trans['notfound_server_error'] ?></h3>

          <p>
            <?= $trans['notfound_server_error_desc'] ?>
          </p>
          <p><?= $this->tag->linkTo(['index', $trans['back_to_home'], 'class' => 'btn btn-primary form-control', 'title' => $trans['back_to_home']]) ?></p>

          
        </div>
        <!-- /.error-content -->
      </div>
    
</div>
</div>