<?php

class LaboDemandes extends \Phalcon\Mvc\Model
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
    public $date;

    /**
     *
     * @var string
     */
    public $close_date;

    /**
     *
     * @var string
     */
    public $paillasse;

    /**
     *
     * @var integer
     */
    public $patients_id;

    /**
     *
     * @var integer
     */
    public $prestations_id;

    /**
     *
     * @var integer
     */
    public $sequence;

    /**
     *
     * @var string
     */
    public $etat;

    /**
     *
     * @var string
     */
    public $provenance;

    /**
     *
     * @var string
     */
    public $prescripteur;

    /**
     *
     * @var string
     */
    public $histoire;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'LaboDemandesDetails', 'labo_demandes_id', array('alias' => 'LaboDemandesDetails'));
        $this->hasMany('id', 'LaboDemandesResultats', 'labo_demandes_id', array('alias' => 'LaboDemandesResultats'));
        $this->hasMany('id', 'LaboDemandesResultatsAntibiotiques', 'labo_demandes_id', array('alias' => 'LaboDemandesResultatsAntibiotiques'));
        $this->belongsTo('patients_id', 'Patients', 'id', array('alias' => 'Patients'));
        $this->belongsTo('prestations_id', 'Prestations', 'id', array('alias' => 'Prestations'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return LaboDemandes[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return LaboDemandes
     */
    

}
