<?php

class TypeAssurance extends \Phalcon\Mvc\Model
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
     * @var double
     */
    public $taux;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'PatientAssurance', 'type_assurance_id', array('alias' => 'PatientAssurance'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TypeAssurance[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TypeAssurance
     */
    

}
