
<?= $this->getContent() ?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">

          <h1>
            <?= $trans['Tableau de bord principal'] ?> 
          </h1>

          <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> <?= $trans['Dashboard'] ?></li>
          </ol>
        </section>

       <?php $this->flash->output() ?>
        
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12" >
                <div class="box box-primary">
                    <div class="box-body">
                        <form class="form-inline" role="form" action="" method="get">

                            <div class="row" style="margin-top : 10px">
                                <div class="form-group  col-md-3" style="margin-right : 10px">
                                    <label for="date1"><?= $trans['DU '] ?> :</label>
                                        <?= $this->tag->datefield(['date1', 'class' => 'form-control', 'id' => 'date1']) ?>
                                </div>

                                <div class="form-group  col-md-3" style="margin-right : 10px">
                                    <label for="date2"><?= $trans['Au '] ?> :</label>
                                    <?= $this->tag->datefield(['date2', 'class' => 'form-control', 'id' => 'date2']) ?>
                                </div>

                                <div class="form-group  col-md-4">
                                    <button type="submit" class="btn btn-defaultx  ajax-navigation  pull-left" title="<?= $trans['Recherche'] ?>">
                                        <i class="fa fa-fw fa-filter"></i> <?= $trans['Filtrer'] ?>
                                    </button>
                                </div>
                            </div>  

                        </form>

                    </div>
                </div>
            </div>
          </div>

      <?php if (($userId == 1) || in_array('caisse_r', $userPermissions) || in_array('caisse_w', $userPermissions) || in_array('caisse_a', $userPermissions)) { ?>

          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?= number_format($nbrVente, 0, '.', ' ') ?></h3>

                  <p>Tickets vendus</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?= number_format($nbrVenteAnnule, 0, '.', ' ') ?></h3>

                  <p>Tickets annulés</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?= number_format($montantTotal, 0, '.', ' ') ?></h3>

                  <p>Montant Total - Ticket</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>

            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?= number_format($montantEncaisse, 0, '.', ' ') ?></h3>

                  <p>Montant encaissé - Ticket</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>

            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-purple">
                <div class="inner">
                  <h3><?= number_format($montantAssureur, 0, '.', ' ') ?></h3>

                  <p>Montant dû par les assureurs</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            
          </div>

      <?php } ?>

      <?php if (($userId == 1) || in_array('ph_r', $userPermissions) || in_array('ph_w', $userPermissions) || in_array('ph_a', $userPermissions)) { ?>

          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?= number_format($nbrVentePh, 0, '.', ' ') ?></h3>

                  <p>Nombre de ventes - Pharmacie</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?= number_format($nbrVenteAnnulePh, 0, '.', ' ') ?><sup style="font-size: 20px"></sup></h3>

                  <p>Reçus Annulés - Pharmacie</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?= number_format($montantTotalPh, 0, '.', ' ') ?></h3>

                  <p>Montant Total - Pharmacie</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>

            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?= number_format($montantEncaissePh, 0, '.', ' ') ?></h3>

                  <p>Montant encaissé - Pharmacie</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>

            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-purple">
                <div class="inner">
                  <h3><?= number_format($montantAssureurPh, 0, '.', ' ') ?></h3>

                  <p>Montant dû par les assureurs - Pharmacie</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>

            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3><?= number_format($nbrLotPeremption, 0, '.', ' ') ?><sup style="font-size: 20px"></sup></h3>

                  <p>Lots proches de la péremption </p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?= number_format($infSeuilMin, 0, '.', ' ') ?><sup style="font-size: 20px"></sup></h3>

                  <p>Produits en dessous du seuil minimum </p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>

            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3><?= number_format($nbrRuptureStock, 0, '.', ' ') ?><sup style="font-size: 20px"></sup></h3>

                  <p>Produits en rupture de stock</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
          </div>

      <?php } ?>

      <?php if (($userId == 1) || in_array('f_r', $userPermissions) || in_array('f_w', $userPermissions) || in_array('f_a', $userPermissions)) { ?>

          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?= number_format($opDepense, 0, '.', ' ') ?></h3>

                  <p>Opérations comptes de dépense</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?= number_format($opRecette, 0, '.', ' ') ?></h3>

                  <p>Opérations comptes de récette</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?= number_format($soldeEspece, 0, '.', ' ') ?></h3>

                  <p>Solde Espèce</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>

            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-purple">
                <div class="inner">
                  <h3><?= number_format($soldeBanque, 0, '.', ' ') ?></h3>

                  <p>Solde Banque</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
        </div>

      <?php } ?>

      <?php if (($userId == 1) || in_array('dp_r', $userPermissions) || in_array('dp_w', $userPermissions) || in_array('dp_a', $userPermissions) || in_array('cs_r', $userPermissions) || in_array('cs_w', $userPermissions) || in_array('cs_a', $userPermissions)) { ?>

        <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?= number_format($totalPatient, 0, '.', ' ') ?></h3>

                  <p>Nombre de nouveau patient</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <!-- ./col -->

            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?= $totalVisite ?></h3>

                  <p>Nombre total de visites</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?= $totalInitial ?></h3>

                  <p>Nombre de nouvelles consultations</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?= $totalSuivi ?></h3>

                  <p>Nombre de suivis</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
        </div>
          
      <?php } ?>

        </section>
      </div>

<script>

  $( document ).ready(function() {

      

  });

</script>