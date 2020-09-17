<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Date;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValid;
use Phalcon\Validation\Validator\Regex as RegexValidator;


class FOperationForm extends Form {

    public function initialize($trans) {
        
        //montant
        $montant = new Numeric('montant', ["class" => "form-control input-md", "min" => "0", "required" => "required"]);
        $montant->setLabel($trans['Montant']);
        $montant->addValidators(array(
            new PresenceOf(array(
                'message' => 'montant is required'
            ))
        ));
        $montant->setFilters(array('striptags'));
        $this->add($montant);

        //date
        $date = new Date('date', ["class" => "form-control input-md", "required" => "required"]);
        $date->setLabel($trans['Date']);
        $date->addValidators(array(
            new PresenceOf(array(
                'message' => 'Date is required'
            ))
        ));
        $date->setFilters(array('striptags'));
        $this->add($date);

        //details
        $details = new TextArea('details', ["class" => "form-control input-md"]);
        $details->setLabel($trans['Détails']);
       /* $details->addValidators(array(
            new PresenceOf(array(
                'message' => 'details is required'
            ))
        ));*/
        $details->setFilters(array('striptags'));
        $this->add($details);

        //banque_cheque
        $banque_cheque = new Text('banque_cheque', ["class" => "form-control input-md"]);
        $banque_cheque->setLabel($trans['Name']);
        /*$banque_cheque->addValidators(array(
            new PresenceOf(array(
                'message' => 'banque_cheque is required'
            ))
        ));*/
        $banque_cheque->setFilters(array('striptags'));
        $this->add($banque_cheque);

        //banque_porteur
        $banque_porteur = new Text('banque_porteur', ["class" => "form-control input-md"]);
        $banque_porteur->setLabel($trans['Porteur']);
        /*$banque_porteur->addValidators(array(
            new PresenceOf(array(
                'message' => 'banque_porteur is required'
            ))
        ));*/
        $banque_porteur->setFilters(array('striptags'));
        $this->add($banque_porteur);

        //banque_details
        $banque_details = new TextArea('banque_details', ["class" => "form-control input-md"]);
        $banque_details->setLabel($trans['Détails banque']);
        /*$banque_details->addValidators(array(
            new PresenceOf(array(
                'message' => 'Name is required'
            ))
        ));*/
        $banque_details->setFilters(array('striptags'));
        $this->add($banque_details);

    }
}
