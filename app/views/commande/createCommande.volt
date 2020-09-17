<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {% if form_action == 'edit' %}
                {{ trans['Modifier une commande'] }}
            {% else %}
                {{ trans['Créer une commande'] }}
            {% endif %}
            </h4>
        </div>
        {% if form_action == 'edit' %}
        <form action="{{ url('commande/' ~ form_action ~ 'Commande/' ~ commande_id) }}" class="form ajaxForm" method="post">
        {% else %}
        <form action="{{ url('commande/' ~ form_action ~ 'Commande/') }}" class="form" method="post">
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
                    <label for="fournisseur_id" class="control-label">{{ trans['Fournisseur'] }}</label>
                    {{ form.render('fournisseur_id') }}
                </div>

                <div class="form-group">
                    <label for="montant" class="control-label">{{ trans['Montant'] }}</label>
                    {{ form.render('montant') }}
                </div>

                {% if produit_list is defined %}
                    <input type="hidden"  name="produit_list" value="{{ produit_list }}" /> 
                {% endif %}
                
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
    $("#fournisseur_id").select2({width: width, placeholder: "choisir", theme: "classic"});
</script>
