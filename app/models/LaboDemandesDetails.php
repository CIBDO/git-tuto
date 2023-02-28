<?php

class LaboDemandesDetails extends \Phalcon\Mvc\Model
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
    public $labo_demandes_id;

    /**
     *
     * @var integer
     */
    public $labo_analyses_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('labo_analyses_id', 'LaboAnalyses', 'id', array('alias' => 'LaboAnalyses'));
        $this->belongsTo('labo_demandes_id', 'LaboDemandes', 'id', array('alias' => 'LaboDemandes'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return LaboDemandesDetails[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return LaboDemandesDetails
     */
    

}
