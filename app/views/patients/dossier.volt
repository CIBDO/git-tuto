<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans['Dossier patient'] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans['Dossier patient']}}</a></li>
            <li><i class="fa fa-circl-o"></i> {{ trans['dossier'] }}</li>
            <li class="active">{{ patient.id }}</li>
        </ol>
    </section>

    <?php $this->flash->output() ?>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-5">

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
                      <i class="fa fa-phone margin-r-5"></i> <b>Telephone</b> <a class="pull-right">{{ patient.telephone }}</a>
                    </li>
                    <li class="list-group-item">
                      <i class="fa fa-phone margin-r-5"></i> <b>Autre contact</b> <a class="pull-right">{{ patient.telephone2 }}</a>
                    </li>

                    <li class="list-group-item">
                      <i class="fa fa-map-marker margin-r-5"></i> <b>Résidence</b> <a class="pull-right">{{ patient_residence }}</a>
                      <br /><a> {{ patient.adresse }}</a>
                    </li>
                  </ul>

                {% if (userId == 1) OR in_array("dp_w", userPermissions) OR in_array("dp_a", userPermissions) %}
                  <a href="{{ url("patients/form/")}}{{ patient.id }}" class="btn btn-primary btn-block"><b>Modifier les données personnelles</b></a>
                {% endif %}

                  <div class="row" style="margin-top: 5px">
                    <div class="col-md-12">
                {% if (userId == 1) OR in_array("cs_w", userPermissions) OR in_array("cs_a", userPermissions) %}
                        <a href="{{ url('consultation/consultation/' ~ patient.id) }}"  class="btn btn-warning"><i class="fa fa-stethoscope"></i> Consultation</a>
                {% endif %}

                {% if (userId == 1) OR in_array("labo_r", userPermissions) OR in_array("labo_w", userPermissions) OR in_array("labo_a", userPermissions) %}
                        <a href="{{ url('labo_demandes/dossier/' ~ patient.id) }}" class="btn btn-warning"><i class="fa fa-flask"></i> Analyse medicale</a>
                {% endif %}

                {% if (userId == 1) OR in_array("img_r", userPermissions) OR in_array("img_w", userPermissions) OR in_array("img_a", userPermissions) %}
                        <a href="{{ url('img_demandes/dossier/' ~ patient.id) }}" class="btn btn-warning"><i class="fa fa-clone"></i> Imagérie</a>
                {% endif %}
                    </div>
                  </div>
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
                  <strong><i class="fa fa-book margin-r-5"></i> Derniere visite</strong>
                  <a class="pull-right">{% if sizeof(patient.getDossiersConsultations()) > 0 %} {{date('d/m/Y H:i:m', strtotime( patient.getDossiersConsultations(['order' : 'date_creation Desc'])[0].date_creation)) }} {% else %} ------- {% endif %}</a>

                  <hr>

                  <strong><i class="fa fa-warning margin-r-5"></i> Antécédants</strong>
                  <!-- <a class="btn btn-primary pull-right antecedantpop" data-antecedantid=""  tabindex="0" data-trigger="click" data-toggle="popover" title="Ajouter un antecedant" data-patientid="{{ patient.id }}">
                    <i class="glyphicon glyphicon-plus"></i>
                  </a> -->

                  <p id="antecedantlist">
                    {% for index, antecedant in patient_antecedant %}
                      <div>
                        <span class="label label-{{antecedant['niveau']}}">
                          {{ antecedant['type'] }} - {{ antecedant['libelle'] }}
                          <!-- <i class="glyphicon glyphicon-remove deleteBtnAntecedant"  data-antecedantid="{{ antecedant['id'] }}" data-antecedantname="{{ antecedant['libelle'] }}" title="Assurance" data-patientid="{{ patient.id }}"></i> -->
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
            <div class="col-md-7">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class=""><a href="#infos_perso" data-toggle="tab" aria-expanded="false">Informations personnelles</a></li>
                  <li class=""><a href="#infos_assurance" data-toggle="tab" aria-expanded="false">Assurances</a></li>
                  <li class="active"><a href="#timeline" data-toggle="tab" aria-expanded="true">Historique</a></li>
                  <li class=""><a href="#activity_comm" data-toggle="tab" aria-expanded="false">Activités communautaires</a></li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane" id="infos_perso">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="table-responsive">
                          <table class="table no-margin">
                            <tbody>
                              <tr>
                                <td><b>Deuxieme prenom:</b></td>
                                <td><a>{{ patient.prenom2 }}</a></td>                            
                              </tr>
                              <tr>
                                <td><b>Nom de jeune fille:</b></td>
                                <td><a>{{ patient.nom_jeune_fille }}</a></td>                            
                              </tr>
                              <tr>
                                <td><b>Conjoint(e):</b></td>
                                <td><a>{{ patient.nom_conjoint }}</a></td>                            
                              </tr>
                               <tr>
                                <td><b>Téléphone conjoint(e):</b></td>
                                <td><a>{{ patient.contact_conjoint }}</a></td>                            
                              </tr>
                              <tr>
                                <td><b>Père:</b></td>
                                <td><a>{{ patient.nom_pere }}</a></td>                            
                              </tr>
                              <tr>
                                <td><b>Téléphone Père:</b></td>
                                <td><a>{{ patient.contact_pere }}</a></td>                            
                              </tr>
                              <tr>
                                <td><b>Mère:</b></td>
                                <td><a>{{ patient.nom_mere }}</a></td>                            
                              </tr>
                              <tr>
                                <td><b>Téléphone Mère:</b></td>
                                <td><a>{{ patient.contact_mere }}</a></td>                            
                              </tr>
                              <tr>
                                <td><b>Personne à prévenir:</b></td>
                                <td><a>{{ patient.personne_a_prev }}</a></td>                            
                              </tr>
                              <tr>
                                <td><b>Ethnie:</b></td>
                                <td><a>{{ patient.ethnie }}</a></td>                            
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane" id="infos_assurance">
                    <div class="row">
                      <div class="col-md-12">
                        
                        <div class="table-responsive no-padding">
                      {% if (userId == 1) OR in_array("dp_w", userPermissions) OR in_array("dp_a", userPermissions) %}
                          <a class="btn btn-primary pull-right assurancepop" data-assuranceid=""  tabindex="0" data-trigger="click" data-toggle="popover" title="Assurance" data-patientid="{{ patient.id }}">
                            <i class="glyphicon glyphicon-plus"></i> Ajouter
                          </a>
                      {% endif %}
                          <table class="table table-hover" id="assurancelist">
                            <thead>
                                <th> Organisme </th>
                                <th> Numéro </th>
                                <th> OGD </th>
                                <th> Bénéficiaire </th>
                                <th> Autres infos</th>
                      {% if (userId == 1) OR in_array("dp_w", userPermissions) OR in_array("dp_a", userPermissions) %}
                                <th> Action </th>
                      {% endif %}
                            </thead>
                            <tbody>
                                {% for index, assurance in patient_assurance %}
                                 <tr id="tr_{{ assurance['id'] }}"> 
                                    <td id="ass_{{ assurance['organisme'].id }}"> 
                                      {{ assurance['organisme'].libelle }} 
                                      {% if assurance['default'] == 1 %} 
                                        <span class="label label-info"><i class="fa fa-check-circle"></i></span>
                                        {% endif %}
                                    </td>
                                    <td> {{ assurance['numero'] }} </td>
                                    <td> {{ assurance['ogd'] }} </td>
                                    <td> {{ assurance['beneficiaire'] }} </td>
                                    <td> {{ assurance['autres_infos'] }} </td>
                      {% if (userId == 1) OR in_array("dp_w", userPermissions) OR in_array("dp_a", userPermissions) %}
                                    <td>
                                        <a class="label label-warning assurancepop" tabindex="{{ index }}" data-trigger="click" data-assuranceid="{{ assurance['id'] }}" data-toggle="popover" title="Assurance" data-patientid="{{ patient.id }}">
                                            <i class="glyphicon glyphicon-edit"></i>
                                        </a>
                                          &nbsp;&nbsp;&nbsp;
                                        <span class="label label-danger deleteBtnAssurance" data-assuranceid="{{ assurance['id'] }}" data-assurancename="{{ assurance['organisme'].libelle }}" title="Assurance" data-patientid="{{ patient.id }}">
                                            <i class="glyphicon glyphicon-remove"></i>
                                        </span>
                                    </td>
                      {% endif %}
                                </tr>
                                {% endfor %}
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- /.tab-pane -->
                  <div class="tab-pane active" id="timeline">
                    <!-- The timeline -->
                    <ul class="timeline timeline-inverse">
                      <!-- timeline time label -->
                      <!-- <li class="time-label">
                            <span class="bg-red">
                              13 Juin 2016
                            </span>
                      </li> -->
                      <!-- /.timeline-label -->
                      <!-- timeline item -->
                    {% for index, timeline in patient_timeline %}
                      <li>

                      {% if timeline['type'] == 'consultation' %}
                        <i class="fa fa-stethoscope bg-aqua"></i>
                      {% elseif timeline['type'] == 'suivi' %}
                        <i class="fa fa-stethoscope bg-yellow"></i>
                      {% elseif timeline['type'] == 'labo' %}
                        <i class="fa fa-flask bg-green"></i>
                      {% elseif timeline['type'] == 'imagerie' %}
                        <i class="fa fa-clone bg-green"></i>
                      {% endif %}

                        <div class="timeline-item">
                          <span class="time"><i class="fa fa-clock-o"></i> {{ date('d/m/Y h:i', strtotime(timeline['date'])) }}</span>
                          <h3 class="timeline-header no-border"  style="padding-top: 3px;padding-bottom: 3px;">
                            <a>{{timeline['titre']}}</a> 
                          </h3>
                          <div class="timeline-body" style="padding-top: 3px;padding-bottom: 3px;">
                            {% for key, body in timeline['body'] %}
                              [ <b>{{key}} : </b> {{body}} ]
                            {% endfor %}
                          </div>

                        {% if (userId == 1) OR in_array(timeline['scope']~"_r", userPermissions) OR in_array(timeline['scope']~"_w", userPermissions) OR in_array(timeline['scope']~"_a", userPermissions) %}
                          <div class="timeline-footer"  style="padding-top: 3px;padding-bottom: 3px;">
                            <a class="btn btn-primary btn-xs" href="{{url(timeline['url'])}}">détails</a>
                          </div>
                        {% endif %}

                        </div>
                      </li>
                    {% endfor %}
                      <!-- END timeline item -->
                      <!-- timeline item -->
                      <!-- <li>
                        <i class="fa fa-user bg-yellow"></i>

                        <div class="timeline-item">
                          <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                          <h3 class="timeline-header"><a href="#">Dossier de sejour</a></h3>

                          <div class="timeline-body">
                            ---------
                          </div>
                          <div class="timeline-footer">
                            <a class="btn btn-warning btn-xs">View details</a>
                          </div>
                        </div>
                      </li> -->
                      <!-- END timeline item -->
                      
                      <li>
                        <i class="fa fa-clock-o bg-gray"></i>
                      </li>
                    </ul>
                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="activity_comm">
                    <!-- Post -->
                    <div class="post">
                      
                      <p>
                        Ici des données des ASC
                      </p>

                    </div>
                    <!-- /.post -->
                  </div>

                </div>
                <!-- /.tab-content -->
              </div>
              <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
        
    </section>

    <div id="createShop" class="modal fade" role="dialog"></div>
    <div id="editShop" class="modal fade" role="dialog"></div>
    <div id="showMerchant" class="modal fade" role="dialog"></div>
