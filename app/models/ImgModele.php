<?php

class ImgModele extends \Phalcon\Mvc\Model
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
    public $interpretation;

    /**
     *
     * @var string
     */
    public $conclusion;

    /**
     *
     * @var string
     */
    public $keyword;

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ImgModele[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ImgModele
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
