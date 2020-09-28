<?php

use Phalcon\Mvc\Model\Resultset;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

class PatientsController extends ControllerBase
{

    public function initialize()
    {
        $this->tag->setTitle('Dossier Patient');
        parent::initialize();
    }


    public function indexAction()
    {

        if ($this->request->isGet()) {
            $data = $this->request->get();


            $conditions = "";
            $bind = [];

            //cas de de la recherche par date
            if (isset($data['date1']) && $data['date1'] != "" && $data['date2'] != "") {
                $conditions .= "DATE(date_creation) between :date1: AND :date2:";
                $bind['date1'] = $data['date1'];
                $bind['date2'] = $data['date2'];

                Phalcon\Tag::setDefaults(array(
                    "date1" => $data['date1'],
                    "date2" => $data['date2']
                ));
            }

            foreach ($data as $key => $value) {
                if ($key == "date1" || $key == "date2" || $key == "_url" || $key == "PHPSESSID")
                    continue;
                if ($value != ""):
                    $conditions .= ($conditions == "") ? $key . " =  :" . $key . ":" : " AND " . $key . " =  :" . $key . ":";
                    $bind[$key] = $value;
                endif;
            }

            $patients = Patients::find(array($conditions, "bind" => $bind));
        } else {
            $patients = [];
        }

        $rs = [];
        for ($i = 0, $iMax = count($patients); $i < $iMax; $i++) {
            $rs[$i]['id'] = $patients[$i]->id;
            $rs[$i]['id_technique'] = $patients[$i]->id_technique;
            $rs[$i]['nom'] = $patients[$i]->nom;
            $rs[$i]['prenom'] = $patients[$i]->prenom;
            $rs[$i]['prenom2'] = $patients[$i]->prenom2;
            $rs[$i]['date_naissance'] = $patients[$i]->date_naissance;
            $rs[$i]['sexe'] = $patients[$i]->sexe;
            $rs[$i]['adresse'] = $patients[$i]->adresse;
            $rs[$i]['residence'] = ($patients[$i]->residence_id != null) ? $patients[$i]->getResidence()->libelle : "";
            $rs[$i]['profession'] = $patients[$i]->profession;
            $rs[$i]['telephone'] = $patients[$i]->telephone;
            $rs[$i]['telephone2'] = $patients[$i]->telephone2;
            $rs[$i]['email'] = $patients[$i]->email;
        }

        $this->view->residences = Residence::find();
        $this->view->patients = json_encode($rs, JSON_PRETTY_PRINT);

    }

