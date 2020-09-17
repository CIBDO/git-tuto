
{{ content() }}
      <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">

          <h1>
            {{ trans['Tableau de bord - Consultation']}} 
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
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{totalVisite}}</h3>

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
                  <h3>{{totalInitial}}</h3>

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
                  <h3>{{totalSuivi}}</h3>

                  <p>Nombre de suivis</p>
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
                  <h3>{{ number_format(nbrMoyenSuivi,2,'.',' ') }}</h3>

                  <p>Nombre moyen de suivis</p>
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
                        <h3 class="box-title">Nombre de nouvelles consultations par tranche d’âge</h3>
                    </div>

                    <div class="box-body" style="max-height: 400px;overflow-y:auto">
                      <div id="chartdiv_visiteParTrancheAge" style="width:100%;height:300px;font-size:11px;"></div>
                    </div>
                </div>

            </div><!-- /.col -->

            <div class="col-md-6">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Nombre de visite par sexe</h3>
                    </div>

                    <div class="box-body" style="max-height: 400px;overflow-y:auto">
                      <div id="chartdiv_visiteParSexeGraph" style="width:100%;height:300px;font-size:11px;"></div>
                    </div>
                </div>
              
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Nombre de nouvelles consultations par mois</h3>
                    </div>

                    <div class="box-body" style="max-height: 400px;overflow-y:auto">
                      <div id="chartdiv_mensuelleInitialGraph" style="width:100%;height:300px;font-size:11px;"></div>
                    </div>
                </div>

            </div><!-- /.col -->
        </div>


        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Nombre de visite par mois</h3>
                    </div>

                    <div class="box-body" style="max-height: 400px;overflow-y:auto">
                      <div id="chartdiv_mensuelleVisiteGraph" style="width:100%;height:300px;font-size:11px;"></div>
                    </div>
                </div>

            </div><!-- /.col -->
        </div>

        <div class="row">
            <div class="col-md-6">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Top 10 des diagnostics </h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="top10Diagnostics">
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

              
            </div>
        </div>

      </section>
    </div>

<script>

  $( document ).ready(function() {

    var chartdiv_visiteParSexeGraph = AmCharts.makeChart("chartdiv_visiteParSexeGraph", {
        "type": "pie",
        "theme": "light",
        "dataProvider": {{ visiteParSexeGraph }},
        "valueField": "nbr",
        "titleField": "sexe",
        "balloon":{
            "fixedPosition":true
        },
        "pathToImages": '{{ static_url("bower_components/amcharts3/amcharts/images/") }}',
        "export": {
          //"enabled": true,
          "libs": {
            "path": "{{ url('assets/amcharts/plugins/export/libs/') }}"
          }
        }

      });
      
    var chartdiv_mensuelleInitialGraph = AmCharts.makeChart("chartdiv_mensuelleInitialGraph", {
            "type": "serial",
            "theme": "light",
            "legend": {
                "useGraphSettings": true
            },
            "dataProvider": {{mensuelleInitialGraph}},
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
                //"title": "Chiffre d'affaire",
                "valueField": "initial",
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


    var chartdiv_mensuelleVisiteGraph = AmCharts.makeChart("chartdiv_mensuelleVisiteGraph", {
            "type": "serial",
            "theme": "light",
            "legend": {
                "useGraphSettings": true
            },
            "dataProvider": {{mensuelleVisiteGraph}},
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
                //"title": "Chiffre d'affaire",
                "valueField": "visite",
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
        

    var chartdiv_visiteParTrancheAge = AmCharts.makeChart("chartdiv_visiteParTrancheAge", {
            "type": "serial",
            "theme": "light",
            "categoryField": "tranche",
            "rotate": false,
            "startDuration": 1,
            "categoryAxis": {
                "gridPosition": "start",
                "position": "left"
            },
            "trendLines": [],
            "graphs": [
                {
                    "balloonText": "Patients:[[value]]",
                    "fillAlphas": 0.8,
                    "id": "AmGraph-1",
                    "lineAlpha": 0.2,
                    "title": "Patients",
                    "type": "column",
                    "valueField": "patient"
                },
                {
                    "balloonText": "Nouvelles consultation:[[value]]",
                    "fillAlphas": 0.8,
                    "id": "AmGraph-2",
                    "lineAlpha": 0.2,
                    "title": "Nouvelles consultation",
                    "type": "column",
                    "valueField": "nouvelle"
                },
                {
                    "balloonText": "Suivis:[[value]]",
                    "fillAlphas": 0.8,
                    "id": "AmGraph-3",
                    "lineAlpha": 0.2,
                    "title": "Suivis",
                    "type": "column",
                    "valueField": "suivi"
                }
            ],
            "guides": [],
            "valueAxes": [
                {
                    "id": "ValueAxis-1",
                    "position": "left",
                    "axisAlpha": 0
                }
            ],
            "legend": {
                "horizontalGap": 10,
                "useGraphSettings": true,
                "markerSize": 10
              },
            "allLabels": [],
            "balloon": {},
            "titles": [],
            "dataProvider": {{ visiteParTrancheAge }},
            "pathToImages": '{{ static_url("bower_components/amcharts3/amcharts/images/") }}',
            "export": {
              /*"enabled": true,
              "libs": {
                  "path": "{{ url('assets/amcharts/plugins/export/libs/') }}"
              }*/
             }

      });


    $('#top10Diagnostics').bootstrapTable({
        data: {{ top10Diagnostics }},
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        /*sortOrder: "desc",
        sortName: "montant",*/
        locale: "{% if language == 'fr' %}fr-FR{% else %}en{% endif %}",
        search: false,
        minimumCountColumns: 2,
        clickToSelect: false,
        toolbar: "#toolbar",
        showFooter: false,
        showExport: true,
        showPaginationSwitch: false,
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
                field: 'libelle',
                title: "{{ trans["Diagnostic"] }}",
                //sortable: true,
            },
            {
                field: 'nbr',
                title: "{{ trans["Nombre de cas"] }}",
                //sortable: true,
            }
        ]
      });

  });

</script>