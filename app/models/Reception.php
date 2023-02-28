<?php

class Reception extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $commande_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Approvisionnement', 'reception_id', array('alias' => 'Approvisionnement'));
        $this->hasMany('id', 'ReceptionDetails', 'reception_id', array('alias' => 'ReceptionDetails'));
        $this->belongsTo('fournisseur_id', 'Fournisseur', 'id', array('alias' => 'Fournisseur'));
        $this->belongsTo('commande_id', 'Commande', 'id', array('alias' => 'Commande'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Reception[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Reception
     */
    

}
