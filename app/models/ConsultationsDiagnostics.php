<?php

class ConsultationsDiagnostics extends \Phalcon\Mvc\Model
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
    public $type;

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
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConsultationsDiagnostics[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConsultationsDiagnostics
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
