<?php

class NotificationsController extends ControllerBase {

    public function initalize() {
        $this->tag->setTitle('Notifications');
        parent::initialize();


    }

    public function indexAction() {
        $api = $this->di->get('api');

        //Récupération catalogue des notifications
        $alertsCatalog = $this->session->get("alertsCatalog");
        if(!isset($alertsCatalog)){
            $alertsCatalog = $api->getAlertsCatalog();
            $this->session->set("alertsCatalog", $alertsCatalog);
        }

        $usersAlerts = $api->getUsersAlerts();
        $alreadyUsed = $this->getAlreadyUsedList($usersAlerts, $alertsCatalog);

        //Récupération liste des pois
        $devices = $this->session->get("devices");
        if(!isset($devices)){
            $devices = $api->getPoisDevices();
            $this->session->set("devices", $devices);
        }

        $monthDecrement = strtotime("-1 month", strtotime(date('m/d/Y 00:00:00')));
        $locale = Locale::acceptFromHttp($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
        $start = MessageFormatter::formatMessage($locale, '{0, date, full}.', array($monthDecrement));

        $notifications = $api->getNotificationsByUserID(null, $monthDecrement, null);

            $sortNotifications = $this->sortByDay($notifications);
            $this->view->setVar("notificationsFull", $sortNotifications);
            $this->view->setVar("startDate", $start);
            $this->view->partial("notifications/listNotif");
        $this->view->setVar('alertsCatalogListing', $this->catalogFormatter($alertsCatalog));
        $this->view->setVar("alertsCatalog", $alertsCatalog);
        $this->view->setVar("alertsCatalogJson", json_encode($alertsCatalog, JSON_PRETTY_PRINT));
        $this->view->setVar("usersAlerts", $usersAlerts);
        $this->view->setVar('devices', $devices);
        $this->view->setVar("alreadyUsed", $alreadyUsed);
    }

    public function exchangesAction() {
        $api = $this->di->get('api');

        $alertsCatalog = $this->session->get("alertsCatalog");
        if(!isset($alertsCatalog)){
            $alertsCatalog = $api->getAlertsCatalog();
            $this->session->set("alertsCatalog", $alertsCatalog);
        }

        $usersAlerts = $api->getUsersAlerts();
        $alreadyUsed = $this->getAlreadyUsedList($usersAlerts, $alertsCatalog);

        $devices = $this->session->get("devices");
        if(!isset($devices)){
            $devices = $api->getPoisDevices();
            $this->session->set("devices", $devices);
        }

        $types = ["COM"];
        // Get date - 3 month
        $monthDecrement = strtotime("-3 month", strtotime(date('m/d/Y 00:00:00')));
        $locale = Locale::acceptFromHttp($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
        $start = MessageFormatter::formatMessage($locale, '{0, date, full}.', array($monthDecrement));

        $notifications = $api->getNotificationsByUserID(null, $monthDecrement, $types);

        $sortNotifications = $this->sortByDay($notifications);
        $this->view->setVar("notificationsFull", $sortNotifications);
        $this->view->setVar("startDate", $start);
        $this->view->partial("notifications/listNotif");
        $this->view->setVar('alertsCatalogListing', $this->catalogFormatter($alertsCatalog));
        $this->view->setVar("alertsCatalog", $alertsCatalog);
        $this->view->setVar("alertsCatalogJson", json_encode($alertsCatalog, JSON_PRETTY_PRINT));
        $this->view->setVar("usersAlerts", $usersAlerts);
        $this->view->setVar('devices', $devices);
        $this->view->setVar("alreadyUsed", $alreadyUsed);
    }

    /**
     * Function for setting the datas for the notifs list
     * @author Mory Bamba
     */
    public function listNotifAction() {
        $this->view->disable();
        $api = $this->di->get('api');
        $types = [];

        if ($this->request->get("page") !== null && $this->request->get("page") === "messages") {
            $types = ["COM"];
        }
        // Get date - 3 month
        $monthDecrement = strtotime("-3 month", strtotime(date('m/d/Y 00:00:00')));
        $locale = Locale::acceptFromHttp($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
        $start = MessageFormatter::formatMessage($locale, '{0, date, full}.', array($monthDecrement));

        $notifications = $api->getNotificationsByUserID(null, $monthDecrement, $types);
        if(isset($notifications->code)) {
            $language = $this->setLanguage();
            $trans = $this->getTranslation($language);
            $this->flash->error($trans['on_error']);
        } else {
            $sortNotifications = $this->sortByDay($notifications);
            $this->view->setVar("notificationsFull", $sortNotifications);
            $this->view->setVar("startDate", $start);
            $this->view->partial("notifications/listNotif");
        }
    }

    /**
     * Create array in good format for view needs
     *
     * @param Json $array Json data from API GET Notifications
     * @return array() Array of notifications sort by day
     */
    private function sortByDay($array){
        $finalArray = array();
        foreach ($array as $value)
        {
            $created = strtotime($value->created);
            $date = date("j M. Y", $created);

            if(!isset($finalArray[$date]) || !is_array($finalArray[$date]))
            {
                $finalArray[$date] = array();
            }
            $value->created = $created; // Need time format for formatting in View
            array_push($finalArray[$date], $value);
        }
        return $finalArray;
    }


    /**
     *
     * @param type $list
     */
    public function setNotificationsReadAction($list=""){
        $this->view->disable();
        $api2 = $this->di->get('api');
        $api2->readNotifications($list);

        // Get notifications (ControllerBase)
        //$this->notificationsWidget();
        $monthDecrement = strtotime("-3 month", strtotime(date('m/d/Y 00:00:00')));
        $notifications = $api2->getNotificationsByUserID(0 , $monthDecrement); // get no read notifications
        if (sizeof($notifications) > 5) {
                array_splice($notifications, 5);
            }
        foreach ($notifications as $key => $value) {
            $dateNotif = strtotime($value->created);
            $notifications[$key]->date = $this->differenceDate(time(), $dateNotif);
        }
        $this->view->setVar("notificationsCount", count($notifications));
        $this->view->setVar("notifications", $notifications);
        $this->view->Partial('notifications/setNotificationsRead');
    }

    /*
     *
     */
    public function setNotificationsReadAllAction(){
        $this->view->disable();
        $api2 = $this->di->get('api');
        $api2->readAllNotifications();

        // Get date - 3 month
        $monthDecrement = strtotime("-3 month", strtotime(date('m/d/Y 00:00:00')));
        $locale = Locale::acceptFromHttp($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
        $start = MessageFormatter::formatMessage($locale, '{0, date, full}.', array($monthDecrement));

        $notifications = $api2->getNotificationsByUserID(null, $monthDecrement);
        $sortNotifications = $this->sortByDay($notifications);
        $this->session->set("notifications", count($notifications));
        $this->view->setVar("notifsCount", count($sortNotifications));
        $this->view->setVar("notificationsFull", $sortNotifications);
        $this->view->setVar("startDate", $start);
        $this->view->Partial('notifications/listNotif');

    }

    public function sendboxAction(){
        if($this->request->isPost()){
            $sendbox = $this->request->getPost('sendbox');
            if(isset($sendbox) && !empty($sendbox)){
                $config = $this->di->get('config');
                $api = $this->di->get('api');
                $usr = $this->session->get('infosUsr')[0];
                $api->sendMail(array(
                "receiver" => $config->adminEmail['email'],
                "template" => "sendBox",
                "subject" => "SmartMerchant - Notifications | Boite à idée",
                "data" => array(
                    "name" => $usr->name." " . $usr->surname,
                    "email" => $usr->login,
                    "message" => $sendbox
                    )
                ));
                $this->flash->success($this->trans['Your idea has been sent.']);
            } else {
                $this->flash->error($this->trans['Please enter a message']);
            }
            $this->response->redirect('notifications/index');
        }
    }

    public function createAction(){
        if($this->request->isPost()){
            $api = $this->di->get('api');
            $postParams = $this->request->getPost();
            $alertsCatalog = $this->session->get("alertsCatalog");
            $api->getUsersAlerts();
            echo "<pre>";
            var_dump($postParams);


            if(isset($postParams['alert_type'])){
                $index = $this->getIndex($postParams['alert_type'], $alertsCatalog);
                $alert = $alertsCatalog[$index];
                $critere = json_decode($alert->criteria);
                $encodedCritere = array();
                if($critere->type == "TRIGGER"){
                    $encodedCritere = $this->encodeTrigger($postParams, $critere);

                }
                else if ($critere->type == "LIVE")
                {
                    $encodedCritere = $this->encodeLive($postParams, $critere);
                }
                else{
                    echo "TYPE D'ALERT NON VALABLE .";
                }
                $dayRange = $this->getDayRange($postParams);
                if(isset($encodedCritere["triggervalues"]["weekly"])) {
                    $dayRange["weekly"] = "";
                }
                if(isset($encodedCritere["triggervalues"]["monthly"])) {
                    $dayRange["monthly"] = "";
                }
                $activated = (isset($postParams['activation_check']) && !empty($postParams['activation_check']) && $postParams['activation_check'] == "on") ? 1 : 0;
                $param = array(
                    "users_id" => $this->session->get('infosUsr')[0]->id,
                    "alerts_id" => $alert->id,
                    "dayRange" => str_replace('\\u0000', "", json_encode($dayRange)),
                    "criteria" => json_encode($encodedCritere),
                    "activated" => $activated
                );
                var_dump($param);
                $api->createUserAlerts($param);
            }
            else
            {
                $this->response->redirect('notifications/index');
                return false;
            }
        }
        $this->response->redirect('notifications/index');
    }


    private function encodeTrigger($postParams, $critere){
        $encoded = array( "type" => "TRIGGER", "triggervalues" => array());

        foreach ($critere->frequency as $frequency) {
            if(isset($postParams["frequency-".$frequency])){
                foreach ($critere->fields as $field) {
                    if($critere->fieldsType->$field == "currency")
                    {
                        $postParams[$frequency."-".$field] *= 100; // Cents to euros if type is currency
                    }
                    $encoded['triggervalues'][$frequency][$field] = (string) $postParams[$frequency."-".$field];
                }
            }
        }
        return $encoded;
    }

    private function encodeLive($postParams, $critere){
        $encoded = array( "type" => "LIVE", "livevalues" => array("daily" => array(), "weekly" => "", "monthly" => ""));
        foreach ($critere->fields as $field) {
            if(isset($postParams[$field]) && isset($postParams["operator-".$field])){
                if($critere->fieldsType->$field == "currency")
                {
                    $postParams[$field] *= 100; // Cents to euros if type is currency
                }
                $encoded["livevalues"]["daily"][] = array("type" => "ctx_value", "value" => "authorization.".$field);
                $encoded["livevalues"]["daily"][] = array("type" => "raw_value", "value" => (string) $postParams[$field]);
                $encoded["livevalues"]["daily"][] = array("type" => "operator", "value" => $postParams["operator-".$field]);
            }
        }
        return $encoded;
    }


    private function criteriaDecode($criteria){
        //echo gettype($criteria);
        $criteria = str_replace('"', '', $criteria);
        $criteria = str_replace("'", '', $criteria);
        $criteria = str_replace("{", '', $criteria);
        $criteria = str_replace("}", '', $criteria);
        $criteria = str_replace(" ", '', $criteria);
        $criteria = explode(",", $criteria);
        return $criteria;
    }


    private function getIndex($id, $array){
        $index = 0;
        foreach ($array as $value) {
            if($value->id == $id){
                return $index;
            }
            $index++;
        }
        return false;
    }

    private function getDayRange($postParams){

        $dayRange = "";
        if(!isset($postParams['frequency-daily'])){
            return array("days" => $dayRange);
        }
        if(isset($postParams['dateRangemonday']) && !empty($postParams['dateRangemonday']))
        {
            $dayRange .= $postParams['dateRangemonday'].",";
        }
        if(isset($postParams['dateRangetuesday']) && !empty($postParams['dateRangetuesday']))
        {
            $dayRange .= $postParams['dateRangetuesday'].",";
        }
        if(isset($postParams['dateRangewednesday']) && !empty($postParams['dateRangewednesday']))
        {
            $dayRange .= $postParams['dateRangewednesday'].",";
        }
        if(isset($postParams['dateRangethursday']) && !empty($postParams['dateRangethursday']))
        {
            $dayRange .= $postParams['dateRangethursday'].",";
        }
        if(isset($postParams['dateRangefriday']) && !empty($postParams['dateRangefriday']))
        {
            $dayRange .= $postParams['dateRangefriday'].",";
        }
        if(isset($postParams['dateRangesaturday']) && !empty($postParams['dateRangesaturday']))
        {
            $dayRange .= $postParams['dateRangesaturday'].",";
        }
        if(isset($postParams['dateRangesunday']) && !empty($postParams['dateRangesunday']))
        {
            $dayRange .= $postParams['dateRangesunday'].",";
        }
        if(strlen($dayRange) > 0 && $dayRange[strlen($dayRange) - 1] == ","){
            $dayRange[strlen($dayRange) - 1] = "";
        }
        return array("days" => $dayRange);

    }

    public function deleteAction($id_alert){
        if(!$id_alert){
            $this->response->redirect('notifications/index');
            return;
        }
        $api = $this->di->get('api');
        $res = $api->deleteUserAlerts($id_alert);
        if(isset($res)){
            $this->flash->success($this->trans['Notification has been deleted successfully']);
        }
        else{
            $this->flash->error($this->trans['on_error']);

        }
    }

    public function deletePoiAction($id_poi){
        if(!$id_poi){
            $this->response->redirect('notifications/index');
            return;
        }
        $api = $this->di->get('api');
        $res = $api->deletePoi($id_poi);
        if(isset($res)){
            $this->flash->success("Téléphone supprimé avec succès .");

            //mise à jour de la liste des POIS
            $devices = $api->getPoisDevices();
            $this->session->set("devices", $devices);
        }
        else{
            $this->flash->error("Une erreur est survenue .");

        }
    }

    // public function editAction(){
    //     if($this->request->isPost()){
    //         $api = $this->di->get('api');
    //         $postParams = $this->request->getPost();
    //         $dayRange = $this->getDayRange($postParams);
    //         $alertsCatalog = $api->getAlertsCatalog();
    //         var_dump($postParams);die;
    //         if(isset($postParams['alert_type'])){
    //             $index = $this->getIndex($postParams['alert_type'], $alertsCatalog);
    //             $alert = $alertsCatalog[$index];
    //             $criteria = $this->criteriaDecode($alert->criteria);
    //             $encodedCriteria = array();
    //             foreach ($criteria as $key => $value) {
    //                 if(isset($postParams[$value]) && isset($postParams["operator-".$value]))
    //                 {
    //                     $encodedCriteria[] = array("type" => "ctx_value", "value" => "authorization.".$value);
    //                     $encodedCriteria[] = array("type" => "raw_value", "value" => $postParams[$value]);
    //                     $encodedCriteria[] = array("type" => "operator", "value" => $postParams["operator-".$value]);
    //                     echo "Field is presents"."<br />";
    //                 }
    //                 else
    //                 {
    //                     echo $value." Need datas ... please refill the form correctly";
    //                     break;
    //                 }
    //             }
    //             $dayRange = $this->getDayRange($postParams);
    //             $activated = (!isset($_POST['activation_check_edit']) || empty($_POST['activation_check_edit'])) ? 0 : 1;
    //             $param = array(
    //                 "users_id" => $this->session->get('infosUsr')[0]->id,
    //                 "alerts_id" => $alert->id,
    //                 "dayRange" => $dayRange,
    //                 "criteria" => json_encode($encodedCriteria),
    //                 "activated" => $activated
    //             );
    //             $api->editUserAlerts($param);
    //             $this->flash->success("L'alerte a bien été modifié .");
    //         }
    //         $this->response->redirect('notifications/index');
    //     }
    // }
    public function editAction(){
        echo "<pre>";
        if($this->request->isPost()){
            $api = $this->di->get('api');
            $postParams = $this->request->getPost();
            $dayRange = $this->getDayRange($postParams);

            $alertsCatalog = $api->getAlertsCatalog();
            if(isset($postParams['alert_type'])){
                $index = $this->getIndex($postParams['alert_type'], $alertsCatalog);
                $alert = $alertsCatalog[$index];
                $critere = json_decode($alert->criteria);
                $encodedCritere = array();
                if($critere->type == "TRIGGER"){
                    $encodedCritere = $this->encodeTrigger($postParams, $critere);

                }
                else if ($critere->type == "LIVE")
                {
                    $encodedCritere = $this->encodeLive($postParams, $critere);
                }

                else{
                    echo "TYPE D'ALERT NON VALABLE .";
                }
                $dayRange = $this->getDayRange($postParams);
                if(isset($encodedCritere["triggervalues"]["weekly"])) {
                    $dayRange["weekly"] = "";
                }
                if(isset($encodedCritere["triggervalues"]["monthly"])) {
                    $dayRange["monthly"] = "";
                }
                $activated = (isset($postParams['activation_check_edit']) && !empty($postParams['activation_check_edit']) && $postParams['activation_check_edit'] == "on") ? 1 : 0;
                $param = array(
                    "users_id" => $this->session->get('infosUsr')[0]->id,
                    "alerts_id" => $alert->id,
                    "dayRange" => str_replace('\\u0000', "", json_encode($dayRange)),
                    "criteria" => json_encode($encodedCritere),
                    "activated" => $activated
                );
                $api->editUserAlerts($param);
            }
            $this->response->redirect('notifications/index');
        }
    }

    public function editModalAction($id_alert){
        $this->view->disable();
        if(!$id_alert){
            $this->response->redirect('notifications/index');
            return;
        }
        $api = $this->di->get('api');
        $usersAlerts = $api->getUsersAlertByID($id_alert);
        $alertsCatalog = $this->session->get("alertsCatalog");
        $alert = $alertsCatalog[$this->getIndex($id_alert, $alertsCatalog)];
        if(!isset($usersAlerts->code)){
            $dayRange = json_decode($alert->dayRange);
            $useralertsDayRange = json_decode($usersAlerts->dayRange)->days;
            $alreadyDayRange = $this->getFrequency($usersAlerts->dayRange);
            $decodedCriteria = json_decode($alert->criteria);
            $usercriteria = json_decode($usersAlerts->criteria, true);
            $this->view->setVar("usersAlerts", $usersAlerts);
            $this->view->setVar("alert", $alert);
            $this->view->setVar("useralertsDayRange", $useralertsDayRange);
            $this->view->setVar("usercriteria", $usercriteria);
            $this->view->setVar("criteria", $decodedCriteria);
            $this->view->setVar("dayRange", $dayRange);
            $this->view->setVar("alreadyDayRange", $alreadyDayRange);
            $this->view->setVar("alreadyDayRangeArray", explode(",", $alreadyDayRange));
            $this->view->setVar("fieldsType", (array) $decodedCriteria->fieldsType); // FieldsType for symbol as array
            $this->view->partial('notifications/editModal');
        }
    }

    private function getFrequency($dayRange)
    {
        $finalDrange = "";
        $drange = json_decode($dayRange);
        if(sizeof($drange->days) > 0 && $drange->days != ""){
            $finalDrange .= "daily,";
        }
        if(isset($drange->monthly)){
            $finalDrange .= "monthly,";
        }
        if(isset($drange->weekly)){
            $finalDrange .= "weekly,";
        }
        return $finalDrange;
    }

    private function getAlreadyUsedList($usersAlerts, $catalogs){
        $result = array();

        foreach ($catalogs as $key => $catalog) {
            foreach ($usersAlerts as $key => $useralert) {
                if($useralert->alerts_id == $catalog->id){
                    $result[] = $catalog->id;
                }
            }

        }
        return $result;

    }


    private function catalogFormatter($catalog){
        $result = array();
        foreach ($catalog as $value) {
            $type = $value->type;
            if(!isset($result[$type])){
                $result[$type] = array();
            }

            array_push($result[$type], $value);
        }
        return $result;
    }
}
