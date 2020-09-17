<?php

class Prestations extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $date;

    /**
     *
     * @var double
     */
    public $montant_normal;

    /**
     *
     * @var double
     */
    public $montant_difference;

    /**
     *
     * @var double
     */
    public $montant_patient;

    /**
     *
     * @var double
     */
    public $montant_restant;

    /**
     *
     * @var string
     */
    public $num_quittance;

    /**
     *
     * @var integer
     */
    public $etat;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var integer
     */
    public $type_assurance_id;

    /**
     *
     * @var double
     */
    public $type_assurance_taux;

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
    public $date_annulation;

    /**
     *
     * @var string
     */
    public $motif_annulation;

    /**
     *
     * @var integer
     */
    public $user_id_annulation;

    /**
     *
     * @var double
     */
    public $montant_recu;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'PrestationsDetails', 'prestations_id', array('alias' => 'PrestationsDetails'));
        $this->belongsTo('patients_id', 'Patients', 'id', array('alias' => 'Patients'));
        $this->belongsTo('type_assurance_id', 'TypeAssurance', 'id', array('alias' => 'TypeAssurance'));
        $this->belongsTo('user_id', 'User', 'id', array('alias' => 'UserCaissier'));
        $this->hasMany('id', 'LaboDemandes', 'prestations_id', array('alias' => 'LaboDemandes'));
        $this->hasMany('id', 'ImgDemandes', 'prestations_id', array('alias' => 'ImgDemandes'));
        $this->belongsTo('user_id_annulation', 'User', 'id', array('alias' => 'UserAnnulation'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'prestations';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Prestations[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Prestations
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
