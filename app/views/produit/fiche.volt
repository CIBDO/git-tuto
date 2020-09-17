
{{ content() }}
      <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">

          <h1>
            {{ trans['Fiche produit']}} 
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
                                    <label for="date1">{{ trans['Depuis le']}} :</label>
                                        {{ dateField(['date1', 'class': 'form-control', 'id': 'date1']) }}
                                </div>

                                <div class="form-group  col-md-3" style="margin-right : 10px">
                                    <label for="date2">{{ trans['et ']}} :</label>
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
            <div class="col-xs-12" >
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row" style="margin-top : 10px">

                            <div class="col-md-3">
                                <b>ID:</b> <span>{{ produit['id'] }}</span><br>
                                <b>Libellé:</b> <span>{{ produit['libelle'] }}</span><br>
                                <b>Type:</b> <span>{{ produit['type'] }}</span><br>
                                <b>Dosage:</b> {{ produit['dosage'] }}</span><br>
                            </div>

                            <div class="col-md-3">
                                <b>Forme:</b> <span>{{ produit['forme'] }}</span><br>
                                <b>Classe thérapeutique:</b> <span>{{ produit['classe_th'] }}</span><br>
                                <b>Unité de dispensation:</b> <span>{{ produit['unite_vente'] }}</span><br>
                                <b>Présentation:</b> <span>{{ produit['presentation'] }}</span><br>
                            </div>

                            <div class="col-md-3">
                                <b>Seuil minimal:</b> <span>{{ produit['seuil_min'] }}</span><br>
                                <b>Seuil maximum:</b> <span>{{ produit['seuil_max'] }}</span><br>
                                <b>Prix de vente:</b> <span>{{ number_format(produit['prix'],0,'.',' ') }}</span><br>
                                <b>Stock actuel:</b> <span>{{ produit['stock'] }}</span><br>
                            </div>

                            <!-- <div class="col-md-3">
                                <a href="#" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{ number_format(rs_entre,0,'.',' ') }}</h3>

                  <p>Quantité réceptionnée</p>
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
                  <h3>{{ number_format(rs_sortie,0,'.',' ') }}</h3>

                  <p>Quantité vendue</p>
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
                  <h3>{{ number_format(rs_perte,0,'.',' ') }}</h3>

                  <p>Qantité ajustée en perte</p>
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
                  <h3>{{ number_format(rs_ajout,0,'.',' ') }}</h3>

                  <p>Qantité ajustée en ajout</p>
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
                        <h3 class="box-title">Stock au niveau des points de distribution</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="stockPointDeVente">
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

            <div class="col-md-3">

                <div class="small-box bg-aqua">
                  <div class="inner">
                    <h3>{{ number_format(rupture,0,'.',' ') }}</h3>

                    <p>Nombre de ruptures</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                  </div>
                  <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
                </div>

            </div>

            <div class="col-md-3">

                <div class="small-box bg-aqua">
                  <div class="inner">
                    <h3>{{ number_format(ruptureJourTotal,0,'.',' ') }}</h3>

                    <p>Nombre Total de jours de rupture</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                  </div>
                  <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
                </div>

            </div>

            <div class="col-md-3">

                <div class="small-box bg-aqua">
                  <div class="inner">
                    <h3>{{ number_format(consoMoyenn,2,'.',' ') }}</h3>

                    <p>Consommation moyenne</p>
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
                        <h3 class="box-title">Consommation sur les 12 derniers mois</h3>
                    </div>

                    <div class="box-body" style="max-height: 400px;overflow-y:auto">
                      <div id="chartdiv_consoMensuel" style="width:100%;height:300px;font-size:11px;"></div>
                    </div>
                </div>

            </div><!-- /.col -->
          </div>

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Historique des approvisionnements</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="approvisionnement">
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
        </div>

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Historique général des opérations</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="transactionProduit">
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
        </div>


      </section>
    </div>

