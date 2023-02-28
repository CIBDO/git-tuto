<?php

class Inventaire extends \Phalcon\Mvc\Model
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
    public $debut;

    /**
     *
     * @var string
     */
    public $fin;

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
        $this->hasMany('id', 'InventaireDetails', 'inventaire_id', array('alias' => 'InventaireDetails'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Inventaire[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Inventaire
     */
    

}
