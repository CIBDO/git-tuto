
{{ content() }}
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">

          <h1>
            {{ trans['Tableau de bord - Finance']}} 
          </h1>

          <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> {{ trans['Dashboard']}}</li>
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

          <!-- <div class="row">
            <div class="col-md-12">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dépense sur les 12 derniers mois</h3>
                    </div>

                    <div class="box-body" style="max-height: 400px;overflow-y:auto">
                      <div id="chartdiv_depenseMensuelle" style="width:100%;height:300px;font-size:11px;"></div>
                    </div>
                </div>

            </div>
          </div> -->


        <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{ number_format(opDepense,0,'.',' ') }}</h3>

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
                  <h3>{{ number_format(opRecette,0,'.',' ') }}</h3>

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
                  <h3>{{ number_format(soldeEspece,0,'.',' ') }}</h3>

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
                  <h3>{{ number_format(soldeBanque,0,'.',' ') }}</h3>

                  <p>Solde Banque</p>
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
                        <h3 class="box-title">Etat des comptes de dépense</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="tableauDepense">
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
                        <h3 class="box-title">Patrimoine financier</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="tableauSolde">
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

            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{ number_format(montantEncaisseBe,0,'.',' ') }}</h3>

                  <p>Récette - Bureau des entrées</p>
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
                  <h3>{{ number_format(montantEncaissePh,0,'.',' ') }}</h3>

                  <p>Récette - Pharmacie</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">Détails <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div>


            <div class="col-md-6">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Etat des comptes de récette</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="tableauRecette">
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

      $('#tableauDepense').bootstrapTable({
        data: {{ tableauDepense }},
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
                field: 'compte_numero',
                title: "{{ trans["Compte"] }}",
                sortable: true,
            },
            {
                field: 'compte_libelle',
                title: "{{ trans["Libellé du compte"] }}",
                sortable: true,
            },
            {
                field: 'montant',
                title: "{{ trans['Montant'] }}",
                align: 'right',
                formatter: amountFormatter,
                sortable: true
            }
        ]
      });

      $('#tableauRecette').bootstrapTable({
        data: {{ tableauRecette }},
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
                field: 'compte_numero',
                title: "{{ trans["Compte"] }}",
                sortable: true,
            },
            {
                field: 'compte_libelle',
                title: "{{ trans["Libellé du compte"] }}",
                sortable: true,
            },
            {
                field: 'montant',
                title: "{{ trans['Montant'] }}",
                align: 'right',
                formatter: amountFormatter,
                sortable: true
            }
        ]
      });

      $('#tableauSolde').bootstrapTable({
        data: {{ tableauSolde }},
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
        showFooter: true,
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
                field: 'compte',
                title: "{{ trans["Compte"] }}",
                sortable: true,
                footerFormatter: function(){return 'Total';}
            },
            {
                field: 'montant',
                title: "{{ trans['Montant'] }}",
                formatter: amountFormatter,
                align: 'right',
                sortable: true,
                footerFormatter: function(data){
                    var total = 0;
                    for(var i = 0; i<data.length; i++){
                        total = total + parseFloat(data[i].montant, 10);
                    }
                    return numberFormatter(total);
                }
            }
        ]
      });


      var chartdiv_depenseMensuelle = AmCharts.makeChart("chartdiv_depenseMensuelle", {
            "type": "serial",
            "theme": "light",
            "legend": {
                "useGraphSettings": true
            },
            "dataProvider": {{depenseMensuelle}},
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

        chartdiv_depenseMensuelle.addListener("dataUpdated", zoomChart);
        function zoomChart(){
            chartdiv_depenseMensuelle.zoomToIndexes(chartdiv_depenseMensuelle.dataProvider.length - 20, chartdiv_depenseMensuelle.dataProvider.length - 1);
        }
        zoomChart();

  });

</script>