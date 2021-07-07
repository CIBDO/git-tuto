<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Paramétrage du système
        </h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-credit-card"></i> parametrage</li>
        </ol>
    </section>

    <?php $this->flash->output() ?>
    
    <!-- Main content -->
    <section class="content">
      <form action="" class="form ajaxForm" method="post" enctype="multipart/form-data">
        <!-- Main row -->
        <div class="row">
          <section class="col-lg-6">
            <div>
                <div class="box box-primary" style="width: 100%;padding: 0;margin: 0;">
                    <div class="box-header with-border">
                      <h3 class="box-title"><b>Informations de la structure</b></h3>  
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="box-body">
                      <div class="form-group">
                          <label for="nom" class="control-label">{{ trans['Nom de la structure'] }}</label>
                          {{ textField(['nom', 'class': 'form-control']) }}

                      </div>

                      <div class="form-group">
                          <label for="adresse" class="control-label">{{ trans['Adresse'] }}</label>
                          {{ textField(['adresse', 'class': 'form-control']) }}
                      </div>

                      <div class="form-group">
                          <label for="telephone" class="control-label">{{ trans['Téléphone'] }}</label>
                          {{ textField(['telephone', 'class': 'form-control']) }}
                      </div>

                    </div>
                </div>
            </div>

            {% if activeModules.modPharmacie == 1 %}
            <div>
                <div class="box box-primary" style="width: 100%;margin-top: 10px;">
                    <div class="box-header with-border">
                      <h3 class="box-title">  <b>Pharmacie</b> </h3>  
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="box-body">
                      <div class="form-group">
                          <label for="pharmacie_type" class="control-label">{{ trans['Type de gestion'] }}</label>
                          {{ select(['pharmacie_type',  ['0' : 'Gestion mono-stock', '1' : 'Gestion multi-stock'], 'useEmpty' : false, 'class': 'form-control', 'id' : 'pharmacie_type', 'required' : 'required']) }}
                      </div>

                      <div class="form-group">
                          <label for="default_lot" class="control-label">{{ trans['Numéro de lot par défaut '] }}</label>
                          {{ textField(['default_lot', 'class': 'form-control']) }}
                      </div>

                      <div class="form-group">
                          <label for="default_peremption" class="control-label">{{ trans['Date de péremption par défaut'] }}</label>
                          {{ dateField(['default_peremption', 'class': 'form-control']) }}
                      </div>

                      <div class="form-group">
                          <label for="default_coef" class="control-label">{{ trans['Coefficient multiplicateur par défaut'] }}</label>
                          {{ numericField(['default_coef', 'class': 'form-control']) }}
                      </div>

                    </div>
                </div>
            </div>
            {% endif %}

            {% if activeModules.modConsultation == 1 %}
            <div>
                <div class="box box-primary"  style="width: 100%;margin-top: 10px;">
                    <div class="box-header with-border">
                      <h3 class="box-title">  <b>Consultation</b> </h3>  
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="box-body">
                      <div class="form-group">
                          <label for="default_constante" class="control-label">{{ trans['Constantes par défaut'] }}</label>
                          <i>(Séparer par des virgules)</i>
                          {{ textField(['default_constante', 'class': 'form-control']) }}
                      </div>

                      <div class="form-group">
                          <label for="default_examen" class="control-label">{{ trans['Examens par défaut'] }}</label>
                          <i>(Format: type/libellé,type/libellé, etc...)</i>
                          {{ textField(['default_examen', 'class': 'form-control']) }}
                      </div>

                      <div class="form-group">
                          <label for="diagnostic_source" class="control-label">{{ trans['Source de données diagnostic'] }}</label>
                          {{ select(['diagnostic_source',  ['ajaxCim10' : 'CIM10', 'ajaxDiagnostic' : 'Autre'], 'useEmpty' : false, 'class': 'form-control', 'id' : 'diagnostic_source', 'required' : 'required']) }}
                      </div>

                    </div>
                </div>
            </div>
            {% endif %}

            {% if activeModules.modImagerie == 1 %}
            <div>
                <div class="box box-primary"  style="width: 100%;margin-top: 10px;">
                    <div class="box-header with-border">
                      <h3 class="box-title">  <b>Imagerie</b> </h3>  
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="box-body">
                      <div class="form-group">
                          <label for="img_msg_annonce" class="control-label">{{ trans["Message d'annonce de resultat"] }}</label>
                          {{ textField(['img_msg_annonce', 'class': 'form-control']) }}
                      </div>

                      <div class="form-group">
                          <label for="img_msg_fin" class="control-label">{{ trans['Message de fin de resultat'] }}</label>
                          {{ textField(['img_msg_fin', 'class': 'form-control']) }}
                      </div>

                    </div>
                </div>
            </div>
            {% endif %}
          </section>

          <section class="col-lg-6">
            
            <div>
                <div class="box box-primary" style="width: 100%;padding: 0;margin: 0;">
                    <div class="box-header with-border">
                      <h3 class="box-title">  <b>Entête et logo</b> </h3>  
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="box-body">
                      
                      <div class="form-group">
                          <label for="type_entete" class="control-label">{{ trans["Type d'entête"] }}</label>
                          {{ select(['type_entete',  ['e' : 'Entête complete en image', 'l' : 'Logo + 4 lignes'], 'useEmpty' : true, 'emptyText' : "Choisir", 'class': 'form-control', 'id' : 'type_entete']) }}
                      </div>

                      <div class="form-group">
                          <label for="logo" class="control-label">{{ trans["Fichier d'entête"] }}</label>
                          {{ fileField(['logo', 'class': 'form-control']) }}
                      </div>

                      {% if logo != "" %}
                      <div class="form-group">
                          <img class="img-responsive pad" src="{{ static_url("img/structure/") }}{{ logo }}" alt="Logo">
                      </div>
                      {% endif %}

                      <div class="form-group">
                          <label for="pharmacie_type" class="control-label">{{ trans["Template d'affichage de l'entête"] }}</label>
                          {{ select(['template_logo',  ['left' : 'Logo à gauche - texte à droite', 'right' : 'Logo à droite - texte à gauche', 'top' : 'Logo en haut au milieu - texte en dessous'], 'useEmpty' : true, 'emptyText' : "Choisir", 'class': 'form-control', 'id' : 'template_logo']) }}
                      </div>

                      <div class="form-group">
                          <label for="ligne1" class="control-label">{{ trans["Ligne d'entête 1"] }}</label>
                          {{ textField(['ligne1', 'class': 'form-control']) }}
                      </div>

                      <div class="form-group">
                          <label for="ligne2" class="control-label">{{ trans["Ligne d'entête 2"] }}</label>
                          {{ textField(['ligne2', 'class': 'form-control']) }}
                      </div>

                      <div class="form-group">
                          <label for="ligne3" class="control-label">{{ trans["Ligne d'entête 3"] }}</label>
                          {{ textField(['ligne3', 'class': 'form-control']) }}
                      </div>

                      <div class="form-group">
                          <label for="ligne4" class="control-label">{{ trans["Ligne d'entête 4"] }}</label>
                          {{ textField(['ligne4', 'class': 'form-control']) }}
                      </div>

                    </div>
                </div>
            </div>

          </section>

          <div class="row">
              <div class="col-xs-12">
              </div>
          </div>

          <section class="content">
            <div class="row">
              <div class="col-xs-12" >
                  <div class="box">
                      <div class="box-body">
                        <div class="row">
                          <div class="col-md-12">
                            <button type="reset" class="btn btn-default">Annuler</button>
                            <button type="submit" class="btn btn-info pull-right">Enregistrer</button>
                          </div>
                        </div>
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
