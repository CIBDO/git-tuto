<?php

class Forms extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $code;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var string
     */
    public $forms_assoc;
         
    /**
     *
     * @var integer
     */
    public $hide_default;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'FormsElements', 'forms_id', array('alias' => 'FormsElements'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Forms[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Forms
     */
    

}
