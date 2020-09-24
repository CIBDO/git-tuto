<?php

use Phalcon\Mvc\Controller;

require('../vendor/autoload.php');
use DeviceDetector\DeviceDetector;
use Phalcon\Config\Adapter\Ini as IniConfig;

class ControllerBase extends Controller {

    protected $isMobile;
    protected $trans;

    protected function initialize() {

        $this->view->setVar("isAjax", $this->request->isAjax());
        $this->view->setVar("scriptLoaded", 0);
        $this->tag->prependTitle('Target | ');
        
        //js
        $this->_getJsAssets();

        //css
        $this->_getCssAssets();

        $this->view->structureConfig = $this->getStructureConfig();

        $this->view->setTemplateAfter('main');
        $language = $this->setLanguage();
        $this->trans = $this->getTranslation($language);

        $this->view->language = $language;
        $this->view->setVar("trans", $this->getTranslation($language));
        $this->view->setVar("pageTitle", $this->getPageTitle());
        $this->view->setVar('controllerName', $this->router->getControllerName());
        $this->view->userId = json_decode($this->session->get('usr')['id']);
        $this->view->userPermissions = json_decode($this->session->get('usr')['permissions']);
        $this->view->activeModules = $this->config->activeModules;
        $this->view->stucture_name_config = $this->config->application->stucture_name_config;
    }

    private function _getJsAssets(){
        $this->assets
            ->collection('target_final_js')
            ->addJs('bower_components/jquery/dist/jquery.min.js', false, false)
            ->addJs('bower_components/jquery-ui/jquery-ui.min.js', false, false)
            ->addJs('bower_components/AdminLTE/bootstrap/js/bootstrap.min.js', false, false)
            ->addJs('bower_components/AdminLTE/dist/js/app.min.js', false, false)
            ->addJs('bower_components/jquery.inputmask/dist/min/inputmask/inputmask.min.js', false, false)
            ->addJs('bower_components/jquery.inputmask/dist/min/inputmask/jquery.inputmask.min.js', false, false)
            ->addJs('bower_components/jquery.inputmask/dist/min/inputmask/inputmask.date.extensions.min.js', false, false)
            ->addJs('bower_components/jquery.inputmask/dist/min/inputmask/inputmask.extensions.min.js', false, false)
            ->addJs('bower_components/bs-typeahead/js/bootstrap-typeahead.min.js', false, false)
            ->addJs('bower_components/select2/dist/js/select2.full.min.js', false, false)

            ->addJs('bower_components/jspdf/dist/jspdf.min.js', false, false)
            ->addJs('bower_components/jspdf-autotable/dist/jspdf.plugin.autotable.src.js', false, false)

            ->addJs('bower_components/tableExport.jquery.plugin/tableExport.min.js', false, false)
            ->addJs('bower_components/bootstrap-table/dist/bootstrap-table.min.js', false, false)
            ->addJs('bower_components/bootstrap-table/dist/locale/bootstrap-table-fr-FR.js', false, true)
            ->addJs('bower_components/bootstrap-table/dist/extensions/export/bootstrap-table-export.min.js', false, false)
            ->addJs('bower_components/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js', false, false)
            ->addJs('bower_components/bootstrap-table/dist/extensions/multiple-sort/bootstrap-table-multiple-sort.min.js', false, false)
            ->addJs('bower_components/bootstrap-table/dist/extensions/filter/bootstrap-table-filter.min.js', false, false)
            ->addJs('bower_components/bootstrap-table/dist/extensions/filter-control/bootstrap-table-filter-control.min.js', false, false)
            ->addJs('bower_components/bootstrap-table-fixed-columns/bootstrap-table-fixed-columns.js', false, true)
            ->addJs('bower_components/bootstrap-table-flatJSON/dist/bootstrap-table-flatJSON.min.js', false, false)
            ->addJs('bower_components/moment/min/moment.min.js', false, false)
            ->addJs('bower_components/numeral/min/numeral.min.js', false, false)
            ->addJs('bower_components/numeral/languages/fr.js', false, true)
            ->addJs('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js', false, false)
            ->addJs('bower_components/amcharts3/amcharts/amcharts.js', false, false)
            ->addJs('bower_components/amcharts3/amcharts/serial.js', false, false)
            ->addJs('bower_components/amcharts3/amcharts/pie.js', false, false)
            ->addJs('bower_components/amcharts3/amcharts/themes/light.js', false, true)
            ->addJs('bower_components/sweetalert2/dist/sweetalert2.min.js', false, false)
            ->addJs('bower_components/JsBarcode/dist/JsBarcode.all.min.js', false, false)


            ->addJs('assets/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js', false, false)
            
            //->addJs('bower_components/js-marker-clusterer/src/markerclusterer_compiled.js', false, true)

            // Les js customisé de target seront dans ce fichier
            ->addJs('js/target_custom.js', false, true)
            ->setTargetPath('js/target_final.js')
            ->setTargetUri('js/target_final.js')
            ->join(true)
            ->addFilter(new Phalcon\Assets\Filters\Jsmin());
    }

