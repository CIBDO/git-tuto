<?php

/**
 * CommandeController
 *
 */
class CommandeController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Commande"]);
        if($this->view->language == "fr"){
            $langue = "fr-FR";
        }
        else{
            $langue = "en-US";
        }
        $this->view->langue = $langue;
    }

    public function indexAction() {       
       if($this->request->get("date1")){
            $data = $this->request->get();

            $conditions = "";
            $bind       = [];

            //cas de de la recherche par date
            if($data['date1'] != "" && $data['date2'] != ""){
                $conditions .= "DATE(date) between :date1: AND :date2:";
                $bind['date1'] = $data['date1'];
                $bind['date2'] = $data['date2'];

                Phalcon\Tag::setDefaults(array(
                    "date1" => $data['date1'],
                    "date2" => $data['date2']
                ));
            }

            foreach ($data as $key => $value) {
                if($key == "date1" || $key == "date2" || $key == "_url")
                    continue;
                if($value != ""):
                    $conditions .= ($conditions == "") ? $key . " =  :" . $key . ":" : " AND " . $key . " =  :" . $key . ":";
                    $bind[$key] = $value;
                endif;
            }
            $commandes = Commande::find( array($conditions, "order" => "date desc", "bind" => $bind) );
        }
        else{
           $commandes = Commande::find(array("order" => "date desc"));
        }

        $rs = [];
        for($i = 0; $i < count($commandes); $i++) {
            $rs[$i]['id']           = $commandes[$i]->id;
            $rs[$i]['objet']        = $commandes[$i]->objet;
            $rs[$i]['date']         = date("d/m/Y", strtotime($commandes[$i]->date));
            $rs[$i]['fournisseur']  = $commandes[$i]->getFournisseur()->libelle;
            $rs[$i]['etat']         = $commandes[$i]->etat;
        }

        /*print json_encode($rs, JSON_PRETTY_PRINT);exit();
        var_dump($rs);exit();*/
        $this->view->commandes   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function detailsAjoutAction() {

        if($this->request->isPost()){
            $data = $this->request->getPost();
            if($data['commande_id'] <= 0){
                $this->flash->error("Veuillez reprendre l'opération");
                return $this->response->redirect("commande/index");
            }
            $item               = new CommandeDetails();
            $item->quantite     = $data['quantite'];
            $item->prix         = $data['prix'];
            $item->produit_id   = $data['produit_id'];
            $item->commande_id  = $data['commande_id'];

            if (!$item->save()) {
                $msg = "le produit n'a pas été correctement enregistrés. veuillez contacter l'admin";
                $this->flash->error($msg);
                return $this->response->redirect("commande/index");
            }
            $this->totalCommande($data['commande_id']);
            $this->flash->success("Les produits ont été ajouté à la commande avec succès");
            return $this->response->redirect("commande/details/" . $data['commande_id']);
        }
        
    }

    private function totalCommande($commande_id){

        $commande = Commande::findFirst($commande_id);
        $commande->montant = CommandeDetails::sum(
                                array(
                                    "column"     => "prix * quantite",
                                    "conditions" => "commande_id = " . $commande_id
                                )
                            );
        $commande->save();

    }


    public function detailsAction($id = 0) {       

        if($this->request->isPost()){
            $data = $this->request->getPost();
            if($data['commande_id'] == ""){
                $this->flash->error("Veuillez choisir une commande pour afficher ses details");
                return $this->response->redirect("commande/index");
            }
            $id = $data['commande_id'];

            //On supprime tout d'abord
            $query = $this->modelsManager->createQuery("DELETE FROM CommandeDetails WHERE commande_id = :id:");
            $exec  = $query->execute(
                            array('id' => $data['commande_id'])
                        );

            $i = 0;
            while(isset($data['id'][$i])){
                $item               = new CommandeDetails();
                $item->quantite     = $data['quantite'][$i];
                $item->prix         = $data['prix'][$i];
                $item->produit_id   = $data['produit_id'][$i];
                $item->commande_id  = $data['commande_id'];

                if (!$item->save()) {
                    $msg = "l'item (". $data['id'] .") n'a pas été correctement enregistrées. veuillez contacter l'admin";
                    $this->flash->error($msg);
                }
                $i++;
            }
            $this->totalCommande($data['commande_id']);
            $this->flash->success("Les produits ont été ajouté à la commande avec succès");
            return $this->response->redirect("commande/details/" . $data['commande_id']);
        }

        if($id == 0){
            $this->flash->error("Veuillez choisir une commande pour afficher ses details");
            return $this->response->redirect("commande/index");
        }
        $rsCommande         = array();
        $rsCommandeDetails  = array();

        //la commande
        $commande = Commande::findFirst($id);
        $rsCommande['id']           = $commande->id;
        $rsCommande['objet']        = $commande->objet;
        $rsCommande['date']         = date("d/m/Y", strtotime($commande->date));
        $rsCommande['fournisseur']  = $commande->getFournisseur()->libelle;
        $rsCommande['montant']      = $commande->montant;
        $rsCommande['etat']         = $commande->etat;
        
        //les details de la commande
        $commandeDetails = $commande->getCommandeDetails();
        for($i = 0; $i < count($commandeDetails); $i++) {
            $rsCommandeDetails[$i]['id']    = $commandeDetails[$i]->id;
            $produit                        = $this->getProduitInfos($commandeDetails[$i]->getProduit());

            $rsCommandeDetails[$i]['produit_id']        = $produit['id'];
            $rsCommandeDetails[$i]['produit_libelle']   = $produit['libelle'];
            $rsCommandeDetails[$i]['produit_dosage']    = $produit['dosage'];
            $rsCommandeDetails[$i]['produit_type']      = $produit['type'];
            $rsCommandeDetails[$i]['produit_forme']     = $produit['forme'];
            $rsCommandeDetails[$i]['produit_classe_th'] = $produit['classe_th'];

            $rsCommandeDetails[$i]['quantite']          = $commandeDetails[$i]->quantite;
            $rsCommandeDetails[$i]['quantite_livree']   = $commandeDetails[$i]->quantite_livree;
            $rsCommandeDetails[$i]['prix']              = $commandeDetails[$i]->prix;
        }
        

        $this->view->commande           = $rsCommande;
        $this->view->commandeDetails    = $rsCommandeDetails;        

    }

    public function createCommandeAction($produit_list = "") {
        $form = new CommandeForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $commande = Commande::findFirst(array(
                    'conditions' => 'objet = ?0 ',
                    'bind' => array( 0 => $data['objet'] )));
            if ($commande) {
                $this->flash->error("Une commande existe déjà avec cet objet");
                //return $this->view->partial("layouts/flash");
                return $this->response->redirect("produit/index");
            }

            $commande = new Commande();
            $commande->objet = $data['objet'];
            $commande->date = $data['date'];
            $commande->fournisseur_id = $data['fournisseur_id'];
            $commande->montant = $data['montant'];
            $commande->etat = "creation";
            if(isset($data["produit_list"]) && $data["produit_list"] != ""){
                $tmp = explode(",", $data["produit_list"]);
                $cmdDetail = array();
                foreach ($tmp as $k => $v) {
                    $cmdDetail[$k] = new CommandeDetails();
                    $cmdDetail[$k]->produit_id      = $v;
                    $cmdDetail[$k]->quantite        = 0;
                    $cmdDetail[$k]->quantite_livree = 0;
                    $cmdDetail[$k]->prix            = 0;
                }
                $commande->commandeDetails = $cmdDetail;
            }

            if (!$commande->save()) {
                //var_dump($commande->getMessages());
                $msg = $this->trans['on_error'];
                $this->view->disable();
                $this->flash->error("$msg");
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Commande créée avec succès']);
            return $this->response->redirect("commande/details/" . $commande->id);
        }
        $this->view->disable();
        $this->view->produit_list = $produit_list;
        $this->view->partial('commande/createCommande');
    }

    public function editCommandeAction($id) {
        $this->view->disable();
        $form = new CommandeForm($this->trans);
        $this->view->commande_id = $id;
        $this->view->form_action = 'edit';
        $commande = Commande::findFirst($id);
        if(!$commande){
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
            $commande->objet = $data['objet'];
            $commande->date = $data['date'];
            $commande->fournisseur_id = $data['fournisseur_id'];
            $commande->montant = $data['montant'];

            if (!$commande->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Commande modifiée avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "objet" => $commande->objet,
                    "date" => $commande->date,
                    "fournisseur_id" => $commande->fournisseur_id,
                    "montant" => $commande->montant,
            ));
            $this->view->partial("commande/createCommande");
        }
    }

    public function deleteCommandeAction($id) {
        $this->view->disable();

        $commande = Commande::findFirst($id);
        if(!$commande){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$commande->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }

    public function deleteDetailsItemCommandeAction($id) {
        
        $this->view->disable();

        $commandeDetails = CommandeDetails::findFirst($id);
        $commande = $commandeDetails->getCommande();
        if(!$commandeDetails){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$commandeDetails->delete()) {
               echo 0;exit();
            }
            else{
                $this->totalCommande($commande->id);
                echo 1;exit();
            }
        }
        echo 0;exit();
    }

    public function ajaxProduitAction($commande_id = 0) {
        $this->view->disable();
        
        //Commande
        $builder = $this->modelsManager->createBuilder();
        $commande = $builder->columns('commandeDetails.produit_id')
            ->addfrom('CommandeDetails', 'commandeDetails')
            ->where('commande_id = ' . $commande_id);
        $commande = $builder->getQuery()->execute();

        //Produit
        $builder = $this->modelsManager->createBuilder();
        $produits = $builder->columns('produit.id, produit.libelle, produit.seuil_max, produit.dosage, formeProduit.libelle as forme_libelle')
            ->addfrom('Produit', 'produit')
            ->join('FormeProduit', 'formeProduit.id = produit.forme_produit_id', 'formeProduit', 'INNER');
        if(count($commande)>0){
            foreach ($commande as $value) {
                $pr[] = $value->produit_id;
            }
            $builder->where('produit.id NOT IN(' . implode(",", $pr) . ')');
        }

        $produits = $builder->getQuery()->execute();
        

        $rs = array();
        for($i = 0; $i < count($produits); $i++) {
            $rs[$i]['id'] = json_encode($produits[$i], JSON_PRETTY_PRINT);
            $rs[$i]['libelle'] = $produits[$i]->libelle . "-" . $produits[$i]->forme_libelle . "-" . $produits[$i]->dosage;
        }
        
        echo json_encode($rs, JSON_PRETTY_PRINT);
    }
}
