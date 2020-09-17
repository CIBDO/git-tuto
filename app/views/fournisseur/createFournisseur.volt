<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {% if form_action == 'edit' %}
                {{ trans['Modifier un fournisseur'] }}
            {% else %}
                {{ trans['Créer un fournisseur'] }}
            {% endif %}
            </h4>
        </div>
        {% if form_action == 'edit' %}
        <form action="{{ url('fournisseur/' ~ form_action ~ 'Fournisseur/' ~ fournisseur_id) }}" class="form ajaxForm" method="post">
        {% else %}
        <form action="{{ url('fournisseur/' ~ form_action ~ 'Fournisseur/') }}" class="form ajaxForm" method="post">
        {% endif %}
            <div class="modal-body">
                <div class="error_modal_container"></div>
                <div class="form-group">
                    <label for="name" class="control-label">{{ trans['Libellé'] }}</label>
                    {{ form.render('libelle') }}
                </div>

                <div class="form-group">
                    <label for="telephone" class="control-label">{{ trans['Téléphone'] }}</label>
                    {{ form.render('telephone') }}
                </div>

                <div class="form-group">
                    <label for="adresse" class="control-label">{{ trans['Adresse'] }}</label>
                    {{ form.render('adresse') }}
                </div>

                <div class="form-group">
                    <label for="email" class="control-label">{{ trans['Email'] }}</label>
                    {{ form.render('email') }}
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="{{ trans['Fermer'] }}">{{ trans['Fermer'] }}</button>
                <input type="submit" value="{{trans['Enregistrer']}}" class="btn btn-success pull-right" title="{{trans['Enregistrer']}}">
            </div>
        </form>
    </div>
</div>

<script>
    var width = "100%"; //Width for the select inputs
</script>
