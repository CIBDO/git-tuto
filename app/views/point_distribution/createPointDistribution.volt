<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {% if form_action == 'edit' %}
                {{ trans['Modifier un  point de distribution'] }}
            {% else %}
                {{ trans['Créer un  point de distribution'] }}
            {% endif %}
            </h4>
        </div>
        {% if form_action == 'edit' %}
        <form action="{{ url('point_distribution/' ~ form_action ~ 'PointDistribution/' ~ pointDistribution_id) }}" class="form ajaxForm" method="post">
        {% else %}
        <form action="{{ url('point_distribution/' ~ form_action ~ 'PointDistribution/') }}" class="form ajaxForm" method="post">
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
                    <label for="default" class="control-label">{{ trans['Point de réception des produits'] }}</label>
                    {{ checkField(["default", "value" : "Y"]) }}
                </div>

                <div class="form-group">
                    <label for="users">{{ trans['Caissiers associés']}} :</label>
                    {{ selectStatic(['users[]', users, 'using' : ['id', 'nom'], 'multiple': 'multiple', 'class': 'form-control', 'id': 'users', 'class': 'users', 'useEmpty': true, 'emptyText' : '']) }}
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
    $("#type").select2({width: width, placeholder: "choisir", theme: "classic"});
    $(".users").select2({
        width: width,
        allowClear: true,
        theme: "classic"
    });

</script>
