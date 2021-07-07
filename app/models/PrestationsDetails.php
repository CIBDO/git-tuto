<?php

class PrestationsDetails extends \Phalcon\Mvc\Model
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
    public $prestations_id;

    /**
     *
     * @var integer
     */
    public $actes_id;

    /**
     *
     * @var double
     */
    public $montant_normal;

    /**
     *
     * @var double
     */
    public $montant_unitaire;

    /**
     *
     * @var double
     */
    public $montant_unitaire_difference;

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
     * @var integer
     */
    public $quantite;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'ConsultationListeAttente', 'prestations_details_id', array('alias' => 'ConsultationListeAttente'));
        $this->belongsTo('actes_id', 'Actes', 'id', array('alias' => 'Actes'));
        $this->belongsTo('prestations_id', 'Prestations', 'id', array('alias' => 'Prestations'));
        $this->belongsTo('user_id', 'User', 'id', array('alias' => 'User'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PrestationsDetails[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PrestationsDetails
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
