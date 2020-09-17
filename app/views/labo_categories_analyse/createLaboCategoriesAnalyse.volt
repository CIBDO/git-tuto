<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {% if form_action == 'edit' %}
                {{ trans["Modifier une catégorie d'analyse"] }}
            {% else %}
                {{ trans["Créer une catégorie d'analyse"] }}
            {% endif %}
            </h4>
        </div>
        {% if form_action == 'edit' %}
        <form action="{{ url('labo_categories_analyse/' ~ form_action ~ 'LaboCategoriesAnalyse/' ~ laboCategoriesAnalyse_id) }}" class="form ajaxForm" method="post">
        {% else %}
        <form action="{{ url('labo_categories_analyse/' ~ form_action ~ 'LaboCategoriesAnalyse/') }}" class="form ajaxForm" method="post">
        {% endif %}
            <div class="modal-body">
                <div class="error_modal_container"></div>
                <div class="form-group">
                    <label for="name" class="control-label">{{ trans['Libellé'] }}</label>
                    {{ form.render('libelle') }}
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
    //$("#merchants_id").select2({width: width});
</script>
