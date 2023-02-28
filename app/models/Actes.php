<?php

class Actes extends \Phalcon\Mvc\Model
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
     *
     * @var string
     */
    public $code;

    /**
     *
     * @var integer
     */
    public $services_id;

    /**
     *
     * @var double
     */
    public $prix;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var string
     */
    public $unite;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'ConsultationsExamen', 'actes_id', array('alias' => 'ConsultationsExamen'));
        $this->hasMany('id', 'PrestationsDetails', 'actes_id', array('alias' => 'PrestationsDetails'));
        $this->hasMany('id', 'PrixActes', 'actes_id', array('alias' => 'PrixActes'));
        $this->belongsTo('services_id', 'Services', 'id', array('alias' => 'Services'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Actes[]|Actes|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Actes|\Phalcon\Mvc\Model\ResultInterface
     */
    
}
