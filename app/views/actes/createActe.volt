<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {% if form_action == 'edit' %}
                {{ trans['Modifier une prestation'] }}
            {% else %}
                {{ trans['Creér une prestation'] }}
            {% endif %}
            </h4>
        </div>
        {% if form_action == 'edit' %}
        <form action="{{ url('actes/' ~ form_action ~ 'Acte/' ~ acte_id) }}" class="form ajaxForm" method="post">
        {% else %}
        <form action="{{ url('actes/' ~ form_action ~ 'Acte/') }}" class="form ajaxForm" method="post">
        {% endif %}
            <div class="modal-body">
                <div class="error_modal_container"></div>

                <div class="form-group">
                    <label for="code" class="control-label">{{ trans['Code'] }}</label>
                    {{ form.render('code') }}
                </div>

                <div class="form-group">
                    <label for="libelle" class="control-label">{{ trans['Libellé'] }}</label>
                    {{ form.render('libelle') }}
                </div>

                <div class="form-group">
                    <label for="unite" class="control-label">{{ trans['Unité'] }}</label>
                    {{ form.render('unite') }}
                </div>
                
                <div class="form-group">
                    <label for="type" class="control-label">{{ trans['Type'] }}</label>
                    {{ form.render('type') }}
                </div>

            {% if  (activeModules.modLaboratoire == 1 OR activeModules.modImagerie == 1) and form_action == 'create' %}

                <div class="form-group" id="check_m">
                    <label for="check_module" class="control-label">
                    {{ trans["Créer l'acte pour le module"] }} <span id="ll"></span>
                    {{ checkField(["check_module", "value" : "1", 'id' : 'check_module']) }}
                    {{ hiddenField(["check_module_value", "value" : "", 'id' : 'check_module_value']) }}
                </div>
            {% endif %}

                <div class="form-group" style="display: none;" id="check_labo_cat">
                    <label for="labo_categories" class="control-label">{{ trans["Catégorie d'analyse"] }}</label>
                    {{ selectStatic(['labo_categories', labo_categories, 'using' : ['id', 'libelle'], 'class': 'form-control labo_categories', 'id': 'labo_categories', 'useEmpty': true, 'emptyText': "---------"]) }}
                </div>
                <div class="form-group" style="display: none;" id="check_img_cat">
                    <label for="img_categories" class="control-label">{{ trans["Catégorie d'imagérie"] }}</label>
                    {{ selectStatic(['img_categories', img_categories, 'using' : ['id', 'libelle'], 'class': 'form-control img_categories', 'id': 'img_categories', 'useEmpty': true, 'emptyText': "---------"]) }}
                </div>

                <div class="form-group">
                    <label for="prix" class="control-label">{{ trans['Prix'] }}</label>
                    {{ form.render('prix') }}
                </div>

                <div class="form-group">
                    <label for="service" class="control-label">{{ trans['Service'] }}</label>
                    {{ form.render('services_id') }}
                </div>

                <div class="form-group">
                    <div class="box box-primary" style="width: 100%;padding: 0;margin: 0;">
                      <div class="box-header with-border">
                        <h3 class="box-title">Autres prix</h3>
                      </div>
                      <div class="box-body">
                            <table width="100%">
                            
                            {% for index, item in prix_list %}
                                <tr  style=" border-bottom: #ccc solid 1px">
                                    <td style=" width:40%"> 
                                        <input type="hidden" value="{{item['prix_id']}}" name="prix_id[]" />
                                        <input type="hidden" value="{{item['id']}}" name="type_assurance_id[]" />
                                        {{item['libelle']}} 
                                    </td>
                                    <td  style=" width:30%"> 
                                        <input type="number" value="{{item['prix']}}" name="prix2[]" /> 
                                    </td>
                                    <td  style=" width:30%" align="right"> 
                                        <select name="relicat[]"> 
                                            <option value="0" {%if item['relicat'] == "0" %} selected="selected" {%endif%}>non</option>
                                            <option value="1" {%if item['relicat'] == "1" %} selected="selected" {%endif%}>oui</option>
                                        </select>
                                    </td>
                                </tr>
                            {% endfor %}

                            </table>
                      </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="{{ trans['Close'] }}">{{ trans['Close'] }}</button>
                <input type="submit" value="{{trans['Save']}}" class="btn btn-success pull-right" title="{{trans['Save']}}">
            </div>
        </form>
    </div>
</div>

<script>
    var width = "100%"; //Width for the select inputs
    $(".services_id").select2({width: width, placeholder: "choisir", theme: "classic"});
    $(".unite").select2({width: width, placeholder: "choisir", theme: "classic"});
    $(".type").select2({width: width, placeholder: "choisir", theme: "classic"});
    $(".labo_categories").select2({width: width, placeholder: "choisir", theme: "classic"});
    $(".img_categories").select2({width: width, placeholder: "choisir", theme: "classic"});
            
    $("#check_m").hide();
    $("#check_labo_cat").hide();
    $("#check_img_cat").hide();

    {% if  (activeModules.modLaboratoire == 1 OR activeModules.modImagerie == 1) and form_action == 'create' %}

        $('body').on('change', '#type', function () {
            if( $(this).val() == "labo") {
                $("#check_m").show();
                $("#ll").html("labo");
                $("#check_module_value").val("labo");
            }
            else if( $(this).val() == "imagerie" ){
                $("#check_m").show();
                $("#ll").html("imagerie");
                $("#check_module_value").val("imagerie");
            }
            else{
                $("#check_m").hide();
                $("#check_img_cat").hide();
                $("#check_labo_cat").hide();
            }
            checkCat();
        });

        $('body').on('change', '#check_module', function () {
            checkCat();
        });

        function checkCat(){
            if( $(check_module).is(':checked') && $("#ll").html() == "labo" ) {
                $("#check_labo_cat").show();
                $("#check_img_cat").hide();
                $("#labo_categories").attr("required", "required");
            }
            else{
                $("#check_labo_cat").hide();
                $("#labo_categories").removeAttr("required");
            }
            if( $(check_module).is(':checked') && $("#ll").html() == "imagerie" ) {
                $("#check_img_cat").show();
                $("#check_labo_cat").hide();
                $("#img_categories").attr("required", "required");
            }
            else{
                $("#check_img_cat").hide();
                $("#img_categories").removeAttr("required");
            }
        }
    {% endif %}

</script>