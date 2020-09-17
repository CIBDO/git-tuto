<?php

use Phalcon\Mvc\Model\Resultset;

/**
 * ActesController
 *
 */
class ActesController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans['Actes']);
        if($this->view->language == "fr"){
            $langue = "fr-FR";
        }
        else{
            $langue = "en-US";
        }
        $this->view->langue = $langue;
        $this->view->activeModules = $this->config->activeModules;
    }

    public function indexAction() {       
        $data = [];
        if ($this->request->isPost()) {
            $data["param1"] = $this->request->getPost("param1");
            Phalcon\Tag::setDefaults( array("param1" => $data["param1"]) );
        }
        $actes = Actes::find();

        $rs = [];
        for($i = 0; $i < count($actes); $i++) {
            $rs[$i]['id']           = $actes[$i]->id;
            $rs[$i]['code']         = $actes[$i]->code;
            $rs[$i]['type']         = $actes[$i]->type;
            $rs[$i]['unite']        = $actes[$i]->unite;
            $rs[$i]['libelle']      = $actes[$i]->libelle;
            $rs[$i]['prix']         = $actes[$i]->prix;
            $rs[$i]['service_name'] = $actes[$i]->getServices()->libelle;
            $rs[$i]['services_id']  = $actes[$i]->services_id;
        }

        $this->view->actes   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function ajaxCaisseAction($prix_id = "") {
        $this->view->disable();

        $actes = Actes::find();

        $rs = [];
        for($i = 0; $i < count($actes); $i++) {
            
            $priceArray = [];
            $prix_actes = PrixActes::find(array("actes_id = " . $actes[$i]->id . " "));
            for($k = 0; $k < count($prix_actes); $k++) {
                $priceArray[$k]['type_assurance_id']    = $prix_actes[$k]->type_assurance_id;
                $priceArray[$k]['prix']                 = $prix_actes[$k]->prix;
                $priceArray[$k]['relicat']              = $prix_actes[$k]->relicat;
            }

            $rs[$i]['id']       = $actes[$i]->id."|".$actes[$i]->prix."|".json_encode($priceArray);
            $rs[$i]['libelle']  = $actes[$i]->unite . " / " . $actes[$i]->libelle . " / " .$actes[$i]->prix . "F CFA";
        }
        
        echo json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function ajaxActeLaboAction() {
        $this->view->disable();
        
        $actes = Actes::find(array("type = 'labo'"));

        $rs = [];
        for($i = 0; $i < count($actes); $i++) {
            $rs[$i]['id'] = $actes[$i]->id;
            $rs[$i]['libelle'] = $actes[$i]->libelle;
        }
        
        echo json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createActeAction() {
        $this->view->disable();
        $form = new ActesForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';

        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $acte = new Actes();
            $acte->libelle      = $data['libelle'];
            $acte->code         = $data['code'];
            $acte->type         = $data['type'];
            $acte->unite        = $data['unite'];
            $acte->prix         = $data['prix'];
            $acte->services_id  = $data['services_id'];

            if (!$acte->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            else{
                 if(isset($data["type_assurance_id"])){
                    $i = 0;
                    while(isset($data['type_assurance_id'][$i])){
                        if($data['prix_id'][$i] != ""){
                            $prixObj    = PrixActes::findFirst($data['prix_id'][$i]);
                        }
                        else{
                            $prixObj    = new PrixActes();
                        }
                        $prixObj->prix              = ($data['prix2'][$i]>0) ? $data['prix2'][$i] : $data['prix'];
                        $prixObj->actes_id          = $acte->id;
                        $prixObj->type_assurance_id = $data['type_assurance_id'][$i];
                        $prixObj->relicat           = $data['relicat'][$i];

                        if (!$prixObj->save()) {
                            $msg = "Les autres prix n'ont pas été correctement enrégistrés";
                            $this->flash->error($msg);
                            return $this->view->partial("layouts/flash");
                        }
                    
                        $i++;
                    }
                }
            }
            
            if( isset($data["check_module"]) ){
                //creation de l'acte dans labo ou imagerie
                if( isset($data["check_module_value"]) && ($data["check_module_value"] == "labo") ){
                    $laboAnalyses           = new LaboAnalyses();
                    $laboAnalyses->libelle  = $data['libelle'];
                    $laboAnalyses->code     = $data['code'];
                    $laboAnalyses->labo_categories_analyse_id = (isset($data['labo_categories']) && $data['labo_categories'] > 0) ? $data['labo_categories'] : null;;

                    if (!$laboAnalyses->save()) {
                        $msg = $this->trans['on_error'];
                        $this->flash->error($msg);
                    }
                }
                if( isset($data["check_module_value"]) && ($data["check_module_value"] == "imagerie") ){
                    $imgItems           = new ImgItems();
                    $imgItems->libelle  = $data['libelle'];
                    $imgItems->code     = $data['code'];
                    $imgItems->img_items_categories_id = (isset($data['img_categories']) && $data['img_categories'] > 0) ? $data['img_categories'] : null;;

                    if (!$imgItems->save()) {
                        $msg = $this->trans['on_error'];
                        $this->flash->error($msg);
                    }
                }
            }
            $this->flash->success($this->trans['Prestation créée avec succès']);
            return $this->view->partial("layouts/flash");
        }

        $prix_list = TypeAssurance::find();
        $rsList = [];
        for($i = 0; $i < count($prix_list); $i++) {
            $rsList[$i]['id']       = $prix_list[$i]->id;
            $rsList[$i]['libelle']  = $prix_list[$i]->libelle;
            $rsList[$i]['prix']     =  "";
            $rsList[$i]['prix_id']  =  "";
            $rsList[$i]['relicat']  =  "0";
        }
        $this->view->prix_list = $rsList;

        $this->view->labo_categories    = LaboCategoriesAnalyse::find();
        $this->view->img_categories     = ImgItemsCategories::find();
        $this->view->partial('actes/createActe');
    }

     public function editActeAction($id) {
        $this->view->disable();
        $form = new ActesForm($this->trans);
        $this->view->acte_id = $id;
        $this->view->form_action = 'edit';
        $this->view->acte_id = $id;

        $acte = Actes::findFirst($id);

        if(!$acte){
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
            $acte->libelle = $data['libelle'];
            $acte->code = $data['code'];
            $acte->type = $data['type'];
            $acte->unite = $data['unite'];
            $acte->prix = $data['prix'];
            $acte->services_id = $data['services_id'];

            if(isset($data["type_assurance_id"])){
               /* $this->flash->error(print_r($data));
            return $this->view->partial("layouts/flash");*/

                $i = 0;
                while(isset($data['type_assurance_id'][$i])){
                    if($data['prix_id'][$i] != ""){
                        $prixObj    = PrixActes::findFirst($data['prix_id'][$i]);
                    }
                    else{
                        $prixObj    = new PrixActes();
                    }
                    $prixObj->prix              = ($data['prix2'][$i]>0) ? $data['prix2'][$i] : $data['prix'];
                    $prixObj->actes_id          = $id;
                    $prixObj->type_assurance_id = $data['type_assurance_id'][$i];
                    $prixObj->relicat           = $data['relicat'][$i];

                    if (!$prixObj->save()) {
                        $msg = "Les autres prix n'ont pas été correctement enrégistrés";
                        $this->flash->error($msg);
                        return $this->view->partial("layouts/flash");
                    }
                
                    $i++;
                }
            }
            
            if (!$acte->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Prestation modifiée avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $acte->libelle,
                    "code" => $acte->code,
                    "type" => $acte->type,
                    "unite" => $acte->unite,
                    "prix" => $acte->prix,
                    "services_id" => $acte->services_id
            ));
            $prix_list = TypeAssurance::find();
            $rsList = [];
            for($i = 0; $i < count($prix_list); $i++) {
                $rsList[$i]['id']       = $prix_list[$i]->id;
                $rsList[$i]['libelle']  = $prix_list[$i]->libelle;
                $tmp                    = PrixActes::findFirst("type_assurance_id = " . $prix_list[$i]->id . " AND actes_id = " . $id );
                $rsList[$i]['prix']     =  ($tmp) ? $tmp->prix : "";
                $rsList[$i]['prix_id']  =  ($tmp) ? $tmp->id : "";
                $rsList[$i]['relicat']  =  ($tmp) ? $tmp->relicat : "0";
            }
            $this->view->prix_list = $rsList;

            $this->view->labo_categories    = LaboCategoriesAnalyse::find();
            $this->view->img_categories     = ImgItemsCategories::find();
            $this->view->partial("actes/createActe");
        }
    }

    public function deleteActeAction($id) {
        $this->view->disable();

        $acte = Actes::findFirst($id);
        if(!$acte){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$acte->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
