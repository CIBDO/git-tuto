<?php

class FDesignation extends \Phalcon\Mvc\Model
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'TfOperationDetails', 'f_designation_id', array('alias' => 'TfOperationDetails'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return FDesignation[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return FDesignation
     */
    

}
