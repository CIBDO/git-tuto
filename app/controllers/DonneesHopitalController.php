<?php

class DonneesHopitalController extends ControllerBase
{

    public function indexAction()
    {

    }

    public function createDhAction($patient_id)
    {
        $this->view->disable();
        $form = new DonneeHopitalForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {

            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }
            $donnees_hopital = new DonneesHopital();
            $donnees_hopital->commentaire = $data['commentaire'];
            $donnees_hopital->date_rdv = $data['date_rdv'];
            $donnees_hopital->patients_id = $patient_id;
            $donnees_hopital->code_asc = Patients::findFirst($patient_id)->getAsc()->code_asc;
            $donnees_hopital->user_id = $this->session->get('usr')['id'];


            if (!$donnees_hopital->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            OnaApi::updateSuiviCsv();
            $this->flash->success($this->trans['Donnée ajouter avec succès']);
            return $this->response->redirect("patients/dossier/$patient_id");
        }
        Phalcon\Tag::setDefaults(array(
            "etat" => "actif"
        ));
        $this->view->patient_id = $patient_id;
        $this->view->partial('donnees_hopital/createDh');
    }

    public function editDhAction($id) {
        $this->view->disable();
        $form = new DonneeHopitalForm($this->trans);
        $this->view->id = $id;
        $this->view->form_action = 'edit';
        $dh = DonneesHopital::findFirst($id);
        if(!$dh){
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
            $dh->commentaire = $data['commentaire'];
            $dh->date_rdv = $data['date_rdv'];

            if (!$dh->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            OnaApi::updateSuiviCsv();
            $this->flash->success($this->trans['Donnée modifiée avec succès']);
            return $this->response->redirect("patients/dossier/".$dh->patients_id);
        }

        $this->view->form = $form;
        Phalcon\Tag::setDefaults(array(
            "commentaire" => $dh->commentaire,
            "date_rdv" => $dh->date_rdv,
        ));
        $this->view->partial("donnees_hopital/createDh");
    }

    public function deleteAction($id)
    {
        $this->view->disable();

        $formsElements = DonneesHopital::findFirst($id);
        if (!$formsElements) {
            echo 0;
            exit();
        }
        if ($this->request->isAjax()) {
            if (!$formsElements->delete()) {
                echo 0;
                exit();
            }
            OnaApi::updateSuiviCsv();
            echo 1;
            exit();
        }
        echo 0;
        exit();
    }

}

