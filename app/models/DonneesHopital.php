<?php


class DonneesHopital extends \Phalcon\Mvc\Model
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
    public $code_asc;

    /**
     *
     * @var string
     */
    public $commentaire;

    /**
     *
     * @var string
     */
    public $date_rdv;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var integer
     */
    public $patients_id;

    /**
     *
     * @var string
     */
    public $created;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("target_db");
        $this->setSource("donnees_hopital");
        $this->belongsTo('patients_id', '\Patients', 'id', ['alias' => 'Patients']);
        $this->belongsTo('user_id', '\User', 'id', ['alias' => 'User']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DonneesHopital[]|DonneesHopital|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DonneesHopital|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
