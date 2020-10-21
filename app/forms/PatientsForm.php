<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Date;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValid;
use Phalcon\Validation\Validator\Regex as RegexValidator;


class PatientsForm extends Form
{

    public function initialize($trans)
    {

        //id_technique
        $id_technique = new Text('id_technique', ["class" => "form-control input-md"]);
        $id_technique->setLabel($trans['ID Technique']);
        $id_technique->setFilters(array('striptags', 'trim'));
        $this->add($id_technique);

        //nom
        $nom = new Text('nom', ["class" => "form-control input-md", "required" => "required"]);
        $nom->setLabel($trans['Nom']);
        $nom->setFilters(array('striptags', 'trim'));
        $nom->addValidators(array(
            new PresenceOf(array(
                'message' => 'Name is required'
            ))
        ));
        $this->add($nom);

        //prenom
        $prenom = new Text('prenom', ["class" => "form-control input-md", "required" => "required"]);
        $prenom->setLabel($trans['Prenom']);
        $prenom->setFilters(array('striptags', 'trim'));
        $prenom->addValidators(array(
            new PresenceOf(array(
                'message' => 'Prenom is required'
            ))
        ));
        $this->add($prenom);

        //prenom2
        $prenom2 = new Text('prenom2', ["class" => "form-control input-md"]);
        $prenom2->setLabel($trans['DeuxiemePrenom ']);
        $prenom2->setFilters(array('striptags'));
        $this->add($prenom2);

        //nom_conjoint
        $nom_conjoint = new Text('nom_conjoint', ["class" => "form-control input-md"]);
        $nom_conjoint->setLabel($trans['Nom du conjoint (De la conjonte)']);
        $nom_conjoint->setFilters(array('striptags'));
        $this->add($nom_conjoint);

        //contact_conjoint
        $contact_conjoint = new Text('contact_conjoint', ["class" => "form-control input-md", 'data-inputmask' => '"mask": "+[223] 99-99-99-99"', 'data-mask' => '']);
        $contact_conjoint->setLabel($trans['Contact du conjoint (De la conjointe)']);
        $contact_conjoint->setFilters(array('striptags'));
        $this->add($contact_conjoint);

        //nom_pere
        $nom_pere = new Text('nom_pere', ["class" => "form-control input-md"]);
        $nom_pere->setLabel($trans['Nom Pere']);
        $nom_pere->setFilters(array('striptags'));
        $this->add($nom_pere);

        //contact_pere
        $contact_pere = new Text('contact_pere', ["class" => "form-control input-md", 'data-inputmask' => '"mask": "+[223] 99-99-99-99"', 'data-mask' => '']);
        $contact_pere->setLabel($trans['Contact Pere']);
        $contact_pere->setFilters(array('striptags'));
        $this->add($contact_pere);

        //nom_mere
        $nom_mere = new Text('nom_mere', ["class" => "form-control input-md"]);
        $nom_mere->setLabel($trans['Nom Mere']);
        $nom_mere->setFilters(array('striptags'));
        $this->add($nom_mere);

        //contact_mere
        $contact_mere = new Text('contact_mere', ["class" => "form-control input-md", 'data-inputmask' => '"mask": "+[223] 99-99-99-99"', 'data-mask' => '']);
        $contact_mere->setLabel($trans['Contact Mere']);
        $contact_mere->setFilters(array('striptags'));
        $this->add($contact_mere);

        //personne_a_prev
        $personne_a_prev = new Text('personne_a_prev', ["class" => "form-control input-md"]);
        $personne_a_prev->setLabel($trans['Personne à prevenir']);
        $personne_a_prev->setFilters(array('striptags'));
        $this->add($personne_a_prev);

        //nom_jeune_fille
        $nom_jeune_fille = new Text('nom_jeune_fille', ["class" => "form-control input-md"]);
        $nom_jeune_fille->setLabel($trans['Nom de jeune fille']);
        $nom_jeune_fille->setFilters(array('striptags'));
        $this->add($nom_jeune_fille);

        //Sexe
        $sexe = new Select('sexe', array('m' => 'Masculin', 'f' => 'Feminin'),
            ["class" => "form-control", "id" => "sexe", "required" => "required", "useEmpty" => true]);
        $sexe->setLabel($trans['Sexe']);
        $sexe->addValidators(array(
            new PresenceOf(array(
                'message' => 'Sexe is required'
            ))
        ));
        $sexe->setFilters(array('striptags'));
        $this->add($sexe);

        //adresse
        $adresse = new Text('adresse', ["class" => "form-control input-md"]);
        $adresse->setLabel($trans['Adresse']);
        $adresse->setFilters(array('striptags'));
        $this->add($adresse);

        //date_naissance
        $date_naissance = new Date('date_naissance', ["class" => "form-control input-md"]);
        $date_naissance->setLabel($trans['Date de naissance']);
        $date_naissance->addValidators(array(
            new PresenceOf(array(
                'message' => 'Date de naissance is required'
            ))
        ));
        $date_naissance->setFilters(array('striptags'));
        $this->add($date_naissance);

        //profession
        $profession = new Text('profession', ["class" => "form-control input-md"]);
        $profession->setLabel($trans['Profession']);
        $profession->setFilters(array('striptags'));
        $this->add($profession);

        //ethnie
        $ethnie = new Text('ethnie', ["class" => "form-control input-md"]);
        $ethnie->setLabel($trans['Ethnie']);
        $ethnie->setFilters(array('striptags'));
        $this->add($ethnie);

        //telephone
        $telephone = new Text('telephone', ["class" => "form-control input-md", 'data-inputmask' => '"mask": "99-99-99-99"', 'data-mask' => '']);
        $telephone->setLabel($trans['Telephone']);
        $telephone->setFilters(array('striptags'));
        $this->add($telephone);

        //telephone2
        $telephone2 = new Text('telephone2', ["class" => "form-control input-md", 'data-inputmask' => '"mask": "99-99-99-99"', 'data-mask' => '']);
        $telephone2->setLabel($trans['Telephone2']);
        $telephone2->setFilters(array('striptags'));
        $this->add($telephone2);

        //email
        $email = new Email('email', ["class" => "form-control input-md"]);
        $email->setLabel($trans['Email']);
        $email->setFilters(array('striptags', 'email'));
        $this->add($email);

        //autre_infos
        $autre_infos = new Text('autre_infos', ["class" => "form-control input-md"]);
        $autre_infos->setLabel($trans['Autres informations']);
        $autre_infos->setFilters(array('striptags'));
        $this->add($autre_infos);

        //Residence_id
        $residence_id = new Select('residence_id',
            SousLocalite::find(),
            ['using' => array('id', 'libelle'), "class" => "form-control", "id" => "residence_id", "required" => "required", "useEmpty" => true]
        );
        $residence_id->setLabel($trans['Profil']);
        $residence_id->addValidators(array(
            new PresenceOf(array(
                'message' => 'Residence is required'
            ))
        ));
        $residence_id->setFilters(array('striptags'));
        $this->add($residence_id);

        //asc_id
        $asc_id = new Select('asc_id',
            Asc::query()
                ->columns("id as id, CONCAT(code_asc, ' ', prenom, ' ', nom) as libelle")
                ->orderBy("libelle")
                ->execute(),
            ['using' => array('id', 'libelle'), "class" => "form-control", "id" => "asc_id", "useEmpty" => true]
        );
        $asc_id->setLabel($trans['Profil']);
        $asc_id->setFilters(array('striptags'));
        $this->add($asc_id);

    }
}
