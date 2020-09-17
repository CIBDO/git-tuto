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


class ServicesForm extends Form {

    public function initialize($trans) {
        //Libelle
        $name = new Text('libelle', ["class" => "form-control input-md", "required" => "required"]);
        $name->setLabel($trans['Libelle']);
        $name->setFilters(array('striptags', 'trim'));
        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Name is required'
            ))
        ));
        $name->setFilters(array('striptags'));
        $this->add($name);
    }
}
