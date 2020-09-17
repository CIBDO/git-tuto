<?php

class PatientsAssurance extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $patients_id;

    /**
     *
     * @var integer
     */
    public $type_assurance_id;

    /**
     *
     * @var string
     */
    public $numero;

    /**
     *
     * @var string
     */
    public $ogd;

    /**
     *
     * @var string
     */
    public $beneficiaire;

    /**
     *
     * @var string
     */
    public $autres_infos;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('patients_id', 'Patients', 'id', array('alias' => 'Patients'));
        $this->belongsTo('type_assurance_id', 'TypeAssurance', 'id', array('alias' => 'TypeAssurance'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'patients_assurance';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PatientsAssurance[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PatientsAssurance
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