</div>

<script type="text/javascript">
$(document).ready(function(){
   
  $('body').on('click', '.assurancepop', function () {
    $(this).popover(
        {
            placement : 'left',
            html : true, 
            template: '<div class="popover" style="max-width:500px"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div><div class="popover-footer"><a href="#" class="btn btn-info btn-sm">Fermer</a></div></div>',
            content: function() {
                      var text = "";
                      $.ajax({
                          url: "{{url('patients/assurancePopover/')}}" + $(this).data('patientid') + "/" + $(this).data('assuranceid'),
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
  });

  $(document).on("click", ".popover-footer .btn" , function(){
      $(this).parents(".popover").popover('hide');
   });
  $('body').on('click', '.deleteBtnAssurance', function () {
      var id = $(this).data('assuranceid');
      var currentTr = $(this).closest("tr");
      swal(
          {
              title: '{{ trans["Are you sure?"] }}',
              text: "{{ trans["Cette action supprimera l'assurance nommée:"] }} '" + $(this).data('assurancename') + "' des informations du patient",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: '{{ trans["Yes, delete it!"] }}',
              cancelButtonText: '{{ trans["No, cancel!"] }}',
              confirmButtonClass: 'btn btn-success',
              cancelButtonClass: 'btn btn-danger',
              buttonsStyling: false,
              closeOnConfirm: true,
              closeOnCancel: true
          })
          .then(function() {
              $('body').addClass('loading');
              $.ajax({
                  url: "{{url('patients/deleteAssurance')}}/" + id,
                  cache: false,
                  async: true
              })
              .done(function( result ) {
                  console.log(result);
                  $('body').removeClass('loading');
                  if(result == "1"){
                     /* swal(
                          '{{ trans["Deleted!"] }}',
                          '{{ trans["The Organisme has been successfully deleted from the patient informations."] }}',
                          'success'
                      );*/
                      $(currentTr).css('background-color', 'red').fadeOut(1000, function(){ $(this).remove();});
                      //$('body').addClass('loading');
                      //window.location.reload();
                  }
                  else{
                      swal(
                          '{{ trans["Cancelled!"] }}',
                          "{{ trans['Cancelled'] }}",
                          'error'
                      );
                  }
              });
          }, function(dismiss) {
            // dismiss can be 'cancel', 'overlay', 'close', 'timer'
            // if (dismiss === 'cancel') {
            //   swal(
            //     'Cancelled',
            //     '---',
            //     'warning'
            //   );
            // }
          });
  });

});



</script>