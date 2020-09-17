<?php

/**
 * UserController
 *
 */
class UserController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans['User']);
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
        $users = User::find();

        $rs = [];
        for($i = 0; $i < count($users); $i++) {
            $rs[$i]['id'] = $users[$i]->id;
            $rs[$i]['nom'] = ucfirst($users[$i]->nom);
            $rs[$i]['prenom'] = strtoupper($users[$i]->prenom);
            $rs[$i]['email'] = $users[$i]->email;
            $rs[$i]['telephone'] = $users[$i]->telephone;
            $rs[$i]['service_name'] = $users[$i]->getServices()->libelle;;
            $rs[$i]['profile'] = $users[$i]->profile;
        }

        $this->view->users   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createUserAction() {
        $this->view->disable();
        $form = new UserForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';

        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $user = new User();
            $user->nom = ucfirst($data['nom']);
            $user->prenom = strtoupper($data['prenom']);
            $user->telephone = $data['telephone'];
            $user->email = $data['email'];
            $user->profile = $data['profile'];
            $user->services_id = $data['services_id'];
            $user->forms_assoc = ( isset($data["forms_assoc"]) && count($data['forms_assoc']) > 0 ) ? implode(",", $data['forms_assoc']) : "";
            if(isset($data['prestataire']) && $data['prestataire'] == "1"){
                $user->prestataire = 1;
            }
            else{
                $user->prestataire = 0;
            }
            $user->login = $data['login'];
            if(!empty($data['login']) && empty($data['password'])){
                $this->flash->error("En mode création, un utilisateur avec un identifiant doit obligatoirement avoir un mot de passe par défaut. L'utilisateur pourra changer son mot de passe après sa connexion.");
                return $this->view->partial("layouts/flash");
            }
            if(!empty($data['login']) && !empty($data['password'])){
                //$user->password = sha1($data['password']);
                $user->password = $data['password'];
            }
            $user->permissions = (count($data["permission"])>0) ? json_encode($data["permission"]) : "";

            if (!$user->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Compte créé avec succès']);
            return $this->view->partial("layouts/flash");
        }

        $forms_assoc = Forms::find( array('conditions' => "type = 'base'") );
        $rs = [];
        foreach ($forms_assoc as $form) {
            $rs[$form->id] = $form->libelle;
        }
        $this->view->forms_assoc = $rs;
        $this->view->partial('user/createUser');
    }

     public function editUserAction($id) {
        $this->view->disable();
        $form = new UserForm($this->trans);
        $this->view->user_id = $id;
        $this->view->form_action = 'edit';
        $this->view->user_id = $id;

        $user = User::findFirst($id);

        if(!$user){
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
            $user->nom = ucfirst($data['nom']);
            $user->prenom = strtoupper($data['prenom']);
            $user->telephone = $data['telephone'];
            $user->email = $data['email'];
            $user->profile = $data['profile'];
            $user->services_id = $data['services_id'];
            $user->forms_assoc = ( isset($data["forms_assoc"]) && count($data['forms_assoc']) > 0 ) ? implode(",", $data['forms_assoc']) : "";
            if(isset($data['prestataire']) && $data['prestataire'] == "1"){
                $user->prestataire = 1;
            }
            else{
                $user->prestataire = 0;
            }
            $user->login = $data['login'];
            if(!empty($data['password'])){
                $user->password = sha1($data['password']);
            }
            $user->password = (!empty($data['login']) && !empty($data['password'])) ? sha1($data['password']) : $user->password;
            $user->permissions = ( isset($data["permission"]) && (count($data["permission"]) > 0) ) ? json_encode($data["permission"]) : "";
            
            if (!$user->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Compte modifié avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "nom" => $user->nom,
                    "prenom" => $user->prenom,
                    "telephone" => $user->telephone,
                    "email" => $user->email,
                    "profile" => $user->profile,
                    "services_id" => $user->services_id,
                    "prestataire" => $user->prestataire,
                    "login" => $user->login,
                    "permission_json" => $user->permissions,
                    "forms_assoc[]" => explode(",", $user->forms_assoc)
            ));
            $forms_assoc = Forms::find( array('conditions' => "type = 'base'") );
            $rs = [];
            foreach ($forms_assoc as $form) {
                $rs[$form->id] = $form->libelle;
            }
            $this->view->forms_assoc = $rs;
            $this->view->partial("user/createUser");
        }
    }

    public function deleteUserAction($id) {
        $this->view->disable();

        $user = User::findFirst($id);
        if(!$user){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$user->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
