<?php

use Phalcon\Mvc\Model\Resultset;

class PrintController extends ControllerBase {

    public function initialize() {

        parent::initialize();
        $this->view->setTemplateAfter('print');
        $this->tag->setTitle('Impression');

        $config = $this->getStructureConfig();

        $this->view->entete = $config;
        $this->view->referer    = $this->request->getHTTPReferer();

    }


    public function ticketAction($id = 0) {

    	if($id == 0){
    		$this->response->redirect("caisse/index");
    		return;
    	}

    	$prestation = Prestations::findFirst($id);
        $prestation->montant_difference = ($prestation->montant_difference == null) ? 0 : $prestation->montant_difference;
        $patient = $prestation->getPatients();
    	$emetteur = $prestation->getUserCaissier();
    	if(!empty($prestation->type_assurance_id) && ($prestation->type_assurance_id != null) )
    	{
            $this->view->prestation_organisme   = $prestation->getTypeAssurance()->libelle;
			/*$this->view->patient_assurance      = $patient->getPatientsAssurance(
                    array("type_assurance_id = :id:", "bind" => array("id" => $prestation->type_assurance_id) ) )[0];*/
    	}

    	$prestation_details = $prestation->getPrestationsdetails();
    	$rs = [];
        for($i = 0; $i < count($prestation_details); $i++) {
            $rs[$i]['id']                   = $prestation_details[$i]->id;
            $rs[$i]['montant_normal']       = $prestation_details[$i]->montant_normal;
            $rs[$i]['montant_unitaire_difference']   = $prestation_details[$i]->montant_unitaire_difference;
            $rs[$i]['montant_patient']      = $prestation_details[$i]->montant_patient;
            $rs[$i]['montant_unitaire']     = $prestation_details[$i]->montant_unitaire;
            $rs[$i]['quantite']             = $prestation_details[$i]->quantite;
            $rs[$i]['prestataire']          = ($prestation_details[$i]->user_id != null) ? 
                                                $prestation_details[$i]->getUser() : "" ;
            $rs[$i]['acte']                 = $prestation_details[$i]->getActes()->libelle;
            $rs[$i]['unite']                = $prestation_details[$i]->getActes()->unite;
        }
        $this->view->prestation_details	= $rs;

    	$this->view->patient 	= $patient;
        $this->view->prestation = $prestation;
        $this->view->montant_retourner = $prestation->montant_recu - $prestation->montant_patient;
    	$this->view->emetteur	= $emetteur;
        $this->view->referer    = $this->request->getHTTPReferer();
    }

    public function recuMedicamentAction($id = 0) {
        
        if($id == 0){
            $this->response->redirect("caisse_pharmacie/index");
            return;
        }

        $recuMedicament = RecuMedicament::findFirst($id);
        $emetteur       = $recuMedicament->getUserCaissierPharmacie();
        $patient        = $recuMedicament->getPatients();
        if(!empty($recuMedicament->type_assurance_id) && ($recuMedicament->type_assurance_id != null) )
        {
            $this->view->recuMedicament_organisme   = $recuMedicament->getTypeAssurance()->libelle;
            /*$this->view->patient_assurance          = $patient->getPatientsAssurance(
                    array("type_assurance_id = :id:", "bind" => array("id" => $recuMedicament->type_assurance_id) ) )[0];*/
        }

        $recuMedicament_details = $recuMedicament->getRecuMedicamentdetails();
        $rs = [];
        for($i = 0; $i < count($recuMedicament_details); $i++) {
            $rs[$i]['id']               = $recuMedicament_details[$i]->id;
            $rs[$i]['montant_normal']   = $recuMedicament_details[$i]->montant_normal;
            $rs[$i]['montant_patient']  = $recuMedicament_details[$i]->montant_patient;
            $rs[$i]['montant_unitaire'] = $recuMedicament_details[$i]->montant_unitaire;
            $rs[$i]['quantite']         = $recuMedicament_details[$i]->quantite;

            $produit                    = $this->getProduitInfos($recuMedicament_details[$i]->getProduit());

            $rs[$i]['produit_id']        = $produit['id'];
            $rs[$i]['produit_libelle']   = $produit['libelle'];
            $rs[$i]['produit_dosage']    = $produit['dosage'];
            $rs[$i]['produit_type']      = $produit['type'];
            $rs[$i]['produit_forme']     = $produit['forme'];
            $rs[$i]['produit_classe_th'] = $produit['classe_th'];
        }
        $this->view->recuMedicament_details = $rs;
        $this->view->patient                = $patient;
        $this->view->montant_retourner = $recuMedicament->montant_recu - $recuMedicament->montant_patient;
        $this->view->recuMedicament         = $recuMedicament;
        $this->view->emetteur               = $emetteur;
        $this->view->referer    = $this->request->getHTTPReferer();
    }


