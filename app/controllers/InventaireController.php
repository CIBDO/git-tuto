<?php


class InventaireController  extends ControllerBase {

    public function indexAction(){

    	if($this->request->get("date1")){
            $data = $this->request->get();

            $conditions = "";
            $bind       = [];

            //cas de la recherche par date
            if($data['date1'] != "" && $data['date2'] != ""){
                $conditions .= "date(date) between :date1: AND :date2:";
                $bind['date1'] = $data['date1'];
                $bind['date2'] = $data['date2'];
            }

            foreach ($data as $key => $value) {
                if($key == "date1" || $key == "date2" || $key == "_url")
                    continue;
                if($value != ""):
                    $conditions .= ($conditions == "") ? $key . " =  :" . $key . ":" : " AND " . $key . " =  :" . $key . ":";
                    $bind[$key] = $value;
                endif;
            }
            $inventaire = Inventaire::find( array($conditions, "bind" => $bind) );
        }
        else{
           $inventaire = Inventaire::find();
        }

        $rs = [];
        for($i = 0; $i < count($inventaire); $i++) {
            $rs[$i]['id']       = $inventaire[$i]->id;
            $rs[$i]['objet']    = $inventaire[$i]->objet;
            $rs[$i]['date']     = date("d/m/Y", strtotime($inventaire[$i]->date));
            $rs[$i]['debut']    = date("d/m/Y", strtotime($inventaire[$i]->debut));
            $rs[$i]['fin']      = date("d/m/Y", strtotime($inventaire[$i]->fin));
            $rs[$i]['etat']     = $inventaire[$i]->etat;
        }

        /*print json_encode($rs, JSON_PRETTY_PRINT);exit();
        var_dump($rs);exit();*/
        $this->view->inventaires   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createInventaireAction() {
        $form = new InventaireForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }


            $inventaire = Inventaire::findFirst(array(
                    'conditions' => 'etat = ?0 ',
                    'bind' => array( 0 => "encours" )));
            if ($inventaire) {
                $this->flash->error("Un inventaire non clôturé existe déjà");
                //return $this->view->partial("layouts/flash");
                return $this->response->redirect("inventaire/index");
            }


            $inventaire = Inventaire::findFirst(array(
                    'conditions' => 'objet = ?0 ',
                    'bind' => array( 0 => $data['objet'] )));
            if ($inventaire) {
                $this->flash->error("Un inventaire existe déjà avec cet objet");
                //return $this->view->partial("layouts/flash");
                return $this->response->redirect("produit/index");
            }


            $inventaire = new Inventaire();
            $inventaire->objet  = $data['objet'];
            $inventaire->date   = $data['date'];
            $inventaire->debut  = $data['debut'];
            $inventaire->fin    = $data['fin'];
            $inventaire->etat   = "encours";

            if (!$inventaire->save()) {
                var_dump($inventaire->getMessages());
                $msg = $this->trans['on_error'];
                $this->view->disable();
                $this->flash->error("$msg");
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Inventaire créé']);
            return $this->response->redirect("inventaire/details/" . $inventaire->id);
        }

        $this->view->disable();
        $this->view->partial('inventaire/createInventaire');
    }

