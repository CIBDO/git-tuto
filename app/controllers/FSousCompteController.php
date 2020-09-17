<?php

/**
 * FSousCompteController
 *
 */
class FSousCompteController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Sous compte"]);
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
        $fsousCompte = FSousCompte::find();

        $rs = [];
        for($i = 0; $i < count($fsousCompte); $i++) {
            $rs[$i]['id']           = $fsousCompte[$i]->id;
            $rs[$i]['numero']       = $fsousCompte[$i]->numero;
            $rs[$i]['libelle']      = $fsousCompte[$i]->libelle;
            $rs[$i]['compte']       = $fsousCompte[$i]->getFCompte()->numero . "-" . $fsousCompte[$i]->getFCompte()->libelle ;
        }
        $this->view->fsousCompte   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createFSousCompteAction() {
        $this->view->disable();
        $form = new FSousCompteForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $fsousCompte = new FSousCompte();
            $fsousCompte->libelle = $data['libelle'];
            $fsousCompte->numero = $data['numero'];
            $fsousCompte->f_compte_id = $data['f_compte_id'];

            if (!$fsousCompte->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Sous compte crée avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('f_sous_compte/createFSousCompte');
    }

    public function editFSousCompteAction($id) {
        $this->view->disable();
        $form = new FSousCompteForm($this->trans);
        $this->view->fsousCompte_id = $id;
        $this->view->form_action = 'edit';
        $fsousCompte = FSousCompte::findFirst($id);
        if(!$fsousCompte){
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
            $fsousCompte->libelle = $data["libelle"];
            $fsousCompte->numero = $data['numero'];
            $fsousCompte->f_compte_id = $data['f_compte_id'];
            if (!$fsousCompte->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Sous compte modifié avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $fsousCompte->libelle,
                    "numero" => $fsousCompte->numero,
                    "f_compte_id" => $fsousCompte->f_compte_id,
            ));
            $this->view->partial("f_sous_compte/createFSousCompte");
        }
    }

    public function deleteFSousCompteAction($id) {
        $this->view->disable();

        $fsousCompte = FSousCompte::findFirst($id);
        if(!$fsousCompte){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$fsousCompte->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
