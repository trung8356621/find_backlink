<?php

class con_User {

    public $view;
    public $ahead;

    function __construct() {
        Controller::get_view();
    }

    function get_info_web() {
        Controller::get_model('home');
        $mh = new home();
        $this->ahead = $mh->get_info_web();
        $this->ahead['robot'] = 0;
        $this->ahead['css'] = array('S_Style');
        $this->ahead['js'] = array('jquery-1.8.1', 'func');
        $this->view = new View(DEF_URL, DEF_PATH, 'default', 'en', $this->ahead);
        $this->view->head();
        $this->view->top();
        $this->view->get_View('modules/user/left_menu.php');
    }

    function your_info() {
        $this->get_info_web();
    }

}
