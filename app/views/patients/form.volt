<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Formulaire patient
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url("index") }}"><i class="fa fa-dashboard"></i> Dossier</a></li>
            <li><i class="fa fa-credit-card"></i> liste</li>
        </ol>
    </section>

    <?php $this->flash->output() ?>
    
    <!-- Main content -->
    <section class="content">
      <form action="{{ url('patients/form/') }}" class="form ajaxForm" method="post">
        <!-- Main row -->
        <div class="row">
          <section class="col-lg-6">
            <div>
                <div class="box box-primary" style="width: 100%;padding: 0;margin: 0;">
                    <div class="box-header with-border">
                      <h3 class="box-title"><b>Informations personnelles</b></h3>  
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="box-body">
                    
                      <div class="form-group">
                          <label for="nom" class="control-label">{{ trans['ID Technique'] }}</label>
                          {{ form.render('id_technique') }}
                      </div>

                      <div class="form-group">
                          <label for="nom" class="control-label">{{ trans['Nom'] }}</label>
                          {{ form.render('nom') }}
                      </div>

                      <div class="form-group">
                          <label for="prenom" class="control-label">{{ trans['Prenom'] }}</label>
                          {{ form.render('prenom') }}
                      </div>

                      <div class="form-group">
                          <label for="prenom2" class="control-label">{{ trans['Deuxième prénom'] }}</label>
                          {{ form.render('prenom2') }}
                      </div>

                      <div class="form-group">
                          <label for="nom_jeune_fille " class="control-label">{{ trans['Nom de jeune fille'] }}</label>
                          {{ form.render('nom_jeune_fille') }}
                      </div>

                      <div class="form-group">
                          <label for="date_naissance" class="control-label">{{ trans['Date de naissance'] }}</label>
                          {{ form.render('date_naissance') }}
                      </div>

                      <div class="form-group">
                          <label for="telephone" class="control-label">{{ trans['Téléphone'] }}</label>
                          {{ form.render('telephone') }}
                      </div>

                      <div class="form-group">
                          <label for="telephone2" class="control-label">{{ trans['Autre téléphone'] }}</label>
                          {{ form.render('telephone2') }}
                      </div>

                      <div class="form-group">
                          <label for="email" class="control-label">{{ trans['Email'] }}</label>
                          {{ form.render('email') }}
                      </div>

                      <div class="form-group">
                          <label for="sexe" class="control-label">{{ trans['Sexe'] }}</label>
                          {{ form.render('sexe') }}
                      </div>

                      <div class="form-group">
                          <label for="residence_id" class="control-label">{{ trans['Résidence'] }}</label>
                          {{ form.render('residence_id') }}
                      </div>

                      <div class="form-group">
                          <label for="adresse" class="control-label">{{ trans['Adresse'] }}</label>
                          {{ form.render('adresse') }}
                      </div>
                      <div>{{ hiddenField(['id', 'class': 'form-control']) }}</div>
                    </div>
                    <div class="box-footer">
                      <button type="reset" class="btn btn-default">Annuler</button>
                      <button type="submit" class="btn btn-info pull-right">Enregistrer</button>
                    </div>
                </div>
            </div>
          </section>
          <section class="col-lg-6">
            <div>
                <div class="box box-primary" style="width: 100%;padding: 0;margin: 0;">
                    <div class="box-header with-border">
                      <h3 class="box-title">  <b>Parent - conjoint</b> </h3>  
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="box-body">
                      <div class="form-group">
                          <label for="nom_pere" class="control-label">{{ trans['Nom père'] }}</label>
                          {{ form.render('nom_pere') }}
                      </div>

                      <div class="form-group">
                          <label for="contact_pere" class="control-label">{{ trans['Téléphone père'] }}</label>
                          {{ form.render('contact_pere') }}
                      </div>

                      <div class="form-group">
                          <label for="nom_mere" class="control-label">{{ trans['Nom mère'] }}</label>
                          {{ form.render('nom_mere') }}
                      </div>
                      
                      <div class="form-group">
                          <label for="contact_mere" class="control-label">{{ trans['Téléphone mère'] }}</label>
                          {{ form.render('contact_mere') }}
                      </div>

                      <div class="form-group">
                          <label for="nom_conjoint" class="control-label">{{ trans['Nom conjoint(e)'] }}</label>
                          {{ form.render('nom_conjoint') }}
                      </div>

                      <div class="form-group">
                          <label for="contact_conjoint" class="control-label">{{ trans['Téléphone conjoint(e)'] }}</label>
                          {{ form.render('contact_conjoint') }}
                      </div>

                      <div class="form-group">
                          <label for="personne_a_prev" class="control-label">{{ trans['Personne à prevenir (Nom prenom et contact)'] }}</label>
                          {{ form.render('personne_a_prev') }}
                      </div>

                    </div>
                </div>
            </div>

            <div style="margin-top: 10px">
                <div class="box box-primary" style="width: 100%;padding: 0;margin: 0;">
                    <div class="box-header with-border">
                      <h3 class="box-title">  <b>Autres</b> </h3>  
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="box-body">
                       <div class="form-group">
                          <label for="ethnie" class="control-label">{{ trans['Ethnie'] }}</label>
                          {{ form.render('ethnie') }}
                      </div>

                      <div class="form-group">
                          <label for="profession" class="control-label">{{ trans['Proféssion'] }}</label>
                          {{ form.render('profession') }}
                      </div>


                      <div class="form-group">
                          <label for="autre_infos" class="control-label">{{ trans['Autre informations'] }}</label>
                          {{ form.render('autre_infos') }}
                      </div>

                    </div>
                </div>
            </div>

          </section>
        </div>
      </form>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
$(document).ready(function(){
    var width = "100%"; //Width for the select inputs
    $("#residence_id").select2({width: width, placeholder: "choisir", theme: "classic"});
    $("#sexe").select2({width: width, placeholder: "choisir", theme: "classic"});

    $("[data-mask]").inputmask();
});
</script>
