<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                {{ trans['Distribution de produit'] }}
            </h4>
        </div>
        <form action="{{ url('stock_point_distribution/createDistribution') }}" class="form" method="post">
            <div class="modal-body">
                <div class="error_modal_container"></div>
                <div class="form-group">
                    <label for="objet" class="control-label">Point de distribution (Destination)</label>
                    {{ select(['point_distribution_id', pointDistribution, 'class': 'form-control', 'using' : ['id', 'libelle'], 'useEmpty' : true, 'id' : 'point_distribution_id', 'required' : 'required']) }}
                </div>

                <div class="box box-info box-solid">
                    <div class="box-header with-border">
                      <h3 class="box-title">Liste des produits selectionnnés</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin table-striped">
                                <thead>
                                    <tr>
                                        <th>Point de distribuion</th>
                                        <th>Produit</th>
                                        <th>Lot</th>
                                        <th>Date de péremtion</th>
                                        <th>Stock disponible</th>
                                        <th>Quantité à distribuer</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                {% for index, stock in stock_list %}

                                    <tr>
                                        <td>
                                            <input type="hidden"  name="id[]" value="{{ stock['id'] }}" /> 
                                            <a href="#">{{ stock['point_distribution'] }}</a>
                                        </td>
                                        <td>
                                            <a href="#">{{ stock['produit_libelle'] }}</a>
                                        </td>
                                        <td>
                                            <a href="#">{{ stock['lot'] }}</a>
                                        </td>
                                        <td>
                                            <a href="#">{{ date(trans['date_only_format'], strtotime(stock['date_peremption'])) }}</a>
                                        </td>
                                        <td>
                                            <a href="#">{{ stock['reste'] }}</a>
                                        </td>
                                        <td>
                                            <input type="number" style="width: 100px;" min="1" max="{{ stock['reste']}}" name="quantite[]" required="required" autocomplete="off" value="" placeholder="Quantité" />
                                        </td>
                                        <td>
                                            <i class="fa fa-trash-o suppElement" style="cursor:pointer"></i>
                                        </td>
                                    </tr>
                                {% endfor %}
                                </tbody>
                            </table>
                        
                        </div>
                        
                    </div>
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
            placeholder: 'Veuillez choisir le point de distribuion',
            allowClear: true,
            //disabled: {% if commande_id is defined %}true{% else %}}false{% endif %},
            theme: "classic"
        });

</script>
