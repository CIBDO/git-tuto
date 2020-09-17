
{{ content() }}
      <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
        <section class="content-header">

          <h1>
            {{ trans['Tableau de bord - Laboratoire']}} 
          </h1>

          <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> {{ trans['Tableau de bord']}}</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <?php $this->flashSession->output() ?>
          <div class="row">
            <div class="col-xs-12">
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
                  <h3>{{totalDemande}}</h3>

                  <p>Nombre total de demande</p>
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
                  <h3>{{totalAttente}}</h3>

                  <p>Nombre de demande en attente</p>
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
                  <h3>{{totalEncour}}</h3>

                  <p>Nombre de demande ecours</p>
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
                  <h3>{{ totalCloture }}</h3>

                  <p>Nombre de demande clôturé</p>
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
                        <h3 class="box-title">Nombre de demande par mois</h3>
                    </div>

                    <div class="box-body" style="max-height: 400px;overflow-y:auto">
                      <div id="chartdiv_DemandeMensuelle" style="width:100%;height:300px;font-size:11px;"></div>
                    </div>
                </div>

            </div><!-- /.col -->
        </div>

        <div class="row">
            <div class="col-md-6">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Nombre de demande par provenance </h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="demandeParProvenance">
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
                        <h3 class="box-title">Nombre de demande par prescripteur </h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table  id="demandeParPrescripteur">
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
      
    var chartdiv_DemandeMensuelle = AmCharts.makeChart("chartdiv_DemandeMensuelle", {
            "type": "serial",
            "theme": "light",
            "legend": {
                "useGraphSettings": true
            },
            "dataProvider": {{mensuelleDemandeGraph}},
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
                "valueField": "nbr",
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


    $('#demandeParProvenance').bootstrapTable({
        data: {{ demandeParProvenance }},
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
                title: "{{ trans["Provenance"] }}",
                //sortable: true,
            },
            {
                field: 'nbr',
                title: "{{ trans["Nombre de cas"] }}",
                //sortable: true,
            }
        ]
      });


    $('#demandeParPrescripteur').bootstrapTable({
        data: {{ demandeParPrescripteur }},
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
                title: "{{ trans["Prescripteur"] }}",
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