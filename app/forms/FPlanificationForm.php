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


class FPlanificationForm extends Form {

    public function initialize($trans) {

        //type_prevision
        $type_prevision = new Select('type_prevision', array(
                                            'Activité'  => 'Activité',
                                            'Subvention'  => 'Subvention',
                                            'Dépense'  => 'Dépense'
                                        ),
                                         ["class" => "form-control", "id" => "type_prevision", "required" => "required", "useEmpty" => true]);
        $type_prevision->setLabel($trans['type_previsione']);
        $type_prevision->addValidators(array(
            new PresenceOf(array(
                'message' => 'type_prevision is required'
            ))
        ));
        $type_prevision->setFilters(array('striptags'));
        $this->add($type_prevision);
        
        //quantite
        $quantite = new Numeric('quantite', ["class" => "form-control input-md", "min" => "0", "required" => "required"]);
        $quantite->setLabel($trans['Prix de vente']);
        $quantite->addValidators(array(
            new PresenceOf(array(
                'message' => 'price is required'
            ))
        ));
        $quantite->setFilters(array('striptags'));
        $this->add($quantite);

        //prix_unitaire
        $prix_unitaire = new Numeric('prix_unitaire', ["class" => "form-control input-md", "min" => "0", "required" => "required"]);
        $prix_unitaire->setLabel($trans['Prix de vente']);
        $prix_unitaire->addValidators(array(
            new PresenceOf(array(
                'message' => 'price is required'
            ))
        ));
        $prix_unitaire->setFilters(array('striptags'));
        $this->add($prix_unitaire);

        //montant
        $montant = new Numeric('montant', ["class" => "form-control input-md", "readonly" => "readonly", "min" => "0", "required" => "required"]);
        $montant->setLabel($trans['Prix de vente']);
        $montant->addValidators(array(
            new PresenceOf(array(
                'message' => 'price is required'
            ))
        ));
        $montant->setFilters(array('striptags'));
        $this->add($montant);

    }
}
