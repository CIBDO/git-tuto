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


class SousLocaliteForm extends Form
{

    public function initialize($trans)
    {
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

        //Residence_id
        $residence_id = new Select('residence_id',
            Residence::find(),
            ['using' => array('id', 'libelle'), "class" => "form-control", "id" => "residence_id", "required" => "required", "useEmpty" => true]
        );
        $residence_id->setLabel($trans['Profil']);
        $residence_id->addValidators(array(
            new PresenceOf(array(
                'message' => 'Residence is required'
            ))
        ));
        $residence_id->setFilters(array('striptags'));
        $this->add($residence_id);


    }
}
