<?php

class FPlanification extends \Phalcon\Mvc\Model
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
    public $type_prevision;

    /**
     *
     * @var string
     */
    public $montant;

    /**
     *
     * @var integer
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
    public $annee;

    /**
     *
     * @var integer
     */
    public $f_sous_compte_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('f_sous_compte_id', 'FSousCompte', 'id', array('alias' => 'FSousCompte'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return FPlanification[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return FPlanification
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
