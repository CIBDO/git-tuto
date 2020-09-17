<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                {{ trans['Validation de la reception'] }}
            </h4>
        </div>
        <form action="{{ url('reception/cloture') }}" class="form" method="post">
            <div class="modal-body">
                <div class="error_modal_container"></div>
                <div class="form-group">
                    <label for="objet" class="control-label">Point de reception</label>
                    {{ select('point_distribution_id', pointDistribution, 'class': 'form-control', 'using' : ['id', 'libelle'], 'useEmpty' : true, 'id' : 'point_distribution_id', 'required' : 'required') }}
                    {{ hiddenField(['reception_id']) }}
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="{{ trans['Close'] }}">{{ trans['Close'] }}</button>
                <input type="submit" value="{{trans['Valider']}}" class="btn btn-warning pull-right" title="{{trans['Valider']}}">
            </div>
        </form>
    </div>
</div>

<script>
    var width = "100%"; //Width for the select inputs
    var select2fournisseur = $("#point_distribution_id").select2({
            width: width,
            placeholder: 'Veuillez choisir le point de reception',
            allowClear: true,
            //disabled: {% if commande_id is defined %}true{% else %}}false{% endif %},
            theme: "classic"
        });

</script>
