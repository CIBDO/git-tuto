<?php

/**
 * AjustementMotifsController
 *
 */
class AjustementMotifsController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Motifs d'ajustement"]);
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
        $ajustementMotifs = AjustementMotifs::find();
        $rs = [];
        for($i = 0; $i < count($ajustementMotifs); $i++) {
            $rs[$i]['id']       = $ajustementMotifs[$i]->id;
            $rs[$i]['libelle']  = $ajustementMotifs[$i]->libelle;
        }
        $this->view->ajustementMotifs   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createAjustementMotifsAction() {
        $this->view->disable();
        $form = new AjustementMotifsForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';

        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $ajustementMotifs = new AjustementMotifs();
            $ajustementMotifs->libelle = $data['libelle'];

            if (!$ajustementMotifs->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Motif créé avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('ajustement_motifs/createAjustementMotifs');
    }

    public function editAjustementMotifsAction($id) {
        $this->view->disable();
        $form = new AjustementMotifsForm($this->trans);
        $this->view->form_action = 'edit';
        $this->view->ajustementMotifs_id = $id;

        $ajustementMotifs = AjustementMotifs::findFirst($id);
        if(!$ajustementMotifs){
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
            $ajustementMotifs->libelle = $data["libelle"];
            if (!$ajustementMotifs->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Motif modifié avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $ajustementMotifs->libelle
            ));
            $this->view->partial("ajustement_motifs/createAjustementMotifs");
        }
    }

    public function deleteAjustementMotifsAction($id) {
        $this->view->disable();

        $ajustementMotifs = AjustementMotifs::findFirst($id);
        if(!$ajustementMotifs){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$ajustementMotifs->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
