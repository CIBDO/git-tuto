<div class='col-lg-12 no-margin'>
    <div class='box box-warning'>
        <h4>Sous dossiers </h4>
        <table class="table no-margin">
            <thead>
                <th> Numéro </th>
                <th> Date de création </th>
                <th> Médecin </th>
                <th> Observations</th>
                <th> Action </th>
            </thead>
            <tbody>
                {% for suivi in suivi_list %}
                 <tr> 
                    <td> {{ suivi['id'] }} </td>
                    <td> {{ suivi['date_creation'] }} </td>
                    <td> {{ suivi['medecin'] }} </td>
                    <td> {{ suivi['observation'] }} </td>
                    <td> 
                        <button type="button" class="btn btn-info editSuivi" title="{{trans["Modifier"]}}" data-patientid="{{ suivi['patients_id'] }}" data-dossierid="{{ dossier_id }}" data-suiviid="{{ suivi['id'] }}" data-toggle="modal" data-target="#createSuivi"><i class="fa fa-edit"></i></button>
                    </td>
                </tr>
                {% endfor %}
            </tbody>
        </table>
    </div>
</div>