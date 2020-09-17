<?php

/**
 * LaboAntibiogrammesTypeController
 *
 */
class LaboAntibiogrammesTypeController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Types d'antibiogramme"]);
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
        $laboAntibiogrammesType = LaboAntibiogrammesType::find();
        $rs = [];
        for($i = 0; $i < count($laboAntibiogrammesType); $i++) {
            $rs[$i]['id']       = $laboAntibiogrammesType[$i]->id;
            $rs[$i]['libelle']  = $laboAntibiogrammesType[$i]->libelle;
        }
        $this->view->laboAntibiogrammesType   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createLaboAntibiogrammesTypeAction() {
        $this->view->disable();
        $form = new LaboAntibiogrammesTypeForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $laboAntibiogrammesType = new LaboAntibiogrammesType();
            $laboAntibiogrammesType->libelle = $data['libelle'];

            if (!$laboAntibiogrammesType->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans["Type d'antibiogramme créé avec succès"]);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('labo_antibiogrammes_type/createLaboAntibiogrammesType');
    }

    public function editLaboAntibiogrammesTypeAction($id) {
        $this->view->disable();
        $form = new LaboAntibiogrammesTypeForm($this->trans);
        $this->view->laboAntibiogrammesType_id = $id;
        $this->view->form_action = 'edit';
        $laboAntibiogrammesType = LaboAntibiogrammesType::findFirst($id);
        if(!$laboAntibiogrammesType){
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
            $laboAntibiogrammesType->libelle = $data["libelle"];
            if (!$laboAntibiogrammesType->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans["Types d'antibiogramme modifié avec succès"]);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $laboAntibiogrammesType->libelle,
            ));
            $this->view->partial("labo_antibiogrammes_type/createLaboAntibiogrammesType");
        }
    }

    public function deleteLaboAntibiogrammesTypeAction($id) {
        $this->view->disable();

        $laboAntibiogrammesType = LaboAntibiogrammesType::findFirst($id);
        if(!$laboAntibiogrammesType){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$laboAntibiogrammesType->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