    private function _getCssAssets(){
        $this->assets
            ->collection('target_final_css')
            ->addCss('bower_components/AdminLTE/bootstrap/css/bootstrap.min.css', false, false)
            ->addCss('bower_components/font-awesome/css/font-awesome.min.css', false, false)
            ->addCss('bower_components/AdminLTE/dist/css/AdminLTE.min.css', false, false)
            ->addCss('bower_components/AdminLTE/dist/css/skins/_all-skins.min.css', false, false)
            ->addCss('bower_components/bootstrap-table/dist/bootstrap-table.min.css', false, false)
            ->addCss('bower_components/bootstrap-table-fixed-columns/bootstrap-table-fixed-columns.css', false, true)
            ->addCss('bower_components/select2/dist/css/select2.min.css', false, false)
            //->addCss('css/select2-bootstrap.min.css', false, false)
            ->addCss('bower_components/sweetalert2/dist/sweetalert2.min.css', false, false)
            ->addCss('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css', false, false)
            ->addCss('bower_components/amcharts3/amcharts/plugins/export/export.css', false, true)
           
            ->addCss('assets/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css', false, false)
           
            //Les css personnalisés seront dans ce fichier
            ->addCss('css/target_custom.css', false, true)
            ->setTargetPath('css/target_final.css')
            ->setTargetUri('css/target_final.css')
            ->join(true)
            ->addFilter(new Phalcon\Assets\Filters\Cssmin());
    }

    protected function forward($uri) {
        $uriParts = explode('/', $uri);
        $params = array_slice($uriParts, 2);
        return $this->dispatcher->forward(
            array(
                'controller' => $uriParts[0],
                'action' => $uriParts[1],
                'params' => $params
            )
        );
    }

    protected function getFormMessages($form) {
        $msg = "";
        foreach ($form->getMessages() as $message) {
            $msg .= $this->trans["$message"] . "<br>";
        }

        return $msg;
    }

    public function setLanguage() {
        if ($this->session->has("language")) {
            $language = $this->session->get("language");
        } else {
            // Ask browser what is the best language
            $language = substr($this->request->getBestLanguage(), 0, 2);
            $this->session->set('language', $language);
        }
        return $language;
    }

    public function frLanguageAction() {
        $request = new Phalcon\Http\Request();
        $uri = $request->getHTTPReferer();
        $this->session->remove("language");
        $language = "fr";
        $this->session->set('language', $language);
        $this->view->setVar("trans", $this->getTranslation($language));
        $this->response->redirect($uri);
    }

    public function enLanguageAction() {
        $request = new Phalcon\Http\Request();
        $uri = $request->getHTTPReferer();
        $this->session->remove("language");
        $language = "en";
        $this->session->set('language', $language);
        $this->view->setVar("trans", $this->getTranslation($language));
        $this->response->redirect($uri);
    }

    /**
     *
     * @return \Phalcon\Translate\Adapter\NativeArray
     */
    public function getTranslation($language) {
        if ($language == "fr") {
            //$this->session->set("language", $language);
            // Check if we have a translation file for that language
            if (file_exists(APP_PATH . "app/languages/" . $language . ".php")) {
                require APP_PATH . "app/languages/" . $language . ".php";
            }
        } else {
            require APP_PATH . "app/languages/en.php";
        }

        // Return a translation object
        return new \Phalcon\Translate\Adapter\NativeArray(
                array(
            "content" => $messages
                )
        );
    }

    public function getDate($date) {
        $language = setLanguage();
        if (is_int($date)) {
            if ($language == 'fr') {
                $formatDate = date("d/m/Y H:i:s", $date);
            } else {
                $formatDate = date("m/d/Y H:i:s", $date);
            }
        } else {
            if ($language == 'fr') {
                $formatDate = date("d/m/Y H:i:s", strtotime($date));
            } else {
                $formatDate = date("m/d/Y H:i:s", strtotime($date));
            }
        }
        return $formatDate;
    }


