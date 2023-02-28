<?php

class Parametrage extends \Phalcon\Mvc\Model
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
    public $adresse;

    /**
     *
     * @var string
     */
    public $telephone;

    /**
     *
     * @var string
     */
    public $logo;

    /**
     *
     * @var string
     */
    public $pharmacie_type;

    /**
     *
     * @var string
     */
    public $default_lot;

    /**
     *
     * @var string
     */
    public $default_peremption;

    /**
     *
     * @var string
     */
    public $default_coef;

    /**
     *
     * @var string
     */
    public $default_constante;

    /**
     *
     * @var string
     */
    public $default_examen;

    /**
     *
     * @var string
     */
    public $diagnostic_source;

    /**
     *
     * @var string
     */
    public $type_entete;

    /**
     *
     * @var string
     */
    public $ligne1;

    /**
     *
     * @var string
     */
    public $ligne2;

    /**
     *
     * @var string
     */
    public $ligne3;

    /**
     *
     * @var string
     */
    public $ligne4;

    /**
     *
     * @var string
     */
    public $template_logo;

    /**
     *
     * @var string
     */
    public $img_msg_annonce;

    /**
     *
     * @var string
     */
    public $img_msg_fin;

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Parametrage[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Parametrage
     */
    

}
