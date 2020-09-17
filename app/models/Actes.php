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
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'actes';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Actes[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Actes
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
