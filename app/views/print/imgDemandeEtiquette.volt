<div class="wrapper">
  <!-- Main content -->

    <div class="row no-print">
      <div class="col-sm-12">
        <center>
          <a onclick="window.print();" class="btn btn-default"><i class="fa fa-print"></i> Imprimer</a>
        </center>
      </div>
    </div>

    <table width="100%" border="0" align="center" style="margin: 5px" cellpadding="0" cellspacing="3">   
      <tr> 
        <td colspan="2" style="border:1px solid #000000;padding:5px;">
          <table width="100%">
            <tr> 
              <td align="center" style="font-size:0.9em;font-weight: bold;">Dossier N°:{{ imgDemande.id }} / {{ date(trans['date_format'], strtotime(imgDemande.date)) }}</td>
            </tr>
            <tr>
              <td align="center" style="font-size:0.9em;font-weight: bold;">
                <img id="bcTarget" />
              </td>
            </tr>
            <tr> 
              <td align="center" style="font-size:0.9em;font-weight: bold;">{{ acte_libelle }}</td>
            </tr>
            <tr> 
              <td align="center" style="font-size:0.7em;font-weight: bold;">
                {{ patient.nom }} {{ patient.prenom }} / Tel: {{ patient.telephone }}
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>    

</div><!-- ./wrapper -->
  
<script type="text/javascript">
$(document).ready(function() 
{ 
    JsBarcode("#bcTarget", "{{ imgDemande.id }}", {
      format: "code128",
      lineColor: "#0aa",
      //width:10,
      height:20,
      displayValue: false
    });

});
</script>