    public function detailsCommandeAction($id = 0) {

        if($id == 0){
            $this->flash->error("Veuillez choisir une commande pour effectuer une impression");
            return $this->response->redirect("commande/index");
        }
        $rsCommande         = array();
        $rsCommandeDetails  = array();

        //la commande
        $commande = Commande::findFirst($id);
        $rsCommande['id']           = $commande->id;
        $rsCommande['objet']        = $commande->objet;
        $rsCommande['date']         = date("d/m/Y", strtotime($commande->date));
        $rsCommande['fournisseur']  = $commande->getFournisseur()->libelle;
        $rsCommande['montant']      = $commande->montant;
        $rsCommande['etat']         = $commande->etat;
        
        //les details de la commande
        $commandeDetails = $commande->getCommandeDetails();
        for($i = 0; $i < count($commandeDetails); $i++) {
            $rsCommandeDetails[$i]['id']    = $commandeDetails[$i]->id;
            $produit                        = $this->getProduitInfos($commandeDetails[$i]->getProduit());

            $rsCommandeDetails[$i]['produit_id']        = $produit['id'];
            $rsCommandeDetails[$i]['produit_libelle']   = $produit['libelle'];
            $rsCommandeDetails[$i]['produit_dosage']    = $produit['dosage'];
            $rsCommandeDetails[$i]['produit_type']      = $produit['type'];
            $rsCommandeDetails[$i]['produit_forme']     = $produit['forme'];
            $rsCommandeDetails[$i]['produit_classe_th'] = $produit['classe_th'];

            $rsCommandeDetails[$i]['quantite']          = $commandeDetails[$i]->quantite;
            $rsCommandeDetails[$i]['quantite_livree']   = $commandeDetails[$i]->quantite_livree;
            $rsCommandeDetails[$i]['prix']              = $commandeDetails[$i]->prix;
        }
        

        $this->view->commande           = $rsCommande;
        $this->view->commandeDetails    = $rsCommandeDetails;
        $this->view->referer    = $this->request->getHTTPReferer();
    }

    public function detailsReceptionAction($id = 0) {

        if($id == 0){
            $this->flash->error("Veuillez choisir une reception pour afficher ses details");
            return $this->response->redirect("reception/index");
        }
        $rsReception        = array();
        $rsCommande         = array();
        $rsReceptionDetails = array();

        //la reception
        $reception = Reception::findFirst($id);
        $rsReception['id']           = $reception->id;
        $rsReception['objet']        = $reception->objet;
        $rsReception['date']         = date("d/m/Y", strtotime($reception->date));
        $rsReception['fournisseur']  = $reception->getFournisseur()->libelle;
        $rsReception['etat']         = $reception->etat;

        if($reception->commande_id != null && $reception->commande_id !=""){
            $tmpCommande = $reception->getCommande();
            $rsCommande['id']           = $tmpCommande->id;
            $rsCommande['objet']        = $tmpCommande->objet;
            $rsCommande['date']         = date("d/m/Y", strtotime($tmpCommande->date));
            $rsCommande['montant']      = $tmpCommande->montant;
            $rsCommande['etat']         = $tmpCommande->etat;
            $this->view->commande       = $rsCommande;
        }
        
        //les details de la reception
        $receptionDetails = $reception->getReceptionDetails();
        for($i = 0; $i < count($receptionDetails); $i++) {
            $rsReceptionDetails[$i]['id']    = $receptionDetails[$i]->id;
            $produit                        = $this->getProduitInfos($receptionDetails[$i]->getProduit());

            $rsReceptionDetails[$i]['produit_id']        = $produit['id'];
            $rsReceptionDetails[$i]['produit_libelle']   = $produit['libelle'];
            $rsReceptionDetails[$i]['produit_dosage']    = $produit['dosage'];
            $rsReceptionDetails[$i]['produit_type']      = $produit['type'];
            $rsReceptionDetails[$i]['produit_forme']     = $produit['forme'];
            $rsReceptionDetails[$i]['produit_classe_th'] = $produit['classe_th'];


            $rsReceptionDetails[$i]['quantite']         = $receptionDetails[$i]->quantite;
            $rsReceptionDetails[$i]['litige']           = $receptionDetails[$i]->litige;
            $rsReceptionDetails[$i]['manquant']         = $receptionDetails[$i]->manquant;
            $rsReceptionDetails[$i]['observation']      = $receptionDetails[$i]->observation;
            $rsReceptionDetails[$i]['lot']              = $receptionDetails[$i]->lot;
            $rsReceptionDetails[$i]['date_peremption']  =date("d/m/Y", strtotime($receptionDetails[$i]->date_peremption));
            
            $rsReceptionDetails[$i]['prix_achat']   = $receptionDetails[$i]->prix_achat;
            $rsReceptionDetails[$i]['coef'] = ($receptionDetails[$i]->coef == "" || $receptionDetails[$i]->coef == null) ? $config->pharmacie->default_coef : $receptionDetails[$i]->coef;
            $rsReceptionDetails[$i]['prix_vente']   = $receptionDetails[$i]->prix_vente;
        }
        
        $this->view->reception          = $rsReception;
        $this->view->receptionDetails   = $rsReceptionDetails; 
        $this->view->referer    = $this->request->getHTTPReferer();
    }

