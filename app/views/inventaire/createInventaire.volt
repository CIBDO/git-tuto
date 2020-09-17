<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {% if form_action == 'edit' %}
                {{ trans['Editer un inventaire'] }}
            {% else %}
                {{ trans['Créer un inventaire'] }}
            {% endif %}
            </h4>
        </div>
        {% if form_action == 'edit' %}
        <form action="{{ url('inventaire/' ~ form_action ~ 'Inventaire/' ~ inventaire_id) }}" class="form ajaxForm" method="post">
        {% else %}
        <form action="{{ url('inventaire/' ~ form_action ~ 'Inventaire/') }}" class="form" method="post">
        {% endif %}
            <div class="modal-body">
                <div class="error_modal_container"></div>
                <div class="form-group">
                    <label for="objet" class="control-label">{{ trans['Objet'] }}</label>
                    {{ form.render('objet') }}
                </div>

                <div class="form-group">
                    <label for="date" class="control-label">{{ trans['Date'] }}</label>
                    {{ form.render('date') }}
                </div>

                <div class="form-group">
                    <label for="date" class="control-label">{{ trans['Debut'] }}</label>
                    {{ form.render('debut') }}
                </div>

                <div class="form-group">
                    <label for="date" class="control-label">{{ trans['Fin'] }}</label>
                    {{ form.render('fin') }}
                </div>
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="{{ trans['Close'] }}">{{ trans['Close'] }}</button>
                <input type="submit" value="{{trans['Save']}}" class="btn btn-success pull-right" title="{{trans['Save']}}">
            </div>
        </form>
    </div>
</div>
