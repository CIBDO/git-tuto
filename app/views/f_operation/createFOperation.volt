<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                {{ trans['Opération financière'] }} - 
                {% if form_action == 'edit' %}
                    {{ trans['Visualisation'] }}
                {% else %}
                    {{ trans['Création'] }}
                {% endif %}
            </h4>
        </div>
        {% if form_action == 'edit' %}
        <form action="{{ url('f_operation/' ~ form_action ~ 'FOperation/' ~ foperation_id) }}" class="form ajaxForm" method="post">
        {% else %}
        <form action="{{ url('f_operation/' ~ form_action ~ 'FOperation/') }}" class="form ajaxForm" method="post">
        {% endif %}
        <fieldset {% if form_action == 'edit' %} disabled="disabled"{% endif %}>
            <div class="modal-body">
                <div class="error_modal_container"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="box box-default">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="date" class="control-label">{{ trans['Date'] }}</label>
                                    {{ form.render('date') }}
                                </div>

                                <div class="form-group">
                                    <label for="f_sous_compte_id" class="control-label">{{ trans['Compte/Sous Compte'] }}</label>
                                    {{ select('f_sous_compte_id', f_sous_compte_id, 'class': 'form-control','useEmpty' : 'true', 'required' : 'required', 'id' : 'f_sous_compte_id') }}
                                </div>

                                <div class="form-group">
                                    <label for="montant" class="control-label">{{ trans['Montant'] }}</label>
                                    {{ form.render('montant') }}
                                </div>
                            
                            {% if form_action != 'edit' %}
                                <div class="form-group">
                                    <label for="type_prevision" class="control-label">{{ trans["Nature de l'opération"] }}</label>
                                    {{ select('type',  ["Espece" : "Espece (Solde: " ~ soldeEspece ~ ")", "Banque" : "Banque (Solde: " ~ soldeBanque ~ ")"], 'class': 'form-control', 'id' : 'type', 'required' : 'required', 'useEmpty' : 'true') }}
                                </div>
                            {% endif %}

                                <div class="form-group">
                                    <label for="details" class="control-label">{{ trans['Autres détails'] }}</label>
                                    {{ form.render('details') }}
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="banque_details">
                        <div class="box box-default">
                            <div class="box-body">

                                <div class="form-group">
                                    <label for="f_banque_compte_id" class="control-label">{{ trans['Banque/Compte'] }}</label>
                                    {{ select('f_banque_compte_id', f_banque_compte_id, 'class': 'form-control', 'using' : ['id', 'libelle'], 'useEmpty' : true, 'id' : 'f_banque_compte_id') }}
                                </div>

                                <div class="form-group">
                                    <label for="banque_cheque" class="control-label">{{ trans['Chèque'] }}</label>
                                    {{ form.render('banque_cheque') }}
                                </div>

                                <div class="form-group">
                                    <label for="banque_porteur" class="control-label">{{ trans['Porteur'] }}</label>
                                    {{ form.render('banque_porteur') }}
                                </div>

                                <div class="form-group">
                                    <label for="banque_details" class="control-label">{{ trans['Autres détails banque'] }}</label>
                                    {{ form.render('banque_details') }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                {% if form_action != 'edit' %}
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="{{ trans['Close'] }}">{{ trans['Close'] }}</button>
                <input type="submit" value="{{trans['Save']}}" class="btn btn-success pull-right" title="{{trans['Save']}}">
                {% endif %}
            </div>
        </fieldset>
        </form>
    </div>
</div>

<script>
    var width = "100%"; //Width for the select inputs
    $("#annee").select2({width: width, placeholder: "choisir", theme: "classic"});
    $("#f_sous_compte_id").select2({width: width, placeholder: "choisir", theme: "classic"});
    $("#f_banque_compte_id").select2({width: width, placeholder: "choisir", theme: "classic"});
    $("#type").select2({width: width, placeholder: "choisir", theme: "classic"});

    //Ce bout de code ne marche pas **********
    {% if form_action == 'edit' %}
        $('#f_sous_compte_id').select2('disable');
    {% endif %}

    $('#banque_details').hide();

    $('body').on('change', '#type', function () {
        var val = $(this).val();
        if(val == "Banque"){
            $('#banque_details').show();
            $('#f_banque_compte_id').prop('required', 'required');
            $('#banque_cheque').prop('required', 'required');
            $('#banque_porteur').prop('required', 'required');
        }
        else{
            $('#banque_details').hide();
            $('#f_banque_compte_id').removeAttr('required');
            $('#banque_cheque').removeAttr('required');
            $('#banque_porteur').removeAttr('required');
        }
    });

</script>
