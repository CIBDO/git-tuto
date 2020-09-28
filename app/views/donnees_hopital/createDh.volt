<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {% if form_action == 'edit' %}
                {{ trans['Modifier un RDV ASC'] }}
            {% else %}
                {{ trans['Créer un RDV ASC'] }}
            {% endif %}
            </h4>
        </div>
        {% if form_action == 'edit' %}
        <form action="{{ url('donnees_hopital/' ~ form_action ~ 'Dh/' ~ id) }}" class="form ajaxForm" method="post">
        {% else %}
        <form action="{{ url('donnees_hopital/' ~ form_action ~ 'Dh/'~ patient_id) }}" class="form ajaxForm" method="post">
        {% endif %}
            <div class="modal-body">
                <div class="error_modal_container"></div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="seuil_min" class="control-label">{{ trans['Date RDV ASC'] }}</label>
                            {{ form.render('date_rdv') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="commentaire" class="control-label">{{ trans['Commentaire'] }}</label>
                            {{ form.render('commentaire') }}
                        </div>

                    </div>
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
    $(".type_produit_id").select2({width: width, allowClear: true, placeholder: "choisir", theme: "classic"});
    $(".forme_produit_id").select2({width: width, allowClear: true, placeholder: "choisir", theme: "classic"});
    $(".classe_therapeutique_id").select2({width: width, allowClear: true, placeholder: "choisir", theme: "classic"});
</script>
