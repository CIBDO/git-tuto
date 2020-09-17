<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {% if form_action == 'edit' %}
                {{ trans['Modifier un compte'] }}
            {% else %}
                {{ trans['Créer un compte'] }}
            {% endif %}
            </h4>
        </div>
        {% if form_action == 'edit' %}
        <form action="{{ url('f_compte/' ~ form_action ~ 'FCompte/' ~ fcompte_id) }}" class="form ajaxForm" method="post">
        {% else %}
        <form action="{{ url('f_compte/' ~ form_action ~ 'FCompte/') }}" class="form ajaxForm" method="post">
        {% endif %}
            <div class="modal-body">
                <div class="error_modal_container"></div>
                <div class="form-group">
                    <label for="numero" class="control-label">{{ trans['Numéro'] }}</label>
                    {{ form.render('numero') }}
                </div>

                <div class="form-group">
                    <label for="libelle" class="control-label">{{ trans['Libellé'] }}</label>
                    {{ form.render('libelle') }}
                </div>

                <div class="form-group">
                    <label for="type" class="control-label">{{ trans['Type'] }}</label>
                    {{ form.render('type') }}
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="{{ trans['Fermer'] }}">{{ trans['Fermer'] }}</button>
                <input type="submit" value="{{trans['Enregister']}}" class="btn btn-success pull-right" title="{{trans['Enregister']}}">
            </div>
        </form>
    </div>
</div>

<script>
    var width = "100%"; //Width for the select inputs
    $("#type").select2({width: width, allowClear: true, placeholder: "choisir", theme: "classic"});
</script>