    public function dossierAction($id)
    {

        $patient = Patients::findFirst($id);
//        var_dump(OnaApi::getFormSuivi('543955', ['id_p' => $patient->id_technique]));exit();
        if (!$patient) {
            $msg = $this->trans['on_error'];
            $this->flash->error($msg);
            return;
        }
        $patient_assurance = [];
        foreach ($patient->getPatientsAssurance() as $k => $v) {
            $patient_assurance[$k]['id'] = $v->id;
            $patient_assurance[$k]['numero'] = $v->numero;
            $patient_assurance[$k]['ogd'] = $v->ogd;
            $patient_assurance[$k]['beneficiaire'] = $v->beneficiaire;
            $patient_assurance[$k]['autres_infos'] = $v->autres_infos;
            $patient_assurance[$k]['default'] = $v->default;
            $patient_assurance[$k]['organisme'] = $v->getTypeAssurance();
        }

        $patient_antecedant = [];
        foreach ($patient->getPatientsAntecedant() as $k => $v) {
            $patient_antecedant[$k]['id'] = $v->id;
            $patient_antecedant[$k]['type'] = $v->type;
            $patient_antecedant[$k]['libelle'] = $v->libelle;
            if ($v->niveau == "normal") {
                $patient_antecedant[$k]['niveau'] = "primary";
            }
            if ($v->niveau == "moyen") {
                $patient_antecedant[$k]['niveau'] = "warning";
            }
            if ($v->niveau == "important") {
                $patient_antecedant[$k]['niveau'] = "danger";
            }
        }

        $patient_timeline = [];

        $tmpConsultation = [];
        foreach ($patient->getDossiersConsultations(array("order" => "date_creation DESC")) as $k => $v) {
            $tmpConsultation[$k]['id'] = $v->id;
            $tmpConsultation[$k]['date'] = $v->date_creation;
            $tmpConsultation[$k]['type'] = "consultation";
            $tmpConsultation[$k]['scope'] = "cs";
            $tmpConsultation[$k]['titre'] = "Dossier de consultation";
            $tmpConsultation[$k]['body'] = array();
            $tmpConsultation[$k]['url'] = "consultation/consultation/" . $id;
            $patient_timeline[] = $tmpConsultation[$k];

            //Suivi
            $tmpSuivi = [];
            foreach ($v->getConsultations(array("order" => "date_creation DESC")) as $h => $hv) {
                $tmpSuivi[$h]['id'] = $hv->id;
                $tmpSuivi[$h]['date'] = $hv->date_creation;
                $tmpSuivi[$h]['type'] = "suivi";
                $tmpSuivi[$h]['scope'] = "cs";
                $tmpSuivi[$h]['titre'] = "Dossier de suivi";
                $tmpSuivi[$h]['body'] = array();
                $tmpSuivi[$h]['url'] = "consultation/consultation/" . $id;
                $patient_timeline[] = $tmpSuivi[$h];
            }
        }

        $tmpLabo = [];
        foreach ($patient->getLaboDemandes(array("order" => "date DESC")) as $k => $v) {
            $tmpLabo[$k]['id'] = $v->id;
            $tmpLabo[$k]['date'] = $v->date;
            $tmpLabo[$k]['type'] = "labo";
            $tmpLabo[$k]['scope'] = "labo";
            $tmpLabo[$k]['titre'] = "Dossier d'analyse medicale";
            $tmpLabo[$k]['body'] = array("Paillasse" => $v->paillasse,
                "Provenance" => $v->provenance,
                "Prescripteur" => $v->prescripteur
            );
            $tmpLabo[$k]['url'] = "labo_demandes/dossier/" . $id;
            $patient_timeline[] = $tmpLabo[$k];
        }

        $tmpImagerie = [];
        foreach ($patient->getImgDemandes(array("order" => "date DESC")) as $k => $v) {
            $tmpImagerie[$k]['id'] = $v->id;
            $tmpImagerie[$k]['date'] = $v->date;
            $tmpImagerie[$k]['type'] = "imagerie";
            $tmpImagerie[$k]['scope'] = "img";
            $tmpImagerie[$k]['titre'] = "Dossier d'imagerie medicale";
            $tmpImagerie[$k]['body'] = array("Provenance" => $v->provenance,
                "Prescripteur" => $v->prescripteur
            );
            $tmpImagerie[$k]['url'] = "img_demandes/dossier/" . $id;
            $patient_timeline[] = $tmpImagerie[$k];
        }

        $patient_donnees_Hopital = [];
        foreach ($patient->getDonneesHopital() as $k => $v) {
            $patient_donnees_Hopital[$k]['id'] = $v->id;
            $patient_donnees_Hopital[$k]['code_asc'] = $v->code_asc;
            $patient_donnees_Hopital[$k]['commentaire'] = $v->commentaire;
            $patient_donnees_Hopital[$k]['date_rdv'] = date('d-m-Y', strtotime($v->date_rdv));
            $patient_donnees_Hopital[$k]['created'] = $v->created;
        }


        usort($patient_timeline, function ($a, $b) {
            $t1 = strtotime($a['date']);
            $t2 = strtotime($b['date']);
            return $t2 - $t1;
        });
        $this->view->patient_timeline = $patient_timeline;

        $this->view->patient_antecedant = $patient_antecedant;
        $this->view->patient = $patient;
        $this->view->patient_residence = ($tmp = $patient->getResidence()) ? $tmp->libelle : "";
        $this->view->patient_asc = ($tmp = $patient->getAsc()) ? $tmp->code_asc . ' ' . $tmp->prenom . ' ' . $tmp->nom : "";
        $this->view->patient_assurance = $patient_assurance;
        $this->view->patient_donnees_Hopital = $patient_donnees_Hopital;
        $suivis = (count($response = OnaApi::getFormSuivi('544798', ['id_p' => $patient->id_technique]))>0)? $response:[];
//        var_dump($suivis);exit();
        $this->view->patient_donnees_asc = $suivis;

    }

