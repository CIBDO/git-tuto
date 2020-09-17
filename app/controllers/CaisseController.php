<?php
use Phalcon\Mvc\Model\Resultset;
class CaisseController extends ControllerBase {

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
        $this->view->prestataires = User::find("prestataire = 1");
        $this->view->residences = Residence::find();

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
            $patient->nom 				= strtoupper($data['nom']);
            $patient->prenom 			= $data['prenom'];
            $patient->date_naissance 	= $data['date_naissance'];
            $patient->sexe 				= $data['sexe'];
            $patient->adresse 			= $data['adresse'];
            $patient->num_quittance		= $data['num_quittance'];
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
	        $prestation = new Prestations();
	        $prestation->patients			= $patient;
            $prestation->date               = date('Y-m-d H:i:s', time());
	        $prestation->etat				= "1";
	        $prestation->montant_recu		= $data['montant_recu'];
            $prestation->montant_normal     = $data['total'];
	        $prestation->montant_difference = ( isset($data['total_difference']) ) ? $data['total_difference'] : 0;
	        $prestation->montant_patient	= $data['total_a_payer'];
	        $prestation->montant_restant	= $data['total_reste'];
	        $prestation->user_id			= $this->session->get('usr')['id'];
	        $prestation->montant_normal		= $data['total'];
	        $prestation->typeAssurance			= $typeAssurance;
            $prestation->type_assurance_taux    = $data['type_assurance_taux'];
            if(isset($data['numero_assurance'])){
                $prestation->numero             = $data['numero_assurance'];
                $prestation->ogd                = $data['ogd'];
    			$prestation->beneficiaire       = $data['beneficiaire'];
            }

	        //Les details du ticket
	        $prestationsDetails        = array();
	        $consultationListeAttente  = array();
	        $laboItem                  = array();
	        $imgItem                   = array();

	        $i = 0;
	        foreach ($data['idacte'] as $key => $value) {
	        	//On instancie l'acte
	        	$actes = Actes::findFirst($value);

	        	$prestationsDetails[$key] = new PrestationsDetails();
	        	$prestationsDetails[$key]->actes = $actes;
	        	$prestationsDetails[$key]->montant_normal     = $data['montant_total_acte'][$key];
                $prestationsDetails[$key]->montant_unitaire   = $data['montant_unitaire_acte'][$key];
	        	$prestationsDetails[$key]->montant_unitaire_difference   = $data['montant_unitaire_difference'][$key];
	        	if($data['type_assurance_taux'] > 0){
	        		$prestationsDetails[$key]->montant_patient = ( $data['montant_total_acte'][$key] - ( $data['montant_total_acte'][$key] * ($data['type_assurance_taux']/100) ) );
	        	}
	        	else{
	        		$prestationsDetails[$key]->montant_patient = $prestationsDetails[$key]->montant_normal;
	        	}
	        	$prestationsDetails[$key]->montant_restant = $prestationsDetails[$key]->montant_normal
						        								- 
						        								$prestationsDetails[$key]->montant_patient;
	        	$prestationsDetails[$key]->quantite = $data['quantite'][$key];

	        	//On instancie le prestataire
                $prestationsDetails[$key]->user = null;
                $hasPrestataire = null;
	        	if( ($data['prestataire'][$key] != "") && ($data['prestataire'][$key] > 0) ){
                    /*$user = User::findFirst($data['prestataire'][$key]);*/
                    $prestationsDetails[$key]->user_id = $data['prestataire'][$key];
                    $hasPrestataire = $data['prestataire'][$key];
                }

	        	//On regarde le type de l'acte et on crée les listes d'attentes
	        	if( ($hasPrestataire != null) && ($actes->type == "consultation") ){
	        		//On place le patient dans la liste d'attente des consultation pour le medecin
	        		$consultationListeAttente[$i] 						= new ConsultationListeAttente();
	        		$consultationListeAttente[$i]->user_id 				= $hasPrestataire;
	        		$consultationListeAttente[$i]->prestationsDetails 	= $prestationsDetails[$key];
                    $consultationListeAttente[$i]->patients             = $patient;
	        		$consultationListeAttente[$i]->etat                 = 0;
			        $consultationListeAttente[$i]->date					= date('Y-m-d H:i:s', time());
	        	}
	        	if($actes->type == "labo" && $actes->code != ""){
	        		//On place le patient dans la liste d'attente des prelevements au labo
                    $tmpItem = LaboAnalyses::findFirst(array("code = '". $actes->code ."' AND labo_categories_analyse_id IS NOT NULL"));
                    if($tmpItem){
                        $laboItem[$i]               = new LaboDemandesDetails();
                        $laboItem[$i]->labo_analyses_id = $tmpItem->id;
                    }
	        	}
	        	if($actes->type == "imagerie"){
	        		//On place le patient dans la liste d'attente d'imagerie
                    $tmpItem = ImgItems::findFirst(array("code = '". $actes->code ."' AND img_items_categories_id IS NOT NULL"));
                    if($tmpItem){

                        $imgDemande                 = new ImgDemandes();
                        $imgDemande->date           = date("Y-m-d H:i:s");
                        $imgDemande->patients       = $patient;
                        $imgDemande->prestations    = $prestation;
                        $imgDemande->user_id        = $hasPrestataire;
                        $imgDemande->etat           = "création";

                        $imgDemandeDetails                      = new ImgDemandesDetails();
                        $imgDemandeDetails->img_items_id        = $tmpItem->id;
                        $imgDemandeDetails->imgDemandesDetails  = $imgItem;

                        $imgDemande->imgDemandesDetails = $imgDemandeDetails;

                        $imgItem[] = $imgDemande;
                    }
	        	}

	        	$i++;
	        }

