
<?= $this->getContent() ?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">

          <h1>
            <?= $trans['Tableau de bord - Caisse'] ?> 
          </h1>

          <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> <?= $trans['Dashboard'] ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <?php $this->flashSession->output() ?>
          <div class="row">
            <div class="col-xs-12" >
                <div class="box box-primary">
                    <div class="box-body">
                        <form class="form-inline" role="form" action="" method="get">

                            <div class="row" style="margin-top : 10px">
                                <div class="form-group  col-md-3" style="margin-right : 10px">
                                    <label for="date1"><?= $trans['Du '] ?> :</label>
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

          <div class="row">
            <div class="col-md-12">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Evolution des ventes sur les 12 derniers mois</h3>
                    </div>

                    <div class="box-body" style="max-height: 400px;overflow-y:auto">
                      <div id="chartdiv_chiffreMensuelle" style="width:100%;height:300px;font-size:11px;"></div>
                    </div>
                </div>

            </div><!-- /.col -->
          </div>


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

                  <p>Montant Total</p>
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
                  <h3><?= number_format($montantDifference, 0, '.', ' ') ?></h3>

                  <p>Montant Total DIFF CELY</p>
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

                  <p>Montant encaissé</p>
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

          <div class="row">
            <div class="col-md-6">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Top 10 des prestations</h3>
                    </div>

                    <div class="box-body" style="max-height: 400px;overflow-y:auto">
                      <div id="chartdiv_top10Prestation" style="width:100%;height:300px;font-size:11px;"></div>
                    </div>
                </div>

                </div><!-- /.col -->

            <div class="col-md-6">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Top 10 des services</h3>
                    </div>

                    <div class="box-body" style="max-height: 400px;overflow-y:auto">
                      <div id="chartdiv_top10Service" style="width:100%;height:300px;font-size:11px;"></div>
                    </div>
                </div>
              
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Etat des tickets par unité</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="venteParUnite">
                                <thead class="bg-aqua-gradient">
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                </div>

                <!-- <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Etat des tickets par service</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="venteParService">
                                <thead class="bg-aqua-gradient">
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> -->

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Etat des tickets par prestation</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="venteParActes">
                                <thead class="bg-aqua-gradient">
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                </div>

            </div><!-- /.col -->

            <div class="col-md-6">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Etat des tickets par organisme d'assurance</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="venteParOrganismeAssurances">
                                <thead class="bg-aqua-gradient">
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                </div>

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ventes par localité</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="venteParResidences">
                                <thead class="bg-aqua-gradient">
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                </div>
              
            </div>
          </div>

        </section>
      </div>

