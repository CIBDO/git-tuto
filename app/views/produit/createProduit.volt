<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {% if form_action == 'edit' %}
                {{ trans['Modifier un produit'] }}
            {% else %}
                {{ trans['Créer un produit'] }}
            {% endif %}
            </h4>
        </div>
        {% if form_action == 'edit' %}
        <form action="{{ url('produit/' ~ form_action ~ 'Produit/' ~ produit_id) }}" class="form ajaxForm" method="post">
        {% else %}
        <form action="{{ url('produit/' ~ form_action ~ 'Produit/') }}" class="form ajaxForm" method="post">
        {% endif %}
            <div class="modal-body">
                <div class="error_modal_container"></div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="libelle" class="control-label">{{ trans['Libellé'] }}</label>
                            {{ form.render('libelle') }}
                        </div>

                        <div class="form-group">
                            <label for="type_produit_id" class="control-label">{{ trans['Type de produit'] }}</label>
                            {{ form.render('type_produit_id') }}
                        </div>

                        <div class="form-group">
                            <label for="forme_produit_id" class="control-label">{{ trans['Forme de produit'] }}</label>
                            {{ form.render('forme_produit_id') }}
                        </div>

                        <div class="form-group">
                            <label for="classe_therapeutique_id" class="control-label">{{ trans['Classe thérapeutique'] }}</label>
                            {{ form.render('classe_therapeutique_id') }}
                        </div>

                        <div class="form-group">
                            <label for="unite_vente" class="control-label">{{ trans['Unité de dispensation'] }}</label>
                            {{ form.render('unite_vente') }}
                        </div>

                        <div class="form-group">
                            <label for="presentation" class="control-label">{{ trans['Présentation'] }}</label>
                            {{ form.render('presentation') }}
                        </div>

                        <div class="form-group">
                            <label for="dosage" class="control-label">{{ trans['Dosage'] }}</label>
                            {{ form.render('dosage') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="seuil_min" class="control-label">{{ trans['Seuil min'] }}</label>
                            {{ form.render('seuil_min') }}
                        </div>


                        <div class="form-group">
                            <label for="seuil_max" class="control-label">{{ trans['Seuil max'] }}</label>
                            {{ form.render('seuil_max') }}
                        </div>

                        <div class="form-group">
                            <label for="prix" class="control-label">{{ trans['Prix de vente'] }}</label>
                            {{ form.render('prix') }}
                        </div>

                        <div class="form-group">
                            <label for="stock" class="control-label">{{ trans['Stock'] }}</label>
                            {{ form.render('stock') }}
                        </div>

                        <div class="form-group">
                            <label for="etat" class="control-label">{{ trans['Etat'] }}</label>
                            {{ form.render('etat') }}
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
