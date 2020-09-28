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


class AscForm extends Form
{

    public function initialize($trans)
    {

        //id_technique
        $code_asc = new Text('code_asc', ["class" => "form-control input-md"]);
        $code_asc->setLabel($trans['Code ASC']);
        $code_asc->setFilters(array('striptags', 'trim'));
        $this->add($code_asc);

        //nom
        $nom = new Text('nom', ["class" => "form-control input-md", "required" => "required"]);
        $nom->setLabel($trans['Nom']);
        $nom->setFilters(array('striptags', 'trim'));
        $nom->addValidators(array(
            new PresenceOf(array(
                'message' => 'Name is required'
            ))
        ));
        $this->add($nom);

        //prenom
        $prenom = new Text('prenom', ["class" => "form-control input-md", "required" => "required"]);
        $prenom->setLabel($trans['Prenom']);
        $prenom->setFilters(array('striptags', 'trim'));
        $prenom->addValidators(array(
            new PresenceOf(array(
                'message' => 'Prenom is required'
            ))
        ));
        $this->add($prenom);

        //profession
        $profession = new Text('profession', ["class" => "form-control input-md"]);
        $profession->setLabel($trans['Profession']);
        $profession->setFilters(array('striptags'));
        $this->add($profession);

        //telephone
        $telephone = new Text('telephone', ["class" => "form-control input-md", 'data-inputmask' => '"mask": "99-99-99-99"', 'data-mask' => '']);
        $telephone->setLabel($trans['Telephone']);
        $telephone->setFilters(array('striptags'));
        $this->add($telephone);

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
