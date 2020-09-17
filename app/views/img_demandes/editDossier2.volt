<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans["Dossier d'imagerie médicale"] }} - {{ trans[' N°'] }} {{ dossier_id }} 
            <a class="btn btn-info" href="{{ url('img_demandes/dossier') }}/{{ patient.id }}">
                {{trans["Retour à la liste des dossiers du patient"]}}
            </a>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('patients/index') }}"><i class="fa fa-dashboard"></i> {{ trans['Dossier patient']}}</a></li>
            <li><i class="fa fa-circl-o"></i> {{ trans["Dossier d'imagerie médicale"] }}</li>
            <li class="active">{{ patient.id }}</li>
        </ol>
    </section>

    <?php $this->flash->output() ?>

    <!-- Main content -->
    <section class="content connectedSortable">
        <div class="row">
            <div class="col-md-3">

              <!-- Profile Image -->
              <div class="box box-primary">
                <div class="box-body box-profile">
                  <img class="profile-user-img img-responsive img-circle" src="{{ url("img/user.jpg") }}" alt="User profile picture">

                  <h3 class="profile-username text-center">{{ patient.nom ~ ' ' ~  patient.prenom }}</h3>

                  <p class="text-muted text-center">{{ patient.profession }}</p>

                  <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <i class="fa fa-key margin-r-5"></i> <b>Identfiant</b> <a class="pull-right">{{ patient.id }}</a>
                    </li>
                    <li class="list-group-item">
                      <i class="fa fa-key margin-r-5"></i> <b>ID Technique</b> <a class="pull-right">{{ patient.id_technique }}</a>
                    </li>
                    <li class="list-group-item">
                      <i class="fa fa-birthday-cake margin-r-5"></i> <b>Date de naissance</b> <a class="pull-right">{{ date('d/m/Y', strtotime(patient.date_naissance)) }}</a>
                    </li>
                    <li class="list-group-item">
                      <i class="fa fa-phone margin-r-5"></i> <b>Téléphone</b> <a class="pull-right">{{ patient.telephone }}</a>
                    </li>
                    <li class="list-group-item">
                      <i class="fa fa-phone margin-r-5"></i> <b>Autre contact</b> <a class="pull-right">{{ patient.telephone2 }}</a>
                    </li>

                    <li class="list-group-item">
                      <i class="fa fa-map-marker margin-r-5"></i> <b>Résidence</b> <a class="pull-right">{{ patient_residence }}</a>
                      <br /><a> {{ patient.adresse }}</a>
                    </li>
                  </ul>

                  <a href="{{ url("patients/dossier/")}}{{ patient.id }}" class="btn btn-primary btn-block"><b>Voir le dossier complet</b></a>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->

              <!-- About Me Box -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">A propos </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <strong><i class="fa fa-book margin-r-5"></i> Dernière visite</strong>
                  <a class="pull-right">{% if sizeof(patient.getLaboDemandes()) > 0 %} {{date('d/m/Y H:i:m', strtotime( patient.getLaboDemandes(['order' : 'date Desc'])[0].date)) }} {% else %} ------- {% endif %}</a>

                  <hr>

                  <strong><i class="fa fa-warning margin-r-5"></i> Antécédents</strong>
                  <!-- <a class="btn btn-primary pull-right antecedantpop" data-antecedantid=""  tabindex="0" data-trigger="click" data-toggle="popover" title="Ajouter un antécédent" data-patientid="{{ patient.id }}">
                    <i class="glyphicon glyphicon-plus"></i>
                  </a> -->

                  <p id="antecedantlist">
                    {% for index, antecedant in patient_antecedant %}
                      <div>
                        <span class="label label-{{antecedant['niveau']}}">
                          {{ antecedant['type'] }} - {{ antecedant['libelle'] }}
                          <i class="glyphicon glyphicon-remove deleteBtnAntecedant"  data-antecedantid="{{ antecedant['id'] }}" data-antecedantname="{{ antecedant['libelle'] }}" title="Assurance" data-patientid="{{ patient.id }}"></i>
                        </span>
                      </div>
                    {% endfor %}
                  </p>

                  <hr>

                  <strong><i class="fa fa-file-text-o margin-r-5"></i> Autres informations</strong>

                  <p>{{ patient.autre_infos }}</p>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
             
                      <!-- ------------------------ -->

              <div class="box box-primary editresultitem">
                <div class="box-body" >

                  <form action="{{ url('img_demandes/editDossier2/' ~ patients_id ~ '/' ~ dossier_id) }}"  id="f_result"  class="form" method="post">

                    {% if imgDemande.etat != 'clotûré' %}
                      <div class="row">
                          <div class="col-xs-12" >
                            <button type="button" class="btn btn-info pull-right importDialog" data-toggle="modal" data-target="importDialog">
                                <i class="fa fa-level-down"></i> {{trans["Importer un modele"]}}
                            </button>
                          </div>
                      </div>
                    {% endif %}
                    
                      <div class="row">
                          <div class="col-xs-6" >
                              <div class="box" style="width: 100%;padding: 0;margin: 0;">
                                  <div class="box-body" >
                                    <div class="form-group">
                                        <label for="provenance" class="control-label">{{ trans['Provenance'] }}</label>
                                        {{ textField(['provenance', 'class': 'form-control provenancetypeahead', 'required' : 'required', 'id' : 'provenance', "autocomplete" : "off"]) }}
                                    </div>
                                  </div>
                              </div>
                          </div>

                         <div class="col-xs-6" >
                              <div class="box" style="width: 100%;padding: 0;margin: 0;">
                                  <div class="box-body" >
                                    <div class="form-group">
                                        <label for="prescripteur" class="control-label">{{ trans['Prescripteur'] }}</label>
                                        {{ textField(['prescripteur', 'class': 'form-control prescripteurtypeahead', 'required' : 'required', 'id' : 'prescripteur', "autocomplete" : "off"]) }}
                                    </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      
                      <div class="row">
                          <div class="col-xs-12" >
                              &nbsp;
                          </div>
                      </div>

                     <!--  <div class="row">
                          <div class="col-xs-12" >
                              <div class="box" style="width: 100%;padding: 0;margin: 0;">
                                  <div class="box-body">
                                    <div class="form-group">
                                        <label for="provenance" class="control-label">{{ trans["Indication"] }}</label>
                                        {{ textArea(['indication', 'class': 'textarea', 'placeholder' : "Indications", 'style' : 'width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;' ]) }}
                                    </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-xs-12" >
                              &nbsp;
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-xs-12" >
                              <div class="box" style="width: 100%;padding: 0;margin: 0;">
                                  <div class="box-body">
                                    <div class="form-group">
                                        <label for="provenance" class="control-label">{{ trans["Protocole d'examen"] }}</label>
                                        {{ textArea(['protocole', 'class': 'textarea', 'placeholder' : "Protocole d'examen", 'style' : 'width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;' ]) }}
                                    </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-xs-12" >
                              &nbsp;
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-xs-12" >
                              <div class="box" style="width: 100%;padding: 0;margin: 0;">
                                  <div class="box-body">
                                    <div class="form-group">
                                        <label for="provenance" class="control-label">{{ trans['Interpretation'] }}</label>
                                        {{ textArea(['interpretation', 'class': 'textarea', 'placeholder' : 'Interpretation', 'style' : 'width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;' ]) }}
                                    </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-xs-12" >
                              &nbsp;
                          </div>
                      </div> -->

                      <div class="row">
                          <div class="col-xs-12" >
                              <div class="box" style="width: 100%;padding: 0;margin: 0;">
                                  <div class="box-body">
                                    <div class="form-group">
                                        <label for="provenance" class="control-label">{{ trans['Interprétation'] }}</label>
                                        {{ textArea(['conclusion', 'class': 'textarea', 'placeholder' : 'Interprétation', 'style' : 'width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;' ]) }}
                                    </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <input type="submit" value="{{trans['Enregistrer']}}" class="btn btn-success pull-right" title="{{trans['Enregistrer']}}">
                        </div>
                      </div>
                      {{ hiddenField(['patient_id', 'value' : patients_id]) }}
                      <input type="hidden" id="current_analyse"  name="current_analyse" value="" /> 
                  </form>
                  <div id="antibiogrammeModal" class="modal fade" role="dialog"></div>

                </div>
              </div>

                      <!-- --------------------------- -->
                 
                <!-- /.tab-content -->
            </div>
              <!-- /.nav-tabs-custom -->
          </div>
            <!-- /.col -->
        </div>
        
    </section>

    <div id="importDialog" class="modal largemodal fade" role="dialog"></div>

