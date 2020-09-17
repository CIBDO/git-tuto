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


class CsMotifsForm extends Form {

    public function initialize($trans) {
        //Libelle
        $libelle = new Text('libelle', ["class" => "form-control input-md", "required" => "required"]);
        $libelle->setLabel($trans['Libelle']);
        $libelle->setFilters(array('striptags', 'trim'));
        $libelle->addValidators(array(
            new PresenceOf(array(
                'message' => 'Libelle is required'
            ))
        ));
        $libelle->setFilters(array('striptags'));
        $this->add($libelle);
    }
}