	        $prestation->prestationsDetails = $prestationsDetails;

	        //On enregistre tous ça de façon magic AHAH!
	        if(!$prestation->save()){
	        	$this->flash->error($this->trans['Something went wrong on saving the ticket. please try again']);
            	$this->response->redirect("caisse/index");
            	return;
	        }
	        else{
	        	//Si tout est ok jusquici alors on enregistre les listes d'attente
	        	foreach ($consultationListeAttente as $item) {
	        		$item->save();
	        	}
	        	if (count($laboItem) > 0) {
	        		$laboDemande                        = new LaboDemandes();
                    $laboDemande->date                  = date("Y-m-d H:i:s");
                    $laboDemande->patients              = $patient;
                    $laboDemande->prestations           = $prestation;
                    $laboDemande->etat                  = "création";
                    $laboDemande->paillasse             = $this->getLaboPaillasse();
                    $laboDemande->laboDemandesDetails   = $laboItem;

                    if(!$laboDemande->save()){
                        $this->flash->error($this->trans["La demande d'analyse n'a pas été correctement enregistrement pour le labo"]);
                    }
	        	}
                foreach ($imgItem as $iItem) {
                    $iItem->save();
                }
	        }

	        $this->flash->success($this->trans['Ticket enregistré avec succès']);
            $this->response->redirect("print/ticket/".$prestation->id);
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

