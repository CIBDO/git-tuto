<?php

class FBanqueCompte extends \Phalcon\Mvc\Model
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
    public $compte;

    /**
     *
     * @var integer
     */
    public $f_banque_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'FOperation', 'f_banque_compte_id', array('alias' => 'FOperation'));
        $this->belongsTo('f_banque_id', 'FBanque', 'id', array('alias' => 'FBanque'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return FBanqueCompte[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return FBanqueCompte
     */
    

}
