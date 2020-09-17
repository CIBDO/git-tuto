<?php

/**
 * ResidenceController
 *
 */
class ResidenceController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Residence"]);
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
        $residence = Residence::find();
        $rs = [];
        for($i = 0; $i < count($residence); $i++) {
            $rs[$i]['id']       = $residence[$i]->id;
            $rs[$i]['libelle']  = $residence[$i]->libelle;
        }
        $this->view->residence   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createResidenceAction() {
        $this->view->disable();
        $form = new ResidenceForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $residence = new Residence();
            $residence->libelle = $data['libelle'];

            if (!$residence->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Residence créée avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('residence/createResidence');
    }

    public function editResidenceAction($id) {
        $this->view->disable();
        $form = new ResidenceForm($this->trans);
        $this->view->residence_id = $id;
        $this->view->form_action = 'edit';
        $residence = Residence::findFirst($id);
        if(!$residence){
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
            $residence->libelle = $data["libelle"];
            if (!$residence->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Residence modifiée avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $residence->libelle,
            ));
            $this->view->partial("residence/createResidence");
        }
    }

    public function deleteResidenceAction($id) {
        $this->view->disable();

        $residence = Residence::findFirst($id);
        if(!$residence){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$residence->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
