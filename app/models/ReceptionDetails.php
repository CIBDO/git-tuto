<?php

class ReceptionDetails extends \Phalcon\Mvc\Model
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
    public $litige;

    /**
     *
     * @var integer
     */
    public $manquant;

    /**
     *
     * @var string
     */
    public $observation;

    /**
     *
     * @var string
     */
    public $lot;

    /**
     *
     * @var string
     */
    public $date_peremption;

    /**
     *
     * @var double
     */
    public $prix_achat;

    /**
     *
     * @var double
     */
    public $prix_vente;

    /**
     *
     * @var integer
     */
    public $reception_id;

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
        $this->belongsTo('reception_id', 'Reception', 'id', array('alias' => 'Reception'));
        $this->belongsTo('produit_id', 'Produit', 'id', array('alias' => 'Produit'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ReceptionDetails[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ReceptionDetails
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
