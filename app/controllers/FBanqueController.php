<?php

/**
 * FBanqueController
 *
 */
class FBanqueController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Banques"]);
        if($this->view->language == "fr"){
            $langue = "fr-FR";
        }
        else{
            $langue = "en-US";
        }
        $this->view->langue = $langue;
    }

    public function indexAction() {       
        $data = [];
        if ($this->request->isPost()) {
            $data["param1"] = $this->request->getPost("param1");
            Phalcon\Tag::setDefaults(array(
                                        "param1" => $data["param1"]
                                        ));
        }
        $fbanque = FBanque::find();

        $rs = [];
        for($i = 0; $i < count($fbanque); $i++) {
            $rs[$i]['id']           = $fbanque[$i]->id;
            $rs[$i]['libelle']      = $fbanque[$i]->libelle;
            $rs[$i]['comptes']       = "";
            foreach ($fbanque[$i]->getFBanqueCompte() as $compte) {
               $rs[$i]['comptes']       .= ($rs[$i]['comptes'] != "") ? "," . $compte->compte : $compte->compte;
            }

        }

        $this->view->fbanque   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createFBanqueAction() {
        $this->view->disable();
        $form = new FBanqueForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }
            $fbanque = new FBanque();

            if(trim($data['comptes']) != ""){
                $comptesArray = explode(",", $data["comptes"]);
                $comptesObjects = array();
                foreach ($comptesArray as $key => $value) {
                    $comptesObjects[$key] = new FBanqueCompte();
                    $comptesObjects[$key]->compte = $value;
                }
                $fbanque->fBanqueCompte = $comptesObjects;
            }
            $fbanque->libelle = $data['libelle'];

            if (!$fbanque->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Banque créée avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('f_banque/createFBanque');
    }

    public function editFBanqueAction($id) {
        $this->view->disable();
        $form = new FBanqueForm($this->trans);
        $this->view->fbanque_id = $id;
        $this->view->form_action = 'edit';
        $fbanque = FBanque::findFirst($id);
        if(!$fbanque){
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

            //On supprime tout d'abord
            $query = $this->modelsManager->createQuery("DELETE FROM FBanqueCompte WHERE f_banque_id = :banque_id:");
            $exec  = $query->execute( array('banque_id' => $id) );

            if(trim($data['comptes']) != ""){
                $comptesArray = explode(",", $data["comptes"]);
                $comptesObjects = array();
                foreach ($comptesArray as $key => $value) {
                    if($value != ""){
                        $comptesObjects[$key] = new FBanqueCompte();
                        $comptesObjects[$key]->compte = $value;
                    }
                }
                $fbanque->fBanqueCompte = $comptesObjects;
            }

            $fbanque->libelle = $data["libelle"];
            if (!$fbanque->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Banque modifiée avec succès']);
            return $this->view->partial("layouts/flash");
        } else {
            $this->view->form = $form;
            $comptes  = "";
            foreach ($fbanque->getFBanqueCompte() as $compte) {
               $comptes .= ($comptes != "") ? "," .  $compte->compte : $compte->compte;
            }
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $fbanque->libelle,
                    "comptes" => $comptes,
            ));
            $this->view->partial("f_banque/createFBanque");
        }
    }

    public function deleteFBanqueAction($id) {
        $this->view->disable();

        $fbanque = FBanque::findFirst($id);
        if(!$fbanque){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$fbanque->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
