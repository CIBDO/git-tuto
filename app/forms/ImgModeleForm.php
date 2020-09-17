<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValid;
use Phalcon\Validation\Validator\Regex as RegexValidator;


class ImgModeleForm extends Form {

    public function initialize($trans) {

        //interpretation
        $interpretation = new TextArea('interpretation', ["class" => "form-control textarea input-md", "required" => "required"]);
        $interpretation->setLabel($trans['Interpretation']);
        $interpretation->addValidators(array(
            new PresenceOf(array(
                'message' => 'interpretation is required'
            ))
        ));
        $this->add($interpretation);

        //conclusion
        $conclusion = new TextArea('conclusion', ["class" => "form-control textarea input-md", "required" => "required"]);
        $conclusion->setLabel($trans['Conclusion']);
        $conclusion->addValidators(array(
            new PresenceOf(array(
                'message' => 'conclusion is required'
            ))
        ));
        $this->add($conclusion);

        //keyword
        $keyword = new Text('keyword', ["class" => "form-control input-md", "required" => "required"]);
        $keyword->setLabel($trans['Libelle']);
        $keyword->addValidators(array(
            new PresenceOf(array(
                'message' => 'keyword is required'
            ))
        ));
        $keyword->setFilters(array('striptags'));
        $this->add($keyword);
    }
}
