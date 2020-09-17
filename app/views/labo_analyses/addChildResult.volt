{% if children is defined %}
  {% for index, child in children %}
    <li> 
        <input type="hidden"  name="position[]" value="" /> 
       <!--  <span class="handle"> 
            <i class="fa fa-ellipsis-v"></i> 
            <i class="fa fa-ellipsis-v"></i> 
        </span>  -->
        <span class="text"> 
            Libellé: <span class="blue3"> {{child["libelle"]}} </span> | 
            <input type="hidden"  name="childs_id[]" value="{{child["id"]}}" />
            Code: <span class="blue3"> {{child["code"]}} </span> |
            Type de valeur: <span class="blue3"> {{child["type_valeur"]}} </span> |
            Valeurs possibles: <span class="blue3"> {{child["valeur_possible"]}} </span> |
            Unité de mesure: <span class="blue3"> {{child["unite"]}} </span> |
            Normes: <span class="blue3"> {{child["norme"]}} </span> 
        </span> 
        <div class="tools">
            <!-- <i class="fa fa-edit editChild" data-childid="{{child['id']}}" data-toggle="modal" data-target="#childEditModal"></i> -->
            <i class="fa fa-trash-o suppElement"></i>
        </div>
    </li>
  {% endfor %}
{% endif %}
