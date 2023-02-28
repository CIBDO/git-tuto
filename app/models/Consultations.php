<?php

class Consultations extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $dossiers_consultations_id;

    /**
     *
     * @var string
     */
    public $observation;

    /**
     *
     * @var string
     */
    public $resultat_exam_comp;

    /**
     *
     * @var string
     */
    public $date_creation;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var integer
     */
    public $form_type_id;

    /**
     *
     * @var double
     */
    public $poids;

    /**
     *
     * @var double
     */
    public $taille;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'ConsultationsConstantes', 'consultations_id', array('alias' => 'ConsultationsConstantes'));
        $this->hasMany('id', 'ConsultationsExamen', 'consultations_id', array('alias' => 'ConsultationsExamen'));
        $this->hasMany('id', 'ConsultationsPrescriptions', 'consultations_id', array('alias' => 'ConsultationsPrescriptions'));
        $this->belongsTo('dossiers_consultations_id', 'DossiersConsultations', 'id', array('alias' => 'DossiersConsultations'));
        $this->belongsTo('user_id', 'User', 'id', array('alias' => 'User'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Consultations[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Consultations
     */
    

}
