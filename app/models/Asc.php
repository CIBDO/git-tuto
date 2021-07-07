<?php


class Asc extends \Phalcon\Mvc\Model
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
    public $nom;

    /**
     *
     * @var string
     */
    public $prenom;

    /**
     *
     * @var string
     */
    public $telephone;

    /**
     *
     * @var string
     */
    public $code_asc;

    /**
     *
     * @var string
     */
    public $profession;

    /**
     *
     * @var integer
     */
    public $residence_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("target_db");
        $this->setSource("asc");
        $this->hasMany('id', 'Patients', 'asc_id', ['alias' => 'Patients']);
        $this->belongsTo('residence_id', '\Residence', 'id', ['alias' => 'Residence']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Asc[]|Asc|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Asc|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
