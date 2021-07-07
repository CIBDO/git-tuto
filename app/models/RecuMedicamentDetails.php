<?php

class RecuMedicamentDetails extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

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
    public $recu_medicament_id;

    /**
     *
     * @var integer
     */
    public $produit_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('recu_medicament_id', 'RecuMedicament', 'id', array('alias' => 'RecuMedicament'));
        $this->belongsTo('produit_id', 'Produit', 'id', array('alias' => 'Produit'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return RecuMedicamentDetails[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return RecuMedicamentDetails
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
