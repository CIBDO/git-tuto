<?php
use Phalcon\Mvc\Model\Resultset;

/**
 * ImgDemandesController
 *
 */
class ImgDemandesController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Imagerie"]);
        if($this->view->language == "fr"){
            $langue = "fr-FR";
        }
        else{
            $langue = "en-US";
        }
        $this->view->langue = $langue;
    }

    public function listeAttenteAction(){

        $listeAttentes = ImgDemandes::find(array("etat = 'création'", "order" => "date ASC"));

        $rs = [];
        for($i = 0; $i < count($listeAttentes); $i++) {
            $rs[$i]['id'] = $listeAttentes[$i]->id;
            $rs[$i]['date'] = strtotime($listeAttentes[$i]->date);
            $itemsArray = [];
            foreach ($listeAttentes[$i]->getImgDemandesDetails() as $key => $value) {
                $itemsArray[] = $value->getImgItems()->libelle;
            };
            $rs[$i]['acte'] = (isset($itemsArray)) ? implode(" , ", $itemsArray) : "";

            $rs[$i]['prestation_id'] = ($listeAttentes[$i]->prestations_id != null) ? 
                                         $listeAttentes[$i]->getPrestations()->id : "";
            $rs[$i]['patient_id'] = $listeAttentes[$i]->getPatients()->id;
            $rs[$i]['patient_nom'] = $listeAttentes[$i]->getPatients()->id . " - " . $listeAttentes[$i]->getPatients()->nom . " " . $listeAttentes[$i]->getPatients()->prenom;
            $rs[$i]['patient_adresse'] = $listeAttentes[$i]->getPatients()->adresse;
            $rs[$i]['patient_telephone'] = $listeAttentes[$i]->getPatients()->telephone;
            $rs[$i]['medecin_traiteur'] = $listeAttentes[$i]->getUser()->nom . " " . $listeAttentes[$i]->getUser()->prenom;
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

        $listeAttentes = ImgDemandes::find(array("etat = :etat: AND DATE(date) BETWEEN :date1: AND :date2:",
                                                    "order" => "date ASC", 
                                                    "bind" => array("etat" => $etat, 
                                                        "date1" => $date1, "date2" => $date2)
                                            ));

        $rs = [];
        for($i = 0; $i < count($listeAttentes); $i++) {
            $rs[$i]['id'] = $listeAttentes[$i]->id;
            $rs[$i]['date'] = strtotime($listeAttentes[$i]->date);
            $itemsArray = [];
            foreach ($listeAttentes[$i]->getImgDemandesDetails() as $key => $value) {
                $itemsArray[] = $value->getImgItems()->libelle;
            };
            $rs[$i]['acte'] = (isset($itemsArray)) ? implode(" , ", $itemsArray) : "";

            $rs[$i]['prestation_id'] = ($listeAttentes[$i]->prestations_id != null) ? 
                                         $listeAttentes[$i]->getPrestations()->id : "";
            $rs[$i]['patient_id'] = $listeAttentes[$i]->getPatients()->id;
            $rs[$i]['patient_nom'] = $listeAttentes[$i]->getPatients()->id . " - " . $listeAttentes[$i]->getPatients()->nom . " " . $listeAttentes[$i]->getPatients()->prenom;
            $rs[$i]['patient_adresse'] = $listeAttentes[$i]->getPatients()->adresse;
            $rs[$i]['patient_telephone'] = $listeAttentes[$i]->getPatients()->telephone;
            $rs[$i]['medecin_traiteur'] = $listeAttentes[$i]->getUser()->nom . " " . $listeAttentes[$i]->getUser()->prenom;
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

        $dossiers_list = ImgDemandes::find(array('conditions' => 'patients_id = :p:',
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
            $itemsArray = [];
            foreach ($dossiers_list[$i]->getImgDemandesDetails() as $key => $value) {
                $itemsArray[] = $value->getImgItems()->libelle;
            };
            $rs[$i]['acte'] = (isset($itemsArray)) ? implode(" , ", $itemsArray) : "";
            $rs[$i]['date'] = date('d/m/Y H:i:m', strtotime($dossiers_list[$i]->date));
        }



        $this->view->dossiers_list   = json_encode($rs, JSON_PRETTY_PRINT);
        $this->view->patient = $patient;     
        $this->view->patient_residence = ( $tmp = $patient->getResidence() ) ? $tmp->libelle : ""; 
    }

    public function editDossier2Action($patients_id = 0, $dossier_id = 0, $modele_id = 0) {  
        
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
                $imgDemande = ImgDemandes::findFirst($dossier_id);
                if(!$imgDemande){
                    $msg = $this->trans['on_error'];
                    $this->flash->error($msg);
                    return $this->response->redirect("img_demandes/dossiers/" . $patients_id);
                }
            }

            $imgDemande->provenance    = $data['provenance'];
            $imgDemande->prescripteur  = $data['prescripteur'];
            //$imgDemande->indication    = $data['indication'];
            $imgDemande->etat          = "encours";

            if (!$imgDemande->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return;
            }
            else{
                //Resultat
                $result = ImgDemandesDetails::findFirst(array("img_demandes_id = " . $dossier_id));
                if( $result){
                   $result->protocole       =  $data['protocole'];
                   //$result->interpretation  =  $data['interpretation'];
                   $result->conclusion      =  $data['conclusion'];
                    if (!$result->save()) {
                        $msg = "Le resultat n'a pas été correctement enregistrées. veuillez contacter l'admin";
                        $this->flash->error($msg);
                    }
                }
            }
           
            $this->flash->success($this->trans['Dossier enregistré avec succès']);
            $this->response->redirect("img_demandes/editDossier2/" . $patients_id . "/" . $dossier_id);
            return;
        }

        if($dossier_id > 0){
            $imgDemande = ImgDemandes::findFirst($dossier_id);
            if(!$imgDemande){
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->response->redirect("img_demandes/dossiers/" . $patients_id);
            }
        }

        $result = ImgDemandesDetails::findFirst(array("img_demandes_id = " . $dossier_id));
        if(!$result){
            $msg = "Le resultat n'a pas été correctement Recupérer. veuillez contacter l'admin";
            $this->flash->error($msg);
        }

        Phalcon\Tag::setDefaults(array(
            "provenance" => $imgDemande->provenance,
            "prescripteur" => $imgDemande->prescripteur,
            /*"indication" => $imgDemande->indication,
            "protocole" => $result->protocole,
            "interpretation" => $result->interpretation,*/
            "conclusion" => $result->conclusion
        ));

        if($modele_id != 0){
            $modele = ImgModele::findFirst($modele_id);
            if($modele){
                Phalcon\Tag::setDefaults(array(
                    //"interpretation" => $modele->interpretation,
                    "conclusion" => $modele->conclusion
                ));
            }
        }

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

        $this->view->imgDemande    = $imgDemande;
        $this->view->patients_id    = $patients_id;
        $this->view->dossier_id     = $dossier_id;
    }

    public function ajaxProvenanceAction() {
        $this->view->disable();
        
        $builder = $this->modelsManager->createBuilder();
        $provenances = $builder->columns('distinct imgDemandes.provenance')
            ->addfrom('ImgDemandes', 'imgDemandes')
            ->andWhere( ' imgDemandes.provenance IS NOT NULL ');

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
        $prescripteurs = $builder->columns('distinct imgDemandes.prescripteur')
            ->addfrom('ImgDemandes', 'imgDemandes')
            ->andWhere( ' imgDemandes.prescripteur IS NOT NULL ');

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
            print_r($data);
            $imgItem   = new ImgDemandesDetails();
            $imgItem->img_items_id = $data['imgItems_id'];
            
            $imgDemande                     = new ImgDemandes();
            $imgDemande->date               = date("Y-m-d H:i:s");
            $imgDemande->patients_id        = $data['patients_id'];
            $imgDemande->prestations        = null;
            $imgDemande->etat               = "création";
            $imgDemande->user_id            = $this->session->get('usr')['id'];
            $imgDemande->imgDemandesDetails = $imgItem;

            if(!$imgDemande->save()){
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }

            $this->flash->success($this->trans['Demande créée avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $builder = $this->modelsManager->createBuilder();
        $imgItems_id = $builder->columns("imgItems.id as id, imgItems.libelle as libelle")
            ->addfrom('ImgItems', 'imgItems')
            ->where(' imgItems.img_items_categories_id IS NOT NULL')
            ->orderBy('libelle asc');
        $imgItems_id = $builder->getQuery()->execute();
        $rs = [];
        foreach ($imgItems_id as $k => $value) {
            $rs[$value->id] = $value->libelle;
        }
        $this->view->imgItems_id   = $rs;

        $this->view->patients_id = $patients_id;
        $this->view->partial('img_demandes/createDemande');
    }

    public function clotureDemandeAction($id) {
        $this->view->disable();

        $demande = ImgDemandes::findFirst($id);
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
               echo 1;exit();
            }
        }
        echo 0;exit();
    }

    public function imprimEnvelopAction($demandes_id = ""){

        $this->view->disable();

        //Dossier
        $imgDemande = ImgDemandes::find(array("id IN (". $demandes_id . ")" ));

        ob_start();
        foreach($imgDemande as $item)
        {
            $itemsArray = [];
            foreach ($item->getImgDemandesDetails() as $key => $value) {
                $itemsArray[] = $value->getImgItems()->libelle;
            };
            $acte_libelle = (isset($itemsArray)) ? implode(" , ", $itemsArray) : "";

            $this->view->partial("img_demandes/imprimEnvelop", ["item" => $item, "acte_libelle" => $acte_libelle]);
        }
        $content    = ob_get_contents();
        ob_end_clean();

        try
        {
            $pdf    = new HTML2PDF('L', 'A4', 'fr');
            $pdf->pdf->SetDisplayMode('fullpage');
            $pdf->writeHTML($content);
            $pdf->Output("img_enveloppe_" . date("dmY") . ".pdf","D");

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
        $req = $builder->columns("count(imgDemandes.id) as nbr")
            ->addfrom('ImgDemandes', 'imgDemandes')
            ->andWhere( 'date(imgDemandes.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $totalDemande = (count($req)>0) ? $req[0]['nbr'] : 0;
        $this->view->totalDemande = (count($req)>0) ? $req[0]['nbr'] : 0;

        //Nombre total de demande en attente
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(imgDemandes.id) as nbr")
            ->addfrom('ImgDemandes', 'imgDemandes')
            ->andWhere( "(date(imgDemandes.date) between :date1: AND :date2:) AND imgDemandes.etat='création'",
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $totalAttente = (count($req)>0) ? $req[0]['nbr'] : 0;
        $this->view->totalAttente = (count($req)>0) ? $req[0]['nbr'] : 0;

        //Nombre total de demande encour
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(imgDemandes.id) as nbr")
            ->addfrom('ImgDemandes', 'imgDemandes')
            ->andWhere( "(date(imgDemandes.date) between :date1: AND :date2:) AND imgDemandes.etat='encours'",
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $totalEncour = (count($req)>0) ? $req[0]['nbr'] : 0;
        $this->view->totalEncour = (count($req)>0) ? $req[0]['nbr'] : 0;

        //Nombre total de demande clotûré
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(imgDemandes.id) as nbr")
            ->addfrom('ImgDemandes', 'imgDemandes')
            ->andWhere( "(date(imgDemandes.date) between :date1: AND :date2:) AND imgDemandes.etat='clotûré'",
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $totalCloture = (count($req)>0) ? $req[0]['nbr'] : 0;
        $this->view->totalCloture = (count($req)>0) ? $req[0]['nbr'] : 0;

        //nouvelles demande
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("YEAR(imgDemandes.date) as annee, 
                MONTH(imgDemandes.date) as mois_chiffre, 
                MONTHNAME(imgDemandes.date) as mois, 
                COUNT(distinct imgDemandes.id) as nbr")
            ->addfrom('ImgDemandes', 'imgDemandes')
            ->join('Patients', 'imgDemandes.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( 'date(imgDemandes.date) between :date1: AND :date2:',
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
        $req = $builder->columns("COUNT(imgDemandes.id) as nbr, provenance as libelle")
            ->addfrom('ImgDemandes', 'imgDemandes')
            ->andWhere( " ( date(imgDemandes.date) between :date1: AND :date2: ) ",
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
        $req = $builder->columns("COUNT(imgDemandes.id) as nbr, prescripteur as libelle")
            ->addfrom('ImgDemandes', 'imgDemandes')
            ->andWhere( " ( date(imgDemandes.date) between :date1: AND :date2: ) ",
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('libelle')->orderBy('libelle ASC')->limit(10);
        $req = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS);
        $rs = array();
        for ($i = 0; $i < count($req); $i++) {
            $rs[$i]['nbr']      = $req[$i]->nbr;
            $rs[$i]['libelle']  = $req[$i]->libelle;
        }
        $this->view->demandeParPrescripteur = json_encode($rs, JSON_PRETTY_PRINT);

        //Par categorie
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("COUNT(imgDemandesDetails.id) as nbr, imgItemsCategories.libelle as libelle")
            ->addfrom('ImgDemandes', 'imgDemandes')
            ->join('ImgDemandesDetails', 'imgDemandesDetails.img_demandes_id = imgDemandes.id', 'imgDemandesDetails', 'INNER')
            ->join('ImgItems', 'imgDemandesDetails.img_items_id = imgItems.id', 'imgItems', 'INNER')
            ->join('ImgItemsCategories', 'imgItems.img_items_categories_id = imgItemsCategories.id', 'imgItemsCategories', 'INNER')
            ->andWhere( " ( date(imgDemandes.date) between :date1: AND :date2: ) ",
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('libelle')->orderBy('libelle ASC')->limit(10);
        $req = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS);
        $rs = array();
        for ($i = 0; $i < count($req); $i++) {
            $rs[$i]['nbr']      = $req[$i]->nbr;
            $rs[$i]['libelle']  = $req[$i]->libelle;
        }
        $this->view->demandeParCategorie = json_encode($rs, JSON_PRETTY_PRINT);

    }

}