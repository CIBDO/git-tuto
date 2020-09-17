<?php

class FSousCompte extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $f_compte_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'FOperation', 'f_sous_compte_id', array('alias' => 'FOperation'));
        $this->hasMany('id', 'FPlanification', 'f_sous_compte_id', array('alias' => 'FPlanification'));
        $this->belongsTo('f_compte_id', 'FCompte', 'id', array('alias' => 'FCompte'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'f_sous_compte';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return FSousCompte[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return FSousCompte
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
