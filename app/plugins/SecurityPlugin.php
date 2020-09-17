<?php

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin {

    /**
     * Returns an existing or new access control list
     *
     * @returns AclList
     */
    public function getAcl() {

        //throw new \Exception("something");

        //if (!isset($this->persistent->acl)) {

            $acl = new AclList();

            $acl->setDefaultAction(Acl::DENY);

            //Register roles
            $roles = array(
                'users' => new Role('Users'),
                'guests' => new Role('Guests')
            );
            foreach ($roles as $role) {
                $acl->addRole($role);
            }

            //Resources accessibles à tous les utilisateurs
            $privateResources = array(
                'index' => array('index', 'swipetranslation', 'frLanguage', 'enLanguage', 'index1'),
                'useraccounts' => array('index', 'userslist', 'create', 'delete', 'modal', 'updateUser'),
                'services' => array('index', 'createService', 'editService', 'deleteService', 'showChambre'),
                'formulaires' => array('index', 'createFormulaires', 'editFormulaires', 'deleteFormulaires', "elements", 'deleteItem'),
                'patients' => array('index', 'form', 'dossier', 'assurancePopover', 'deleteAssurance', 'addAssurance', 'ajaxPatient', 'getAssuranceInfos', 'dashboard'),
                'type_assurance' => array('index', 'createTypeAssurance', 'editTypeAssurance', 'deleteTypeAssurance'),
                'type_produit' => array('index', 'createTypeProduit', 'editTypeProduit', 'deleteTypeProduit'),
                'residence' => array('index', 'createResidence', 'editResidence', 'deleteResidence'),
                'forme_produit' => array('index', 'createFormeProduit', 'editFormeProduit', 'deleteFormeProduit'),
                'produit' => array('index', 'createProduit', 'editProduit', 'deleteProduit', "ajaxProduit", "ajaxProduitCaisse", 'fiche'),
                'commande' => array('index', 'createCommande', 'editCommande', 'deleteCommande', 'details', 'deleteDetailsItemCommande', 'ajaxProduit', 'detailsAjout'),
                'reception' => array('index', 'createReception', 'editReception', 'deleteReception', 'details', 'deleteDetailsItemReception', 'ajaxProduit', 'cloture', 'detailsAjout'),
                'inventaire' => array('index', 'createInventaire', 'editInventaire', 'deleteInventaire', 'details', 'deleteDetailsItemInventaire', 'ajaxProduit', 'cloture', 'detailsAjout', 'searchProduitStock'),
                'approvisionnement' => array('index'),
                'stock_point_distribution' => array('index', 'createDistribution'),
                'ajustement' => array('index', 'createAjustement', 'searchProduitStock'),
                'point_distribution' => array('index', 'createPointDistribution', 'editPointDistribution', 'deletePointDistribution'),
                'ajustement_motifs' => array('index', 'createAjustementMotifs', 'editAjustementMotifs', 'deleteAjustementMotifs'),
                'fournisseur' => array('index', 'createFournisseur', 'editFournisseur', 'deleteFournisseur'),
                'f_banque' => array('index', 'createFBanque', 'editFBanque', 'deleteFBanque'),
                'f_compte' => array('index', 'createFCompte', 'editFCompte', 'deleteFCompte'),
                'f_designation' => array('index', 'createFDesignation', 'editFDesignation', 'deleteFDesignation'),
                'f_operation' => array('index', 'createFOperation', 'editFOperation', 'deleteFOperation', 'dashboard'),
                'f_planification' => array('index', 'createFPlanification', 'editFPlanification', 'deleteFPlanification', 'etatProgressPlanif'),
                'f_sous_compte' => array('index', 'createFSousCompte', 'editFSousCompte', 'deleteFSousCompte'),
                'classe_therapeutique' => array('index', 'createClasseTherapeutique', 'editClasseTherapeutique', 'deleteClasseTherapeutique'),
                //LABO
                'labo_categories_analyse' => array('index', 'createLaboCategoriesAnalyse', 'editLaboCategoriesAnalyse', 'deleteLaboCategoriesAnalyse'),
                'labo_antibiogrammes_type' => array('index', 'createLaboAntibiogrammesType', 'editLaboAntibiogrammesType', 'deleteLaboAntibiogrammesType'),
                'labo_antibiogrammes' => array('index', 'createLaboAntibiogrammes', 'editLaboAntibiogrammes', 'deleteLaboAntibiogrammes'),
                'labo_antibiotiques' => array('index', 'createLaboAntibiotiques', 'editLaboAntibiotiques', 'deleteLaboAntibiotiques'),
                'labo_analyses' => array('index', 'form', 'addChild', 'editChild', 'deleteLaboAnalyses'),
                'labo_demandes' => array('index', 'listeAttente', 'dossier', 'editDossier', 'editDossier2', 'editAntibiogramme', 'detailsAntibiogramme', 'ajaxPrescripteur', 'ajaxProvenance', 'demandes', 'clotureDemande', 'createDemande', 'validItem', 'imprimEnvelop', 'dashboard'),

                //IMG
                'img_items_categories' => array('index', 'createImgItemsCategories', 'editImgItemsCategories', 'deleteImgItemsCategories'),
                'img_items' => array('index', 'createImgItems', 'editImgItems', 'deleteImgItems'),
                'img_modele' => array('index', 'createImgModele', 'editImgModele', 'deleteImgModele', 'importModele'),
                'img_demandes' => array('index', 'listeAttente', 'dossier', 'editDossier2','ajaxPrescripteur', 'ajaxProvenance', 'demandes', 'clotureDemande', 'createDemande', 'imprimEnvelop', 'dashboard'),

                'user' => array('index', 'createUser', 'editUser', 'deleteUser'),
                'actes' => array('index', 'createActe', 'editActe', 'deleteActe', 'ajaxCaisse', 'ajaxActeLabo'),
                'print' => array('index', 'ticket', 'recuMedicament', 'detailsCommande', 'detailsReception', 'detailsInventaire', 'laboDemande', 'imgDemande', 'imgDemandeEtiquette', "laboDemandeEnveloppe", "rembourssementsPrestations", "rembourssementsPharmacie"),
                'cs_motifs' => array('index', 'createCsMotifs', 'editCsMotifs', 'deleteCsMotifs'),
                'diagnostic_source' => array('index', 'createDiagnosticSource', 'editDiagnosticSource', 'deleteDiagnosticSource'),
                'consultation' => array('index', 'consultation', 'createConsultation', 'dossierSuivis', 'createSuivi', 'antecedantPopover', 'deleteAntecedant', 'addAntecedant', 'listeAttente', 'ajaxCim10', 'dashboard', 'liste', 'ajaxPrescriptionMode', 'ajaxPrescriptionPosologie', 'ajaxPrescriptionDuree', 'ajaxDiagnostic', 'exportCustomForm', 'exportRTA'),
                'laboratoire' => array('index'),
                'caisse' => array('index', 'etatTicket', 'dashboard', 'cancelTicket', 'etatTicketDetails'),
                'caisse_pharmacie' => array('index', 'changePointDeVente', 'etatRecu', 'dashboard', 'cancelTicket', 'etatRecuDetails', 'openOrdonnanceList'),
                'f_rembourssement' => array('index'),
                'notifications' => array('index', 'setNotificationsRead', 'setNotificationsReadAll', 'create', 'delete', 'editModal', 'edit', 'sendbox', 'deletePoi', 'listNotif', 'exchanges'),
                'account' => array('index', 'editPassword', 'editInfos', 'expiryPassword', 'expirypassword'),
                'support' => array('index', 'sendQuestion'),
                'parametrage' => array('index')
            );

            /*//Ressources accessibles en fonction des scopes
            $resourcesByScope = array(
                'manage_users' => array('useraccounts' => array('index', 'userslist', 'create', 'delete', 'modal', 'updateUser'))
            );*/

            foreach ($privateResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            //Public area resources
            $publicResources = array(
                //'index' => array('index', 'index1'),
                'authen' => array('login', 'logout', 'sendmailpwdreset', 'resetpwd', 'emailformpwdreset', 'index'),
                'errors' => array('show401', 'show404', 'show500')
            );
            
            foreach ($publicResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }

            //Grant access to public areas to both users and guests
            foreach ($roles as $role) {
                foreach ($publicResources as $resource => $actions) {
                    foreach ($actions as $action) {
                        $acl->allow($role->getName(), $resource, $action);
                    }
                }
            }

            //Grant acess to private area to role Users
            foreach ($privateResources as $resource => $actions) {
                foreach ($actions as $action) {
                    $acl->allow('Users', $resource, $action);
                }
            }

            //The acl is stored in session, APC would be useful here too
            $this->persistent->acl = $acl;
       //}

        return $this->persistent->acl;
    }

    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher) {

        $auth = $this->session->get('usr');
        if (!$auth) {
            $role = 'Guests';
        } else {
            $role = 'Users';
        }

        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        $acl = $this->getAcl();

        $allowed = $acl->isAllowed($role, $controller, $action);
        if ($allowed != Acl::ALLOW && $action!="img") {
            $dispatcher->forward(array(
                'controller' => 'errors',
                'action' => 'show401'
            ));
            //$this->session->destroy();
            return false;
        }

        if (!$auth && $controller!="authen"){
            $dispatcher->forward(array(
                'controller' => 'authen',
                'action' => 'index'
            ));
        }

    }

}
