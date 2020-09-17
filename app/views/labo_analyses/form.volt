<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Formulaire - Analyse
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans["Analyses"]}}</a></li>
            <li class="active"><i class="fa fa-circl-o"></i> {{ trans['Gestion'] }}</li>
        </ol>
    </section>

    <?php $this->flash->output() ?>
    
    <!-- Main content -->
    <section class="content">
      <form action="{{ url('labo_analyses/form/') }}" class="form ajaxForm" method="post">
        <!-- Main row -->
        <div class="row">
          <section class="col-lg-12">
            <div>
                <div class="box box-primary" style="width: 100%;padding: 0;margin: 0;">
                    <!-- <div class="box-header with-border">
                      <h3 class="box-title"><b>Informations personnelles</b></h3>  
                    </div> -->
                    <div class="box-body">
                    
                      <div class="form-group">
                          <label for="labo_categories_analyse_id" class="control-label">{{ trans['Catégorie'] }}</label>
                           {{ selectStatic(['labo_categories_analyse_id', labo_categories_analyse, 'using' : ['id', 'libelle'], 'class': 'form-control', 'required' : 'required', 'id': 'labo_categories_analyse_id', 'useEmpty': true, 'emptyText': "---------"]) }}
                      </div>

                      <div class="form-group">
                          <label for="libelle" class="control-label">{{ trans['Libellé'] }}</label>
                          {{ textField(['libelle', 'class': 'form-control', 'required' : 'required']) }}
                      </div>

                      <div class="form-group">
                          <label for="code" class="control-label">{{ trans['Code'] }}</label>
                          {{ textField(['code', 'class': 'form-control', 'required' : 'required']) }}
                      </div>

                      <div class="form-group">
                          <label for="has_antibiogramme" class="control-label">{{ trans["Cette analyse peut faire l'objet d'un antibiogrammes"] }}</label>
                          {{ checkField(["has_antibiogramme", "value" : "Y"]) }}
                      </div>

                      <div class="form-group">
                          <label for="parent" class="control-label">{{ trans['Cette analyse est constituée de plusieurs éléments'] }}</label>
                          {{ checkField(["parent", "value" : "Y", 'id' : 'parent']) }}
                      </div>

                      <div id="dd">
                       
                        <div class="form-group">
                            <label for="type_valeur" class="control-label">{{ trans['Type de valeur'] }}</label>
                            {{ select('type_valeur',  ['n' : 'Numérique', 'a' : 'Alpha numérique', 'm' : 'Liste de choix'], 'useEmpty' : true, 'emptyText' : "Choisir", 'class': 'form-control', 'id' : 'type_valeur', 'required' : 'required') }}
                        </div>

                        <div class="form-group">
                            <label for="valeur_possible" class="control-label">{{ trans['Valeurs possibles'] }}</label>
                            <i>(Séparer par des virgules)</i>
                            {{ textField(['valeur_possible', 'class': 'form-control']) }}
                        </div>

                        <div class="form-group">
                            <label for="unite" class="control-label">{{ trans['Unités de mesure possibles'] }}</label>
                            <i>(Séparer par des virgules)</i>
                            {{ textField(['unite', 'class': 'form-control']) }}
                        </div>

                        <div class="form-group">
                            <label for="norme" class="control-label">{{ trans['Normes'] }}</label>
                            {{ textField(['norme', 'class': 'form-control']) }}
                        </div>

                      </div>

                      <div id="child">
                        <div class="col-xs-12" >
                          <div class="box box-primary" style="width: 100%;padding: 0;margin: 0;">
                            <div class="box-header with-border">
                              <h3 class="box-title">
                                <b>Sous analyses</b>
                                <a href="#" class="addChild" data-toggle="modal" data-analyseid="{% if analyse_id is defined %} {{analyse_id}} {% endif %}" data-target="#childModal" title="Editer les éléments">
                                  <i class="glyphicon glyphicon-edit"></i>
                                </a>
                              </h3>  
                            </div>
                            <div class="box-body">
                              <ul class="todo-list" id="childList">
                                {% if children is defined %}
                                  {% for index, child in children %}
                                    <li> 
                                      <input type="hidden"  name="position[]" value="" /> 
                                      <!-- <span class="handle"> 
                                        <i class="fa fa-ellipsis-v"></i> 
                                        <i class="fa fa-ellipsis-v"></i> 
                                      </span>  -->
                                      <span class="text"> 
                                        Libellé: <span class="blue3"> {{child["libelle"]}} </span> | 
                                            <input type="hidden"  name="childs_id[]" value="{{child["id"]}}" />
                                         Code: <span class="blue3"> {{child["code"]}} </span> |
                                         Type de valeur: <span class="blue3"> {{child["type_valeur"]}} </span> |
                                         Valeurs possibles: <span class="blue3"> {{child["valeur_possible"]}} </span> |
                                         Unité de mesure: <span class="blue3"> {{child["unite"]}} </span> |
                                         Normes: <span class="blue3"> {{child["norme"]}} </span> 
                                      </span> 
                                      <div class="tools">
                                        <!-- <i class="fa fa-edit editChild" data-childid="{{child['id']}}" data-toggle="modal" data-target="#childEditModal"></i> -->
                                        <i class="fa fa-trash-o suppElement"></i>
                                      </div>
                                    </li>
                                  {% endfor %}
                                {% endif %}
                              </ul>
                            </div>
                          </div>
                        </div>
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

    <div id="childModal" class="modal fade" role="dialog"></div>

</div><!-- /.content-wrapper -->

<script>
$(document).ready(function(){

    if($("#parent").prop("checked")){
      $("#dd").hide();
      $("#type_valeur").removeAttr("required");
      $("#child").show();
    }else{
      $("#dd").show();
      $("#type_valeur").attr("required", "required");
      $("#child").hide();
    }

    $('body').on('click', '.addChild', function () {
        $('body').addClass('loading');
        $.ajax({
            url: "{{url('labo_analyses/addChild')}}/" + $(this).data('analyseid'),
            cache: false,
            async: true
        })
        .done(function( html ) {
            $('body').removeClass('loading');
            $('#childModal').html(html);
        });
    });

    $('body').on('change', '#parent', function () {
      if($(this).prop("checked")){
        $("#dd").hide();
        $("#type_valeur").removeAttr("required");
        $("#child").show();
      }
      else{
        $("#dd").show();
        $("#type_valeur").attr("required", "required");
        $("#child").hide();
      }
    });

    $(".todo-list").sortable({
      placeholder: "sort-highlight",
      handle: ".handle",
      forcePlaceholderSize: true,
      zIndex: 999999
    });

    $('body').on('submit', '.addChildForm', function( event ) {
        // Stop form from submitting normally
        event.preventDefault();
        $('body').addClass('loading');

        // Get some values from elements on the page:
        var $form   = $(this),
            url     = $form.attr('action'),
            data    = $form.serialize();

        // Send the data using post
        var posting = $.post(url, data);

        // Put the results in a div
        posting.done(function( data ) {
            $('body').removeClass('loading');
            $( "#childList" ).empty().append( data );
            $("#childModal").modal("hide");
        });
    });

    $('body').on('click', '.suppElement', function () {
        var current = $(this).closest("li");
        $(current).css('background-color', '#ff9933').fadeOut(300, function(){ $(this).remove();});
    });
});
</script>
