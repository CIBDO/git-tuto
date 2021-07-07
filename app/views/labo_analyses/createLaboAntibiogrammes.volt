<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {% if form_action == 'edit' %}
                {{ trans['Editer un élément'] }}
            {% else %}
                {{ trans['Ajouter un élément'] }}
            {% endif %}
            </h4>
        </div>
        
        <div class="modal-body">
            <div class="error_modal_container"></div>

            <div class="form-group">
                <label for="libelle" class="control-label">{{ trans['Libellé'] }}</label>
                {{ textField(['_libelle', 'class': 'form-control _libelle', 'id' : '_libelle', 'required' : 'required']) }}
            </div>

            <div class="form-group">
                <label for="code" class="control-label">{{ trans['Code'] }}</label>
                {{ textField(['_code', 'class': 'form-control', 'id' : '_code', 'required' : 'required']) }}
            </div>

            <div class="form-group">
                <label for="type_valeur" class="control-label">{{ trans['Type de valeur'] }}</label>
                {{ select(['_type_valeur',  ['n' : 'Numérique', 'a' : 'Alpha numérique', 'm' : 'liste de choix'], 'useEmpty' : true, 'emptyText' : "Choisir", 'class': 'form-control', 'id' : '_type_valeur']) }}
            </div>

            <div class="form-group">
                <label for="valeur_possible" class="control-label">{{ trans['Valeurs possibles'] }}</label>
                <i>(Séparer par des virgules)</i>
                {{ textField(['_valeur_possible', 'class': 'form-control', 'id' : '_valeur_possible']) }}
            </div>

            <div class="form-group">
                <label for="unite" class="control-label">{{ trans['Unités de mesure possibles'] }}</label>
                <i>(Séparer par des virgules)</i>
                {{ textField(['_unite', 'class': 'form-control', 'id' : '_unite']) }}
            </div>

            <div class="form-group">
                <label for="norme" class="control-label">{{ trans['Normes'] }}</label>
                {{ textField(['_norme', 'class': 'form-control', 'id' : '_norme']) }}
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="{{ trans['Close'] }}">{{ trans['Close'] }}</button>
            <button type="button" class="btn btn-success pull-right addBtn" title="{{trans['Ajouter']}}">{{ trans['Ajouter'] }}</button>
        </div>

    </div>
</div>