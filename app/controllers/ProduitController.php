<?php
use Phalcon\Mvc\Model\Resultset;

/**
 * ProduitController
 *
 */
class ProduitController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Produit"]);
        if($this->view->language == "fr"){
            $langue = "fr-FR";
        }
        else{
            $langue = "en-US";
        }
        $this->view->langue = $langue;
    }

    public function indexAction() {     

        if($this->request->get("filtre")){
            $data = $this->request->get();

            $conditions = "";
            $bind       = [];
            if($data['filtre'] == "rupture"){
                $conditions .= ($conditions == "") ? " stock = 0 " : " AND stock = 0 ";
                //$bind[$key] = $value;
            }
            if($data['filtre'] == "seuilMin"){
                $conditions .= ($conditions == "") ? " stock < seuil_min " : " AND stock < seuil_min ";
                //$bind[$key] = $value;
            }

            $produits = Produit::find( array($conditions, "bind" => $bind) );
            
            Phalcon\Tag::setDefaults(array(
                    "filtre" => $data['filtre'],
            ));
        }
        else{
           $produits = Produit::find();
        }

        $rs = [];
        for($i = 0; $i < count($produits); $i++) {
            $rs[$i]['id']           = $produits[$i]->id;
            $rs[$i]['libelle']      = $produits[$i]->libelle;

            $rs[$i]['type']         = ( $tmp = $produits[$i]->getTypeProduit() ) ? $tmp->libelle : "";
            $rs[$i]['forme']        = ( $tmp = $produits[$i]->getFormeProduit() ) ? $tmp->libelle : "";
            $rs[$i]['classe_th']    = ( $tmp = $produits[$i]->getClasseTherapeutique() ) ? $tmp->libelle : "";

            $rs[$i]['unite_vente']  = $produits[$i]->unite_vente;
            $rs[$i]['presentation'] = $produits[$i]->presentation;
            $rs[$i]['dosage']       = $produits[$i]->dosage;
            $rs[$i]['seuil_min']    = $produits[$i]->seuil_min;
            $rs[$i]['seuil_max']    = $produits[$i]->seuil_max;
            $rs[$i]['prix']         = $produits[$i]->prix;
            $rs[$i]['stock']        = $produits[$i]->stock;
            $rs[$i]['etat']         = $produits[$i]->etat;
        }

        /*print json_encode($rs, JSON_PRETTY_PRINT);exit();
        var_dump($rs);exit();*/
        $this->view->produits   = json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function createProduitAction() {
        $this->view->disable();
        $form = new ProduitForm($this->trans);
        $this->view->form = $form;
        $this->view->form_action = 'create';
        if ($this->request->isPost()) {

            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return $this->view->partial("layouts/flash");
            }

            $produit = new Produit();
            $produit->libelle           = $data['libelle'];
            $produit->type_produit_id   = ($data['type_produit_id'] != "") ? $data['type_produit_id'] : null;
            $produit->forme_produit_id  = ($data['forme_produit_id'] != "") ? $data['forme_produit_id'] : null;
            $produit->classe_therapeutique_id = ($data['classe_therapeutique_id'] != "") ? $data['classe_therapeutique_id'] : null;
            $produit->unite_vente       = $data['unite_vente'];
            $produit->presentation      = $data['presentation'];
            $produit->dosage            = $data['dosage'];
            $produit->seuil_min         = $data['seuil_min'];
            $produit->seuil_max         = $data['seuil_max'];
            $produit->prix              = $data['prix'];
            $produit->stock             = $data['stock'];
            $produit->etat              = "actif";

            if (!$produit->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Produit créé avec succès']);
            return $this->view->partial("layouts/flash");
        }
        Phalcon\Tag::setDefaults(array(
                    "etat"  => "actif"
            ));
        $this->view->partial('produit/createProduit');
    }

    public function editProduitAction($id) {
        $this->view->disable();
        $form = new ProduitForm($this->trans);
        $this->view->produit_id = $id;
        $this->view->form_action = 'edit';
        $produit = Produit::findFirst($id);
        if(!$produit){
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
            $produit->libelle = $data['libelle'];
            $produit->type_produit_id   = ($data['type_produit_id'] != "") ? $data['type_produit_id'] : null;
            $produit->forme_produit_id  = ($data['forme_produit_id'] != "") ? $data['forme_produit_id'] : null;
            $produit->classe_therapeutique_id = ($data['classe_therapeutique_id'] != "") ? $data['classe_therapeutique_id'] : null;
            $produit->unite_vente = $data['unite_vente'];
            $produit->presentation = $data['presentation'];
            $produit->dosage = $data['dosage'];
            $produit->seuil_min = $data['seuil_min'];
            $produit->seuil_max = $data['seuil_max'];
            $produit->prix = $data['prix'];
            $produit->stock = $data['stock'];
            $produit->etat = "actif";
            if (!$produit->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return $this->view->partial("layouts/flash");
            }
            $this->flash->success($this->trans['Produit modifié avec succès']);
            return $this->view->partial("layouts/flash");
        } else {

            $this->view->form = $form;
            Phalcon\Tag::setDefaults(array(
                    "libelle" => $produit->libelle,
                    "type_produit_id" => $produit->type_produit_id,
                    "forme_produit_id" => $produit->forme_produit_id,
                    "forme_produit_id" => $produit->forme_produit_id,
                    "classe_therapeutique_id" => $produit->classe_therapeutique_id,
                    "unite_vente" => $produit->unite_vente,
                    "presentation" => $produit->presentation,
                    "dosage" => $produit->dosage,
                    "seuil_min" => $produit->seuil_min,
                    "seuil_max" => $produit->seuil_max,
                    "prix" => $produit->prix,
                    "stock" => $produit->stock,
                    "etat"  => $produit->etat,
            ));
            $this->view->partial("produit/createProduit");
        }
    }

    public function deleteProduitAction($id) {
        $this->view->disable();

        $produit = Produit::findFirst($id);
        if(!$produit){
             echo 0;exit();
        }

        if ($this->request->isAjax()) {
            if (!$produit->delete()) {
               echo 0;exit();
            }
            else{
               echo 1;exit();
            }
        }
        echo 0;exit();
    }

    public function ajaxProduitAction() {
        $this->view->disable();
        
        $builder = $this->modelsManager->createBuilder();
        $produits = $builder->columns('produit.id, produit.libelle, produit.seuil_max, produit.dosage, formeProduit.libelle as forme_libelle')
            ->addfrom('Produit', 'produit')
            ->join('FormeProduit', 'formeProduit.id = produit.forme_produit_id', 'formeProduit', 'LEFT');

        $produits = $builder->getQuery()->execute();
        
        $rs = array();
        for($i = 0; $i < count($produits); $i++) {
            $rs[$i]['id'] = json_encode($produits[$i], JSON_PRETTY_PRINT);
            $rs[$i]['libelle'] = $produits[$i]->libelle . "-" . $produits[$i]->forme_libelle . "-" . $produits[$i]->dosage;
        }
        
        echo json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function ajaxProduitCaisseAction($point_distribution_id = 0) {
        $this->view->disable();

        if($point_distribution_id == 0){
            $rs = [];
            $rs[0]['id'] = 0;
            $rs[0]['libelle'] = "Veuillez choisir un point de vente";
            echo json_encode($rs, JSON_PRETTY_PRINT);
            exit();
        }
        
        $builder = $this->modelsManager->createBuilder();
        $produits = $builder->columns('SUM(stockPointDistribution.reste) as stockPointDeVente, produit.id, produit.libelle, produit.stock, produit.prix, produit.dosage, formeProduit.libelle as forme_libelle')
            ->addfrom('Produit', 'produit')
            ->join('FormeProduit', 'formeProduit.id = produit.forme_produit_id', 'formeProduit', 'LEFT')
            ->join('StockPointDistribution', 'stockPointDistribution.produit_id = produit.id', 'stockPointDistribution', 'INNER')
            ->where('stockPointDistribution.point_distribution_id = :id:', array('id' => $point_distribution_id ))
            ->groupBy('produit.id, produit.libelle, produit.stock, produit.prix, produit.dosage, formeProduit.libelle');

        $produits = $builder->getQuery()->execute();

        $rs = [];
        for($i = 0; $i < count($produits); $i++) {
            $rs[$i]['id'] = $produits[$i]->id."|".$produits[$i]->prix."|".$produits[$i]->stockPointDeVente;
            $rs[$i]['libelle'] = $produits[$i]->libelle . "-" . $produits[$i]->forme_libelle . "-" . $produits[$i]->dosage . " | Stock: " . $produits[$i]->stockPointDeVente . " | Prix: " .$produits[$i]->prix . "F CFA";
        }
        
        echo json_encode($rs, JSON_PRETTY_PRINT);
    }

    public function ficheAction($produit_id = 0){
        
        $produit_id = ($this->request->get("produit_id"))  ? $this->request->get("produit_id") : $produit_id;

        if($produit_id == 0 || $produit_id == ""){
            return $this->forward("produit/index");
        }

        if($this->request->get("date1")){
            $data = $this->request->get();
            if($data['date1'] != "" && $data['date2'] != ""){
                $date1 = $data['date1'];
                $date2 = $data['date2'];
            }
        }
        else{
            $date1 = date('Y-m-d',strtotime("1 ". date('F Y')));
            $date2 = date("Y-m-d");
        }

        $conditions = " 1 = 1 ";
        $bind       = [];
        $conditions .=  " AND produit_id =  :produit_id:";
        $bind["produit_id"] = $produit_id;

        Phalcon\Tag::setDefaults(array(
                "produit_id" => $produit_id,
                "date1" => $date1,
                "date2" => $date2
        ));

        //Le produit
        $produit = array();
        $rsProduit = Produit::findFirst($produit_id);
        $produit['id']           = $rsProduit->id;
        $produit['libelle']      = $rsProduit->libelle;

        $produit['type']         = ( $tmp = $rsProduit->getTypeProduit() ) ? $tmp->libelle : "";
        $produit['forme']        = ( $tmp = $rsProduit->getFormeProduit() ) ? $tmp->libelle : "";
        $produit['classe_th']    = ( $tmp = $rsProduit->getClasseTherapeutique() ) ? $tmp->libelle : "";

        $produit['unite_vente']  = $rsProduit->unite_vente;
        $produit['presentation'] = $rsProduit->presentation;
        $produit['dosage']       = $rsProduit->dosage;
        $produit['seuil_min']    = $rsProduit->seuil_min;
        $produit['seuil_max']    = $rsProduit->seuil_max;
        $produit['prix']         = $rsProduit->prix;
        $produit['stock']        = $rsProduit->stock;
        $produit['etat']         = $rsProduit->etat;

        $this->view->produit = $produit;

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
            ->where('recuMedicamentDetails.produit_id = :id: AND date(recuMedicament.date) between :date1: and :date2:' , array('id' => $produit_id, 'date1' => $date1, 'date2' => $date2))
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
            ->where('ajustement.produit_id = :id: AND ajustement.type = "perte" AND DATE(ajustement.date) between :date1: and :date2:' , array('id' => $produit_id, 'date1' => $date1, 'date2' => $date2))
            ->getQuery()->execute();
        $this->view->rs_perte = (count($req)>0) ? $req[0]->summe : 0;

        //ajout
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns('IFNULL(sum(ajustement.quantite), 0) as summe')
            ->addfrom('Ajustement', 'ajustement')
            ->where('ajustement.produit_id = :id: AND ajustement.type = "ajout" AND DATE(ajustement.date) between :date1: and :date2:' , array('id' => $produit_id, 'date1' => $date1, 'date2' => $date2))
            ->getQuery()->execute();
        $this->view->rs_ajout = (count($req)>0) ? $req[0]->summe : 0;

        //Ruprure
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns('IFNULL(COUNT(ruptureStock.id), 0) as nbr')
            ->addfrom('RuptureStock', 'ruptureStock')
            ->where('ruptureStock.produit_id = :id: AND DATE(ruptureStock.date_rupture) between :date1: and :date2:' , array('id' => $produit_id, 'date1' => $date1, 'date2' => $date2))
            ->getQuery()->execute();
        $this->view->rupture = (count($req)>0) ? $req[0]->nbr : 0;

        //Ruprure Jour Total
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("DATEDIFF(IFNULL(ruptureStock.date_appro, '" . date("Y-m-d") . "'), ruptureStock.date_rupture) as nbr")
            ->addfrom('RuptureStock', 'ruptureStock')
            ->where('ruptureStock.produit_id = :id: AND DATE(ruptureStock.date_rupture) between :date1: and :date2:' , array('id' => $produit_id, 'date1' => $date1, 'date2' => $date2))
            ->getQuery()->execute();
        $this->view->ruptureJourTotal = (count($req)>0) ? $req[0]->nbr : 0;

        //Consommation moyenne
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns('IFNULL(AVG(recuMedicamentDetails.quantite), 0) as summe')
            ->addfrom('RecuMedicamentDetails', 'recuMedicamentDetails')
            ->join('RecuMedicament', 'recuMedicament.id = recuMedicamentDetails.recu_medicament_id', 'recuMedicament', 'INNER')
            ->where('recuMedicamentDetails.produit_id = :id: AND date(recuMedicament.date) between :date1: and :date2:' , array('id' => $produit_id, 'date1' => $date1, 'date2' => $date2))
            ->getQuery()->execute();
        $this->view->consoMoyenn = (count($req)>0) ? $req[0]->summe : 0;

        //Cosommation mensuelle
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("YEAR(recuMedicament.date) as annee, 
                MONTH(recuMedicament.date) as mois_chiffre, 
                MONTHNAME(recuMedicament.date) as mois, 
                SUM(recuMedicamentDetails.montant_patient) as montant, 
                AVG(recuMedicamentDetails.quantite) as moyenne, 
                SUM(recuMedicamentDetails.quantite) as total")
            ->addfrom('RecuMedicamentDetails', 'recuMedicamentDetails')
            ->join('RecuMedicament', 'recuMedicament.id = recuMedicamentDetails.recu_medicament_id', 'recuMedicament', 'INNER')
            ->andWhere( 'recuMedicamentDetails.produit_id = :id: AND recuMedicament.etat = 1 AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('id' => $produit_id, 'date1' => date('Y-m-d',strtotime("-12 months")), 'date2' => date("Y-m-d")) )
            ->groupBy('annee, mois, mois_chiffre')
            ->orderBy('annee asc, mois_chiffre ASC');
        $rq = $builder->getQuery()->execute();
        $rs = array();
        for ($i = 0; $i < count($rq); $i++) {
            $rq[$i]->color = $this->colorsPalette[$i];
            $rs[$i]['mois'] = $rq[$i]->mois;
            $rs[$i]['montant'] = $rq[$i]->montant;
            $rs[$i]['moyenne'] = $rq[$i]->moyenne;
            $rs[$i]['total'] = $rq[$i]->total;
        }
        $this->view->consoMensuel = json_encode($rs, JSON_PRETTY_PRINT);

        //Stock point de distribution
        $stockPointDistribution = StockPointDistribution::find( array($conditions, "bind" => $bind) );
        $rs = [];
        for($i = 0; $i < count($stockPointDistribution); $i++) {
            $rs[$i]['id']                   = $stockPointDistribution[$i]->id;
            $rs[$i]['point_distribution']   = $stockPointDistribution[$i]->getPointDistribution()->libelle;
            $rs[$i]['lot']                  = $stockPointDistribution[$i]->lot;
            $rs[$i]['stock']                = $stockPointDistribution[$i]->stock;
            $rs[$i]['reste']                = $stockPointDistribution[$i]->reste;
            $rs[$i]['date_peremption']      = $stockPointDistribution[$i]->date_peremption;
        }
        $this->view->stockPointDeVente  = json_encode($rs, JSON_PRETTY_PRINT);

        //Historique des approvisionement
        $approvisionnement = Approvisionnement::find( array('produit_id = :id: AND DATE(date) between :date1: and :date2:', 
                'bind' => array('id' => $produit_id, 'date1' => $date1, 'date2' => $date2),
                'order' => "date asc" ) );
        $rs = [];
        for($i = 0; $i < count($approvisionnement); $i++) {
            $rs[$i]['id']                   = $approvisionnement[$i]->id;
            $rs[$i]['date']                 = $approvisionnement[$i]->date;
            $rs[$i]['motif']                = $approvisionnement[$i]->motif;
            $rs[$i]['lot']                  = $approvisionnement[$i]->lot;
            $rs[$i]['date_peremption']      = $approvisionnement[$i]->date_peremption;
            $rs[$i]['quantite']             = $approvisionnement[$i]->quantite;
        }
        $this->view->approvisionnement  = json_encode($rs, JSON_PRETTY_PRINT);

        //Fiche de pointage detaillée
        $transactionProduit = TransactionProduit::find( array('produit_id = :id: AND DATE(created) between :date1: and :date2:', 
                                        'bind' => array('id' => $produit_id, 'date1' => $date1, 'date2' => $date2),
                                        'order' => "created asc"
                                        ) );
        $rs = [];
        for($i = 0; $i < count($transactionProduit); $i++) {
            $rs[$i]['id']               = $transactionProduit[$i]->id;
            $rs[$i]['date']             = $transactionProduit[$i]->created;
            $rs[$i]['quantite']         = $transactionProduit[$i]->quantite;
            $rs[$i]['type']             = $transactionProduit[$i]->type;
            $rs[$i]['stock_g_avant']    = $transactionProduit[$i]->stock_g_avant;
            $rs[$i]['stock_g_apres']    = $transactionProduit[$i]->stock_g_apres;
            $rs[$i]['stock_pv_avant']   = $transactionProduit[$i]->stock_pv_avant;
            $rs[$i]['stock_pv_apres']   = $transactionProduit[$i]->stock_pv_apres;
            $rs[$i]['lot']              = $transactionProduit[$i]->lot;
            $rs[$i]['date_peremption']  = $transactionProduit[$i]->date_peremption;
            $rs[$i]['operation']        = $transactionProduit[$i]->operation;

            $rs[$i]['point_distribution']        = $transactionProduit[$i]->getPointDistribution()->libelle;
        }
        $this->view->transactionProduit  = json_encode($rs, JSON_PRETTY_PRINT);
    }
}
