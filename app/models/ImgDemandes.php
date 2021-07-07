<?php

class ImgDemandes extends \Phalcon\Mvc\Model
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
    public $date;

    /**
     *
     * @var string
     */
    public $close_date;

    /**
     *
     * @var string
     */
    public $etat;

    /**
     *
     * @var string
     */
    public $provenance;

    /**
     *
     * @var string
     */
    public $prescripteur;

    /**
     *
     * @var string
     */
    public $indication;

    /**
     *
     * @var integer
     */
    public $patients_id;

    /**
     *
     * @var integer
     */
    public $prestations_id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'ImgDemandesDetails', 'img_demandes_id', array('alias' => 'ImgDemandesDetails'));
        $this->belongsTo('patients_id', 'Patients', 'id', array('alias' => 'Patients'));
        $this->belongsTo('prestations_id', 'Prestations', 'id', array('alias' => 'Prestations'));
        $this->belongsTo('user_id', 'User', 'id', array('alias' => 'User'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ImgDemandes[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ImgDemandes
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
