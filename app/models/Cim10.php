<?php

class Cim10 extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $LID;

    /**
     *
     * @var string
     */
    public $SID;

    /**
     *
     * @var string
     */
    public $source;

    /**
     *
     * @var integer
     */
    public $valid;

    /**
     *
     * @var string
     */
    public $libelle;

    /**
     *
     * @var string
     */
    public $FR_OMS;

    /**
     *
     * @var string
     */
    public $EN_OMS;

    /**
     *
     * @var string
     */
    public $GE_DIMDI;

    /**
     *
     * @var string
     */
    public $GE_AUTO;

    /**
     *
     * @var string
     */
    public $FR_CHRONOS;

    /**
     *
     * @var string
     */
    public $date;

    /**
     *
     * @var string
     */
    public $author;


    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Cim10[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Cim10
     */
    

}
