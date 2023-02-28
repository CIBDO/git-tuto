<?php

class ConsultationListeAttente extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $prestations_details_id;

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
     * @var integer
     */
    public $etat;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('prestations_details_id', 'PrestationsDetails', 'id', array('alias' => 'PrestationsDetails'));
        $this->belongsTo('user_id', 'User', 'id', array('alias' => 'User'));
        $this->belongsTo('patients_id', 'Patients', 'id', array('alias' => 'Patients'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConsultationListeAttente[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConsultationListeAttente
     */
    

}
