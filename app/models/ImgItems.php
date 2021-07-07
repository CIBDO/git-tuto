<?php

class ImgItems extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $img_items_categories_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'ImgDemandesDetails', 'img_items_id', array('alias' => 'ImgDemandesDetails'));
        $this->belongsTo('img_items_categories_id', 'ImgItemsCategories', 'id', array('alias' => 'ImgItemsCategories'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ImgItems[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ImgItems
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
