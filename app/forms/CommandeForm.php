<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Date;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValid;
use Phalcon\Validation\Validator\Regex as RegexValidator;


class CommandeForm extends Form {

    public function initialize($trans) {
        //objet
        $objet = new Text('objet', ["class" => "form-control input-md", "required" => "required"]);
        $objet->setLabel($trans['Objet']);
        $objet->addValidators(array(
            new PresenceOf(array(
                'message' => 'Object is required'
            ))
        ));
        $objet->setFilters(array('striptags'));
        $this->add($objet);

        //date
        $date = new Date('date', ["class" => "form-control input-md", "required" => "required"]);
        $date->setLabel($trans['Date']);
        $date->addValidators(array(
            new PresenceOf(array(
                'message' => 'Date is required'
            ))
        ));
        $date->setFilters(array('striptags'));
        $this->add($date);

        

        //Service
        $fournisseur_id = new Select( 'fournisseur_id', 
                                    Fournisseur::find(),
                                    ['using' => array('id', 'libelle'), "class" => "form-control", "id" => "fournisseur_id", "required" => "required", "useEmpty" => true]
                                );
        $fournisseur_id->setLabel($trans['Profil']);
        $fournisseur_id->addValidators(array(
            new PresenceOf(array(
                'message' => 'Fournisseur is required'
            ))
        ));
        $fournisseur_id->setFilters(array('striptags'));
        $this->add($fournisseur_id);

         //montant
        $montant = new Numeric('montant', ["class" => "form-control input-md", "value" => "0", "readonly" => "readonly", "required" => "required"]);
        $montant->setLabel($trans['Stock']);
        $montant->addValidators(array(
            new PresenceOf(array(
                'message' => 'montant is required'
            ))
        ));
        $montant->setFilters(array('striptags'));
        $this->add($montant);
    }
}