    public function detailsInventaireAction($id = 0) {

        if($id == 0){
            $this->flash->error("Veuillez choisir un inventaire pour afficher ses details");
            return $this->response->redirect("inventaire/index");
        }
        $rsInventaire        = array();
        $rsInventaireDetails = array();

        //la inventaire
        $inventaire = Inventaire::findFirst($id);
        $rsInventaire['id']     = $inventaire->id;
        $rsInventaire['objet']  = $inventaire->objet;
        $rsInventaire['date']   = date("d/m/Y", strtotime($inventaire->date));
        $rsInventaire['debut']  = date("d/m/Y", strtotime($inventaire->debut));
        $rsInventaire['debut_times']  = strtotime($inventaire->debut);
        $rsInventaire['fin']    = date("d/m/Y", strtotime($inventaire->fin));
        $rsInventaire['fin_times']    = strtotime($inventaire->fin);
        $rsInventaire['etat']   = $inventaire->etat;
        
        //les details de la inventaire
        $inventaireDetails = $inventaire->getInventaireDetails();
        for($i = 0; $i < count($inventaireDetails); $i++) {
            $rsInventaireDetails[$i]['id']    = $inventaireDetails[$i]->id;
            $produit                        = $this->getProduitInfos($inventaireDetails[$i]->getProduit());

            $rsInventaireDetails[$i]['produit_id']        = $produit['id'];
            $rsInventaireDetails[$i]['produit_libelle']   = $produit['libelle'];
            $rsInventaireDetails[$i]['produit_dosage']    = $produit['dosage'];
            $rsInventaireDetails[$i]['produit_type']      = $produit['type'];
            $rsInventaireDetails[$i]['produit_forme']     = $produit['forme'];
            $rsInventaireDetails[$i]['produit_classe_th'] = $produit['classe_th'];

            $rsInventaireDetails[$i]['initial']     = $inventaireDetails[$i]->initial;
            $rsInventaireDetails[$i]['entre']       = $inventaireDetails[$i]->entre;
            $rsInventaireDetails[$i]['sortie']      = $inventaireDetails[$i]->sortie;
            $rsInventaireDetails[$i]['theorique']   = $inventaireDetails[$i]->theorique;
            $rsInventaireDetails[$i]['physique']    = $inventaireDetails[$i]->physique;
            $rsInventaireDetails[$i]['perte']       = $inventaireDetails[$i]->perte;
            $rsInventaireDetails[$i]['ajout']       = $inventaireDetails[$i]->ajout;
            $rsInventaireDetails[$i]['observation'] = $inventaireDetails[$i]->observation;
            $rsInventaireDetails[$i]['details']     = $inventaireDetails[$i]->details;
        }
        

        $this->view->inventaire          = $rsInventaire;
        $this->view->inventaireDetails   = $rsInventaireDetails; 
        $this->view->referer    = $this->request->getHTTPReferer();
    }


