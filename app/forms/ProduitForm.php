<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValid;
use Phalcon\Validation\Validator\Regex as RegexValidator;


class ProduitForm extends Form {

    public function initialize($trans) {
        //Libelle
        $libelle = new Text('libelle', ["class" => "form-control input-md", "required" => "required"]);
        $libelle->setLabel($trans['Libelle']);
        $libelle->addValidators(array(
            new PresenceOf(array(
                'message' => 'libelle is required'
            ))
        ));
        $libelle->setFilters(array('striptags'));
        $this->add($libelle);

        //unite_vente
        $unite_vente = new Text('unite_vente', ["class" => "form-control input-md"]);
        $unite_vente->setLabel($trans['Unité de vente']);
        $unite_vente->setFilters(array('striptags'));
        $this->add($unite_vente);

        //presentation
        $presentation = new Text('presentation', ["class" => "form-control input-md"]);
        $presentation->setLabel($trans['Unité de vente']);
        $presentation->setFilters(array('striptags'));
        $this->add($presentation);

        //dosage
        $dosage = new Text('dosage', ["class" => "form-control input-md"]);
        $dosage->setLabel($trans['Unité de vente']);
        $dosage->setFilters(array('striptags'));
        $this->add($dosage);

        //seuil_min
        $seuil_min = new Numeric('seuil_min', ["class" => "form-control input-md", "required" => "required"]);
        $seuil_min->setLabel($trans['Seuil min']);
        $seuil_min->addValidators(array(
            new PresenceOf(array(
                'message' => 'seuil_min is required'
            ))
        ));
        $seuil_min->setFilters(array('striptags'));
        $this->add($seuil_min);

        //seuil_max
        $seuil_max = new Numeric('seuil_max', ["class" => "form-control input-md", "required" => "required"]);
        $seuil_max->setLabel($trans['Seuil max']);
        $seuil_max->addValidators(array(
            new PresenceOf(array(
                'message' => 'seuil_max is required'
            ))
        ));
        $seuil_max->setFilters(array('striptags'));
        $this->add($seuil_max);

        //prix
        $prix = new Numeric('prix', ["class" => "form-control input-md"]);
        $prix->setLabel($trans['Prix de vente']);
        $prix->setFilters(array('striptags'));
        $this->add($prix);

        //stock
        $stock = new Numeric('stock', ["class" => "form-control input-md", "value" => "0", "readonly" => "readonly"]);
        $stock->setLabel($trans['Stock']);
        $stock->setFilters(array('striptags'));
        $this->add($stock);

        //type_produit_id   
        $type_produit_id = new Select( 'type_produit_id', 
                                    TypeProduit::find(),
                                    ['using' => array('id', 'libelle'), "class" => "form-control type_produit_id", "id" => "type_produit_id", "useEmpty" => true]
                                );
        $type_produit_id->setLabel($trans['Profil']);
        $type_produit_id->setFilters(array('striptags'));
        $this->add($type_produit_id);

        //classe_therapeutique_id   
        $classe_therapeutique_id = new Select( 'classe_therapeutique_id', 
                                    ClasseTherapeutique::find(),
                                    ['using' => array('id', 'libelle'), "class" => "form-control classe_therapeutique_id", "id" => "classe_therapeutique_id", "useEmpty" => true]
                                );
        $classe_therapeutique_id->setLabel($trans['Profil']);
        $classe_therapeutique_id->setFilters(array('striptags'));
        $this->add($classe_therapeutique_id);

        //forme_produit_id   
        $forme_produit_id = new Select( 'forme_produit_id', 
                                    FormeProduit::find(),
                                    ['using' => array('id', 'libelle'), "class" => "form-control forme_produit_id", "id" => "forme_produit_id", "useEmpty" => true]
                                );
        $forme_produit_id->setLabel($trans['Profil']);
        $forme_produit_id->setFilters(array('striptags'));
        $this->add($forme_produit_id);

        //etat
        $etat = new Select('etat', array(
                                            'actif'     => 'Actif',
                                            'inactif'   => 'Inactif'
                                        ),
                                         ["class" => "form-control", "id" => "etat", "required" => "required", "useEmpty" => true]);
        $etat->setLabel($trans['Type']);
        $etat->addValidators(array(
            new PresenceOf(array(
                'message' => 'Etat is required'
            ))
        ));
        $etat->setFilters(array('striptags'));
        $this->add($etat);
    }
}
