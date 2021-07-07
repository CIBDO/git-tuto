<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                {{ trans['Imagerie médicale'] }} - {{ trans['Création'] }}
            </h4>
        </div>
        <form action="{{ url('img_demandes/createDemande') }}" class="form ajaxForm" method="post">
        <div class="modal-body">
            <div class="error_modal_container"></div>
            <div class="row">
                <div class="col-md-12">
                    
                    <div class="form-group">
                        <label for="f_sous_compte_id" class="control-label">{{ trans["Veuillez selectionner l'acte"] }}</label>
                        {{ select(['imgItems_id', imgItems_id, 'class': 'form-control', 'useEmpty' : "true", "emptyText" : "Choisir...", 'required' : 'required', 'id' : 'analyse_id']) }}
                    </div>

                    {{ hiddenField(['patients_id', 'value' : patients_id]) }}

                </div>
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
    $("#imgItems_id").select2({width: width, theme: "classic"});

</script>