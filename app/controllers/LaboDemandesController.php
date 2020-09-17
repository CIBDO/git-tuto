<?php

use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\View\Simple as SimpleView;

require __DIR__ . '/../../vendor/autoload.php';

/**
 * LaboDemandesController
 *
 */
class LaboDemandesController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Analyses"]);
        if($this->view->language == "fr"){
            $langue = "fr-FR";
        }
        else{
            $langue = "en-US";
        }
        $this->view->langue = $langue;
    }

    public function listeAttenteAction(){

        $listeAttentes = LaboDemandes::find(array("etat = 'création'", "order" => "date ASC"));

        $rs = [];
        for($i = 0; $i < count($listeAttentes); $i++) {
            $rs[$i]['id'] = $listeAttentes[$i]->id;
            $rs[$i]['date'] = strtotime($listeAttentes[$i]->date);
            $rs[$i]['paillasse'] = $listeAttentes[$i]->paillasse;
            $analyseArray = [];
            foreach ($listeAttentes[$i]->getLaboDemandesDetails() as $key => $value) {
                $analyseArray[] = $value->getLaboAnalyses()->libelle;
            };
            $rs[$i]['acte'] = (isset($analyseArray)) ? implode(" , ", $analyseArray) : "";

            $rs[$i]['prestation_id'] = ($listeAttentes[$i]->prestations_id != null) ? 
                                         $listeAttentes[$i]->getPrestations()->id : "";
            $rs[$i]['patient_id'] = $listeAttentes[$i]->getPatients()->id;
            $rs[$i]['patient_nom'] = $listeAttentes[$i]->getPatients()->id . " - " . $listeAttentes[$i]->getPatients()->nom . " " . $listeAttentes[$i]->getPatients()->prenom;
            $rs[$i]['patient_adresse'] = $listeAttentes[$i]->getPatients()->adresse;
            $rs[$i]['patient_telephone'] = $listeAttentes[$i]->getPatients()->telephone;
        }

        $this->view->liste_attentes = json_encode($rs, JSON_PRETTY_PRINT);

    }

     public function demandesAction(){
        $date1 = $date2 = date("Y-m-d");
        $etat = "encours";
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

        $listeAttentes = LaboDemandes::find(array("etat = :etat: AND DATE(date) BETWEEN :date1: AND :date2:",
                                                    "order" => "date ASC", 
                                                    "bind" => array("etat" => $etat, 
                                                        "date1" => $date1, "date2" => $date2)
                                            ));

        $rs = [];
        for($i = 0; $i < count($listeAttentes); $i++) {
            $rs[$i]['id'] = $listeAttentes[$i]->id;
            $rs[$i]['date'] = strtotime($listeAttentes[$i]->date);
            $rs[$i]['paillasse'] = $listeAttentes[$i]->paillasse;
            $analyseArray = [];
            foreach ($listeAttentes[$i]->getLaboDemandesDetails() as $key => $value) {
                $analyseArray[] = $value->getLaboAnalyses()->libelle;
            };
            $rs[$i]['acte'] = (isset($analyseArray)) ? implode(" , ", $analyseArray) : "";

            $rs[$i]['prestation_id'] = ($listeAttentes[$i]->prestations_id != null) ? 
                                         $listeAttentes[$i]->getPrestations()->id : "";
            $rs[$i]['patient_id'] = $listeAttentes[$i]->getPatients()->id;
            $rs[$i]['patient_nom'] = $listeAttentes[$i]->getPatients()->id . " - " . $listeAttentes[$i]->getPatients()->nom . " " . $listeAttentes[$i]->getPatients()->prenom;
            $rs[$i]['patient_adresse'] = $listeAttentes[$i]->getPatients()->adresse;
            $rs[$i]['patient_telephone'] = $listeAttentes[$i]->getPatients()->telephone;
        }

        $this->view->liste_attentes = json_encode($rs, JSON_PRETTY_PRINT);

    }

    public function dossierAction($patient_id = 0) {
        
        if($patient_id == 0)
        {
            $this->flash->error("Veuillez choisir un patient pour ouvrir un dossier");
            return $this->forward("patients/index");
        }
       

        $patient = Patients::findFirst($patient_id);
        if(!$patient){
            $msg = $this->trans['on_error'];
            $this->flash->error($msg);
            return;
        }
        $patient_antecedant = [];
        foreach ($patient->getPatientsAntecedant() as $k => $v) {
            $patient_antecedant[$k]['id'] = $v->id;
            $patient_antecedant[$k]['type'] = $v->type;
            $patient_antecedant[$k]['libelle'] = $v->libelle;
            if($v->niveau == "normal"){
                $patient_antecedant[$k]['niveau'] = "primary";
            }
            if($v->niveau == "moyen"){
                $patient_antecedant[$k]['niveau'] = "warning";
            }
            if($v->niveau == "important"){
                $patient_antecedant[$k]['niveau'] = "danger";
            }
        }
        $this->view->patient_antecedant = $patient_antecedant;     

        $dossiers_list = LaboDemandes::find(array('conditions' => 'patients_id = :p:',
                                                            "order" => "date DESC",
                                                            "bind"  => array('p' => $patient_id)
                                                            ));
        $rs = [];
        for($i = 0; $i < count($dossiers_list); $i++) {
            $rs[$i]['id']           = $dossiers_list[$i]->id;
            $rs[$i]['provenance']   = $dossiers_list[$i]->provenance;
            $rs[$i]['prescripteur'] = $dossiers_list[$i]->prescripteur;
            $rs[$i]['etat']         = $dossiers_list[$i]->etat;
            $rs[$i]['patients_id']  = $dossiers_list[$i]->patients_id;
            $rs[$i]['paillasse']    = $dossiers_list[$i]->paillasse;
            $analyseArray = [];
            foreach ($dossiers_list[$i]->getLaboDemandesDetails() as $key => $value) {
                $analyseArray[] = $value->getLaboAnalyses()->libelle;
            };
            $rs[$i]['acte'] = (isset($analyseArray)) ? implode(" , ", $analyseArray) : "";
            $rs[$i]['date'] = date('d/m/Y H:i:m', strtotime($dossiers_list[$i]->date));
        }



        $this->view->dossiers_list   = json_encode($rs, JSON_PRETTY_PRINT);
        $this->view->patient = $patient;     
        $this->view->patient_residence = ( $tmp = $patient->getResidence() ) ? $tmp->libelle : ""; 
    }

    public function editDossier2Action($patients_id = 0, $dossier_id = 0) {  
        
        $dossier_constantes = [];
        $dossier_prescriptions = [];
        $dossier_examens = [];

        if($dossier_id <= 0 || $patients_id <= 0){
            $this->flash->error("Une erreur c'est produit. Il semble que les informations du patients sont corrompues");
            return $this->response->redirect("patients/index");
        }

        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            //var_dump($data);exit();
            if($dossier_id > 0){
                $laboDemande = LaboDemandes::findFirst($dossier_id);
                if(!$laboDemande){
                    $msg = $this->trans['on_error'];
                    $this->flash->error($msg);
                    return $this->response->redirect("labo_demandes/dossiers/" . $patients_id);
                }
            }

            $laboDemande->provenance    = $data['provenance'];
            $laboDemande->prescripteur  = $data['prescripteur'];
            $laboDemande->histoire      = $data['histoire'];
            $laboDemande->etat          = "encours";

            if (!$laboDemande->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return;
            }
            else{
                //Resultat

                //Examen

                /*//On supprime tout d'abord
                $query = $this->modelsManager->createQuery("DELETE FROM LaboDemandesResultats WHERE labo_demandes_id = :id:");
                $exec  = $query->execute(
                                array('id' => $dossier_id)
                            );*/
                $i = 0;
                while( isset($data['id_' . $i]) ){
                    if ( isset($data['r_id_' . $i]) && ($data['r_id_' . $i] != "") ) {
                        $result = LaboDemandesResultats::findFirst($data['r_id_' . $i]);
                    }
                    else{
                        $result = new LaboDemandesResultats();
                    }
                    $antibiogrammes = "";

                    if( isset($data['antibiogrammes_' . $data['id_' . $i]]) ){
                        $tmp = $data['antibiogrammes_' . $data['id_' . $i]];
                        $antibiogrammes = implode("|", $tmp);
                        //print_r($tmp);exit();
                    }

                    $result->valeur = ( isset($data['value_' . $i]) ) ? $data['value_' . $i] : "";
                    $result->unite  = ( isset($data['unite_' . $i]) ) ? $data['unite_' . $i] : "";
                    $result->labo_analyses_id   = $data['id_' . $i];
                    $result->labo_demandes_id   = $dossier_id;
                    $result->antibiogramme      = $antibiogrammes;

                    if (!$result->save()) {
                        $msg = "Le resultat n'a pas été correctement enregistrées. veuillez contacter l'admin";
                        $this->flash->error($msg);
                    }
                    $i++;
                }

            }
           
            $this->flash->success($this->trans['Dossier enregistré avec succès']);
            $this->response->redirect("labo_demandes/editDossier2/" . $patients_id . "/" . $dossier_id);
            return;
        }

        if($dossier_id > 0){
            $laboDemande = LaboDemandes::findFirst($dossier_id);
            if(!$laboDemande){
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->response->redirect("labo_demandes/dossiers/" . $patients_id);
            }
        }

        Phalcon\Tag::setDefaults(array(
            "provenance" => $laboDemande->provenance,
            "prescripteur" => $laboDemande->prescripteur,
            "histoire" => $laboDemande->histoire
        ));

        $detailsDemande =  array();
        

        foreach ($laboDemande->getLaboDemandesDetails() as $k => $detail) {
            $tmp = $detail->getLaboAnalyses();
            $detailsDemande[$k]['id']               = $tmp->id;
            $detailsDemande[$k]['libelle']          = $tmp->libelle;
            $detailsDemande[$k]['code']             = $tmp->code;
            $detailsDemande[$k]['unite']            = explode(",", $tmp->unite);
            $detailsDemande[$k]['type_valeur']      = $tmp->type_valeur;
            $detailsDemande[$k]['valeur_possible']  = explode(",", $tmp->valeur_possible);
            $detailsDemande[$k]['has_antibiogramme']    = $tmp->has_antibiogramme;
            $rsTmp = LaboDemandesResultats::findFirst("labo_analyses_id = " . $tmp->id . " AND labo_demandes_id = " . $dossier_id);
            if($rsTmp){
                $detailsDemande[$k]['r_id']       = $rsTmp->id;
                $detailsDemande[$k]['r_etat']       = $rsTmp->etat;
                $detailsDemande[$k]['r_valeur']     = $rsTmp->valeur;
                $detailsDemande[$k]['r_unite']      = $rsTmp->unite;
                $detailsDemande[$k]['r_antibiogrammes']    = explode("|", $rsTmp->antibiogramme);
            }else{
                $detailsDemande[$k]['r_id']     = "";
                $detailsDemande[$k]['r_etat']   = "";
                $detailsDemande[$k]['r_valeur'] = "";
                $detailsDemande[$k]['r_unite']  = "";
                $detailsDemande[$k]['r_antibiogrammes'] = [];
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

                    $rsTmp = LaboDemandesResultats::findFirst("labo_analyses_id = " . $child->id . " AND labo_demandes_id = " . $dossier_id);
                    if($rsTmp){
                        $arrayChild[$l]['r_id']   = $rsTmp->id;
                        $arrayChild[$l]['r_etat']   = $rsTmp->etat;
                        $arrayChild[$l]['r_valeur'] = $rsTmp->valeur;
                        $arrayChild[$l]['r_unite']  = $rsTmp->unite;
                    }else{
                        $arrayChild[$l]['r_id']   = "";
                        $arrayChild[$l]['r_etat']   = "";
                        $arrayChild[$l]['r_valeur'] = "";
                        $arrayChild[$l]['r_unite']  = "";
                    }
                }
                $detailsDemande[$k]['children'] = $arrayChild;
            }
        }
        
        $this->view->detailsDemande    = $detailsDemande;

        // INFOS PATIENT
        $patient = Patients::findFirst($patients_id);
        if(!$patient){
            $msg = $this->trans['on_error'];
            $this->flash->error($msg);
            return;
        }
        $patient_antecedant = [];
        foreach ($patient->getPatientsAntecedant() as $k => $v) {
            $patient_antecedant[$k]['id'] = $v->id;
            $patient_antecedant[$k]['type'] = $v->type;
            $patient_antecedant[$k]['libelle'] = $v->libelle;
            if($v->niveau == "normal"){
                $patient_antecedant[$k]['niveau'] = "primary";
            }
            if($v->niveau == "moyen"){
                $patient_antecedant[$k]['niveau'] = "warning";
            }
            if($v->niveau == "important"){
                $patient_antecedant[$k]['niveau'] = "danger";
            }
        }
        $this->view->patient_antecedant = $patient_antecedant;     
        $this->view->patient = $patient;     
        $this->view->patient_residence = ( $tmp = $patient->getResidence() ) ? $tmp->libelle : ""; 

        $this->view->patients_id    = $patients_id;
        $this->view->dossier_id     = $dossier_id;
        $this->view->laboDemande    = $laboDemande;
        
    }

    public function editAntibiogrammeAction($dossier_id = 0, $analyse_id = 0, $analyse_nom = "") {  

        if($dossier_id <= 0 || $analyse_id <= 0){
            $this->flash->error("Une erreur c'est produit. Il semble que les informations du dossier sont corrompues");
            return $this->response->redirect("patients/index");
        }

        if ($this->request->isPost()) {
           
        }
        
        $this->view->disable();

        $builder = $this->modelsManager->createBuilder();
        $f_banque_compte = $builder->columns("laboAntibiogrammes.id as id, CONCAT(laboAntibiogrammesType.libelle, ' / ', laboAntibiogrammes.libelle) as libelle")
            ->addfrom('LaboAntibiogrammes', 'laboAntibiogrammes')
            ->join('LaboAntibiogrammesType', 'laboAntibiogrammes.labo_antibiogrammes_type_id = laboAntibiogrammesType.id', 'laboAntibiogrammesType', 'INNER')
            ->orderBy('laboAntibiogrammesType.libelle asc');
        $antibiogramme = $builder->getQuery()->execute();
        $rs = [];
        for($i = 0; $i < count($antibiogramme); $i++) {
            $rs[$antibiogramme[$i]->id] = $antibiogramme[$i]->libelle;
        }
        $this->view->antibiogramme  = $rs;

        $this->view->dossier_id     = $dossier_id;
        $this->view->analyse_id     = $analyse_id;
        $this->view->analyse_nom    = $analyse_nom;
        
        $this->view->partial('labo_demandes/editAntibiogramme');
    }

     public function detailsAntibiogrammeAction($antibiogramme_id = 0) {  

        if($antibiogramme_id <= 0){
            $this->flash->error("Une erreur c'est produit.");
            return $this->response->redirect("patients/index");
        }

        $this->view->disable();

        $antibiogramme = LaboAntibiogrammes::findFirst($antibiogramme_id);
        $arrayAntibiotique = [];
        if($antibiogramme->antibiotiques != ""){
            $arrayAntibiotique = explode(",", $antibiogramme->antibiotiques);
            $antibiotiques = LaboAntibiotiques::find(array("id IN (". $antibiogramme->antibiotiques .")"));
            if(count($antibiotiques)>0){
                $arrayAntibiotique = $antibiotiques;
            }
        }
        $this->view->arrayAntibiotique  = $arrayAntibiotique;
        
        $this->view->partial('labo_demandes/detailsAntibiogramme');

    }


    public function ajaxProvenanceAction() {
        $this->view->disable();
        
        $builder = $this->modelsManager->createBuilder();
        $provenances = $builder->columns('distinct laboDemandes.provenance')
            ->addfrom('LaboDemandes', 'laboDemandes')
            ->andWhere( ' laboDemandes.provenance IS NOT NULL ');

        $provenances = $builder->getQuery()->execute();
        $rs = array();
        for($i = 0; $i < count($provenances); $i++) {
            $rs[$i]['id'] = 1;
            $rs[$i]['libelle'] = $provenances[$i]->provenance;
        }
        
        echo json_encode($rs, JSON_PRETTY_PRINT);
    }


    public function ajaxPrescripteurAction() {
        $this->view->disable();
        
        $builder = $this->modelsManager->createBuilder();
        $prescripteurs = $builder->columns('distinct laboDemandes.prescripteur')
            ->addfrom('LaboDemandes', 'laboDemandes')
            ->andWhere( ' laboDemandes.prescripteur IS NOT NULL ');

        $prescripteurs = $builder->getQuery()->execute();
        $rs = array();
        for($i = 0; $i < count($prescripteurs); $i++) {
            $rs[$i]['id'] = 1;
            $rs[$i]['libelle'] = $prescripteurs[$i]->prescripteur;
        }
        
        echo json_encode($rs, JSON_PRETTY_PRINT);
    }

     public function createDemandeAction($patients_id = "") {
        $this->view->disable();
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $laboItem = array();
            foreach ($data['analyse_id'] as $key => $value) {
                $laboItem[$key]   = new LaboDemandesDetails();
                $laboItem[$key]->labo_analyses_id = $value;
            }
            
            if (count($laboItem) > 0) {
                $laboDemande                        = new LaboDemandes();
                $laboDemande->date                  = date("Y-m-d H:i:s");
                $laboDemande->patients_id           = $data['patients_id'];
                $laboDemande->prestations           = null;
                $laboDemande->etat                  = "création";
                $laboDemande->paillasse             = $this->getLaboPaillasse();
                $laboDemande->laboDemandesDetails   = $laboItem;

                if(!$laboDemande->save()){
                    $msg = $this->trans['on_error'];
                    $this->flash->error($msg);
                    return $this->view->partial("layouts/flash");
                }
            }

            $this->flash->success($this->trans['Demande créée avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $builder = $this->modelsManager->createBuilder();
        $analyse_id = $builder->columns("laboAnalyses.id as id, laboAnalyses.libelle as libelle")
            ->addfrom('LaboAnalyses', 'laboAnalyses')
            ->where(' laboAnalyses.labo_categories_analyse_id IS NOT NULL')
            ->orderBy('libelle asc');
        $analyse_id = $builder->getQuery()->execute();
        $rs = [];
        foreach ($analyse_id as $k => $value) {
            $rs[$value->id] = $value->libelle;
        }
        $this->view->analyse_id   = $rs;

        $this->view->patients_id = $patients_id;
        $this->view->partial('labo_demandes/createDemande');
    }

    public function clotureDemandeAction($id) {
        $this->view->disable();

        $demande = LaboDemandes::findFirst($id);
        if(!$demande){
            echo 0;exit();
        }

        $demande->etat = "clotûré";
        $demande->close_date = date("Y-m-d H:i:m");

        if ($this->request->isAjax()) {
            if (!$demande->save()) {
               echo 0;exit();
            }
            else{
                //On met ajour letat
                $query = $this->modelsManager->createQuery("
                                    UPDATE LaboDemandesResultats SET etat = '1'
                                        WHERE labo_demandes_id = :id:");
                $exec  = $query->execute( array('id' => $id));
               echo 1;exit();
            }
        }
        echo 0;exit();
    }

    public function validItemAction($demande_id, $analyse_id) {
        $this->view->disable();

        //On met ajour letat
        $query = $this->modelsManager->createQuery("
                            UPDATE LaboDemandesResultats SET etat = '1'
                                WHERE labo_demandes_id = :id: AND labo_analyses_id = :analyse_id: ");
        $exec  = $query->execute( array('id' => $demande_id, 'analyse_id' => $analyse_id));

        echo 1;exit();
    }

    public function imprimEnvelopAction($demandes_id = ""){

        $this->view->disable();

        //Dossier
        $laboDemande = LaboDemandes::find(array("id IN (". $demandes_id . ")" ));

        ob_start();
        foreach($laboDemande as $item)
        {
            $this->view->partial("labo_demandes/imprimEnvelop", ["item" => $item]);
        }
        $content    = ob_get_contents();
        ob_end_clean();

        try
        {
            $pdf    = new HTML2PDF('L', 'A4', 'fr');
            $pdf->pdf->SetDisplayMode('fullpage');
            $pdf->writeHTML($content);
            $pdf->Output("lab_enveloppe_" . date("dmY") . ".pdf","D");

        }catch (HTML2PDF_exception $e){
            die($e);
        }
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

        //Nombre total de demande
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(laboDemandes.id) as nbr")
            ->addfrom('LaboDemandes', 'laboDemandes')
            ->andWhere( 'date(laboDemandes.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $totalDemande = (count($req)>0) ? $req[0]['nbr'] : 0;
        $this->view->totalDemande = (count($req)>0) ? $req[0]['nbr'] : 0;

        //Nombre total de demande en attente
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(laboDemandes.id) as nbr")
            ->addfrom('LaboDemandes', 'laboDemandes')
            ->andWhere( "(date(laboDemandes.date) between :date1: AND :date2:) AND laboDemandes.etat='création'",
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $totalAttente = (count($req)>0) ? $req[0]['nbr'] : 0;
        $this->view->totalAttente = (count($req)>0) ? $req[0]['nbr'] : 0;

        //Nombre total de demande encour
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(laboDemandes.id) as nbr")
            ->addfrom('LaboDemandes', 'laboDemandes')
            ->andWhere( "(date(laboDemandes.date) between :date1: AND :date2:) AND laboDemandes.etat='encours'",
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $totalEncour = (count($req)>0) ? $req[0]['nbr'] : 0;
        $this->view->totalEncour = (count($req)>0) ? $req[0]['nbr'] : 0;

        //Nombre total de demande clotûré
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(laboDemandes.id) as nbr")
            ->addfrom('LaboDemandes', 'laboDemandes')
            ->andWhere( "(date(laboDemandes.date) between :date1: AND :date2:) AND laboDemandes.etat='clotûré'",
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $totalCloture = (count($req)>0) ? $req[0]['nbr'] : 0;
        $this->view->totalCloture = (count($req)>0) ? $req[0]['nbr'] : 0;

        //nouvelles demande
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("YEAR(laboDemandes.date) as annee, 
                MONTH(laboDemandes.date) as mois_chiffre, 
                MONTHNAME(laboDemandes.date) as mois, 
                COUNT(distinct laboDemandes.id) as nbr")
            ->addfrom('LaboDemandes', 'laboDemandes')
            ->join('Patients', 'laboDemandes.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( 'date(laboDemandes.date) between :date1: AND :date2:',
                                         array('date1' => date('Y-m-d',strtotime("-12 months")), 'date2' => date("Y-m-d")) )
            ->groupBy('annee, mois, mois_chiffre')
            ->orderBy('annee asc, mois_chiffre ASC');
        $req = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS);
        $rs = array();
        for ($i = 0; $i < count($req); $i++) {
            $rs[$i]['mois'] = $req[$i]->mois;
            $rs[$i]['nbr'] = $req[$i]->nbr;
        }
        $this->view->mensuelleDemandeGraph = json_encode($rs, JSON_PRETTY_PRINT);

        //Par provenance
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("COUNT(laboDemandes.id) as nbr, provenance as libelle")
            ->addfrom('LaboDemandes', 'laboDemandes')
            ->andWhere( " ( date(laboDemandes.date) between :date1: AND :date2: ) ",
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('libelle')->orderBy('libelle ASC')->limit(10);
        $req = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS);
        $rs = array();
        for ($i = 0; $i < count($req); $i++) {
            $rs[$i]['nbr']      = $req[$i]->nbr;
            $rs[$i]['libelle']  = $req[$i]->libelle;
        }
        $this->view->demandeParProvenance = json_encode($rs, JSON_PRETTY_PRINT);

        //Par prescripteur
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("COUNT(laboDemandes.id) as nbr, prescripteur as libelle")
            ->addfrom('LaboDemandes', 'laboDemandes')
            ->andWhere( " ( date(laboDemandes.date) between :date1: AND :date2: ) ",
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('libelle')->orderBy('libelle ASC')->limit(10);
        $req = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS);
        $rs = array();
        for ($i = 0; $i < count($req); $i++) {
            $rs[$i]['nbr']      = $req[$i]->nbr;
            $rs[$i]['libelle']  = $req[$i]->libelle;
        }
        $this->view->demandeParPrescripteur = json_encode($rs, JSON_PRETTY_PRINT);

    }


}