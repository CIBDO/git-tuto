<?php

class PatientsAntecedant extends \Phalcon\Mvc\Model
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
    public $type;

    /**
     *
     * @var string
     */
    public $libelle;

    /**
     *
     * @var integer
     */
    public $patients_id;

    /**
     *
     * @var string
     */
    public $niveau;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('patients_id', 'Patients', 'id', array('alias' => 'Patients'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PatientsAntecedant[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PatientsAntecedant
     */
    

}
