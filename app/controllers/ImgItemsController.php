<?php

/**
 * ImgItemsController
 *
 */
class ImgItemsController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Actes d'imagerie"]);
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
        $imgItems = ImgItems::find();

        $rs = [];
        for($i = 0; $i < count($imgItems); $i++) {
            $rs[$i]['id']           = $imgItems[$i]->id;
            $rs[$i]['libelle']      = $imgItems[$i]->libelle;
            $rs[$i]['code']      = $imgItems[$i]->code;
            $rs[$i]['img_items_categories']       = $imgItems[$i]->getImgItemsCategories()->libelle ;
        }
        $this->view->imgItems   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createImgItemsAction() {
        $this->view->disable();
        $form = new ImgItemsForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $imgItems = new ImgItems();
            $imgItems->libelle = $data['libelle'];
            $imgItems->code = $data['code'];
            $imgItems->img_items_categories_id = $data['img_items_categories_id'];

            if (!$imgItems->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Acte créé avec succès']);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('img_items/createImgItems');
    }

    public function editImgItemsAction($id) {
        $this->view->disable();
        $form = new ImgItemsForm($this->trans);
        $this->view->imgItems_id = $id;
        $this->view->form_action = 'edit';
        $imgItems = ImgItems::findFirst($id);
        if(!$imgItems){
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
            $imgItems->libelle = $data["libelle"];
            $imgItems->code = $data["code"];
            $imgItems->img_items_categories_id = $data['img_items_categories_id'];
            if (!$imgItems->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Acte modifié avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $imgItems->libelle,
                    "code" => $imgItems->code,
                    "img_items_categories_id" => $imgItems->img_items_categories_id,
            ));
            $this->view->partial("img_items/createImgItems");
        }
    }

    public function deleteImgItemsAction($id) {
        $this->view->disable();

        $imgItems = ImgItems::findFirst($id);
        if(!$imgItems){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$imgItems->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
