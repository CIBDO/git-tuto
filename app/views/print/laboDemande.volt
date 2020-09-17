<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">

    {% include "print/enteteLabo.volt" %}

    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          Résultat d'analyse médicale
          <small class="pull-right">Date: {{ date(trans['date_format'], strtotime(laboDemande.date)) }}</small>
        </h2>
      </div><!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        <b>Identifiant Patient</b>: {{ patient.id }}<br>
        <b>Nom:</b> {{ patient.nom }}<br>
        <b>Prénom:</b> {{ patient.prenom }}<br>
        <b>Adresse:</b> {{ patient.adresse }}
      </div><!-- /.col -->

      <div class="col-sm-4 invoice-col">
        <b>Dossier #{{ laboDemande.id }}</b><br>
        <b>Paillasse: </b> {{ paillasse }}<br>
        <b>Provenance: </b> {{ laboDemande.provenance }}
        <!-- <b>Prescripteur: </b> {{ laboDemande.prescripteur }} -->
        <br>
      </div><!-- /.col -->

      <div class="col-sm-4 invoice-col">
        <b>Informations cliniques</b><br>
        {{ laboDemande.histoire }}
      </div><!-- /.col -->

    </div><!-- /.row -->

    <!-- Table row -->
    <div class="row" style="margin-top: 15px;">

      <div class="col-xs-12" >
          <div class="box" style="width: 100%;padding: 0;margin: 0;">
              <div class="box-header with-border">
                <h3 class="box-title">  <b>Resultat</b> </h3>
              </div>
              <div class="box-body">
                <div class="col-xs-12 table-responsive">
                  <table class="table table-bordered" style="border: 1px solid">
                    <thead>
                      <tr>
                        <th style="border: 1px solid">Analyse</th>
                        <th style="border: 1px solid">Resultat</th>
                        <th style="border: 1px solid">Normes</th>
                        <th style="border: 1px solid">Valeurs anterieurs</th>
                      </tr>
                    </thead>
                    <tbody>
                      {% set counter = 0 %}
                      {% for index, item in detailsDemande %}
                        {% if item['r_etat'] == 1 %}
                        <tr>
                          <td style="border: 1px solid" {% if item['children'] is defined %} colspan="4" {% endif %}>
                            <b>{{ item['categorie'] }} / {{ item['libelle'] }}</b>
                            <input type="hidden" name="id_{{ counter }}" value="{{ item['id'] }}">
                          </td>
                        {% if item['children'] is defined %}
                        </tr>
                          {% for key, child in item['children'] %}
                            {% set counter = counter + 1 %}

                        <tr>
                          <td style="border: 1px solid">
                            &nbsp;&nbsp;&nbsp;{{ child['libelle'] }}
                            <input type="hidden" name="id_{{ counter }}" value="{{ child['id'] }}">
                          </td>
                          <td style="border: 1px solid">
                            {{ child['r_valeur'] }}&nbsp;{{ child['r_unite'] }}
                          </td>
                          <td style="border: 1px solid">
                            {{ child['norme'] }}
                          </td>
                          <td style="border: 1px solid">
                            {{ child['valeur_anterieur'] }}
                          </td>
                        </tr>

                          {% endfor %}

                        {% else %}
                      <!-- PAS D'ENFANTS -->
                          <td style="border: 1px solid">
                            {{ item['r_valeur'] }}&nbsp;{{ item['r_unite'] }}
                          </td>
                          <td style="border: 1px solid">
                            {{ item['norme'] }}
                          </td>
                          <td style="border: 1px solid">
                            {{ item['r_valeur_anterieur'] }}
                          </td>
                        </tr>                        
                        {% endif %}

                        {% if item['has_antibiogramme'] == 1 AND count(item['r_antibiogrammes']) > 0 %}
                        <tr>
                          <td style="border: 1px solid; padding-right: 18px;" colspan="4">
                            <div class="row">
                            <div class="col-md-12">
                                <div class="box box-default" style="margin: 1px 10px">
                                  <div class="box-header with-border">
                                    <h3 class="box-title">Antibiogramme - {{ count(item['r_antibiogrammes']) }} germe(s)</h3>
                                    <div class="box-tools pull-right">
                                      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    </div><!-- /.box-tools -->
                                  </div><!-- /.box-header -->

                                  <div class="box-body" id="antibigrammes_{{ item['libelle'] | trim }}">

                                    <div class="antibio_to_destroy">
                                    {% for i, antibio in item['r_antibiogrammes'] %}
                                      {% if antibio != "" %}
                                        <input type="hidden" data-analyselibelle="{{ item['libelle'] | trim }}" class="antibio_hidden" name='antibiogrammes_{{item["id"]}}[]' value='{{antibio}}' />
                                      {% endif %}
                                    {% endfor %}
                                    </div>

                                  </div><!-- /.box-body -->
                                </div>
                              </div>
                            </div>
                          </td>
                        </tr>
                        {% endif %}

                        {% set counter = counter + 1 %}
                        {% endif %}
                      {% endfor %}

                    </tbody>
                  </table>
                </div>

              </div>
          </div>
      </div>
    </div>

    {{ hiddenField(['patient_id', 'value' : patient.id]) }}
    <input type="hidden" id="current_analyse"  name="current_analyse" value="" /> 


    <div class="row">
      <!-- accepted payments column -->
      <div class="col-xs-8">
        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            Cadre reservé pour les signatures
            <br /><br /><br />
            <img id="bcTarget" />
            <br /><br /><br />
        </p>
      </div><!-- /.col -->
      
    </div><!-- /.row -->
  </section><!-- /.content -->
</div><!-- ./wrapper -->
  
<script type="text/javascript">
$(document).ready(function() 
{ 
    JsBarcode("#bcTarget", "{{ patient.id }}", {
      format: "code128",
      lineColor: "#0aa",
      //width:10,
      height:40,
      displayValue: false
    });

    function displayAntibiogramme(){
        
        $(".antibio_hidden").each(function(){

            var _name = $(this).attr('name');
            var _val = $(this).val();
            var analyse_libelle = $(this).data("analyselibelle");
            var rs = JSON.parse(_val);
            
            var str = '' +
                '<div class="col-xs-4 atb_div" >' +
                  '<div class="box box-primary" style="width: 100%;padding: 0;margin: 0;">' +
                    '<div class="box-header with-border">' +
                      '<h3 class="box-title">'+ rs.germe +'</h3>' +
                    '</div>' +
                    '<div class="box-body" >';

            var antibiotiques =  rs.antibiogrammes;
            for (var i = 0; i < antibiotiques.length; i++) {
                str +=   '<div class="form-group"><b>'+antibiotiques[i].antibiotique+':</b> ' + antibiotiques[i].valeur + '</div>';
            }

            str +=     '<div class="form-group"><b>Conclusion:</b> ' + rs.conclusion + '</div>';
            var antistr = JSON.stringify(rs);
            antistr = antistr.replace(/'/g, "\\'");
            str +=      "<input type='hidden' name='"+_name+"' value='"+ antistr+"' />" +
                    '</div>' +
                  '</div>' +
                '</div>';

            $("#antibigrammes_" + analyse_libelle).append(str);

        });

        $(".antibio_to_destroy").each(function(){
          $(this).remove();
        });
    }

    displayAntibiogramme();


});
</script>