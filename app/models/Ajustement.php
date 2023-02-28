<?php

class Ajustement extends \Phalcon\Mvc\Model
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
    public $date;

    /**
     *
     * @var string
     */
    public $type;

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
    public $ajustement_motifs_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('point_distribution_id', 'PointDistribution', 'id', array('alias' => 'PointDistribution'));
        $this->belongsTo('produit_id', 'Produit', 'id', array('alias' => 'Produit'));
        $this->belongsTo('ajustement_motifs_id', 'AjustementMotifs', 'id', array('alias' => 'AjustementMotifs'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Ajustement[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Ajustement
     */
    

}
