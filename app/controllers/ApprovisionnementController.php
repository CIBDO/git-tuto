<?php

/**
 * ReceptionController
 *
 */
class ApprovisionnementController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->tag->appendTitle($this->trans["Approvisionnement"]);
        if($this->view->language == "fr"){
            $langue = "fr-FR";
        }
        else{
            $langue = "en-US";
        }
        $this->view->langue = $langue;
    }

    public function indexAction($mode = "approvisionnement") {       
       
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
            $approvisionnement = Approvisionnement::find( array($conditions, "order" => "id asc", "bind" => $bind) );
        }
        else{
           $approvisionnement = Approvisionnement::find(array("order" => "id asc"));
        }

        $rs = [];
        for($i = 0; $i < count($approvisionnement); $i++) {
            $rs[$i]['id']                   = $approvisionnement[$i]->id;
            $rs[$i]['motif']                = $approvisionnement[$i]->motif;
            $rs[$i]['date']                 = $approvisionnement[$i]->date;
            $rs[$i]['point_distribution']   = $approvisionnement[$i]->getPointDistribution()->libelle;
            $rs[$i]['lot']                  = $approvisionnement[$i]->lot;
            $rs[$i]['quantite']             = $approvisionnement[$i]->quantite;
            $rs[$i]['date_peremption']             = $approvisionnement[$i]->date_peremption;

            $produit                     = $this->getProduitInfos($approvisionnement[$i]->getProduit());
            $rs[$i]['produit_id']        = $produit['id'];
            $rs[$i]['produit_libelle']   = $produit['libelle'];
            $rs[$i]['produit_dosage']    = $produit['dosage'];
            $rs[$i]['produit_type']      = $produit['type'];
            $rs[$i]['produit_forme']     = $produit['forme'];
            $rs[$i]['produit_classe_th'] = $produit['classe_th'];

            if($approvisionnement[$i]->reception_id != "" && $approvisionnement[$i]->reception_id != null){
                $tmpReception                = $approvisionnement[$i]->getReception();
                $rs[$i]['reception_id']      = $tmpReception->id;
                $rs[$i]['reception_objet']   = $tmpReception->objet;
                $rs[$i]['reception_date']    = $tmpReception->date;
            }
            else{
                $rs[$i]['reception_id']      = "";
                $rs[$i]['reception_objet']   = "";
                $rs[$i]['reception_date']    = "";
            }

        }

        /*print json_encode($rs, JSON_PRETTY_PRINT);exit();
        var_dump($rs);exit();*/
        $this->view->mode              = "approvisionnement";
        $this->view->approvisionnements = json_encode($rs, JSON_PRETTY_PRINT);
    }

}
