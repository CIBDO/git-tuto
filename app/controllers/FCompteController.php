<?php

/**
 * FCompteController
 *
 */
class FCompteController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["FCompte"]);
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
        $fcompte = FCompte::find();
        $rs = [];
        for($i = 0; $i < count($fcompte); $i++) {
            $rs[$i]['id']       = $fcompte[$i]->id;
            $rs[$i]['libelle']  = $fcompte[$i]->libelle;
            $rs[$i]['numero']  = $fcompte[$i]->numero;
            $rs[$i]['type']  = $fcompte[$i]->type;
        }
        $this->view->fcompte   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createFCompteAction() {
        $this->view->disable();
        $form = new FCompteForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $fcompte = new FCompte();
            $fcompte->libelle = $data['libelle'];
            $fcompte->numero = $data['numero'];
            $fcompte->type = $data['type'];

            if (!$fcompte->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Compte crée avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('f_compte/createFCompte');
    }

    public function editFCompteAction($id) {
        $this->view->disable();
        $form = new FCompteForm($this->trans);
        $this->view->fcompte_id = $id;
        $this->view->form_action = 'edit';
        $fcompte = FCompte::findFirst($id);
        if(!$fcompte){
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
            $fcompte->libelle = $data["libelle"];
            $fcompte->numero = $data['numero'];
            $fcompte->type = $data['type'];
            if (!$fcompte->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Compte modifié avec succès']);
            return $this->view->partial("layouts/flash");
        } else {
            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $fcompte->libelle,
                    "numero" => $fcompte->numero,
                    "type" => $fcompte->type,
            ));
            $this->view->partial("f_compte/createFCompte");
        }
    }

    public function deleteFCompteAction($id) {
        $this->view->disable();

        $fcompte = FCompte::findFirst($id);
        if(!$fcompte){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$fcompte->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
