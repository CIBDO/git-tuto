<?php

class AscController extends ControllerBase
{

    public function indexAction($id = null)
    {

        $ascs = Asc::find();

        $rs = [];
        for ($i = 0, $iMax = count($ascs); $i < $iMax; $i++) {
            $rs[$i]['id'] = $ascs[$i]->id;
            $rs[$i]['code_asc'] = $ascs[$i]->code_asc;
            $rs[$i]['nom'] = $ascs[$i]->nom;
            $rs[$i]['prenom'] = $ascs[$i]->prenom;
            $rs[$i]['telephone'] = $ascs[$i]->telephone;
            $rs[$i]['profession'] = $ascs[$i]->profession;
            $rs[$i]['residence'] = ($ascs[$i]->residence_id != null) ? $ascs[$i]->getResidence()->libelle : "";
        }

        $this->view->residences = Residence::find();
        $this->view->ascs = json_encode($rs, JSON_PRETTY_PRINT);

    }

    public function formAction($id = 0)
    {
        $form = new AscForm($this->trans);
        $this->view->form = $form;

        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return;
            }

            if ($data['id'] > 0) {
                $asc = Asc::findFirst($data['id']);
                if (!$asc) {
                    $msg = $this->trans['on_error'];
                    $this->flash->error($msg);
                    return;
                }
                Phalcon\Tag::setDefaults(array(
                    "id" => $asc->id,
                    "code_asc" => $asc->code_asc,
                    "nom" => $asc->nom,
                    "prenom" => $asc->prenom,
                    "residence_id" => $asc->residence_id,
                    "profession" => $asc->profession,
                    "telephone" => $asc->telephone,
                ));
            } else {
                $asc = new Asc();
            }

            $asc->nom = strtoupper($data['nom']);
            $asc->code_asc = $data['code_asc'];
            $asc->prenom = $data['prenom'];
            if ($data['residence_id'] != "") {
                $asc->residence_id = $data['residence_id'];
            }
            $asc->profession = $data['profession'];
            $asc->telephone = $data['telephone'];

            if (!$asc->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return;
            }
            OnaApi::updateAscCsv();
            $this->flash->success($this->trans['Enregistrement effectué avec succès']);
            $this->response->redirect("asc/");
        } else {
            if ($id > 0) {
                $asc = Asc::findFirst($id);
                if (!$asc) {
                    $msg = $this->trans['on_error'];
                    $this->flash->error($msg);
                    return;
                }
                Phalcon\Tag::setDefaults(array(
                    "id" => $asc->id,
                    "code_asc" => $asc->code_asc,
                    "nom" => $asc->nom,
                    "prenom" => $asc->prenom,
                    "residence_id" => $asc->residence_id,
                    "profession" => $asc->profession,
                    "telephone" => $asc->telephone,
                ));
            }
        }
    }

    public function deleteAscAction($id)
    {
        $this->view->disable();

        $formsElements = Asc::findFirst($id);
        if (!$formsElements) {
            echo 0;
            exit();
        }

        if ($this->request->isAjax()) {
            if (!$formsElements->delete()) {
                echo 0;
                exit();
            }
            OnaApi::updateAscCsv();
            echo 1;
            exit();
        }
        echo 0;
        exit();
    }


}

