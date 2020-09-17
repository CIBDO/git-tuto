<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {{ patient.nom ~ " " ~ patient.prenom }} 
            /
            Consultation - suivis - 
            {% if form_action == 'edit' %}
                {{ trans['Modification'] }}
            {% else %}
                {{ trans['Création'] }}
            {% endif %}

            {% if formulaire is defined %}
                 / Type de formulaire : {{ formulaire.libelle }}
            {% endif %}

            </h4>
        </div>
        <form action="{{ url('consultation/createSuivi/' ~ patients_id ~ '/' ~ consultation_id ~ '/' ~ suivi_id) }}" class="form" method="post">
            <div class="modal-body">
                <div class="error_modal_container"></div>
                
                <div class="row">
                  <div class="col-xs-12" >
                    <div class="box box-default" style="width: 100%;padding: 0;margin: 0;">
                      <div class="box-header with-border">
                        <h3 class="box-title"><b>Rappel d'informations</b></h3>  
                        <div class="box-tools pull-right">
                          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                          </button>
                        </div>
                      </div>
                      <div class="box-body">
                        <div class="row">

                          <!-- Antecedant -->
                          <div class="col-xs-3" >
                            <div class="box box-danger" style="width: 100%;padding: 0;margin: 0;">
                              <div class="box-header with-border">
                                <h5 class="box-title">Antécédent</h5>  
                              </div>
                               <div class="box-body">
                                    {% for index, antecedant in patient_antecedant %}
                                        <span class="label label-{{antecedant['niveau']}}">
                                          {{ antecedant['type'] }} - {{ antecedant['libelle'] }}
                                        </span> &nbsp;
                                    {% endfor %}
                               </div>
                            </div>
                          </div>
                          <!-- Dernières Prescriptions -->
                          <div class="col-xs-3" >
                            <div class="box box-default" style="width: 100%;padding: 0;margin: 0;">
                              <div class="box-header with-border">
                                <h5 class="box-title">Dernières prescriptions</h5>  
                              </div>
                               <div class="box-body">
                                  {% for index, last_prescription in last_prescriptions %}
                                    <li>
                                      <span class="text">
                                        {{ last_prescription['medicament'] }} - 
                                        {{ last_prescription['quantite'] }} -
                                        {{ last_prescription['mode'] }} -
                                        {{ last_prescription['posologie'] }} 
                                      </span>
                                    </li>
                                  {% endfor %}
                               </div>
                            </div>
                          </div>
                          <!-- Dernières Constantes -->
                          <div class="col-xs-3" >
                            <div class="box box-default" style="width: 100%;padding: 0;margin: 0;">
                              <div class="box-header with-border">
                                <h5 class="box-title">Dernières Constantes</h5>  
                              </div>
                               <div class="box-body">
                                  {% for index, last_constante in last_constantes %}
                                    <li>
                                      <span class="text">
                                        {{ last_constante['cle'] }} - 
                                        {{ last_constante['valeur'] }}
                                      </span>
                                    </li>
                                  {% endfor %}
                               </div>
                            </div>
                          </div>
                          <!-- Derniers diagnostique -->
                          <div class="col-xs-3" >
                            <div class="box box-default" style="width: 100%;padding: 0;margin: 0;">
                              <div class="box-header with-border">
                                <h5 class="box-title">Derniers diagnostics</h5>  
                              </div>
                               <div class="box-body">
                                  <b>Hypotheses:</b>
                                  {% for index, last_hypothese in last_hypotheses %}
                                    <li>
                                      <span class="text">
                                        {{ last_hypothese }}
                                      </span>
                                    </li>
                                  {% endfor %}
                                  <b>Diagnostics:</b>
                                  {% for index, last_diagnostique in last_diagnostiques %}
                                    <li>
                                      <span class="text">
                                        {{ last_diagnostique }}
                                      </span>
                                    </li>
                                  {% endfor %}
                               </div>
                            </div>
                          </div>

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
                    <div class="col-xs-6" >
                        <div class="box box-default" style="width: 100%;padding: 0;margin: 0;">
                            <div class="box-header with-border">
                              <h3 class="box-title"><b>Observations</b></h3>
                            </div>
                            <div class="box-body" >
                              {{ textArea(['observation', 'class': '', 'required' : 'required', 'placeholder' : 'observation', 'style' : 'width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;' ]) }}
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6" >
                        <div class="box box-default" style="width: 100%;padding: 0;margin: 0;">
                          <div class="box-header with-border">
                            <h3 class="box-title">
                              <b>Constantes</b>
                              <a href="#" class="s_constanteAdd" title="Ajouter une constante">
                                <i class="glyphicon glyphicon-plus"></i>
                              </a>
                            </h3>  
                            <div class="box-tools pull-right">
                              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                              </button>
                            </div>
                          </div>
                          <div class="box-body">
                            <ul class="todo-list" id="s_constList">
                                <li>
                                  <span class="text">
                                    <b>Poids:</b>  
                                    {{ numericField(['poids', 'class': '', 'id': 'poids', 'step' : 'any']) }} Kg
                                  </span>
                                </li>
                                <li>
                                  <span class="text">
                                    <b>Taille:</b>  
                                    {{ numericField(['taille', 'class': '', 'id': 'taille', 'step' : 'any']) }} cm
                                  </span>
                                </li>
                            {% if form_action != 'edit' %}

                              {% for index, default_constante in default_constantes %}
                                <li>
                                  <span class="handle">
                                    <i class="fa fa-ellipsis-v"></i>
                                    <i class="fa fa-ellipsis-v"></i>
                                  </span>
                                  <span class="text">
                                    <input type="text"  name="cons_cle[]" autocomplete="off" value="{{ default_constante }}" placeholder="Constante" /> - 
                                    <input type="text"  name="cons_valeur[]" autocomplete="off" value="" placeholder="Valeur" />
                                  </span>
                                  <div class="tools">
                                    <i class="fa fa-trash-o suppElement"></i>
                                  </div>
                                </li>
                              {% endfor %}

                            {% endif %}
                            {% for index, dossier_constante in dossier_constantes %}
                              <li>
                                <span class="handle">
                                  <i class="fa fa-ellipsis-v"></i>
                                  <i class="fa fa-ellipsis-v"></i>
                                </span>
                                <span class="text">
                                  <input type="hidden"  name="idconstante[]" value="{{ dossier_constante['id'] }}" /> 
                                  <input type="text"  name="cons_cle[]" autocomplete="off" value="{{ dossier_constante['cle'] }}" placeholder="Constante" /> - 
                                  <input type="text"  name="cons_valeur[]" autocomplete="off" value="{{ dossier_constante['valeur'] }}" placeholder="Valeur" />
                                </span>
                                <div class="tools">
                                  <i class="fa fa-trash-o suppElement"></i>
                                </div>
                              </li>
                            {% endfor %}
                            </ul>
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
                    <div class="col-xs-7" >
                        <div class="box box-default" style="width: 100%;padding: 0;margin: 0;">
                          <div class="box-header with-border">
                            <h3 class="box-title">
                              <b>Prescriptions</b>
                              <a href="#" class="s_prescriptionAdd" title="Ajouter un médicament">
                                <i class="glyphicon glyphicon-plus"></i>
                              </a>
                            </h3>  
                            <div class="box-tools pull-right">
                              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                              </button>
                            </div>
                          </div>
                          <div class="box-body">
                            <ul class="todo-list ui-sortable"  id="s_prescList">
                            {% for index, dossier_prescription in dossier_prescriptions %}
                              <li>
                                <span class="handle">
                                  <i class="fa fa-ellipsis-v"></i>
                                  <i class="fa fa-ellipsis-v"></i>
                                </span>
                                <span class="text"> 
                                  <input type="hidden"  name="idprescription[]" value="{{ dossier_prescription['id'] }}" /> 
                                  <input type="hidden" class="medicament_id"  name="medicament_id[]" value="{{ dossier_prescription['medicament_id'] }}" />
                                  <input type="text"  required="required" name="medicament[]" style="width: 260px" class="pharmacietypeahead" autocomplete="off" value="{{ dossier_prescription['medicament'] }}" placeholder="Médicament" /> - 
                                  <input type="number" required="required"  name="quantite[]" style="width: 60px" autocomplete="off" value="{{ dossier_prescription['quantite'] }}" placeholder="Quantité" /> - 
                                  <input type="text" required="required" style="width: 150px" name="mode[]" lass="pharmaciemodetypeahead" autocomplete="off" value="{{ dossier_prescription['mode'] }}" placeholder="Mode" /> -
                                  <input type="text" required="required" name="posologie[]" autocomplete="off" class="pharmacieposologietypeahead" value="{{ dossier_prescription['posologie'] }}" placeholder="Posologie" /> - 
                                  <input type="text" required="required"  style="width: 70px" class="pharmaciedureetypeahead" name="duree[]" autocomplete="off" value="{{ dossier_prescription['duree'] }}" placeholder="Durée" />
                                </span>
                                <div class="tools">
                                  <i class="fa fa-trash-o suppElement"></i>
                                </div>
                              </li>
                            {% endfor %}
                            </ul>
                          </div>
                        </div>
                    </div>

                    <div class="col-xs-5" >
                      <div class="box box-default" style="width: 100%;padding: 0;margin: 0;">
                          <div class="box-header with-border">
                            <h3 class="box-title">  
                              <b>Examen complementaires</b> 
                              <a href="#" class="s_examenAdd" title="Ajouter un examen">
                                <i class="glyphicon glyphicon-plus"></i>
                              </a>
                            </h3>
                            <div class="box-tools pull-right">
                              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                              </button>
                            </div>
                          </div>
                          <div class="box-body">
                              {{ selectStatic(['exam_comps[]', exam_comps, 'multiple': 'multiple', 'class': 'form-control', 'id': 'exam_comps', 'class': 'exam_comps', 'useEmpty': true, 'emptyText' : '']) }}

                              {{ textArea(['resultat_exam_comp', 'class': '', 'placeholder' : 'resultat des examens', 'style' : 'width: 100%; height: 60px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;']) }}
                          </div>
                      </div>
                  </div>
                </div>
                    
            </div>

            <div class="modal-footer">
                <div>{{ hiddenField(['patient_id', 'value' : patients_id]) }}</div>
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="{{ trans['Fermer'] }}">{{ trans['Fermer'] }}</button>
                <input type="submit" value="{{trans['Enregistrer']}}" class="btn btn-success pull-right" title="{{trans['Enregistrer']}}">
            </div>
        </form>
    </div>
