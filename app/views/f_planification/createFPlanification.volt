<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                {{ trans['Formulaire de planification'] }} - 
                {% if form_action == 'edit' %}
                    {{ trans['Modification'] }}
                {% else %}
                    {{ trans['Création'] }}
                {% endif %}
            </h4>
        </div>
        {% if form_action == 'edit' %}
        <form action="{{ url('f_planification/' ~ form_action ~ 'FPlanification/' ~ fplanification_id) }}" class="form ajaxForm" method="post">
        {% else %}
        <form action="{{ url('f_planification/' ~ form_action ~ 'FPlanification/') }}" class="form ajaxForm" method="post">
        {% endif %}
            <div class="modal-body">
                <div class="error_modal_container"></div>

                <div class="form-group">
                    <label for="_annee" class="control-label">{{ trans['Année'] }}</label>
                    {{ select(['annee',  annee, 'class': 'form-control', 'id' : '_annee', 'useEmpty' : true]) }}
                </div>

                <div class="form-group">
                    <label for="type_prevision" class="control-label">{{ trans['Type de prévision'] }}</label>
                    {{ form.render('type_prevision') }}
                </div>

                <div class="form-group">
                    <label for="f_sous_compte_id" class="control-label">{{ trans['Compte/Sous Compte'] }}</label>
                    {{ select(['f_sous_compte_id', f_sous_compte_id, 'class': 'form-control', 'using' : ['id', 'libelle'], 'useEmpty' : true, 'id' : 'f_sous_compte_id']) }}
                </div>

                <div class="form-group">
                    <label for="quantite" class="control-label">{{ trans['Quantité'] }}</label>
                    {{ form.render('quantite') }}
                </div>

                <div class="form-group">
                    <label for="prix_unitaire" class="control-label">{{ trans['Prix unitaire'] }}</label>
                    {{ form.render('prix_unitaire') }}
                </div>

                <div class="form-group">
                    <label for="montant" class="control-label">{{ trans['Montant'] }}</label>
                    {{ form.render('montant') }}
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="{{ trans['Close'] }}">{{ trans['Close'] }}</button>
                <input type="submit" value="{{trans['Save']}}" class="btn btn-success pull-right" title="{{trans['Save']}}">
            </div>
        </form>
    </div>
</div>

<script>
    var width = "100%"; //Width for the select inputs
    $("#_annee").select2({width: width, placeholder: "choisir", theme: "classic"});
    $("#type_prevision").select2({width: width, placeholder: "choisir", theme: "classic"});
    $("#f_sous_compte_id").select2({width: width, placeholder: "choisir", theme: "classic"});

    function calculer() {
        var rs= Math.ceil(parseFloat($("#quantite").val(), 10) * parseFloat($("#prix_unitaire").val(), 10));
        $("#montant").val(rs);
    }

    $('#quantite, #prix_unitaire').change(function () {
        calculer();
    });
    
</script>
