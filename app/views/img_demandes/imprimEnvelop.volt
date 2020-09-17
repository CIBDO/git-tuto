<page backleft="50mm" backtop="50mm" backright="50mm">

	<p>&nbsp;</p>
	<p>&nbsp;</p>

	<table border="0" align="center" style="margin: 5px; width: 70%" cellpadding="0" cellspacing="3">   
      <tr> 
        <td style="border:1px solid #000000;padding:5px;">
          <table>
            <tr> 
              <td align="center" style="font-size:0.9em;font-weight: bold;">Dossier N°: {{ item.id }}  /  {{date(trans['date_format'], strtotime(item.date)) }} </td>
            </tr>
            <tr>
              <td align="center" style="font-size:0.9em;font-weight: bold;">
                <barcode type="C128A" value="{{ item.id }}" label="none" style="width:30mm; height:6mm; color: #000; font-size: 4mm"></barcode>
              </td>
            </tr>
            <tr> 
              <td align="center" style="font-size:0.9em;font-weight: bold;"> 
              	{{ acte_libelle }}
              </td>
            </tr>
            <tr> 
              <td align="center" style="font-size:0.7em;font-weight: bold;">
                {{ item.getPatients().nom }}  {{ item.getPatients().prenom }}  / Tel:  {{ item.getPatients().telephone }} 
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

</page>
