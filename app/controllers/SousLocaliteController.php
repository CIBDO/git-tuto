<?php

/**
 * ResidenceController
 *
 */
class SousLocaliteController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Sous localité"]);
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
        $sous_localites = SousLocalite::find();
        $sl = [];
//        var_dump($sous_localites[0]->residence->libelle);exit();
        for($i = 0; $i < count($sous_localites); $i++) {
            $sl[$i]['id']       = $sous_localites[$i]->id;
            $sl[$i]['libelle']  = $sous_localites[$i]->libelle;
            $sl[$i]['localite']  = $sous_localites[$i]->residence->libelle;
        }

        $this->view->sous_localites   = json_encode($sl, JSON_PRETTY_PRINT);
    }

    public function createSousLocaliteAction() {
        $this->view->disable();
        $form = new SousLocaliteForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $sous_localite = new SousLocalite();
            $sous_localite->libelle = $data['libelle'];
            $sous_localite->residence_id = $data['residence_id'];

            if (!$sous_localite->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Sous localité créée avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('sous_localite/createSousLocalite');
    }

    public function editSousLocaliteAction($id) {
        $this->view->disable();
        $form = new SousLocaliteForm($this->trans);
        $this->view->sous_localite_id = $id;
        $this->view->form_action = 'edit';
        $sous_localite = SousLocalite::findFirst($id);
        if(!$sous_localite){
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
            $sous_localite->libelle = $data["libelle"];
            $sous_localite->residence_id = $data['residence_id'];
            if (!$sous_localite->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Sous localité modifiée avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $sous_localite->libelle,
                    "residence_id" => $sous_localite->residence_id,
            ));
            $this->view->partial("sous_localite/createSousLocalite");
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
