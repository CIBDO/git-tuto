
{{ content() }}
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">

          <h1>
            {{ trans['Tableau de bord - Pharmacie']}} 
          </h1>

          <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> {{ trans['Tableau de bord']}}</li>
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
                                    <label for="date1">{{ trans['Du ']}} :</label>
                                        {{ dateField(['date1', 'class': 'form-control', 'id': 'date1']) }}
                                </div>

                                <div class="form-group  col-md-3" style="margin-right : 10px">
                                    <label for="date2">{{ trans['Au ']}} :</label>
                                    {{ dateField(['date2', 'class': 'form-control', 'id': 'date2']) }}
                                </div>

                                <div class="form-group  col-md-4">
                                    <button type="submit" class="btn btn-defaultx  ajax-navigation  pull-left" title="{{trans['Recherche']}}">
                                        <i class="fa fa-fw fa-filter"></i> {{trans['Filtrer']}}
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

            </div>
          </div>

          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{ number_format(nbrVente,0,'.',' ') }}</h3>

                  <p>Nombre de ventes</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3>{{ number_format(nbrVenteAnnule,0,'.',' ') }}<sup style="font-size: 20px"></sup></h3>

                  <p>Reçus Annulés</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>{{ number_format(montantTotalPh,0,'.',' ') }}</h3>

                  <p>Montant Total</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>{{ number_format(montantEncaissePh,0,'.',' ') }}</h3>

                  <p>Montant encaissé</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-purple">
                <div class="inner">
                  <h3>{{ number_format(montantAssureur,0,'.',' ') }}</h3>

                  <p>Montant dû par les assureurs</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <!-- ./col -->

            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>{{ number_format(nbrLotPeremption,0,'.',' ') }}<sup style="font-size: 20px"></sup></h3>

                  <p>Lots proches de la péremption </p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div> 

            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3>{{ number_format(infSeuilMin,0,'.',' ') }}<sup style="font-size: 20px"></sup></h3>

                  <p>Produits en dessous du seuil minimum </p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>   
            
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>{{ number_format(nbrRuptureStock,0,'.',' ') }}<sup style="font-size: 20px"></sup></h3>

                  <p>Produits en rupture de stock</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Top 20 des produits vendus</h3>
                    </div>

                    <div class="box-body" style="max-height: 400px;overflow-y:auto">
                      <div id="chartdiv_top20Produit" style="width:100%;height:300px;font-size:11px;"></div>
                    </div>
                </div>
            </div>
        </div>

         <div class="row">
            <div class="col-md-12">
                 <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Lots de produits proches de la péremption</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="lotPeremption">
                                <thead class="bg-aqua-gradient">
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">

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
                    </div>
                </div>

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pointage des produits par Forme </h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="pointageProduitByForme">
                                <thead class="bg-aqua-gradient">
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pointage des produits par classe thérapeutique </h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="pointageProduitByClasseTh">
                                <thead class="bg-aqua-gradient">
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-6">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Etat des reçus par organisme d'assurance</h3>
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
                    </div>
                </div>

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pointage des produits</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="pointageProduit">
                                <thead class="bg-aqua-gradient">
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
              
            </div>
          </div>

        </section>
      </div>

