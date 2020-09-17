<?php
use Phalcon\Mvc\Model\Resultset;

class CaissePharmacieController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('Etats');
        parent::initialize();
    }


    public function indexAction($patient_id = 0) {
        
        //$typeAssurancelist = TypeAssurance::find();
    	$typeAssurancelist = TypeAssurance::query()
	    							->columns("CONCAT(id, '|', taux) as id, libelle as libelle")
								    ->orderBy("libelle")
								    ->execute();

        $this->view->typeAssurancelist   = $typeAssurancelist;
        $this->view->residences = Residence::find();

        $pointDeVenteList = PointDistributionUser::find("user_id = " . $this->session->get('usr')['id']);
        $rs = [];
        foreach ($pointDeVenteList as $pointDeVente) {
            $rs[$pointDeVente->point_distribution_id] = $pointDeVente->getPointDistribution()->libelle;
        }
        $this->view->pointDeVenteList = $rs;

        if($this->session->get('pointDeVente')){
        	$this->view->pointDeVente = $this->session->get('pointDeVente');
        }
        else{
	        $this->view->pointDeVente = array("id" => 0, "libelle" => "?");
        }

        //Post process
        if($this->request->isPost()) {
            $data = $this->request->getPost();

            //Patient informations
            if($data['id'] > 0){
                $patient = Patients::findFirst($data['id']);
                if(!$patient){
                    $msg = $this->trans['on_error'];
                    $this->flash->error($msg);
                    return;
                }
            }
            else{
            	$patient = new Patients();
            }
            $patient->nom 				= $data['nom'];
            $patient->prenom 			= $data['prenom'];
            $patient->date_naissance 	= $data['date_naissance'];
            $patient->sexe 				= $data['sexe'];
            $patient->adresse 			= $data['adresse'];
            $patient->num_ordonnance	= $data['num_ordonnance'];
            $patient->telephone 		= $data['telephone'];
            if(!empty($data['residence_id']) && ($data['residence_id'] > 0) ){
                $patient->residence_id = $data['residence_id'];
            }
	        $patient->date_creation		= date('Y-m-d H:i:s', time());

	        //Les infos d'assurance s'ils existent
	        $typeAssurance = null;
	        if(!empty($data['type_assurance_id']) && ($data['type_assurance_id'] > 0) ){
                //Instance de l'organisme d'assurance
                $typeAssurance = TypeAssurance::findFirst($data['type_assurance_id']);

                if($data['id'] > 0){
                    $patientsAssurance = PatientsAssurance::findFirst(array("patients_id = :patients_id: AND type_assurance_id = :type_assurance_id:", 
                                        "bind" => array("patients_id" => $data['id'], "type_assurance_id" => $data['type_assurance_id']) ));
                    if(!$patientsAssurance){
                        $patientsAssurance = new PatientsAssurance();
                    }
                    $patientsAssurance->numero              = $data['numero_assurance'];
                    $patientsAssurance->beneficiaire        = $data['beneficiaire'];
                    $patientsAssurance->ogd                 = $data['ogd'];
                    $patientsAssurance->autres_infos        = $data['autres_infos'];
                    $patientsAssurance->type_assurance_id   = $data['type_assurance_id'];
                    $patientsAssurance->patients_id         = $data['id'];
                    $patientsAssurance->save();
                }
                else{
                    $patientsAssurance[0] = new PatientsAssurance();
                    $patientsAssurance[0]->numero              = $data['numero_assurance'];
                    $patientsAssurance[0]->beneficiaire        = $data['beneficiaire'];
                    $patientsAssurance[0]->ogd                 = $data['ogd'];
                    $patientsAssurance[0]->autres_infos        = $data['autres_infos'];
                    $patientsAssurance[0]->type_assurance_id   = $data['type_assurance_id'];
                    $patient->patientsAssurance                = $PatientsAssurance[0];
                }       
            }

	        //Le ticket
	        $recuMedicament = new RecuMedicament();
	        $recuMedicament->patients			= $patient;
	        $recuMedicament->date				= date('Y-m-d H:i:s', time());
	        $recuMedicament->montant_recu		= $data['montant_recu'];
	        $recuMedicament->montant_normal		= $data['total'];
	        $recuMedicament->montant_patient	= $data['total_a_payer'];
	        $recuMedicament->montant_restant	= $data['total_reste'];
	        $recuMedicament->user_id			= $this->session->get('usr')['id'];
	        $recuMedicament->montant_normal		= $data['total'];
	        $recuMedicament->typeAssurance			= $typeAssurance;
			$recuMedicament->type_assurance_taux	= $data['type_assurance_taux'];
            if(isset($data['numero_assurance'])){
                $recuMedicament->numero             = $data['numero_assurance'];
                $recuMedicament->ogd                = $data['ogd'];
                $recuMedicament->beneficiaire       = $data['beneficiaire'];
            }

	        //Les details du ticket
	        $recuMedicamentDetails = array();
	        $consultationListeAttente = array();
	        $laboListeAttente = array();
	        $imagerieListeAttente = array();

	        $i = 0;
	        foreach ($data['idproduit'] as $key => $value) {
	        	//on recupere les stock par lot et date de peremtion la plus proche
	        	$stockPointDistribution = StockPointDistribution::find( 
	        											array("reste > 0 AND produit_id =  :produit_id:",
	        													"order" => "date_peremption asc",
	        													"bind" => array("produit_id" => $value) 
	        												) );
		        $reste = 0;
		        foreach($stockPointDistribution as $k => $lot) {
		        	if($reste<0){
		        		$reste		= $lot->reste - abs($reste);
						$resultat	= ($reste<0) ? 0 : $reste;
						$lot->reste = $resultat;
						$lot->save();
		        	}
		        	else{
		        		$reste		= $lot->reste - $data['quantite'][$key];
						$resultat	= ($reste<0) ? 0 : $reste;
						$lot->reste = $resultat;
						$lot->save();
		        	}
		        	if($reste>=0) break;
		        }
	        	//On instancie le produit
	        	$produit = Produit::findFirst($value);

	        	$recuMedicamentDetails[$key] = new RecuMedicamentDetails();
	        	$recuMedicamentDetails[$key]->produit = $produit;
	        	$recuMedicamentDetails[$key]->montant_normal = $data['montant_total_produit'][$key];
	        	$recuMedicamentDetails[$key]->montant_unitaire = $data['prix'][$key];
	        	if($data['type_assurance_taux'] > 0){
	        		$recuMedicamentDetails[$key]->montant_patient = ( $data['montant_total_produit'][$key] - ( $data['montant_total_produit'][$key] * ($data['type_assurance_taux']/100) ) );
	        	}
	        	else{
	        		$recuMedicamentDetails[$key]->montant_patient = $recuMedicamentDetails[$key]->montant_normal;
	        	}
	        	$recuMedicamentDetails[$key]->montant_restant = $recuMedicamentDetails[$key]->montant_normal 
                                                                - 
						        								$recuMedicamentDetails[$key]->montant_patient;
	        	$recuMedicamentDetails[$key]->quantite = $data['quantite'][$key];

	        	//Mise a jour du stock general
	        	$produit->stock = $produit->stock - $data['quantite'][$key];
	        	$produit->save();
                
                //on verifie si le prduit es en rupture
                if($produit->stock <= 0){
                    $this->createRuptureStock($produit);
                }

	        	$i++;
	        }

	        $recuMedicament->recuMedicamentDetails = $recuMedicamentDetails;

	        //On enregistre tous ça de façon magic AHAH!
	        if(!$recuMedicament->save()){
	        	foreach ($recuMedicament->getMessages() as $msg) {
	        		$this->flash->error($msg);
	        	}
	        	$this->flash->error($this->trans['Something went wrong on saving the ticket. please try again']);
            	$this->response->redirect("caissePharmacie/index");
            	return;
	        }

	        $this->flash->success($this->trans['Ticket enregistré avec success']);
            $this->response->redirect("print/recuMedicament/".$recuMedicament->id);
            return;
        }

        if($patient_id != 0){
            $patient = Patients::findFirst($patient_id);
            if($patient){                
                Phalcon\Tag::setDefaults(array(
                    "id" => $patient->id,
                    "nom" => $patient->nom,
                    "prenom" => $patient->prenom,
                    "prenom2" => $patient->prenom2,
                    "nom_conjoint" => $patient->nom_conjoint,
                    "nom_pere" => $patient->nom_pere,
                    "contact_pere" => $patient->contact_pere,
                    "nom_mere" => $patient->nom_mere,
                    "contact_mere" => $patient->contact_mere,
                    "personne_a_prev" => $patient->personne_a_prev,
                    "nom_jeune_fille" => $patient->nom_jeune_fille,
                    "date_naissance" => $patient->date_naissance,
                    "sexe" => $patient->sexe,
                    "adresse" => $patient->adresse,
                    "residence_id" => $patient->residence_id,
                    "ethnie" => $patient->ethnie,
                    "profession" => $patient->profession,
                    "telephone" => $patient->telephone,
                    "telephone2" => $patient->telephone2,
                    "email" => $patient->email,
                    "autre_infos" => $patient->autre_infos
                ));
            }
        }

    }

    public function changePointDeVenteAction($id) {
        $this->view->disable();

        $pointDistribution = PointDistribution::findFirst("id = " . $id);

        $this->session->set('pointDeVente', array(
            'id' => $pointDistribution->id,
            'libelle' => $pointDistribution->libelle
        ));

        echo $id;
        exit();
    }

    public function openOrdonnanceListAction($point_distribution_id = 0, $patients_id = 0) {
        $this->view->disable();

        $pharmacieWorkFlow  = PharmacieWorkFlow::find(
                                array("patients_id = " . $patients_id, "order" => "id DESC", "limit" => 5) );
        $rs = [];
        for($i = 0; $i < count($pharmacieWorkFlow); $i++) {
            $tmp_patient = $pharmacieWorkFlow[$i]->getPatients();
            $rs[$i]['patient_id']       = $tmp_patient->id;
            $rs[$i]['patient_nom']      = $tmp_patient->nom . " " . $tmp_patient->prenom;
            $rs[$i]['ordonnance_id']    = $pharmacieWorkFlow[$i]->entity_type . "_" . $pharmacieWorkFlow[$i]->entity_id;

            /*$rs[$i]['not_available']    = $pharmacieWorkFlow[$i]->not_available;
            $rs[$i]['available']        = $pharmacieWorkFlow[$i]->available;*/
            $rs[$i]['produits_available']       = array();
            $rs[$i]['produits_not_available']   = array();
            if($pharmacieWorkFlow[$i]->available != ""){
                $tmpAvailable   = json_decode($pharmacieWorkFlow[$i]->available, true);
                foreach ($tmpAvailable as $key => $val) {
                    //On recupere l'id et la quantite
                    $p_id = array_keys($val)[0];
                    $p_qt = array_values($val)[0];

                    $rs[$i]['produits_available'][$key]["infos"]        = $this->getProduitInfos(Produit::findFirst($p_id));
                    $rs[$i]['produits_available'][$key]["quantite"]     = $p_qt;
                    //Recup du stock au niveau du point de distribution
                    $builder = $this->modelsManager->createBuilder();
                    $rq = $builder->columns("SUM(stockPointDistribution.reste) as qt")
                        ->addfrom('StockPointDistribution', 'stockPointDistribution')
                        ->andWhere( 'produit_id = '.$p_id.' AND point_distribution_id = ' . $point_distribution_id);
                    $rq = $builder->getQuery()->execute();
                    $rs[$i]['produits_available'][$key]["stockDispo"] = (count($rq)>0) ? $rq[0]['qt'] : 0;
                    $rs[$i]['produits_available'][$key]["stockDispo"] = (count($rq)>0) ? $rq[0]['qt'] : 0;
                    
                    $dispo      = $rs[$i]['produits_available'][$key]["stockDispo"];
                    $presc      = $rs[$i]['produits_available'][$key]["quantite"];
                    $poss       = ($dispo - $presc);
                    $a_ajouter  = ($poss < 0) ? 0 : $presc;

                    $rs[$i]['produits_available'][$key]["a_ajouter"]    = $a_ajouter;
                }
            }
            if($pharmacieWorkFlow[$i]->not_available != ""){
                $tmpNotAvailable   = json_decode($pharmacieWorkFlow[$i]->not_available, true);
                foreach ($tmpNotAvailable as $k => $v) {
                    //On recupere l'id et la quantite
                    $rs[$i]['produits_not_available'][$k]["libelle"] = array_keys($v)[0];
                    $rs[$i]['produits_not_available'][$k]["quantite"] = array_values($v)[0];
                }
            }
        }
        //print json_encode($rs);exit();
        
        $this->view->result  = $rs;
        $this->view->partial('caisse_pharmacie/openOrdonnanceList');
    }

    public function cancelTicketAction($id, $motif){
        $this->view->disable();

        $recuMedicament = RecuMedicament::findFirst($id);
        if(!$recuMedicament){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            $recuMedicament->etat = 0;
            $recuMedicament->date_annulation = date("Y-m-d H:i:s");
            $recuMedicament->motif_annulation = $motif;
            $recuMedicament->user_id_annulation = $this->session->get('usr')['id'];

            if (!$recuMedicament->save()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
                $this->flash->warning($this->trans['Le reçu a été annulé avec succès. Veuillez retouner les produits en faisant des ajustements en ajout.']);
            }
        }
        echo 0;exit();
    }

    public function etatRecuAction(){
    	
    	$date1 = $date2 = date("Y-m-d");
        $etat = 1;
        if($this->request->get("date1")){
            $data = $this->request->get();
            if($data['date1'] != "" && $data['date2'] != ""){
                $date1 = $data['date1'];
                $date2 = $data['date2'];
            }
            $etat = $data['etat'];
        }
        else{
            $date1 = $date2 = date("Y-m-d");
        }

        Phalcon\Tag::setDefaults(array(
                "date1" => $date1,
                "date2" => $date2,
                "etat" => $etat
        ));

        $builder = $this->modelsManager->createBuilder();
        $recus = $builder->columns("recuMedicament.id, recuMedicament.date, recuMedicament.montant_normal, recuMedicament.montant_patient, recuMedicament.montant_restant, recuMedicament.etat, 
        		patients.id as patient_id, CONCAT(patients.prenom, ' ', patients.nom) as patients_nom, 
        		usercaissier.id as caissier_id, CONCAT(usercaissier.prenom, ' ', usercaissier.nom) as caissier_nom, 
        		typeAssurance.libelle as assurance_libelle, typeAssurance.taux as assurance_taux, motif_annulation")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->join('Patients', 'patients.id = recuMedicament.patients_id', 'patients', 'INNER')
            ->join('User', 'usercaissier.id = recuMedicament.user_id', 'usercaissier', 'INNER')
            ->join('TypeAssurance', 'typeAssurance.id = recuMedicament.type_assurance_id', 'typeAssurance', 'LEFT')
            ->andWhere( 'etat = :etat: AND date(recuMedicament.date) between :date1: AND :date2:',
        								 array('etat' => $etat, 'date1' => $date1, 'date2' => $date2) );

        $recus = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();

        $this->view->recus   = json_encode($recus, JSON_PRETTY_PRINT);
    }

    public function etatRecuDetailsAction(){
        
        $date1 = $date2 = date("Y-m-d");
        $etat = 1;
        if($this->request->get("date1")){
            $data = $this->request->get();
            if($data['date1'] != "" && $data['date2'] != ""){
                $date1 = $data['date1'];
                $date2 = $data['date2'];
            }
            $etat = $data['etat'];
        }
        else{
            $date1 = $date2 = date("Y-m-d");
        }

        Phalcon\Tag::setDefaults(array(
                "date1" => $date1,
                "date2" => $date2,
                "etat" => $etat
        ));

        $builder = $this->modelsManager->createBuilder();
        $recus = $builder->columns("recuMedicament.id, recuMedicament.date, recuMedicament.montant_normal, recuMedicament.montant_patient, recuMedicament.montant_restant, recuMedicament.etat, 
                patients.id as patient_id, CONCAT(patients.prenom, ' ', patients.nom) as patients_nom, 
                usercaissier.id as caissier_id, CONCAT(usercaissier.prenom, ' ', usercaissier.nom) as caissier_nom, 
                typeAssurance.libelle as assurance_libelle, typeAssurance.taux as assurance_taux,
                 recuMedicamentDetails.montant_normal as d_montant_normal, 
                recuMedicamentDetails.quantite as d_quantite, 
                recuMedicamentDetails.montant_patient as d_montant_patient, 
                (recuMedicamentDetails.montant_normal - recuMedicamentDetails.montant_patient ) as d_reste,
                CONCAT(produit.libelle, '-', IFNULL(formeProduit.libelle, ' '), '-', produit.dosage) as p_libelle")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->join('RecuMedicamentDetails', 'recuMedicamentDetails.recu_medicament_id = recuMedicament.id', 'recuMedicamentDetails', 'INNER')
            ->join('Produit', 'produit.id = recuMedicamentDetails.produit_id', 'produit', 'INNER')
            ->join('FormeProduit', 'formeProduit.id = produit.forme_produit_id', 'formeProduit', 'LEFT')
            ->join('Patients', 'patients.id = recuMedicament.patients_id', 'patients', 'INNER')
            ->join('User', 'usercaissier.id = recuMedicament.user_id', 'usercaissier', 'INNER')
            ->join('TypeAssurance', 'typeAssurance.id = recuMedicament.type_assurance_id', 'typeAssurance', 'LEFT')
            ->andWhere( 'recuMedicament.etat = :etat: AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('etat' => $etat, 'date1' => $date1, 'date2' => $date2) );

        $recus = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();

        $this->view->recus   = json_encode($recus, JSON_PRETTY_PRINT);
    }

    public function dashboardAction(){

        if($this->request->get("date1")){
            $data = $this->request->get();
            if($data['date1'] != "" && $data['date2'] != ""){
                $date1 = $data['date1'];
                $date2 = $data['date2'];
            }
        }
        else{
            $date1 = date('Y-m-d',strtotime("last Monday"));
            $date2 = date("Y-m-d");
        }

        Phalcon\Tag::setDefaults(array(
                "date1" => $date1,
                "date2" => $date2
        ));

        //Nombre de vente
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("count(recuMedicament.id) as nbr")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->andWhere( 'date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->nbrVente = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //Nombre de vente annule
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("count(recuMedicament.id) as nbr")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->andWhere( 'recuMedicament.date_annulation IS NOT NULL AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->nbrVenteAnnule = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //Montant encaissé
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(recuMedicament.montant_patient) as montant")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->andWhere( 'recuMedicament.etat = 1 AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->montantEncaissePh = (count($rq)>0) ? $rq[0]['montant'] : 0;

        //Montant total
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(recuMedicament.montant_normal) as montant")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->andWhere( 'recuMedicament.etat = 1 AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->montantTotalPh = (count($rq)>0) ? $rq[0]['montant'] : 0;
        
        //Montant assureur
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(recuMedicament.montant_restant) as montant")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->andWhere( 'recuMedicament.etat = 1 AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->montantAssureur = (count($rq)>0) ? $rq[0]['montant'] : 0;

        //Lot proche de la peremption
        $stockPointDistribution = StockPointDistribution::find("reste > 0 AND date_peremption < '" . date('Y-m-d',strtotime("+3 months")) . "'");
        $rs = [];
        for($i = 0; $i < count($stockPointDistribution); $i++) {
            $rs[$i]['id']                   = $stockPointDistribution[$i]->id;
            $rs[$i]['point_distribution']   = $stockPointDistribution[$i]->getPointDistribution()->libelle;
            $rs[$i]['lot']                  = $stockPointDistribution[$i]->lot;
            $rs[$i]['stock']                = $stockPointDistribution[$i]->stock;
            $rs[$i]['reste']                = $stockPointDistribution[$i]->reste;
            $rs[$i]['date_peremption']      = $stockPointDistribution[$i]->date_peremption;

            $produit                     = $this->getProduitInfos($stockPointDistribution[$i]->getProduit());
            $rs[$i]['produit_id']        = $produit['id'];
            $rs[$i]['produit_libelle']   = $produit['libelle'];
            $rs[$i]['produit_dosage']    = $produit['dosage'];
            $rs[$i]['produit_type']      = $produit['type'];
            $rs[$i]['produit_forme']     = $produit['forme'];
            $rs[$i]['produit_classe_th'] = $produit['classe_th'];
        }
        $this->view->nbrLotPeremption = count($rs);
        $this->view->lotPeremption = json_encode($rs, JSON_PRETTY_PRINT);

        //Chiffre d'affaire mensuelle
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("YEAR(recuMedicament.date) as annee, MONTH(recuMedicament.date) as mois_chiffre, MONTHNAME(recuMedicament.date) as mois, SUM(recuMedicament.montant_normal) as montant")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->andWhere( 'recuMedicament.etat = 1 AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => date('Y-m-d',strtotime("-12 months")), 'date2' => date("Y-m-d")) )
            ->groupBy('annee, mois, mois_chiffre')
            ->orderBy('annee asc, mois_chiffre ASC');
        $rq = $builder->getQuery()->execute();
        $rs = array();
        for ($i = 0; $i < count($rq); $i++) {
            $rq[$i]->color = $this->colorsPalette[$i];
            $rs[$i]['mois'] = $rq[$i]->mois;
            $rs[$i]['montant'] = $rq[$i]->montant;
        }
        $this->view->chiffreMensuelle = json_encode($rs, JSON_PRETTY_PRINT);

        //Top 20 des recu par produit
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("CONCAT(produit.libelle, '-', IFNULL(formeProduit.libelle, ' '), '-', produit.dosage) as produit_libelle, count(recuMedicament.id) as nbr")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->join('RecuMedicamentDetails', 'recuMedicamentDetails.recu_medicament_id = recuMedicament.id', 'recuMedicamentDetails', 'INNER')
            ->join('Produit', 'produit.id = recuMedicamentDetails.produit_id', 'produit', 'INNER')
            ->join('FormeProduit', 'formeProduit.id = produit.forme_produit_id', 'formeProduit', 'LEFT')
            ->andWhere( 'recuMedicament.etat = 1 AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) )
            ->limit('20')
            ->groupBy('produit_libelle');
        $rq = $builder->getQuery()->execute();
        $rs = array();
        for ($i = 0; $i < count($rq); $i++) {
            $rq[$i]->color = $this->colorsPalette[$i];
            $rs[$i] = $rq[$i];
        }
        $this->view->top20ProduitGraph = json_encode($rs, JSON_PRETTY_PRINT);

        //En dessous du seuil min
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("count(produit.id) as nbr")
            ->addfrom('Produit', 'produit')
            ->andWhere('produit.stock < produit.seuil_min');
        $rq = $builder->getQuery()->execute();
        $this->view->infSeuilMin = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //En rupture de stock
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("count(produit.id) as nbr")
            ->addfrom('Produit', 'produit')
            ->andWhere('produit.stock = 0');
        $rq = $builder->getQuery()->execute();
        $this->view->nbrRuptureStock = (count($rq)>0) ? $rq[0]['nbr'] : 0;
        
        //Pointage des produits
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("CONCAT(produit.libelle, '-', IFNULL(formeProduit.libelle, ' '), '-', produit.dosage) as produit_libelle, SUM(recuMedicamentDetails.montant_patient) as montant, SUM(recuMedicamentDetails.quantite) as quantite")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->join('RecuMedicamentDetails', 'recuMedicamentDetails.recu_medicament_id = recuMedicament.id', 'recuMedicamentDetails', 'INNER')
            ->join('Produit', 'produit.id = recuMedicamentDetails.produit_id', 'produit', 'INNER')
            ->join('FormeProduit', 'formeProduit.id = produit.forme_produit_id', 'formeProduit', 'LEFT')
            ->andWhere( 'recuMedicament.etat = 1 AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('produit_libelle');
        $rq = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();
        $this->view->pointageProduit = json_encode($rq, JSON_PRETTY_PRINT);

        //Pointage des produits par forme
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("formeProduit.libelle as forme_libelle, SUM(recuMedicamentDetails.montant_patient) as montant, SUM(recuMedicamentDetails.quantite) as quantite")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->join('RecuMedicamentDetails', 'recuMedicamentDetails.recu_medicament_id = recuMedicament.id', 'recuMedicamentDetails', 'INNER')
            ->join('Produit', 'produit.id = recuMedicamentDetails.produit_id', 'produit', 'INNER')
            ->join('FormeProduit', 'formeProduit.id = produit.forme_produit_id', 'formeProduit', 'INNER')
            ->andWhere( 'recuMedicament.etat = 1 AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('forme_libelle');
        $rq = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();
        $this->view->pointageProduitByForme = json_encode($rq, JSON_PRETTY_PRINT);

        //Pointage des produits par classe therapeutique
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("classeTherapeutique.libelle as classeTh_libelle, SUM(recuMedicamentDetails.montant_patient) as montant, SUM(recuMedicamentDetails.quantite) as quantite")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->join('RecuMedicamentDetails', 'recuMedicamentDetails.recu_medicament_id = recuMedicament.id', 'recuMedicamentDetails', 'INNER')
            ->join('Produit', 'produit.id = recuMedicamentDetails.produit_id', 'produit', 'INNER')
            ->join('ClasseTherapeutique', 'classeTherapeutique.id = produit.classe_therapeutique_id', 'classeTherapeutique', 'INNER')
            ->andWhere( 'recuMedicament.etat = 1 AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('classeTh_libelle');
        $rq = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();
        $this->view->pointageProduitByClasseTh = json_encode($rq, JSON_PRETTY_PRINT);

        //Vente par residence
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("residence.libelle as residence, sum(recuMedicament.montant_patient) as montant")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->join('RecuMedicamentDetails', 'recuMedicamentDetails.recu_medicament_id = recuMedicament.id', 'recuMedicamentDetails', 'INNER')
            ->join('Patients', 'recuMedicament.patients_id = patients.id', 'patients', 'INNER')
            ->join('Residence', 'patients.residence_id = residence.id', 'residence', 'INNER')
            ->andWhere( 'recuMedicament.etat = 1 AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('residence');
        $rq = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();
        $this->view->venteParResidences = json_encode($rq, JSON_PRETTY_PRINT);

        //Vente par organisme
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("typeAssurance.libelle as organisme, CONCAT(typeAssurance.taux, '%') as taux, sum(recuMedicament.montant_restant) as part_organisme, sum(recuMedicament.montant_patient) as part_assure")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->join('TypeAssurance', 'typeAssurance.id = recuMedicament.type_assurance_id', 'typeAssurance', 'INNER')
            ->andWhere( 'recuMedicament.etat = 1 AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('organisme, taux');
        $rq = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();
        $this->view->venteParOrganismeAssurances = json_encode($rq, JSON_PRETTY_PRINT);

    }

}
