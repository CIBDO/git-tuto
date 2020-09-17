<?php

/**
 * TypeProduitController
 *
 */
class TypeProduitController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Type de produit"]);
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
        $typeProduit = TypeProduit::find();
        $rs = [];
        for($i = 0; $i < count($typeProduit); $i++) {
            $rs[$i]['id']       = $typeProduit[$i]->id;
            $rs[$i]['libelle']  = $typeProduit[$i]->libelle;
        }
        $this->view->typeProduit   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createTypeProduitAction() {
        $this->view->disable();
        $form = new TypeProduitForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $typeProduit = new TypeProduit();
            $typeProduit->libelle = $data['libelle'];

            if (!$typeProduit->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Type de produit créé avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('type_produit/createTypeProduit');
    }

    public function editTypeProduitAction($id) {
        $this->view->disable();
        $form = new TypeProduitForm($this->trans);
        $this->view->typeProduit_id = $id;
        $this->view->form_action = 'edit';
        $typeProduit = TypeProduit::findFirst($id);
        if(!$typeProduit){
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
            $typeProduit->libelle = $data["libelle"];
            if (!$typeProduit->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Type de produit modifié avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $typeProduit->libelle,
            ));
            $this->view->partial("type_produit/createTypeProduit");
        }
    }

    public function deleteTypeProduitAction($id) {
        $this->view->disable();

        $typeProduit = TypeProduit::findFirst($id);
        if(!$typeProduit){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$typeProduit->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
