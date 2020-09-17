<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                {{ trans['Ordonnances en attendes'] }}
            </h4>
        </div>
            <div class="modal-body">
                <div class="error_modal_container"></div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Numéro Ordonnance</th>
                            <th>ID Patient</th>
                            <th>Nom Patient</th>
                        </tr>
                    </thead>
                    <tbody>
                    {% for index, rs in result %}

                        <tr  class="showPrescrition" style="background-color: #cce0ff;">
                            <td>{{ rs['ordonnance_id'] }}</td>
                            <td>{{ rs['patient_id'] }}</td>
                            <td>{{ rs['patient_nom'] }}</td>
                        </tr>

                        {% if count(rs['produits_available']) > 0 %}
                        <tr {% if index != 0 %} class="hidden" {% endif %}>
                            <td colspan="3" style="padding: 2px 20px;">
                                <table class="table no-margin table-striped">
                                    <thead>
                                        <tr style="background-color: #b3ffb3;">
                                            <th>Produits </th>
                                            <th>Prix</th>
                                            <th>Qt Prescrite</th>
                                            <th>Qt Disponible</th>
                                            <th width="100">
                                                <button class="btn btn-info btn-sm produitOrdonnanceAddAll" type="button"><i class="fa fa-plus"></i> Ajouter tout</button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    {% for index_available, available in rs['produits_available'] %}
                                        <tr>
                                            <td>
                                                <input type="hidden" class="ord_idproduit" value="{{ available['infos']['id'] }}" />
                                                <input type="hidden" class="ord_libelle_produit" value="{{ available['infos']['libelle'] }}" />
                                                {{ available['infos']['libelle'] }}
                                            </td>
                                            <td>
                                                <input type="hidden" class="ord_prix" value="{{available['infos']['prix']}}" />
                                                {{ number_format(available['infos']['prix'],0,'.',' ') }} F CFA
                                            </td>
                                            <td>
                                                {{ available['quantite'] }}
                                            </td>
                                            <td>
                                                {% if available['stockDispo'] > 0 %}{{ available['stockDispo'] }}{% else %}0{% endif %}
                                            </td>
                                            <td>
                                                {% if available['stockDispo'] > 0 %}
                                                <div class="input-group input-group-sm">
                                                    <input type="number" value="{{ available['a_ajouter'] }}" min="0" max="{{ available['stockDispo'] }}" class="ord_quantite form-control">
                                                    <span class="input-group-btn">
                                                      <button class="btn btn-info btn-flat produitOrdonnanceAdd" type="button"><i class="fa fa-plus"></i></button>
                                                    </span>
                                                </div>
                                                {% else %}
                                                <input type="hidden" class="ord_quantite" value="0" />
                                                {% endif %}
                                            </td>
                                        </tr>
                                    {% endfor %}
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        {% endif %}

                        {% if count(rs['produits_not_available']) > 0 %}
                        <tr {% if index != 0 %} class="hidden" {% endif %}>
                            <td colspan="3" style="padding: 2px 20px;">
                                <table class="table no-margin table-striped">
                                    <thead>
                                        <tr style="background-color: #ffd6cc;">
                                            <th>Produits (Non dispensés) </th>
                                            <th>Quantité prescrite</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    {% for index_not_available, not_available in rs['produits_not_available'] %}
                                        <tr>
                                            <td>{{ not_available['libelle'] }}</td>
                                            <td>{{ not_available['quantite'] }}</td>
                                        </tr>
                                    {% endfor %}
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        {% endif %}

                    {% endfor %}
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="{{ trans['Close'] }}">{{ trans['Close'] }}</button>
            </div>
    </div>
</div>

<script type="text/javascript">

    $(".showPrescrition").click(function(){
        //alert("TT");

        var currentRow = $(this).closest("tr");
        var nextRow = $(this).closest("tr").next();
        var nextnextRow = nextRow.next();
            //nextRow.show();
        if(nextRow.hasClass( "hidden" ).toString() == "true"){
            nextRow.removeClass("hidden");
        }else{
            nextRow.addClass("hidden");
        }

        if(nextnextRow.hasClass( "hidden" ).toString() == "true"){
            nextnextRow.removeClass("hidden");
        }else{
            nextnextRow.addClass("hidden");
        }
    });

</script>