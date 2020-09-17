<?php

class ConsultationsConstantes extends \Phalcon\Mvc\Model
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
    public $cle;

    /**
     *
     * @var string
     */
    public $valeur;

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
        $this->belongsTo('consultations_id', 'Consultations', 'id', array('alias' => 'Consultations'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConsultationsConstantes[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConsultationsConstantes
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'consultations_constantes';
    }

}
