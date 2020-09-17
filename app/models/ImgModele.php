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
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'img_modele';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ImgModele[]
     */
    public static function find($parameters = null)
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
