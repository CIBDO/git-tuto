<?php

class LaboCategoriesAnalyse extends \Phalcon\Mvc\Model
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
        $this->hasMany('id', 'LaboAnalyses', 'labo_categories_analyse_id', array('alias' => 'LaboAnalyses'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return LaboCategoriesAnalyse[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return LaboCategoriesAnalyse
     */
    

}
