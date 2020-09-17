<?php
use Phalcon\Mvc\Model\Resultset;

/**
 * FOperationController
 *
 */
class FOperationController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Opération"]);
        if($this->view->language == "fr"){
            $langue = "fr-FR";
        }
        else{
            $langue = "en-US";
        }
        $this->view->langue = $langue;
    }

    public function indexAction() {       
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
        $foperation = $builder->columns("foperation.id as id, foperation.montant as montant, foperation.details as details, foperation.date as date, foperation.banque_cheque as banque_cheque, foperation.banque_porteur as banque_porteur, CONCAT(fcompte.numero, fsouscompte.numero) as compte_numero, CONCAT(fcompte.libelle, '/', fsouscompte.libelle) as compte_libelle, CONCAT(fbanque.libelle, '/', fbanquecompte.compte) as compte,
            (IF(foperation.f_banque_compte_id IS NULL, 'Espèce', 'Banque')) as nature, fcompte.type as type_compte, CONCAT(user.prenom, ' ', user.nom) as agent_nom")
            ->addfrom('FOperation', 'foperation')
            ->join('FSousCompte', 'foperation.f_sous_compte_id = fsouscompte.id', 'fsouscompte', 'INNER')
            ->join('FCompte', 'fsouscompte.f_compte_id = fcompte.id', 'fcompte', 'INNER')
            ->join('FBanqueCompte', 'foperation.f_banque_compte_id = fbanquecompte.id', 'fbanquecompte', 'LEFT')
            ->join('FBanque', 'fbanquecompte.f_banque_id = fbanque.id', 'fbanque', 'LEFT')
            ->join('User', 'user.id = foperation.user_id', 'user', 'INNER')
            ->orderBy('id desc')
            ->andWhere( 'date(foperation.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $foperation = $builder->getQuery()->execute();

        $rs = [];
        for($i = 0; $i < count($foperation); $i++) {
            $rs[$i]['id']               = $foperation[$i]->id;
            $rs[$i]['agent_nom']        = $foperation[$i]->agent_nom;
            $rs[$i]['banque_porteur']   = $foperation[$i]->banque_porteur;
            $rs[$i]['type_compte']      = $foperation[$i]->type_compte;
            $rs[$i]['compte_numero']    = $foperation[$i]->compte_numero;
            $rs[$i]['compte_libelle']   = $foperation[$i]->compte_libelle;
            $rs[$i]['details']          = $foperation[$i]->details;
            $rs[$i]['date']             = date("d/m/Y", strtotime($foperation[$i]->date));
            $rs[$i]['banque_cheque']    = $foperation[$i]->banque_cheque;
            $rs[$i]['montant']          = $foperation[$i]->montant;
            $rs[$i]['nature']           = $foperation[$i]->nature;
            $rs[$i]['compte']           = $foperation[$i]->compte;
        }
        $this->view->foperation   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createFOperationAction() {
        $this->view->disable();
        $form = new FOperationForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $foperation = new FOperation();
            $foperation->date = $data['date'];
            $foperation->montant = $data['montant'];
            $foperation->f_sous_compte_id = $data['f_sous_compte_id'];
            $foperation->details = $data['details'];
            $foperation->user_id = $this->session->get('usr')['id'];
            if($data['f_banque_compte_id'] != ""){
                $foperation->f_banque_compte_id = $data['f_banque_compte_id'];
                $foperation->banque_cheque = $data['banque_cheque'];
                $foperation->banque_porteur = $data['banque_porteur'];
                $foperation->banque_details = $data['banque_details'];
            }

            $testSolde = $this->_checkSolde($data['f_sous_compte_id'], $data['f_banque_compte_id'], $data['montant']);
            if($testSolde != "--"){
                $this->flash->warning("Votre solde (". number_format($testSolde,0,'.',' ') .") est insuffisant pour cette opération.");
                return $this->view->partial("layouts/flash");
            }

            if (!$foperation->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            //Mise à jour du solde
            $this->_upateSolde($data['f_sous_compte_id'], $data['f_banque_compte_id'], $data['montant']);

            $this->flash->success($this->trans['Opération créée avec succès']);
            return $this->view->partial("layouts/flash");
        }

        $builder = $this->modelsManager->createBuilder();
        $f_sous_compte_id = $builder->columns("fsouscompte.id as id, CONCAT(fcompte.numero, fsouscompte.numero, '-', fcompte.libelle, '/', fsouscompte.libelle) as libelle, IFNULL(fplanification.montant, '0') as prevision")
            ->addfrom('FSousCompte', 'fsouscompte')
            ->join('FCompte', 'fsouscompte.f_compte_id = fcompte.id', 'fcompte', 'INNER')
            ->join('FPlanification', 'fplanification.f_sous_compte_id = fsouscompte.id', 'fplanification', 'LEFT')
            ->where(' 1 = 1 OR fplanification.annee = ' . date("Y"). ' ')
            ->orderBy('libelle asc');
        $f_sous_compte_id = $builder->getQuery()->execute();
        $rs = [];
        foreach ($f_sous_compte_id as $k => $value) {
            $rs[$value->id] = $value->libelle . " (Prévision ". date('Y') ." : " .  number_format($value->prevision,0,'.',' ') . ")";
        }
        $this->view->f_sous_compte_id   = $rs;

        $builder = $this->modelsManager->createBuilder();
        $f_banque_compte = $builder->columns("fbanquecompte.id as id, CONCAT(fbanque.libelle, ' / ', fbanquecompte.compte) as libelle, fsolde.montant as solde")
            ->addfrom('FBanqueCompte', 'fbanquecompte')
            ->join('FBanque', 'fbanquecompte.f_banque_id = fbanque.id', 'fbanque', 'INNER')
            ->join('FSolde', 'fsolde.f_banque_compte_id = fbanquecompte.id', 'fsolde', 'LEFT')
            ->orderBy('fbanque.libelle asc');
        $f_banque_compte = $builder->getQuery()->execute();
        $rs = [];
        for($i = 0; $i < count($f_banque_compte); $i++) {
            $rs[$f_banque_compte[$i]->id]       = $f_banque_compte[$i]->libelle . " (Solde:  ". number_format($f_banque_compte[$i]->solde,0,'.',' ') .")";
        }
        $this->view->f_banque_compte_id   = $rs;

         //solde espece 
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("fsolde.montant as nbr")
           ->addfrom('FSolde', 'fsolde')
            ->andWhere( 'fsolde.f_banque_compte_id IS NULL');
        $rq = $builder->getQuery()->execute();
        $this->view->soldeEspece = (count($rq)>0) ? number_format($rq[0]['nbr'],0,'.',' ') : 0;

        //solde banque 
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(fsolde.montant) as nbr")
           ->addfrom('FSolde', 'fsolde')
            ->andWhere( 'fsolde.f_banque_compte_id IS NOT NULL');
        $rq = $builder->getQuery()->execute();
        $this->view->soldeBanque = (count($rq)>0) ? number_format($rq[0]['nbr'],0,'.',' ') : 0;

        $this->view->partial('f_operation/createFOperation');
    }

    private function _upateSolde($f_sous_compte_id, $f_banque_compte_id, $montant){

        if($f_banque_compte_id != ""){
            $solde = FSolde::findFirst(array("f_banque_compte_id = :compte_id:", 
                                        "bind" => array("compte_id" => $f_banque_compte_id) ));
            if(!$solde){
                $solde = new FSolde();
                $solde->f_banque_compte_id = $f_banque_compte_id;
                $solde->montant = 0;
            }
        }
        else{
            $solde = FSolde::findFirst( array("f_banque_compte_id IS NULL") );
            if(!$solde){
                $solde = new FSolde();
                $solde->montant = 0;
            }
        }

        $typeCompte = FSousCompte::findFirst($f_sous_compte_id)->getFCompte()->type;
        if(($typeCompte == "recette")){
            $solde->montant += $montant;
        }
        else{
            $solde->montant -= $montant;
            if($solde->montant < 0)
                $solde->montant = 0;
        }
        $solde->save();

    }

    private function _checkSolde($f_sous_compte_id, $f_banque_compte_id, $montant){

        if($f_banque_compte_id != ""){
            $solde = FSolde::findFirst(array("f_banque_compte_id = :compte_id:", 
                                        "bind" => array("compte_id" => $f_banque_compte_id) ));
            if(!$solde){
                $solde = new FSolde();
                $solde->f_banque_compte_id = $f_banque_compte_id;
                $solde->montant = 0;
            }
        }
        else{
            $solde = FSolde::findFirst( array("f_banque_compte_id IS NULL") );
            if(!$solde){
                $solde = new FSolde();
                $solde->montant = 0;
            }
        }

        $typeCompte = FSousCompte::findFirst($f_sous_compte_id)->getFCompte()->type;
        if(($typeCompte == "depense")){
            if($montant > $solde->montant){
               return $solde->montant;
            }
        }

        return "--";
    }

    public function editFOperationAction($id) {
        $this->view->disable();
        $form = new FOperationForm($this->trans);
        $this->view->foperation_id = $id;
        $this->view->form_action = 'edit';
        $foperation = FOperation::findFirst($id);
        if(!$foperation){
            $msg = $this->trans['on_error'];
            $this->flash->error($msg);
            return $this->view->partial("layouts/flash");
        }

        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }
            $foperation->date = $data['date'];
            $foperation->montant = $data['montant'];
            $foperation->f_sous_compte_id = $data['f_sous_compte_id'];
            $foperation->details = $data['details'];
            $foperation->user_id = $this->session->get('usr')['id'];
            if($data['f_banque_compte_id'] != ""){
                $foperation->f_banque_compte_id = $data['f_banque_compte_id'];
                $foperation->banque_cheque = $data['banque_cheque'];
                $foperation->banque_porteur = $data['banque_porteur'];
                $foperation->banque_details = $data['banque_details'];
            }
            else{
                $foperation->f_banque_compte_id = null;
            }
            if (!$foperation->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Opération modifiée avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "date" => $foperation->date,
                    "montant" => $foperation->montant,
                    "details" => $foperation->details,
                    "f_sous_compte_id" => $foperation->f_sous_compte_id,
                    "f_banque_compte_id" => $foperation->f_banque_compte_id,
                    "banque_cheque" => $foperation->banque_cheque,
                    "banque_porteur" => $foperation->banque_porteur,
                    "banque_details" => $foperation->banque_details,
            ));

            $builder = $this->modelsManager->createBuilder();
            $f_sous_compte_id = $builder->columns("fsouscompte.id as id, CONCAT(fcompte.numero, fsouscompte.numero, '-', fcompte.libelle, '/', fsouscompte.libelle) as libelle, IFNULL(fplanification.montant, '0') as prevision")
                ->addfrom('FSousCompte', 'fsouscompte')
                ->join('FCompte', 'fsouscompte.f_compte_id = fcompte.id', 'fcompte', 'INNER')
                ->join('FPlanification', 'fplanification.f_sous_compte_id = fsouscompte.id', 'fplanification', 'LEFT')
                ->where(' 1 = 1 OR fplanification.annee = ' . date("Y"). ' ')
                ->orderBy('libelle asc');
            $f_sous_compte_id = $builder->getQuery()->execute();
            $rs = [];
            foreach ($f_sous_compte_id as $k => $value) {
                $rs[$value->id] = $value->libelle . " (Prévision ". date('Y') ." : " .  number_format($value->prevision,0,'.',' ') . ")";
            }
            $this->view->f_sous_compte_id   = $rs;

            $builder = $this->modelsManager->createBuilder();
            $f_banque_compte_id = $builder->columns("fbanquecompte.id as id, CONCAT(fbanque.libelle, '/', fbanquecompte.compte) as libelle")
                ->addfrom('FBanqueCompte', 'fbanquecompte')
                ->join('FBanque', 'fbanquecompte.f_banque_id = fbanque.id', 'fbanque', 'INNER')
                ->orderBy('fbanque.libelle asc');
            $f_banque_compte_id = $builder->getQuery()->execute();

            $this->view->f_banque_compte_id   = $f_banque_compte_id;

            $this->view->partial("f_operation/createFOperation");
        }
    }

    public function deleteFOperationAction($id) {
        $this->view->disable();

        $foperation = FOperation::findFirst($id);
        if(!$foperation){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$foperation->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
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

        //operation depense 
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(foperation.montant) as nbr")
           ->addfrom('FOperation', 'foperation')
            ->join('FSousCompte', 'foperation.f_sous_compte_id = fsouscompte.id', 'fsouscompte', 'INNER')
            ->join('FCompte', 'fsouscompte.f_compte_id = fcompte.id', 'fcompte', 'INNER')
            ->andWhere( 'fcompte.type = "depense" AND date(foperation.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->opDepense = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //operation recette 
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(foperation.montant) as nbr")
           ->addfrom('FOperation', 'foperation')
            ->join('FSousCompte', 'foperation.f_sous_compte_id = fsouscompte.id', 'fsouscompte', 'INNER')
            ->join('FCompte', 'fsouscompte.f_compte_id = fcompte.id', 'fcompte', 'INNER')
            ->andWhere( 'fcompte.type = "recette" AND date(foperation.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->opRecette = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //solde espece 
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("fsolde.montant as nbr")
           ->addfrom('FSolde', 'fsolde')
            ->andWhere( 'fsolde.f_banque_compte_id IS NULL');
        $rq = $builder->getQuery()->execute();
        $this->view->soldeEspece = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //solde banque 
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(fsolde.montant) as nbr")
           ->addfrom('FSolde', 'fsolde')
            ->andWhere( 'fsolde.f_banque_compte_id IS NOT NULL');
        $rq = $builder->getQuery()->execute();
        $this->view->soldeBanque = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //solde par compte 
        $builder = $this->modelsManager->createBuilder();
         $foperation = $builder->columns("fsolde.montant as montant, IFNULL(CONCAT(fbanque.libelle, '/', fbanquecompte.compte), '1 Espèce') as compte")
            ->addfrom('FSolde', 'fsolde')
            ->join('FBanqueCompte', 'fsolde.f_banque_compte_id = fbanquecompte.id', 'fbanquecompte', 'LEFT')
            ->join('FBanque', 'fbanquecompte.f_banque_id = fbanque.id', 'fbanque', 'LEFT')
            ->orderBy('compte desc');
        $rq = $builder->getQuery()->execute();
        $rs = [];
        for($i = 0; $i < count($rq); $i++) {
            $rs[$i]['montant']  = $rq[$i]->montant;
            $rs[$i]['compte']   = str_replace("1 Espèce", "Espèce", $rq[$i]->compte);
        }
        $this->view->tableauSolde = json_encode($rs, JSON_PRETTY_PRINT);

        //etat compte de depense
        // $builder = $this->modelsManager->createBuilder();
        // $rq = $builder->columns("SUM(foperation.montant) as montant, CONCAT(fcompte.numero, fsouscompte.numero) as compte_numero, CONCAT(fcompte.libelle, '/', fsouscompte.libelle) as compte_libelle,
        //     (IF(foperation.f_banque_compte_id IS NULL, 'Espece', 'Banque')) as type")
        //     ->addfrom('FOperation', 'foperation')
        //     ->join('FSousCompte', 'foperation.f_sous_compte_id = fsouscompte.id', 'fsouscompte', 'INNER')
        //     ->join('FCompte', 'fsouscompte.f_compte_id = fcompte.id', 'fcompte', 'INNER')
        //     ->groupBy('compte_numero, compte_libelle, type')
        //     ->andWhere( 'fcompte.type = "depense" AND date(foperation.date) between :date1: AND :date2:',
        //                                  array('date1' => $date1, 'date2' => $date2) );
        // $rq = $builder->getQuery()->execute();
        $rs = [];
        // for($i = 0; $i < count($rq); $i++) {
        //     $rs[$i]['compte_numero']    = $rq[$i]->compte_numero;
        //     $rs[$i]['compte_libelle']   = $rq[$i]->compte_libelle;
        //     $rs[$i]['montant']          = $rq[$i]->montant;
        // }
        $this->view->tableauDepense = json_encode($rs, JSON_PRETTY_PRINT);

        //etat compte de recette
        $builder = $this->modelsManager->createBuilder();
        // $rq = $builder->columns("SUM(foperation.montant) as montant, CONCAT(fcompte.numero, fsouscompte.numero) as compte_numero, CONCAT(fcompte.libelle, '/', fsouscompte.libelle) as compte_libelle,
        //     (IF(foperation.f_banque_compte_id IS NULL, 'Espece', 'Banque')) as type")
        //     ->addfrom('FOperation', 'foperation')
        //     ->join('FSousCompte', 'foperation.f_sous_compte_id = fsouscompte.id', 'fsouscompte', 'INNER')
        //     ->join('FCompte', 'fsouscompte.f_compte_id = fcompte.id', 'fcompte', 'INNER')
        //     ->groupBy('compte_numero, compte_libelle, type')
        //     ->andWhere( 'fcompte.type = "recette" AND date(foperation.date) between :date1: AND :date2:',
        //                                  array('date1' => $date1, 'date2' => $date2) );
        // $rq = $builder->getQuery()->execute();
        $rs = [];
        // for($i = 0; $i < count($rq); $i++) {
        //     $rs[$i]['compte_numero']    = $rq[$i]->compte_numero;
        //     $rs[$i]['compte_libelle']   = $rq[$i]->compte_libelle;
        //     $rs[$i]['montant']          = $rq[$i]->montant;
        // }
        $this->view->tableauRecette = json_encode($rs, JSON_PRETTY_PRINT);

        //Depense mensuelle
        // $builder = $this->modelsManager->createBuilder();
        // $rq = $builder->columns("YEAR(foperation.date) as annee, MONTH(foperation.date) as mois_chiffre, MONTHNAME(foperation.date) as mois, SUM(foperation.montant) as montant")
        //     ->addfrom('FOperation', 'foperation')
        //     ->join('FSousCompte', 'foperation.f_sous_compte_id = fsouscompte.id', 'fsouscompte', 'INNER')
        //     ->join('FCompte', 'fsouscompte.f_compte_id = fcompte.id', 'fcompte', 'INNER')
        //     ->andWhere( 'date(foperation.date) between :date1: AND :date2:',
        //                                  array('date1' => date('Y-m-d',strtotime("-12 months")), 'date2' => date("Y-m-d")) )
        //     ->groupBy('annee, mois, mois_chiffre')
        //     ->orderBy('annee asc, mois_chiffre ASC');
        // $rq = $builder->getQuery()->execute();
        $rs = array();
        // for ($i = 0; $i < count($rq); $i++) {
        //     $rs[$i]['mois'] = $rq[$i]->mois;
        //     $rs[$i]['montant'] = $rq[$i]->montant;
        // }
        $this->view->depenseMensuelle = json_encode($rs, JSON_PRETTY_PRINT);


        //Montant encaissé CAISSE BE
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(prestations.montant_patient) as montant")
            ->addfrom('Prestations', 'prestations')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->montantEncaisseBe = (count($rq)>0) ? $rq[0]['montant'] : 0;


        //Montant encaissé CAISSE PH
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(recuMedicament.montant_patient) as montant")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->andWhere( 'recuMedicament.etat = 1 AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->montantEncaissePh = (count($rq)>0) ? $rq[0]['montant'] : 0;

        
    }
}
