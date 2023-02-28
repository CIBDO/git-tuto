<?php

class DiagnosticSource extends \Phalcon\Mvc\Model
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
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DiagnosticSource[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DiagnosticSource
     */
    

}
