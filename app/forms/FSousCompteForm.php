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


class FSousCompteForm extends Form {

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

        //numero
        $numero = new Text('numero', ["class" => "form-control input-md", "required" => "required"]);
        $numero->setLabel($trans['Numero']);
        $numero->addValidators(array(
            new PresenceOf(array(
                'message' => 'numero is required'
            ))
        ));
        $numero->setFilters(array('striptags'));
        $this->add($numero);

        //f_compte_id
        $f_compte_id = new Select( 'f_compte_id', 
                                    FCompte::find(),
                                    ['using' => array('id', 'libelle'), "class" => "form-control", "id" => "f_compte_id", "required" => "required", "useEmpty" => true]
                                );
        $f_compte_id->setLabel($trans['Compte']);
        $f_compte_id->addValidators(array(
            new PresenceOf(array(
                'message' => 'Compte is required'
            ))
        ));
        $f_compte_id->setFilters(array('striptags'));
        $this->add($f_compte_id);
    }
}
