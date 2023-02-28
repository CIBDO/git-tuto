<?php

class Commande extends \Phalcon\Mvc\Model
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
    public $objet;

    /**
     *
     * @var string
     */
    public $date;

    /**
     *
     * @var string
     */
    public $etat;

    /**
     *
     * @var integer
     */
    public $fournisseur_id;

    /**
     *
     * @var string
     */
    public $montant;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'CommandeDetails', 'commande_id', array('alias' => 'CommandeDetails'));
        $this->hasMany('id', 'Reception', 'commande_id', array('alias' => 'Reception'));
        $this->belongsTo('fournisseur_id', 'Fournisseur', 'id', array('alias' => 'Fournisseur'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Commande[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Commande
     */
    

}
