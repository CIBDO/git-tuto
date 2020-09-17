<?php

class FOperationDetails extends \Phalcon\Mvc\Model
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
    public $quantite;

    /**
     *
     * @var string
     */
    public $prix_unitaire;

    /**
     *
     * @var string
     */
    public $montant;

    /**
     *
     * @var integer
     */
    public $f_operation_id;

    /**
     *
     * @var integer
     */
    public $f_designation_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('f_designation_id', 'FDesignation', 'id', array('alias' => 'FDesignation'));
        $this->belongsTo('f_operation_id', 'FOperation', 'id', array('alias' => 'FOperation'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'f_operation_details';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return FOperationDetails[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return FOperationDetails
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
