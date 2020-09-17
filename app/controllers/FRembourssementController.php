<?php

use Phalcon\Mvc\Model\Resultset;

/**
 * FRembourssementController
 *
 */
class FRembourssementController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Rembourssement"]);
        if($this->view->language == "fr"){
            $langue = "fr-FR";
        }
        else{
            $langue = "en-US";
        }
        $this->view->langue = $langue;
    }

    public function indexAction(){
        
        $typeAssurancelist = TypeAssurance::find(
                                array(  "columns"   => "id as id, CONCAT(libelle, ' - ', taux, '%') as libelle",
                                        "order"     => "libelle" ) );

        $this->view->typeAssurancelist   = $typeAssurancelist;


        $date1 = $date2 = date("Y-m-d");
        $type_assurance = 0;
        $name_assurance = "";
        $ogd            = "-1";
        if($this->request->get("date1")){
            $data = $this->request->get();
            if($data['date1'] != "" && $data['date2'] != ""){
                $date1 = $data['date1'];
                $date2 = $data['date2'];
            }
            $type_assurance = ($data['type_assurance'] != "") ? $data['type_assurance'] : 0;
            $name_assurance = $data['name_assurance'];
            $ogd = ($data['ogd'] != "") ? $data['ogd'] : "-1";
        }
        else{
            $date1 = $date2 = date("Y-m-d");
        }

        Phalcon\Tag::setDefaults(array(
                "date1" => $date1,
                "date2" => $date2,
                "type_assurance" => $type_assurance,
                "name_assurance" => $name_assurance,
                "ogd" => $ogd
        ));
        
        $this->view->date1          = $date1;
        $this->view->date2          = $date2;
        $this->view->type_assurance = $type_assurance;
        $this->view->name_assurance = $name_assurance;
        $this->view->ogd            = $ogd;

        $builder = $this->modelsManager->createBuilder();
        $tickets = $builder->columns("
                SUM(prestationsDetails.montant_normal) as montant_normal, 
                SUM(prestationsDetails.montant_patient) as montant_patient, 
                SUM(prestationsDetails.montant_normal - prestationsDetails.montant_patient ) as reste")
            ->addfrom('Prestations', 'prestations')
            ->join('PrestationsDetails', 'prestationsDetails.prestations_id = prestations.id', 'prestationsDetails', 'INNER')
            ->join('Actes', 'actes.id = prestationsDetails.actes_id', 'actes', 'INNER')
            ->join('Patients', 'patients.id = prestations.patients_id', 'patients', 'INNER')
            ->join('User', 'usercaissier.id = prestations.user_id', 'usercaissier', 'INNER')
            ->join('TypeAssurance', 'typeAssurance.id = prestations.type_assurance_id', 'typeAssurance', 'LEFT')
            ->andWhere( 'prestations.etat = 1 AND date(prestations.date) between :date1: AND :date2:', array('date1' => $date1, 'date2' => $date2) );
        if ($ogd != "" && $ogd != "-1") {
            $tickets = $builder->andWhere('prestations.ogd LIKE ?0', array("%{$ogd}%"));
        }
        
        if ($name_assurance != "") {
            $tickets = $builder->andWhere('typeAssurance.libelle LIKE ?0', array("%{$name_assurance}%"));
        }
        else{
            $tickets = $builder->andWhere( 'prestations.type_assurance_id = :type_assurance: ', array('type_assurance' => $type_assurance) );
        }

        $tickets = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();

        //print_r($tickets);exit();

        $this->view->tickets   = isset($tickets[0]) ? $tickets[0] : [];

        $builder = $this->modelsManager->createBuilder();
        $pharmacie = $builder->columns("
                SUM(recuMedicamentDetails.montant_normal) as montant_normal, 
                SUM(recuMedicamentDetails.montant_patient) as montant_patient, 
                SUM(recuMedicamentDetails.montant_normal - recuMedicamentDetails.montant_patient ) as reste")
             ->addfrom('RecuMedicament', 'recuMedicament')
            ->join('RecuMedicamentDetails', 'recuMedicamentDetails.recu_medicament_id = recuMedicament.id', 'recuMedicamentDetails', 'INNER')
            ->join('Produit', 'produit.id = recuMedicamentDetails.produit_id', 'produit', 'INNER')
            ->join('FormeProduit', 'formeProduit.id = produit.forme_produit_id', 'formeProduit', 'LEFT')
            ->join('Patients', 'patients.id = recuMedicament.patients_id', 'patients', 'INNER')
            ->join('User', 'usercaissier.id = recuMedicament.user_id', 'usercaissier', 'INNER')
            ->join('TypeAssurance', 'typeAssurance.id = recuMedicament.type_assurance_id', 'typeAssurance', 'LEFT')
            ->andWhere( 'recuMedicament.etat = 1 AND date(recuMedicament.date) between :date1: AND :date2:', array('date1' => $date1, 'date2' => $date2) );

        if ($ogd != "" && $ogd != "-1") {
            $pharmacie = $builder->andWhere('recuMedicament.ogd LIKE ?0', array("%{$ogd}%"));
        }
        if ($name_assurance != "") {
            $pharmacie = $builder->andWhere('typeAssurance.libelle LIKE ?0', array("%{$name_assurance}%"));
        }
        else{
            $pharmacie = $builder->andWhere( 'recuMedicament.type_assurance_id = :type_assurance: ', array('type_assurance' => $type_assurance) );
        }


        $pharmacie = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
                              ->toArray();

        //print_r($pharmacie);exit();

        $this->view->pharmacie   = isset($pharmacie[0]) ? $pharmacie[0] : [];

    }
}