<script>

  $( document ).ready(function() {

      $('#stockPointDeVente').bootstrapTable({
        data: {{ stockPointDeVente }},
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
                title: "{{ trans["Point de distribution"] }}",
                sortable: true,
            },
            {
                field: 'lot',
                title: "{{ trans["Lot"] }}",
                sortable: true,
            },
            {
                field: 'date_peremption',
                title: "{{ trans['Date de Péremption'] }}",
                formatter: date2OnlyFormatter,
                sortable: true
            },
            {
                field: 'reste',
                title: "{{ trans['Stock'] }}",
                sortable: true
            }
        ]
      });

      $('#approvisionnement').bootstrapTable({
        data: {{ approvisionnement }},
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
                field: 'date',
                title: "{{ trans["Date"] }}",
                formatter: date2Formatter,
                sortable: true,
            },
            {
                field: 'motif',
                title: "{{ trans["Motif"] }}",
                sortable: true,
            },
            {
                field: 'lot',
                title: "{{ trans["Lot"] }}",
                sortable: true,
            },
            {
                field: 'date_peremption',
                title: "{{ trans['Date de Péremption'] }}",
                formatter: date2OnlyFormatter,
                sortable: true
            },
            {
                field: 'quantite',
                title: "{{ trans['Quantité'] }}",
                sortable: true
            }
        ]
      });


      $('#transactionProduit').bootstrapTable({
        data: {{ transactionProduit }},
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
        showColumns: true,
        filterControl: true,
        columns: [
            {
                title: 'state',
                checkbox: true,
            },
            {
                field: 'operation',
                title: "{{ trans[" "] }}",
                formatter: function(value, row){
                    if(value == "s"){
                        return '--';
                    }
                    else if(value == "a"){
                        return '++';
                    }
                    else{
                        return ' ';
                    }
                }
            },
            {
                field: 'date',
                title: "{{ trans["Date de l'opération"] }}",
                sortable: true,
                formatter: date2Formatter
            },
            {
                field: 'point_distribution',
                title: "{{ trans["Point de distribution"] }}",
                sortable: true,
                filterControl: "select"
            },
            {
                field: 'type',
                title: "{{ trans["Type"] }}",
                sortable: true,
                filterControl: "select"
            },
            {
                field: 'lot',
                title: "{{ trans["Lot"] }}",
                sortable: true,
            },
            {
                field: 'date_peremption',
                title: "{{ trans['Date de<br> Peremtion'] }}",
                sortable: true
            },
            {
                field: 'quantite',
                title: "{{ trans['Quantité'] }}",
                sortable: true
            },
            {
                field: 'stock_g_avant',
                title: "{{ trans['Stock G<br> avant'] }}",
                sortable: true
            },
            {
                field: 'stock_g_apres',
                title: "{{ trans['Stock G<br> apres'] }}",
                sortable: true
            },
            {
                field: 'stock_pv_avant',
                title: "{{ trans['Stock P<br> avant'] }}",
                sortable: true
            },
            {
                field: 'stock_pv_apres',
                title: "{{ trans['Stock P<br> apres'] }}",
                sortable: true
            }
        ]
      });


      var chartdiv_consoMensuel = AmCharts.makeChart("chartdiv_consoMensuel", {
            "type": "serial",
            "theme": "light",
            "legend": {
                "useGraphSettings": true
            },
            "dataProvider": {{consoMensuel}},
            "synchronizeGrid":true,
            "valueAxes": [{
                "id":"v1",
                "axisColor": "#FF6600",
                "axisThickness": 2,
                "axisAlpha": 1,
                "position": "left"
            }, {
                "id":"v2",
                "axisColor": "#FCD202",
                "axisThickness": 2,
                "axisAlpha": 1,
                "position": "right"
            }, {
                "id":"v3",
                "axisColor": "#B0DE09",
                "axisThickness": 2,
                "gridAlpha": 0,
                "offset": 50,
                "axisAlpha": 1,
                "position": "left"
            }],
            "graphs": [{
                "valueAxis": "v1",
                "lineColor": "#FF6600",
                "bullet": "round",
                "bulletBorderThickness": 1,
                "hideBulletsCount": 30,
                "title": "Chiffre d'affaire",
                "valueField": "montant",
                "fillAlphas": 0
            },
            {
                "valueAxis": "v2",
                "lineColor": "#FCD202",
                "bullet": "round",
                "bulletBorderThickness": 1,
                "hideBulletsCount": 30,
                "title": "Consommation",
                "valueField": "total",
                "fillAlphas": 0
            },
            {
                "valueAxis": "v3",
                "lineColor": "#B0DE09",
                "bullet": "round",
                "bulletBorderThickness": 1,
                "hideBulletsCount": 30,
                "title": "Consommation Moyenne",
                "valueField": "moyenne",
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
              /*"enabled": true,
              "libs": {
                  "path": "{{ url('assets/amcharts/plugins/export/libs/') }}"
              }*/
             }
        });

  });

</script>