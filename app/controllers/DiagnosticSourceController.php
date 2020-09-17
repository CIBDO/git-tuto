<?php

/**
 * DiagnosticSourceController
 *
 */
class DiagnosticSourceController extends ControllerBase {

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
        $diagnosticSource = DiagnosticSource::find();
        $rs = [];
        for($i = 0; $i < count($diagnosticSource); $i++) {
            $rs[$i]['id']       = $diagnosticSource[$i]->id;
            $rs[$i]['libelle']  = $diagnosticSource[$i]->libelle;
        }
        $this->view->diagnosticSource   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createDiagnosticSourceAction() {
        $this->view->disable();
        $form = new DiagnosticSourceForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';

        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $diagnosticSource = new DiagnosticSource();
            $diagnosticSource->libelle = $data['libelle'];

            if (!$diagnosticSource->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Diagnostic créé avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('diagnostic_source/createDiagnosticSource');
    }

    public function editDiagnosticSourceAction($id) {
        $this->view->disable();
        $form = new DiagnosticSourceForm($this->trans);
        $this->view->form_action = 'edit';
        $this->view->diagnosticSource_id = $id;

        $diagnosticSource = DiagnosticSource::findFirst($id);
        if(!$diagnosticSource){
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
            $diagnosticSource->libelle = $data["libelle"];
            if (!$diagnosticSource->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Diagnostic modifié avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $diagnosticSource->libelle
            ));
            $this->view->partial("diagnostic_source/createDiagnosticSource");
        }
    }

    public function deleteDiagnosticSourceAction($id) {
        $this->view->disable();

        $diagnosticSource = DiagnosticSource::findFirst($id);
        if(!$diagnosticSource){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$diagnosticSource->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
