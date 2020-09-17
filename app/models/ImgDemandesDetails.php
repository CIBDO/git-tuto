<?php

class ImgDemandesDetails extends \Phalcon\Mvc\Model
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
    public $protocole;

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
     * @var integer
     */
    public $img_demandes_id;

    /**
     *
     * @var integer
     */
    public $img_items_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('img_demandes_id', 'ImgDemandes', 'id', array('alias' => 'ImgDemandes'));
        $this->belongsTo('img_items_id', 'ImgItems', 'id', array('alias' => 'ImgItems'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'img_demandes_details';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ImgDemandesDetails[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ImgDemandesDetails
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
