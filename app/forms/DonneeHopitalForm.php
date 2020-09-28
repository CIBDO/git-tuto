<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Date;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValid;
use Phalcon\Validation\Validator\Regex as RegexValidator;


class DonneeHopitalForm extends Form
{

    public function initialize($trans)
    {

        //commentaire
        $commentaire = new TextArea('commentaire', ["class" => "form-control input-md"]);
        $commentaire->setLabel($trans['Commentaire']);
        $commentaire->setFilters(array('striptags', 'trim'));
        $this->add($commentaire);

        //date rdv
        $date_rdv = new Date('date_rdv', ["class" => "form-control input-md"]);
        $date_rdv->setLabel($trans['Date rendez-vous']);
        $date_rdv->setFilters(array('striptags', 'trim'));
        $this->add($date_rdv);

    }
}
