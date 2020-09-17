<?php

/**
 * FournisseurController
 *
 */
class FournisseurController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Fournisseur"]);
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
        $fournisseur = Fournisseur::find();
        $rs = [];
        for($i = 0; $i < count($fournisseur); $i++) {
            $rs[$i]['id']       = $fournisseur[$i]->id;
            $rs[$i]['libelle']  = $fournisseur[$i]->libelle;
            $rs[$i]['telephone']  = $fournisseur[$i]->telephone;
            $rs[$i]['adresse']  = $fournisseur[$i]->adresse;
            $rs[$i]['email']  = $fournisseur[$i]->email;
        }
        $this->view->fournisseur   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createFournisseurAction() {
        $this->view->disable();
        $form = new FournisseurForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $fournisseur = new Fournisseur();
            $fournisseur->libelle = $data['libelle'];
            $fournisseur->telephone = $data['telephone'];
            $fournisseur->adresse = $data['adresse'];
            $fournisseur->email = $data['email'];

            if (!$fournisseur->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Founisseur créé avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('fournisseur/createFournisseur');
    }

    public function editFournisseurAction($id) {
        $this->view->disable();
        $form = new FournisseurForm($this->trans);
        $this->view->fournisseur_id = $id;
        $this->view->form_action = 'edit';
        $fournisseur = Fournisseur::findFirst($id);
        if(!$fournisseur){
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
            $fournisseur->libelle = $data["libelle"];
            $fournisseur->telephone = $data['telephone'];
            $fournisseur->adresse = $data['adresse'];
            $fournisseur->email = $data['email'];
            if (!$fournisseur->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Founisseur créé avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $fournisseur->libelle,
                    "telephone" => $fournisseur->telephone,
                    "adresse" => $fournisseur->adresse,
                    "email" => $fournisseur->email,
            ));
            $this->view->partial("fournisseur/createFournisseur");
        }
    }

    public function deleteFournisseurAction($id) {
        $this->view->disable();

        $fournisseur = Fournisseur::findFirst($id);
        if(!$fournisseur){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$fournisseur->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
