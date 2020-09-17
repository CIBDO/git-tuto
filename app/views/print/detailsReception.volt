<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
   
    {% include "print/entete.volt" %}
   
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          Bordereau de réception
          <small class="pull-right">Date: {{ reception['date'] }}</small>
        </h2>
      </div><!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        <b>Référence de la réception:</b> <span>{{ reception['id'] }}</span><br>
        <b>Objet:</b> <span>{{ reception['objet'] }}</span><br>
        <b>Date:</b> <span>{{ reception['date'] }}</span><br>
      </div><!-- /.col -->

      {% if commande is defined %}
      <div class="col-sm-4 invoice-col">
          <b>Référence de la commande:</b> <span>{{ commande['id'] }}</span><br>
          <b>Objet:</b> <span>{{ commande['objet'] }}</span><br>
          <b>Date:</b> <span>{{ commande['date'] }}</span><br>
          <b>Montant:</b> <span>{{ number_format(commande['montant'],0,'.',' ') }}</span><br>
      </div>
      {% endif %}
      <div class="col-sm-4 invoice-col">
          <b>Fournisseur:</b>
          <span>{{ reception['fournisseur'] }}</span>
      </div>
    </div>

    <!-- Table row -->
    <div class="row" style="margin-top: 15px">
      <div class="col-xs-12 table-responsive">
        <table class="table table-striped"  style="border: 1px solid">
          <thead>
            <tr>
              <th style="border: 1px solid">Produit</th>
              <th style="border: 1px solid">Quantité</th>
              <th style="border: 1px solid">Litige</th>
              <th style="border: 1px solid">Manquant</th>
              <th style="border: 1px solid">Observation</th>
              <th style="border: 1px solid">Lot</th>
              <th style="border: 1px solid">Date péremption</th>
              <th style="border: 1px solid">Pr Achat</th>
              <th style="border: 1px solid">Coef</th>
              <th style="border: 1px solid">Pr de Vente</th>
            </tr>
          </thead>
          <tbody>
        {% for index, receptionDetail in receptionDetails %}
            <tr>
              <td style="border: 1px solid">{{ receptionDetail['produit_libelle'] }}</td>
              <td style="border: 1px solid">{{ receptionDetail['quantite'] }}</td>
              <td style="border: 1px solid">{{ receptionDetail['litige'] }}</td>
              <td style="border: 1px solid">{{ receptionDetail['manquant'] }}</td>
              <td style="border: 1px solid">{{ receptionDetail['observation'] }}</td>
              <td style="border: 1px solid">{{ receptionDetail['lot'] }}</td>
              <td style="border: 1px solid">{{ receptionDetail['date_peremption'] }}</td>
              <td style="border: 1px solid">{{ number_format(receptionDetail['prix_achat'],0,'.',' ') }}</td>
              <td style="border: 1px solid">{{ receptionDetail['coef'] }}</td>
              <td style="border: 1px solid">{{ number_format(receptionDetail['prix_vente'],0,'.',' ') }}</td>
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
          Cadre reservé pour les signatures
          <br /><br /><br /><br /><br /><br /><br />
        </p>
      </div><!-- /.col -->
      <div class="col-xs-4">
        
      </div><!-- /.col -->
    </div><!-- /.row -->
  </section><!-- /.content -->
</div><!-- ./wrapper -->