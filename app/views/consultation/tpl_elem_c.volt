

	  <div class="custom_form_elements">

	    <label for="elem_{{elem['id']}}" class="control-label">{{ elem['libelle'] }} {% if elem['required'] == '1' %}<span style="color: red">*</span>{% endif %} :</label>

	    {% if elem['type_valeur'] == "n" %}
	      <input class="pull-right" type="number" step="any" placeholder="" name="elem_{{elem['id']}}" value="{{ elem['r_valeur'] }}" {% if elem['required'] == '1' %}required{% endif %} />
	    {% elseif elem['type_valeur'] == "an" %}
	      <input class="pull-right" type="text" placeholder="" name="elem_{{elem['id']}}" value="{{ elem['r_valeur'] }}" {% if elem['required'] == '1' %}required{% endif %} />
	    {% elseif elem['type_valeur'] == "d" %}
	      <input class="pull-right" type="date" placeholder="" name="elem_{{elem['id']}}" value="{{ elem['r_valeur'] }}" {% if elem['required'] == '1' %}required{% endif %} />
	    {% elseif elem['type_valeur'] == "c" %}

	        <select class="pull-right" name="elem_{{elem['id']}}" {% if elem['required'] == '1' %}required{% endif %}>
	            <option value="">----Choisir la valeur----</option>
	            {% if count(elem['valeur_possible']) > 0 %}
	                {% for k, val in elem['valeur_possible'] %}
	                  <option value="{{val}}" {% if elem['r_valeur'] == val %} selected="selected"{% endif %}>{{val}}</option>
	              	{% endfor %}
	          {% endif %}
	        </select>

	    {% endif %}

	  </div>

	