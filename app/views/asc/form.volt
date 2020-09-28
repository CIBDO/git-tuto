<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Ajout - ASC
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url("index") }}"><i class="fa fa-dashboard"></i> Dossier</a></li>
            <li><i class="fa fa-credit-card"></i> liste</li>
        </ol>
    </section>

    <?php $this->flash->output() ?>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <a type="button" class="btn btn-primary" href="{{ url('asc/') }}">
                    <i class="fa fa-list"></i> {{ trans['Liste des ASC'] }}
                </a>
            </div>
        </div>
        <form action="{{ url('asc/form/') }}" class="form ajaxForm" method="post">
            <!-- Main row -->
            <div class="row">
                <section class="col-md-offset-3 col-lg-6">
                    <div>
                        <div class="box box-primary" style="width: 100%;padding: 0;margin: 0;">
                            <div class="box-header with-border">
                                <h3 class="box-title"><b>Veuillez saisir les informations de l'ASC</b></h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="box-body">

                                <div class="form-group">
                                    <label for="code_asc" class="control-label">{{ trans['Code ASC'] }}</label>
                                    {{ form.render('code_asc') }}
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
                                    <label for="telephone" class="control-label">{{ trans['Téléphone'] }}</label>
                                    {{ form.render('telephone') }}
                                </div>

                                <div class="form-group">
                                    <label for="profession" class="control-label">{{ trans['Proféssion'] }}</label>
                                    {{ form.render('profession') }}
                                </div>


                                <div class="form-group">
                                    <label for="residence_id" class="control-label">{{ trans['Résidence'] }}</label>
                                    {{ form.render('residence_id') }}
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
            </div>
        </form>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    $(document).ready(function () {
        var width = "100%"; //Width for the select inputs
        $("#residence_id").select2({width: width, placeholder: "choisir", theme: "classic"});
        $("#sexe").select2({width: width, placeholder: "choisir", theme: "classic"});

        $("[data-mask]").inputmask();
    });
</script>
