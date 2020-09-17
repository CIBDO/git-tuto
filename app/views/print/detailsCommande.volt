<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
   
    {% include "print/entete.volt" %}

    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
         Bon de commande
          <small class="pull-right">Date: {{ commande['date'] }}</small>
        </h2>
      </div><!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        <b>Référence de la commande:</b> <span>{{ commande['id'] }}</span><br>
        <b>Objet:</b> <span>{{ commande['objet'] }}</span><br>
        <b>Date:</b> <span>{{ commande['date'] }}</span><br>
      </div><!-- /.col -->

      <div class="col-sm-4 invoice-col">
        <b>Montant:</b> <span>{{ number_format(commande['montant'],0,'.',' ') }}</span><br>
        <b>Fournisseur:</b> <span>{{ commande['fournisseur'] }}</span>
      </div><!-- /.col -->
    </div>

    <!-- Table row -->
    <div class="row" style="margin-top: 15px">
      <div class="col-xs-12 table-responsive">
        <table class="table table-bordered table-striped" style="border: 1px solid">
          <thead>
            <tr>
              <th style="border: 1px solid">Produit</th>
              <th style="border: 1px solid">Quantité</th>
              <th style="border: 1px solid">Prix d'achat</th>
            </tr>
          </thead>
          <tbody>
            {% for index, commandeDetail in commandeDetails %}
            <tr>
              <td style="border: 1px solid">{{ commandeDetail['produit_libelle'] }}</td>
              <td style="border: 1px solid">{{ commandeDetail['quantite'] }}</td>
              <td style="border: 1px solid">{{ number_format(commandeDetail['prix'],0,'.',' ') }}</td>
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