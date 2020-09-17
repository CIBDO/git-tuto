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


class FormsForm extends Form {

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

        //Type
        $type = new Select('type', array(   'base'  => 'base', 
                                            'onglet' => 'onglet'
                                        ),
                                         ["class" => "form-control type", "id" => "type", "required" => "required", "useEmpty" => true, "emptyText" => "choisir"]);
        $type->setLabel($trans['Type']);
        $type->addValidators(array(
            new PresenceOf(array(
                'message' => 'Type is required'
            ))
        ));
        $type->setFilters(array('striptags'));
        $this->add($type);

         //Hide_default
        $hide_default = new Select('hide_default', array(   '0' => 'Afficher le formulaire par defaut',
                                                            '1'  => 'Cacher le formulaire par defaut'
                                        ),
                                         ["class" => "form-control hide_default", "id" => "hide_default", "required" => "required", "useEmpty" => false]);
        $hide_default->setLabel($trans['Hide_default']);
        $hide_default->addValidators(array(
            new PresenceOf(array(
                'message' => 'Hide_default is required'
            ))
        ));
        $hide_default->setFilters(array('striptags'));
        $this->add($hide_default);


    }
}
