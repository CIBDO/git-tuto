<?php

/**
 * LaboAnalysesController
 *
 */
class LaboAnalysesController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Analyses"]);
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
        $laboAnalyses = LaboAnalyses::find();

        $rs = [];
        for($i = 0; $i < count($laboAnalyses); $i++) {
            $rs[$i]['id']               = $laboAnalyses[$i]->id;
            $rs[$i]['code']             = $laboAnalyses[$i]->code;
            $rs[$i]['libelle']          = $laboAnalyses[$i]->libelle;
            $rs[$i]['labo_categories']  = ($laboAnalyses[$i]->labo_categories_analyse_id > 0) ? 
                                                $laboAnalyses[$i]->getLaboCategoriesAnalyse()->libelle : "-";
        }
        $this->view->laboAnalyses   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function formAction($id = "") {
        $this->view->form_action = 'create';
        $this->view->labo_categories_analyse = LaboCategoriesAnalyse::find();
        if($id != ""){
            $laboAnalyses = LaboAnalyses::findFirst($id);
            $this->view->analyse_id = $id;
            if(!$laboAnalyses){
                $this->flash->error("Aucune analyse trouver");
                return $this->response->redirect("labo_analyses/index");
            }
            $children = array();
            if( !empty($laboAnalyses->childs_id) ){
                $laboAnalysesChilds = LaboAnalyses::find(array(" id IN (". $laboAnalyses->childs_id .")"));
                foreach ($laboAnalysesChilds as $k => $v) {
                    $children[$k]['id'] = $v->id;
                    $children[$k]['libelle'] = $v->libelle;
                    $children[$k]['code'] = $v->code;
                    $children[$k]['type_valeur'] = $v->type_valeur;
                    $children[$k]['valeur_possible'] = $v->valeur_possible;
                    $children[$k]['unite'] = $v->unite;
                    $children[$k]['norme'] = $v->norme;
                }
                $this->view->children   = $children;
            }

            Phalcon\Tag::setDefaults(array(
                    "id" => $laboAnalyses->id,
                    "libelle" => $laboAnalyses->libelle,
                    "has_antibiogramme" => ($laboAnalyses->has_antibiogramme == 1 ) ? "Y" : null,
                    "code" => $laboAnalyses->code,
                    "type_valeur" => $laboAnalyses->type_valeur,
                    "valeur_possible" => $laboAnalyses->valeur_possible,
                    "unite" => $laboAnalyses->unite,
                    "norme" => $laboAnalyses->norme,
                    "parent" => ( !empty($laboAnalyses->childs_id) ) ? "Y" : "",
                    "labo_categories_analyse_id" => $laboAnalyses->labo_categories_analyse_id,
            ));
        }
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            //var_dump($data);exit();
            if ($data["libelle"] == "" || $data["code"] == "" || $data["labo_categories_analyse_id"] == "" ) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->response->redirect("labo_analyses/form");
            }else{
                if($data['id'] > 0){
                    $laboAnalyses = LaboAnalyses::findFirst($data['id']);
                }else{
                    $laboAnalyses = new LaboAnalyses();
                }
                $laboAnalyses->libelle = (isset($data['libelle'])) ? $data['libelle'] : null;
                $laboAnalyses->code = (isset($data['code'])) ? $data['code'] : null;
                $laboAnalyses->type_valeur = (isset($data['type_valeur'])) ? $data['type_valeur'] : null;
                $laboAnalyses->valeur_possible = (isset($data['valeur_possible'])) ? $data['valeur_possible'] : null;
                $laboAnalyses->unite = (isset($data['unite'])) ? $data['unite'] : null;
                $laboAnalyses->norme = (isset($data['norme'])) ? $data['norme'] : null;
                $laboAnalyses->labo_categories_analyse_id = $data['labo_categories_analyse_id'];

                if( isset($data['parent']) && $data['parent'] == "Y" ){
                    $laboAnalyses->childs_id = ( isset($data["childs_id"]) ) ? implode(",", $data["childs_id"]) : "";
                }
                else{
                    $laboAnalyses->childs_id = "";
                }
                if(isset($data['has_antibiogramme']) && $data['has_antibiogramme'] == "Y"){
                    $laboAnalyses->has_antibiogramme = 1;
                }
                if (!$laboAnalyses->save()) {
                    $msg = $this->trans['on_error'];
                    $this->flash->error($msg);
                }else{
                    $this->flash->success($this->trans['Analyse créée avec succès']);
                    return $this->response->redirect("labo_analyses/index");
                }
            }
        }
    }

    public function addChildAction($analyseid = "") {
        $this->view->disable();
        if($analyseid != ""){
            $laboAnalyses = LaboAnalyses::find(array(" id = " . $analyseid));
            $currentChilds = array();
            if( !empty($laboAnalyses[0]->childs_id) ){
                $laboAnalysesChilds = LaboAnalyses::find(array(" id IN (". $laboAnalyses[0]->childs_id .")"));
                foreach ($laboAnalysesChilds as $v) {
                    $currentChilds[] = $v->id;
                }
            }
            Phalcon\Tag::setDefaults(array(
                    "childs[]" => $currentChilds
            ));
        }

        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $children = array();
            if( isset($data["childs"]) ){
                $laboAnalyses = LaboAnalyses::find(array(" id IN (". implode(",", $data["childs"]) .")"));
                foreach ($laboAnalyses as $k => $v) {
                    $children[$k]['id'] = $v->id;
                    $children[$k]['libelle'] = $v->libelle;
                    $children[$k]['code'] = $v->code;
                    $children[$k]['type_valeur'] = $v->type_valeur;
                    $children[$k]['valeur_possible'] = $v->valeur_possible;
                    $children[$k]['unite'] = $v->unite;
                    $children[$k]['norme'] = $v->norme;
                }
                $this->view->children   = $children;
                return $this->view->partial('labo_analyses/addChildResult');
            }
        }

        $childs = LaboAnalyses::find();
        $rs = [];
        foreach ($childs as $child) {
            if( ($analyseid != "") && ($child->id == $analyseid) ){
                continue;
            }
            $rs[$child->id] = $child->code . "-" . $child->libelle;
        }
        $this->view->childs = $rs;
        $this->view->partial('labo_analyses/addChild');
    }


    public function deleteLaboAnalysesAction($id) {
        $this->view->disable();

        $laboAnalyses = LaboAnalyses::findFirst($id);
        if(!$laboAnalyses){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$laboAnalyses->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
