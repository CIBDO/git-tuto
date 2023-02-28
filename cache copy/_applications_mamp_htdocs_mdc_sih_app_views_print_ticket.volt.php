<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">

    
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

    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          Ticket de prestation
          <small class="pull-right">Date: <?= date($trans['date_format'], strtotime($prestation->date)) ?></small>
        </h2>
      </div><!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        <b>Identifiant Patient</b>: <?= $patient->id ?><br>
        <b>Nom:</b> <?= $patient->nom ?><br>
        <b>Prénom:</b> <?= $patient->prenom ?><br>
        <b>Adresse:</b> <?= $patient->adresse ?>
      </div><!-- /.col -->

      <?php if (isset($prestation_organisme)) { ?>
      <div class="col-sm-4 invoice-col">
        <b>Prise en charge</b><br>
        <b>Organisme</b>: <?= $prestation_organisme ?><br>
        <b>Numéro:</b> <?= $prestation->numero ?><br>
        <b>Bénéficiaire:</b> <?= $prestation->beneficiaire ?> / <b>OGD:</b> <?= $prestation->ogd ?><br>
      </div><!-- /.col -->
      <?php } ?>


      <div class="col-sm-4 invoice-col">
        <b>Ticket #<?= $prestation->id ?></b><br>
        <b>Emis par: </b>
        <?= $emetteur->nom . ' ' . $emetteur->prenom ?>
        <br>
      </div><!-- /.col -->
    </div><!-- /.row -->

    <!-- Table row -->
    <div class="row" style="margin-top: 15px">
      <div class="col-xs-12 table-responsive">
        <table class="table table-striped"  style="border: 1px solid">
          <thead>
            <tr>
              <th style="border: 1px solid">Prestation</th>
              <th style="border: 1px solid">Prestataire</th>
              <th style="border: 1px solid">Quantité</th>
              <th style="border: 1px solid">Prix Unitaire</th>
            <?php if ($prestation->montant_difference > 0) { ?>
              <th style="border: 1px solid">Diff CELY</th>
            <?php } ?>
              <th style="border: 1px solid">Total</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($prestation_details as $index => $prestation_detail) { ?>
            <tr>
              <td style="border: 1px solid">
                <?= $prestation_detail['unite'] ?> / <?= $prestation_detail['acte'] ?>
              </td>
              <td style="border: 1px solid">
              <?php if ($prestation_detail['prestataire'] != '') { ?>
                <?= $prestation_detail['prestataire']->nom . ' ' . $prestation_detail['prestataire']->prenom ?>
              <?php } else { ?>
                -
              <?php } ?>
              </td>
              <td style="border: 1px solid"><?= $prestation_detail['quantite'] ?></td>
              <td style="border: 1px solid">
                <?= number_format($prestation_detail['montant_unitaire'], 0, '.', ' ') ?> F CFA
              </td>
            <?php if ($prestation->montant_difference > 0) { ?>
              <td style="border: 1px solid">
                <?= number_format($prestation_detail['montant_unitaire_difference'], 0, '.', ' ') ?> F CFA
              </td>
            <?php } ?>
              <td style="border: 1px solid">
                <?= number_format(($prestation_detail['montant_normal'] + ($prestation_detail['montant_unitaire_difference'] * $prestation_detail['quantite'])), 0, '.', ' ') ?> F CFA
                </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div><!-- /.col -->
    </div><!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-xs-8">
        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
          Merci de conserver ce ticket pour votre prochaine visite.<br /><br /><br />
          <img id="bcTarget" />
          <br /><br /><br />
        </p>
      </div><!-- /.col -->
      <div class="col-xs-4">
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Total:</th>
              <td align="right"><?= number_format($prestation->montant_normal, 0, '.', ' ') ?> F CFA</td>
            </tr>
            <?php if (isset($prestation_organisme)) { ?>
            <tr>
              <th>Prise en charge (<?= $prestation->type_assurance_taux ?>%)</th>
              <td align="right"><?= number_format($prestation->montant_restant, 0, '.', ' ') ?> F CFA</td>
            </tr>
            <tr>
              <th>Reste à payer:</th>
              <td align="right"><?= number_format($prestation->montant_normal - $prestation->montant_restant, 0, '.', ' ') ?> F CFA</td>
            </tr>
            <?php } ?>
            <?php if ($prestation->montant_difference > 0) { ?>
            <tr>
              <th>Difference CELY:</th>
              <td align="right"><?= number_format($prestation->montant_difference, 0, '.', ' ') ?> F CFA</td>
            </tr>
            <?php } ?>
            <tr>
              <th>Montant dû:</th>
              <td align="right"><?= number_format($prestation->montant_patient, 0, '.', ' ') ?> F CFA</td>
            </tr>
            <tr>
              <th>Montant reçu:</th>
              <td align="right"><?= number_format($prestation->montant_recu, 0, '.', ' ') ?> F CFA</td>
            </tr>
            <tr>
              <th>Montant à retourner:</th>
              <td align="right"><?= number_format($montant_retourner, 0, '.', ' ') ?> F CFA</td>
            </tr>
          </table>
        </div>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </section><!-- /.content -->
</div><!-- ./wrapper -->
  
<script type="text/javascript">
$(document).ready(function() 
{ 
    JsBarcode("#bcTarget", "<?= $patient->id ?>", {
      format: "code128",
      lineColor: "#0aa",
      //width:10,
      height:40,
      displayValue: false
    });

});
</script>