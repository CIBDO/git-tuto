<?php

/**
 * TypeAssuranceController
 *
 */
class TypeAssuranceController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Organisme d'assurance"]);
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
        $typeAssurance = TypeAssurance::find();
        $rs = [];
        for($i = 0; $i < count($typeAssurance); $i++) {
            $rs[$i]['id']       = $typeAssurance[$i]->id;
            $rs[$i]['libelle']  = $typeAssurance[$i]->libelle;
            $rs[$i]['taux']     = $typeAssurance[$i]->taux;
        }
        /*print json_encode($typeAssurance, JSON_PRETTY_PRINT);exit();
        var_dump($typeAssurance);exit();*/
        $this->view->typeAssurance   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createTypeAssuranceAction() {
        $this->view->disable();
        $form = new TypeAssuranceForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            //verification doublons
            $typeAssurance = TypeAssurance::find("libelle = '" . $data['libelle'] . "'");
            if( count($typeAssurance) > 0 ){
                $this->flash->warning("Ce libellé existe déjà en base.");
                return $this->view->partial("layouts/flash");
            }
            $typeAssurance = new TypeAssurance();
            $typeAssurance->libelle = $data['libelle'];
            $typeAssurance->taux = $data['taux'];

            if (!$typeAssurance->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans["Organisme d'assurance créé avec succès"]);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('type_assurance/createTypeAssurance');
    }

    public function editTypeAssuranceAction($id) {
        $this->view->disable();
        $form = new TypeAssuranceForm($this->trans);
        $this->view->typeAssurance_id = $id;
        $this->view->form_action = 'edit';
        $typeAssurance = TypeAssurance::findFirst($id);
        if(!$typeAssurance){
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

            //verification doublons
            $test = TypeAssurance::find("libelle = '" . $data['libelle'] . "' AND id != ". $id);
            if( count($test) > 0 ){
                $this->flash->warning("Ce libellé existe déjà en base.");
                return $this->view->partial("layouts/flash");
            }
            
            $typeAssurance->libelle = $data["libelle"];
            $typeAssurance->taux = $data["taux"];
            if (!$typeAssurance->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans["Organisme d'assurance modifié avec succès"]);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $typeAssurance->libelle,
                    "taux" => $typeAssurance->taux
            ));
            $this->view->partial("type_assurance/createTypeAssurance");
        }
    }

    public function deleteTypeAssuranceAction($id) {
        $this->view->disable();

        $typeAssurance = TypeAssurance::findFirst($id);
        if(!$typeAssurance){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$typeAssurance->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
