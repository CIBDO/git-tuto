<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class User extends \Phalcon\Mvc\Model
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
    public $email;

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
    public $login;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $profile;

    /**
     *
     * @var string
     */
    public $permissions;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $services_id;

    /**
     *
     * @var integer
     */
    public $prestataire;

    /**
     *
     * @var string
     */
    public $forms_assoc;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        /*$this->validate(
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

        return true;*/
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'ConsultationListeAttente', 'user_id', array('alias' => 'ConsultationListeAttente'));
        $this->hasMany('id', 'Consultations', 'user_id', array('alias' => 'Consultations'));
        $this->hasMany('id', 'DossiersConsultations', 'user_id', array('alias' => 'DossiersConsultations'));
        $this->hasMany('id', 'Prestations', 'user_id', array('alias' => 'PrestationsCaissier'));
        $this->hasMany('id', 'Prestations', 'user_id_annulation', array('alias' => 'PrestationsAnnulation'));
        $this->hasMany('id', 'PrestationsDetails', 'user_id', array('alias' => 'PrestationsDetails'));
        $this->belongsTo('services_id', 'Services', 'id', array('alias' => 'Services'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return User[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return User
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
