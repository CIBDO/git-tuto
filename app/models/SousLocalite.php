<?php

class SousLocalite extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $residence_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("target_db");
        $this->setSource("sous_localite");
        $this->belongsTo('residence_id', '\Residence', 'id', ['alias' => 'Residence']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SousLocalite[]|SousLocalite|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SousLocalite|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
