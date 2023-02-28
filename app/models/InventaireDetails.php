<?php

class InventaireDetails extends \Phalcon\Mvc\Model
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
    public $produit_id;

    /**
     *
     * @var integer
     */
    public $inventaire_id;

    /**
     *
     * @var integer
     */
    public $initial;

    /**
     *
     * @var integer
     */
    public $entre;

    /**
     *
     * @var integer
     */
    public $sortie;

    /**
     *
     * @var integer
     */
    public $theorique;

    /**
     *
     * @var integer
     */
    public $physique;

    /**
     *
     * @var integer
     */
    public $perte;

    /**
     *
     * @var integer
     */
    public $ajout;

    /**
     *
     * @var string
     */
    public $observation;

    /**
     *
     * @var string
     */
    public $details;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('produit_id', 'Produit', 'id', array('alias' => 'Produit'));
        $this->belongsTo('inventaire_id', 'Inventaire', 'id', array('alias' => 'Inventaire'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return InventaireDetails[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return InventaireDetails
     */
    

}
