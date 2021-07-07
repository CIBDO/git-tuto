<?php


use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as Email;

class Patients extends \Phalcon\Mvc\Model
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
    public $id_technique;

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
    public $prenom2;

    /**
     *
     * @var string
     */
    public $nom_conjoint;

    /**
     *
     * @var string
     */
    public $contact_conjoint;

    /**
     *
     * @var string
     */
    public $nom_pere;

    /**
     *
     * @var string
     */
    public $nom_mere;

    /**
     *
     * @var string
     */
    public $contact_pere;

    /**
     *
     * @var string
     */
    public $contact_mere;

    /**
     *
     * @var string
     */
    public $personne_a_prev;

    /**
     *
     * @var string
     */
    public $nom_jeune_fille;

    /**
     *
     * @var string
     */
    public $date_naissance;

    /**
     *
     * @var string
     */
    public $sexe;

    /**
     *
     * @var string
     */
    public $adresse;

    /**
     *
     * @var string
     */
    public $ethnie;

    /**
     *
     * @var string
     */
    public $profession;

    /**
     *
     * @var string
     */
    public $telephone;

    /**
     *
     * @var string
     */
    public $telephone2;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $autre_infos;

    /**
     *
     * @var string
     */
    public $date_creation;

    /**
     *
     * @var integer
     */
    public $residence_id;

    /**
     *
     * @var integer
     */
    public $asc_id;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    /*public function validation()
    {
        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );

        if ($this->validationHasFailed() == true) {
            return false;
        }

        return true;
    }*/

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("target_db");
        $this->setSource("patients");
        $this->hasMany('id', 'ConsultationListeAttente', 'patients_id', ['alias' => 'ConsultationListeAttente']);
        $this->hasMany('id', 'DonneesHopital', 'patients_id', ['alias' => 'DonneesHopital']);
        $this->hasMany('id', 'DossiersConsultations', 'patients_id', ['alias' => 'DossiersConsultations']);
        $this->hasMany('id', 'ImgDemandes', 'patients_id', ['alias' => 'ImgDemandes']);
        $this->hasMany('id', 'LaboDemandes', 'patients_id', ['alias' => 'LaboDemandes']);
        $this->hasMany('id', 'PatientsAntecedant', 'patients_id', ['alias' => 'PatientsAntecedant']);
        $this->hasMany('id', 'PatientsAssurance', 'patients_id', ['alias' => 'PatientsAssurance']);
        $this->hasMany('id', 'PharmacieWorkFlow', 'patients_id', ['alias' => 'PharmacieWorkFlow']);
        $this->hasMany('id', 'Prestations', 'patients_id', ['alias' => 'Prestations']);
        $this->hasMany('id', 'RecuMedicament', 'patients_id', ['alias' => 'RecuMedicament']);
        $this->belongsTo('asc_id', '\Asc', 'id', ['alias' => 'Asc']);
        $this->belongsTo('residence_id', '\Residence', 'id', ['alias' => 'Residence']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Patients[]|Patients|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Patients|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
