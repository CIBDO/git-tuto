<?php

class PointDistribution extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $default;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Approvisionnement', 'point_distribution_id', array('alias' => 'Approvisionnement'));
        $this->hasMany('id', 'PointDistributionUser', 'point_distribution_id', array('alias' => 'PointDistributionUser'));
        $this->hasMany('id', 'StockPointDistribution', 'point_distribution_id', array('alias' => 'StockPointDistribution'));
        $this->hasMany('id', 'TransactionProduit', 'point_distribution_id', array('alias' => 'TransactionProduit'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'point_distribution';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PointDistribution[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PointDistribution
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