    public function cancelTicketAction($id, $motif){
        $this->view->disable();

        $prestations = Prestations::findFirst($id);
        if(!$prestations){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            $prestations->etat = 0;
            $prestations->date_annulation = date("Y-m-d H:i:s");
            $prestations->motif_annulation = $motif;
            $prestations->user_id_annulation = $this->session->get('usr')['id'];

            if (!$prestations->save()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }

    public function etatTicketAction(){
        
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
        $tickets = $builder->columns("prestations.id, prestations.date, prestations.montant_normal, prestations.montant_difference, prestations.montant_patient, prestations.montant_restant, prestations.etat, prestations.motif_annulation, 
        		patients.id as patient_id, CONCAT(patients.prenom, ' ', patients.nom) as patients_nom, 
        		usercaissier.id as caissier_id, CONCAT(usercaissier.prenom, ' ', usercaissier.nom) as caissier_nom, 
        		typeAssurance.libelle as assurance_libelle, CONCAT(typeAssurance.taux, '%') as assurance_taux")
            ->addfrom('Prestations', 'prestations')
            ->join('Patients', 'patients.id = prestations.patients_id', 'patients', 'INNER')
            ->join('User', 'usercaissier.id = prestations.user_id', 'usercaissier', 'INNER')
            ->join('TypeAssurance', 'typeAssurance.id = prestations.type_assurance_id', 'typeAssurance', 'LEFT')
            ->andWhere( 'etat = :etat: AND date(prestations.date) between :date1: AND :date2:',
        								 array('etat' => $etat, 'date1' => $date1, 'date2' => $date2) );

        $tickets = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();

        $this->view->tickets   = json_encode($tickets, JSON_PRETTY_PRINT);
    }

    public function etatTicketDetailsAction(){
        
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
        $tickets = $builder->columns("prestations.id, prestations.date, prestations.montant_normal, prestations.montant_patient, prestations.montant_restant, prestations.etat, 
                patients.id as patient_id, CONCAT(patients.prenom, ' ', patients.nom) as patients_nom, 
                usercaissier.id as caissier_id, CONCAT(usercaissier.prenom, ' ', usercaissier.nom) as caissier_nom, CONCAT(userPrestataire.prenom, ' ', userPrestataire.nom) as prestataire_nom, 
                typeAssurance.libelle as assurance_libelle, CONCAT(typeAssurance.taux, '%') as assurance_taux,
                prestationsDetails.montant_normal as d_montant_normal, 
                (prestationsDetails.montant_unitaire_difference * prestationsDetails.quantite) as d_montant_difference, 
                ( (prestationsDetails.montant_unitaire_difference * prestationsDetails.quantite) + prestationsDetails.montant_patient) as d_montant_percu, 
                prestationsDetails.quantite as d_quantite, 
                prestationsDetails.montant_patient as d_montant_patient, 
                (prestationsDetails.montant_normal - prestationsDetails.montant_patient ) as d_reste, 
                actes.libelle as a_libelle, 
                actes.unite as a_unite 
                ")
            ->addfrom('Prestations', 'prestations')
            ->join('PrestationsDetails', 'prestationsDetails.prestations_id = prestations.id', 'prestationsDetails', 'INNER')
            ->join('User', 'userPrestataire.id = prestationsDetails.user_id', 'userPrestataire', 'INNER')
            ->join('Actes', 'actes.id = prestationsDetails.actes_id', 'actes', 'INNER')
            ->join('Patients', 'patients.id = prestations.patients_id', 'patients', 'INNER')
            ->join('User', 'usercaissier.id = prestations.user_id', 'usercaissier', 'INNER')
            ->join('TypeAssurance', 'typeAssurance.id = prestations.type_assurance_id', 'typeAssurance', 'LEFT')
            ->andWhere( 'prestations.etat = :etat: AND date(prestations.date) between :date1: AND :date2:',
                                         array('etat' => $etat, 'date1' => $date1, 'date2' => $date2) );

        $tickets = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();

        $this->view->tickets   = json_encode($tickets, JSON_PRETTY_PRINT);
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
        $rq = $builder->columns("count(prestations.id) as nbr")
            ->addfrom('Prestations', 'prestations')
            ->andWhere( 'date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->nbrVente = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //Montant total
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(prestations.montant_normal) as montant")
            ->addfrom('Prestations', 'prestations')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->montantTotal = (count($rq)>0) ? $rq[0]['montant'] : 0;

        //Montant difference
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(prestations.montant_difference) as montant")
            ->addfrom('Prestations', 'prestations')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->montantDifference = (count($rq)>0) ? $rq[0]['montant'] : 0;
        
        //Montant encaissé
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(prestations.montant_patient) as montant")
            ->addfrom('Prestations', 'prestations')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->montantEncaisse = (count($rq)>0) ? $rq[0]['montant'] : 0;

        //Montant assureur
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(prestations.montant_restant) as montant")
            ->addfrom('Prestations', 'prestations')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->montantAssureur = (count($rq)>0) ? $rq[0]['montant'] : 0;

        //Nombre de vente annule
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("count(prestations.id) as nbr")
            ->addfrom('Prestations', 'prestations')
            ->andWhere( 'prestations.date_annulation IS NOT NULL AND date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->nbrVenteAnnule = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //Vente par unité
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("actes.unite as unite, sum(prestations.montant_patient) as montant")
            ->addfrom('Prestations', 'prestations')
            ->join('PrestationsDetails', 'prestationsDetails.prestations_id = prestations.id', 'prestationsDetails', 'INNER')
            ->join('Actes', 'actes.id = prestationsDetails.actes_id', 'actes', 'INNER')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('unite');
        $rq = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();
        $this->view->venteParUnites = json_encode($rq, JSON_PRETTY_PRINT);

        //Vente par service
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("services.libelle as service, sum(prestations.montant_patient) as montant")
            ->addfrom('Prestations', 'prestations')
            ->join('PrestationsDetails', 'prestationsDetails.prestations_id = prestations.id', 'prestationsDetails', 'INNER')
            ->join('Actes', 'actes.id = prestationsDetails.actes_id', 'actes', 'INNER')
            ->join('Services', 'services.id = actes.services_id', 'services', 'INNER')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('service');
        $rq = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();
        $this->view->venteParServices = json_encode($rq, JSON_PRETTY_PRINT);

        //Vente par prestation
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("actes.libelle as acte_libelle, actes.unite as unite, SUM(prestationsDetails.quantite) as nbr, sum(prestationsDetails.montant_patient) as montant")
            ->addfrom('Prestations', 'prestations')
            ->join('PrestationsDetails', 'prestationsDetails.prestations_id = prestations.id', 'prestationsDetails', 'INNER')
            ->join('Actes', 'actes.id = prestationsDetails.actes_id', 'actes', 'INNER')
            ->join('Services', 'services.id = actes.services_id', 'services', 'INNER')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('acte_libelle, unite');
        $rq = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();
        $this->view->venteParActes = json_encode($rq, JSON_PRETTY_PRINT);

        //Vente par organisme
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("typeAssurance.libelle as organisme, CONCAT(typeAssurance.taux, '%') as taux, sum(prestations.montant_restant) as part_organisme, sum(prestations.montant_patient) as part_assure")
            ->addfrom('Prestations', 'prestations')
            ->join('TypeAssurance', 'typeAssurance.id = prestations.type_assurance_id', 'typeAssurance', 'INNER')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('organisme, taux');
        $rq = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();
        $this->view->venteParOrganismeAssurances = json_encode($rq, JSON_PRETTY_PRINT);

        //Top 10 des prestations par actes
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("actes.libelle as acte_libelle, SUM(prestationsDetails.quantite) as nbr")
            ->addfrom('Prestations', 'prestations')
            ->join('PrestationsDetails', 'prestationsDetails.prestations_id = prestations.id', 'prestationsDetails', 'INNER')
            ->join('Actes', 'actes.id = prestationsDetails.actes_id', 'actes', 'INNER')
            ->join('Services', 'services.id = actes.services_id', 'services', 'INNER')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) )
            ->limit('10')
            ->orderBy('nbr DESC')
            ->groupBy('acte_libelle');
        $rq = $builder->getQuery()->execute();
        $rs = array();
        for ($i = 0; $i < count($rq); $i++) {
            $rq[$i]->color = $this->colorsPalette[$i];
            $rs[$i] = $rq[$i];
        }
        //print_r($rs);exit();
        $this->view->top10PrestationsGraph = json_encode($rs, JSON_PRETTY_PRINT);

        //Top 10 des prestations par service
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("services.libelle as service_libelle, count(prestations.id) as nbr")
            ->addfrom('Prestations', 'prestations')
            ->join('PrestationsDetails', 'prestationsDetails.prestations_id = prestations.id', 'prestationsDetails', 'INNER')
            ->join('Actes', 'actes.id = prestationsDetails.actes_id', 'actes', 'INNER')
            ->join('Services', 'services.id = actes.services_id', 'services', 'INNER')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) )
            ->limit('10')
            ->groupBy('service_libelle');
        $rq = $builder->getQuery()->execute();
        $rs = array();
        for ($i = 0; $i < count($rq); $i++) {
            $rq[$i]->color = $this->colorsPalette[$i];
            $rs[$i] = $rq[$i];
        }
        $this->view->top10ServicesGraph = json_encode($rs, JSON_PRETTY_PRINT);

        //Chiffre d'affaire mensuelle
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("YEAR(prestations.date) as annee, MONTH(prestations.date) as mois_chiffre, MONTHNAME(prestations.date) as mois, SUM(prestations.montant_normal) as montant")
            ->addfrom('Prestations', 'prestations')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:',
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

        //Vente par residence
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("residence.libelle as residence, sum(prestations.montant_patient) as montant")
            ->addfrom('Prestations', 'prestations')
            ->join('PrestationsDetails', 'prestationsDetails.prestations_id = prestations.id', 'prestationsDetails', 'INNER')
            ->join('Patients', 'prestations.patients_id = patients.id', 'patients', 'INNER')
            ->join('Residence', 'patients.residence_id = residence.id', 'residence', 'INNER')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('residence');
        $rq = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();
        $this->view->venteParResidences = json_encode($rq, JSON_PRETTY_PRINT);
    	
    }

}
