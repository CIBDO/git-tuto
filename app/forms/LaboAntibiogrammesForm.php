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


class LaboAntibiogrammesForm extends Form {

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


        //labo_antibiogrammes_type_id
        $labo_antibiogrammes_type_id = new Select( 'labo_antibiogrammes_type_id', 
                                    LaboAntibiogrammesType::find(),
                                    ['using' => array('id', 'libelle'), "class" => "form-control", "id" => "labo_antibiogrammes_type_id", "required" => "required", "useEmpty" => true]
                                );
        $labo_antibiogrammes_type_id->setLabel($trans['Type']);
        $labo_antibiogrammes_type_id->addValidators(array(
            new PresenceOf(array(
                'message' => 'Type is required'
            ))
        ));
        $labo_antibiogrammes_type_id->setFilters(array('striptags'));
        $this->add($labo_antibiogrammes_type_id);
    }
}
