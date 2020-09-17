<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    
    {% include "print/entete.volt" %}

    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          Rapport d'iventaire
          <small class="pull-right">Date: {{ inventaire['date'] }}</small>
        </h2>
      </div><!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        <b>Réference de l'inventaire:</b> <span>{{ inventaire['id'] }}</span><br>
        <b>objet:</b> <span>{{ inventaire['objet'] }}</span><br>
        <b>Date:</b> <span>{{ inventaire['date'] }}</span><br>
      </div><!-- /.col -->

      <div class="col-sm-4 invoice-col">
        <b>Début:</b> <span>{{ inventaire['debut'] }}</span><br>
        <b>Fin:</b> <span>{{ inventaire['fin'] }}</span><br>
      </div>
    </div>

    <!-- Table row -->
    <div class="row" style="margin-top: 15px">
      <div class="col-xs-12 table-responsive" style="border: 1px solid">
        <table class="table table-striped">
          <thead>
            <tr>
              <th style="border: 1px solid">Produit</th>
              <th style="border: 1px solid">Initial</th>
              <th style="border: 1px solid">Entré</th>
              <th style="border: 1px solid">Sortie</th>
              <th style="border: 1px solid">Théorique</th>
              <th style="border: 1px solid">Physique</th>
              <th style="border: 1px solid">Perte</th>
              <th style="border: 1px solid">Ajout</th>
              <th style="border: 1px solid">Observation</th>
            </tr>
          </thead>
          <tbody>
        {% for index, inventaireDetail in inventaireDetails %}
            <tr>
              <td style="border: 1px solid">{{ inventaireDetail['produit_libelle'] }}</td>
              <td style="border: 1px solid">{{ inventaireDetail['initial'] }}</td>
              <td style="border: 1px solid">{{ inventaireDetail['entre'] }}</td>
              <td style="border: 1px solid">{{ inventaireDetail['sortie'] }}</td>
              <td style="border: 1px solid">{{ inventaireDetail['theorique'] }}</td>
              <td style="border: 1px solid">{{ inventaireDetail['physique'] }}</td>
              <td style="border: 1px solid">{{ inventaireDetail['perte'] }}</td>
              <td style="border: 1px solid">{{ inventaireDetail['ajout'] }}</td>
              <td style="border: 1px solid">{{ inventaireDetail['observation'] }}</td>
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