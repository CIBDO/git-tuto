<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">

    {% include "print/entete.volt" %}

    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          Ticket de prestation
          <small class="pull-right">Date: {{ date(trans['date_format'], strtotime(prestation.date)) }}</small>
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

      {% if prestation_organisme is defined %}
      <div class="col-sm-4 invoice-col">
        <b>Prise en charge</b><br>
        <b>Organisme</b>: {{ prestation_organisme }}<br>
        <b>Numéro:</b> {{ prestation.numero }}<br>
        <b>Bénéficiaire:</b> {{ prestation.beneficiaire }} / <b>OGD:</b> {{ prestation.ogd }}<br>
      </div><!-- /.col -->
      {% endif %}


      <div class="col-sm-4 invoice-col">
        <b>Ticket #{{ prestation.id }}</b><br>
        <b>Emis par: </b>
        {{ emetteur.nom ~ " " ~ emetteur.prenom}}
        <br>
      </div><!-- /.col -->
    </div><!-- /.row -->

    <!-- Table row -->
    <div class="row" style="margin-top: 15px">
      <div class="col-xs-12 table-responsive">
        <table class="table table-striped"  style="border: 1px solid">
          <thead>
            <tr>
              <th style="border: 1px solid">Prestation</th>
              <th style="border: 1px solid">Prestataire</th>
              <th style="border: 1px solid">Quantité</th>
              <th style="border: 1px solid">Prix Unitaire</th>
            {% if prestation.montant_difference > 0 %}
              <th style="border: 1px solid">Diff CELY</th>
            {% endif %}
              <th style="border: 1px solid">Total</th>
            </tr>
          </thead>
          <tbody>
            {% for index, prestation_detail in prestation_details %}
            <tr>
              <td style="border: 1px solid">
                {{ prestation_detail['unite'] }} / {{ prestation_detail['acte'] }}
              </td>
              <td style="border: 1px solid">
              {% if prestation_detail['prestataire'] != "" %}
                {{ prestation_detail['prestataire'].nom ~ " "~ prestation_detail['prestataire'].prenom }}
              {% else %}
                -
              {% endif %}
              </td>
              <td style="border: 1px solid">{{ prestation_detail['quantite'] }}</td>
              <td style="border: 1px solid">
                {{ number_format(prestation_detail['montant_unitaire'],0,'.',' ') }} F CFA
              </td>
            {% if prestation.montant_difference > 0 %}
              <td style="border: 1px solid">
                {{ number_format(prestation_detail['montant_unitaire_difference'],0,'.',' ') }} F CFA
              </td>
            {% endif %}
              <td style="border: 1px solid">
                {{ number_format( (prestation_detail['montant_normal'] + (prestation_detail['montant_unitaire_difference'] * prestation_detail['quantite'])),0,'.',' ') }} F CFA
                </td>
            </tr>
            {% endfor %}
          </tbody>
        </table>
      </div><!-- /.col -->
    </div><!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-xs-8">
        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
          Merci de conserver ce ticket pour votre prochaine visite.<br /><br /><br />
          <img id="bcTarget" />
          <br /><br /><br />
        </p>
      </div><!-- /.col -->
      <div class="col-xs-4">
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Total:</th>
              <td align="right">{{ number_format(prestation.montant_normal,0,'.',' ') }} F CFA</td>
            </tr>
            {% if prestation_organisme is defined %}
            <tr>
              <th>Prise en charge ({{ prestation.type_assurance_taux }}%)</th>
              <td align="right">{{ number_format(prestation.montant_restant,0,'.',' ') }} F CFA</td>
            </tr>
            <tr>
              <th>Reste à payer:</th>
              <td align="right">{{ number_format(prestation.montant_normal - prestation.montant_restant,0,'.',' ') }} F CFA</td>
            </tr>
            {% endif %}
            {% if prestation.montant_difference > 0 %}
            <tr>
              <th>Difference CELY:</th>
              <td align="right">{{ number_format(prestation.montant_difference,0,'.',' ') }} F CFA</td>
            </tr>
            {% endif %}
            <tr>
              <th>Montant dû:</th>
              <td align="right">{{ number_format(prestation.montant_patient,0,'.',' ') }} F CFA</td>
            </tr>
            <tr>
              <th>Montant reçu:</th>
              <td align="right">{{ number_format(prestation.montant_recu,0,'.',' ') }} F CFA</td>
            </tr>
            <tr>
              <th>Montant à retourner:</th>
              <td align="right">{{ number_format(montant_retourner,0,'.',' ') }} F CFA</td>
            </tr>
          </table>
        </div>
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

});
</script>