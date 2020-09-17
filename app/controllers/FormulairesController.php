<?php

/**
 * FormulairesController
 *
 */
class FormulairesController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans['Gestion des formulaires']);
        if($this->view->language == "fr"){
            $langue = "fr-FR";
        }
        else{
            $langue = "en-US";
        }
        $this->view->langue = $langue;
    }

    public function indexAction($id = "") {       
        $forms = Forms::find();
        $rs = [];
        for($i = 0; $i < count($forms); $i++) {
            $rs[$i]['id']       = $forms[$i]->id;
            $rs[$i]['libelle']  = $forms[$i]->libelle;
            $rs[$i]['code']     = $forms[$i]->code;
            $rs[$i]['type']     = $forms[$i]->type;
            $rs[$i]['hide_default'] = $forms[$i]->hide_default;
        }
        $this->view->forms              = $rs;
        $this->view->currentForms_id    = $id;
        if($id > 0){
            $currentForms = Forms::findFirst($id);
            if($currentForms){
                $this->view->currentForms   = $currentForms;
                $this->view->formsElements  = $currentForms->getFormsElements(array("order" => "position ASC"));
            }
        }
    }

    public function createFormulairesAction() {
        $this->view->disable();
        $form = new FormsForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';

        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $forms = new Forms();
            $forms->libelle     = $data['libelle'];
            $forms->type        = $data['type'];
            $forms->hide_default= $data['hide_default'];
            $forms->forms_assoc = isset($data['forms']) ? implode(",", $data['forms']) : "";
            $forms->code        = $this->no_special_character($data['libelle']);

            if (!$forms->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Formulaire créé avec succès']);
            return $this->view->partial("layouts/flash");
        }

        $forms = Forms::find("type = 'onglet'");
        $rs = [];
        foreach ($forms as $f) {
            $rs[$f->id] = $f->libelle;
        }
        $this->view->forms = $rs;
        $this->view->partial('formulaires/createFormulaires');
    }

    public function editFormulairesAction($id) {
        $this->view->disable();
        $form = new FormsForm($this->trans);
        $this->view->form_action    = 'edit';
        $this->view->forms_id       = $id;

        $forms = Forms::findFirst($id);
        if(!$forms){
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
            $forms->libelle     = $data["libelle"];
            $forms->forms_assoc = isset($data['forms']) ? implode(",", $data['forms']) : "";
            $forms->type        = $data["type"];
            $forms->hide_default= $data["hide_default"];
            $forms->code        = $this->no_special_character($data['libelle']);
            if (!$forms->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Formulaire modifié avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle"       => $forms->libelle,
                    "forms[]"       => explode(",", $forms->forms_assoc),
                    "type"          => $forms->type,
                    "hide_default"  => $forms->hide_default
            ));

            $forms = Forms::find(array("type = 'onglet'"));
            $rs = [];
            foreach ($forms as $f) {
                $rs[$f->id] = $f->libelle;
            }
            $this->view->forms = $rs;
            $this->view->partial("formulaires/createFormulaires");
        }
    }

    public function elementsAction($id) {

        if ($this->request->isPost()) {
            $data = $this->request->getPost();

            $i = 0;
            while(isset($data["element"][$i])){
                if ($data["element"][$i] != "") {
                    $formsElements = FormsElements::findFirst($data["element"][$i]);
                }
                else{
                    $formsElements = new FormsElements();
                }

                $formsElements->libelle         = $data["libelle"][$i];
                $formsElements->position        = $i;
                $formsElements->type_valeur     = $data["type_valeur"][$i];
                $formsElements->valeur_possible = isset($data["valeur_possible"][$i]) ? $data["valeur_possible"][$i] : "";
                $formsElements->forms_id        = $id;
                $formsElements->place_after_c   = isset($data["place_after_c"][$i]) ? $data["place_after_c"][$i] : "";
                $formsElements->place_after_s   = "";
                $formsElements->required        = $data["required"][$i];

                $formsElements->save();

                $i++;
            }

            $this->flash->success($this->trans['Elements enregistrés avec succès']);
            return $this->response->redirect('formulaires/index/' . $id);
        }
    }

    public function deleteFormulairesAction($id) {
        $this->view->disable();

        //Suppression des result
        $formsElements = FormsElements::find("forms_id = " . $id);
        for($i = 0; $i < count($formsElements); $i++) {
            $query = $this->modelsManager->createQuery("DELETE FROM FormsResults WHERE forms_elements_id = :id:");
            $exec  = $query->execute(array('id' => $formsElements[$i]->id));
        }
       
        //Suppression des elements
        $query = $this->modelsManager->createQuery("DELETE FROM FormsElements WHERE forms_id = :id:");
        $exec  = $query->execute(array('id' => $id));

        $forms = Forms::findFirst($id);
        if(!$forms){
            echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$forms->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }

    public function deleteItemAction($id) {

        $this->view->disable();
        $query = $this->modelsManager->createQuery("DELETE FROM FormsResults WHERE forms_elements_id = :id:");
        $exec  = $query->execute(array('id' => $id));

        $formsElements = FormsElements::findFirst($id);
        if(!$formsElements){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$formsElements->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }

    private function no_special_character($chaine){
        //  les accents
        $chaine=trim($chaine);
        $chaine= strtr($chaine,"ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ","aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn");
        //  les caracètres spéciaux (aures que lettres et chiffres en fait)
        $chaine = preg_replace('/([^.a-z0-9]+)/i', '_', $chaine);
        $chaine = trim($chaine, '_');
        $chaine = strtolower($chaine);
        return $chaine;
    }

}
