<?php

class FormsResults extends \Phalcon\Mvc\Model
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
    public $valeur;

    /**
     *
     * @var string
     */
    public $entity_type;

    /**
     *
     * @var integer
     */
    public $entity_id;

    /**
     *
     * @var integer
     */
    public $forms_elements_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('forms_elements_id', 'FormsElements', 'id', array('alias' => 'FormsElements'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return FormsResults[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return FormsResults
     */
    

}
