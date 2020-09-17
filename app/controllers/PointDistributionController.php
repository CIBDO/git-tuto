<?php

/**
 * PointDistributionController
 *
 */
class PointDistributionController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Point de distribution"]);
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
        $pointDistributions = PointDistribution::find();
        $rs = [];
        for($i = 0; $i < count($pointDistributions); $i++) {
            $rs[$i]['id']       = $pointDistributions[$i]->id;
            $rs[$i]['libelle']  = $pointDistributions[$i]->libelle;
            $rs[$i]['type']  = $pointDistributions[$i]->type;
            $rs[$i]['default']  = $pointDistributions[$i]->default;
        }
        $this->view->pointDistributions   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createPointDistributionAction() {
        $this->view->disable();
        $form = new PointDistributionForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $pointDistribution = new PointDistribution();
            $pointDistribution->libelle = $data['libelle'];
            $pointDistribution->type = $data['type'];
            if(isset($data['default']) && $data['default'] == "Y"){
                $query = $this->modelsManager->createQuery("UPDATE PointDistribution SET default = :d:");
                $exec  = $query->execute( array('d' => 0) );
                $pointDistribution->default = 1;
            }

            if (!$pointDistribution->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            foreach ($data["users"] as $user) {
                if($user != ""){
                    $pointDistributionUser = new PointDistributionUser();
                    $pointDistributionUser->point_distribution_id   = $pointDistribution->id;
                    $pointDistributionUser->user_id                 = $user;
                    $pointDistributionUser->save();
                }
            }
            $this->flash->success($this->trans['Point de distribution créé avec succès']);
            return $this->view->partial("layouts/flash");
        }

        $users = User::find();
        $rs = [];
        foreach ($users as $user) {
            if(in_array("ventemedic_w", json_decode($user->permissions))){
                $rs[$user->id] = $user->prenom . " " . $user->nom;
            }
        }
        $this->view->users = $rs;
        $this->view->partial('point_distribution/createPointDistribution');
    }

    public function editPointDistributionAction($id) {
        $this->view->disable();
        $form = new PointDistributionForm($this->trans);
        $this->view->pointDistribution_id = $id;
        $this->view->form_action = 'edit';
        $pointDistribution = PointDistribution::findFirst($id);
        if(!$pointDistribution){
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
            $pointDistribution->libelle = $data["libelle"];
            $pointDistribution->type = $data['type'];
            if(isset($data['default']) && $data['default'] == "Y"){
                $query = $this->modelsManager->createQuery("UPDATE PointDistribution SET default = :d:");
                $exec  = $query->execute( array('d' => 0) );
                $pointDistribution->default = 1;
            }
            if (!$pointDistribution->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            //On supprime les associations existantes
            $query = $this->modelsManager->createQuery("DELETE FROM  PointDistributionUser WHERE point_distribution_id = :id:");
            $exec  = $query->execute( array('id' => $pointDistribution->id) );
            //On recrée les associations
            foreach ($data["users"] as $user) {
                if($user != ""){
                    $pointDistributionUser = new PointDistributionUser();
                    $pointDistributionUser->point_distribution_id   = $pointDistribution->id;
                    $pointDistributionUser->user_id                 = $user;
                    $pointDistributionUser->save();
                }
            }

            $this->flash->success($this->trans['Point de distribution modifié avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $tmpUsers = $pointDistribution->getPointDistributionUser();
            $currentUsers = [];
            foreach ($tmpUsers as $tmpUser) {
                $currentUsers[] = $tmpUser->user_id;
                //$currentUsers[$tmpUser->user_id] = $tmpUser->getUser()->prenom . " " . $tmpUser->getUser()->nom;
            }
            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $pointDistribution->libelle,
                    "type" => $pointDistribution->type,
                    "users[]" => $currentUsers,
                    "default" => ($pointDistribution->default == 1 ) ? "Y" : null,
            ));

            $users = User::find();
            $rs = [];
            foreach ($users as $user) {
                $rs[$user->id] = $user->prenom . " " . $user->nom;
            }
            $this->view->users = $rs;
            $this->view->partial("point_distribution/createPointDistribution");
        }
    }

    public function deletePointDistributionAction($id) {
        $this->view->disable();

        $pointDistribution = PointDistribution::findFirst($id);
        if(!$pointDistribution){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$pointDistribution->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
