<?php

class FormeProduit extends \Phalcon\Mvc\Model
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Produit', 'forme_produit_id', array('alias' => 'Produit'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'forme_produit';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return FormeProduit[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return FormeProduit
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
