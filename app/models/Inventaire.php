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
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'inventaire';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Inventaire[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Inventaire
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
