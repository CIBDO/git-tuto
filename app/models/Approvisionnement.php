<?php

class Approvisionnement extends \Phalcon\Mvc\Model
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
    public $point_distribution_id;

    /**
     *
     * @var integer
     */
    public $quantite;

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
     * @var string
     */
    public $date;

    /**
     *
     * @var string
     */
    public $motif;

    /**
     *
     * @var integer
     */
    public $reception_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'StockPointDistribution', 'approvisionnement_details_id', array('alias' => 'StockPointDistribution'));
        $this->belongsTo('produit_id', 'Produit', 'id', array('alias' => 'Produit'));
        $this->belongsTo('point_distribution_id', 'PointDistribution', 'id', array('alias' => 'PointDistribution'));
        $this->belongsTo('reception_id', 'Reception', 'id', array('alias' => 'Reception'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Approvisionnement[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Approvisionnement
     */
    

}
