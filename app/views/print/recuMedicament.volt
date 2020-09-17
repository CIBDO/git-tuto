<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    
    {% include "print/entete.volt" %}
    
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          Reçu
          <small class="pull-right">Date: {{ date(trans['date_format'], strtotime(recuMedicament.date)) }}</small>
        </h2>
      </div><!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        <b>Identifiant Patient</b>: {{ patient.id }}<br>
        <b>Nom - Prénom:</b> {{ patient.nom }} {{ patient.prenom }}<br>
        <b>Adresse:</b> {{ patient.adresse }}<br>
        <b>Numéro d'ordonnance</b>: {{ recuMedicament.num_ordonnance }}<br>
      </div><!-- /.col -->

      {% if recuMedicament_organisme is defined %}
      <div class="col-sm-4 invoice-col">
        <b>Prise en charge</b><br>
        <b>Organisme</b>: {{ recuMedicament_organisme }}<br>
        <b>Numéro:</b> {{ recuMedicament.numero }}<br>
        <b>Bénéficiaire:</b> {{ recuMedicament.beneficiaire }} / <b>OGD:</b> {{ recuMedicament.ogd }}<br>
      </div><!-- /.col -->
      {% endif %}


      <div class="col-sm-4 invoice-col">
        <b>Reçu #{{ recuMedicament.id }}</b> <br>
        <b>Emis par: </b>
        {{ emetteur.nom ~ " " ~ emetteur.prenom}}
        <br>
      </div><!-- /.col -->
    </div><!-- /.row -->

    <!-- Table row -->
    <div class="row" style="margin-top: 15px">
      <div class="col-xs-12 table-responsive">
        <table class="table table-striped" style="border: 1px solid">
          <thead>
            <tr>
              <th style="border: 1px solid">Produit</th>
              <th style="border: 1px solid">Quantité</th>
              <th style="border: 1px solid">Prix Unitaire</th>
              <th style="border: 1px solid">Total</th>
            </tr>
          </thead>
          <tbody>
            {% for index, recuMedicament_detail in recuMedicament_details %}
            <tr>
              <td style="border: 1px solid">{{ recuMedicament_detail['produit_libelle'] }}</td>
              <td style="border: 1px solid">{{ recuMedicament_detail['quantite'] }}</td>
              <td style="border: 1px solid">
                {{ number_format(recuMedicament_detail['montant_unitaire'],0,'.',' ') }} F CFA
              </td>
              <td style="border: 1px solid">
                {{ number_format(recuMedicament_detail['montant_normal'],0,'.',' ') }} F CFA
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
              <td align="right">{{ number_format(recuMedicament.montant_normal,0,'.',' ') }} F CFA</td>
            </tr>
            {% if patient_assurance is defined %}
            <tr>
              <th>Prise en charge ({{ recuMedicament.type_assurance_taux }}%)</th>
              <td align="right">{{ number_format(recuMedicament.montant_restant,0,'.',' ') }} F CFA</td>
            </tr>
            {% endif %}
            <tr>
              <th>Motant dû:</th>
              <td align="right">{{ number_format(recuMedicament.montant_patient,0,'.',' ') }} F CFA</td>
            </tr>
            <tr>
              <th>Montant reçu:</th>
              <td align="right">{{ number_format(recuMedicament.montant_recu,0,'.',' ') }} F CFA</td>
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