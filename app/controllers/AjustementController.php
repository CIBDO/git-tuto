<?php


class AjustementController  extends ControllerBase {

    public function indexAction(){

    	if($this->request->get("date1")){
            $data = $this->request->get();

            $conditions = "";
            $bind       = [];

            //cas de de la recherche par date
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
            $ajustement = Ajustement::find( array($conditions, "bind" => $bind) );
        }
        else{
           $ajustement = Ajustement::find();
        }

        $rs = [];
        for($i = 0; $i < count($ajustement); $i++) {
            $rs[$i]['id']               = $ajustement[$i]->id;
            $rs[$i]['motif']            = $ajustement[$i]->getAjustementMotifs()->libelle;
            $rs[$i]['date']             = $ajustement[$i]->date;
            $rs[$i]['point_distribution']   = $ajustement[$i]->getPointDistribution()->libelle;
            $rs[$i]['lot']                  = $ajustement[$i]->lot;
            $rs[$i]['quantite']             = $ajustement[$i]->quantite;
            $rs[$i]['type']                 = $ajustement[$i]->type;
            $rs[$i]['date_peremption']		= $ajustement[$i]->date_peremption;

            $produit                     = $this->getProduitInfos($ajustement[$i]->getProduit());
            $rs[$i]['produit_id']        = $produit['id'];
            $rs[$i]['produit_libelle']   = $produit['libelle'];
            $rs[$i]['produit_dosage']    = $produit['dosage'];
            $rs[$i]['produit_type']      = $produit['type'];
            $rs[$i]['produit_forme']     = $produit['forme'];
            $rs[$i]['produit_classe_th'] = $produit['classe_th'];

        }

        /*print json_encode($rs, JSON_PRETTY_PRINT);exit();
        var_dump($rs);exit();*/
        $this->view->ajustements   = json_encode($rs, JSON_PRETTY_PRINT);

    }

    public function createAjustementAction(){

        if($this->request->isPost()){

            $data = $this->request->getPost();
            $i = 0;
            while( isset($data['stock_id'][$i]) ){

                if($data['quantite'][$i] > 0){
                    if($data['type'] == 'perte'){
                        //on instancie la transaction en moin
                        $transactionSource = new TransactionProduit();

                        $pointDistributionSource = StockPointDistribution::findFirst($data['stock_id'][$i]);
                        //Le produit
                        $currentProduit = $pointDistributionSource->getProduit();
                        //var_dump($currentProduit->stock);exit();

                        $transactionSource->stock_g_avant  = $currentProduit->stock;
                        //On desuit le stock generale
                        $currentProduit->stock            -= $data['quantite'][$i];
                        $transactionSource->stock_g_apres  = $currentProduit->stock;

                        //$currentProduit->save();

                        $transactionSource->stock_pv_avant = $pointDistributionSource->reste;

                        //On deduit le stock de la source
                        $pointDistributionSource->reste   -= $data['quantite'][$i];
                        $transactionSource->stock_pv_apres = $pointDistributionSource->reste;

                        $pointDistributionSource->save();

                        $transactionSource->created        = date("Y-m-d h:i:s");
                        $transactionSource->quantite       = $data['quantite'][$i];
                        $transactionSource->type           = $data['type'];
                        $transactionSource->produit_id     = $currentProduit->id;
                        $transactionSource->lot            = $pointDistributionSource->lot;
                        $transactionSource->date_peremption= $pointDistributionSource->date_peremption;
                        $transactionSource->operation      = 's';
                        $transactionSource->point_distribution_id  = $pointDistributionSource->point_distribution_id;
                        $transactionSource->save();

                        //On enregistre dans la table ajustement
                        $ajustement = new Ajustement();
                        $ajustement->produit_id             = $currentProduit->id;
                        $ajustement->point_distribution_id  = $pointDistributionSource->point_distribution_id;
                        $ajustement->quantite               = $data['quantite'][$i];
                        $ajustement->lot                    = $pointDistributionSource->lot;
                        $ajustement->type                   = $data['type'];
                        $ajustement->date_peremption        = $pointDistributionSource->date_peremption;
                        $ajustement->date                   = date("Y-m-d h:i:s");
                        $ajustement->ajustement_motifs_id   = $data['motif'];
                        $mess = "";
                        $ajustement->save();
                    }
                    if($data['type'] == 'ajout'){
                        //on instancie la transaction en moin
                        $transactionSource = new TransactionProduit();

                        $pointDistributionSource = StockPointDistribution::findFirst($data['stock_id'][$i]);
                        //Le produit
                        $currentProduit = $pointDistributionSource->getProduit();
                        //var_dump($currentProduit->stock);exit();

                        $transactionSource->stock_g_avant  = $currentProduit->stock;
                        //On desuit le stock generale
                        $currentProduit->stock            += $data['quantite'][$i];
                        $transactionSource->stock_g_apres  = $currentProduit->stock;

                        $currentProduit->save();

                        $transactionSource->stock_pv_avant = $pointDistributionSource->reste;

                        //On deduit le stock de la source
                        $pointDistributionSource->reste   += $data['quantite'][$i];
                        $transactionSource->stock_pv_apres = $pointDistributionSource->reste;

                        $pointDistributionSource->save();

                        $transactionSource->created        = date("Y-m-d h:i:s");
                        $transactionSource->quantite       = $data['quantite'][$i];
                        $transactionSource->type           = $data['type'];
                        $transactionSource->produit_id     = $currentProduit->id;
                        $transactionSource->lot            = $pointDistributionSource->lot;
                        $transactionSource->date_peremption= $pointDistributionSource->date_peremption;
                        $transactionSource->operation      = 'a';
                        $transactionSource->point_distribution_id  = $pointDistributionSource->point_distribution_id;
                        $transactionSource->save();

                        //On enregistre dans la table ajustement
                        $ajustement = new Ajustement();
                        $ajustement->produit_id              = $currentProduit->id;
                        $ajustement->point_distribution_id   = $pointDistributionSource->point_distribution_id;
                        $ajustement->quantite                = $data['quantite'][$i];
                        $ajustement->lot                     = $pointDistributionSource->lot;
                        $ajustement->type                    = $data['type'];
                        $ajustement->date_peremption         = $pointDistributionSource->date_peremption;
                        $ajustement->date                    = date("Y-m-d h:i:s");
                        $ajustement->ajustement_motifs_id    = $data['motif'];
                        $mess = "";
                        $ajustement->save();
                    }
                }

                $i++;
            }
            
            $this->flash->success("Opération enregistrée avec succès");
            return $this->response->redirect("ajustement/index/distribution");
        }
        else{
            $this->view->disable();
            
            $this->view->partial("ajustement/createAjustement");
        }
    }

    public function searchProduitStockAction($type = 0, $produit_id = 0, $lot = 0) {
        $this->view->disable();
        
        $conditions = ($type == 'perte') ? " reste > 0 " : " 1 = 1 ";
        $bind       = [];
        if($produit_id != 0){
            $conditions .=  " AND produit_id =  :produit_id:";
            $bind["produit_id"] = $produit_id;
        }
        if($lot != 0){
            $conditions .= " AND lot = :lot:";
            $bind["lot"] = $lot;
        }

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
        }

        /*print json_encode($rs, JSON_PRETTY_PRINT);exit();
        var_dump($rs);exit();*/
        $this->view->type           = $type;
        $this->view->stockProduits  = $rs;

        $this->view->motif = AjustementMotifs::find();
        $this->view->partial("ajustement/searchProduitStock");
    }

}

