<?php

/**
 * LaboCategoriesAnalyseController
 *
 */
class LaboCategoriesAnalyseController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Catégorie d'analyse"]);
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
        $laboCategoriesAnalyse = LaboCategoriesAnalyse::find();
        $rs = [];
        for($i = 0; $i < count($laboCategoriesAnalyse); $i++) {
            $rs[$i]['id']       = $laboCategoriesAnalyse[$i]->id;
            $rs[$i]['libelle']  = $laboCategoriesAnalyse[$i]->libelle;
        }
        $this->view->laboCategoriesAnalyse   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createLaboCategoriesAnalyseAction() {
        $this->view->disable();
        $form = new LaboCategoriesAnalyseForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $laboCategoriesAnalyse = new LaboCategoriesAnalyse();
            $laboCategoriesAnalyse->libelle = $data['libelle'];

            if (!$laboCategoriesAnalyse->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans["Catégorie d'analyse créée avec succès"]);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('labo_categories_analyse/createLaboCategoriesAnalyse');
    }

    public function editLaboCategoriesAnalyseAction($id) {
        $this->view->disable();
        $form = new LaboCategoriesAnalyseForm($this->trans);
        $this->view->laboCategoriesAnalyse_id = $id;
        $this->view->form_action = 'edit';
        $laboCategoriesAnalyse = LaboCategoriesAnalyse::findFirst($id);
        if(!$laboCategoriesAnalyse){
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
            $laboCategoriesAnalyse->libelle = $data["libelle"];
            if (!$laboCategoriesAnalyse->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans["Catégorie d'analyse modifiée avec succès"]);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $laboCategoriesAnalyse->libelle,
            ));
            $this->view->partial("labo_categories_analyse/createLaboCategoriesAnalyse");
        }
    }

    public function deleteLaboCategoriesAnalyseAction($id) {
        $this->view->disable();

        $laboCategoriesAnalyse = LaboCategoriesAnalyse::findFirst($id);
        if(!$laboCategoriesAnalyse){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$laboCategoriesAnalyse->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
