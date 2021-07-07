<?php

class FSolde extends \Phalcon\Mvc\Model
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
    public $montant;

    /**
     *
     * @var integer
     */
    public $f_banque_compte_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('f_banque_compte_id', 'FBanqueCompte', 'id', array('alias' => 'FBanqueCompte'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return FSolde[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return FSolde
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
