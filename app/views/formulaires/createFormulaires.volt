<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {% if form_action == 'edit' %}
                {{ trans['Editer un formulaire'] }}
            {% else %}
                {{ trans['Créer un formulaire'] }}
            {% endif %}
            </h4>
        </div>
        {% if form_action == 'edit' %}
        <form action="{{ url('formulaires/' ~ form_action ~ 'Formulaires/' ~ forms_id) }}" class="form ajaxForm" method="post">
        {% else %}
        <form action="{{ url('formulaires/' ~ form_action ~ 'Formulaires/') }}" class="form ajaxForm" method="post">
        {% endif %}
            <div class="modal-body">
                <div class="error_modal_container"></div>
                <div class="form-group">
                    <label for="name" class="control-label">{{ trans['Libellé'] }}</label>
                    {{ form.render('libelle') }}
                </div>

                <div class="form-group">
                    <label for="type" class="control-label">{{ trans['Type'] }}</label>
                    {{ form.render('type') }}
                </div>

                <div class="form-group">
                    {{ form.render('hide_default') }}
                </div>

                <div class="form-group">
                    <label for="forms">{{ trans['Onglets associés']}} <i>(Pour les formulaires de type "base")</i>:</label>
                    {{ selectStatic(['forms[]', forms, 'using' : ['id', 'nom'], 'multiple': 'multiple', 'class': 'form-control', 'id': 'forms', 'class': 'forms', 'useEmpty': true, 'emptyText' : '']) }}
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
    $(".forms").select2({
        width: width,
        allowClear: true,
        theme: "classic"
    });
</script>
