<?php

class RuptureStock extends \Phalcon\Mvc\Model
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
    public $date_rupture;

    /**
     *
     * @var string
     */
    public $date_appro;

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
        $this->belongsTo('produit_id', 'Produit', 'id', array('alias' => 'Produit'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return RuptureStock[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return RuptureStock
     */
    

}
