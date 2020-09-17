<div class="table-responsive">
    <div class="alert alert-warning alert-dismissable">
        <h4><i class="icon fa fa-warning"></i> Pour ignorer un point de distribution, laisser à vide la colonne "Physique".</h4>
    </div>
    <div class="table-responsive">
        <table class="table no-margin table-striped">
            <thead>
                <tr>
                    <th>Initial</th>
                    <th>Entré</th>
                    <th>Sortie</th>
                    <th>Théorique</th>
                    <th>Perte</th>
                    <th>Ajout</th>
                    <th>observation</th>
                </tr>
            </thead>
            <tbody id="myBody">
                <tr>
                    <td><input type="number" readonly="readonly" style="width:70px" name="initial" value="{{rs_initial}}" /></td>
                    <td><input type="number" readonly="readonly" style="width:70px" name="entre" value="{{rs_entre}}" /></td>
                    <td><input type="number" readonly="readonly" style="width:70px" name="sortie" value="{{rs_sortie}}" /></td>
                    <td><input type="number" readonly="readonly" style="width:70px" name="theorique" value="{{rs_theorique}}" /></td>
                    <td><input type="number" readonly="readonly" style="width:70px" name="perte" value="{{rs_perte}}" /></td>
                    <td><input type="number" readonly="readonly" style="width:70px" name="ajout" value="{{rs_ajout}}" /></td>
                    <td><input type="text" name="observation" value="{{rs_observation}}" /></td>
                </tr>
            </tbody>
        </table>
    
    </div>

    <table class="table no-margin table-striped">
        <thead>
            <tr>
                <th>Point de distribution</th>
                <th>Lot</th>
                <th>Date de péremption</th>
                <th>Stock théorique</th>
                <th>Physique</th>
            </tr>
        </thead>
        <tbody>
        {% for index, stockProduit in stockProduits %}

            <tr>
                <td>
                    <input type="hidden"  name="st_stock_id[]" value="{{ stockProduit['id'] }}" /> 
                    <input type="hidden"  name="st_produit_id[]" value="{{ stockProduit['produit_id'] }}" /> 
                    <a href="#">{{ stockProduit['point_distribution'] }}</a>
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
                    <input type="number" style="width:70px" name="st_quantite[]" autocomplete="off" min="0" placeholder="Physique" value="{{ stockProduit['physique'] }}" />
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
    <div style="margin:10px">
        <input type="submit" value="{{trans['Save']}}" class="btn btn-success pull-right" title="{{trans['Save']}}">
    </div>
</div>