</div>

<script>
    function dateFormatter(value, row, index) {
        if (value) {
            return moment(value, "X").format("{{ trans['js_date_format'] }}");
        } else {
            return "-";
        } 
    }
   
    $(".textarea").wysihtml5({
        toolbar: {
          "font-styles": true, // Font styling, e.g. h1, h2, etc.
          "emphasis": true, // Italics, bold, etc.
          "lists": true, // (Un)ordered lists, e.g. Bullets, Numbers.
          "html": false, // Button which allows you to edit the generated HTML.
          "link": false, // Button to insert a link.
          "image": false, // Button to insert an image.
          "color": false, // Button to change color of font
          "blockquote": false, // Blockquote
        }
    });

    $( document ).ready(function() {

      $('body').on('submit', '#f_result', function () {
          $('body').addClass('loading');
      });

      $('body').on('click', '.importDialog', function () {
            $('#importDialog').modal("show");
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('img_modele/importModele')}}/{{imgDemande.patients_id}}/{{imgDemande.id}}",
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                console.log($('#importDialog'));
                $('#importDialog').html(html);
            });
        });

      
      $(".antecedantpop").popover(
      {
          placement : 'left',
          html : true, 
          template: '<div class="popover" style="max-width:500px"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div><div class="popover-footer"><a href="#" class="btn btn-info btn-sm">Fermer</a></div></div>',
          content: function() {
                    var text = "";
                    $.ajax({
                        url: "{{url('img_demandes/antecedantPopover/')}}" + $(this).data('patientid') + "/" + $(this).data('antecedantid'),
                        cache: false,
                        async: false
                    })
                    .done(function( html ) {
                        text = html;
                    });
                    return text;
                  },
          title: "Titre",
          animation: true,
          delay: { show: 100, hide: 100 },
      });
      $(document).on("click", ".popover-footer .btn" , function(){
        $(this).parents(".popover").popover('hide');
      });

      function ajaxLoader(){

        $('input.provenancetypeahead').typeahead({
            ajax: {
                url: '{{url("img_demandes/ajaxProvenance")}}',
                method: 'get',
            },
            displayField: 'libelle',
            scrollBar:true
            //onSelect: displayResult
        });

        $('input.prescripteurtypeahead').typeahead({
            ajax: {
                url: '{{url("img_demandes/ajaxPrescripteur")}}',
                method: 'get',
            },
            displayField: 'libelle',
            scrollBar:true
            //onSelect: displayResult
        });
      }

      ajaxLoader();
      submitAjaxForm();

    });
</script>