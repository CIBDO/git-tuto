<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                {{ trans['Faire un ajustement'] }}
            </h4>
        </div>
        <form action="{{ url('ajustement/createAjustement/') }}" class="form" method="post">
            <div class="modal-body">
                <div class="error_modal_container"></div>
                <div class="form-group">
                    <label for="objet" class="control-label">Type</label>
                    {{ select('type', ["perte" : "perte", "ajout" : "ajout"], 'class': 'form-control', 'useEmpty' : true, 'id' : 'type', 'required' : 'required') }}
                </div>
                <div class="recherche">
                    <div class="row">
                        <div class="col-xs-7">
                            <input type="hidden"  name="s_idproduit" id="s_idproduit" value="" /> 
                            <input type="text"  name="s_produit" id="s_produit" class="form-control typeahead" autocomplete="off" value="" placeholder="Rechercher un produit" />
                        </div>
                        <div class="col-xs-3">
                            <input type="text"  name="s_lot" id="s_lot" class="form-control" size="35" autocomplete="off" value="" placeholder="Numero de lot" />
                        </div>
                        
                        <div class="col-xs-1">
                            <button type="button" class="btn btn-default searchProduit pull-right" title="{{ trans['rechercher'] }}"><i class="fa fa-search"></i></button>

                        </div>
                    </div>
                </div>
                <br />

                <div id="s_resultat">
                    
                </div>

            </div>
            <br />
            <br />
            <br />
            <br />
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="{{ trans['Close'] }}">{{ trans['Close'] }}</button>
                <input type="submit" value="{{trans['Valider']}}" class="btn btn-warning pull-right" title="{{trans['valider']}}">
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    function displayResult(item) {
        var current = JSON.parse(item.value);
        console.log(current);
        $("#s_idproduit").val(current.id);
        $("#s_lot").focus();
    }

    function autoloader(){
        $('input.typeahead').typeahead({
            ajax: {
                url: '{{ url("produit/ajaxProduit") }}',
                method: 'get',
            },
            displayField: 'libelle',
            //scrollBar:true,
            onSelect: displayResult
        });
    }
    autoloader();

    var width = "100%"; //Width for the select inputs
    var select2type = $("#type").select2({
            width: width,
            placeholder: "Veuillez choisir le type d'ajustement",
            allowClear: true,
            //disabled: {% if commande_id is defined %}true{% else %}}false{% endif %},
            theme: "classic"
        });

</script>