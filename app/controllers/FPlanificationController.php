<?php

/**
 * FPlanificationController
 *
 */
class FPlanificationController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Planification"]);
        if($this->view->language == "fr"){
            $langue = "fr-FR";
        }
        else{
            $langue = "en-US";
        }
        $this->view->langue = $langue;
    }

    public function indexAction() {       
        if($this->request->get("annee")){
            $data = $this->request->get();
            if($data['annee'] != ""){
                $annee = $data['annee'];
            }
        }
        else{
            $annee = date('Y');
        }

        Phalcon\Tag::setDefaults(array(
                "annee" => $annee,
        ));

        $builder = $this->modelsManager->createBuilder();
        $fplanification = $builder->columns("fplanification.id as id, fplanification.type_prevision as type_prevision, fplanification.montant as montant, fplanification.quantite as quantite, fplanification.prix_unitaire as prix_unitaire, fplanification.annee as annee, CONCAT(fcompte.numero, fsouscompte.numero) as compte_numero, CONCAT(fcompte.libelle, '/', fsouscompte.libelle) as compte_libelle")
            ->addfrom('FPlanification', 'fplanification')
            ->join('FSousCompte', 'fplanification.f_sous_compte_id = fsouscompte.id', 'fsouscompte', 'INNER')
            ->join('FCompte', 'fsouscompte.f_compte_id = fcompte.id', 'fcompte', 'INNER')
            ->andWhere( 'fplanification.annee = :annee:', array('annee' => $annee) );
        $fplanification = $builder->getQuery()->execute();

        $rs = [];
        for($i = 0; $i < count($fplanification); $i++) {
            $rs[$i]['id']               = $fplanification[$i]->id;
            $rs[$i]['annee']            = $fplanification[$i]->annee;
            $rs[$i]['compte_numero']    = $fplanification[$i]->compte_numero;
            $rs[$i]['compte_libelle']   = $fplanification[$i]->compte_libelle;
            $rs[$i]['montant']          = $fplanification[$i]->montant;
            $rs[$i]['quantite']         = $fplanification[$i]->quantite;
            $rs[$i]['prix_unitaire']    = $fplanification[$i]->prix_unitaire;
            $rs[$i]['type_prevision']   = $fplanification[$i]->type_prevision;
            //$rs[$i]['compte']           = $fplanification[$i]->getFCompte()->numero . "-" . $fplanification[$i]->getFCompte()->libelle ;
        }
        $this->view->annee   = array(date("Y") => date("Y"), date("Y") + 1 => date("Y") + 1, );
        $this->view->fplanification   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createFPlanificationAction() {
        $this->view->disable();
        $form = new FPlanificationForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $fplanification = new FPlanification();
            $fplanification->type_prevision = $data['type_prevision'];
            $fplanification->montant = $data['montant'];
            $fplanification->quantite = $data['quantite'];
            $fplanification->prix_unitaire = $data['prix_unitaire'];
            $fplanification->annee = $data['annee'];
            $fplanification->f_sous_compte_id = $data['f_sous_compte_id'];

            if (!$fplanification->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Planification créée avec succès']);
            return $this->view->partial("layouts/flash");
        }

        $builder = $this->modelsManager->createBuilder();
        $f_sous_compte_id = $builder->columns("fsouscompte.id as id, CONCAT(fcompte.numero, fsouscompte.numero, '-', fcompte.libelle, '/', fsouscompte.libelle) as libelle")
            ->addfrom('FSousCompte', 'fsouscompte')
            ->join('FCompte', 'fsouscompte.f_compte_id = fcompte.id', 'fcompte', 'INNER')
            ->orderBy('libelle asc');
        $f_sous_compte_id = $builder->getQuery()->execute();

        $this->view->annee   = array(date("Y") => date("Y"), date("Y") + 1 => date("Y") + 1, );
        $this->view->f_sous_compte_id   = $f_sous_compte_id;
        $this->view->partial('f_planification/createFPlanification');
    }

    public function editFPlanificationAction($id) {
        $this->view->disable();
        $form = new FPlanificationForm($this->trans);
        $this->view->fplanification_id = $id;
        $this->view->form_action = 'edit';
        $fplanification = FPlanification::findFirst($id);
        if(!$fplanification){
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
            $fplanification->type_prevision = $data["type_prevision"];
            $fplanification->montant = $data['montant'];
            $fplanification->quantite = $data['quantite'];
            $fplanification->prix_unitaire = $data['prix_unitaire'];
            $fplanification->annee = $data['annee'];
            $fplanification->f_sous_compte_id = $data['f_sous_compte_id'];
            if (!$fplanification->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Planification modifiée avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->annee   = array(date("Y") => date("Y"), date("Y") + 1 => date("Y") + 1, );
            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "annee" => $fplanification->annee,
                    "type_prevision" => $fplanification->type_prevision,
                    "montant" => $fplanification->montant,
                    "quantite" => $fplanification->quantite,
                    "prix_unitaire" => $fplanification->prix_unitaire,
                    "annee" => $fplanification->annee,
                    "f_sous_compte_id" => $fplanification->f_sous_compte_id,
            ));

            $builder = $this->modelsManager->createBuilder();
            $f_sous_compte_id = $builder->columns("fsouscompte.id as id, CONCAT(fcompte.numero, fsouscompte.numero, '-', fcompte.libelle, '/', fsouscompte.libelle) as libelle")
                ->addfrom('FSousCompte', 'fsouscompte')
                ->join('FCompte', 'fsouscompte.f_compte_id = fcompte.id', 'fcompte', 'INNER')
                ->orderBy('libelle asc');
            $f_sous_compte_id = $builder->getQuery()->execute();

            $this->view->f_sous_compte_id   = $f_sous_compte_id;
            $this->view->partial("f_planification/createFPlanification");
        }
    }

    public function deleteFPlanificationAction($id) {
        $this->view->disable();

        $fplanification = FPlanification::findFirst($id);
        if(!$fplanification){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$fplanification->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }

    public function etatProgressPlanifAction(){
        if($this->request->get("annee")){
            $data = $this->request->get();
            if($data['annee'] != ""){
                $annee = $data['annee'];
            }
        }
        else{
            $annee = date('Y');
        }

        Phalcon\Tag::setDefaults(array(
                "annee" => $annee,
        ));
        
        $this->view->annee   = array(date("Y") - 1 => date("Y") - 1, date("Y") => date("Y"), date("Y") + 1 => date("Y") + 1, );

        $builder = $this->modelsManager->createBuilder();
        $fplanification = $builder->columns("fplanification.type_prevision as type_prevision, fplanification.montant as montant_planif, CONCAT(fcompte.numero, fsouscompte.numero) as compte_numero, CONCAT(fcompte.libelle, '/', fsouscompte.libelle) as compte_libelle, fcompte.type as type_compte, SUM(foperation.montant) as montant_op")
            ->addfrom('FPlanification', 'fplanification')
            ->join('FSousCompte', 'fplanification.f_sous_compte_id = fsouscompte.id', 'fsouscompte', 'INNER')
            ->join('FCompte', 'fsouscompte.f_compte_id = fcompte.id', 'fcompte', 'INNER')
            ->join('FOperation', 'foperation.f_sous_compte_id = fplanification.f_sous_compte_id', 'foperation', 'LEFT')
            ->groupBy('type_prevision, montant_planif, type_compte, compte_numero, compte_libelle')
            ->andWhere( 'fplanification.annee = :annee:', array('annee' => $annee) );
        $fplanification = $builder->getQuery()->execute();

        $rs = [];
        for($i = 0; $i < count($fplanification); $i++) {
            $rs[$i]['type_prevision']   = $fplanification[$i]->type_prevision;
            $rs[$i]['type_compte']      = $fplanification[$i]->type_compte;
            $rs[$i]['compte_numero']    = $fplanification[$i]->compte_numero;
            $rs[$i]['compte_libelle']   = $fplanification[$i]->compte_libelle;
            $rs[$i]['montant_planif']   = $fplanification[$i]->montant_planif;
            $rs[$i]['montant_op']       = $fplanification[$i]->montant_op;
            $rs[$i]['progression']      = ($fplanification[$i]->montant_op/$fplanification[$i]->montant_planif) * 100;
            $rs[$i]['progression']      = round($rs[$i]['progression'], 2);
        }
        $this->view->progressByCompte   = json_encode($rs, JSON_PRETTY_PRINT);

        $builder = $this->modelsManager->createBuilder();
        $fplanification = $builder->columns("fplanification.type_prevision as type_prevision, fcompte.type as type_compte, SUM(fplanification.montant) as montant_planif, SUM(foperation.montant) as montant_op")
            ->addfrom('FPlanification', 'fplanification')
            ->join('FSousCompte', 'fplanification.f_sous_compte_id = fsouscompte.id', 'fsouscompte', 'INNER')
            ->join('FCompte', 'fsouscompte.f_compte_id = fcompte.id', 'fcompte', 'INNER')
            ->join('FOperation', 'foperation.f_sous_compte_id = fplanification.f_sous_compte_id', 'foperation', 'LEFT')
            ->groupBy('type_prevision, type_compte')
            ->andWhere( 'fplanification.annee = :annee:', array('annee' => $annee) );
        $fplanification = $builder->getQuery()->execute();

        $rs = [];
        for($i = 0; $i < count($fplanification); $i++) {
            $rs[$i]['type_prevision']   = $fplanification[$i]->type_prevision;
            $rs[$i]['type_compte']      = $fplanification[$i]->type_compte;
            $rs[$i]['montant_planif']   = $fplanification[$i]->montant_planif;
            $rs[$i]['montant_op']       = $fplanification[$i]->montant_op;
            $rs[$i]['progression']      = ($fplanification[$i]->montant_op/$fplanification[$i]->montant_planif) * 100;
            $rs[$i]['progression']      = round($rs[$i]['progression'], 2);
        }
        $this->view->progressByTypePrevision   = json_encode($rs, JSON_PRETTY_PRINT);
    }

}
