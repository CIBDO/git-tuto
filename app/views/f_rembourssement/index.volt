<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans["Facturation"] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans["Finance"]}}</a></li>
            <li class="active"><i class="fa fa-circl-o"></i> {{ trans['Facturation'] }}</li>
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

                            <div class="row">
                                <div class="form-group  col-md-2" style="margin-right : 10px">
                                    <label for="date1">{{ trans['Du ']}} :</label>
                                        {{ dateField(['date1', 'class': 'form-control', 'id': 'date1']) }}
                                </div>

                                <div class="form-group  col-md-2" style="margin-right : 10px">
                                    <label for="date2">{{ trans['Au ']}} :</label>
                                    {{ dateField(['date2', 'class': 'form-control', 'id': 'date2']) }}
                                </div>

                            </div>

                            <br />

                            <div class="row">
                                <div class="form-group  col-md-2" style="margin-right : 10px">
                                    <label for="date1">{{ trans['Recherche par mot clé']}} :</label><br>
                                        {{ textField(['name_assurance', 'class': 'form-control', 'id': 'name_assurance']) }}
                                </div>


                                <div class="form-group  col-md-3">
                                    <label for="typeAssurance">{{ trans["Tierce Payant"]}} :</label>
                                    {{ select('type_assurance', typeAssurancelist, 'class': 'form-control', 'using' : ['id', 'libelle'], 'useEmpty' : true, 'emptyText' : 'Choisir', 'id' : '_type_assurance') }}
                                </div>

                                 <div class="form-group  col-md-3">
                                    <label for="typeAssurance">{{ trans["OGD"]}} :</label><br>
                                    {{ select('ogd', ['inps' : 'inps', 'cmss' : 'cmss'], 'class': 'form-control', 'useEmpty' : true, 'emptyText' : 'Choisir', 'id' : '_ogd') }}
                                </div>

                                <div class="form-group  col-md-2">
                                    <button type="submit" class="btn btn-defaultx  ajax-navigation" title="{{trans['Recherche']}}">
                                        <i class="fa fa-filter"></i> {{trans['Filtrer']}}
                                    </button>
                                </div>
                            </div>  

                        </form>

                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main row -->
        <div class="row">
            <div class="col-xs-6">

                <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Prestations</h3>
                    </div>

                    <div class="box-body">
                        
                        <div class="table-responsive">
                            
                            <table class="table no-margin">
                              <tbody>
                                <tr>
                                  <th>Total</th>
                                  <td>{{ number_format(tickets['montant_normal'],0,'.',' ') }} F CFA</td>
                                </tr>
                                <tr>
                                  <th>Part/Assuré</th>
                                  <td>{{ number_format(tickets['montant_patient'],0,'.',' ') }} F CFA</td>
                                </tr>
                                <tr>
                                  <th>Part/Assureur</th>
                                  <td>{{ number_format(tickets['reste'],0,'.',' ') }} F CFA</td>
                                </tr>
                              </tbody>
                            </table>

                        </div>

                    </div>
                    {% if tickets['montant_normal'] is defined and tickets['montant_normal'] > 0 %}
                    <div class="box-footer clearfix">
                      <a href="{{ url('print/rembourssementsPrestations/' ~ date1 ~ '/'~ date2  ~ '/'  ~ tickets['reste'] ~ '/'  ~ tickets['montant_patient'] ~ '/'  ~ tickets['montant_normal'] ~ '/'  ~ type_assurance ~ '/'  ~ ogd ~  '/'  ~ name_assurance) }}" class="btn btn-sm btn-info btn-flat ">Voir la facture -></a>
                    </div>
                    {% endif %}
                </div>
            </div>

            <div class="col-xs-6">

                <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Pharmacie</h3>
                    </div>

                    <div class="box-body">
                        
                        <table class="table no-margin">
                             <tbody>
                                <tr>
                                  <th>Total</th>
                                  <td>{{ number_format(pharmacie['montant_normal'],0,'.',' ') }} F CFA</td>
                                </tr>
                                <tr>
                                  <th>Part/Assuré</th>
                                  <td>{{ number_format(pharmacie['montant_patient'],0,'.',' ') }} F CFA</td>
                                </tr>
                                <tr>
                                  <th>Part/Assureur</th>
                                  <td>{{ number_format(pharmacie['reste'],0,'.',' ') }} F CFA</td>
                                </tr>
                              </tbody>
                        </table>

                    </div>

                    {% if pharmacie['montant_normal'] is defined and pharmacie['montant_normal'] > 0 %}
                    <div class="box-footer clearfix">
                      <a href="{{ url('print/rembourssementsPharmacie/' ~ date1 ~ '/'~ date2  ~ '/'  ~ pharmacie['reste'] ~ '/'  ~ pharmacie['montant_patient'] ~ '/'  ~ pharmacie['montant_normal'] ~ '/'  ~ type_assurance ~ '/'  ~ ogd ~  '/'  ~ name_assurance) }}" class="btn btn-sm btn-info btn-flat ">Voir la facture -></a>
                    </div>
                    {% endif %}

                </div>
            </div>

        </div>
        
    </section>

</div>

<script>
    
    var width = "200px"; //Width for the select inputs
    $("#_type_assurance").select2({width: width, allowClear: true, placeholder: "choisir", theme: "classic"});
    $("#_ogd").select2({width: width, allowClear: true, placeholder: "choisir", theme: "classic"});

    $( document ).ready(function() {
        

        

    });

</script>