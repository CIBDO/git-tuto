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


class ActesForm extends Form {

    public function initialize($trans) {
        //libelle
        $libelle = new Text('libelle', ["class" => "form-control input-md", "required" => "required"]);
        $libelle->setLabel($trans['Libelle']);
        $libelle->setFilters(array('striptags'));
        $libelle->addValidators(array(
            new PresenceOf(array(
                'message' => 'Libelle is required'
            ))
        ));
        $this->add($libelle);

        //code
        $code = new Text('code', ["class" => "form-control input-md"]);
        $code->setLabel($trans['Code']);
        $code->setFilters(array('striptags'));
        $this->add($code);

        //telephone
        $prix = new Numeric('prix', ["class" => "form-control input-md", "required" => "required"]);
        $prix->setLabel($trans['Prix']);
        $prix->setFilters(array('striptags'));
        $this->add($prix);

        //Service
        $services_id = new Select( 'services_id', 
                                    Services::find(),
                                    ['using' => array('id', 'libelle'), "class" => "form-control services_id", "id" => "services_id", "required" => "required", "useEmpty" => true]
                                );
        $services_id->setLabel($trans['Profil']);
        $services_id->addValidators(array(
            new PresenceOf(array(
                'message' => 'Service is required'
            ))
        ));
        $services_id->setFilters(array('striptags'));
        $this->add($services_id);

        //Type
        $type = new Select('type', array(
                                            'consultation'  => 'Consultation',
                                            'labo'          => 'Labo',
                                            'imagerie'      => 'Imagerie',
                                            'autre'         => 'autre'
                                        ),
                                         ["class" => "form-control type", "id" => "type", "required" => "required", "useEmpty" => true]);
        $type->setLabel($trans['Type']);
        $type->addValidators(array(
            new PresenceOf(array(
                'message' => 'Type is required'
            ))
        ));
        $type->setFilters(array('striptags'));
        $this->add($type);

        //Unité
        $unite = new Select('unite', array(
                                            'URENI'  => 'URENI',
                                            'Dispensaire'  => 'Dispensaire',
                                            'Maternité'  => 'Maternité',
                                            'Autre'  => 'Autre'
                                        ),
                                         ["class" => "form-control unite", "id" => "unite", "required" => "required", "useEmpty" => true]);
        $unite->setLabel($trans['Type']);
        $unite->addValidators(array(
            new PresenceOf(array(
                'message' => 'Unité is required'
            ))
        ));
        $unite->setFilters(array('striptags'));
        $this->add($unite);
    }
}
