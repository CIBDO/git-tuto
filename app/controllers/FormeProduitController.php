<?php

/**
 * FormeProduitController
 *
 */
class FormeProduitController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Forme de produit"]);
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
        $formeProduit = FormeProduit::find();
        $rs = [];
        for($i = 0; $i < count($formeProduit); $i++) {
            $rs[$i]['id']       = $formeProduit[$i]->id;
            $rs[$i]['libelle']  = $formeProduit[$i]->libelle;
        }
        $this->view->formeProduit   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createFormeProduitAction() {
        $this->view->disable();
        $form = new FormeProduitForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $formeProduit = new FormeProduit();
            $formeProduit->libelle = $data['libelle'];

            if (!$formeProduit->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Forme de produit créée avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('forme_produit/createFormeProduit');
    }

    public function editFormeProduitAction($id) {
        $this->view->disable();
        $form = new FormeProduitForm($this->trans);
        $this->view->formeProduit_id = $id;
        $this->view->form_action = 'edit';
        $formeProduit = FormeProduit::findFirst($id);
        if(!$formeProduit){
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
            $formeProduit->libelle = $data["libelle"];
            if (!$formeProduit->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Forme de produit modifiée avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $formeProduit->libelle,
            ));
            $this->view->partial("forme_produit/createFormeProduit");
        }
    }

    public function deleteFormeProduitAction($id) {
        $this->view->disable();

        $formeProduit = FormeProduit::findFirst($id);
        if(!$formeProduit){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$formeProduit->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
