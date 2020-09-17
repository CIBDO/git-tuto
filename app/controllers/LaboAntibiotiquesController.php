<?php

/**
 * LaboAntibiotiquesController
 *
 */
class LaboAntibiotiquesController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Antibiotique"]);
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
        $laboAntibiotiques = LaboAntibiotiques::find();

        $rs = [];
        for($i = 0; $i < count($laboAntibiotiques); $i++) {
            $rs[$i]['id']       = $laboAntibiotiques[$i]->id;
            $rs[$i]['code']     = $laboAntibiotiques[$i]->code;
            $rs[$i]['libelle']  = $laboAntibiotiques[$i]->libelle;
        }
        $this->view->laboAntibiotiques   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createLaboAntibiotiquesAction() {
        $this->view->disable();
        $form = new LaboAntibiotiquesForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $laboAntibiotiques = new LaboAntibiotiques();
            $laboAntibiotiques->code = $data['code'];
            $laboAntibiotiques->libelle = $data['libelle'];

            if (!$laboAntibiotiques->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Antibiotique créé avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('labo_antibiotiques/createLaboAntibiotiques');
    }

    public function editLaboAntibiotiquesAction($id) {
        $this->view->disable();
        $form = new LaboAntibiotiquesForm($this->trans);
        $this->view->laboAntibiotiques_id = $id;
        $this->view->form_action = 'edit';
        $laboAntibiotiques = LaboAntibiotiques::findFirst($id);
        if(!$laboAntibiotiques){
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
            $laboAntibiotiques->code = $data["code"];
            $laboAntibiotiques->libelle = $data["libelle"];
            if (!$laboAntibiotiques->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Antibiotique modifié avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "code" => $laboAntibiotiques->code,
                    "libelle" => $laboAntibiotiques->libelle,
            ));
            $this->view->partial("labo_antibiotiques/createLaboAntibiotiques");
        }
    }

    public function deleteLaboAntibiotiquesAction($id) {
        $this->view->disable();

        $laboAntibiotiques = LaboAntibiotiques::findFirst($id);
        if(!$laboAntibiotiques){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$laboAntibiotiques->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
