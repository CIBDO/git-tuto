<?php

class DossiersConsultations extends \Phalcon\Mvc\Model
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
    public $type;

    /**
     *
     * @var integer
     */
    public $patients_id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $motif;

    /**
     *
     * @var string
     */
    public $date_creation;

    /**
     *
     * @var string
     */
    public $debut_maladie;

    /**
     *
     * @var string
     */
    public $histoire;

    /**
     *
     * @var string
     */
    public $examen_clinique;

    /**
     *
     * @var string
     */
    public $commentaire;

    /**
     *
     * @var string
     */
    public $confidentialite;

    /**
     *
     * @var string
     */
    public $resume;

    /**
     *
     * @var string
     */
    public $resultat_exam_comp;

    /**
     *
     * @var string
     */
    public $etat;

    /**
     *
     * @var integer
     */
    public $form_type_id;

    /**
     *
     * @var string
     */
    public $debut_maladie_periode;

    /**
     *
     * @var string
     */
    public $refere_asc;

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
        $this->hasMany('id', 'Consultations', 'dossiers_consultations_id', array('alias' => 'Consultations'));
        $this->hasMany('id', 'ConsultationsConstantes', 'dossiers_consultations_id', array('alias' => 'ConsultationsConstantes'));
        $this->hasMany('id', 'ConsultationsDiagnostics', 'dossiers_consultations_id', array('alias' => 'ConsultationsDiagnostics'));
        $this->hasMany('id', 'ConsultationsExamen', 'dossiers_consultations_id', array('alias' => 'ConsultationsExamen'));
        $this->hasMany('id', 'ConsultationsMotifs', 'dossiers_consultations_id', array('alias' => 'ConsultationsMotifs'));
        $this->hasMany('id', 'ConsultationsPrescriptions', 'dossiers_consultations_id', array('alias' => 'ConsultationsPrescriptions'));
        $this->belongsTo('user_id', 'User', 'id', array('alias' => 'User'));
        $this->belongsTo('patients_id', 'Patients', 'id', array('alias' => 'Patients'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'dossiers_consultations';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DossiersConsultations[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DossiersConsultations
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