    public function laboDemandeAction($dossier_id = 0) {

        $dossier_prescriptions = [];
        $dossier_examens = [];

        if($dossier_id <= 0){
            $this->flash->error("Une erreur c'est produit. Il semble que les informations du patients sont corrompues");
            return $this->response->redirect("patients/index");
        }

       if($dossier_id > 0){
            $laboDemande = LaboDemandes::findFirst($dossier_id);
            if(!$laboDemande){
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->response->redirect("patients/index");
            }
        }

        $patient = $laboDemande->getPatients();

        $detailsDemande =  array();
        

        foreach ($laboDemande->getLaboDemandesDetails() as $k => $detail) {
            $tmp = $detail->getLaboAnalyses();
            $detailsDemande[$k]['categorie']            = $tmp->getLaboCategoriesAnalyse()->libelle;
            $detailsDemande[$k]['id']                   = $tmp->id;
            $detailsDemande[$k]['libelle']              = $tmp->libelle;
            $detailsDemande[$k]['code']                 = $tmp->code;
            $detailsDemande[$k]['unite']                = explode(",", $tmp->unite);
            $detailsDemande[$k]['type_valeur']          = $tmp->type_valeur;
            $detailsDemande[$k]['norme']                = $tmp->norme;
            $detailsDemande[$k]['valeur_possible']      = explode(",", $tmp->valeur_possible);
            $detailsDemande[$k]['has_antibiogramme']    = $tmp->has_antibiogramme;
            $rsTmp = LaboDemandesResultats::findFirst("labo_analyses_id = " . $tmp->id . " AND labo_demandes_id = " . $dossier_id);
            if($rsTmp){
                $detailsDemande[$k]['r_etat']           = $rsTmp->etat;
                $detailsDemande[$k]['r_valeur']         = $rsTmp->valeur;
                $detailsDemande[$k]['r_unite']          = $rsTmp->unite;
                $detailsDemande[$k]['r_valeur_anterieur']   =  $this->_getValeurAnterieur($dossier_id, $tmp->id, $patient->id);
                $detailsDemande[$k]['r_antibiogrammes'] = ($rsTmp->antibiogramme != "") ? explode("|", $rsTmp->antibiogramme) : array();
            }else{
                $detailsDemande[$k]['r_etat']   = "";
                $detailsDemande[$k]['r_valeur']   = "";
                $detailsDemande[$k]['r_unite']    = "";
                $detailsDemande[$k]['r_valeur_anterieur']    = "--";
                $detailsDemande[$k]['r_antibiogrammes']    = [];
            }

            if( !empty($tmp->childs_id) ){
                $children = LaboAnalyses::find(array(" id IN (". $tmp->childs_id .")"));
                foreach ($children as $l => $child) {
                    $arrayChild[$l]['id'] = $child->id;
                    $arrayChild[$l]['libelle'] = $child->libelle;
                    $arrayChild[$l]['code'] = $child->code;
                    $arrayChild[$l]['unite'] = explode(",", $child->unite);
                    $arrayChild[$l]['type_valeur'] = $child->type_valeur;
                    $arrayChild[$l]['valeur_possible'] = explode(",", $child->valeur_possible);
                    $arrayChild[$l]['norme'] = $child->norme;
                    $arrayChild[$l]['valeur_anterieur'] = $this->_getValeurAnterieur($dossier_id, $child->id, $patient->id);

                    $rsTmp = LaboDemandesResultats::findFirst("labo_analyses_id = " . $child->id . " AND labo_demandes_id = " . $dossier_id);
                    if($rsTmp){
                        $arrayChild[$l]['r_etat']   = $rsTmp->etat;
                        $arrayChild[$l]['r_valeur'] = $rsTmp->valeur;
                        $arrayChild[$l]['r_unite']  = $rsTmp->unite;
                    }else{
                        $arrayChild[$l]['r_etat']   = "";
                        $arrayChild[$l]['r_valeur'] = "";
                        $arrayChild[$l]['r_unite']  = "";
                    }
                }
                $detailsDemande[$k]['children'] = $arrayChild;
            }
        }
        
        $this->view->laboDemande    = $laboDemande;
        $this->view->detailsDemande = $detailsDemande;

        $this->view->patient    = $patient;
        $this->view->dossier_id = $dossier_id;
        $this->view->paillasse  = $laboDemande->paillasse;

        $this->view->referer    = $this->request->getHTTPReferer();
    }

    public function laboDemandeEnveloppeAction($dossier_id = 0) {

        $dossier_prescriptions = [];
        $dossier_examens = [];

        if($dossier_id <= 0){
            $this->flash->error("Une erreur c'est produit. Il semble que les informations du patients sont corrompues");
            return $this->response->redirect("patients/index");
        }

       if($dossier_id > 0){
            $laboDemande = LaboDemandes::findFirst($dossier_id);
            if(!$laboDemande){
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->response->redirect("patients/index");
            }
        }

        $patient = $laboDemande->getPatients();
        
        $this->view->laboDemande    = $laboDemande;

        $this->view->patient    = $patient;
        $this->view->dossier_id = $dossier_id;
        $this->view->paillasse  = $laboDemande->paillasse;

        $this->view->referer    = $this->request->getHTTPReferer();
    }

