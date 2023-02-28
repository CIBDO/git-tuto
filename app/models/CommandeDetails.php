<?php

class CommandeDetails extends \Phalcon\Mvc\Model
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
    public $quantite;

    /**
     *
     * @var integer
     */
    public $quantite_livree;

    /**
     *
     * @var double
     */
    public $prix;

    /**
     *
     * @var integer
     */
    public $commande_id;

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
        $this->belongsTo('commande_id', 'Commande', 'id', array('alias' => 'Commande'));
        $this->belongsTo('produit_id', 'Produit', 'id', array('alias' => 'Produit'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CommandeDetails[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CommandeDetails
     */
    

}
