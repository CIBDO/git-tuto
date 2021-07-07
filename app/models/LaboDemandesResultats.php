<?php

class LaboDemandesResultats extends \Phalcon\Mvc\Model
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
    public $valeur;

    /**
     *
     * @var string
     */
    public $unite;

    /**
     *
     * @var integer
     */
    public $labo_demandes_id;

    /**
     *
     * @var integer
     */
    public $labo_analyses_id;

    /**
     *
     * @var string
     */
    public $antibiogramme;

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
        $this->belongsTo('labo_demandes_id', 'LaboDemandes', 'id', array('alias' => 'LaboDemandes'));
        $this->belongsTo('labo_analyses_id', 'LaboAnalyses', 'id', array('alias' => 'LaboAnalyses'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return LaboDemandesResultats[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return LaboDemandesResultats
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