    public function imgDemandeAction($dossier_id = 0) {


        if($dossier_id <= 0){
            $this->flash->error("Une erreur c'est produit. Il semble que les informations du patients sont corrompues");
            return $this->response->redirect("patients/index");
        }

       if($dossier_id > 0){
            $imgDemande = ImgDemandes::findFirst($dossier_id);
            if(!$imgDemande){
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->response->redirect("patients/index");
            }
        }

        $result = ImgDemandesDetails::findFirst(array("img_demandes_id = " . $dossier_id));

        $patient = $imgDemande->getPatients();

        $this->view->imgDemande = $imgDemande;
        $this->view->result     = $result;
        $this->view->radiologue = $imgDemande->getUser()->prenom . " " . $imgDemande->getUser()->nom;

        $this->view->acte_libelle   = $result->getImgItems()->libelle;

        $this->view->patient    = $patient;
        $this->view->dossier_id = $dossier_id;

        $this->view->referer    = $this->request->getHTTPReferer();
    }

    public function imgDemandeEtiquetteAction($dossier_id = 0) {

        if($dossier_id <= 0){
            $this->flash->error("Une erreur c'est produit. Il semble que les informations du patients sont corrompues");
            return $this->response->redirect("patients/index");
        }

       if($dossier_id > 0){
            $imgDemande = ImgDemandes::findFirst($dossier_id);
            if(!$imgDemande){
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->response->redirect("patients/index");
            }
        }
        
        $result = ImgDemandesDetails::findFirst(array("img_demandes_id = " . $dossier_id));

        $patient = $imgDemande->getPatients();

        $this->view->imgDemande = $imgDemande;
        $this->view->result     = $result;

        $this->view->acte_libelle   = $result->getImgItems()->libelle;

        $this->view->patient    = $patient;
        $this->view->dossier_id = $dossier_id;

        $this->view->referer    = $this->request->getHTTPReferer();
    }

    private function _getValeurAnterieur($demande_id, $analyse_id, $patients_id){
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("ldr.valeur as valeur, ldr.unite as unite")
            ->addfrom('LaboDemandesResultats', 'ldr')
            ->join('LaboDemandes', 'ld.id = ldr.labo_demandes_id', 'ld', 'INNER')
            ->andWhere( 'ld.patients_id = :patients_id: AND ldr.labo_analyses_id = :analyse_id:
                        AND ld.id != :demande_id:',
                    array('patients_id' => $patients_id, 'analyse_id' => $analyse_id, 'demande_id' => $demande_id) )
            ->limit('1');
        $rq = $builder->getQuery()->execute();
        $rs = array();
        for ($i = 0; $i < count($rq); $i++) {
            $rs[$i]['valeur'] = $rq[$i]->valeur;
            $rs[$i]['unite'] = $rq[$i]->unite;
        }
        return (isset($rs[0]['valeur'])) ? $rs[0]['valeur'] . " " . $rs[0]['unite'] : "--";

    }

