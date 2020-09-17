<?php

class RecuMedicament extends \Phalcon\Mvc\Model
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
     * @var double
     */
    public $montant_normal;

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
    public $num_ordonnance;

    /**
     *
     * @var integer
     */
    public $etat;

    /**
     *
     * @var double
     */
    public $type_assurance_taux;

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
     * @var double
     */
    public $montant_recu;

    /**
     *
     * @var integer
     */
    public $patients_id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var integer
     */
    public $user_annulation_id;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'RecuMedicamentDetails', 'recu_medicament_id', array('alias' => 'RecuMedicamentDetails'));
        $this->belongsTo('patients_id', 'Patients', 'id', array('alias' => 'Patients'));
        $this->belongsTo('type_assurance_id', 'TypeAssurance', 'id', array('alias' => 'TypeAssurance'));
        $this->belongsTo('user_id', 'User', 'id', array('alias' => 'UserCaissierPharmacie'));
        $this->belongsTo('user_annulation_id', 'User', 'id', array('alias' => 'UserRecuAnnulation'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'recu_medicament';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return RecuMedicament[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return RecuMedicament
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
