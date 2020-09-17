<?php

/**
 * FDesignationController
 *
 */
class FDesignationController extends ControllerBase {

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
        $fdesignation = FDesignation::find();
        $rs = [];
        for($i = 0; $i < count($fdesignation); $i++) {
            $rs[$i]['id']       = $fdesignation[$i]->id;
            $rs[$i]['libelle']  = $fdesignation[$i]->libelle;
        }
        $this->view->fdesignation   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createFDesignationAction() {
        $this->view->disable();
        $form = new FDesignationForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $fdesignation = new FDesignation();
            $fdesignation->libelle = $data['libelle'];

            if (!$fdesignation->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Designation créée avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('f_designation/createFDesignation');
    }

    public function editFDesignationAction($id) {
        $this->view->disable();
        $form = new FDesignationForm($this->trans);
        $this->view->fdesignation_id = $id;
        $this->view->form_action = 'edit';
        $fdesignation = FDesignation::findFirst($id);
        if(!$fdesignation){
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
            $fdesignation->libelle = $data["libelle"];
            if (!$fdesignation->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Designation modifiée avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $fdesignation->libelle,
            ));
            $this->view->partial("f_designation/createFDesignation");
        }
    }

    public function deleteFDesignationAction($id) {
        $this->view->disable();

        $fdesignation = FDesignation::findFirst($id);
        if(!$fdesignation){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$fdesignation->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
