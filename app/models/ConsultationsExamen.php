<?php

class ConsultationsExamen extends \Phalcon\Mvc\Model
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
    public $actes_id;

    /**
     *
     * @var integer
     */
    public $consultations_id;

    /**
     *
     * @var integer
     */
    public $dossiers_consultations_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('dossiers_consultations_id', 'DossiersConsultations', 'id', array('alias' => 'DossiersConsultations'));
        $this->belongsTo('consultations_id', 'Consultations', 'id', array('alias' => 'Consultations'));
        $this->belongsTo('actes_id', 'Actes', 'id', array('alias' => 'Actes'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConsultationsExamen[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConsultationsExamen
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
