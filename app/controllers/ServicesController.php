<?php

/**
 * ServicesController
 *
 */
class ServicesController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans['Services']);
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
        $services = Services::find();
        $rs = [];
        for($i = 0; $i < count($services); $i++) {
            $rs[$i]['id']       = $services[$i]->id;
            $rs[$i]['libelle']  = $services[$i]->libelle;
        }
        $this->view->services   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createServiceAction() {
        $this->view->disable();
        $form = new ServicesForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';

        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $service = new Services();
            $service->libelle = $data['libelle'];

            if (!$service->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Service créé avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('services/createService');
    }

    public function editServiceAction($id) {
        $this->view->disable();
        $form = new ServicesForm($this->trans);
        $this->view->service_id = $id;
        $this->view->form_action = 'edit';
        $this->view->service_id = $id;

        $service = Services::findFirst($id);
        if(!$service){
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
            $service->libelle = $data["libelle"];
            if (!$service->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Service modifié avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $service->libelle
            ));
            $this->view->partial("services/createService");
        }
    }

    public function deleteServiceAction($id) {
        $this->view->disable();

        $service = Services::findFirst($id);
        if(!$service){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$service->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
