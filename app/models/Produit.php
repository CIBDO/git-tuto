<?php

class Produit extends \Phalcon\Mvc\Model
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
    public $libelle;

    /**
     *
     * @var integer
     */
    public $type_produit_id;

    /**
     *
     * @var integer
     */
    public $forme_produit_id;

    /**
     *
     * @var integer
     */
    public $classe_therapeutique_id;

    /**
     *
     * @var string
     */
    public $unite_vente;

    /**
     *
     * @var string
     */
    public $presentation;

    /**
     *
     * @var string
     */
    public $dosage;

    /**
     *
     * @var integer
     */
    public $seuil_min;

    /**
     *
     * @var integer
     */
    public $seuil_max;

    /**
     *
     * @var double
     */
    public $prix;

    /**
     *
     * @var integer
     */
    public $stock;

    /**
     *
     * @var string
     */
    public $etat;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Approvisionnement', 'produit_id', array('alias' => 'Approvisionnement'));
        $this->hasMany('id', 'ReceptionDetails', 'produit_id', array('alias' => 'ReceptionDetails'));
        $this->hasMany('id', 'StockPointDistribution', 'produit_id', array('alias' => 'StockPointDistribution'));
        $this->hasMany('id', 'TransactionProduit', 'produit_id', array('alias' => 'TransactionProduit'));
        $this->belongsTo('forme_produit_id', 'FormeProduit', 'id', array('alias' => 'FormeProduit'));
        $this->belongsTo('type_produit_id', 'TypeProduit', 'id', array('alias' => 'TypeProduit'));
        $this->belongsTo('classe_therapeutique_id', 'ClasseTherapeutique', 'id', array('alias' => 'ClasseTherapeutique'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Produit[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Produit
     */
    

}
