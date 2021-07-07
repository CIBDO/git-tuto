<?php

class ConsultationsMotifs extends \Phalcon\Mvc\Model
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
    public $dossiers_consultations_id;

    /**
     *
     * @var integer
     */
    public $cs_motifs_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('dossiers_consultations_id', 'DossiersConsultations', 'id', array('alias' => 'DossiersConsultations'));
        $this->belongsTo('cs_motifs_id', 'CsMotifs', 'id', array('alias' => 'CsMotifs'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConsultationsMotifs[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConsultationsMotifs
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