<script>

  $( document ).ready(function() {

      var chartdiv_top20Produit = AmCharts.makeChart("chartdiv_top20Produit", {
        "type": "serial",
        "theme": "light",
        "marginRight": 70,
        "dataProvider": {{ top20ProduitGraph }},
        "valueAxes": [{
          "axisAlpha": 0,
          "position": "left",
          "title": "Top 20 des produits"
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
        "categoryField": "produit_libelle",
        "categoryAxis": {
          "gridPosition": "start",
          "labelRotation": 30
        },
        "pathToImages": '{{ static_url("bower_components/amcharts3/amcharts/images/") }}',
        "export": {
          //"enabled": true,
          "libs": {
            "path": "{{ url('assets/amcharts/plugins/export/libs/') }}"
          }
        }

      });

      $('#pointageProduit').bootstrapTable({
        data: {{ pointageProduit }},
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "desc",
        sortName: "montant",
        locale: "{% if language == 'fr' %}fr-FR{% else %}en{% endif %}",
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
                field: 'produit_libelle',
                title: "{{ trans["Produit"] }}",
                sortable: true,
            },
            {
                field: 'quantite',
                title: "{{ trans["Quantité"] }}",
                sortable: true,
            },
            {
                field: 'montant',
                title: "{{ trans['Montant'] }}",
                formatter: amountFormatter,
                sortable: true
            }
        ]
      });

      $('#pointageProduitByForme').bootstrapTable({
        data: {{ pointageProduitByForme }},
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "desc",
        sortName: "montant",
        locale: "{% if language == 'fr' %}fr-FR{% else %}en{% endif %}",
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
                field: 'forme_libelle',
                title: "{{ trans["Forme"] }}",
                sortable: true,
            },
            {
                field: 'quantite',
                title: "{{ trans["Quantité"] }}",
                sortable: true,
            },
            {
                field: 'montant',
                title: "{{ trans['Montant'] }}",
                formatter: amountFormatter,
                sortable: true
            }
        ]
      });

      $('#pointageProduitByClasseTh').bootstrapTable({
        data: {{ pointageProduitByClasseTh }},
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "desc",
        sortName: "montant",
        locale: "{% if language == 'fr' %}fr-FR{% else %}en{% endif %}",
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
                field: 'classeTh_libelle',
                title: "{{ trans["Classe thérapeutique"] }}",
                sortable: true,
            },
            {
                field: 'quantite',
                title: "{{ trans["Quantité"] }}",
                sortable: true,
            },
            {
                field: 'montant',
                title: "{{ trans['Montant'] }}",
                formatter: amountFormatter,
                sortable: true
            }
        ]
      });

      $('#venteParOrganismeAssurances').bootstrapTable({
        data: {{ venteParOrganismeAssurances }},
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "desc",
        sortName: "montant",
        locale: "{% if language == 'fr' %}fr-FR{% else %}en{% endif %}",
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
                title: "{{ trans["Organisme"] }}",
                sortable: true,
            },
            {
                field: 'taux',
                title: "{{ trans["Taux"] }}",
                sortable: true,
            },
            {
                field: 'part_assure',
                title: "{{ trans['Part Assuré'] }}",
                formatter: amountFormatter,
                sortable: true
            },
            {
                field: 'part_organisme',
                title: "{{ trans['Part Organisme'] }}",
                formatter: amountFormatter,
                sortable: true
            }
        ]
      });

      $('#venteParResidences').bootstrapTable({
        data: {{ venteParResidences }},
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "desc",
        sortName: "montant",
        locale: "{% if language == 'fr' %}fr-FR{% else %}en{% endif %}",
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
                title: "{{ trans["Localité"] }}",
                sortable: true,
            },
            {
                field: 'montant',
                title: "{{ trans['Montant'] }}",
                formatter: amountFormatter,
                sortable: true
            }
        ]
      });

      $('#lotPeremption').bootstrapTable({
        data: {{ lotPeremption }},
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "desc",
        sortName: "montant",
        locale: "{% if language == 'fr' %}fr-FR{% else %}en{% endif %}",
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
                field: 'point_distribution',
                title: "{{ trans['Point de distribution'] }}",
                sortable: true,
                filterControl: "select"
            },
            {
                field: 'produit_libelle',
                title: "{{ trans['Produit'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'reste',
                title: "{{ trans['Stock'] }}",
                sortable: true,
            },
            {
                field: 'lot',
                title: "{{ trans['Lot'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'date_peremption',
                title: "{{ trans['Date de peremption'] }}",
                sortable: true,
                filterControl: "input"
            }
        ]
      });

      var chartdiv_chiffreMensuelle = AmCharts.makeChart("chartdiv_chiffreMensuelle", {
            "type": "serial",
            "theme": "light",
            "legend": {
                "useGraphSettings": true
            },
            "dataProvider": {{chiffreMensuelle}},
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
            "pathToImages": '{{ static_url("bower_components/amcharts3/amcharts/images/") }}',
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