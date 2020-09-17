<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">

    {% include "print/enteteImagerie.volt" %}

    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          Résultat d'imagerie médicale
          <small class="pull-right">Date: {{ date(trans['date_format'], strtotime(imgDemande.date)) }}</small>
        </h2>
      </div><!-- /.col -->
    </div>
    <!-- info row -->
    <table width="100%" border="0" align="center" style="margin: 5px" cellpadding="0" cellspacing="3">   
      <tr> 
        <td colspan="2" style="border:1px solid #000000;padding:5px;">
          {% if entete.img_msg_annonce != "" %}
            <div><b>{{ entete.img_msg_annonce }}</b></div>
          {% endif %}
          <div class="row invoice-info">
           
            <div class="col-sm-4 invoice-col">
              <b>Identifiant Patient</b>: {{ patient.id }}<br>
              <b>Nom:</b> {{ patient.nom }}<br>
              <b>Prénom:</b> {{ patient.prenom }}<br>
              <b>Adresse:</b> {{ patient.adresse }}
            </div><!-- /.col -->

            <div class="col-sm-4 invoice-col">
              <b>Dossier #{{ imgDemande.id }}</b><br>
              <b>Prescripteur: </b> {{ imgDemande.provenance }} / {{ imgDemande.prescripteur }}
              <br>
            </div><!-- /.col -->

          </div><!-- /.row -->
        </td>
      </tr>
    </table>
    
    <center><h2 class="box-title">  <b><u>{{ acte_libelle }}</u></b> </h2></center>

    <!-- Table row -->
    <div class="row" style="margin-top: 15px;">

      <div class="col-xs-12" >
          
          <!-- <h4 class="box-title">  <u><b>INDICATIONS</b></u> </h4>
          <p>
            {{ imgDemande.indication }}
          </p>

          <h4 class="box-title">  <u><b>PROTOCOLE D'EXAMEN</b></u> </h4>
          <p>
                {{ result.protocole }}
          </p>

          <h4 class="box-title">  <u><b>RESULTAT</b></u> </h4>
          <p>
                {{ result.interpretation }}
          </p>

          <h4 class="box-title">  <u><b>CONCLUSION</b></u> </h4> -->
          <p>
                {{ result.conclusion }}
          </p>

      </div>
    </div>

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-xs-8" {% if imgDemande.etat != "clotûré" %}style="z-index: 99999;position: absolute;top: 300px;opacity: 0.4;transform: rotate(7deg);left:200px;"{% endif %}>
        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
          {% if imgDemande.etat == "clotûré" %}
            {% if entete.img_msg_annonce != "" %}
              <b>{{ entete.img_msg_fin }}</b>
            {% endif %}
            <br /><br /><br />
            <b>Le radiologue:</b><br>
            <b>{{ radiologue }} </b><br>
            <img class="pull-right" id="bcTarget" />
            <br /><br /><br />
          {% else %}

            <b><i>* * * * Résultat non validé /// Résultat non validé /// Résultat non validé * * * * </i></b>

          {% endif %}

        </p>
      </div><!-- /.col -->
      
    </div><!-- /.row -->
  </section><!-- /.content -->
</div><!-- ./wrapper -->
  
<script type="text/javascript">
$(document).ready(function() 
{ 
    {% if imgDemande.etat == "clotûré" %}
    JsBarcode("#bcTarget", "{{ imgDemande.id }}", {
      format: "code128",
      lineColor: "#0aa",
      //width:10,
      height:40,
      displayValue: false
    });
    {% endif %}



});
</script>