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


class ImgItemsForm extends Form {

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

        //code
        $code = new Text('code', ["class" => "form-control input-md", "required" => "required"]);
        $code->setLabel($trans['Code']);
        $code->setFilters(array('striptags'));
        $this->add($code);
        
        //img_items_categories_id
        $img_items_categories_id = new Select( 'img_items_categories_id', 
                                    ImgItemsCategories::find(),
                                    ['using' => array('id', 'libelle'), "class" => "form-control", "id" => "img_items_categories_id", "required" => "required", "useEmpty" => true]
                                );
        $img_items_categories_id->setLabel($trans['Type']);
        $img_items_categories_id->addValidators(array(
            new PresenceOf(array(
                'message' => 'Type is required'
            ))
        ));
        $img_items_categories_id->setFilters(array('striptags'));
        $this->add($img_items_categories_id);
    }
}
