<?php
	/*$idPatient	= $rsP[$k]->patient_id;
	$num_recu	= $rsP[$k]->num_recu;
	
	if( $idPatient == -1 || $num_recu == -1 )
	{
		header("location: fin_facture.resh");
		exit();
	}
	else
	{
		$rsPatient	= Patient::findByPk( $idPatient );
	}*/

?>
<page backleft="50mm" backtop="5mm" backright="8mm">

<table border="0" cellspacing="0" width="100%">
	<tr>
		<td style="padding-right:10px">
		logo
		</td>
		<td>
			<table border="0" cellspacing="0" width="100%">
				<tr>
					<td><b>------</b></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>-------</td>
				</tr>
				<tr>
					<td>---------</td>
				</tr>
				<tr>
					<td>-----</td>
				</tr>
				<tr>
					<td><b>LABORATOIRE D'ANALYSES BIOMEDICALES</b></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<p>&nbsp;</p>
<p>&nbsp;</p>

<table style="width:100%">	 
	<tr> 
		 <td colspan="2">
		  <table style="width:100%">
			<tr> 
				 <td style="width:50%;font-size:16px;font-weight: bold;">&nbsp;</td>
				 <td style="width:50%;font-size:20px;font-weight: bold;">		
					nom prenom	
				 </td>
			</tr>
			<tr> 
				 <td colspan="2">&nbsp;</td>
			</tr>
			<tr> 
				 <td style="width:50%;font-size:16px;font-weight: bold;">Identifiant patient:</td>
				 <td style="width:50%;font-size:16px;font-weight: bold;">		
					id patient		
				 </td>
			</tr>
			<tr> 
				 <td style="width:50%;font-size:16px;font-weight: bold;">Adresse:</td>
				 <td style="width:50%;font-size:16px;font-weight: bold;">		
					adresse patient
				 </td>
			</tr>
			<tr> 
				 <td style="width:50%;font-size:16px;font-weight: bold;">Tel:</td>
				 <td style="width:50%;font-size:16px;font-weight: bold;">	
					tel patien
				 </td>
			</tr>
			<tr>
				 <td style="width:50%;font-size:16px;font-weight: bold;">Numéro Paillasse:</td>
				 <td style="width:50%;font-size:16px;font-weight: bold;">		
					paillasse			
				 </td>
			 </tr>
			
		   </table>
		  </td>
	</tr>
</table>

</page>
