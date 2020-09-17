<?php

/**
 * CsMotifsController
 *
 */
class CsMotifsController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Motifs de consultation"]);
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
        $csMotifs = CsMotifs::find();
        $rs = [];
        for($i = 0; $i < count($csMotifs); $i++) {
            $rs[$i]['id']       = $csMotifs[$i]->id;
            $rs[$i]['libelle']  = $csMotifs[$i]->libelle;
        }
        $this->view->csMotifs   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createCsMotifsAction() {
        $this->view->disable();
        $form = new CsMotifsForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';

        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $csMotifs = new CsMotifs();
            $csMotifs->libelle = $data['libelle'];

            if (!$csMotifs->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Motif créé avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('cs_motifs/createCsMotifs');
    }

    public function editCsMotifsAction($id) {
        $this->view->disable();
        $form = new CsMotifsForm($this->trans);
        $this->view->form_action = 'edit';
        $this->view->csMotifs_id = $id;

        $csMotifs = CsMotifs::findFirst($id);
        if(!$csMotifs){
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
            $csMotifs->libelle = $data["libelle"];
            if (!$csMotifs->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Motif modifié avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $csMotifs->libelle
            ));
            $this->view->partial("cs_motifs/createCsMotifs");
        }
    }

    public function deleteCsMotifsAction($id) {
        $this->view->disable();

        $csMotifs = CsMotifs::findFirst($id);
        if(!$csMotifs){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$csMotifs->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
