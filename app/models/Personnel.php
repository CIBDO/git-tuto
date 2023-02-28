<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class Personnel extends \Phalcon\Mvc\Model
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
    public $prenom;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $telephone;

    /**
     *
     * @var string
     */
    public $profil;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var integer
     */
    public $services_id;

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
        $this->belongsTo('user_id', 'User', 'id', array('alias' => 'User'));
        $this->belongsTo('services_id', 'Services', 'id', array('alias' => 'Services'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Personnel[]
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Personnel
     */
    

}
