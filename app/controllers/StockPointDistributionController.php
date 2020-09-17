<?php

/**
 * ReceptionController
 *
 */
class StockPointDistributionController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Stock - point de distribution"]);
        if($this->view->language == "fr"){
            $langue = "fr-FR";
        }
        else{
            $langue = "en-US";
        }
        $this->view->langue = $langue;
    }

    public function indexAction() {       
       
    
        if($this->request->isPost()){
            $data = $this->request->getPost();

            $conditions = "";
            $bind       = [];
            foreach ($data as $key => $value) {
                if($value != ""):
                    $conditions .= ($conditions == "") ? $key . " =  :" . $key . ":" : " AND " . $key . " =  :" . $key . ":";
                    $bind[$key] = $value;
                endif;
            }
            $stockPointDistribution = StockPointDistribution::find( array($conditions, "bind" => $bind) );
        }
        else{
           $stockPointDistribution = StockPointDistribution::find("reste > 0 ");
        }

        $rs = [];
        for($i = 0; $i < count($stockPointDistribution); $i++) {
            $rs[$i]['id']                   = $stockPointDistribution[$i]->id;
            $rs[$i]['point_distribution']   = $stockPointDistribution[$i]->getPointDistribution()->libelle;
            $rs[$i]['lot']                  = $stockPointDistribution[$i]->lot;
            $rs[$i]['stock']                = $stockPointDistribution[$i]->stock;
            $rs[$i]['reste']                = $stockPointDistribution[$i]->reste;
            $rs[$i]['date_peremption']      = $stockPointDistribution[$i]->date_peremption;

            $produit                     = $this->getProduitInfos($stockPointDistribution[$i]->getProduit());
            $rs[$i]['produit_id']        = $produit['id'];
            $rs[$i]['produit_libelle']   = $produit['libelle'];
            $rs[$i]['produit_dosage']    = $produit['dosage'];
            $rs[$i]['produit_type']      = $produit['type'];
            $rs[$i]['produit_forme']     = $produit['forme'];
            $rs[$i]['produit_classe_th'] = $produit['classe_th'];
        }

        /*print json_encode($rs, JSON_PRETTY_PRINT);exit();
        var_dump($rs);exit();*/
        $this->view->mode              = "distribution";
        $this->view->stockPointDistributions = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createDistributionAction($stock_list = ""){

        if($this->request->isPost()){

            $data = $this->request->getPost();
            $i = 0;
            while( isset($data['id'][$i]) ){
                if($data['quantite'][$i] > 0){
                    $pointDistributionSource = StockPointDistribution::findFirst($data['id'][$i]);
                    //Le produit
                    $currentProduit = $pointDistributionSource->getProduit();

                    //on instancie la transaction en moin
                    $transactionSource = new TransactionProduit();
                    $transactionSource->stock_pv_avant  = $pointDistributionSource->reste;

                    //On deduit le stock de la source
                    $pointDistributionSource->reste -= $data['quantite'][$i];
                    $transactionSource->stock_pv_apres = $pointDistributionSource->reste;
                    $transactionSource->stock_g_avant  = $currentProduit->stock;

                    $pointDistributionSource->save();

                    $transactionSource->stock_g_apres  = $currentProduit->stock;
                    $transactionSource->created        = date("Y-m-d H:i:s");
                    $transactionSource->quantite       = $data['quantite'][$i];
                    $transactionSource->type           = 'distribution';
                    $transactionSource->produit_id     = $currentProduit->id;
                    $transactionSource->lot            = $pointDistributionSource->lot;
                    $transactionSource->date_peremption= $pointDistributionSource->date_peremption;
                    $transactionSource->operation      = 's';
                    $transactionSource->point_distribution_id  = $pointDistributionSource->point_distribution_id;
                    $transactionSource->save();


                    //On augmente le stock destination

                    //on instancie la transaction en moin
                    $transactionDestination = new TransactionProduit();

                    //On verifie si le point de distribution destination a deja le lot pour le produit
                    $test = StockPointDistribution::findFirst(array("produit_id = :produit_id: AND point_distribution_id = :point_distribution_id: AND lot = :lot:", "bind"=> array(
                                                        "produit_id" => $pointDistributionSource->produit_id, 
                                                        "point_distribution_id" => $data['point_distribution_id'],
                                                        "lot" => $pointDistributionSource->lot 
                                                        )
                                                    ));

                    $motifAppro = $pointDistributionSource->getPointDistribution()->libelle . "->";
                    if($test){
                        //On met a jour le stock
                        $transactionDestination->stock_pv_avant  = $test->reste;
                        $test->reste += $data['quantite'][$i];

                        //Todo
                        /* si c'est un appro alors stock++
                        * sinon si c'es un retour alors on touche pas au stock
                        */
                        $test->stock += $data['quantite'][$i];
                        
                        $transactionDestination->stock_pv_apres = $test->reste;
                        $transactionDestination->stock_g_avant  = $currentProduit->stock;
                        $transactionDestination->stock_g_apres  = $currentProduit->stock;
                        $transactionDestination->created        = date("Y-m-d H:i:s");
                        $transactionDestination->quantite       = $data['quantite'][$i];
                        $transactionDestination->type           = 'distribution';
                        $transactionDestination->produit_id     = $currentProduit->id;
                        $transactionDestination->lot            = $pointDistributionSource->lot;
                        $transactionDestination->date_peremption= $pointDistributionSource->date_peremption;
                        $transactionDestination->operation      = 'a';
                        $transactionDestination->point_distribution_id  = $data['point_distribution_id'];
                        $transactionDestination->save();

                        $test->save();
                        $motifAppro .= $test->getPointDistribution()->libelle;
                    }
                    else{
                        $transactionDestination->stock_pv_avant  = 0;
                        //On cree la ligne
                        $destination = new StockPointDistribution();
                        $destination->lot                   = $pointDistributionSource->lot;
                        $destination->date_peremption       = $pointDistributionSource->date_peremption;
                        $destination->point_distribution_id = $data['point_distribution_id'];
                        $destination->stock                 = $data['quantite'][$i];
                        $destination->reste                 = $data['quantite'][$i];
                        $destination->produit_id            = $pointDistributionSource->produit_id;

                        $transactionDestination->stock_pv_apres = $data['quantite'][$i];
                        $transactionDestination->stock_g_avant  = $currentProduit->stock;
                        $transactionDestination->stock_g_apres  = $currentProduit->stock;
                        $transactionDestination->created        = date("Y-m-d H:i:s");
                        $transactionDestination->quantite       = $data['quantite'][$i];
                        $transactionDestination->type           = 'appro';
                        $transactionDestination->produit_id     = $currentProduit->id;
                        $transactionDestination->lot            = $pointDistributionSource->lot;
                        $transactionDestination->date_peremption= $pointDistributionSource->date_peremption;
                        $transactionDestination->operation      = 'a';
                        $transactionDestination->point_distribution_id  = $data['point_distribution_id'];
                        //var_dump($transactionDestination);exit();
                        $transactionDestination->save();

                        $destination->save();
                        $motifAppro .= $destination->getPointDistribution()->libelle;
                    }

                    //On enregistre dans la table provision
                    $appro = new Approvisionnement();
                    $appro->produit_id              = $currentProduit->id;
                    $appro->point_distribution_id   = $data['point_distribution_id'];
                    $appro->quantite                = $data['quantite'][$i];
                    $appro->lot                     = $pointDistributionSource->lot;
                    $appro->date_peremption         = $pointDistributionSource->date_peremption;
                    $appro->date                    = date("Y-m-d H:i:s");
                    $appro->motif                   = $motifAppro;
                    $appro->save();
                }

                $i++;
            }

            $this->flash->success("Produit distribué avec succès");
            return $this->response->redirect("stock_point_distribution/index");
        }
        else{
            $this->view->disable();
            if($stock_list == ""){
                $this->flash->error("Veuillez choisir un ou plusieurs produits pour faire une distribution");
                return $this->response->redirect("stock_point_distribution/index");
            }

            $stock_list = explode(",", $stock_list);
            $rs = array();
            foreach ($stock_list as $k => $v) {
                if($stockDetail[$k] = StockPointDistribution::findFirst($v)){

                    $rs[$k]['id']                   = $stockDetail[$k]->id;
                    $rs[$k]['point_distribution']   = $stockDetail[$k]->getPointDistribution()->libelle;
                    $rs[$k]['lot']                  = $stockDetail[$k]->lot;
                    $rs[$k]['stock']                = $stockDetail[$k]->stock;
                    $rs[$k]['reste']                = $stockDetail[$k]->reste;
                    $rs[$k]['date_peremption']      = $stockDetail[$k]->date_peremption;

                    $produit                        = $this->getProduitInfos($stockDetail[$k]->getProduit());
                    $rs[$k]['produit_id']           = $produit['id'];
                    $rs[$k]['produit_libelle']      = $produit['libelle'];
                    $rs[$k]['produit_dosage']       = $produit['dosage'];
                    $rs[$k]['produit_type']         = $produit['type'];
                    $rs[$k]['produit_forme']        = $produit['forme'];
                    $rs[$k]['produit_classe_th']    = $produit['classe_th'];
                }
            }
            $this->view->pointDistribution  = PointDistribution::find();
            $this->view->stock_list         = $rs;
            $this->view->partial("stock_point_distribution/createDistribution");
        }
    }

}
