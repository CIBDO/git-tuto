<?php

require APP_PATH . "vendor/swiftmailer/swiftmailer/lib/swift_required.php";


class AuthenController extends ControllerBase {

    public function initialize() {

        parent::initialize();
        $this->tag->setTitle($this->trans['Authentication']);
        $this->view->setTemplateAfter('login');
    }

    public function indexAction($param = "") {
        // if param isset and not empty get the User Agent
        /*if ($param) {
            $usrAgent = $this->request->getUserAgent();
            // if the User Agent is Android or iPhone set Session SmartControlToken
            if (stripos($usrAgent, "Android")) {
                $this->session->set("SmartControlToken", $param);
            }
            if (stripos($usrAgent, "iPhone")) {
                $this->session->set("SmartControlToken", $param);
            }
            // todo : same check for other divices
        }*/

    }

    /**
     * Register an authenticated user into session data
     *
     * @param Users $user
     */
    private function _registerSession(User $user)
    {
        $this->session->set('usr', array(
            'id' => $user->id,
            'email' => $user->email,
            'login' => $user->login,
            'nom' => $user->nom,
            'prenom' => $user->prenom,
            'profile' => $user->profile,
            'permissions' => $user->permissions,
            'forms_assoc' => $user->forms_assoc
        ));
    }

    /*
     * Get fields from form Authent to login
     *
     * @return a session infosUsr with email, name, etc...
     * @return a session usr with credentials provided by OAuth 2.0
     */

    public function loginAction() {
        if ($this->request->isPost()) {
            $language = $this->setLanguage();
            $this->view->language = $language;
            // Get email and password fields from form
            $login = $this->request->getPost("login");
            $password = $this->request->getPost("password");
           
            $user = User::findFirst(array(
                "login = :login: AND password = :password: ",
                'bind' => array('login' => $login, 'password' => $password)
            ));
            if ($user != false) {
                $this->_registerSession($user);
                $this->flash->success('Bienvenue ' . $user->nom);
                return $this->response->redirect('index/index');
            }
        }

        $this->flash->error($this->trans['error_auth']);
        return $this->forward('authen/index');

    }

    /*
     * Get all session and destroy them to logout
     */

    public function logoutAction() {
       $this->session->destroy();

       $this->response->redirect("authen");
    }

    

}
