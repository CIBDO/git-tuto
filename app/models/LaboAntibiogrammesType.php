<?php

class LaboAntibiogrammesType extends \Phalcon\Mvc\Model
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'LaboAntibiogrammes', 'labo_antibiogrammes_type_id', array('alias' => 'LaboAntibiogrammes'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return LaboAntibiogrammesType[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return LaboAntibiogrammesType
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
