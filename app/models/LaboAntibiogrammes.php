<?php

class LaboAntibiogrammes extends \Phalcon\Mvc\Model
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
    public $antibiotiques;

    /**
     *
     * @var integer
     */
    public $labo_antibiogrammes_type_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'LaboAntibiotiques', 'labo_antibiogrammes_id', array('alias' => 'LaboAntibiotiques'));
        $this->belongsTo('labo_antibiogrammes_type_id', 'LaboAntibiogrammesType', 'id', array('alias' => 'LaboAntibiogrammesType'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'labo_antibiogrammes';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return LaboAntibiogrammes[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return LaboAntibiogrammes
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
