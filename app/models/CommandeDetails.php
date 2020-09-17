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
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'commande_details';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CommandeDetails[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CommandeDetails
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
