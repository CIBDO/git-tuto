<?php

class FOperation extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $f_sous_compte_id;

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
     *
     * @var string
     */
    public $details;

    /**
     *
     * @var string
     */
    public $date;

    /**
     *
     * @var string
     */
    public $banque_cheque;

    /**
     *
     * @var string
     */
    public $banque_porteur;

    /**
     *
     * @var string
     */
    public $banque_details;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'FOperationDetails', 'f_operation_id', array('alias' => 'FOperationDetails'));
        $this->belongsTo('f_banque_compte_id', 'FBanqueCompte', 'id', array('alias' => 'FBanqueCompte'));
        $this->belongsTo('f_sous_compte_id', 'FSousCompte', 'id', array('alias' => 'FSousCompte'));
        $this->belongsTo('user_id', 'User', 'id', array('alias' => 'User'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return FOperation[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return FOperation
     */
    

}