<script>

  $( document ).ready(function() {

      var chartdiv_top10Prestation = AmCharts.makeChart("chartdiv_top10Prestation", {
        "type": "serial",
        "theme": "light",
        "marginRight": 70,
        "dataProvider": <?= $top10PrestationsGraph ?>,
        "valueAxes": [{
          "axisAlpha": 0,
          "position": "left",
          "title": "Top 10 des prestations"
        }],
        "startDuration": 1,
        "graphs": [{
          "balloonText": "<b>[[category]]: [[value]]</b>",
          "fillColorsField": "color",
          "fillAlphas": 0.9,
          "lineAlpha": 0.2,
          "type": "column",
          "valueField": "nbr"
        }],
        "chartCursor": {
          "categoryBalloonEnabled": false,
          "cursorAlpha": 0,
          "zoomable": false
        },
        "categoryField": "acte_libelle",
        "categoryAxis": {
          "gridPosition": "start",
          "labelRotation": 25
        },
        "pathToImages": '<?= $this->url->getStatic('bower_components/amcharts3/amcharts/images/') ?>',
        "export": {
          //"enabled": true,
          "libs": {
            "path": "<?= $this->url->get('assets/amcharts/plugins/export/libs/') ?>"
          }
        }

      });

      var chartdiv_top10Service = AmCharts.makeChart("chartdiv_top10Service", {
        "type": "serial",
        "theme": "light",
        "marginRight": 70,
        "dataProvider": <?= $top10ServicesGraph ?>,
        "valueAxes": [{
          "axisAlpha": 0,
          "position": "left",
          "title": "Top 10 des services"
        }],
        "startDuration": 1,
        "graphs": [{
          "balloonText": "<b>[[category]]: [[value]]</b>",
          "fillColorsField": "color",
          "fillAlphas": 0.9,
          "lineAlpha": 0.2,
          "type": "column",
          "valueField": "nbr"
        }],
        "chartCursor": {
          "categoryBalloonEnabled": false,
          "cursorAlpha": 0,
          "zoomable": false
        },
        "categoryField": "service_libelle",
        "categoryAxis": {
          "gridPosition": "start",
          "labelRotation": 25
        },
        "pathToImages": '<?= $this->url->getStatic('bower_components/amcharts3/amcharts/images/') ?>',
        "export": {
          //"enabled": true,
          "libs": {
            "path": "<?= $this->url->get('assets/amcharts/plugins/export/libs/') ?>"
          }
        }

      });

      //PIE exemple
      /*var chartdiv_top10Service = AmCharts.makeChart( "chartdiv_top10Service", {
        "type": "pie",
        "theme": "light",
        "dataProvider": <?= $top10ServicesGraph ?>,
        "valueField": "nbr",
        "titleField": "service_libelle",
         "balloon":{
         "fixedPosition":true
        },
        "export": {
          //"enabled": true
        }
      } );*/

      $('#venteParService').bootstrapTable({
        data: <?= $venteParServices ?>,
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "desc",
        sortName: "montant",
        locale: "<?php if ($language == 'fr') { ?>fr-FR<?php } else { ?>en<?php } ?>",
        search: true,
        minimumCountColumns: 2,
        clickToSelect: false,
        toolbar: "#toolbar",
        showFooter: false,
        showExport: true,
        showPaginationSwitch: true,
        exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
        exportDataType : "selected",
        mobileResponsive: true,
        filterControl: false,
        columns: [
            {
                title: 'state',
                checkbox: true,
            },
            {
                field: 'service',
                title: "<?= $trans['Service'] ?>",
                sortable: true,
            },
            {
                field: 'montant',
                title: "<?= $trans['Montant'] ?>",
                formatter: amountFormatter,
                sortable: true
            }
        ]
      });

      $('#venteParUnite').bootstrapTable({
        data: <?= $venteParUnites ?>,
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "desc",
        sortName: "montant",
        locale: "<?php if ($language == 'fr') { ?>fr-FR<?php } else { ?>en<?php } ?>",
        search: true,
        minimumCountColumns: 2,
        clickToSelect: false,
        toolbar: "#toolbar",
        showFooter: false,
        showExport: true,
        showPaginationSwitch: true,
        exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
        exportDataType : "selected",
        mobileResponsive: true,
        filterControl: false,
        columns: [
            {
                title: 'state',
                checkbox: true,
            },
            {
                field: 'unite',
                title: "<?= $trans['Unité'] ?>",
                sortable: true,
            },
            {
                field: 'montant',
                title: "<?= $trans['Montant'] ?>",
                formatter: amountFormatter,
                sortable: true
            }
        ]
      });

      $('#venteParActes').bootstrapTable({
        data: <?= $venteParActes ?>,
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "desc",
        sortName: "montant",
        locale: "<?php if ($language == 'fr') { ?>fr-FR<?php } else { ?>en<?php } ?>",
        search: true,
        minimumCountColumns: 2,
        clickToSelect: false,
        toolbar: "#toolbar",
        showFooter: false,
        showExport: true,
        showPaginationSwitch: true,
        exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
        exportDataType : "selected",
        mobileResponsive: true,
        filterControl: false,
        columns: [
            {
                title: 'state',
                checkbox: true,
            },
            {
                field: 'unite',
                title: "<?= $trans['Unité'] ?>",
                sortable: true,
            },
            {
                field: 'acte_libelle',
                title: "<?= $trans['Prestation'] ?>",
                sortable: true,
            },
            {
                field: 'nbr',
                title: "<?= $trans['Quantité'] ?>",
                sortable: true
            },
            {
                field: 'montant',
                title: "<?= $trans['Montant'] ?>",
                formatter: amountFormatter,
                sortable: true
            }
        ]
      });

      $('#venteParResidences').bootstrapTable({
        data: <?= $venteParResidences ?>,
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "desc",
        sortName: "montant",
        locale: "<?php if ($language == 'fr') { ?>fr-FR<?php } else { ?>en<?php } ?>",
        search: true,
        minimumCountColumns: 2,
        clickToSelect: false,
        toolbar: "#toolbar",
        showFooter: false,
        showExport: true,
        showPaginationSwitch: true,
        exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
        exportDataType : "selected",
        mobileResponsive: true,
        filterControl: false,
        columns: [
            {
                title: 'state',
                checkbox: true,
            },
            {
                field: 'residence',
                title: "<?= $trans['Localité'] ?>",
                sortable: true,
            },
            {
                field: 'montant',
                title: "<?= $trans['Montant'] ?>",
                formatter: amountFormatter,
                sortable: true
            }
        ]
      });

      $('#venteParOrganismeAssurances').bootstrapTable({
        data: <?= $venteParOrganismeAssurances ?>,
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "desc",
        sortName: "montant",
        locale: "<?php if ($language == 'fr') { ?>fr-FR<?php } else { ?>en<?php } ?>",
        search: true,
        minimumCountColumns: 2,
        clickToSelect: false,
        toolbar: "#toolbar",
        showFooter: false,
        showExport: true,
        showPaginationSwitch: true,
        exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
        exportDataType : "selected",
        mobileResponsive: true,
        filterControl: false,
        columns: [
            {
                title: 'state',
                checkbox: true,
            },
            {
                field: 'organisme',
                title: "<?= $trans['Organisme'] ?>",
                sortable: true,
            },
            {
                field: 'taux',
                title: "<?= $trans['Taux'] ?>",
                sortable: true,
            },
            {
                field: 'part_assure',
                title: "<?= $trans['Part Assuré'] ?>",
                formatter: amountFormatter,
                sortable: true
            },
            {
                field: 'part_organisme',
                title: "<?= $trans['Part Organisme'] ?>",
                formatter: amountFormatter,
                sortable: true
            }
        ]
      });


      var chartdiv_chiffreMensuelle = AmCharts.makeChart("chartdiv_chiffreMensuelle", {
            "type": "serial",
            "theme": "light",
            "legend": {
                "useGraphSettings": true
            },
            "dataProvider": <?= $chiffreMensuelle ?>,
            "synchronizeGrid":true,
            "valueAxes": [{
                "id":"v1",
                "axisColor": "#B0DE09",
                "axisThickness": 2,
                "axisAlpha": 1,
                "position": "left"
            }],
            "graphs": [{
                "valueAxis": "v1",
                "lineColor": "#B0DE09",
                "bullet": "round",
                "bulletBorderThickness": 1,
                "hideBulletsCount": 30,
                "title": "Chiffre d'affaire",
                "valueField": "montant",
                "fillAlphas": 0
            }],
            "chartScrollbar": {},
            "chartCursor": {
                "cursorPosition": "mouse"
            },
            "categoryField": "mois",
            "categoryAxis": {
                //"parseDates": true,
                "axisColor": "#DADADA",
                "minorGridEnabled": true
            },
            "pathToImages": '<?= $this->url->getStatic('bower_components/amcharts3/amcharts/images/') ?>',
            "export": {
              //"enabled": true,
             }
        });

        chartdiv_chiffreMensuelle.addListener("dataUpdated", zoomChart);
        function zoomChart(){
            chartdiv_chiffreMensuelle.zoomToIndexes(chartdiv_chiffreMensuelle.dataProvider.length - 20, chartdiv_chiffreMensuelle.dataProvider.length - 1);
        }
        zoomChart();

  });

</script>