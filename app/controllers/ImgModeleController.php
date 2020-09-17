<?php

/**
 * ImgModeleController
 *
 */
class ImgModeleController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Modèle de résultat"]);
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
        $imgModele = ImgModele::find();
        $rs = [];
        for($i = 0; $i < count($imgModele); $i++) {
            $rs[$i]['id']       = $imgModele[$i]->id;
            $rs[$i]['interpretation']  = $imgModele[$i]->interpretation;
            $rs[$i]['conclusion']  = $imgModele[$i]->conclusion;
            $rs[$i]['keyword']  = $imgModele[$i]->keyword;
        }
        $this->view->imgModele   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createImgModeleAction() {
        $this->view->disable();
        $form = new ImgModeleForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $imgModele = new ImgModele();
            $imgModele->interpretation = $data['interpretation'];
            $imgModele->conclusion = $data['conclusion'];
            $imgModele->keyword = $data['keyword'];

            if (!$imgModele->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans["Modele créé avec succès"]);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('img_modele/createImgModele');
    }

    public function editImgModeleAction($id) {
        $this->view->disable();
        $form = new ImgModeleForm($this->trans);
        $this->view->imgModele_id = $id;
        $this->view->form_action = 'edit';
        $imgModele = ImgModele::findFirst($id);
        if(!$imgModele){
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
            $imgModele->interpretation  = $data["interpretation"];
            $imgModele->conclusion      = $data["conclusion"];
            $imgModele->keyword         = $data["keyword"];
            if (!$imgModele->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans["Catégorie d'acte modifiée avec succès"]);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "interpretation" => $imgModele->interpretation,
                    "conclusion" => $imgModele->conclusion,
                    "keyword" => $imgModele->keyword
            ));
            $this->view->partial("img_modele/createImgModele");
        }
    }

    public function importModeleAction($patients_id, $dossier_id) {
        $this->view->disable();

        $imgModele = ImgModele::find();
        $rs = [];
        for($i = 0; $i < count($imgModele); $i++) {
            $rs[$i]['id']       = $imgModele[$i]->id;
            $rs[$i]['interpretation']  = $imgModele[$i]->interpretation;
            $rs[$i]['conclusion']  = $imgModele[$i]->conclusion;
            $rs[$i]['keyword']  = $imgModele[$i]->keyword;
            $rs[$i]['keyword2']  = $imgModele[$i]->keyword;
        }
        $this->view->patients_id   = $patients_id;
        $this->view->dossier_id   = $dossier_id;
        $this->view->imgModele   = json_encode($rs, JSON_PRETTY_PRINT);
        $this->view->partial('img_modele/importModele');
    }

    public function deleteImgModeleAction($id) {
        $this->view->disable();

        $imgModele = ImgModele::findFirst($id);
        if(!$imgModele){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$imgModele->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}