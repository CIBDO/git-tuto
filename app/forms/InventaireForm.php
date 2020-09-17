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


class InventaireForm extends Form {

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

        //debut
        $debut = new Date('debut', ["class" => "form-control input-md", "required" => "required"]);
        $debut->setLabel($trans['Date']);
        $debut->addValidators(array(
            new PresenceOf(array(
                'message' => 'Date is required'
            ))
        ));
        $debut->setFilters(array('striptags'));
        $this->add($debut);

        //fin
        $fin = new Date('fin', ["class" => "form-control input-md", "required" => "required"]);
        $fin->setLabel($trans['Date']);
        $fin->addValidators(array(
            new PresenceOf(array(
                'message' => 'Date is required'
            ))
        ));
        $fin->setFilters(array('striptags'));
        $this->add($fin);
    }
}
