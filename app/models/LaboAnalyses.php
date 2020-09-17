<?php

class LaboAnalyses extends \Phalcon\Mvc\Model
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
    public $code;

    /**
     *
     * @var string
     */
    public $unite;

    /**
     *
     * @var string
     */
    public $type_valeur;

    /**
     *
     * @var string
     */
    public $valeur_possible;

    /**
     *
     * @var string
     */
    public $norme;

    /**
     *
     * @var integer
     */
    public $labo_categories_analyse_id;

    /**
     *
     * @var string
     */
    public $childs_id;

    /**
     *
     * @var integer
     */
    public $position;

    /**
     *
     * @var integer
     */
    public $has_antibiogramme;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'LaboDemandesDetails', 'labo_analyses_id', array('alias' => 'LaboDemandesDetails'));
        $this->hasMany('id', 'LaboDemandesResultats', 'labo_analyses_id', array('alias' => 'LaboDemandesResultats'));
        $this->belongsTo('labo_categories_analyse_id', 'LaboCategoriesAnalyse', 'id', array('alias' => 'LaboCategoriesAnalyse'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'labo_analyses';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return LaboAnalyses[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return LaboAnalyses
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
