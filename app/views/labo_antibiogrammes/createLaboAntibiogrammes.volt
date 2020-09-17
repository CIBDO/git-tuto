<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {% if form_action == 'edit' %}
                {{ trans['Editer un antibiogramme'] }}
            {% else %}
                {{ trans['Créer un antibiogramme'] }}
            {% endif %}
            </h4>
        </div>
        {% if form_action == 'edit' %}
        <form action="{{ url('labo_antibiogrammes/' ~ form_action ~ 'LaboAntibiogrammes/' ~ laboAntibiogrammes_id) }}" class="form ajaxForm" method="post">
        {% else %}
        <form action="{{ url('labo_antibiogrammes/' ~ form_action ~ 'LaboAntibiogrammes/') }}" class="form ajaxForm" method="post">
        {% endif %}
            <div class="modal-body">
                <div class="error_modal_container"></div>

                <div class="form-group">
                    <label for="libelle" class="control-label">{{ trans['Libellé'] }}</label>
                    {{ form.render('libelle') }}
                </div>

                <div class="form-group">
                    <label for="labo_antibiogrammes_type_id" class="control-label">{{ trans['Type'] }}</label>
                    {{ form.render('labo_antibiogrammes_type_id') }}
                </div>

                <div class="form-group">
                    <label for="laboAntibiotiques">{{ trans['Antibiotiques associés']}} :</label>
                    {{ selectStatic(['laboAntibiotiques[]', laboAntibiotiques, 'using' : ['id', 'libelle'], 'multiple': 'multiple', 'class': 'form-control', 'id': 'laboAntibiotiques', 'class': 'laboAntibiotiques', 'useEmpty': true, 'emptyText' : '']) }}
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
    $("#labo_antibiogrammes_type_id").select2({width: width, allowClear: true, placeholder: "choisir", theme: "classic"});
    $(".laboAntibiotiques").select2({
        width: width,
        allowClear: true,
        theme: "classic"
    });
</script>
