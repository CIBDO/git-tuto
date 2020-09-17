<div class="table-responsive">
    {% if type == 'ajout' %}
    <div class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-warning"></i> ATTENTION!</h4>
        Un ajustement de type "Ajout" pourrait impacter le stock de manière indésirable.<br />
        Il est conseillé de réaliser cette opération aucours de l'inventaire prochain.
    </div>
    {% endif %}
    <div class="form-group">
        <label for="libelle" class="control-label">{{ trans['Motif'] }}:</label>
        {{ select('motif', motif, 'class': 'form-control', 'required' : 'required', 'using' : ['id', 'libelle'], 'useEmpty' : true, 'id' : 'motif') }}
    </div>
    <table class="table no-margin table-striped">
        <thead>
            <tr>
                <th>Point de distribution</th>
                <th>Produit</th>
                <th>Lot</th>
                <th>Date de peremption</th>
                <th>Stock</th>
                <th>Quantité</th>
            </tr>
        </thead>
        <tbody>
        {% for index, stockProduit in stockProduits %}

            <tr>
                <td>
                    <input type="hidden"  name="stock_id[]" value="{{ stockProduit['id'] }}" /> 
                    <a href="#">{{ stockProduit['point_distribution'] }}</a>
                </td>
                <td>
                    <input type="hidden"  name="produit_id[]" value="{{ stockProduit['produit_id'] }}" /> 
                    <a href="#">{{ stockProduit['produit_libelle'] }}</a>
                </td>
                <td>
                    <a href="#">{{ stockProduit['lot'] }}</a>
                </td>
                <td>
                    <a href="#">{{ date(trans['date_only_format'], strtotime(stockProduit['date_peremption'])) }}</a>
                </td>
                <td>
                    <a href="#">{{ stockProduit['reste'] }}</a>
                </td>
                <td>
                    <input type="number" name="quantite[]" style="width:70px" autocomplete="off" min="0" max="{% if type == 'perte' %}{{ stockProduit['reste'] }}{% endif %}"  placeholder="Quantité " />
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
</div>

<script>
    var width = "100%"; //Width for the select inputs
    $("#motif").select2({width: width, allowClear: true, placeholder: "choisir", theme: "classic"});
</script>