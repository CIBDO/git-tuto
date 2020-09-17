<?php

/**
 * LaboAntibiogrammesController
 *
 */
class LaboAntibiogrammesController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Antibiogramme"]);
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
        $laboAntibiogrammes = LaboAntibiogrammes::find();

        $rs = [];
        for($i = 0; $i < count($laboAntibiogrammes); $i++) {
            $rs[$i]['id']           = $laboAntibiogrammes[$i]->id;
            $rs[$i]['libelle']      = $laboAntibiogrammes[$i]->libelle;
            $rs[$i]['labo_antibiogrammes_type']       = $laboAntibiogrammes[$i]->getLaboAntibiogrammesType()->libelle ;
        }
        $this->view->laboAntibiogrammes   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createLaboAntibiogrammesAction() {
        $this->view->disable();
        $form = new LaboAntibiogrammesForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $laboAntibiogrammes = new LaboAntibiogrammes();
            $laboAntibiogrammes->libelle = $data['libelle'];
            $laboAntibiogrammes->antibiotiques = ( count($data['laboAntibiotiques']) >0 ) 
                                                    ? implode(",",$data['laboAntibiotiques']) : "";
            $laboAntibiogrammes->labo_antibiogrammes_type_id = $data['labo_antibiogrammes_type_id'];

            if (!$laboAntibiogrammes->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Antibiogramme créé avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $laboAntibiotiques = LaboAntibiotiques::find();
        $rs = [];
        foreach ($laboAntibiotiques as $laboAntibiotique) {
            $rs[$laboAntibiotique->id] = $laboAntibiotique->libelle;
        }
        $this->view->laboAntibiotiques = $rs;
        $this->view->partial('labo_antibiogrammes/createLaboAntibiogrammes');
    }

    public function editLaboAntibiogrammesAction($id) {
        $this->view->disable();
        $form = new LaboAntibiogrammesForm($this->trans);
        $this->view->laboAntibiogrammes_id = $id;
        $this->view->form_action = 'edit';
        $laboAntibiogrammes = LaboAntibiogrammes::findFirst($id);
        if(!$laboAntibiogrammes){
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
            $laboAntibiogrammes->libelle = $data["libelle"];
            $laboAntibiogrammes->antibiotiques = (count($data['laboAntibiotiques'])>0) 
                                                    ? implode(",",$data['laboAntibiotiques']) : "";
            $laboAntibiogrammes->labo_antibiogrammes_type_id = $data['labo_antibiogrammes_type_id'];
            if (!$laboAntibiogrammes->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Antibiogramme modifié avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $laboAntibiogrammes->libelle,
                    "laboAntibiotiques[]" => explode(",", $laboAntibiogrammes->antibiotiques),
                    "labo_antibiogrammes_type_id" => $laboAntibiogrammes->labo_antibiogrammes_type_id,
            ));
            $laboAntibiotiques = LaboAntibiotiques::find();
            $rs = [];
            foreach ($laboAntibiotiques as $laboAntibiotique) {
                $rs[$laboAntibiotique->id] = $laboAntibiotique->libelle;
            }
            $this->view->laboAntibiotiques = $rs;
            $this->view->partial("labo_antibiogrammes/createLaboAntibiogrammes");
        }
    }

    public function deleteLaboAntibiogrammesAction($id) {
        $this->view->disable();

        $laboAntibiogrammes = LaboAntibiogrammes::findFirst($id);
        if(!$laboAntibiogrammes){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$laboAntibiogrammes->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
