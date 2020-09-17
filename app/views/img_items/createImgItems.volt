<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {% if form_action == 'edit' %}
                {{ trans["Editer un acte d'imagerie"] }}
            {% else %}
                {{ trans["Créer un acte d'imagerie"] }}
            {% endif %}
            </h4>
        </div>
        {% if form_action == 'edit' %}
        <form action="{{ url('img_items/' ~ form_action ~ 'ImgItems/' ~ imgItems_id) }}" class="form ajaxForm" method="post">
        {% else %}
        <form action="{{ url('img_items/' ~ form_action ~ 'ImgItems/') }}" class="form ajaxForm" method="post">
        {% endif %}
            <div class="modal-body">
                <div class="error_modal_container"></div>

                <div class="form-group">
                    <label for="code" class="control-label">{{ trans['Code'] }}</label>
                    {{ form.render('code') }}
                </div>

                <div class="form-group">
                    <label for="libelle" class="control-label">{{ trans['Libellé'] }}</label>
                    {{ form.render('libelle') }}
                </div>

                <div class="form-group">
                    <label for="img_items_categories_id" class="control-label">{{ trans['Categorie'] }}</label>
                    {{ form.render('img_items_categories_id') }}
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
    $("#img_items_categories_id").select2({width: width, allowClear: true, placeholder: "choisir", theme: "classic"});
</script>
