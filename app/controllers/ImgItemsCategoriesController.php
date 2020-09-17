<?php

/**
 * ImgItemsCategoriesController
 *
 */
class ImgItemsCategoriesController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Catégorie d'actes d'imagerie"]);
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
        $imgItemsCategories = ImgItemsCategories::find();
        $rs = [];
        for($i = 0; $i < count($imgItemsCategories); $i++) {
            $rs[$i]['id']       = $imgItemsCategories[$i]->id;
            $rs[$i]['libelle']  = $imgItemsCategories[$i]->libelle;
        }
        $this->view->imgItemsCategories   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createImgItemsCategoriesAction() {
        $this->view->disable();
        $form = new ImgItemsCategoriesForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $imgItemsCategories = new ImgItemsCategories();
            $imgItemsCategories->libelle = $data['libelle'];

            if (!$imgItemsCategories->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans["Catégorie d'acte créée avec succès"]);
            return $this->view->partial("layouts/flash");
        }
        $this->view->partial('img_items_categories/createImgItemsCategories');
    }

    public function editImgItemsCategoriesAction($id) {
        $this->view->disable();
        $form = new ImgItemsCategoriesForm($this->trans);
        $this->view->imgItemsCategories_id = $id;
        $this->view->form_action = 'edit';
        $imgItemsCategories = ImgItemsCategories::findFirst($id);
        if(!$imgItemsCategories){
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
            $imgItemsCategories->libelle = $data["libelle"];
            if (!$imgItemsCategories->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans["Catégorie d'acte modifiée avec succès"]);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $imgItemsCategories->libelle,
            ));
            $this->view->partial("img_items_categories/createImgItemsCategories");
        }
    }

    public function deleteImgItemsCategoriesAction($id) {
        $this->view->disable();

        $imgItemsCategories = ImgItemsCategories::findFirst($id);
        if(!$imgItemsCategories){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$imgItemsCategories->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }
}
