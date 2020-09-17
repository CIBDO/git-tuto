<?php

class FCompte extends \Phalcon\Mvc\Model
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
    public $numero;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'FSousCompte', 'f_compte_id', array('alias' => 'FSousCompte'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'f_compte';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return FCompte[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return FCompte
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
