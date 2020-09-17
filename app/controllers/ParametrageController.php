<?php

class ParametrageController extends ControllerBase
{

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans['Parametrage du systeme']);
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

        if($this->request->isPost()){
            $data = $this->request->getPost();
            
            $parametrage = Parametrage::find();
            if(count($parametrage) > 0){
                $parametrage = $parametrage[0];
            }
            else{
                $parametrage = new Parametrage();
            }

            $parametrage->nom                   = (isset($data["nom"])) ? $data["nom"] : "";
            $parametrage->adresse               = (isset($data["adresse"])) ? $data["adresse"] : "";
            $parametrage->telephone             = (isset($data["telephone"])) ? $data["telephone"] : "";
            $parametrage->pharmacie_type        = (isset($data["pharmacie_type"])) ? $data["pharmacie_type"] : "";
            $parametrage->default_lot           = (isset($data["default_lot"])) ? $data["default_lot"] : "";
            $parametrage->default_peremption    = (isset($data["default_peremption"])) ? $data["default_peremption"] : "";
            $parametrage->default_coef          = (isset($data["default_coef"])) ? $data["default_coef"] : "";
            $parametrage->default_constante     = (isset($data["default_constante"])) ? $data["default_constante"] : "";
            $parametrage->default_examen        = (isset($data["default_examen"])) ? $data["default_examen"] : "";
            $parametrage->diagnostic_source     = (isset($data["diagnostic_source"])) ? $data["diagnostic_source"] : "";

            if ($this->request->hasFiles() == true) {
                $file = $this->request->getUploadedFiles()[0];
                if($file->getName() != ""){
                    if(in_array($file->getExtension(), array("png", "PNG", "jpg", "JPG", "JPEG", "jpeg"))){
                        $parametrage->logo        = $file->getName();
                        $file->moveTo('img/structure/' . $file->getName());
                    }
                    else{
                        $this->flash->error('Le logo n' . "'" . 'a pa été enregistré. le format doit etre de type: "png", "PNG", "jpg", "JPG", "JPEG", "jpeg"');
                    }
                }
               
            }

            $parametrage->type_entete   = (isset($data["type_entete"])) ? $data["type_entete"] : "";
            $parametrage->template_logo = (isset($data["template_logo"])) ? $data["template_logo"] : "";
            $parametrage->ligne1        = (isset($data["ligne1"])) ? $data["ligne1"] : "";
            $parametrage->ligne2        = (isset($data["ligne2"])) ? $data["ligne2"] : "";
            $parametrage->ligne3        = (isset($data["ligne3"])) ? $data["ligne3"] : "";
            $parametrage->ligne4        = (isset($data["ligne4"])) ? $data["ligne4"] : "";

            $parametrage->img_msg_annonce   = (isset($data["img_msg_annonce"])) ? $data["img_msg_annonce"] : "";
            $parametrage->img_msg_fin       = (isset($data["img_msg_fin"])) ? $data["img_msg_fin"] : "";


           if (!$parametrage->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Parametres enregistrés avec succès']);
        }

        $parametrage = Parametrage::find();
        if(count($parametrage) > 0){
            $parametrage = $parametrage[0];
        }
        else{
            $parametrage = new Parametrage();
        }

        Phalcon\Tag::setDefaults(array(
                    "nom" => $parametrage->nom,
                    "adresse" => $parametrage->adresse,
                    "telephone" => $parametrage->telephone,
                    "pharmacie_type" => $parametrage->pharmacie_type,
                    "default_lot" => $parametrage->default_lot,
                    "default_peremption" => $parametrage->default_peremption,
                    "default_coef" => $parametrage->default_coef,
                    "default_constante" => $parametrage->default_constante,
                    "default_examen" => $parametrage->default_examen,
                    "diagnostic_source" => $parametrage->diagnostic_source,

                    "type_entete" => $parametrage->type_entete,
                    "template_logo" => $parametrage->template_logo,
                    "ligne1" => $parametrage->ligne1,
                    "ligne2" => $parametrage->ligne2,
                    "ligne3" => $parametrage->ligne3,
                    "ligne4" => $parametrage->ligne4,

                     "img_msg_annonce"  => $parametrage->img_msg_annonce,
                    "img_msg_fin"       => $parametrage->img_msg_fin
            ));

        $this->view->logo = ( file_exists(APP_PATH . 'public/img/structure/' . $parametrage->logo) ) 
                                ? $parametrage->logo : "";
        
    }
}
