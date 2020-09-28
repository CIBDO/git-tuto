<?php

/**
 * FormulairesController
 *
 */
class FormulairesAscController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
        $this->tag->appendTitle($this->trans['Gestion des formulaires']);
        if ($this->view->language == "fr") {
            $langue = "fr-FR";
        } else {
            $langue = "en-US";
        }
        $this->view->langue = $langue;
    }

    public function indexAction($id = "")
    {


//        OnaApi::$USERPWD='diaroumba:123456';
        $forms = OnaApi::get(['type' => 'data']);
        if (!$forms) {
            $forms = [];
        }
        $this->view->currentForms_id = $id;
        $this->view->forms = $forms;

        if ($id > 0) {
            $currentFormData = OnaApi::get(['type' => 'forms', 'param' => "/$id"]);
            if ($currentFormData) {
                try {
                    OnaApi::fileMaker($id);
                    $this->view->currentForms = $currentFormData;
                } catch (Exception $e) {

                }
            }
        }
    }

    public function createFormulairesAction()
    {
        $this->view->disable();
        $this->view->form_action = 'create';

        if ($this->request->isPost()) {
            if ($this->request->hasFiles()) {
                $files = $this->request->getUploadedFiles();
                $Onaresponse = OnaApi::post(['type' => 'forms', 'file' => $files[0]]);
                if ($Onaresponse['response']) {
                    $this->flash->success("Fichier envoyer");
                    $this->response->redirect("formulaires_asc");
                    return;
                }
                $this->flash->error('Error : ' . $Onaresponse['error']);
                $this->response->redirect("formulaires_asc");
                exit();
            }
        } else {

            $this->view->partial("formulaires_asc/createFormulaires");
        }
    }

    public function editFormulairesAction($id)
    {
        $this->view->disable();
        $this->view->form_action = 'edit';
        $this->view->forms_id = $id;
        if ($this->request->isPost()) {
            if ($this->request->hasFiles()) {
                $files = $this->request->getUploadedFiles();
                $Onaresponse = OnaApi::post(['type' => 'forms', 'file' => $files[0], 'param' => "/$id/import"]);
                if ($Onaresponse['response']) {
                    $this->flash->success("Fichier mis à jour");
                    $this->response->redirect("formulaires_asc");
                    return;
                }
                $this->flash->error('Error : ' . $Onaresponse['error']);
                $this->response->redirect("formulaires_asc");
                exit();
            }
        } else {

            $this->view->partial("formulaires_asc/createFormulaires");
        }

    }

    public function elementsAction($id)
    {

        if ($this->request->isPost()) {
            $data = $this->request->getPost();

        }
    }

    public function deleteFormulairesAction($id)
    {
        $this->view->disable();

        if ($this->request->isAjax()) {
            $Onaresponse = OnaApi::delete(['type' => 'forms', 'param' => "/$id"]);
            if (!$Onaresponse) {
                echo 0;
                exit();
            }

            echo 1;
            exit();
        }
        echo 0;
        exit();
    }

    public function deleteItemAction($id)
    {
        $this->view->disable();

    }

    private function no_special_character($chaine)
    {
        //  les accents
        $chaine = trim($chaine);
        $chaine = strtr($chaine, "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ", "aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn");
        //  les caracètres spéciaux (aures que lettres et chiffres en fait)
        $chaine = preg_replace('/([^.a-z0-9]+)/i', '_', $chaine);
        $chaine = trim($chaine, '_');
        $chaine = strtolower($chaine);
        return $chaine;
    }
    public function updatecsvAction($file){
        $this->view->disable();

        if($file === "csv_suivi"){
            if (!(OnaApi::updateSuiviCsv()['error'])){
                $this->flash->success("le Fichier csv_suivi.csv a été mis à jour");
                $this->response->redirect("formulaires_asc/index/543955");
            }else{
                $this->flash->error("le Fichier csv_suivi.csv n'a pas pu être mis à jour");
                $this->response->redirect("formulaires_asc/index/543955");
            }
        }

        if($file === "liste_asc"){
            if (!(OnaApi::updateAscCsv()['error'])){
                $this->flash->success("le Fichier liste_asc.csv a été mis à jour");
                $this->response->redirect("formulaires_asc/index/543955");
            }else{
                $this->flash->error("le Fichier liste_asc.csv n'a pas pu être mis à jour");
                $this->response->redirect("formulaires_asc/index/543955");
            }
        }

        if($file === "liste_patients"){
            if (!(OnaApi::updateSuiviCsv()['error'])){
                $this->flash->success("le Fichier liste_patients.csv a été mis à jour");
                $this->response->redirect("formulaires_asc/index/543955");
            }else{
                $this->flash->error("le Fichier liste_patients.csv n'a pas pu être mis à jour");
                $this->response->redirect("formulaires_asc/index/543955");
            }
        }



    }

}
