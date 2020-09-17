<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValid;
use Phalcon\Validation\Validator\Regex as RegexValidator;


class UserForm extends Form {

    public function initialize($trans) {
        //Nom
        $nom = new Text('nom', ["class" => "form-control input-md", "required" => "required"]);
        $nom->setLabel($trans['Name']);
        $nom->addValidators(array(
            new PresenceOf(array(
                'message' => 'Name is required'
            ))
        ));
        $nom->setFilters(array('striptags'));
        $this->add($nom);

        //prenom
        $prenom = new Text('prenom', ["class" => "form-control input-md", "required" => "required"]);
        $prenom->setLabel($trans['Prenom']);
        $prenom->addValidators(array(
            new PresenceOf(array(
                'message' => 'Last name is required'
            ))
        ));
        $prenom->setFilters(array('striptags'));
        $this->add($prenom);

        //email
        $email = new Email('email', ["class" => "form-control input-md"]);
        $email->setLabel($trans['Email']);
        $email->setFilters(array('striptags', 'email'));
        /*$email->addValidators(array(
            new EmailValid(array(
                'message' => 'Email format is required'
            ))
        ));*/
        $this->add($email);

        //telephone
        $telephone = new Text('telephone', ["class" => "form-control input-md", 'data-inputmask' => '"mask": "99-99-99-99"', 'data-mask' => '', "required" => "required"]);
        $telephone->setLabel($trans['Telephone']);
        $telephone->setFilters(array('striptags'));
        $this->add($telephone);

        //Profil
        $profile = new Select('profile', array( //'admin'  => 'admin',
                                            'agent'  => 'agent (Autre profile)', 'medecin' => 'medecin',
                                            //'laboratin' => 'laboratin',
                                            'pharmacien' => 'pharmacien', 'comptable' => 'comptable'
                                        ),
                                         ["class" => "form-control profile", "id" => "profile", "required" => "required", "useEmpty" => true]);
        $profile->setLabel($trans['Profile']);
        $profile->addValidators(array(
            new PresenceOf(array(
                'message' => 'Profil is required'
            ))
        ));
        $profile->setFilters(array('striptags'));
        $this->add($profile);

        //Service
        $services_id = new Select( 'services_id', 
                                    Services::find(),
                                    ['using' => array('id', 'libelle'), "class" => "form-control services_id", "id" => "services_id", "required" => "required", "useEmpty" => true]
                                );
        $services_id->setLabel($trans['Service']);
        $services_id->addValidators(array(
            new PresenceOf(array(
                'message' => 'Service is required'
            ))
        ));
        $services_id->setFilters(array('striptags'));
        $this->add($services_id);


        //Login
        $login = new Text('login', ["class" => "form-control input-md", "autocomplete" => "off",]);
        $login->setLabel($trans['login']);
        $login->setFilters(array('striptags'));
        $this->add($login);

        //Password
        $password = new Password('password', ["class" => "form-control input-md", "autocomplete" => "new-password", "value" => ""]);
        $password->setLabel($trans['password']);
        //$password->setFilters(array('striptags'));
        $this->add($password);
    }
}
