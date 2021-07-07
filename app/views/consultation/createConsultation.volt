<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {{ patient.nom ~ " " ~ patient.prenom }} 
            /
            Consultation
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
        <form action="{{ url('consultation/createConsultation/' ~ patients_id ~ '/' ~ consultation_id) }}" class="form" method="post">
            <div class="modal-body">
                <div class="error_modal_container"></div>

                <div class="row">
                <div class="col-xs-12">
                    <div class="box box-default">
                      <div class="box-header with-border">
                        <h3 class="box-title">&nbsp;</h3>
                        <div class="box-tools pull-right">
                          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        
                        <div class="nav-tabs-custom">

                          <ul class="nav nav-tabs">
                          {% for index, onglet in ongletList %}
                            <li {% if index == 0 %} class="active" {% endif %}><a href="#onglet_{{ onglet['id'] }}" data-toggle="tab" aria-expanded="{% if index == 0 %}true{% else %}false{% endif %}">{{ onglet['libelle'] }}</a></li>
                          {% endfor %}
                          </ul>

                          <div class="tab-content">
                          {% for index, onglet in ongletList %}

                            <div class="tab-pane {% if index == 0 %} active {% endif %}>" id="onglet_{{ onglet['id'] }}">
                              <div class="row">

                                  <div class="col-md-12">

                                    {% for index, elem in onglet['fields'] %}

                                      {% include "consultation/tpl_elem_c.volt" %}

                                      <!-- {% if (index+1) % 10 == 0 %}
                                        </div>
                                        <div class="col-md-6">
                                      {% endif %} -->

                                    {% endfor %}

                                  </div>

                              </div>
                            </div>

                          {% endfor %}
                          </div>

                        </div>

                      </div><!-- /.box-body -->
                    </div>
                  </div>  
                </div>
                
                <br />

            {% if formulaire.hide_default == 0 %}

                <div class="row">
                    <div class="col-xs-6" >
                        <div class="box box-default" style="width: 100%;padding: 0;margin: 0;">
                            <div class="box-header with-border">
                              <h3 class="box-title"><b>Motif</b></h3>
                            </div>
                            <div class="box-body" >
                              {{ selectStatic(['motifs[]', motifs, 'multiple': 'multiple', 'class': 'form-control', 'id': 'motifs', 'class': 'motifs', 'useEmpty': true, 'emptyText' : '']) }}

                              {{ textArea(['motif', 'class': '', 'placeholder' : 'motif', 'style' : 'width: 100%; height: 60px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;']) }}

                              <!-- --------  -->
                              
                              {% for index, elem in formulaireElements %}

                                {% if elem['place_after_c'] == 'motif' %}
                                
                                  {% include "consultation/tpl_elem_c.volt" %}

                                {% endif %}

                              {% endfor %}

                              <!-- --------  -->

                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6" >
                        <div class="box box-default" style="width: 100%;padding: 0;margin: 0;">
                            <div class="box-header with-border">
                              <h3 class="box-title"> <b>Histoire de la maladie</b> </h3>
                            </div>
                            <div class="box-body">
                              <b>REFERE ASC:</b> 
                              {{ select(['refere_asc',  ['oui' : 'oui', 'non' : 'non'], 'class': '', 'id' : 'refere_asc']) }}

                              <br /><br />

                              <b>Début de la maladie:</b> 
                              {{ dateField(['debut_maladie', 'class': '', 'size': '70', 'id': 'debut_maladie', 'required' : 'required']) }} 
                              - 
                              {{ select(['debut_maladie_periode',  ['matin' : 'matin', 'après-midi' : 'après-midi', 'soir' : 'soir', 'nuit' : 'nuit'], 'useEmpty' : true, 'emptyText' : 'Quand?', 'class': '', 'id' : 'debut_maladie_periode', 'required' : 'required']) }}

                              <br /><br />
                              {{ textArea(['histoire', 'class': '', 'placeholder' : 'histoire de la maladie', 'style' : 'width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;' ]) }}

                              <!-- --------  -->
                              
                              {% for index, elem in formulaireElements %}

                                {% if elem['place_after_c'] == 'histoire' %}
                                
                                  {% include "consultation/tpl_elem_c.volt" %}

                                {% endif %}

                              {% endfor %}

                              <!-- --------  -->

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
                              <h3 class="box-title"><b>Examen clinique</b></h3>
                            </div>
                            <div class="box-body">
                              {{ textArea(['examen_clinique', 'class': '', 'placeholder' : 'examen clinique', 'style' : 'width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;' ]) }}

                              <!-- --------  -->
                              
                              {% for index, elem in formulaireElements %}

                                {% if elem['place_after_c'] == 'exam_clinic' %}
                                
                                  {% include "consultation/tpl_elem_c.volt" %}

                                {% endif %}

                              {% endfor %}

                              <!-- --------  -->

                            </div>
                        </div>
                    </div>


                    <div class="col-xs-6" >
                        <div class="box box-default" style="width: 100%;padding: 0;margin: 0;">
                          <div class="box-header with-border">
                            <h3 class="box-title">
                              <b>Constantes</b>
                              <a href="#" class="constanteAdd" title="Ajouter une constante">
                                <i class="glyphicon glyphicon-plus"></i>
                              </a>
                            </h3>  
                            <div class="box-tools pull-right">
                              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                              </button>
                            </div>
                          </div>
                          <div class="box-body">
                            <ul class="todo-list ui-sortable" id="constList">
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

                            <!-- --------  -->
                              
                              {% for index, elem in formulaireElements %}

                                {% if elem['place_after_c'] == 'constante' %}
                                
                                  {% include "consultation/tpl_elem_c.volt" %}

                                {% endif %}

                              {% endfor %}

                              <!-- --------  -->

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
                            <h3 class="box-title">
                              <b>Hypothèses Diagnostiques</b>
                              <a href="#" class="hypotheseAdd" title="Ajouter une Hypothèse Diagnostique">
                                <i class="glyphicon glyphicon-plus"></i>
                              </a>
                            </h3>  
                            <div class="box-tools pull-right">
                              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                              </button>
                            </div>
                          </div>
                          <div class="box-body">
                            <ul class="todo-list ui-sortable" id="hypotheseList">
                            {% for index, dossier_hypothese in dossier_hypotheses %}
                              <li>
                                <span class="handle">
                                  <i class="fa fa-ellipsis-v"></i>
                                  <i class="fa fa-ellipsis-v"></i>
                                </span>
                                <span class="text">
                                  <input type="text"  name="hypothese[]" size="50" class="diagnostiquetypeahead" autocomplete="off" value="{{ dossier_hypothese }}" placeholder="Hypothèse" />
                                </span>
                                <div class="tools">
                                  <i class="fa fa-trash-o suppElement"></i>
                                </div>
                              </li>
                            {% endfor %}
                            </ul>

                            <!-- --------  -->
                              
                              {% for index, elem in formulaireElements %}

                                {% if elem['place_after_c'] == 'hypothese' %}
                                
                                  {% include "consultation/tpl_elem_c.volt" %}

                                {% endif %}

                              {% endfor %}

                              <!-- --------  -->

                          </div>
                        </div>
                    </div>

                  <div class="col-xs-6" >
                      <div class="box box-default" style="width: 100%;padding: 0;margin: 0;">
                          <div class="box-header with-border">
                            <h3 class="box-title">  
                              <b>Examen complementaires</b> 
                              <a href="#" class="examenAdd" title="Ajouter un examen">
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
                            

                            <!-- --------  -->
                              
                              {% for index, elem in formulaireElements %}

                                {% if elem['place_after_c'] == 'exam_comp' %}
                                
                                  {% include "consultation/tpl_elem_c.volt" %}

                                {% endif %}

                              {% endfor %}

                              <!-- --------  -->

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
                    <div class="col-xs-5" >
                        <div class="box box-default" style="width: 100%;padding: 0;margin: 0;">
                          <div class="box-header with-border">
                            <h3 class="box-title">
                              <b>Diagnostics</b>
                              <a href="#" class="diagnostiqueAdd" title="Ajouter un Diagnostic">
                                <i class="glyphicon glyphicon-plus"></i>
                              </a>
                            </h3>  
                            <div class="box-tools pull-right">
                              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                              </button>
                            </div>
                          </div>
                          <div class="box-body">
                            <ul class="todo-list ui-sortable" id="diagnostiqueList">
                            {% for index, dossier_diagnostique in dossier_diagnostiques %}
                              <li>
                                <span class="handle">
                                  <i class="fa fa-ellipsis-v"></i>
                                  <i class="fa fa-ellipsis-v"></i>
                                </span>
                                <span class="text">
                                  <input type="text"  name="diagnostique[]" size="50" class="diagnostiquetypeahead" autocomplete="off" value="{{ dossier_diagnostique }}" placeholder="Diagnostique" />
                                </span>
                                <div class="tools">
                                  <i class="fa fa-trash-o suppElement"></i>
                                </div>
                              </li>
                            {% endfor %}
                            </ul>

                            <!-- --------  -->
                              
                              {% for index, elem in formulaireElements %}

                                {% if elem['place_after_c'] == 'diagnostic' %}
                                
                                  {% include "consultation/tpl_elem_c.volt" %}

                                {% endif %}

                              {% endfor %}

                              <!-- --------  -->

                          </div>
                        </div>
                    </div>


                    <div class="col-xs-7" >
                        <div class="box box-default" style="width: 100%;padding: 0;margin: 0;">
                          <div class="box-header with-border">
                            <h3 class="box-title">
                              <b>Prescriptions</b>
                              <a href="#" class="prescriptionAdd" title="Ajouter un médicament">
                                <i class="glyphicon glyphicon-plus"></i>
                              </a>
                            </h3>  
                            <div class="box-tools pull-right">
                              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                              </button>
                            </div>
                          </div>
                          <div class="box-body">
                            <ul class="todo-list ui-sortable"  id="prescList">
                            {% for index, dossier_prescription in dossier_prescriptions %}
                              <li>
                                <span class="handle">
                                  <i class="fa fa-ellipsis-v"></i>
                                  <i class="fa fa-ellipsis-v"></i>
                                </span>
                                <span class="text"> 
                                  <input type="hidden" name="idprescription[]" value="{{ dossier_prescription['id'] }}" /> 
                                  <input type="hidden" class="medicament_id"  name="medicament_id[]" value="{{ dossier_prescription['medicament_id'] }}" />
                                  <input type="text"  name="medicament[]" style="width: 260px" class="pharmacietypeahead" autocomplete="off" required="required" value="{{ dossier_prescription['medicament'] }}" placeholder="Médicament" /> - 
                                  <input type="number" required="required"  name="quantite[]" style="width: 60px" autocomplete="off" value="{{ dossier_prescription['quantite'] }}" placeholder="Quantité" /> - 
                                  <input type="text" required="required"  name="mode[]" style="width: 100px" class="pharmaciemodetypeahead"  autocomplete="off" value="{{ dossier_prescription['mode'] }}" placeholder="Mode" /> -
                                  <input type="text" required="required"  style="width: 70px" class="pharmacieposologietypeahead" name="posologie[]" autocomplete="off" value="{{ dossier_prescription['posologie'] }}" placeholder="Posologie" /> - 
                                  <input type="text" style="width: 70px" class="pharmaciedureetypeahead" name="duree[]" autocomplete="off" value="{{ dossier_prescription['duree'] }}" placeholder="Durée" />
                                </span>
                                <div class="tools">
                                  <i class="fa fa-trash-o suppElement"></i>
                                </div>
                              </li>
                            {% endfor %}
                            </ul>

                            <!-- --------  -->
                              
                              {% for index, elem in formulaireElements %}

                                {% if elem['place_after_c'] == 'prescription' %}
                                
                                  {% include "consultation/tpl_elem_c.volt" %}

                                {% endif %}

                              {% endfor %}

                              <!-- --------  -->

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
                            <h3 class="box-title"><b>Commentaires</b></h3>
                          </div>
                          <div class="box-body">
                            {{ textArea(['commentaire', 'class': 'textarea', 'placeholder' : 'commentaire', 'style' : 'width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;' ]) }}

                            <!-- --------  -->
                              
                              {% for index, elem in formulaireElements %}

                                {% if elem['place_after_c'] == 'commentaire' %}
                                
                                  {% include "consultation/tpl_elem_c.volt" %}

                                {% endif %}

                              {% endfor %}

                            <!-- --------  -->

                          </div>
                      </div>
                  </div>

                  <div class="col-xs-6" >
                    <div class="box box-default" style="width: 100%;padding: 0;margin: 0;">
                      <div class="box-header with-border">
                        <h3 class="box-title">  <b>Résumé</b> </h3>
                      </div>
                      <div class="box-body">
                        {{ textArea(['resume', 'class': 'textarea_resumer', 'placeholder' : 'resume', 'style' : 'width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;' ]) }}

                        <!-- --------  -->
                              
                          {% for index, elem in formulaireElements %}

                            {% if elem['place_after_c'] == 'resume' %}
                            
                              {% include "consultation/tpl_elem_c.volt" %}

                            {% endif %}

                          {% endfor %}

                        <!-- --------  -->

                      </div>
                    </div>
                  </div>

                </div>

          {% endif %}

            </div>

            <div class="modal-footer">
                <div>{{ hiddenField(['patient_id', 'value' : patients_id]) }}</div>
                <div>{{ hiddenField(['formType_id', 'value' : formType_id]) }}</div>
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="{{ trans['Close'] }}">{{ trans['Fermer'] }}</button>
                <input type="submit" value="{{trans['Enregistrer']}}" class="btn btn-success pull-right" title="{{trans['Enregistrer']}}">
                {% if form_action == 'edit' %}
                <!-- <input type="submit" value="{{trans['Sauvegarder et clôturer']}}" class="btn btn-warning pull-right" title="{{trans['']}}"> -->
                {% endif %}
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

    $(".motifs").select2({
        width: width,
        allowClear: true,
        theme: "classic"
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

    $(".textarea_resumer").wysihtml5({
        toolbar: {
          "font-styles": true, // Font styling, e.g. h1, h2, etc.
          "emphasis": true, // Italics, bold, etc.
          "lists": true, // (Un)ordered lists, e.g. Bullets, Numbers.
          "html": false, // Button which allows you to edit the generated HTML.
          "link": false, // Button to insert a link.
          "image": false, // Button to insert an image.
          "color": true, // Button to change color of font
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
      $('input.pharmacietypeahead').typeahead({
          ajax: {
              url: '{{url("produit/ajaxProduit")}}',
              method: 'get',
          },
          displayField: 'libelle',
          scrollBar:true,
          onSelect: displayResult
      });
    
      $('input.diagnostiquetypeahead').typeahead({
          ajax: {
              url: '{{url("consultation/" ~ structureConfig.diagnostic_source)}}',
              method: 'get',
          },
          displayField: 'libelle',
          scrollBar:true
          //onSelect: displayResult
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
