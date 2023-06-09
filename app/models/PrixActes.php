<?php

class PrixActes extends \Phalcon\Mvc\Model
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
    public $prix;

    /**
     *
     * @var integer
     */
    public $actes_id;

    /**
     *
     * @var integer
     */
    public $type_assurance_id;

    /**
     *
     * @var integer
     */
    public $relicat;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('actes_id', 'Actes', 'id', array('alias' => 'Actes'));
        $this->belongsTo('type_assurance_id', 'TypeAssurance', 'id', array('alias' => 'TypeAssurance'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PrixActes[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PrixActes
     */
    

}
