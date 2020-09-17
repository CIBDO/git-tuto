<?php

/**
 * ReceptionController
 *
 */
class ReceptionController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Reception"]);
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
                if($key == "date1" || $key == "date2"|| $key == "_url")
                    continue;
                if($value != ""):
                    $conditions .= ($conditions == "") ? $key . " =  :" . $key . ":" : " AND " . $key . " =  :" . $key . ":";
                    $bind[$key] = $value;
                endif;
            }
            $receptions = Reception::find( array($conditions, "order" => "date desc", "bind" => $bind) );
        }
        else{
           $receptions = Reception::find(array("order" => "date desc"));
        }

        $rs = [];
        for($i = 0; $i < count($receptions); $i++) {
            $rs[$i]['id']               = $receptions[$i]->id;
            $rs[$i]['objet']            = $receptions[$i]->objet;
            $rs[$i]['date']             = date("d/m/Y", strtotime($receptions[$i]->date));
            $rs[$i]['fournisseur']      = $receptions[$i]->getFournisseur()->libelle;
            $rs[$i]['etat']             = $receptions[$i]->etat;
            
            if($receptions[$i]->commande_id != "" && $receptions[$i]->commande_id != null){
                $tmpCommande                = $receptions[$i]->getCommande();
                $rs[$i]['commande_id']      = $tmpCommande->id;
                $rs[$i]['commande_objet']   = $tmpCommande->objet;
                $rs[$i]['commande_date']    = $tmpCommande->date;
            }
            else{
                $rs[$i]['commande_id']      = "";
                $rs[$i]['commande_objet']   = "";
                $rs[$i]['commande_date']    = "";
            }

        }

        /*print json_encode($rs, JSON_PRETTY_PRINT);exit();
        var_dump($rs);exit();*/
        $this->view->receptions   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function detailsAjoutAction() {

        if($this->request->isPost()){
            $data = $this->request->getPost();
            if($data['reception_id'] == ""){
                $this->flash->error("Veuillez choisir une reception pour afficher ses details");
                return $this->response->redirect("reception/index");
            }
            $id = $data['reception_id'];
            $item                   = new ReceptionDetails();
            $item->quantite         = $data['quantite'];
            $item->litige           = ($data['litige'] == "") ? 0 : $data['litige'];
            $item->manquant         = ($data['manquant'] == "") ? 0 : $data['manquant'];;
            $item->observation      = $data['observation'];
            $item->lot              = $data['lot'];
            $item->date_peremption  = $data['date_peremption'];
            $item->prix_achat       = $data['prix_achat'];
            $item->coef             = $data['coef'];
            $item->prix_vente       = $data['prix_vente'];
            $item->produit_id       = $data['produit_id'];
            $item->reception_id     = $data['reception_id'];

            if (!$item->save()) {
                $msg = "l'item (". $data['id'] .") n'a pas été correctement enregistrées. veuillez contacter l'admin";
                $this->flash->error($msg);
            }
            if(isset($data['commande_id'])){
                $commande = Reception::findFirst($data['reception_id'])->getCommande();
                $commande->etat = 'reception';
                $commande->save();
            }
            $this->flash->success("Produit ajouté à la réception avec succès");
            return $this->response->redirect("reception/details/" . $data['reception_id']);
        }
        
    }

    public function detailsAction($id = 0) {       
        
        $config = $this->getStructureConfig();
        $this->view->lot_multiple = ($config->pharmacie_type != "") ? $config->pharmacie_type : 0;

        if($this->request->isPost()){
            $data = $this->request->getPost();
            if($data['reception_id'] == ""){
                $this->flash->error("Veuillez choisir une reception pour afficher ses details");
                return $this->response->redirect("reception/index");
            }
           //On supprime tout d'abord
            $query = $this->modelsManager->createQuery("DELETE FROM ReceptionDetails WHERE reception_id = :id:");
            $exec  = $query->execute(
                            array('id' => $data['reception_id'])
                        );

            $i = 0;
            while(isset($data['id'][$i])){
                $item                   = new ReceptionDetails();
                $item->quantite         = $data['quantite'][$i];
                $item->litige           = ($data['litige'][$i] == "") ? 0 : $data['litige'][$i];
                $item->manquant         = ($data['manquant'][$i] == "") ? 0 : $data['manquant'][$i];
                $item->observation      = $data['observation'][$i];
                $item->lot              = $data['lot'][$i];
                $item->date_peremption  = $data['date_peremption'][$i];
                $item->prix_achat       = $data['prix_achat'][$i];
                $item->coef             = $data['coef'][$i];
                $item->prix_vente       = $data['prix_vente'][$i];
                $item->produit_id       = $data['produit_id'][$i];
                $item->reception_id     = $data['reception_id'];

                if (!$item->save()) {
                    $msg = "l'item (". $data['id'] .") n'a pas été correctement enregistrées. veuillez contacter l'admin";
                    $this->flash->error($msg);
                }
                $i++;
            }
            if(isset($data['commande_id'])){
                $commande = Reception::findFirst($data['reception_id'])->getCommande();
                $commande->etat = 'reception';
                $commande->save();
            }
            $this->flash->success("Produit ajouté avec succès");
            return $this->response->redirect("reception/details/" . $data['reception_id']);
        }

        if($id == 0){
            $this->flash->error("Veuillez choisir une reception pour afficher ses details");
            return $this->response->redirect("reception/index");
        }
        $rsReception        = array();
        $rsCommande         = array();
        $rsReceptionDetails = array();

        //la reception
        $reception = Reception::findFirst($id);
        $rsReception['id']           = $reception->id;
        $rsReception['objet']        = $reception->objet;
        $rsReception['date']         = date("d/m/Y", strtotime($reception->date));
        $rsReception['fournisseur']  = $reception->getFournisseur()->libelle;
        $rsReception['etat']         = $reception->etat;

        if($reception->commande_id != null && $reception->commande_id !=""){
            $tmpCommande = $reception->getCommande();
            $rsCommande['id']           = $tmpCommande->id;
            $rsCommande['objet']        = $tmpCommande->objet;
            $rsCommande['date']         = date("d/m/Y", strtotime($tmpCommande->date));
            $rsCommande['montant']      = $tmpCommande->montant;
            $rsCommande['etat']         = $tmpCommande->etat;
            $this->view->commande       = $rsCommande;
        }
        
        //les details de la reception
        $receptionDetails = $reception->getReceptionDetails();
        for($i = 0; $i < count($receptionDetails); $i++) {
            $rsReceptionDetails[$i]['id']    = $receptionDetails[$i]->id;
            $produit                        = $this->getProduitInfos($receptionDetails[$i]->getProduit());

            $rsReceptionDetails[$i]['produit_id']        = $produit['id'];
            $rsReceptionDetails[$i]['produit_libelle']   = $produit['libelle'];
            $rsReceptionDetails[$i]['produit_dosage']    = $produit['dosage'];
            $rsReceptionDetails[$i]['produit_type']      = $produit['type'];
            $rsReceptionDetails[$i]['produit_forme']     = $produit['forme'];
            $rsReceptionDetails[$i]['produit_classe_th'] = $produit['classe_th'];

            $rsReceptionDetails[$i]['reste_a_livree'] = "";
            if($reception->commande_id != null && $reception->commande_id !=""){
                $builder = $this->modelsManager->createBuilder();
                $test = $builder->columns('commandeDetails.quantite, commandeDetails.quantite_livree')
                ->addfrom('CommandeDetails', 'commandeDetails')
                ->where('commandeDetails.commande_id = :id: AND commandeDetails.produit_id = :p:' , array('id' => $reception->commande_id, 'p' => $produit['id']))
                ->getQuery()->execute();
                if(count($test)>0){
                    $rsReceptionDetails[$i]['reste_a_livree']   = $test[0]->quantite - $test[0]->quantite_livree;
                }
            }

            $rsReceptionDetails[$i]['quantite']             = $receptionDetails[$i]->quantite;
            $rsReceptionDetails[$i]['litige']               = $receptionDetails[$i]->litige;
            $rsReceptionDetails[$i]['manquant']             = $receptionDetails[$i]->manquant;
            $rsReceptionDetails[$i]['observation']          = $receptionDetails[$i]->observation;
            
            if($config->pharmacie_type == 0){
                $rsReceptionDetails[$i]['lot']  = ($receptionDetails[$i]->lot == "") ? $config->default_lot : $receptionDetails[$i]->lot;
                $rsReceptionDetails[$i]['date_peremption']  = ($receptionDetails[$i]->date_peremption == "") ? $config->default_peremption : $receptionDetails[$i]->date_peremption;
            }
            else{
                $rsReceptionDetails[$i]['lot']  = $receptionDetails[$i]->lot;
                $rsReceptionDetails[$i]['date_peremption']  = $receptionDetails[$i]->date_peremption;
            }
            $rsReceptionDetails[$i]['prix_achat']   = $receptionDetails[$i]->prix_achat;
            $rsReceptionDetails[$i]['coef'] = ($receptionDetails[$i]->coef == "" || $receptionDetails[$i]->coef == null) ? $config->default_coef : $receptionDetails[$i]->coef;
            $rsReceptionDetails[$i]['prix_vente']   = $receptionDetails[$i]->prix_vente;
        }
        

        $this->view->reception          = $rsReception;
        $this->view->receptionDetails   = $rsReceptionDetails; 

    }

    public function createReceptionAction($commande_id = "", $produit_list = "") {
        $form = new ReceptionForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if($commande_id == "" && $produit_list == ""){
            $this->view->test = "a";
        }
        else{
            $this->view->test = "b";
        }
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $reception = Reception::findFirst(array(
                    'conditions' => 'objet = ?0 ',
                    'bind' => array( 0 => $data['objet'] )));
            if ($reception) {
                $this->flash->error("Une réception existe déjà avec cet objet");
                //return $this->view->partial("layouts/flash");
                return $this->response->redirect("produit/index");
            }

            $reception = new Reception();
            $reception->objet = $data['objet'];
            $reception->date = $data['date'];
            $reception->etat = "encours";
            if(isset($data["produit_list"]) && $data["produit_list"] != ""){
                $tmp = explode(",", $data["produit_list"]);
                $rcpDetail = array();
                foreach ($tmp as $k => $v) {
                    $rcpDetail[$k]              = new ReceptionDetails();
                    $rcpDetail[$k]->produit_id  = $v;
                }
                $reception->receptionDetails = $rcpDetail;
            }

            if( $data['commande_id'] != "" && $data['commande_id'] > 0 ){
                $reception->commande_id = $data['commande_id'];
                $tmpCommande = Commande::findFirst($data['commande_id']);
                $fournisseur_id = $tmpCommande->getFournisseur()->id;
                $reception->fournisseur_id = $tmpCommande->getFournisseur()->id;

                $rcpDetail = array();
                $tmpCommandeDetails = $tmpCommande->getCommandeDetails();
                foreach ($tmpCommandeDetails as $k => $v) {
                    $rcpDetail[$k]              = new ReceptionDetails();
                    $rcpDetail[$k]->produit_id  = $v->produit_id;
                    $rcpDetail[$k]->quantite    = $v->quantite - $v->quantite_livree;
                    $rcpDetail[$k]->prix_achat  = $v->prix;
                }
                $reception->receptionDetails = $rcpDetail;
            }
            else{
                $reception->fournisseur_id = $data['fournisseur_id'];
            }


            if (!$reception->save()) {
                var_dump($reception->getMessages());
                $msg = $this->trans['on_error'];
                $this->view->disable();
                $this->flash->error("$msg");
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Réception créée avec succès']);
            return $this->response->redirect("reception/details/" . $reception->id);
        }

        $this->view->disable();
        if( $commande_id != "" && $commande_id != 0 && $commande_id != "0" && $commande_id > 0 ){
            $commande = Commande::findFirst($commande_id);
            $fournisseur_id = $commande->getFournisseur()->id;
            Phalcon\Tag::setDefaults(array(
                    "objet" => "RCP_ " . $commande->objet,
                    "commande_id" => $commande_id,
                    "fournisseur_id" => $fournisseur_id
            ));
            $this->view->commande_id = $commande_id;
            $this->view->fournisseur_id = $commande->fournisseur_id;
        }

        if(isset($data["produit_list"]) && $data["produit_list"] != ""){
            $this->view->produit_list = $produit_list;
        }
        $this->view->partial('reception/createReception');
    }

    public function editReceptionAction($id) {
        $this->view->disable();
        $form = new ReceptionForm($this->trans);
        $this->view->reception_id = $id;
        $this->view->form_action = 'edit';
        $reception = Reception::findFirst($id);
        if(!$reception){
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
            $reception->objet = $data['objet'];
            $reception->date = $data['date'];
            $reception->fournisseur_id = $data['fournisseur_id'];
            if( $data['commande_id'] != "" && $data['commande_id'] > 0 ){
                $reception->commande_id = $data['commande_id'];
            }
            if (!$reception->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Reception modified']);
            return $this->view->partial("layouts/flash");
        } else {
            if( $reception->commande_id != null ){
                $this->view->test = "a";
            }
            else{
                $this->view->test = "b";
            }
            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "objet" => $reception->objet,
                    "date" => $reception->date,
                    "fournisseur_id" => $reception->fournisseur_id,
                    "commande_id" => $reception->commande_id,
            ));
            $this->view->partial("reception/createReception");
        }
    }

    public function deleteReceptionAction($id) {
        $this->view->disable();

        $reception = Reception::findFirst($id);
        if(!$reception){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$reception->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }

    public function deleteDetailsItemReceptionAction($id) {
        
        $this->view->disable();

        $receptionDetails = ReceptionDetails::findFirst($id);
        if(!$receptionDetails){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$receptionDetails->delete()) {
                echo 0;exit();
            }
            else{
                echo 1;exit();
            }
        }
        echo 0;exit();
    }

    public function ajaxProduitAction($reception_id = 0, $commande_id = 0) {
        $this->view->disable();
        
        //Reception
        /*$builder = $this->modelsManager->createBuilder();
        $reception = $builder->columns('receptionDetails.produit_id')
            ->addfrom('ReceptionDetails', 'receptionDetails')
            ->where('reception_id = ' . $reception_id);
        $reception = $builder->getQuery()->execute();*/

        //Produit
        $config = $this->getStructureConfig();
        $builder = $this->modelsManager->createBuilder();
        if($commande_id != 0){
            $produits = $builder->columns('produit.id, produit.libelle, produit.seuil_max, produit.dosage, formeProduit.libelle as forme_libelle, commandeDetails.quantite, commandeDetails.quantite_livree, commandeDetails.prix as prix_achat')
                ->addfrom('Produit', 'produit')
                ->join('FormeProduit', 'formeProduit.id = produit.forme_produit_id', 'formeProduit', 'LEFT')
                ->join('CommandeDetails', 'produit.id = commandeDetails.produit_id', 'commandeDetails', 'LEFT')
                ->where('commandeDetails.commande_id = :id:' , array('id' => $commande_id))
                ->getQuery()->execute();
        }
        else{
            $produits = $builder->columns('produit.id, produit.libelle, produit.seuil_max, produit.dosage, formeProduit.libelle as forme_libelle')
                ->addfrom('Produit', 'produit')
                ->join('FormeProduit', 'formeProduit.id = produit.forme_produit_id', 'formeProduit', 'LEFT');
            /*if(count($reception)>0){
                foreach ($reception as $value) {
                    $pr[] = $value->produit_id;
                }
                $builder->where('produit.id NOT IN(' . implode(",", $pr) . ')');
            }*/
            $produits = $builder->getQuery()->execute();
        }

        $rs = array();
        for($i = 0; $i < count($produits); $i++) {
            $produits[$i]->def_coef = $config->default_coef;
            if($config->pharmacie_type == 0){
                $produits[$i]->def_lot = $config->default_lot;
                $produits[$i]->def_peremption = $config->default_peremption;
            }
            else{
                $produits[$i]->def_lot = "";
                $produits[$i]->def_peremption = "";
            }
            
            $produits[$i]->index = strtotime("now");
            $rs[$i]['id'] = json_encode($produits[$i], JSON_PRETTY_PRINT);
            $rs[$i]['libelle'] = $produits[$i]->libelle . "-" . $produits[$i]->forme_libelle . "-" . $produits[$i]->dosage;
        }
        
        echo json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function clotureAction($id = 0){

        if($this->request->isPost()){

            $data = $this->request->getPost();

            //la reception
            $reception = Reception::findFirst($data['reception_id']);
            $rsReception['id']           = $reception->id;
            
            //les details de la reception
            $receptionDetails = $reception->getReceptionDetails();
            for($i = 0; $i < count($receptionDetails); $i++) {

                //Le produit
                $currentProduit = $receptionDetails[$i]->getProduit();

                //On instancie la transaction
                $transactionProduit = new TransactionProduit();
                $transactionProduit->created        = date("Y-m-d H:i:s");
                $transactionProduit->quantite       = $receptionDetails[$i]->quantite;
                $transactionProduit->type           = 'appro';
                $transactionProduit->produit_id     = $currentProduit->id;
                $transactionProduit->lot            = $receptionDetails[$i]->lot;
                $transactionProduit->date_peremption= $receptionDetails[$i]->date_peremption;
                $transactionProduit->operation      = 'a';
                $transactionProduit->point_distribution_id  = $data['point_distribution_id'];
                $transactionProduit->stock_g_avant  = $currentProduit->stock;

                //On enregistre dans la table provision
                $appro = new Approvisionnement();
                $appro->produit_id              = $currentProduit->id;
                $appro->point_distribution_id   = $data['point_distribution_id'];
                $appro->quantite                = $receptionDetails[$i]->quantite;
                $appro->lot                     = $receptionDetails[$i]->lot;
                $appro->date_peremption         = $receptionDetails[$i]->date_peremption;
                $appro->date                    = date("Y-m-d H:i:s");
                $appro->motif                   = $reception->objet;
                $appro->reception_id            = $reception->id;

                if ($appro->save()) {
                    //On enregistre la table stock_point_de_vente

                    //On verifie si le point de distribution destination a deja le lot pour le produit
                    $test = StockPointDistribution::findFirst(array("produit_id = :produit_id: AND point_distribution_id = :point_distribution_id: AND lot = :lot:", 
                        "bind"=> array("produit_id" => $currentProduit->id, "point_distribution_id" => $data['point_distribution_id'], "lot" => $receptionDetails[$i]->lot 
                        )
                    ));
                    if($test){
                        $transactionProduit->stock_pv_avant  = $test->reste;

                        //On met a jour le stock
                        $test->reste += $receptionDetails[$i]->quantite;
                        $test->stock += $receptionDetails[$i]->quantite;
                        $test->save();
                        
                        $transactionProduit->stock_pv_apres = $test->reste;
                    }
                    else{
                        $transactionProduit->stock_pv_avant  = 0;

                        //On cree la ligne
                        $stockPointDistribution = new StockPointDistribution();
                        $stockPointDistribution->produit_id                 = $currentProduit->id;
                        $stockPointDistribution->point_distribution_id      = $data['point_distribution_id'];
                        $stockPointDistribution->stock                      = $receptionDetails[$i]->quantite;
                        $stockPointDistribution->reste                      = $receptionDetails[$i]->quantite;
                        $stockPointDistribution->lot                        = $receptionDetails[$i]->lot;
                        $stockPointDistribution->date_peremption            = $receptionDetails[$i]->date_peremption;
                        $stockPointDistribution->save();

                        $transactionProduit->stock_pv_apres = $receptionDetails[$i]->quantite;
                    }
                    //mise a jour du stock global
                    $currentProduit->stock +=  $receptionDetails[$i]->quantite;
                    $currentProduit->save();

                    //Enregistrement de la transaction
                    $transactionProduit->stock_g_apres  = $currentProduit->stock;
                    $transactionProduit->save();
                    
                    //On appelle la fonction qui verifie et met a jour la date d'appro au cas ou le produit etait en rupture
                    $this->checkAndUpdateRuptureStock($currentProduit);
                }  

                //On verifie si la reception est lié a une commande alors on met ajour la quantité livrée
                //et eventuellemnt on cloture la commande
                if($reception->commande_id != null && $reception->commande_id !=""){
                    $currentCommande = $reception->getCommande();
                    $currentCommandeDetails = $currentCommande->getCommandeDetails("produit_id = " . $currentProduit->id);
                    if( count($currentCommandeDetails)>0 ){
                        $currentCommandeDetails[0]->quantite_livree += $receptionDetails[$i]->quantite;
                        $currentCommande->etat = "reception";
                        $currentCommande->save();
                        $currentCommandeDetails[0]->save();
                    }
                }
            }

            //On change l'etat de la reception
            $reception->etat = "cloture";
            $reception->save();

            //On regarde la commande si tous les produits ont été livré alors on cloture la commande
            if($reception->commande_id != null && $reception->commande_id !=""){
                $currentCommande = $reception->getCommande();
                $currentCommandeDetails = $currentCommande->getCommandeDetails();
                $toutEstLivre = 1;
                foreach ($currentCommandeDetails as $currentItem) {
                    if($currentItem->quantite != $currentItem->quantite_livree){
                        $toutEstLivre = 0;
                    }
                }
                if($toutEstLivre == 1){
                    $currentCommande->etat = "cloture";
                    $currentCommande->save();
                }
            }

            $this->flash->success("Réception cloturée avec succès");
            return $this->response->redirect("reception/index");
        }
        else{
            $this->view->disable();
            if($id == 0){
                $this->flash->error("Veuillez choisir une réception pour valider");
                return $this->response->redirect("reception/index");
            }

            $this->view->pointDistribution = PointDistribution::find(array("type = 'stockage'"));
            $tmp = PointDistribution::find(array("type = 'stockage' and default = 1"));
            $defaultPointDistribution =  (count($tmp)>0) ? $tmp[0]->id : "";
            Phalcon\Tag::setDefaults(array(
                    "reception_id" => $id,
                    "point_distribution_id" => $defaultPointDistribution,
            ));
            $this->view->partial("reception/clotureReception");
        }
    }
}
