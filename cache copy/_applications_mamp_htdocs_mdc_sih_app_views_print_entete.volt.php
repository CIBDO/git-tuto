
<div class="row no-print">
    <div class="col-sm-3">
      <a class="btn btn-default pull-left" href="<?= $referer ?>"><i class="fa fa-arrow-left"></i><?= $trans['Retour'] ?></a>
    </div>
    <div class="col-sm-3">
      <a onclick="window.print();" class="btn btn-default"><i class="fa fa-print"></i> Imprimer</a>
    </div>
</div>

<div class="row">
  <div class="col-sm-12">

  <?php if ($entete->type_entete == 'l') { ?>
    
    <?php if ($entete->template_logo == 'left') { ?>

      <?php if ($entete->logo != '') { ?>
        <img class="img-responsive pad" style="max-height:135px;float:left" src="<?= $this->url->getStatic('img/structure/') ?><?= $entete->logo ?>" alt="Logo">
      <?php } ?>

      <table class="table table-borderless" style="display:inline !important">
        <tr><td><?= $entete->ligne1 ?></td></tr>
        <tr><td><?= $entete->ligne2 ?></td></tr>
        <tr><td><?= $entete->ligne3 ?></td></tr>
        <tr><td><?= $entete->ligne4 ?></td></tr>
      </table>

    <?php } ?>

    <?php if ($entete->template_logo == 'right') { ?>

      <?php if ($entete->logo != '') { ?>
        <img class="img-responsive pad" style="max-height:135px;float:right" src="<?= $this->url->getStatic('img/structure/') ?><?= $entete->logo ?>" alt="Logo">
      <?php } ?>

      <table class="table table-borderless" style="display:inline !important">
        <tr><td><?= $entete->ligne1 ?></td></tr>
        <tr><td><?= $entete->ligne2 ?></td></tr>
        <tr><td><?= $entete->ligne3 ?></td></tr>
        <tr><td><?= $entete->ligne4 ?></td></tr>
      </table>

    <?php } ?>

    <?php if ($entete->template_logo == 'top') { ?>
    <center>
      <?php if ($entete->logo != '') { ?>
        <img class="img-responsive pad" style="max-height:135px" src="<?= $this->url->getStatic('img/structure/') ?><?= $entete->logo ?>" alt="Logo">
      <?php } ?>

      <table class="table table-borderless table-nomargin">
        <tr align="center"><td style="padding: 0px; margin:0px"><?= $entete->ligne1 ?></td></tr>
        <tr align="center"><td style="padding: 0px; margin:0px"><?= $entete->ligne2 ?></td></tr>
        <tr align="center"><td style="padding: 0px; margin:0px"><?= $entete->ligne3 ?></td></tr>
        <tr align="center"><td style="padding: 0px; margin:0px"><?= $entete->ligne4 ?></td></tr>
      </table>
    </center>
    <?php } ?>

  <?php } elseif ($entete->type_entete == 'e') { ?>

    <?php if ($entete->logo != '') { ?>
      <img class="img-responsive pad" style="max-height:150px;float:left" src="<?= $this->url->getStatic('img/structure/') ?><?= $entete->logo ?>" alt="Logo">
    <?php } ?>

  <?php } ?>

  </div>
</div>