<div style="max-height:200px;overflow-y:scroll" >
    <table class="table no-margin table-striped">
        <thead>
            <tr>
                <th>Antibiotique</th>
                <th>Valeur</th>
            </tr>
        </thead>
        <tbody id="myBody">
        {% for index, antibiotique in arrayAntibiotique %}
            <tr>
                <td>
                    <input type="hidden" class="antibiotique_l" id="antibiotique_l_{{index}}"  name="antibiotique[]" value="{{ antibiotique.libelle }}" /> 
                    {{ antibiotique.libelle }}
                </td>
                
                <td>
                    <select style="width:120px" class="antibiotique_v" id="antibiotique_v_{{index}}" name="valeur[]">
                        <option value="Non definie">Non definie</option>
                        <option value="Résistant">Résistant</option>
                        <option value="Sensible">Sensible</option>
                        <option value="Intermediaire">Intermediaire</option>
                    </select>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

</div>
