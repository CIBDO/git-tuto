<?php

class AccountController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
        $usrSession = $this->session->get("usr");
        Phalcon\Tag::setDefaults(array(
                                    "firstname" => $usrSession['prenom'],
                                    "lastname" => $usrSession['nom']));

    }

    public function indexAction()
    {
        $this->assets->addJs('js/pages/account.js');

    }

    /**
     * Action performed when submiting the password change form
     * @return boolean [[Description]]
     */
    public function editPasswordAction() {

        $usrSession = $this->session->get("usr");
        // check if one of inputs is send in post data
        if (!$this->request->getPost('password') || !$this->request->getPost('newPassword')) {
            $this->flash->error($this->trans['Veuillez renseigner les champs obligatoires']);
            $this->response->redirect('account');
            return;
        }

        $data = [];
        $data['password'] = $this->request->getPost('password');
        $data['newPassword'] = $this->request->getPost('newPassword');

        $user = User::findFirst(array(
            "id = :id:",
            'bind' => array('id' => $usrSession['id'])
        ));
        if ($user != false) {
            if($user->password != sha1($data['password'])){
                $this->flash->error($this->trans['Le mot de passe actuel est incorrecte']);
                $this->response->redirect('account');
                return;
            }
            $user->password = sha1($data['newPassword']);
            if($user->save()){
                $this->flash->success($this->trans['Votre mot de passe a été modifié avec succès']);
                return $this->response->redirect('account');
            }
        }
        $this->response->redirect('account');
    }

    public function editInfosAction(){

        $usrSession = $this->session->get("usr");
        // check if one of inputs is send in post data
        if (!$this->request->getPost('firstname') || !$this->request->getPost('lastname')) {
            $this->flash->error($this->trans['fill_fields']);
            $this->response->redirect('account');
            return;
        }

        $data = [];
        $data['name'] = $this->request->getPost('firstname');
        $data['surname'] = $this->request->getPost('lastname');

        $user = User::findFirst(array(
            "id = :id:",
            'bind' => array('id' => $usrSession['id'])
        ));
        if ($user != false) {
            $user->prenom = $data['name'];
            $user->nom = $data['surname'];
            if($user->save()){
                $usrSession['prenom'] = $user->prenom;
                $usrSession['nom'] = $user->nom;
                $this->session->set('usr', $usrSession);
                $this->flash->success($this->trans['userinfos_updated']);
                return $this->response->redirect('account');
            }
        }

        $this->response->redirect('account');
    }

    /**
     * Displays the reset password form when the user password is about to expire
     * @author Mory Bamba
     */
    public function expiryPasswordAction() {
        if ($this->session->get("pwdAboutToExpire")) {
            $this->view->disable();
            $this->view->partial('account/expirypassword');
        } else {
            $this->response->redirect("");
        }
    }
}
