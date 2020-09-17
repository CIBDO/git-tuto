<?php

/**
 * ClasseTherapeutiqueController
 *
 */
class ClasseTherapeutiqueController extends ControllerBase {

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
        $classeTherapeutique = ClasseTherapeutique::find();
        $rs = [];
        for($i = 0; $i < count($classeTherapeutique); $i++) {
            $rs[$i]['id']       = $classeTherapeutique[$i]->id;
            $rs[$i]['libelle']  = $classeTherapeutique[$i]->libelle;
        }
        $this->view->classeTherapeutique   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createClasseTherapeutiqueAction() {
        $this->view->disable();
        $form = new ClasseTherapeutiqueForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $classeTherapeutique = new ClasseTherapeutique();
            $classeTherapeutique->libelle = $data['libelle'];

            if (!$classeTherapeutique->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Classe therapeutique créée avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('classe_therapeutique/createClasseTherapeutique');
    }

    public function editClasseTherapeutiqueAction($id) {
        $this->view->disable();
        $form = new ClasseTherapeutiqueForm($this->trans);
        $this->view->classeTherapeutique_id = $id;
        $this->view->form_action = 'edit';
        $classeTherapeutique = ClasseTherapeutique::findFirst($id);
        if(!$classeTherapeutique){
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
            $classeTherapeutique->libelle = $data["libelle"];
            if (!$classeTherapeutique->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Classe therapeutique modifiée avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $classeTherapeutique->libelle,
            ));
            $this->view->partial("classe_therapeutique/createClasseTherapeutique");
        }
    }

    public function deleteClasseTherapeutiqueAction($id) {
        $this->view->disable();

        $classeTherapeutique = ClasseTherapeutique::findFirst($id);
        if(!$classeTherapeutique){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$classeTherapeutique->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