    public function rembourssementsPrestationsAction($date1, $date2, $reste = 0, $assure = 0, $total = 0, $type_assurance = "", $ogd = "-1", $name_assurance = ""){
        
        $date1          = $date1;
        $date2          = $date2;
        $type_assurance = $type_assurance;
        $name_assurance = $name_assurance;
        $ogd            = $ogd;

        $this->view->dateFacture    = date("Y-m-d");
        $this->view->date1          = $date1;
        $this->view->date2          = $date2;
        $this->view->type_assurance = $type_assurance;
        $this->view->name_assurance = $name_assurance;
        $this->view->ogd            = $ogd;

        $builder = $this->modelsManager->createBuilder();
         $tickets = $builder->columns("prestations.id, prestations.date, prestations.numero, prestations.montant_normal, prestations.montant_patient, prestations.montant_restant, prestations.etat, 
                patients.id as patient_id, CONCAT(patients.prenom, ' ', patients.nom) as patients_nom, 
                usercaissier.id as caissier_id, CONCAT(usercaissier.prenom, ' ', usercaissier.nom) as caissier_nom, 
                typeAssurance.libelle as assurance_libelle, CONCAT(typeAssurance.taux, '%') as assurance_taux,
                prestationsDetails.montant_normal as d_montant_normal, 
                prestationsDetails.quantite as d_quantite, 
                (prestationsDetails.montant_unitaire_difference * prestationsDetails.quantite) as d_montant_difference, 
                prestationsDetails.montant_patient as d_montant_patient, 
                (prestationsDetails.montant_normal - prestationsDetails.montant_patient ) as d_reste, 
                actes.libelle as a_libelle, 
                actes.unite as a_unite 
                ")
            ->addfrom('Prestations', 'prestations')
            ->join('PrestationsDetails', 'prestationsDetails.prestations_id = prestations.id', 'prestationsDetails', 'INNER')
            ->join('Actes', 'actes.id = prestationsDetails.actes_id', 'actes', 'INNER')
            ->join('Patients', 'patients.id = prestations.patients_id', 'patients', 'INNER')
            ->join('User', 'usercaissier.id = prestations.user_id', 'usercaissier', 'INNER')
            ->join('TypeAssurance', 'typeAssurance.id = prestations.type_assurance_id', 'typeAssurance', 'LEFT')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:', array('date1' => $date1, 'date2' => $date2) );
        if ($ogd != "" && $ogd != "-1") {
            $tickets = $builder->andWhere('prestations.ogd LIKE ?0', array("%{$ogd}%"));
        }
        if ($name_assurance != "") {
            $tickets = $builder->andWhere('typeAssurance.libelle LIKE ?0', array("%{$name_assurance}%"));
        }
        else{
            $tickets = $builder->andWhere( 'prestations.type_assurance_id = :type_assurance: ', array('type_assurance' => $type_assurance) );
        }

        $tickets = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();

        //print_r($date2);exit();
         //print_r($date2);exit();
        $obj = new Nuts($reste, "CFA");
        $mtEnLettre = $obj->convert("fr-FR");
        $nb = $obj->getFormated(" ", ",");

        $this->view->reste      = $reste;
        $this->view->total      = $total;
        $this->view->assure     = $assure;
        $this->view->mtEnLettre = $mtEnLettre;
        $this->view->tickets    = json_encode($tickets, JSON_PRETTY_PRINT);

    }

    public function rembourssementsPharmacieAction($date1, $date2, $reste = 0, $assure = 0, $total = 0, $type_assurance = "", $ogd = "-1", $name_assurance = ""){
        
        $date1          = $date1;
        $date2          = $date2;
        $type_assurance = $type_assurance;
        $name_assurance = $name_assurance;
        $ogd            = $ogd;
        
        $this->view->dateFacture    = date("Y-m-d");
        $this->view->date1          = $date1;
        $this->view->date2          = $date2;
        $this->view->type_assurance = $type_assurance;
        $this->view->name_assurance = $name_assurance;
        $this->view->ogd            = $ogd;

        $builder = $this->modelsManager->createBuilder();
        $recus = $builder->columns("recuMedicament.id, recuMedicament.date, recuMedicament.numero, recuMedicament.montant_normal, recuMedicament.montant_patient, recuMedicament.montant_restant, recuMedicament.etat, 
                patients.id as patient_id, CONCAT(patients.prenom, ' ', patients.nom) as patients_nom, 
                usercaissier.id as caissier_id, CONCAT(usercaissier.prenom, ' ', usercaissier.nom) as caissier_nom, 
                typeAssurance.libelle as assurance_libelle, CONCAT(typeAssurance.taux, '%') as assurance_taux,
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
            ->andWhere( 'recuMedicament.etat = 1 AND date(recuMedicament.date) between :date1: AND :date2:', array('date1' => $date1, 'date2' => $date2) );
        if ($ogd != "" && $ogd != "-1") {
            $pharmacie = $builder->andWhere('recuMedicament.ogd LIKE ?0', array("%{$ogd}%"));
        }

        if ($name_assurance != "") {
            $pharmacie = $builder->andWhere('typeAssurance.libelle LIKE ?0', array("%{$name_assurance}%"));
        }
        else{
            $pharmacie = $builder->andWhere( 'recuMedicament.type_assurance_id = :type_assurance: ', array('type_assurance' => $type_assurance) );
        }

        $recus = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();

        //print_r($date2);exit();
        $obj = new Nuts($reste, "CFA");
        $mtEnLettre = $obj->convert("fr-FR");
        $nb = $obj->getFormated(" ", ",");

        $this->view->reste      = $reste;
        $this->view->total      = $total;
        $this->view->assure     = $assure;
        $this->view->mtEnLettre = $mtEnLettre;
        $this->view->recus      = json_encode($recus, JSON_PRETTY_PRINT);

    }

}