    public function editInventaireAction($id) {
        $this->view->disable();
        $form = new InventaireForm($this->trans);
        $this->view->inventaire_id = $id;
        $this->view->form_action = 'edit';
        $inventaire = Inventaire::findFirst($id);
        if(!$inventaire){
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
            $inventaire->objet  = $data['objet'];
            $inventaire->date   = $data['date'];
            $inventaire->debut  = $data['debut'];
            $inventaire->fin    = $data['fin'];

            if (!$inventaire->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Inventaire modifié']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "objet" => $inventaire->objet,
                    "date" => $inventaire->date,
                    "debut" => $inventaire->debut,
                    "fin" => $inventaire->fin
            ));
            $this->view->partial("inventaire/createInventaire");
        }
    }

    public function detailsAction($id = 0) {       

        if($this->request->isPost()){
            $data = $this->request->getPost();
            if($data['inventaire_id'] == ""){
                $this->flash->error("Veuillez choisir un inventaire pour afficher ses details");
                return $this->response->redirect("inventaire/index");
            }
           //On supprime tout d'abord
            $query = $this->modelsManager->createQuery("DELETE FROM InventaireDetails WHERE inventaire_id = :id:");
            $exec  = $query->execute(
                            array('id' => $data['inventaire_id'])
                        );

            $i = 0;
            while(isset($data['id'][$i])){
                $item               = new InventaireDetails();
                $item->initial      = $data['initial'][$i];
                $item->entre        = $data['entre'][$i];
                $item->sortie       = $data['sortie'][$i];
                $item->theorique    = $data['theorique'][$i];
                $item->physique     = $data['physique'][$i];
                $item->perte        = $data['perte'][$i];
                $item->ajout        = $data['ajout'][$i];
                $item->observation  = $data['observation'][$i];
                $item->details      = $data['details'][$i];
                $item->produit_id   = $data['produit_id'][$i];
                $item->inventaire_id    = $data['inventaire_id'];

                if (!$item->save()) {
                    $msg = "l'item (". $data['id'] .") n'a pas été correctement enregistrées. veuillez contacter l'admin";
                    $this->flash->error($msg);
                }
                $i++;
            }

            $this->flash->success("Produit ajouté à l'inventaire avec succès");
            return $this->response->redirect("inventaire/details/" . $data['inventaire_id']);
        }

        if($id == 0){
            $this->flash->error("Veuillez choisir un inventaire pour afficher ses details");
            return $this->response->redirect("inventaire/index");
        }
        $rsInventaire        = array();
        $rsInventaireDetails = array();

        //l'inventaire
        $inventaire = Inventaire::findFirst($id);
        $rsInventaire['id']     = $inventaire->id;
        $rsInventaire['objet']  = $inventaire->objet;
        $rsInventaire['date']   = date("d/m/Y", strtotime($inventaire->date));
        $rsInventaire['debut']  = date("d/m/Y", strtotime($inventaire->debut));
        $rsInventaire['debut_times']  = strtotime($inventaire->debut);
        $rsInventaire['fin']    = date("d/m/Y", strtotime($inventaire->fin));
        $rsInventaire['fin_times']    = strtotime($inventaire->fin);
        $rsInventaire['etat']   = $inventaire->etat;
        
        //les details de la inventaire
        $inventaireDetails = $inventaire->getInventaireDetails();
        for($i = 0; $i < count($inventaireDetails); $i++) {
            $rsInventaireDetails[$i]['id']    = $inventaireDetails[$i]->id;
            $produit                        = $this->getProduitInfos($inventaireDetails[$i]->getProduit());

            $rsInventaireDetails[$i]['produit_id']        = $produit['id'];
            $rsInventaireDetails[$i]['produit_libelle']   = $produit['libelle'];
            $rsInventaireDetails[$i]['produit_dosage']    = $produit['dosage'];
            $rsInventaireDetails[$i]['produit_type']      = $produit['type'];
            $rsInventaireDetails[$i]['produit_forme']     = $produit['forme'];
            $rsInventaireDetails[$i]['produit_classe_th'] = $produit['classe_th'];

            $rsInventaireDetails[$i]['initial']     = $inventaireDetails[$i]->initial;
            $rsInventaireDetails[$i]['entre']       = $inventaireDetails[$i]->entre;
            $rsInventaireDetails[$i]['sortie']      = $inventaireDetails[$i]->sortie;
            $rsInventaireDetails[$i]['theorique']   = $inventaireDetails[$i]->theorique;
            $rsInventaireDetails[$i]['physique']    = $inventaireDetails[$i]->physique;
            $rsInventaireDetails[$i]['perte']       = $inventaireDetails[$i]->perte;
            $rsInventaireDetails[$i]['ajout']       = $inventaireDetails[$i]->ajout;
            $rsInventaireDetails[$i]['observation'] = $inventaireDetails[$i]->observation;
            $rsInventaireDetails[$i]['details']     = $inventaireDetails[$i]->details;
        }
        

        $this->view->inventaire          = $rsInventaire;
        $this->view->inventaireDetails   = $rsInventaireDetails; 
    }

    public function deleteDetailsItemInventaireAction($id) {
        
        $this->view->disable();

        $inventaireDetails = InventaireDetails::findFirst($id);
        if(!$inventaireDetails){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$inventaireDetails->delete()) {
                echo 0;exit();
            }
            else{
                echo 1;exit();
            }
        }
        echo 0;exit();
    }

    public function ajaxProduitAction($commande_id = 0) {
        $this->view->disable();
        $builder = $this->modelsManager->createBuilder();
        $produits = $builder->columns('produit.id, produit.libelle, produit.seuil_max, produit.dosage, formeProduit.libelle as forme_libelle')
            ->addfrom('Produit', 'produit')
            ->join('FormeProduit', 'formeProduit.id = produit.forme_produit_id', 'formeProduit', 'LEFT')
            ->getQuery()->execute();
        $rs = array();
        for($i = 0; $i < count($produits); $i++) {
            $rs[$i]['id'] = json_encode($produits[$i], JSON_PRETTY_PRINT);
            $rs[$i]['libelle'] = $produits[$i]->libelle . "-" . $produits[$i]->forme_libelle . "-" . $produits[$i]->dosage;
        }
        echo json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function deleteInventaireAction($id) {
        $this->view->disable();

        $inventaire = Inventaire::findFirst($id);
        if(!$inventaire){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$inventaire->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }

    public function detailsAjoutAction() {

        if($this->request->isPost()){
            $data = $this->request->getPost();
            //var_dump($data);exit();
            if($data['inventaire_id'] == "" || $data['s_idproduit'] == ""){
                $this->flash->error("Veuillez choisir un inventaire pour afficher ses details");
                return $this->response->redirect("inventaire/index");
            }

            //On supprime tout d'abord
            $query = $this->modelsManager->createQuery("DELETE FROM InventaireDetails WHERE inventaire_id = :id: AND produit_id = :p:");
            $exec  = $query->execute(
                            array('id' => $data['inventaire_id'], 'p' => $data['s_idproduit'])
                        );
            $id = $data['inventaire_id'];
            $item               = new InventaireDetails();
            $item->initial      = $data['initial'];
            $item->entre        = $data['entre'];
            $item->sortie       = $data['sortie'];
            $item->theorique    = $data['theorique'];
            $item->perte        = $data['perte'];
            $item->ajout        = $data['ajout'];
            $item->observation  = $data['observation'];
            $item->physique     = 0;
            $i = 0;
            $tmpArray = array();
            while( isset($data['st_stock_id'][$i]) ){
                if($data['st_quantite'][$i] != ""){
                    $tmpArray[]    = array("st_stock_id" => $data['st_stock_id'][$i], "st_quantite" => $data['st_quantite'][$i]);
                    $item->physique += $data['st_quantite'][$i];
                }
                $i++;
            }

            $item->details      = json_encode($tmpArray);
            $item->produit_id   = $data['s_idproduit'];
            $item->inventaire_id    = $data['inventaire_id'];

            if (!$item->save()) {
                $msg = "l'item (". $data['id'] .") n'a pas été correctement enregistrées. veuillez contacter l'admin";
                $this->flash->error($msg);
            }
            $this->flash->success("Produit ajouté à l'inventaire avec succès");
            return $this->response->redirect("inventaire/details/" . $data['inventaire_id']);
        }
        
    }

    public function clotureAction($id = 0){

        if($id <= 0 ){
            echo 0;exit();
        }

        //l'inventaire
        $inventaire = Inventaire::findFirst($id);
        
        //On recupere les details de l'inventaire et on boucle dessus
        $inventaireDetails = $inventaire->getInventaireDetails();
        for($i = 0; $i < count($inventaireDetails); $i++) {

            //Le produit
            $currentProduit = $inventaireDetails[$i]->getProduit();

            if($inventaireDetails[$i]->details != ""){
                //On recupere les details
                $stockArray = json_decode($inventaireDetails[$i]->details);
                foreach ($stockArray as $key => $stockArrayItem) {
                
                    //On instancie la transaction
                    $transactionProduit = new TransactionProduit();

                    //On instancie le stock_point_de_vente
                    $stockPointDistribution = StockPointDistribution::findFirst($stockArrayItem->st_stock_id);

                    $transactionProduit->stock_pv_avant  = $stockPointDistribution->reste;

                    //on donne les valeur a mettre a jour
                    $stockPointDistribution->stock  = $stockArrayItem->st_quantite;
                    $stockPointDistribution->reste  = $stockArrayItem->st_quantite;
                    
                    $transactionProduit->stock_pv_apres  = $stockArrayItem->st_quantite;

                    if ($stockPointDistribution->save()) {
                        $transactionProduit->stock_g_avant  = $currentProduit->stock;
                        $transactionProduit->stock_g_apres  = $inventaireDetails[$i]->physique;
                        $transactionProduit->created        = date("Y-m-d h:i:s");
                        $transactionProduit->quantite       = $stockArrayItem->st_quantite;
                        $transactionProduit->type           = 'inventaire';
                        $transactionProduit->produit_id     = $currentProduit->id;
                        $transactionProduit->lot            = $stockPointDistribution->lot;
                        $transactionProduit->date_peremption= $stockPointDistribution->date_peremption;
                        $transactionProduit->operation      = 'inventaire';
                        $transactionProduit->point_distribution_id  = $stockPointDistribution->point_distribution_id;
                        //On enregistre la transaction
                        $transactionProduit->save();
                    }  
                }
            }
            //on met a jour le stock general
            $currentProduit->stock = $inventaireDetails[$i]->physique;
            $currentProduit->save();

            //On appelle la fonction qui verifie et met a jour la date d'appro au cas ou le produit etait en rupture
            //TODO: la ligne a été commenté. Faut-il appéler cette fonction dans le cas des inventaires??
            //$this->checkAndUpdateRuptureStock($currentProduit);
        }

        //On change l'etat de l'inventaire
        $inventaire->etat = "cloture";
        $inventaire->save();

        echo 1;exit();
    
    }

    public function searchProduitStockAction($produit_id = 0, $date1 = "", $date2 = "", $inventaire_id = "") {
        $this->view->disable();

        $conditions = " 1 = 1 ";
        $bind       = [];
        if($produit_id != 0){
            $conditions .=  " AND produit_id =  :produit_id:";
            $bind["produit_id"] = $produit_id;
        }
        $date1 = date("Y-m-d", $date1);
        $date2 = date("Y-m-d", $date2);
        //initial
        $builder = $this->modelsManager->createBuilder();
        $inventaireDetails = InventaireDetails::find( 
                                array(  "produit_id = :id: AND inventaire_id != :inventaire_id:",
                                        "order" => "id desc", "limit" => "1",
                                        "bind" => array( "id" => $produit_id, "inventaire_id"  => $inventaire_id ) 
                                ) );

        $this->view->rs_initial = (count($inventaireDetails)>0) ? $inventaireDetails[0]->physique : 0;


        //entre
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns('IFNULL(sum(receptionDetails.quantite), 0) as summe')
            ->addfrom('Reception', 'reception')
            ->join('ReceptionDetails', 'reception.id = receptionDetails.reception_id', 'receptionDetails', 'INNER')
            ->where("produit_id = :id: AND reception.etat = 'cloture' AND reception.date between :date1: and :date2:" , array('id' => $produit_id, 'date1' => $date1, 'date2' => $date2))
            ->getQuery()->execute();
        $this->view->rs_entre = (count($req)>0) ? $req[0]->summe : 0;
        //sortie
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns('IFNULL(sum(recuMedicamentDetails.quantite), 0) as summe')
            ->addfrom('RecuMedicamentDetails', 'recuMedicamentDetails')
            ->join('RecuMedicament', 'recuMedicament.id = recuMedicamentDetails.recu_medicament_id', 'recuMedicament', 'INNER')
            ->where('recuMedicamentDetails.produit_id = :id: AND (recuMedicament.date) between :date1: and :date2:' , array('id' => $produit_id, 'date1' => $date1, 'date2' => $date2))
            ->getQuery()->execute();
        $this->view->rs_sortie = (count($req)>0) ? $req[0]->summe : 0;

        //theorique
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns('produit.stock')
            ->addfrom('Produit', 'produit')
            ->where('produit.id = :id:' , array('id' => $produit_id))
            ->getQuery()->execute();
        $this->view->rs_theorique = (count($req)>0) ? $req[0]->stock : 0;

        //perte
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns('IFNULL(sum(ajustement.quantite), 0) as summe')
            ->addfrom('Ajustement', 'ajustement')
            ->where('ajustement.produit_id = :id: AND ajustement.type = "perte" AND ajustement.date between :date1: and :date2:' , array('id' => $produit_id, 'date1' => $date1, 'date2' => $date2))
            ->getQuery()->execute();
        $this->view->rs_perte = (count($req)>0) ? $req[0]->summe : 0;

        //ajout
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns('IFNULL(sum(ajustement.quantite), 0) as summe')
            ->addfrom('Ajustement', 'ajustement')
            ->where('ajustement.produit_id = :id: AND ajustement.type = "ajout" AND ajustement.date between :date1: and :date2:' , array('id' => $produit_id, 'date1' => $date1, 'date2' => $date2))
            ->getQuery()->execute();
        $this->view->rs_ajout = (count($req)>0) ? $req[0]->summe : 0;

        $this->view->rs_observation = "";

        $stockPointDistribution = StockPointDistribution::find( array($conditions, "bind" => $bind) );
        $rs = [];
        for($i = 0; $i < count($stockPointDistribution); $i++) {
            $rs[$i]['id']                   = $stockPointDistribution[$i]->id;
            $rs[$i]['point_distribution']   = $stockPointDistribution[$i]->getPointDistribution()->libelle;
            $rs[$i]['lot']                  = $stockPointDistribution[$i]->lot;
            $rs[$i]['stock']                = $stockPointDistribution[$i]->stock;
            $rs[$i]['reste']                = $stockPointDistribution[$i]->reste;
            $rs[$i]['date_peremption']      = $stockPointDistribution[$i]->date_peremption;

            $produit                        = $this->getProduitInfos($stockPointDistribution[$i]->getProduit());
            $rs[$i]['produit_id']           = $produit['id'];
            $rs[$i]['produit_libelle']      = $produit['libelle'];
            $rs[$i]['produit_dosage']       = $produit['dosage'];
            $rs[$i]['produit_type']         = $produit['type'];
            $rs[$i]['produit_forme']        = $produit['forme'];
            $rs[$i]['produit_classe_th']    = $produit['classe_th'];
            $rs[$i]['physique']             = "";

            $tmpPhysique = InventaireDetails::find( 
                                array(  "produit_id = :id: AND inventaire_id = :inventaire_id:",
                                        "order" => "id desc", "limit" => "1",
                                        "bind" => array( "id" => $produit['id'], "inventaire_id"  => $inventaire_id ) 
                                ) );
            if(count($tmpPhysique)>0){
                $this->view->rs_observation = $tmpPhysique[0]->observation;
                //On recupere les details
                $stockArray = json_decode($tmpPhysique[0]->details);
                foreach ($stockArray as $key => $stockArrayItem) {
                    if($stockArrayItem->st_stock_id == $stockPointDistribution[$i]->id){
                        $rs[$i]['physique'] = $stockArrayItem->st_quantite;
                    }
                }
            }
        }

        /*print json_encode($rs, JSON_PRETTY_PRINT);exit();
        var_dump($rs);exit();*/
        $this->view->stockProduits  = $rs;
        $this->view->partial("inventaire/searchProduitStock");
    }
}
