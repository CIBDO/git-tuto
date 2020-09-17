<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {% if form_action == 'edit' %}
                {{ trans['Modifier une banque'] }}
            {% else %}
                {{ trans['Créer une banque'] }}
            {% endif %}
            </h4>
        </div>
        {% if form_action == 'edit' %}
        <form action="{{ url('f_banque/' ~ form_action ~ 'FBanque/' ~ fbanque_id) }}" class="form ajaxForm" method="post">
        {% else %}
        <form action="{{ url('f_banque/' ~ form_action ~ 'FBanque/') }}" class="form ajaxForm" method="post">
        {% endif %}
            <div class="modal-body">
                <div class="error_modal_container"></div>
                <div class="form-group">
                    <label for="name" class="control-label">{{ trans['Libellé'] }}</label>
                    {{ form.render('libelle') }}
                </div>

                <div class="form-group">
                    <label for="comptes" class="control-label">{{ trans['Comptes (séparer par des virgules)'] }}</label>
                    {{ form.render('comptes') }}
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
</script>
