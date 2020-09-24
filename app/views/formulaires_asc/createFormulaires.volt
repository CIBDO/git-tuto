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
        <form action="{{ url('formulaires_asc/' ~ form_action ~ 'Formulaires/' ~ forms_id) }}" class="form " method="post" enctype="multipart/form-data">
        {% else %}
        <form action="{{ url('formulaires_asc/' ~ form_action ~ 'Formulaires/') }}" class="form " method="post" enctype="multipart/form-data">
        {% endif %}
            <div class="modal-body">
                <div class="error_modal_container"></div>
                <div class="form-group">
                    <input type="file" name="file" id="files">
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