    public function formAction($id = 0)
    {
        $form = new PatientsForm($this->trans);
        $this->view->form = $form;

        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if (!$form->isValid($data)) {
                $this->flash->error($this->getFormMessages($form));
                return;
            }

            if ($data['id'] > 0) {
                $patient = Patients::findFirst($data['id']);
                if (!$patient) {
                    $msg = $this->trans['on_error'];
                    $this->flash->error($msg);
                    return;
                }
                Phalcon\Tag::setDefaults(array(
                    "id" => $patient->id,
                    "id_technique" => $patient->id_technique,
                    "nom" => $patient->nom,
                    "prenom" => $patient->prenom,
                    "prenom2" => $patient->prenom2,
                    "nom_conjoint" => $patient->nom_conjoint,
                    "contact_conjoint" => $patient->contact_conjoint,
                    "nom_pere" => $patient->nom_pere,
                    "contact_pere" => $patient->contact_pere,
                    "nom_mere" => $patient->nom_mere,
                    "contact_mere" => $patient->contact_mere,
                    "personne_a_prev" => $patient->personne_a_prev,
                    "nom_jeune_fille" => $patient->nom_jeune_fille,
                    "date_naissance" => $patient->date_naissance,
                    "sexe" => $patient->sexe,
                    "adresse" => $patient->adresse,
                    "residence_id" => $patient->residence_id,
                    "ethnie" => $patient->ethnie,
                    "profession" => $patient->profession,
                    "telephone" => $patient->telephone,
                    "telephone2" => $patient->telephone2,
                    "email" => $patient->email,
                    "autre_infos" => $patient->autre_infos
                ));
            } else {
                $patient = new Patients();
            }


            $patient->nom = strtoupper($data['nom']);
            $patient->id_technique = $data['id_technique'];
            $patient->prenom = $data['prenom'];
            $patient->prenom2 = $data['prenom2'];
            $patient->nom_conjoint = $data['nom_conjoint'];
            $patient->contact_conjoint = $data['contact_conjoint'];
            $patient->nom_pere = $data['nom_pere'];
            $patient->contact_pere = $data['contact_pere'];
            $patient->nom_mere = $data['nom_mere'];
            $patient->contact_mere = $data['contact_mere'];
            $patient->personne_a_prev = $data['personne_a_prev'];
            $patient->nom_jeune_fille = $data['nom_jeune_fille'];
            $patient->date_naissance = $data['date_naissance'];
            $patient->sexe = $data['sexe'];
            $patient->adresse = $data['adresse'];
            if ($data['residence_id'] != "") {
                $patient->residence_id = $data['residence_id'];
            }
            if ($data['asc_id'] != "") {
                $patient->asc_id = $data['asc_id'];
            }
            $patient->ethnie = $data['ethnie'];
            $patient->profession = $data['profession'];
            $patient->telephone = $data['telephone'];
            $patient->telephone2 = $data['telephone2'];
            $patient->email = $data['email'];
            $patient->autre_infos = $data['autre_infos'];
            $patient->date_creation = date('Y-m-d H:i:s', time());

            if (!$patient->save()) {
                $msg = $this->trans['on_error'];
                $this->flash->error($msg);
                return;
            }
            $this->flash->success($this->trans['Enregistrement effectué avec succès']);
            $this->response->redirect("patients/dossier/" . $patient->id);
        } else {
            if ($id > 0) {
                $patient = Patients::findFirst($id);
                if (!$patient) {
                    $msg = $this->trans['on_error'];
                    $this->flash->error($msg);
                    return;
                }
                Phalcon\Tag::setDefaults(array(
                    "id" => $patient->id,
                    "id_technique" => $patient->id_technique,
                    "nom" => $patient->nom,
                    "prenom" => $patient->prenom,
                    "prenom2" => $patient->prenom2,
                    "nom_conjoint" => $patient->nom_conjoint,
                    "contact_conjoint" => $patient->contact_conjoint,
                    "nom_pere" => $patient->nom_pere,
                    "contact_pere" => $patient->contact_pere,
                    "nom_mere" => $patient->nom_mere,
                    "contact_mere" => $patient->contact_mere,
                    "personne_a_prev" => $patient->personne_a_prev,
                    "nom_jeune_fille" => $patient->nom_jeune_fille,
                    "date_naissance" => $patient->date_naissance,
                    "sexe" => $patient->sexe,
                    "adresse" => $patient->adresse,
                    "residence_id" => $patient->residence_id,
                    "asc_id" => $patient->asc_id,
                    "ethnie" => $patient->ethnie,
                    "profession" => $patient->profession,
                    "telephone" => $patient->telephone,
                    "telephone2" => $patient->telephone2,
                    "email" => $patient->email,
                    "autre_infos" => $patient->autre_infos
                ));
            }
        }
    }


    public function assurancePopoverAction($patients_id = 0, $id = 0)
    {

        $this->view->disable();

        if ($id != 0) {
            $current = PatientsAssurance::findFirst($id);
            Phalcon\Tag::setDefaults(array(
                "type_assurance_id" => $current->type_assurance_id,
                "numero" => $current->numero,
                "ogd" => $current->ogd,
                "beneficiaire" => $current->beneficiaire,
                "autres_infos" => $current->autres_infos,
                "default" => $current->default
            ));
        }

        $typeAssurance = TypeAssurance::find();

        $this->view->typeAssurance = $typeAssurance;
        $this->view->id = $id;
        $this->view->patients_id = $patients_id;

        $this->view->partial("patients/assurancePopover");
    }

    public function deleteAssuranceAction($id)
    {
        $this->view->disable();

        $current = PatientsAssurance::findFirst($id);
        if (!$current) {
            echo 0;
            exit();
        }

        if ($this->request->isAjax()) {
            if (!$current->delete()) {
                echo 0;
                exit();
            } else {
                echo 1;
                exit();
            }
        }
        echo 0;
        exit();
    }

    public function addAssuranceAction($id = 0)
    {
        $this->view->disable();

        $data = $this->request->getPost();
        //print_r($id);exit();
        if ($this->request->isAjax()) {
            if ($id != 0) {
                $current = PatientsAssurance::findFirst($id);
            } else {
                $current = new PatientsAssurance();
            }

            if (!$current) {
                echo 0;
                exit();
            }
            //print_r($data);
            $current->patients_id = $data['patients_id'];
            $current->type_assurance_id = $data['type_assurance_id'];
            $current->numero = $data['numero'];
            $current->ogd = $data['ogd'];
            $current->beneficiaire = $data['beneficiaire'];
            $current->autres_infos = $data['autres_infos'];
            if (isset($data['default']) && $data['default'] == "1") {
                $query = $this->modelsManager->createQuery("UPDATE PatientsAssurance SET default = :d: WHERE patients_id = :p:");
                $exec = $query->execute(
                    array('d' => 0, 'p' => $data['patients_id'])
                );
                $current->default = 1;
            }

            if (!$current->save()) {
                //print_r($current->getMessages());exit();
                echo 0;
                exit();
            } else {
                echo $current->id;
                exit();
            }
            echo 0;
            exit();
        }
    }

    public function getAssuranceInfosAction($patients_id = 0, $assurance_id = 0)
    {
        $this->view->disable();

        if ($patients_id == 0 || $assurance_id == 0) {
            echo json_encode(array(), JSON_PRETTY_PRINT);
            exit();
        }

        $current = PatientsAssurance::find("patients_id = " . $patients_id . " AND type_assurance_id = " . $assurance_id);
        if (count($current) > 0) {
            echo json_encode($current[0], JSON_PRETTY_PRINT);
            exit();
        } else {
            echo json_encode(array(), JSON_PRETTY_PRINT);
            exit();
        }
    }

    public function ajaxPatientAction()
    {
        $this->view->disable();

        $builder = $this->modelsManager->createBuilder();
        $patients = $builder->columns('patients.id, patients.id_technique, patients.prenom, patients.nom, patients.date_naissance, patients.sexe, patients.residence_id, patients.adresse, patients.telephone, patients.nom_pere, patients.nom_mere, patientsAssurance.numero as ass_numero, patientsAssurance.ogd as ass_ogd, patientsAssurance.beneficiaire as ass_beneficiaire, patientsAssurance.autres_infos as ass_autres_infos, patientsAssurance.default as ass_default, CONCAT(typeAssurance.id, "|", typeAssurance.taux) as ass_id')
            ->addfrom('Patients', 'patients')
            ->join('Residence', 'residence.id = patients.residence_id', 'residence', 'LEFT')
            ->join('PatientsAssurance', 'patientsAssurance.patients_id = patients.id', 'patientsAssurance', 'LEFT')
            ->join('TypeAssurance', 'patientsAssurance.type_assurance_id = typeAssurance.id', 'typeAssurance', 'LEFT')
            ->where(' 1 = 1 OR patientsAssurance.default = :d:', array('d' => 1));

        $patients = $builder->getQuery()->execute();

        $rs = array();
        for ($i = 0; $i < count($patients); $i++) {
            $rs[$i]['id'] = json_encode($patients[$i], JSON_PRETTY_PRINT);
            $rs[$i]['libelle'] = "ID:" . $patients[$i]->id . "-IDT:" . $patients[$i]->id_technique . "-NOM: " . $patients[$i]->prenom . " " . $patients[$i]->nom . "-NAISS:" . $patients[$i]->date_naissance . "-TEL:" . $patients[$i]->telephone . "-ADR:" . $patients[$i]->adresse . "-PERE:" . $patients[$i]->nom_pere . "-MERE:" . $patients[$i]->nom_mere;
        }

        echo json_encode($rs, JSON_PRETTY_PRINT);
    }


    public function dashboardAction()
    {

        if ($this->request->get("date1")) {
            $data = $this->request->get();
            if ($data['date1'] != "" && $data['date2'] != "") {
                $date1 = $data['date1'];
                $date2 = $data['date2'];
            }
        } else {
            $date1 = date('Y-m-d', strtotime("last Monday"));
            $date2 = date("Y-m-d");
        }

        Phalcon\Tag::setDefaults(array(
            "date1" => $date1,
            "date2" => $date2
        ));

        //Nombre total de patient
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(patients.id) as nbr")
            ->addfrom('Patients', 'patients')
            ->andWhere('date(patients.date_creation) between :date1: AND :date2:',
                array('date1' => $date1, 'date2' => $date2));
        $req = $builder->getQuery()->execute();
        $totalInitial = (count($req) > 0) ? $req[0]['nbr'] : 0;
        $this->view->totalPatient = (count($req) > 0) ? $req[0]['nbr'] : 0;

        //Nombre de patient par sexe
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("(IF(patients.sexe = 'f', 'Feminin', 'Masculin')) as sexe, COUNT(distinct patients.id)  as nbr")
            ->addfrom('Patients', 'patients')
            ->andWhere('date(patients.date_creation) between :date1: AND :date2:',
                array('date1' => $date1, 'date2' => $date2))
            ->groupBy('sexe');
        $req = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
            ->toArray();
        $this->view->patientParSexeGraph = json_encode($req, JSON_PRETTY_PRINT);

        //Nombre de patient par localité
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("residence.libelle as residence, COUNT(distinct patients.id)  as nbr")
            ->addfrom('Patients', 'patients')
            ->join('Residence', 'residence.id = patients.residence_id', 'residence', 'inner')
            ->andWhere('date(patients.date_creation) between :date1: AND :date2:',
                array('date1' => $date1, 'date2' => $date2))
            ->groupBy('residence');
        $req = $builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_OBJECTS)
            ->toArray();
        $this->view->patientParResidence = json_encode($req, JSON_PRETTY_PRINT);

        //Comparaison graphique entre nombre de patient par mois
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("YEAR(patients.date_creation) as annee, 
                MONTH(patients.date_creation) as mois_chiffre, 
                MONTHNAME(patients.date_creation) as mois, 
                COUNT(distinct patients.id) as nbr")
            ->addfrom('Patients', 'patients')
            ->andWhere('date(patients.date_creation) between :date1: AND :date2:',
                array('date1' => date('Y-m-d', strtotime("-12 months")), 'date2' => date("Y-m-d")))
            ->groupBy('annee, mois, mois_chiffre')
            ->orderBy('annee asc, mois_chiffre ASC');
        $req = $builder->getQuery()->execute();
        $rs = array();
        for ($i = 0; $i < count($req); $i++) {
            $rs[$i]['mois'] = $req[$i]->mois;
            $rs[$i]['nbr'] = $req[$i]->nbr;
        }
        $this->view->mensuelleInitialGraph = json_encode($rs, JSON_PRETTY_PRINT);

        //Nombre de patient par Tranche d'age
        $rsFinal = array();
        //0-5
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(patients.id) as nbr")
            ->addfrom('Patients', 'patients')
            ->andWhere(' ( date(patients.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 0 AND 5) ',
                array('date1' => $date1, 'date2' => $date2));
        $req = $builder->getQuery()->execute();
        $rsFinal[] = array("tranche" => "0-5", "patient" => $req[0]['nbr']);

        //6-10
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(patients.id) as nbr")
            ->addfrom('Patients', 'patients')
            ->andWhere(' ( date(patients.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 6 AND 10) ',
                array('date1' => $date1, 'date2' => $date2));
        $req = $builder->getQuery()->execute();
        $rsFinal[] = array("tranche" => "6-10", "patient" => $req[0]['nbr']);

        //11-15
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(patients.id) as nbr")
            ->addfrom('Patients', 'patients')
            ->andWhere(' ( date(patients.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 11 AND 15) ',
                array('date1' => $date1, 'date2' => $date2));
        $req = $builder->getQuery()->execute();
        $rsFinal[] = array("tranche" => "11-15", "patient" => $req[0]['nbr']);

        //16-20
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(patients.id) as nbr")
            ->addfrom('Patients', 'patients')
            ->andWhere(' ( date(patients.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 16 AND 20) ',
                array('date1' => $date1, 'date2' => $date2));
        $req = $builder->getQuery()->execute();
        $rsFinal[] = array("tranche" => "16-20", "patient" => $req[0]['nbr']);

        //21-30
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(patients.id) as nbr")
            ->addfrom('Patients', 'patients')
            ->andWhere(' ( date(patients.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 21 AND 30) ',
                array('date1' => $date1, 'date2' => $date2));
        $req = $builder->getQuery()->execute();
        $rsFinal[] = array("tranche" => "21-30", "patient" => $req[0]['nbr']);

        //31-50
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(patients.id) as nbr")
            ->addfrom('Patients', 'patients')
            ->andWhere(' ( date(patients.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  BETWEEN 31 AND 50) ',
                array('date1' => $date1, 'date2' => $date2));
        $req = $builder->getQuery()->execute();
        $rsFinal[] = array("tranche" => "31-50", "patient" => $req[0]['nbr']);

        //Plus de 50
        $builder = $this->modelsManager->createBuilder();
        $req = $builder->columns("count(patients.id) as nbr")
            ->addfrom('Patients', 'patients')
            ->andWhere(' ( date(patients.date_creation) between :date1: AND :date2: ) AND (DIALECT_GET_AGE(patients.date_naissance)  >= 51) ',
                array('date1' => $date1, 'date2' => $date2));
        $req = $builder->getQuery()->execute();
        $rsFinal[] = array("tranche" => "51 et +", "patient" => $req[0]['nbr']);

        $this->view->patientParTrancheAge = json_encode($rsFinal, JSON_PRETTY_PRINT);
    }

}
