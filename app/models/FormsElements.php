<?php

class FormsElements extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $libelle;

    /**
     *
     * @var integer
     */
    public $position;

    /**
     *
     * @var string
     */
    public $type_valeur;

    /**
     *
     * @var string
     */
    public $valeur_possible;

    /**
     *
     * @var integer
     */
    public $forms_id;

    /**
     *
     * @var integer
     */
    public $place_after_c;

    /**
     *
     * @var integer
     */
    public $place_after_s;

    /**
     *
     * @var integer
     */
    public $required;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'FormsResults', 'forms_elements_id', array('alias' => 'FormsResults'));
        $this->belongsTo('forms_id', 'Forms', 'id', array('alias' => 'Forms'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return FormsElements[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return FormsElements
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