    /*
     * This function generate data for the notifications Widget
     *
     * @return void
     */

    public function notificationsWidget() {
        $api2 = $this->di->get('api');
        $notifications = $api2->getNotificationsByUserID(0, null);
        if (!isset($notifications->code)) {
            $this->view->setVar("notificationsCount", count($notifications));
            if (sizeof($notifications) > 5) {
                array_splice($notifications, 5);
            }

            //Format Date for notifications and build à list of notifications id
            $id = array();
            foreach ($notifications as $key => $value) {
                $dateNotif = strtotime($value->created);
                $notifications[$key]->date = $this->differenceDate(time(), $dateNotif);
                $id[] = $value->id;
            }
            $notificationsIDLists = implode(",", $id);

            $this->view->setVar("notifications", $notifications);
            $this->session->set("notifications", count($notifications));
            $this->view->setVar("notificationsIDLists", $notificationsIDLists);
        }
    }

     public function getPageTitle(){
        $ctrlName = $this->router->getControllerName();
        $ctrlAction = $this->router->getActionName();

        $conversion = array(
            "index" => array(
                "index" => "Tableau de bord"
                ),
            "notifications" => array(
                "index" => "Mes notifications"
                ),
            "account" => array(
                "index" => "Mon compte"
                ),
            "support" => array(
                "index" => "Support"
                ),
            );
        if($ctrlName == "" || $ctrlName == NULL){
            $ctrlName = "index";
        }
        if($ctrlAction == "" || $ctrlAction == NULL){
            $ctrlAction = "index";
        }
        return (isset($conversion[$ctrlName][$ctrlAction]) ? $conversion[$ctrlName][$ctrlAction] : "");
    }

    /*
     *   Calculation of difference between 2 date
     *
     *   @params strtotime $date1
     *   @params strtotime $date2
     *
     *   @return String
     */

    public function differenceDate($date1, $date2) {
        $differenceHours = ($date1 - $date2) / 3600;
        if ($differenceHours > 24) {
            $differenceDay = 0;
            // For each 24h, add 1 day
            while ($differenceHours > 24) {
                $differenceHours -= 24;
                $differenceDay++;
            }

            if ($differenceDay <= 7) {
                return $differenceDay . " Jours";
            } else {
                $differenceWeek = 0;
                // Foreach 7 days, add 1 week
                while ($differenceDay >= 7) {
                    $differenceDay -= 7;
                    $differenceWeek++;
                }
                return "Il y a " . $differenceWeek . " Semaines";
            }
        } else if ($differenceHours < 1) {
            return " Moins d'1 Minutes";
        } else {
            return round($differenceHours) . " Heures";
        }
    }

    public function getProduitInfos(Produit $produit){

        $rs['id']        = $produit->id;
        $rs['forme']     = ( $tmp = $produit->getFormeProduit() ) ? "-" . $tmp->libelle : "";
        $tmpDosage       = ( $produit->dosage != "" ) ? "-" . $produit->dosage : "";
        $rs['libelle']   = $produit->libelle . $rs['forme'] . $tmpDosage;
        $rs['dosage']    = $produit->dosage;
        $rs['stock']     = $produit->stock;
        $rs['prix']      = $produit->prix;
        $rs['type']      = ( $tmp = $produit->getTypeProduit() ) ? $tmp->libelle : "";
        $rs['classe_th'] = ( $tmp = $produit->getClasseTherapeutique() ) ? $tmp->libelle : "";

        return $rs;
    }

    public function createRuptureStock(Produit $produit){
            $ruptureStock = new RuptureStock();
            $ruptureStock->date_rupture = date("Y-m-d");
            $ruptureStock->produit_id   = $produit->id;
            $ruptureStock->save();
    }
    public function checkAndUpdateRuptureStock(Produit $produit){
        if($produit->stock > 0){
            $ruptureStock = RuptureStock::find(array("produit_id = " . $produit->id . " AND date_appro IS NULL", 
                                                    "limit" => 1) );
            if(count($ruptureStock)>0){
                $ruptureStock[0]->date_appro = date("Y-m-d");
                $ruptureStock[0]->save();
            }
        }
    }

    protected function getStructureConfig(){
        $parametrage = Parametrage::find();
        if(count($parametrage) > 0){
            $parametrage = $parametrage[0];
        }
        else{
            $parametrage = new Parametrage();
        }
        return $parametrage;
    }

    protected function getLaboPaillasse(){
        
        return time();
    }
    protected function objectToArray($object){
        return json_decode(json_encode($object), true);
    }
}