</div>

<script>
  
  var width = "100%"; //Width for the select inputs
  $(function () {
    $(".todo-list").sortable({
      placeholder: "sort-highlight",
      handle: ".handle",
      forcePlaceholderSize: true,
      zIndex: 999999
    });

    $(".exam_comps").select2({
        width: width,
        allowClear: true,
        theme: "classic"
    });
    
    $(".textarea").wysihtml5({
        toolbar: {
          "font-styles": false, // Font styling, e.g. h1, h2, etc.
          "emphasis": false, // Italics, bold, etc.
          "lists": false, // (Un)ordered lists, e.g. Bullets, Numbers.
          "html": false, // Button which allows you to edit the generated HTML.
          "link": false, // Button to insert a link.
          "image": false, // Button to insert an image.
          "color": false, // Button to change color of font
          "blockquote": false, // Blockquote
        }
      });

      function displayResult(item) {
          console.log(item.value);
          var current = JSON.parse(item.value);
          setTimeout(function(){
            var currentMedId = $(":focus").closest("li").find(".medicament_id").val(JSON.parse(item.value).id);
          }, 900);
      }
      $('.pharmacietypeahead').typeahead({
          ajax: {
              url: '{{url("produit/ajaxProduit")}}',
              method: 'get',
          },
          displayField: 'libelle',
          scrollBar:true,
          onSelect: displayResult
      });

      $('input.examcomplementaire').typeahead({
          ajax: {
              url: '{{url("actes/ajaxActeLabo")}}',
              method: 'get',
          },
          displayField: 'libelle',
          scrollBar:true
          //onSelect: displayResult
      });

      $('input.pharmaciemodetypeahead').typeahead({
          ajax: {
              url: '{{url("consultation/ajaxPrescriptionMode")}}',
              method: 'get',
          },
          displayField: 'libelle',
          scrollBar:true
          //onSelect: displayResult
      });

      $('input.pharmacieposologietypeahead').typeahead({
          ajax: {
              url: '{{url("consultation/ajaxPrescriptionPosologie")}}',
              method: 'get',
          },
          displayField: 'libelle',
          scrollBar:true
          //onSelect: displayResult
      });

      $('input.pharmaciedureetypeahead').typeahead({
          ajax: {
              url: '{{url("consultation/ajaxPrescriptionDuree")}}',
              method: 'get',
          },
          displayField: 'libelle',
          scrollBar:true
          //onSelect: displayResult
      });

  });
</script>