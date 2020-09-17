<?php

class SupportController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('Support');
        parent::initialize();
    }

    public function indexAction() {
        $api = $this->di->get('api');
        $faq = $api->getFaq();
        $this->view->setVar("faq", $faq);
    }

    public function sendQuestionAction() {
        if($this->request->isPost()) {
            $sendbox = $this->request->getPost('sendbox');
            if(isset($sendbox) && trim($sendbox)) {
                $config = $this->di->get('config');
                $api = $this->di->get('api');
                $usr = $this->session->get('infosUsr')[0];
                $api->sendMail(array(
                "receiver" => $config->adminEmail['email'],
                "template" => "sendQuestion", // A changer
                "subject" => "SmartMerchant - Support | Questions au support",
                "data" => array(
                    "name" => $usr->name." ".$usr->surname,
                    "email" => $usr->login,
                    "message" => $sendbox
                    )
                ));
                $this->flash->success($this->trans['message sent']);
            } else {
                $this->flash->error($this->trans['Please enter a message']);
            }
            $this->response->redirect('support/index');
        }
    }
}
?>
