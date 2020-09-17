<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">
                {{ analyse_nom }} - Antibiogramme
            </h4>
        </div>
        <form action="{{ url('labo_demandes/editAntibiogramme/' ~ dossier_id ~ '/'~ analyse_id ~ '/'~ analyse_nom ~ '/') }}" class="form formAntibiogramme" method="post">
            <div class="modal-body">
                <div class="error_modal_container"></div>
                <div class="form-group">
                    <label for="date" class="control-label">{{ trans['Germe'] }}</label>
                    {{ textField(['germe', 'id' : 'germe', 'class': 'form-control', 'required' : 'required']) }}
                </div>

                <div class="form-group">
                    <label for="f_sous_compte_id" class="control-label">{{ trans['Antibiogramme'] }}</label>
                    {{ select('antibiogramme', antibiogramme, 'class': 'form-control antibiogrammeChoser','useEmpty' : 'true', 'required' : 'required', 'id' : 'antibiogramme') }}
                </div>

                <div id="detailsDiv"></div><br />

                <div class="form-group">
                    <label for="date" class="control-label">{{ trans['Conclusion'] }}</label>
                    {{ textField(['conclusion', 'id' : 'conclusion', 'class': 'form-control', 'required' : 'required']) }}
                </div>

            </div>

            <div class="modal-footer">
                <input type="hidden" name="_analyse_id"  id="_analyse_id" value="{{analyse_id}}">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="{{ trans['Close'] }}">{{ trans['Fermer'] }}</button>
                <input type="submit" value="{{trans['Ajouter']}}" class="btn btn-success pull-right saveAntibiogramme" title="{{trans['Ajouter']}}">
            </div>
        </form>
    </div>
</div>

<script>

    var width = "100%"; //Width for the select inputs
    $("#antibiogramme").select2({width: width, placeholder: "choisir", theme: "classic"});
    $("#current_analyse").val("{{analyse_nom}}");

</script>
