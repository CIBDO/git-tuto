<?php

class UseraccountsController extends ControllerBase {

    public function initalize() {
        $this->tag->setTitle('Useraccounts');
        $this->assets->addJs('js/pages/useraccounts.js');
    }

    public function indexAction() {
        $this->assets->addJs('js/pages/useraccounts.js');
        
    }

    public function usersListAction() {

        $this->view->disable();
        $api = $this->di->get('api');
        $res = $api->getUsers();
        if(isset($res->code)){
            $language = $this->setLanguage();
            $trans = $this->getTranslation($language);
            $this->flash->error($trans['on_error']);
        }else{
            $this->view->setVar("usersList", $res);
            $this->view->partial("useraccounts/userslist");
        }
    }

    public function createAction() {
        $language = $this->setLanguage();
        $trans = $this->getTranslation($language);
        $this->view->disable();

        if ($this->request->isPost()) {
            if ($this->request->getPost('email') && $this->request->getPost('firstname') && $this->request->getPost('lastname') && $this->request->getPost('shops')) {
                if (!empty($this->request->getPost('email')) && !empty($this->request->getPost('firstname')) && !empty($this->request->getPost('lastname'))) {

                    $email = $this->request->getPost('email', 'email');
                    $name = $this->request->getPost('firstname', 'string');
                    $lastname = $this->request->getPost('lastname', 'string');
                    $shops = $this->request->getPost('shops');
                    $smartcontroll = 0;

                    if ($this->request->getPost('smartcontrol')) {
                        $smartcontroll = 1;
                    }
                    $api = $this->di->get('api');
                    if (!$api->loginexist($email)) {
                        $params = [
                            "login" => $email,
                            "name" => $name,
                            "surname" => $lastname,
                            "hasSmartControl" => $smartcontroll,
                            "password" => "pass",
                            "shops" => $shops];
                        $res = $api->addUser($params);
                        $this->flash->success($trans['user_created']);
                        if(!$this->createUserEmail($res)){
                            $this->flash->error($trans['email_error']);
                        }
                    }
                    else
                    {
                        $this->flash->error($trans['user_already_exist']);
                    }
                } else {
                    $this->flash->error($trans['fill_fields']);
                }
            } else {
                $this->flash->error($trans['fill_fields']);
            }
            $this->view->partial("useraccounts/create");
            //$this->response->redirect('useraccounts');
        }
    }

    public function deleteAction($id) {
        if (!preg_match("/^\d+$/", $id)) {

            $this->response->redirect('useraccounts');
            $this->view->disable();
            return false;
        }
        $language = $this->setLanguage();
        $trans = $this->getTranslation($language);
        $api = $this->di->get('api');
        $response = $api->deleteUser($id);
        if($response->getStatusCode()== "204") {
            $this->flash->success($trans['user_deleted']);
        } else {
            $this->flash->error($trans['on_error']);
        }
    }


    private function createUserEmail($usrInfos){
        $api = $this->di->get('api');
        //$usrInfos = $api->loginexist($email);
        if($usrInfos) {
            // Token generation for password creation
            $idUsr = $usrInfos->id;
            $name = $usrInfos->name . ' ' . $usrInfos->surname;
            $date = date('Y-m-d H:i:s');
            $expire = date("Y-m-d H:m:s", strtotime('+24 hours', time()));
            $token = md5(uniqid(rand(), true));
            $params = array('token' =>$token,
                'created' => $date,
                'expires' => $expire,
                'users_id' => $idUsr
            );

            $api->newToken($params);

            $token_url = $this->di->get('url')->getBaseUri().'authen/resetpwd/'.$token;
            $sendmail = $api->sendMail(array(
            "receiver" => $usrInfos->login,
            "template" => "initPwd",
            "subject" => "ShopControl | Déclarer votre mot de passe",
            "data" => array(
                "name" => $name,
                "token_url" => $token_url,
                "email" => $usrInfos->login
                )
            ));
            return $sendmail;
        }
    }

    public function modalAction($id) {
        $this->view->disable();
        $api = $this->di->get('api');
        $res = $api->getUsr($id);
        if(isset($res->code)){
            $language = $this->setLanguage();
            $trans = $this->getTranslation($language);
            $this->flash->error($trans['on_error']);
        }else{
            $shops = $api->getUserShops($res[0]->id);
            $shops = is_array($shops) ? $shops : [$shops];
            $this->view->setVar("usrInfos", $res[0]);
            $this->view->setVar("merchantShops", $this->session->get("merchantShops"));
            $this->view->setVar("userShops", $shops);
            Phalcon\Tag::setDefaults(array("email" => $res[0]->login, "firstname" => $res[0]->name, "lastname" => $res[0]->surname));
            if($res[0]->hasSmartControl == 1){
                Phalcon\Tag::setDefault("smartcontrol", "checked");
            }
        }

        $this->view->partial("useraccounts/modal");
    }


    public function updateUserAction($id) {
        $language = $this->setLanguage();
        $trans = $this->getTranslation($language);
        $this->view->disable();
        $api = $this->di->get('api');
        $data = [];
        if ($this->request->isPost()) {
            if ($this->request->getPost('email') && $this->request->getPost('firstname') && $this->request->getPost('lastname') && $this->request->getPost('shops')
                && trim($this->request->getPost('email')) && trim($this->request->getPost('firstname')) && trim($this->request->getPost('lastname'))) {

                $email = $this->request->getPost('email', 'email');
                $name = $this->request->getPost('firstname', 'string');
                $lastname = $this->request->getPost('lastname', 'string');
                $shops = $this->request->getPost('shops');
                $smartcontroll = 0;
                if ($this->request->getPost('smartcontrol') == 'checked' || $this->request->getPost('smartcontrol') == 'on') {
                    $smartcontroll = 1;
                }

                $data = [
                    "login" => $email,
                    "name" => $name,
                    "surname" => $lastname,
                    "hasSmartControl" => $smartcontroll,
                    "shops" => $shops
                ];
                $api->patchUser($id, $data);
                $this->flash->success($trans['user_update']);
                $this->response->redirect('useraccounts');
            } else {
                $this->flash->error($trans['fill_fields']);
            }
//            $this->view->partial("useraccounts/updateUser");
            $this->response->redirect('useraccounts');
        }
    }
}
