<div class="box box-primary editresultitem">
  <div class="box-body" >

    <form action="{{ url('labo_demandes/editDossier2/' ~ patients_id ~ '/' ~ dossier_id) }}" id="f_result" class="form" method="post">

        <div class="row">
            <div class="col-xs-6" >
                <div class="box" style="width: 100%;padding: 0;margin: 0;">
                    <div class="box-body" >
                      <div class="form-group">
                          <label for="provenance" class="control-label">{{ trans['Provenance'] }}</label>
                          {{ textField(['provenance', 'class': 'form-control provenancetypeahead', 'required' : 'required', 'id' : 'provenance', "autocomplete" : "off"]) }}
                      </div>
                      <div class="form-group">
                          <label for="prescripteur" class="control-label">{{ trans['Prescripteur'] }}</label>
                          {{ textField(['prescripteur', 'class': 'form-control prescripteurtypeahead', 'required' : 'required', 'id' : 'prescripteur', "autocomplete" : "off"]) }}
                      </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-6" >
                <div class="box" style="width: 100%;padding: 0;margin: 0;">
                    
                    <div class="box-body">
                      <div class="form-group">
                          <label for="provenance" class="control-label">{{ trans['Informations cliniques'] }}</label>
                          {{ textArea(['histoire', 'class': '', 'placeholder' : 'histoire de la maladie', 'required' : 'required', 'style' : 'width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;' ]) }}
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
                    <div class="box-header with-border">
                      <h3 class="box-title">  <b>Résultat</b> </h3>
                      {% if laboDemande.etat != 'clotûré' %}
                      <div class="box-tools pull-right">
                          <button type="button" class="btn btn-warning clotureBtn" data-demandeid="{{laboDemande.id}}" ><i class="fa fa-check"></i> Tout valider</button>
                      </div>
                      {%endif%}
                    </div>
                    <div class="box-body">
                        <ul class="todo-list ui-sortable">
                        {% set counter = 0 %}
                          {% for index, item in detailsDemande %}
                          <li>
                          {% if (userId == 1) OR in_array("labo_a", userPermissions) %}
                            {% if item['r_etat'] == '1' %}
                              <button type="button" class="btn btn-success" data-id="{{ item['id'] }}" data-demandeid="{{ dossier_id }}"><i class="fa fa-check"></i></button>
                            {%else%}
                              <button class="btn btn-warning validItem" data-id="{{ item['id'] }}" data-demandeid="{{ dossier_id }}"><i class="fa fa-check"></i></button>
                            {%endif%}
                          {%endif%}
                            &nbsp;
                            <span class="text" style="width:300px">{{ item['libelle'] }}</span>
                           
                            <input type="hidden" name="id_{{ counter }}" value="{{ item['id'] }}">
                            <input type="hidden" name="r_id_{{ counter }}" value="{{ item['r_id'] }}">
                            {% if item['children'] is defined %}
                              <div style="margin-left:10px;border:1px solid #ccc">

                                {% for key, child in item['children'] %}
                                  {% set counter = counter + 1 %}
                                  <div style="padding:5px;border-bottom:1px solid #ccc">
                                    <span class="text" style="width:300px">{{ child['libelle'] }}</span>:
                                    <input type="hidden" name="id_{{ counter }}" value="{{ child['id'] }}">
                                    <input type="hidden" name="r_id_{{ counter }}" value="{{ child['r_id'] }}">
                                    {% if child['type_valeur'] == "n" %}
                                      <input type="number" placeholder="Valeur" name="value_{{ counter }}" value="{{ child['r_valeur'] }}" style="width:120px">
                                    {% elseif child['type_valeur'] == "a" %}
                                      <input type="text" placeholder="Valeur" name="value_{{ counter }}" value="{{ child['r_valeur'] }}" style="width:120px">
                                    {% elseif child['type_valeur'] == "m" %}

                                      {% if count(child['valeur_possible']) > 0 %}
                                        <select style="width:120px" name="value_{{ counter }}">
                                            <option value="">Choisir la valeur</option>
                                          {% for k, val in child['valeur_possible'] %}
                                            <option value="{{val}}" {% if child['r_valeur'] == val %} selected="selected"{% endif %}>{{val}}</option>
                                          {% endfor %}
                                        </select>
                                      {% endif %}
                                    {% endif %}

                                    {% if count(child['unite']) > 0 %}
                                      <select style="width:120px" name="unite_{{ counter }}">
                                       <!--  <option value="">Choisir l'unité</option> -->
                                        {% for k, val in child['unite'] %}
                                          <option value="{{val}}" {% if child['r_unite'] == val %} selected="selected"{% endif %}>{{val}}</option>
                                        {% endfor %}
                                      </select>
                                    {% endif %}

                                  </div> 

                                {% endfor %}

                              </div>
                            {% else %}
                            <!-- PAS D'ENFANTS -->
                              {% if item['type_valeur'] == "n" %}
                                <input type="number" placeholder="Valeur" name="value_{{ counter }}" value="{{ item['r_valeur'] }}" style="width:120px">
                              {% elseif item['type_valeur'] == "a" %}
                                <input type="text"  placeholder="Valeur"  name="value_{{ counter }}" value="{{ item['r_valeur'] }}" style="width:120px">
                              {% elseif item['type_valeur'] == "m" %}

                                {% if count(item['valeur_possible']) > 0 %}
                                  <select style="width:120px" name="value_{{ counter }}">
                                      <option value="">Choisir la valeur</option>
                                    {% for k, val in item['valeur_possible'] %}
                                      <option value="{{val}}" {% if item['r_valeur'] == val %} selected="selected"{% endif %}>{{val}}</option>
                                    {% endfor %}
                                  </select>
                                {% endif %}
                              {% endif %}

                              {% if count(item['unite']) > 0 %}
                                <select style="width:120px" name="unite_{{ counter }}">
                                  <!-- <option value="">Choisir l'unité</option> -->
                                  {% for k, val in item['unite'] %}
                                    <option value="{{val}}" {% if item['r_unite'] == val %} selected="selected"{% endif %}>{{val}}</option>
                                  {% endfor %}
                                </select>
                              {% endif %}
                            {% endif %}

                            {% if item['has_antibiogramme'] == 1 %}
                              <div class="box box-default">
                                <div class="box-header with-border">
                                  <h3 class="box-title">Antibiogramme</h3>
                                  <div class="box-tools pull-right">
                                    <a class="btn btn-info btn-xs antibiogrammeModal" data-toggle="modal" data-target="#antibiogrammeModal" href="#"  data-demandeid="{{ dossier_id }}" data-analyseid="{{ item['id'] }}" data-analysenom="{{ item['libelle'] }}">{{trans["Ajouter"]}}</a>
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                  </div><!-- /.box-tools -->
                                </div><!-- /.box-header -->

                                <div class="box-body" id="antibigrammes_{{ item['libelle'] | trim }}">

                                  <div class="antibio_to_destroy">
                                  {% for i, antibio in item['r_antibiogrammes'] %}
                                    {% if antibio != "" %}
                                      <input type="hidden" data-analyselibelle="{{ item['libelle'] | trim }}" class="antibio_hidden" name='antibiogrammes_{{item["id"]}}[]' value='{{antibio}}' />
                                    {% endif %}
                                  {% endfor %}
                                  </div>

                                </div><!-- /.box-body -->
                              </div>
                            {% endif %}

                          </li>

                            {% set counter = counter + 1 %}
                          {% endfor %}
                        </ul>

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