<?php
use Phalcon\Mvc\Model\Resultset;

class ConsultationController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('Dossier de consultation');
        parent::initialize();
    }


    public function indexAction() {
        
        
    }

    public function ajaxCim10Action() {
        $this->view->disable();
        
        $cim10 = Cim10::find();

        $rs = [];
        for($i = 0; $i < count($cim10); $i++) {
            $rs[$i]['id'] = $cim10[$i]->LID;
            $rs[$i]['libelle'] = $cim10[$i]->FR_OMS;
        }
        
        echo json_encode($rs, JSON_PRETTY_PRINT);
    }

     public function ajaxDiagnosticAction() {
        $this->view->disable();
        
        $diagnostique = DiagnosticSource::find();

        $rs = [];
        for($i = 0; $i < count($diagnostique); $i++) {
            $rs[$i]['id'] = $diagnostique[$i]->id;
            $rs[$i]['libelle'] = $diagnostique[$i]->libelle;
        }
        
        echo json_encode($rs, JSON_PRETTY_PRINT);
    }


    public function consultationAction($patient_id = 0, $id = 0) {
        
        if($patient_id == 0)
        {
            $this->flash->error("Veuillez choisir un patient pour ouvrir un dossier de consultation");
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

        $dossiers_list = DossiersConsultations::find(array('conditions' => 'patients_id = :p:',
                                                            "bind"      => array('p' => $patient_id)
                                                            ));
        $rs = [];
        for($i = 0; $i < count($dossiers_list); $i++) {
            $rs[$i]['id'] = $dossiers_list[$i]->id;
            $rs[$i]['patients_id'] = $dossiers_list[$i]->patients_id;
            $rs[$i]['form_type_id'] = $dossiers_list[$i]->form_type_id;
            $rs[$i]['motif'] = $dossiers_list[$i]->motif;
            $rs[$i]['date_creation'] = date('d/m/Y H:i:m', strtotime($dossiers_list[$i]->date_creation));
            $medecin = $dossiers_list[$i]->getUser();
            $rs[$i]['medecin'] = $medecin->nom . " " . $medecin->prenom;
        }
        $formList    = [];
        if($this->session->get('usr')["forms_assoc"] != ''){
            $userFilter = " AND id IN (" . $this->session->get('usr')["forms_assoc"] . ")";
            $formListReq = Forms::find( array('conditions' => "type = 'base' " . $userFilter ) );
            for($i = 0; $i < count($formListReq); $i++) {
                $formList[$i]['id']         = $formListReq[$i]->id;
                $formList[$i]['libelle']    = $formListReq[$i]->libelle;
            }
        }
        $this->view->formList   = $formList;

        $this->view->dossiers_list   = json_encode($rs, JSON_PRETTY_PRINT);
        $this->view->patient = $patient;     
        $this->view->patient_residence = ( $tmp = $patient->getResidence() ) ? $tmp->libelle : ""; 
    }


    public function createConsultationAction($patients_id = 0, $consultation_id = 0, $formType_id = 0) {                
        if($patients_id <= 0){
            $this->flash->error("Une erreur c'est produit. Il semble que les informations du patients sont corrompues");
            return $this->response->redirect("patients/index");
        }

        $patient = Patients::findFirst($patients_id);
        $patient = Patients::findFirst($patients_id);
        //on enleve le patient de la liste d'attente
        $query = $this->modelsManager->createQuery("UPDATE ConsultationListeAttente SET etat = 1 WHERE patients_id = :id:");
        $exec  = $query->execute(array('id' => $patients_id));

        $this->view->patient = $patient;
        $dossier_constantes     = [];
        $dossier_prescriptions  = [];
        $dossier_examens        = [];
        $dossier_hypotheses     = [];
        $dossier_diagnostiques  = [];

        $f_id = 0;
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            //var_dump($data);exit();
            if($consultation_id > 0){
                $dossier_cons = DossiersConsultations::findFirst($consultation_id);
                if(!$dossier_cons){
                    $msg = $this->trans['on_error'];
                    $this->flash->error($msg);
                    return $this->response->redirect("consultation/consultation/" . $patients_id);
                }

                $formType_id = $dossier_cons->form_type_id;
                $f_id = $formType_id;
            }
            else{
                $this->view->form_action = 'create';
                $dossier_cons = new DossiersConsultations();

                $dossier_cons->form_type_id = $data['formType_id'];
                $f_id = $data['formType_id'];
            }
            $hide_default = 0;
            if($f_id > 0){
                $formulaire     = Forms::findFirst($f_id);
                $hide_default   = ($formulaire) ? $formulaire->hide_default : 0;
            }

            $dossier_cons->patients_id      = $data['patient_id'];
            $dossier_cons->user_id          = $this->session->get('usr')['id'];
            $dossier_cons->date_creation    = date('Y-m-d H:i:s', time());
            $dossier_cons->etat             = 'ouvert';
            if($hide_default == 0){
                $dossier_cons->motif                = $data['motif'];
                $dossier_cons->debut_maladie        = $data['debut_maladie'];
                $dossier_cons->debut_maladie_periode    = $data['debut_maladie_periode'];
                $dossier_cons->histoire             = $data['histoire'];
                $dossier_cons->examen_clinique      = $data['examen_clinique'];
                $dossier_cons->commentaire          = $data['commentaire'];
                $dossier_cons->resultat_exam_comp   = $data['resultat_exam_comp'];
                $dossier_cons->resume               = $data['resume'];
                $dossier_cons->refere_asc           = $data['refere_asc'];
                $dossier_cons->poids                = $data['poids'];
                $dossier_cons->taille               = $data['taille'];
            }

            if (!$dossier_cons->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return;
            }
            else{
                //Diagnostique - Hypothese
                //On supprime tout d'abord
                $query = $this->modelsManager->createQuery("DELETE FROM ConsultationsDiagnostics WHERE dossiers_consultations_id = :id:");
                $exec  = $query->execute(array('id' => $dossier_cons->id));
                if(isset($data['diagnostique'])){
                    $i = 0;
                    while(isset($data['diagnostique'][$i]) && $data['diagnostique'][$i] != ""){
                        $consDiagnostics = new ConsultationsDiagnostics();
                        $consDiagnostics->libelle = $data['diagnostique'][$i];
                        $consDiagnostics->type = 'd';
                        $consDiagnostics->dossiers_consultations_id = $dossier_cons->id;
                        if (!$consDiagnostics->save()) {
                            $msg = "Les diagnostiques n'ont pas été correctement enregistrées. veuillez contacter l'admin";
                            $this->flash->error($msg);
                        }
                        $i++;
                    }
                }
                if(isset($data['hypothese'])){
                    $i = 0;
                    while(isset($data['hypothese'][$i]) && $data['hypothese'][$i] != ""){
                        $consDiagnostics = new ConsultationsDiagnostics();
                        $consDiagnostics->libelle = $data['hypothese'][$i];
                        $consDiagnostics->type = 'h';
                        $consDiagnostics->dossiers_consultations_id = $dossier_cons->id;
                        if (!$consDiagnostics->save()) {
                            $msg = "Les hyphothese diagnostiques n'ont pas été correctement enregistrées. veuillez contacter l'admin";
                            $this->flash->error($msg);
                        }
                        $i++;
                    }
                }

                //Prescription
                //On supprime tout d'abord
                $query = $this->modelsManager->createQuery("DELETE FROM ConsultationsPrescriptions WHERE dossiers_consultations_id = :id:");
                $exec  = $query->execute(array('id' => $dossier_cons->id));
                $query = $this->modelsManager->createQuery("DELETE FROM PharmacieWorkFlow WHERE entity_id = :id: AND entity_type ='c' ");
                $exec  = $query->execute(array('id' => $dossier_cons->id));

                $this->savePrescrition($data, 'c', $dossier_cons->id, $patients_id);

                //Examen
                //On supprime tout d'abord
                $query = $this->modelsManager->createQuery("DELETE FROM ConsultationsExamen WHERE dossiers_consultations_id = :id:");
                $exec  = $query->execute(array('id' => $dossier_cons->id));

                if(isset($data['exam_type'])){
                    $i = 0;
                    while(isset($data['exam_type'][$i]) && $data['exam_type'][$i] != "" && $data['exam_libelle'][$i] != ""){
                        $exmamens = new ConsultationsExamen();
                        $exmamens->type = $data['exam_type'][$i];
                        $exmamens->libelle = $data['exam_libelle'][$i];
                        $exmamens->resultat = $data['exam_resultat'][$i];
                        $exmamens->dossiers_consultations_id = $dossier_cons->id;

                        if (!$exmamens->save()) {
                            $msg = "Les examens complementaires n'ont pas été correctement enregistrées. veuillez contacter l'admin";
                            $this->flash->error($msg);
                        }
                        $i++;
                    }
                }

                //Constante
                //On supprime tout d'abord
                $query = $this->modelsManager->createQuery("DELETE FROM ConsultationsConstantes WHERE dossiers_consultations_id = :id:");
                $exec  = $query->execute( array('id' => $dossier_cons->id) );
                
                if(isset($data['cons_cle'])){
                    $i = 0;
                    while(isset($data['cons_cle'][$i]) && $data['cons_cle'][$i] != "" && $data['cons_valeur'][$i] != ""){
                        $constantes = new ConsultationsConstantes();
                        $constantes->cle = $data['cons_cle'][$i];
                        $constantes->valeur = $data['cons_valeur'][$i];
                        $constantes->dossiers_consultations_id = $dossier_cons->id;

                        if (!$constantes->save()) {
                            $msg = "Les constantes n'ont pas été correctement enregistrées. veuillez contacter l'admin";
                            $this->flash->error($msg);
                        }
                        $i++;
                    }
                }

                //Motifs
                //On supprime tout d'abord
                $query = $this->modelsManager->createQuery("DELETE FROM ConsultationsMotifs WHERE dossiers_consultations_id = :id:");
                $exec  = $query->execute( array('id' => $dossier_cons->id) );
                if(isset($data["motifs"])){
                    foreach ($data["motifs"] as $motif) {
                        $tmpMotifs = new ConsultationsMotifs();
                        $tmpMotifs->dossiers_consultations_id   = $dossier_cons->id;
                        $tmpMotifs->cs_motifs_id                = $motif;
                        $tmpMotifs->save();
                    }
                }

                //Examen Comp
                //On supprime tout d'abord
                $query = $this->modelsManager->createQuery("DELETE FROM ConsultationsExamen WHERE dossiers_consultations_id = :id:");
                $exec  = $query->execute(array('id' => $dossier_cons->id));
                if(isset($data["exam_comps"])){
                    foreach ($data["exam_comps"] as $exam_comp) {
                        $tmpExamComp = new ConsultationsExamen();
                        $tmpExamComp->dossiers_consultations_id = $dossier_cons->id;
                        $tmpExamComp->actes_id                  = $exam_comp;
                        $tmpExamComp->save();
                    }
                }

                //Custom field
                $this->_saveCustomField($data, 'c', $dossier_cons->id);
            }
            $this->flash->success($this->trans['Consultation enregistrée']);
            $this->response->redirect("consultation/consultation/" . $patients_id);
            return;
        }
        else{            
            $this->view->form_action = 'create';
            $consultation_constantes = [];

            if($consultation_id > 0){
                $dossier_cons = DossiersConsultations::findFirst($consultation_id);
                if(!$dossier_cons){
                    $msg = $this->trans['on_error'];
                    $this->flash->error($msg);
                    return $this->response->redirect("consultation/consultation/" . $patients_id . "/" . $consultation_id );
                }
                else{
                    foreach ($dossier_cons->getConsultationsConstantes() as $k => $v) {
                        $dossier_constantes[$k]['id'] = $v->id;
                        $dossier_constantes[$k]['cle'] = $v->cle;
                        $dossier_constantes[$k]['valeur'] = $v->valeur;
                    }

                    foreach ($dossier_cons->getConsultationsPrescriptions() as $k => $v) {
                        $dossier_prescriptions[$k]['id'] = $v->id;
                        $dossier_prescriptions[$k]['medicament_id'] = $v->medicament_id;
                        $dossier_prescriptions[$k]['medicament'] = $v->medicament;
                        $dossier_prescriptions[$k]['quantite'] = $v->quantite;
                        $dossier_prescriptions[$k]['mode'] = $v->mode;
                        $dossier_prescriptions[$k]['posologie'] = $v->posologie;
                        $dossier_prescriptions[$k]['duree'] = $v->duree;
                    }

                    foreach ($dossier_cons->getConsultationsDiagnostics(array("type='h'")) as $k => $v) {
                        $dossier_hypotheses[] = $v->libelle;
                    }
                    foreach ($dossier_cons->getConsultationsDiagnostics(array("type='d'")) as $k => $v) {
                        $dossier_diagnostiques[] = $v->libelle;
                    }
                }

                $tmpMotif = [];
                foreach ($dossier_cons->getConsultationsMotifs() as $k => $v) {
                    $tmpMotif[] = $v->cs_motifs_id;
                }
                $tmpExamComp = [];
                foreach ($dossier_cons->getConsultationsExamen() as $k => $v) {
                    $tmpExamComp[] = $v->actes_id;
                }
                Phalcon\Tag::setDefaults(array(
                        "id" => $dossier_cons->id,
                        "type" => $dossier_cons->type,
                        "patients_id" => $dossier_cons->patients_id,
                        "user_id" => $dossier_cons->user_id,
                        "motif" => $dossier_cons->motif,
                        "debut_maladie" => $dossier_cons->debut_maladie,
                        "debut_maladie_periode" => $dossier_cons->debut_maladie_periode,
                        "date_creation" => $dossier_cons->date_creation,
                        "histoire" => $dossier_cons->histoire,
                        "examen_clinique" => $dossier_cons->examen_clinique,
                        "commentaire" => $dossier_cons->commentaire,
                        /*"hypothese" => $dossier_cons->hypothese,
                        "diagnostique" => $dossier_cons->diagnostique,*/
                        "confidentialite" => $dossier_cons->confidentialite,
                        "motifs[]" => $tmpMotif,
                        "exam_comps[]" => $tmpExamComp,
                        "resume" => $dossier_cons->resume,
                        "resultat_exam_comp" => $dossier_cons->resultat_exam_comp,
                        "refere_asc" => $dossier_cons->refere_asc,
                        "poids" => $dossier_cons->poids,
                        "taille" => $dossier_cons->taille,
                        "etat" => $dossier_cons->etat
                    ));
                $this->view->form_action = 'edit';
            }

            $this->view->disable();
            $this->view->formType_id        = $formType_id;
            $this->view->patients_id        = $patients_id;
            $this->view->consultation_id    = $consultation_id;
            
            $this->view->dossier_constantes     = $dossier_constantes;
            $this->view->dossier_prescriptions  = $dossier_prescriptions;
            $this->view->dossier_examens        = $dossier_examens;
            $this->view->dossier_hypotheses     = $dossier_hypotheses;
            $this->view->dossier_diagnostiques  = $dossier_diagnostiques;

            $config = $this->getStructureConfig();
            if($config->default_constante != ""){
                $this->view->default_constantes = explode(",", $config->default_constante);
            }
            else{
                $this->view->default_constantes = array();
            }

            if($config->default_examen != ""){
                $tmp = explode(",", $config->default_examen);
                foreach ($tmp as $k => $value) {
                    $default_examens[$k]['type']    = explode("/", $value)[0];
                    $default_examens[$k]['libelle'] = explode("/", $value)[1];
                }
                $this->view->default_examens = $default_examens;
            }
            else{
                $this->view->default_examens = array();
            }

            if($formType_id != 0){
                $this->_getCustomForm($formType_id, 'c', $consultation_id);
            }
            else{
                $this->view->formulaireElements = [];
            }

            $this->view->formType_id = $formType_id;

            //ONGLET FORM
            $this->_getCustomOnglet('c', $consultation_id, $formType_id);
            
            //Motif list
            $motifs = CsMotifs::find();
            $rs = [];
            foreach ($motifs as $motif) {
                $rs[$motif->id] = $motif->libelle;
            }
            $this->view->motifs = $rs;

            //Examen Comp list
            $exam_comps = Actes::find(array("type IN ('labo', 'imagerie')"));
            $rs = [];
            foreach ($exam_comps as $exam_comp) {
                $rs[$exam_comp->id] = $exam_comp->libelle;
            }
            $this->view->exam_comps = $rs;

            $this->view->partial('consultation/createConsultation');
        }

    }

    private function _getCustomForm($formType_id, $entity_type, $entity_id){
        $formulaire     = Forms::findFirst($formType_id);
        $formsElements  = $formulaire->getFormsElements(array("order" => "position ASC"));

        $formulaireElements = [];
        foreach ($formsElements as $l => $elem) {

            $formulaireElements[$l]['id']               = $elem->id;
            $formulaireElements[$l]['libelle']          = $elem->libelle;
            $formulaireElements[$l]['position']         = $elem->position;
            $formulaireElements[$l]['type_valeur']      = $elem->type_valeur;
            $formulaireElements[$l]['valeur_possible']  = explode(",", $elem->valeur_possible);
            $formulaireElements[$l]['place_after_c']    = $elem->place_after_c;
            $formulaireElements[$l]['place_after_s']    = $elem->place_after_s;
            $formulaireElements[$l]['required']         = $elem->required;

            $rsTmp = FormsResults::findFirst("entity_type = '" . $entity_type . "' AND entity_id = " . $entity_id . " AND forms_elements_id = " . $elem->id);
            if($rsTmp){
                $formulaireElements[$l]['r_id']     = $rsTmp->id;
                $formulaireElements[$l]['r_valeur'] = $rsTmp->valeur;
            }else{
                $formulaireElements[$l]['r_id']     = "";
                $formulaireElements[$l]['r_valeur'] = "";
            }

        }

        $this->view->formulaire         = $formulaire;
        $this->view->formulaireElements = $formulaireElements;
    }

    private function _getCustomOnglet($entity_type, $entity_id, $formType_id = 0){
        
        $formulaire = Forms::findFirst($formType_id);
        if($formulaire->forms_assoc == ""){
            $this->view->ongletList = [];
        }
        else{
            $formulaireOnglet = Forms::find(array("type = 'onglet' AND id IN (" . $formulaire->forms_assoc . ")"));
            $ongletList = [];
            foreach ($formulaireOnglet as $k => $onglet) {
                $ongletList[$k]['id']       = $onglet->id;
                $ongletList[$k]['libelle']  = $onglet->libelle;
                $ongletList[$k]['code']     = $onglet->code;
                $ongletList[$k]['type']     = $onglet->type;
                $ongletList[$k]['fields']   = [];

                //Les champs
                $tmpField  = $onglet->getFormsElements(array("order" => "position ASC"));
                foreach ($tmpField as $l => $elem) {

                    $ongletList[$k]['fields'][$l]['id']               = $elem->id;
                    $ongletList[$k]['fields'][$l]['libelle']          = $elem->libelle;
                    $ongletList[$k]['fields'][$l]['position']         = $elem->position;
                    $ongletList[$k]['fields'][$l]['type_valeur']      = $elem->type_valeur;
                    $ongletList[$k]['fields'][$l]['valeur_possible']  = explode(",", $elem->valeur_possible);
                    $ongletList[$k]['fields'][$l]['place_after_c']    = $elem->place_after_c;
                    $ongletList[$k]['fields'][$l]['place_after_s']    = $elem->place_after_s;
                    $ongletList[$k]['fields'][$l]['required']         = $elem->required;

                    $rsTmp = FormsResults::findFirst("entity_type = '" . $entity_type . "' AND entity_id = " . $entity_id . " AND forms_elements_id = " . $elem->id);
                    if($rsTmp){
                        $ongletList[$k]['fields'][$l]['r_id']     = $rsTmp->id;
                        $ongletList[$k]['fields'][$l]['r_valeur'] = $rsTmp->valeur;
                    }else{
                        $ongletList[$k]['fields'][$l]['r_id']     = "";
                        $ongletList[$k]['fields'][$l]['r_valeur'] = "";
                    }

                }
            }
            usort($ongletList, function($a, $b){
                return strcmp($a['libelle'], $b['libelle']);
            });
            $this->view->ongletList = $ongletList;
        }
    }

    public function createSuiviAction($patients_id = 0, $consultation_id = 0, $suivi_id = 0) {  

        $dossier_constantes = [];
        $dossier_prescriptions = [];
        $dossier_examens = [];

        if($consultation_id <= 0 || $patients_id <= 0){
            $this->flash->error("Une erreur c'est produit. Il semble que les informations du patients sont corrompues");
            return $this->response->redirect("patients/index");
        }

        $patient = Patients::findFirst($patients_id);
        //on enleve le patient de la liste d'attente
        $query = $this->modelsManager->createQuery("UPDATE ConsultationListeAttente SET etat = 1 WHERE patients_id = :id:");
        $exec  = $query->execute(array('id' => $patients_id));
        
        $this->view->patient = $patient;
        if ($this->request->isPost()) {
            $data = $this->request->getPost();

            if($suivi_id > 0){
                $dossier_suivi = Consultations::findFirst($suivi_id);
                if(!$dossier_suivi){
                    $msg = $this->trans['on_error'];
                    $this->flash->error($msg);
                    return $this->response->redirect("consultation/consultation/" . $patients_id);
                }
            }
            else{
                $this->view->form_action = 'create';
                $dossier_suivi = new Consultations();
            }

            $dossier_suivi->dossiers_consultations_id   = $consultation_id;
            $dossier_suivi->user_id                     = $this->session->get('usr')['id'];
            $dossier_suivi->observation                 = $data['observation'];
            $dossier_suivi->resultat_exam_comp          = $data['resultat_exam_comp'];
            $dossier_suivi->poids                       = $data['poids'];
            $dossier_suivi->taille                      = $data['taille'];
            $dossier_suivi->date_creation               = date('Y-m-d H:i:s', time());

            if (!$dossier_suivi->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return;
            }
            else{

                //Prescription
                //On supprime tout d'abord
                $query = $this->modelsManager->createQuery("DELETE FROM ConsultationsPrescriptions WHERE consultations_id = :id:");
                $exec  = $query->execute(array('id' => $dossier_suivi->id));
                $query = $this->modelsManager->createQuery("DELETE FROM PharmacieWorkFlow WHERE entity_id = :id: AND entity_type ='s' ");
                $exec  = $query->execute(array('id' => $dossier_suivi->id));

                $this->savePrescrition($data, 's', $dossier_suivi->id, $patients_id);

                //Examen Comp
                //On supprime tout d'abord
                $query = $this->modelsManager->createQuery("DELETE FROM ConsultationsExamen WHERE consultations_id = :id:");
                $exec  = $query->execute(array('id' => $dossier_suivi->id));
                if(isset($data["exam_comps"])){
                    foreach ($data["exam_comps"] as $exam_comp) {
                        $tmpExamComp = new ConsultationsExamen();
                        $tmpExamComp->consultations_id  = $dossier_suivi->id;
                        $tmpExamComp->actes_id          = $exam_comp;
                        $tmpExamComp->save();
                    }
                }

                //Constante
                //On supprime tout d'abord
                $query = $this->modelsManager->createQuery("DELETE FROM ConsultationsConstantes WHERE consultations_id = :id:");
                $exec  = $query->execute( array('id' => $dossier_suivi->id) );

                if(isset($data['cons_cle'])){
                    $i = 0;
                    while(isset($data['cons_cle'][$i]) && $data['cons_cle'][$i] != "" && $data['cons_valeur'][$i] != ""){
                        $constantes                     = new ConsultationsConstantes();
                        $constantes->cle                = $data['cons_cle'][$i];
                        $constantes->valeur             = $data['cons_valeur'][$i];
                        $constantes->consultations_id   = $dossier_suivi->id;

                        if (!$constantes->save()) {
                            $msg = "Les constantes n'ont pas été correctement enregistrées. veuillez contacter l'admin";
                            $this->flash->error($msg);
                        }
                        $i++;
                    }
                }

            }
            $this->flash->success($this->trans['Suivi enregistré avec succès']);
            $this->response->redirect("consultation/consultation/" . $patients_id);
            return;
        }
        else{     
            $this->view->form_action = 'create';

            if($suivi_id > 0){
                $dossier_suivi = Consultations::findFirst($suivi_id);
                if(!$dossier_suivi){
                    $msg = $this->trans['on_error'];
                    $this->flash->error($msg);
                    return $this->response->redirect("consultation/consultation/" . $patients_id . "/" . $consultation_id );
                }
                else{
                    foreach ($dossier_suivi->getConsultationsConstantes() as $k => $v) {
                        $dossier_constantes[$k]['id']       = $v->id;
                        $dossier_constantes[$k]['cle']      = $v->cle;
                        $dossier_constantes[$k]['valeur']   = $v->valeur;
                    }

                    foreach ($dossier_suivi->getConsultationsPrescriptions() as $k => $v) {
                        $dossier_prescriptions[$k]['id']            = $v->id;
                        $dossier_prescriptions[$k]['medicament_id'] = $v->medicament_id;
                        $dossier_prescriptions[$k]['medicament']    = $v->medicament;
                        $dossier_prescriptions[$k]['quantite']      = $v->quantite;
                        $dossier_prescriptions[$k]['mode']          = $v->mode;
                        $dossier_prescriptions[$k]['posologie']     = $v->posologie;
                        $dossier_prescriptions[$k]['duree']         = $v->duree;
                    }

                }

                $tmpExamComp = [];
                foreach ($dossier_suivi->getConsultationsExamen() as $k => $v) {
                    $tmpExamComp[] = $v->actes_id;
                }
                Phalcon\Tag::setDefaults(array(
                        "id"                    => $dossier_suivi->id,
                        "observation"           => $dossier_suivi->observation,
                        "exam_comps[]"          => $tmpExamComp,
                        "resultat_exam_comp"    => $dossier_suivi->resultat_exam_comp,
                        "poids"                 => $dossier_suivi->poids,
                        "taille"                => $dossier_suivi->taille
                    ));
                $this->view->form_action = 'edit';
            }

            $this->view->disable();
           

            //On recupere les informations pour rappel
            $patient = Patients::findFirst($patients_id);
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

            $last_constantes    = [];
            $last_prescriptions = [];
            $last_hypotheses = [];
            $last_diagnostiques = [];

            $_dossier = DossiersConsultations::findFirst($consultation_id);

            foreach ($_dossier->getConsultationsDiagnostics(array("type='h'")) as $k => $v) {
                $last_hypotheses[] = $v->libelle;
            }
            foreach ($_dossier->getConsultationsDiagnostics(array("type='d'")) as $k => $v) {
                $last_diagnostiques[] = $v->libelle;
            }

            $_suivi = $_dossier->getConsultations( array('order' => 'date_creation desc') );
            if( count($_suivi) > 0 ){
                foreach ($_suivi[0]->getConsultationsConstantes() as $k => $v) {
                    $last_constantes[$k]['id']      = $v->id;
                    $last_constantes[$k]['cle']     = $v->cle;
                    $last_constantes[$k]['valeur']  = $v->valeur;
                }

                foreach ($_suivi[0]->getConsultationsPrescriptions() as $k => $v) {
                    $last_prescriptions[$k]['id']           = $v->id;
                    $last_prescriptions[$k]['medicament_id']   = $v->medicament_id;
                    $last_prescriptions[$k]['medicament']   = $v->medicament;
                    $last_prescriptions[$k]['quantite']     = $v->quantite;
                    $last_prescriptions[$k]['mode']         = $v->mode;
                    $last_prescriptions[$k]['posologie']    = $v->posologie;
                    $last_prescriptions[$k]['duree']        = $v->duree;
                }
            }
            else{
                foreach ($_dossier->getConsultationsConstantes() as $k => $v) {
                    $last_constantes[$k]['id']      = $v->id;
                    $last_constantes[$k]['cle']     = $v->cle;
                    $last_constantes[$k]['valeur']  = $v->valeur;
                }

                foreach ($_dossier->getConsultationsPrescriptions() as $k => $v) {
                    $last_prescriptions[$k]['id']           = $v->id;
                    $last_prescriptions[$k]['medicament_id']   = $v->medicament_id;
                    $last_prescriptions[$k]['medicament']   = $v->medicament;
                    $last_prescriptions[$k]['quantite']     = $v->quantite;
                    $last_prescriptions[$k]['mode']         = $v->mode;
                    $last_prescriptions[$k]['posologie']    = $v->posologie;
                    $last_prescriptions[$k]['duree']        = $v->duree;
                }
            }


            $this->view->last_hypotheses     = $last_hypotheses;
            $this->view->last_diagnostiques  = $last_diagnostiques;
            $this->view->last_constantes     = $last_constantes;
            $this->view->last_prescriptions  = $last_prescriptions;
            

            $this->view->patients_id            = $patients_id;
            $this->view->consultation_id        = $consultation_id;
            $this->view->suivi_id               = $suivi_id;

            $this->view->dossier_constantes     = $dossier_constantes;
            $this->view->dossier_prescriptions  = $dossier_prescriptions;
            $this->view->dossier_examens        = $dossier_examens;

            $config = $this->getStructureConfig();
            if($config->default_constante != ""){
                $this->view->default_constantes = explode(",", $config->default_constante);
            }
            else{
                $this->view->default_constantes = array();
            }

            if($config->default_examen != ""){
                $tmp = explode(",", $config->default_examen);
                foreach ($tmp as $k => $value) {
                    $default_examens[$k]['type'] = explode("/", $value)[0];
                    $default_examens[$k]['libelle'] = explode("/", $value)[1];
                }
                $this->view->default_examens = $default_examens;
            }
            else{
                $this->view->default_examens = array();
            }

            //Examen Comp list
            $exam_comps = Actes::find(array("type IN ('labo', 'imagerie')"));
            $rs = [];
            foreach ($exam_comps as $exam_comp) {
                $rs[$exam_comp->id] = $exam_comp->libelle;
            }
            $this->view->exam_comps = $rs;

            $this->view->partial('consultation/createSuivi');
        }

    }

    private function savePrescrition($data, $type, $id, $patients_id){

        if(isset($data['medicament'])){
            $tmpPrescWorkFlowAvailable = [];
            $tmpPrescWorkFlowNotAvailable = [];
            $i = 0;
            while(isset($data['medicament'][$i]) && $data['medicament'][$i] != ""){

                $prescription = new ConsultationsPrescriptions();
                $prescription->medicament_id    = $data['medicament_id'][$i];
                $prescription->medicament       = $data['medicament'][$i];
                $prescription->quantite         = isset($data['quantite'][$i])  ? $data['quantite'][$i]     : "";
                $prescription->mode             = isset($data['mode'][$i])      ? $data['mode'][$i]         : "";
                $prescription->posologie        = isset($data['posologie'][$i]) ? $data['posologie'][$i]    : "";
                $prescription->duree            = isset($data['duree'][$i])     ? $data['duree'][$i]        : "";
                if($type == "c"){
                    $prescription->dossiers_consultations_id = $id;
                }
                if($type == "s"){
                    $prescription->consultations_id = $id;
                }

                if($prescription->medicament_id != ""){
                    $tmpPrescWorkFlowAvailable[][$prescription->medicament_id]  = $prescription->quantite;
                }else{
                    $tmpPrescWorkFlowNotAvailable[][$prescription->medicament]    = $prescription->quantite;
                }
                if (!$prescription->save()) {
                    $msg = "Les prescriptions n'ont pas été correctement enregistrées. veuillez contacter l'admin";
                    $this->flash->error($msg);
                }
                $i++;
            }
            if(!empty($tmpPrescWorkFlowAvailable) || !empty($tmpPrescWorkFlowNotAvailable)){
                $pharmacieWorkFlow                  = new PharmacieWorkFlow();
                $pharmacieWorkFlow->available       = json_encode($tmpPrescWorkFlowAvailable);
                $pharmacieWorkFlow->not_available   = json_encode($tmpPrescWorkFlowNotAvailable);
                $pharmacieWorkFlow->patients_id     = $patients_id;
                $pharmacieWorkFlow->entity_id       = $id;
                $pharmacieWorkFlow->entity_type     = $type;
                $pharmacieWorkFlow->save();
            }
        }

    }

    private function _saveCustomField($data, $entity_type, $entity_id){
        foreach ($data as $key => $valeur_resultat) {
            if(strpos($key, "elem_") !== false){
                $elem_id = explode("_", $key)[1];
                $result = FormsResults::findFirst(array("forms_elements_id = " . $elem_id . " AND entity_id = " . $entity_id . " AND entity_type = '" . $entity_type . "' "));
                if (!$result) {
                    $result = new FormsResults();
                }

                $result->valeur = $valeur_resultat;
                $result->entity_type        = $entity_type;
                $result->entity_id          = $entity_id;
                $result->forms_elements_id  = $elem_id;

                if (!$result->save()) {
                    $msg = "Le resultat d'un champs personnalisé n'a pas été correctement enregistrées. veuillez contacter l'admin";
                    $this->flash->error($msg);
                }
            }
        }
    }

    public function dossierSuivisAction($id) {
        $this->view->disable();
        $suivi_list = Consultations::find(array('conditions' => 'dossiers_consultations_id = :d:',
                                                            "bind"      => array('d' => $id)
                                                            ));
        $rs = [];
        for($i = 0; $i < count($suivi_list); $i++) {
            $rs[$i]['id'] = $suivi_list[$i]->id;
            $rs[$i]['dossiers_consultations_id'] = $suivi_list[$i]->dossiers_consultations_id;
            $rs[$i]['patients_id'] = $suivi_list[$i]->getDossiersConsultations()->patients_id;
            $rs[$i]['form_type_id'] = $suivi_list[$i]->form_type_id;
            $rs[$i]['observation'] = $suivi_list[$i]->observation;
            $rs[$i]['date_creation'] = date('d/m/Y H:i:m', strtotime($suivi_list[$i]->date_creation));
            $medecin = $suivi_list[0]->getUser();
            $rs[$i]['medecin'] = $medecin->nom . " " . $medecin->prenom;
        }

        $this->view->suivi_list   = $rs;
        $this->view->dossier_id = $id;

        $this->view->partial("consultation/dossierSuivis");
    }

    public function antecedantPopoverAction($patients_id = 0, $id = 0) {
        
        $this->view->disable();

        if($id != 0){
            $current = PatientsAntecedant::findFirst($id);
            Phalcon\Tag::setDefaults(array(
                        "type" => $current->type,
                        "libelle" => $current->libelle,
                        "niveau" => $current->niveau
                    ));
        }

        $this->view->id = $id;
        $this->view->patients_id = $patients_id;

        $this->view->partial("consultation/antecedantPopover");
    }

    public function deleteAntecedantAction($id) {
        $this->view->disable();

        $current = PatientsAntecedant::findFirst($id);
        if(!$current){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$current->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }

    public function listeAction(){
        
        $date1 = $date2 = date("Y-m-d");
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

        $builder = $this->modelsManager->createBuilder();
        $dossiers = $builder->columns("dossiersConsultations.id, dossiersConsultations.date_creation, 
                patients.id as patient_id, CONCAT(patients.prenom, ' ', patients.nom) as patients_nom, 
                user.id as medecin_, CONCAT(user.prenom, ' ', user.nom) as medecin_nom")
            ->addfrom('DossiersConsultations', 'dossiersConsultations')
            ->join('Patients', 'patients.id = dossiersConsultations.patients_id', 'patients', 'INNER')
            ->join('User', 'user.id = dossiersConsultations.user_id', 'user', 'INNER')
            ->andWhere( 'date(dossiersConsultations.date_creation) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );

        $dossiers = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();

        $this->view->dossiers   = json_encode($dossiers, JSON_PRETTY_PRINT);
    }

    public function exportCustomFormAction(){
        
        $form_id    = 0;
        if($this->request->get("date1")){
            $data = $this->request->get();
            if($data['date1'] != "" && $data['date2'] != ""){
                $date1      = $data['date1'];
                $date2      = $data['date2'];
                $form_id    = $data['form_id'];
            }
        }
        else{
            $date1 = date('Y-m-d',strtotime("last Monday"));
            $date2 = date("Y-m-d");
        }
        $formulaireIds = $form_id;
        $formulaire = Forms::findFirst($form_id);
        if($formulaire && $formulaire->forms_assoc != ""){
            $formulaireIds .= ", " . $formulaire->forms_assoc;
        }

        Phalcon\Tag::setDefaults(array(
                "date1"     => $date1,
                "date2"     => $date2,
                "form_id"   => $form_id
        ));

        $final      = [];
        $finalKey   = [];
        if($form_id != 0){
            $finalKey = array("ds_date_consultation", "ds_debut_maladie", "ds_debut_maladie_periode", "ds_medecin_nom", "ds_patients_id_technique", "ds_patients_nom", "ds_patients_sexe", "ds_patients_age", "ds_patients_residence", "ds_patients_adresse", "ds_patients_ethnie", "ds_patients_profession", "ds_nouvelle_cs", "ds_ancienne_cs", "ds_refere_asc", "ds_poids", "ds_taille", "ds_poids_taille", "ds_taille_age", "ds_imc", "ds_motif", "ds_exam_labo", "ds_hypothese_diagnostic", "ds_diagnostic", "ds_traitement", "ds_commentaire");

            $builder = $this->modelsManager->createBuilder();
            $result = $builder->columns("ds.date_creation as ds_date_consultation, ds.debut_maladie as ds_debut_maladie, ds.debut_maladie_periode as ds_debut_maladie_periode, ds.id as ds_id_consultation, f.id as id_form, f.libelle as f_libelle, fe.libelle as e_libelle, fe.type_valeur as fe_type_valeur, fr.valeur as r_valeur, CONCAT(p.prenom, ' ', p.nom) as ds_patients_nom, p.id_technique as ds_patients_id_technique, p.sexe as ds_patients_sexe, p.adresse as ds_patients_adresse, DIALECT_GET_AGE(p.date_naissance) as ds_patients_age, rsd.libelle as ds_patients_residence, p.ethnie as ds_patients_ethnie, p.profession as ds_patients_profession, CONCAT(user.prenom, ' ', user.nom) as ds_medecin_nom, ds.refere_asc as ds_refere_asc, ds.poids as ds_poids, ds.taille as ds_taille, GROUP_CONCAT(distinct csmotif.libelle) as ds_motif, ds.resultat_exam_comp as ds_exam_labo, GROUP_CONCAT(distinct if(dsdiagnostic.type = 'h', dsdiagnostic.libelle, null)) as ds_hypothese_diagnostic, GROUP_CONCAT(distinct if(dsdiagnostic.type = 'd', dsdiagnostic.libelle, null)) as ds_diagnostic, GROUP_CONCAT(distinct dsprescription.medicament) as ds_traitement, ds.commentaire as ds_commentaire")
                ->addfrom('FormsResults', 'fr')
                ->join('FormsElements', 'fr.forms_elements_id = fe.id', 'fe', 'INNER')
                ->join('Forms', 'fe.forms_id = f.id', 'f', 'INNER')
                ->join('DossiersConsultations', 'fr.entity_id = ds.id', 'ds', 'INNER')
                ->join('Patients', 'ds.patients_id = p.id', 'p', 'INNER')
                ->join('Residence', 'rsd.id = p.residence_id', 'rsd', 'LEFT')
                ->join('User', 'user.id = ds.user_id', 'user', 'INNER')
                ->join('ConsultationsPrescriptions', 'dsprescription.dossiers_consultations_id = ds.id', 'dsprescription', 'LEFT')
                ->join('ConsultationsDiagnostics', 'dsdiagnostic.dossiers_consultations_id = ds.id', 'dsdiagnostic', 'LEFT')
                ->join('ConsultationsMotifs', 'dsmotif.dossiers_consultations_id = ds.id', 'dsmotif', 'LEFT')
                ->join('CsMotifs', 'csmotif.id = dsmotif.cs_motifs_id', 'csmotif', 'LEFT')
                ->andWhere( "date(ds.date_creation) between :date1: AND :date2: AND fr.entity_type = 'c' AND f.id IN (" . $formulaireIds . ")",
                                             array('date1' => $date1, 'date2' => $date2) )
                ->groupBy('ds_date_consultation, ds_debut_maladie, ds_debut_maladie_periode, ds_id_consultation, id_form, f_libelle, e_libelle, fe_type_valeur, r_valeur, ds_patients_nom, ds_patients_sexe, ds_patients_age, ds_patients_residence, ds_patients_adresse, ds_patients_ethnie, ds_patients_profession, ds_medecin_nom, ds_refere_asc, ds_poids, ds_taille, ds_exam_labo, ds_commentaire');

            $result = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                                  ->toArray();

            foreach ($result as $k => $value) {
                $cle = intval($value['ds_id_consultation']);
                //print_r($value);
                $final[$cle]['id_form']                 = $value['id_form'];
                //$final[$cle]['ds_id_consultation']      = $value['ds_id_consultation'];
                $final[$cle]['normal_date']             = $value['ds_date_consultation'];
                $final[$cle]['ds_date_consultation']    = date('d/m/Y h:i:s', strtotime($value['ds_date_consultation']));
                if($value['ds_debut_maladie'] != "" && $value['ds_debut_maladie'] != "0000-00-00"){
                    $final[$cle]['ds_debut_maladie']    = date('d/m/Y', strtotime($value['ds_debut_maladie']));
                    $final[$cle]['ds_debut_maladie_periode']    = $value['ds_debut_maladie_periode'];
                }
                else{
                    $final[$cle]['ds_debut_maladie']    = "";
                    $final[$cle]['ds_debut_maladie_periode']    = $value['ds_debut_maladie_periode'];
                }
                $final[$cle]['ds_medecin_nom']          = $value['ds_medecin_nom'];
                $final[$cle]['ds_patients_id_technique']    = $value['ds_patients_id_technique'];
                $final[$cle]['ds_patients_nom']         = $value['ds_patients_nom'];
                $final[$cle]['ds_patients_sexe']        = $value['ds_patients_sexe'];
                $final[$cle]['ds_patients_age']         = $value['ds_patients_age'];
                $final[$cle]['ds_patients_residence']   = $value['ds_patients_residence'];
                $final[$cle]['ds_patients_adresse']     = $value['ds_patients_adresse'];
                $final[$cle]['ds_patients_ethnie']      = $value['ds_patients_ethnie'];
                $final[$cle]['ds_patients_profession']  = $value['ds_patients_profession'];
                $final[$cle]['ds_nouvelle_cs']          = "oui";
                $final[$cle]['ds_ancienne_cs']          = "";
                $final[$cle]['ds_refere_asc']           = $value['ds_refere_asc'];
                $final[$cle]['ds_poids']                = $value['ds_poids'];
                $final[$cle]['ds_taille']               = $value['ds_taille'];
                if( ($value['ds_poids'] > 0 && $value['ds_taille'] > 0) ){
                    $final[$cle]['ds_poids_taille'] = number_format($value['ds_poids']/$value['ds_taille'],2,'.','');
                }
                else{
                    $final[$cle]['ds_poids_taille'] = "";
                }
                if( ($value['ds_taille'] > 0 && $value['ds_patients_age'] > 0) ){
                    $final[$cle]['ds_taille_age'] = number_format($value['ds_taille']/$value['ds_patients_age'],2,'.','');
                }
                else{
                    $final[$cle]['ds_taille_age'] = "";
                }
                if( ($value['ds_taille'] > 0 && $value['ds_poids'] > 0) ){
                    $tailleEnMettre = $value['ds_taille']/100;
                    $final[$cle]['ds_imc'] = number_format($value['ds_poids']/($tailleEnMettre*$tailleEnMettre),2,'.','');
                }
                else{
                    $final[$cle]['ds_imc'] = "";
                }

                $final[$cle]['ds_motif']                = $value['ds_motif'];
                $final[$cle]['ds_exam_labo']            = $value['ds_exam_labo'];
                $final[$cle]['ds_hypothese_diagnostic'] = $value['ds_hypothese_diagnostic'];
                $final[$cle]['ds_diagnostic']           = $value['ds_diagnostic'];
                $final[$cle]['ds_traitement']           = $value['ds_traitement'];
                $final[$cle]['ds_commentaire']          = $value['ds_commentaire'];



                if( ($value['fe_type_valeur'] == 'd') && ($value['r_valeur'] != "") ){
                    $final[$cle][$value['e_libelle']]  = date('d/m/Y', strtotime($value['r_valeur']));
                }
                else{
                    $final[$cle][$value['e_libelle']]  = $value['r_valeur'];
                }

                $finalKey[] = $value['e_libelle'];
            }

            $finalKey = array_unique($finalKey);
        }
        
        $columns[0] = [ 'title' => 'state', 'checkbox' => true ];
        foreach ($finalKey as $k => $value) {
           array_push($columns, [   'field' => $value,  'title' => $value, 
                                    'sortable' => true, 'filterControl' => "input" ] );
        }

        $columns    = array_values($columns);
        $final      = array_values($final);
        

        //print json_encode(array_values($columns), JSON_PRETTY_PRINT);exit();
        //print json_encode(array_values($final), JSON_PRETTY_PRINT);
        //print json_encode(array_values($finalKey), JSON_PRETTY_PRINT);exit();

        //$this->view->forms = Forms::find( array('conditions' => "type = 'base'") );
        $this->view->forms = Forms::find("type = 'base'");

        $this->view->columns  = json_encode($columns, JSON_PRETTY_PRINT);
        $this->view->result = json_encode($final, JSON_PRETTY_PRINT);


    }

    public function exportRTAAction(){
        
        if($this->request->get("date1")){
            $data = $this->request->get();
            if($data['date1'] != "" && $data['date2'] != ""){
                $date1      = $data['date1'];
                $date2      = $data['date2'];
            }
        }
        else{
            $date1 = date('Y-m-d',strtotime("last Monday"));
            $date2 = date("Y-m-d");
        }

        Phalcon\Tag::setDefaults(array(
                "date1"     => $date1,
                "date2"     => $date2
        ));

        $final      = [];
        $finalKey   = [];
        $finalKey = array("ds_date_consultation", "ds_medecin_nom", "ds_patients_id_technique", "ds_patients_nom", "ds_patients_sexe", "ds_patients_age", "ds_patients_residence", "ds_patients_ethnie", "ds_patients_profession", "ds_nouvelle_cs", "ds_ancienne_cs", "ds_refere_asc", "ds_poids", "ds_taille", "ds_poids_taille", "ds_taille_age", "ds_imc", "ds_motif", "ds_exam_labo", "ds_diagnostic", "ds_traitement", "ds_commentaire");

        $builder = $this->modelsManager->createBuilder();
        $result = $builder->columns("ds.date_creation as ds_date_consultation, ds.id as ds_id_consultation, CONCAT(p.prenom, ' ', p.nom) as ds_patients_nom, p.id_technique as ds_patients_id_technique, p.sexe as ds_patients_sexe, DIALECT_GET_AGE(p.date_naissance) as ds_patients_age, rsd.libelle as ds_patients_residence, p.ethnie as ds_patients_ethnie, p.profession as ds_patients_profession, CONCAT(user.prenom, ' ', user.nom) as ds_medecin_nom, ds.refere_asc as ds_refere_asc, ds.poids as ds_poids, ds.taille as ds_taille, GROUP_CONCAT(distinct csmotif.libelle) as ds_motif, ds.resultat_exam_comp as ds_exam_labo, GROUP_CONCAT(distinct if(dsdiagnostic.type = 'd', dsdiagnostic.libelle, null)) as ds_diagnostic, GROUP_CONCAT(distinct dsprescription.medicament) as ds_traitement, ds.commentaire as ds_commentaire")
            ->addfrom('DossiersConsultations', 'ds')
            ->join('Patients', 'ds.patients_id = p.id', 'p', 'INNER')
            ->join('Residence', 'rsd.id = p.residence_id', 'rsd', 'LEFT')
            ->join('User', 'user.id = ds.user_id', 'user', 'INNER')
            ->join('ConsultationsPrescriptions', 'dsprescription.dossiers_consultations_id = ds.id', 'dsprescription', 'LEFT')
            ->join('ConsultationsDiagnostics', 'dsdiagnostic.dossiers_consultations_id = ds.id', 'dsdiagnostic', 'LEFT')
            ->join('ConsultationsMotifs', 'dsmotif.dossiers_consultations_id = ds.id', 'dsmotif', 'LEFT')
            ->join('CsMotifs', 'csmotif.id = dsmotif.cs_motifs_id', 'csmotif', 'LEFT')
            ->andWhere( "date(ds.date_creation) between :date1: AND :date2:",
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('ds_date_consultation, ds_id_consultation, ds_patients_nom, ds_patients_sexe, ds_patients_age, ds_patients_residence, ds_patients_ethnie, ds_patients_profession, ds_medecin_nom, ds_refere_asc, ds_poids, ds_taille, ds_exam_labo, ds_commentaire');

        $result = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();

        foreach ($result as $k => $value) {
            $cle = intval($value['ds_id_consultation']);
            //print_r($value);
            //$final[$cle]['ds_id_consultation']      = $value['ds_id_consultation'];
            $final[$cle]['normal_date']             = $value['ds_date_consultation'];
            $final[$cle]['ds_date_consultation']    = date('d/m/Y h:i:s',strtotime($value['ds_date_consultation']));
            $final[$cle]['ds_medecin_nom']          = $value['ds_medecin_nom'];
            $final[$cle]['ds_patients_id_technique']    = $value['ds_patients_id_technique'];
            $final[$cle]['ds_patients_nom']         = $value['ds_patients_nom'];
            $final[$cle]['ds_patients_sexe']        = $value['ds_patients_sexe'];
            $final[$cle]['ds_patients_age']         = $value['ds_patients_age'];
            $final[$cle]['ds_patients_residence']   = $value['ds_patients_residence'];
            $final[$cle]['ds_patients_ethnie']      = $value['ds_patients_ethnie'];
            $final[$cle]['ds_patients_profession']  = $value['ds_patients_profession'];
            $final[$cle]['ds_nouvelle_cs']          = "oui";
            $final[$cle]['ds_ancienne_cs']          = "";
            $final[$cle]['ds_refere_asc']           = $value['ds_refere_asc'];
            $final[$cle]['ds_poids']                = $value['ds_poids'];
            $final[$cle]['ds_taille']               = $value['ds_taille'];
            if( ($value['ds_poids'] > 0 && $value['ds_taille'] > 0) ){
                $final[$cle]['ds_poids_taille'] = number_format($value['ds_poids']/$value['ds_taille'],2,'.','');
            }
            else{
                $final[$cle]['ds_poids_taille'] = "";
            }
            if( ($value['ds_taille'] > 0 && $value['ds_patients_age'] > 0) ){
                $final[$cle]['ds_taille_age'] = number_format($value['ds_taille']/$value['ds_patients_age'],2,'.','');
            }
            else{
                $final[$cle]['ds_taille_age'] = "";
            }
            if( ($value['ds_taille'] > 0 && $value['ds_poids'] > 0) ){
                $tailleEnMettre = $value['ds_taille']/100;
                $final[$cle]['ds_imc'] = number_format( $value['ds_poids']/($tailleEnMettre*$tailleEnMettre) ,2,'.','');
            }
            else{
                $final[$cle]['ds_imc'] = "";
            }

            $final[$cle]['ds_motif']        = $value['ds_motif'];
            $final[$cle]['ds_exam_labo']    = $value['ds_exam_labo'];
            $final[$cle]['ds_diagnostic']   = $value['ds_diagnostic'];
            $final[$cle]['ds_traitement']   = $value['ds_traitement'];
            $final[$cle]['ds_commentaire']  = $value['ds_commentaire'];
        }

        $finalKey = array_unique($finalKey);

        /*  SUIVI */

        $builder = $this->modelsManager->createBuilder();
        $result = $builder->columns("ds.date_creation as ds_date_consultation, ds.id as ds_id_consultation, CONCAT(p.prenom, ' ', p.nom) as ds_patients_nom, p.id_technique as ds_patients_id_technique, p.sexe as ds_patients_sexe, DIALECT_GET_AGE(p.date_naissance) as ds_patients_age, rsd.libelle as ds_patients_residence, p.ethnie as ds_patients_ethnie, p.profession as ds_patients_profession, CONCAT(user.prenom, ' ', user.nom) as ds_medecin_nom, ds.poids as ds_poids, ds.taille as ds_taille, ds.resultat_exam_comp as ds_exam_labo, GROUP_CONCAT(distinct dsprescription.medicament) as ds_traitement, ds.observation as ds_commentaire")
            ->addfrom('Consultations', 'ds')
            ->join('DossiersConsultations', 'dossier.id = ds.dossiers_consultations_id', 'dossier', 'INNER')
            ->join('Patients', 'dossier.patients_id = p.id', 'p', 'INNER')
            ->join('Residence', 'rsd.id = p.residence_id', 'rsd', 'LEFT')
            ->join('User', 'user.id = ds.user_id', 'user', 'INNER')
            ->join('ConsultationsPrescriptions', 'dsprescription.dossiers_consultations_id = ds.id', 'dsprescription', 'LEFT')
            ->andWhere( "date(ds.date_creation) between :date1: AND :date2:",
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('ds_date_consultation, ds_id_consultation, ds_patients_nom, ds_patients_sexe, ds_patients_age, ds_patients_residence, ds_patients_ethnie, ds_patients_profession, ds_medecin_nom, ds_poids, ds_taille, ds_exam_labo, ds_commentaire');

        $result = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();

        foreach ($result as $k => $value) {
            $cle = intval($value['ds_id_consultation']) + 10000;
            //print_r($value);
            //$final[$cle]['ds_id_consultation']      = $value['ds_id_consultation'];
            $final[$cle]['normal_date']             = $value['ds_date_consultation'];
            $final[$cle]['ds_date_consultation']    = date('d/m/Y h:i:s',strtotime($value['ds_date_consultation']));
            $final[$cle]['ds_medecin_nom']          = $value['ds_medecin_nom'];
            $final[$cle]['ds_patients_id_technique']    = $value['ds_patients_id_technique'];
            $final[$cle]['ds_patients_nom']         = $value['ds_patients_nom'];
            $final[$cle]['ds_patients_sexe']        = $value['ds_patients_sexe'];
            $final[$cle]['ds_patients_age']         = $value['ds_patients_age'];
            $final[$cle]['ds_patients_residence']   = $value['ds_patients_residence'];
            $final[$cle]['ds_patients_ethnie']      = $value['ds_patients_ethnie'];
            $final[$cle]['ds_patients_profession']  = $value['ds_patients_profession'];
            $final[$cle]['ds_nouvelle_cs']          = "";
            $final[$cle]['ds_ancienne_cs']          = "oui";
            $final[$cle]['ds_refere_asc']           = "";
            $final[$cle]['ds_poids']                = $value['ds_poids'];
            $final[$cle]['ds_taille']               = $value['ds_taille'];
            if( ($value['ds_poids'] > 0 && $value['ds_taille'] > 0) ){
                $final[$cle]['ds_poids_taille'] = number_format($value['ds_poids']/$value['ds_taille'],2,'.','');
            }
            else{
                $final[$cle]['ds_poids_taille'] = "";
            }
            if( ($value['ds_taille'] > 0 && $value['ds_patients_age'] > 0) ){
                $final[$cle]['ds_taille_age'] = number_format($value['ds_taille']/$value['ds_patients_age'],2,'.','');
            }
            else{
                $final[$cle]['ds_taille_age'] = "";
            }
            if( ($value['ds_taille'] > 0 && $value['ds_poids'] > 0) ){
                $tailleEnMettre = $value['ds_taille']/100;
                $final[$cle]['ds_imc'] = number_format($value['ds_poids']/($tailleEnMettre*$tailleEnMettre),2,'.','');
            }
            else{
                $final[$cle]['ds_imc'] = "";
            }

            $final[$cle]['ds_motif']        = "";
            $final[$cle]['ds_exam_labo']    = $value['ds_exam_labo'];
            $final[$cle]['ds_diagnostic']   = "";
            $final[$cle]['ds_traitement']   = $value['ds_traitement'];
            $final[$cle]['ds_commentaire']  = $value['ds_commentaire'];

        }

        /* FIN SUIVI */
        
        $columns[0] = [ 'title' => 'state', 'checkbox' => true ];
        foreach ($finalKey as $k => $value) {
           array_push($columns, [   'field' => $value,  'title' => $value, 
                                    'sortable' => true, 'filterControl' => "input" ] );
        }

        $columns    = array_values($columns);
        $final      = array_values($final);
        usort($final, function($a, $b){
            $t1 = strtotime($a['normal_date']);
            $t2 = strtotime($b['normal_date']);
            return $t2 - $t1;
        });

        //print json_encode(array_values($columns), JSON_PRETTY_PRINT);exit();
        //print json_encode(array_values($final), JSON_PRETTY_PRINT);
        //print json_encode(array_values($finalKey), JSON_PRETTY_PRINT);exit();

        //$this->view->forms = Forms::find( array('conditions' => "type = 'base'") );
        $this->view->forms = Forms::find();

        $this->view->columns  = json_encode($columns, JSON_PRETTY_PRINT);
        $this->view->result = json_encode($final, JSON_PRETTY_PRINT);


    }

    public function addAntecedantAction($id = 0) {
        $this->view->disable();

        $data = $this->request->getPost();
        //print_r($id);exit();
        if ($this->request->isAjax()) {
            if($id != 0){
                $current = PatientsAntecedant::findFirst($id);
            }
            else{
                $current = new PatientsAntecedant();
            }

            if(!$current){
                 echo 0;exit();
            }            
            //print_r($data);
            $current->patients_id = $data['patients_id'];
            $current->type = $data['type'];
            $current->niveau = $data['niveau'];
            $current->libelle = $data['libelle'];

            if (!$current->save()) {
                //print_r($current->getMessages());exit();
               echo 0;exit();
            }
            else{
               echo $current->id;exit();
            }
            echo 0;exit();
        }
    }

    public function listeAttenteAction(){

        $listeAttentes = ConsultationListeAttente::find(" etat != 1 AND user_id = " . $this->session->get('usr')['id']);

        $rs = [];
        for($i = 0; $i < count($listeAttentes); $i++) {
            $rs[$i]['id']               = $listeAttentes[$i]->id;
            $rs[$i]['date']             = strtotime($listeAttentes[$i]->date);
            $rs[$i]['acte']             = $listeAttentes[$i]->getPrestationsDetails()->getActes();
            $rs[$i]['prestation']       = $listeAttentes[$i]->getPrestationsDetails()->getPrestations();
            $rs[$i]['patient_id']       = $listeAttentes[$i]->getPatients()->id;
            $rs[$i]['patient_nom']      = $listeAttentes[$i]->getPatients()->nom . " " . $listeAttentes[$i]->getPatients()->prenom;
            $rs[$i]['patient_adresse']      = $listeAttentes[$i]->getPatients()->adresse;
            $rs[$i]['patient_telephone']    = $listeAttentes[$i]->getPatients()->telephone;
        }

        $this->view->liste_attentes = json_encode($rs, JSON_PRETTY_PRINT);

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

        //Nombre total de visite
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(dossiersConsultations.id) as nbr")
            ->addfrom('DossiersConsultations', 'dossiersConsultations')
            ->andWhere( 'date(dossiersConsultations.date_creation) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $totalInitial = (count($req)>0) ? $req[0]['nbr'] : 0;
        $this->view->totalInitial = (count($req)>0) ? $req[0]['nbr'] : 0;

        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(consultations.id) as nbr")
            ->addfrom('Consultations', 'consultations')
            ->andWhere( 'date(consultations.date_creation) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $totalSuivi = (count($req)>0) ? $req[0]['nbr'] : 0;
        $this->view->totalSuivi = (count($req)>0) ? $req[0]['nbr'] : 0;
        $this->view->totalVisite = $totalInitial + $totalSuivi;


        //Nombre moyen de suivi
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(distinct patients.id) as nbrPatient, count(consultations.id) as nbrSuivie")
            ->addfrom('Consultations', 'consultations')
            ->join('DossiersConsultations', 'consultations.dossiers_consultations_id = dossiersConsultations.id', 'dossiersConsultations', 'inner')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( 'date(consultations.date_creation) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $this->view->nbrMoyenSuivi = ($req[0]['nbrPatient'] > 0) ? $req[0]['nbrSuivie']/$req[0]['nbrPatient'] : 0;
        
        //Nombre de visite par sexe
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("(IF(patients.sexe = 'f', 'Feminin', 'Masculin')) as sexe, 
                                    (COUNT(distinct dossiersConsultations.id) + COUNT(consultations.id)) as nbr")
            ->addfrom('Consultations', 'consultations')
            ->join('DossiersConsultations', 'consultations.dossiers_consultations_id = dossiersConsultations.id', 'dossiersConsultations', 'right')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( 'date(dossiersConsultations.date_creation) between :date1: AND :date2: OR date(consultations.date_creation) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('sexe');
        $req = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();
        $this->view->visiteParSexeGraph = json_encode($req, JSON_PRETTY_PRINT);

        //Comparaison graphique entre nombre de nouvelles consultations
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("YEAR(dossiersConsultations.date_creation) as annee, 
                MONTH(dossiersConsultations.date_creation) as mois_chiffre, 
                MONTHNAME(dossiersConsultations.date_creation) as mois, 
                COUNT(distinct dossiersConsultations.id) as initial")
            ->addfrom('DossiersConsultations', 'dossiersConsultations')
            ->join('Consultations', 'consultations.dossiers_consultations_id = dossiersConsultations.id', 'consultations', 'left')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( 'date(dossiersConsultations.date_creation) between :date1: AND :date2:',
                                         array('date1' => date('Y-m-d',strtotime("-12 months")), 'date2' => date("Y-m-d")) )
            ->groupBy('annee, mois, mois_chiffre')
            ->orderBy('annee asc, mois_chiffre ASC');
        $req = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS);
        $rs = array();
        for ($i = 0; $i < count($req); $i++) {
            $rs[$i]['mois'] = $req[$i]->mois;
            $rs[$i]['initial'] = $req[$i]->initial;
        }
        $this->view->mensuelleInitialGraph = json_encode($rs, JSON_PRETTY_PRINT);

        //Comparaison graphique entre nombre de visite
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("YEAR(dossiersConsultations.date_creation) as annee, 
                MONTH(dossiersConsultations.date_creation) as mois_chiffre, 
                MONTHNAME(dossiersConsultations.date_creation) as mois, 
                (COUNT(distinct dossiersConsultations.id) + COUNT(consultations.id)) as visite")
            ->addfrom('Consultations', 'consultations')
            ->join('DossiersConsultations', 'consultations.dossiers_consultations_id = dossiersConsultations.id', 'dossiersConsultations', 'right')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( 'date(dossiersConsultations.date_creation) between :date1: AND :date2: OR date(consultations.date_creation) between :date1: AND :date2:',
                                         array('date1' => date('Y-m-d',strtotime("-12 months")), 'date2' => date("Y-m-d")) )
            ->groupBy('annee, mois, mois_chiffre')
            ->orderBy('annee asc, mois_chiffre ASC');
        $req = $builder->getQuery()->execute();
        $rs = array();
        for ($i = 0; $i < count($req); $i++) {
            $rs[$i]['mois'] = $req[$i]->mois;
            $rs[$i]['visite'] = $req[$i]->visite;
        }
        $this->view->mensuelleVisiteGraph = json_encode($rs, JSON_PRETTY_PRINT);

        //Top 10 des diagnostics
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("COUNT(consultationsDiagnostics.id) as nbr, libelle")
            ->addfrom('DossiersConsultations', 'dossiersConsultations')
            ->join('ConsultationsDiagnostics', 'consultationsDiagnostics.dossiers_consultations_id = dossiersConsultations.id', 'consultationsDiagnostics', 'INNER')
            ->andWhere( " ( date(dossiersConsultations.date_creation) between :date1: AND :date2: ) AND consultationsDiagnostics.type = 'd' ",
                                         array('date1' => $date1, 'date2' => $date2) )
            ->groupBy('libelle')->orderBy('libelle ASC')->limit(10);
        $req = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS);
        $rs = array();
        for ($i = 0; $i < count($req); $i++) {
            $rs[$i]['nbr']      = $req[$i]->nbr;
            $rs[$i]['libelle']  = $req[$i]->libelle;
        }
        $this->view->top10Diagnostics = json_encode($rs, JSON_PRETTY_PRINT);


        //Nombre de visite par Tranche d'age
        $rsFinal = array();
        //0-5
         $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("COUNT(distinct dossiersConsultations.id) as nouvelle, COUNT(distinct patients.id) as patient")
            ->addfrom('DossiersConsultations', 'dossiersConsultations')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( ' ( date(dossiersConsultations.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 0 AND 5) ',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $builder = $this->modelsManager->createBuilder();
        $req2 = $builder->columns("COUNT(distinct consultations.id) as suivi")
            ->addfrom('Consultations', 'consultations')
            ->join('DossiersConsultations', 'consultations.dossiers_consultations_id = dossiersConsultations.id', 'dossiersConsultations', 'inner')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( ' ( date(consultations.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 0 AND 5) ',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req2 = $builder->getQuery()->execute();
        $rsFinal[] = array("tranche" => "0-5", "nouvelle" => $req[0]['nouvelle'], "patient" => $req[0]['patient'], "suivi" => $req2[0]['suivi']);

        //6-10
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("COUNT(distinct dossiersConsultations.id) as nouvelle, COUNT(distinct patients.id) as patient")
            ->addfrom('DossiersConsultations', 'dossiersConsultations')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( ' ( date(dossiersConsultations.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 6 AND 10) ',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $builder = $this->modelsManager->createBuilder();
        $req2 = $builder->columns("COUNT(distinct consultations.id) as suivi")
            ->addfrom('Consultations', 'consultations')
            ->join('DossiersConsultations', 'consultations.dossiers_consultations_id = dossiersConsultations.id', 'dossiersConsultations', 'inner')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( ' ( date(consultations.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 6 AND 10) ',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req2 = $builder->getQuery()->execute();
        $rsFinal[] = array("tranche" => "6-10", "nouvelle" => $req[0]['nouvelle'], "patient" => $req[0]['patient'], "suivi" => $req2[0]['suivi']);

        //11-15
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("COUNT(distinct dossiersConsultations.id) as nouvelle, COUNT(distinct patients.id) as patient")
            ->addfrom('DossiersConsultations', 'dossiersConsultations')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( ' ( date(dossiersConsultations.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 11 AND 15) ',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $builder = $this->modelsManager->createBuilder();
        $req2 = $builder->columns("COUNT(distinct consultations.id) as suivi")
            ->addfrom('Consultations', 'consultations')
            ->join('DossiersConsultations', 'consultations.dossiers_consultations_id = dossiersConsultations.id', 'dossiersConsultations', 'inner')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( ' ( date(consultations.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 11 AND 15) ',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req2 = $builder->getQuery()->execute();
        $rsFinal[] = array("tranche" => "11-15", "nouvelle" => $req[0]['nouvelle'], "patient" => $req[0]['patient'], "suivi" => $req2[0]['suivi']);

        //16-20
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("COUNT(distinct dossiersConsultations.id) as nouvelle, COUNT(distinct patients.id) as patient")
            ->addfrom('DossiersConsultations', 'dossiersConsultations')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( ' ( date(dossiersConsultations.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 16 AND 20) ',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $builder = $this->modelsManager->createBuilder();
        $req2 = $builder->columns("COUNT(distinct consultations.id) as suivi")
            ->addfrom('Consultations', 'consultations')
            ->join('DossiersConsultations', 'consultations.dossiers_consultations_id = dossiersConsultations.id', 'dossiersConsultations', 'inner')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( ' ( date(consultations.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 16 AND 20) ',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req2 = $builder->getQuery()->execute();
        $rsFinal[] = array("tranche" => "16-20", "nouvelle" => $req[0]['nouvelle'], "patient" => $req[0]['patient'], "suivi" => $req2[0]['suivi']);

        //21-30
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("COUNT(distinct dossiersConsultations.id) as nouvelle, COUNT(distinct patients.id) as patient")
            ->addfrom('DossiersConsultations', 'dossiersConsultations')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( ' ( date(dossiersConsultations.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 21 AND 30) ',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $builder = $this->modelsManager->createBuilder();
        $req2 = $builder->columns("COUNT(distinct consultations.id) as suivi")
            ->addfrom('Consultations', 'consultations')
            ->join('DossiersConsultations', 'consultations.dossiers_consultations_id = dossiersConsultations.id', 'dossiersConsultations', 'inner')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( ' ( date(consultations.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 21 AND 30) ',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req2 = $builder->getQuery()->execute();
        $rsFinal[] = array("tranche" => "21-30", "nouvelle" => $req[0]['nouvelle'], "patient" => $req[0]['patient'], "suivi" => $req2[0]['suivi']);

        //31-50
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("COUNT(distinct dossiersConsultations.id) as nouvelle, COUNT(distinct patients.id) as patient")
            ->addfrom('DossiersConsultations', 'dossiersConsultations')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( ' ( date(dossiersConsultations.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 31 AND 50) ',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $builder = $this->modelsManager->createBuilder();
        $req2 = $builder->columns("COUNT(distinct consultations.id) as suivi")
            ->addfrom('Consultations', 'consultations')
            ->join('DossiersConsultations', 'consultations.dossiers_consultations_id = dossiersConsultations.id', 'dossiersConsultations', 'inner')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( ' ( date(consultations.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 31 AND 50) ',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req2 = $builder->getQuery()->execute();
        $rsFinal[] = array("tranche" => "31-50", "nouvelle" => $req[0]['nouvelle'], "patient" => $req[0]['patient'], "suivi" => $req2[0]['suivi']);

        //Plus de 50
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("COUNT(distinct dossiersConsultations.id) as nouvelle, COUNT(distinct patients.id) as patient")
            ->addfrom('DossiersConsultations', 'dossiersConsultations')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( ' ( date(dossiersConsultations.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  >= 51 ) ',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $builder = $this->modelsManager->createBuilder();
        $req2 = $builder->columns("COUNT(distinct consultations.id) as suivi")
            ->addfrom('Consultations', 'consultations')
            ->join('DossiersConsultations', 'consultations.dossiers_consultations_id = dossiersConsultations.id', 'dossiersConsultations', 'inner')
            ->join('Patients', 'dossiersConsultations.patients_id = patients.id', 'patients', 'inner')
            ->andWhere( ' ( date(consultations.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  >= 51) ',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req2 = $builder->getQuery()->execute();
        $rsFinal[] = array("tranche" => "51 et +", "nouvelle" => $req[0]['nouvelle'], "patient" => $req[0]['patient'], "suivi" => $req2[0]['suivi']);

        $this->view->visiteParTrancheAge = json_encode($rsFinal, JSON_PRETTY_PRINT);
    }


    public function ajaxPrescriptionModeAction() {
        $this->view->disable();
        
        $builder = $this->modelsManager->createBuilder();
        $modes = $builder->columns('distinct consultationsPrescriptions.mode')
            ->addfrom('ConsultationsPrescriptions', 'consultationsPrescriptions')
            ->andWhere( ' consultationsPrescriptions.mode IS NOT NULL ');

        $modes = $builder->getQuery()->execute();
        $rs = array();
        for($i = 0; $i < count($modes); $i++) {
            $rs[$i]['id'] = 1;
            $rs[$i]['libelle'] = $modes[$i]->mode;
        }
        
        echo json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function ajaxPrescriptionPosologieAction() {
        $this->view->disable();
        
        $builder = $this->modelsManager->createBuilder();
        $posologies = $builder->columns('distinct consultationsPrescriptions.posologie')
            ->addfrom('ConsultationsPrescriptions', 'consultationsPrescriptions')
            ->andWhere( ' consultationsPrescriptions.posologie IS NOT NULL ');

        $posologies = $builder->getQuery()->execute();
        $rs = array();
        for($i = 0; $i < count($posologies); $i++) {
            $rs[$i]['id'] = 1;
            $rs[$i]['libelle'] = $posologies[$i]->posologie;
        }
        
        echo json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function ajaxPrescriptionDureeAction() {
        $this->view->disable();
        
        $builder = $this->modelsManager->createBuilder();
        $durees = $builder->columns('distinct consultationsPrescriptions.duree')
            ->addfrom('ConsultationsPrescriptions', 'consultationsPrescriptions')
            ->andWhere( ' consultationsPrescriptions.duree IS NOT NULL ');

        $durees = $builder->getQuery()->execute();
        $rs = array();
        for($i = 0; $i < count($durees); $i++) {
            $rs[$i]['id'] = 1;
            $rs[$i]['libelle'] = $durees[$i]->duree;
        }
        
        echo json_encode($rs, JSON_PRETTY_PRINT);
    }

}
