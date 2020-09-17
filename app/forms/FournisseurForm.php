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


class FournisseurForm extends Form {

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

        //telephone
        $telephone = new Text('telephone', ["class" => "form-control input-md", "required" => "required"]);
        $telephone->setLabel($trans['Telephone']);
        $telephone->addValidators(array(
            new PresenceOf(array(
                'message' => 'telephone is required'
            ))
        ));
        $telephone->setFilters(array('striptags'));
        $this->add($telephone);

        //adresse
        $adresse = new Text('adresse', ["class" => "form-control input-md", "required" => "required"]);
        $adresse->setLabel($trans['Adresse']);
        $adresse->addValidators(array(
            new PresenceOf(array(
                'message' => 'adresse is required'
            ))
        ));
        $adresse->setFilters(array('striptags'));
        $this->add($adresse);

        //email
        $email = new Email('email', ["class" => "form-control input-md"]);
        $email->setLabel($trans['Email']);
        $email->setFilters(array('striptags', 'trim'));
        $email->addValidators(array(
            new EmailValid(array(
                'message' => 'Email is required'
            ))
        ));
        $email->setFilters(array('striptags', 'email'));
        $this->add($email);
    }
}
