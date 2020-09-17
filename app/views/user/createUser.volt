<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {% if form_action == 'edit' %}
                {{ trans['Modifier un utilisateur'] }}
            {% else %}
                {{ trans['Créer un utilisateur'] }}
            {% endif %}
            </h4>
        </div>
        {% if form_action == 'edit' %}
        <form action="{{ url('user/' ~ form_action ~ 'User/' ~ user_id) }}" class="form ajaxForm" method="post">
        {% else %}
        <form action="{{ url('user/' ~ form_action ~ 'User/') }}" class="form ajaxForm" method="post">
        {% endif %}
            <div class="modal-body">
                <div class="error_modal_container"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="box box-warning">
                            <div class="box-header">
                                <h3 class="box-title">Informations personnelles</h3>
                                <hr class="paymentDetail">
                            </div>
                            <div class="box-body">
                                
                                <div class="form-group">
                                    <label for="nom" class="control-label">{{ trans['Name'] }}</label>
                                    {{ form.render('nom') }}
                                </div>

                                <div class="form-group">
                                    <label for="prenom" class="control-label">{{ trans['Nom'] }}</label>
                                    {{ form.render('prenom') }}
                                </div>

                                <div class="form-group">
                                    <label for="email" class="control-label">{{ trans['Email'] }}</label>
                                    {{ form.render('email') }}
                                </div>

                                <div class="form-group">
                                    <label for="telephone" class="control-label">{{ trans['Téléphone'] }}</label>
                                    {{ form.render('telephone') }}
                                </div>

                                <div class="form-group">
                                    <label for="profile" class="control-label">{{ trans['Profil'] }}</label>
                                    {{ form.render('profile') }}
                                </div>

                                <div class="form-group">
                                    <label for="service" class="control-label">{{ trans['Service'] }}</label>
                                    {{ form.render('services_id') }}
                                </div>

                                <div class="form-group">
                                    <label for="prestataire" class="control-label">{{ trans['Prestataire'] }}</label>
                                    {{ checkField(["prestataire", "value" : "1"]) }}
                                </div>

                                <div class="form-group">
                                    <label for="forms">{{ trans['Formulaires associés']}} :</label>
                                    {{ selectStatic(['forms_assoc[]', forms_assoc, 'using' : ['id', 'nom'], 'multiple': 'multiple', 'class': 'form-control', 'id': 'forms_assoc', 'class': 'forms_assoc', 'useEmpty': true, 'emptyText' : '']) }}
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="box box-warning">
                            <div class="box-header">
                                <h3 class="box-title">Connexion et sécurité</h3>
                                <hr class="paymentDetail">
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="login" class="control-label">Identifiant</label>
                                         {{ form.render('login') }}
                                    </div>
                                    <div class="col-md-6">
                                        <label for="telephone" class="control-label">Mot de passe</label>
                                        {{ form.render('password') }}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h4>&nbsp;&nbsp;Permissions</h4>
                                </div>
                                
                            </div>
                            <div class="table-responsive">
                                <table class="table table table-bordered no-margin">
                                  <thead>
                                  <tr>
                                    <th>&nbsp;</th>
                                    <th align="center">Lecture seule</th>
                                    <th align="center">Lecture - Ecriture</th>
                                    <th align="center">Administration</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                      <tr>
                                        <td align="left">Vente de ticket</td>
                                        <td align="center"></td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "venteticket_w"]) }}</td>
                                        <td align="center"></td>
                                      </tr>

                                    {% if activeModules.modPharmacie == 1%}
                                      <tr>
                                        <td align="left">Vente de médicament</td>
                                        <td align="center"></td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "ventemedic_w"]) }}</td>
                                        <td align="center"></td>
                                      </tr>
                                    {% endif %}

                                      <tr>
                                        <td align="left">Caisse</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "caisse_r"]) }}</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "caisse_w"]) }}</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "caisse_a"]) }}</td>
                                      </tr>
                                      <tr>
                                        <td align="left">Dossier patient</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "dp_r"]) }}</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "dp_w"]) }}</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "dp_a"]) }}</td>
                                      </tr>

                                    {% if activeModules.modConsultation == 1%}
                                      <tr>
                                        <td align="left">Consultation</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "cs_r"]) }}</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "cs_w"]) }}</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "cs_a"]) }}</td>
                                      </tr>
                                    {% endif %}
                                    
                                    {% if activeModules.modPharmacie == 1%}
                                      <tr>
                                        <td align="left">Pharmacie</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "ph_r"]) }}</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "ph_w"]) }}</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "ph_a"]) }}</td>
                                      </tr>
                                    {% endif %}

                                    {% if activeModules.modLaboratoire == 1%}
                                      <tr>
                                        <td align="left">Labo</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "labo_r"]) }}</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "labo_w"]) }}</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "labo_a"]) }}</td>
                                      </tr>
                                    {% endif %}

                                    {% if activeModules.modImagerie == 1%}
                                      <tr>
                                        <td align="left">Imagerie</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "img_r"]) }}</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "img_w"]) }}</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "img_a"]) }}</td>
                                      </tr>
                                    {% endif %}

                                    {% if activeModules.modFinance == 1%}
                                      <tr>
                                        <td align="left">Finance</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "f_r"]) }}</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "f_w"]) }}</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "f_a"]) }}</td>
                                      </tr>
                                    {% endif %}

                                      <tr>
                                        <td align="left">Configuration</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "conf_r"]) }}</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "conf_w"]) }}</td>
                                        <td align="center">{{ checkField(["permission[]", "value" : "conf_a"]) }}</td>
                                      </tr>
                                  </tbody>
                                </table>
                                {{ hiddenField(['permission_json', 'id' : 'permission_json']) }}
                            </div>
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
    $(".profile").select2({width: width, placeholder:"choisir",theme: "classic"});
    $(".services_id").select2({width: width, placeholder:"choisir",theme: "classic"});
    $(".forms_assoc").select2({width: width, placeholder:"choisir",theme: "classic"});
    $("#password").val("");
        
    setTimeout(function(){
        if($("#permission_json").val() != ""){
            var permission = JSON.parse($("#permission_json").val());
            for(var i=0; i<permission.length; i++)
            {
                $("input:checkbox[value='"+permission[i]+"']").attr("checked", "checked");
            }
        }
    }, '1000');

    $("[data-mask]").inputmask();
</script>
