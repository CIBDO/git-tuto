<?php

use Phalcon\Mvc\User\Component;

/**
 * Elements
 *
 * Helps to build UI elements for the application
 */
class Elements extends Component {
     
    /**
     * Builds sidebar menu.
     *
     */
    public function getSidebarMenu($trans) {

        $controllerName = $this->view->getControllerName();
        $actionName = $this->view->getActionName();
        $sidebarMenu = json_decode(file_get_contents(APP_PATH . "/app/config/sidebarMenu.json"));
        // Menu de profondeur 3 au maximum
        foreach ($sidebarMenu as $menu) {
            if(isset($menu->active) && $menu->active == 0)
                continue;
            $this->buildTree($controllerName, $menu, $trans, $actionName);

            if (!empty($menu->sub)) {
                echo '<ul class="treeview-menu ">';

                foreach ($menu->sub as $sub) {
                    $this->buildTree($controllerName, $sub, $trans, $actionName);

                    if (!empty($sub->sub)) {
                        echo '<ul class="treeview-menu ">';

                        foreach ($sub->sub as $su) {
                            $this->buildTree($controllerName, $su, $trans, $actionName);
                            echo '</li>';
                        }
                        echo '</ul>';
                    }
                    echo '</li>';
                }
                echo '</ul>';
            }
            echo '</li>';
        }
    }

    /**
     * 
     * Build a treeview element
     * 
     * @param string $controllerName controller name of the curent page
     * @param type $sub treeview element to build
     * @param type $trans array of translations
     */
    private function buildTree($controllerName, $sub, $trans, $actionName) {
        //Permission
        $permissions = json_decode($this->session->get('usr')['permissions']);
        if($this->session->get('usr')['id'] != 1){
            if(isset($sub->permissions)){
                $test = 1;
                /*var_dump($sub->permissions);
                var_dump($permissions);*/
                foreach ($sub->permissions as $value) {
                    if(in_array($value,$permissions)){
                        //var_dump($trans[$sub->caption]);
                        $test = 0;
                        //break;
                    }
                }
                if($test == 1) return;
            }
        }
        // On vérifie si le controleur courant fait parti du menu
        if (strstr(json_encode($sub), "\"controller\":\"" . $controllerName . "\"") && strstr(json_encode($sub), "\"action\":\"" . $actionName . "\"")) {
            echo '<li class="treeview active">';
        } else {
            echo '<li class="treeview">';
        }

        // Si on a les droits, on affiche le menu
        $scope = $this->session->get("usr");
        if(isset($scope['scope'])){
            $scope = $scope['scope'];
        }
        if (empty($sub->scope) || (!empty($sub->scope) && in_array($sub->scope, $scope))) {
            if (empty($sub->controller)) {
                echo '<a href="#">';
            } else {
                $actionUrl = $sub->controller . '/' . $sub->action;
                $baseUrl = $this->di->get('url')->getBaseUri();
                $url = $baseUrl . $actionUrl;
                if (isset($sub->ajax) && $sub->ajax == true) {
                    echo '<a href="' . $url . '" class="ajax-navigation">';
                } else {
                    echo '<a href="' . $url . '">';
                }
            }

            if (!empty($sub->class)) {
                echo '<i class="' . $sub->class . '"></i>';
            }

            if (!empty($sub->caption)) {
                echo '<span class="menu-span-side-bar"> ' . $trans[$sub->caption] . '</span>';
            }

            if (!empty($sub->right_label)) {
                echo '<small class="label pull-right ' . $sub->right_label->color . '">' . $sub->right_label->label . '</small>';
            }

            if (!empty($sub->sub)) {
                echo '<i class="fa fa-angle-left pull-right"></i>';
            }

            echo '</a>';
        }
    }

}
