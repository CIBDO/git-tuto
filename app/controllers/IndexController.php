<?php

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

class IndexController extends ControllerBase {

    public function initialize() {
        $this->tag->setTitle('Dashboard');
        parent::initialize();
    }


    public function indexAction() {
        
        if($this->request->get("date1")){
            $data = $this->request->get();
            if($data['date1'] != "" && $data['date2'] != ""){
                $date1 = $data['date1'];
                $date2 = $data['date2'];
            }
        }
        else{
            $date1 = date('Y-m-d',strtotime("last Monday"));
            $date2 = date("Y-m-d");
        }

        Phalcon\Tag::setDefaults(array(
                "date1" => $date1,
                "date2" => $date2
        ));

        //Caisse ****************
        //Nombre de vente
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("count(prestations.id) as nbr")
            ->addfrom('Prestations', 'prestations')
            ->andWhere( 'date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->nbrVente = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //Montant encaissé
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(prestations.montant_patient) as montant")
            ->addfrom('Prestations', 'prestations')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->montantEncaisse = (count($rq)>0) ? $rq[0]['montant'] : 0;

        //Montant total
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(prestations.montant_normal + prestations.montant_difference) as montant")
            ->addfrom('Prestations', 'prestations')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->montantTotal = (count($rq)>0) ? $rq[0]['montant'] : 0;

        //Montant assureur
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(prestations.montant_restant) as montant")
            ->addfrom('Prestations', 'prestations')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->montantAssureur = (count($rq)>0) ? $rq[0]['montant'] : 0;

        //Nombre de vente annule
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("count(prestations.id) as nbr")
            ->addfrom('Prestations', 'prestations')
            ->andWhere( 'prestations.date_annulation IS NOT NULL AND date(prestations.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->nbrVenteAnnule = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //Pharmacie *****************
        //Nombre de vente
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("count(recuMedicament.id) as nbr")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->andWhere( 'date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->nbrVentePh = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //Nombre de vente annule
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("count(recuMedicament.id) as nbr")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->andWhere( 'recuMedicament.date_annulation IS NOT NULL AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->nbrVenteAnnulePh = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //Montant total
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(recuMedicament.montant_normal) as montant")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->andWhere( 'recuMedicament.etat = 1 AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->montantTotalPh = (count($rq)>0) ? $rq[0]['montant'] : 0;

        //Montant encaissé
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(recuMedicament.montant_patient) as montant")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->andWhere( 'recuMedicament.etat = 1 AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->montantEncaissePh = (count($rq)>0) ? $rq[0]['montant'] : 0;

        //Montant assureur
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(recuMedicament.montant_restant) as montant")
            ->addfrom('RecuMedicament', 'recuMedicament')
            ->andWhere( 'recuMedicament.etat = 1 AND date(recuMedicament.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->montantAssureurPh = (count($rq)>0) ? $rq[0]['montant'] : 0;

        //En dessous du seuil min
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("count(produit.id) as nbr")
            ->addfrom('Produit', 'produit')
            ->andWhere('produit.stock < produit.seuil_min');
        $rq = $builder->getQuery()->execute();
        $this->view->infSeuilMin = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //En rupture de stock
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("count(produit.id) as nbr")
            ->addfrom('Produit', 'produit')
            ->andWhere('produit.stock = 0');
        $rq = $builder->getQuery()->execute();
        $this->view->nbrRuptureStock = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //Lot proche de la peremption
        $stockPointDistribution = StockPointDistribution::find("reste > 0 AND date_peremption < '" . date('Y-m-d',strtotime("+3 months")) . "'");
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
        $this->view->nbrLotPeremption = count($rs);
        $this->view->lotPeremption = json_encode($rs, JSON_PRETTY_PRINT);


        //Finance ****************
        //operation depense 
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(foperation.montant) as nbr")
           ->addfrom('FOperation', 'foperation')
            ->join('FSousCompte', 'foperation.f_sous_compte_id = fsouscompte.id', 'fsouscompte', 'INNER')
            ->join('FCompte', 'fsouscompte.f_compte_id = fcompte.id', 'fcompte', 'INNER')
            ->andWhere( 'fcompte.type = "depense" AND date(foperation.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->opDepense = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //operation recette 
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(foperation.montant) as nbr")
           ->addfrom('FOperation', 'foperation')
            ->join('FSousCompte', 'foperation.f_sous_compte_id = fsouscompte.id', 'fsouscompte', 'INNER')
            ->join('FCompte', 'fsouscompte.f_compte_id = fcompte.id', 'fcompte', 'INNER')
            ->andWhere( 'fcompte.type = "recette" AND date(foperation.date) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $rq = $builder->getQuery()->execute();
        $this->view->opRecette = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //solde espece 
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("fsolde.montant as nbr")
           ->addfrom('FSolde', 'fsolde')
            ->andWhere( 'fsolde.f_banque_compte_id IS NULL');
        $rq = $builder->getQuery()->execute();
        $this->view->soldeEspece = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //solde banque 
        $builder = $this->modelsManager->createBuilder();
        $rq = $builder->columns("SUM(fsolde.montant) as nbr")
           ->addfrom('FSolde', 'fsolde')
            ->andWhere( 'fsolde.f_banque_compte_id IS NOT NULL');
        $rq = $builder->getQuery()->execute();
        $this->view->soldeBanque = (count($rq)>0) ? $rq[0]['nbr'] : 0;

        //Patient ****************
        //Nombre total de patient
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(patients.id) as nbr")
            ->addfrom('Patients', 'patients')
            ->andWhere( 'date(patients.date_creation) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $totalInitial = (count($req)>0) ? $req[0]['nbr'] : 0;
        $this->view->totalPatient = (count($req)>0) ? $req[0]['nbr'] : 0;


        //Consultation ************
        //Nombre total de visite
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(dossiersConsultations.id) as nbr")
            ->addfrom('DossiersConsultations', 'dossiersConsultations')
            ->andWhere( 'date(dossiersConsultations.date_creation) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $totalInitial = (count($req)>0) ? $req[0]['nbr'] : 0;
        $this->view->totalInitial = (count($req)>0) ? $req[0]['nbr'] : 0;

        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(consultations.id) as nbr")
            ->addfrom('Consultations', 'consultations')
            ->andWhere( 'date(consultations.date_creation) between :date1: AND :date2:',
                                         array('date1' => $date1, 'date2' => $date2) );
        $req = $builder->getQuery()->execute();
        $totalSuivi = (count($req)>0) ? $req[0]['nbr'] : 0;
        $this->view->totalSuivi = (count($req)>0) ? $req[0]['nbr'] : 0;
        $this->view->totalVisite = $totalInitial + $totalSuivi;
        
    }

    /*
     * Method use when click on language dropdown to set language
     * Language is on the session : get("language")
     */

    public function swipetranslationAction() {
        $language = $this->session->get("language");
        if ($language == "fr") {
            $this->session->remove("language");
            $this->session->set("language", 'en');
            $language = $this->session->get("language", 'en');
        }
        $this->view->language = $language;
    }

    public function checkDevice() {
        return $this->getDeviceInfos()['os']['name'];
    }

    /*
     * Get all hardware infos ( OS, Model & brand name & versions )
     */

    public function getDeviceInfos() {
        DeviceParserAbstract::setVersionTruncation(DeviceParserAbstract::VERSION_TRUNCATION_NONE);
        $dd = new DeviceDetector($this->request->getUserAgent());
        $dd->parse();
        if ($dd->isBot()) {
            return false;
        } else {
            $osInfo = $dd->getOs();
            $brand = $dd->getBrand();
            $model = $dd->getModel();
        }
        $data = array("os" => $osInfo, "brand" => $brand, "model" => $model);
        return $data;
    }

    /*
     * Set array for Labels for dashbord2.js
     */

    public function getLabels($dataSettlements) {
        $labels = [];
        foreach ($dataSettlements as $key => $value) {
            $labels[$key] = $value['dateConcat'];
        }
        $arrayclean = array_values(array_unique($labels));
        $arrayclean = array_reverse($arrayclean);
        return $arrayclean;
    }

}
