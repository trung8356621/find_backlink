<?php

/*
 * Project_Name: Your node
 * Coder: Nguyễn Trần Minh Trung
 */

class Controller {

    public static $a_con = array('localhost', 'root', '', 'check_backlink');
    public $a_ajax = array('', 'register-ac', 'login', 'login-ac','s-false','s-result','bl-tbl');
    public $a_normal = array('','bill', 'home', 'link','search', 'logout', 'pricing', 'register', 'admin');
    public $a_user = array('', 'your-info', 'your-domain', 'your-bill');
    protected $lan;

    function __construct() {
        $this->check_con();
        $this->check_main_seg();
    }

    private function check_con() {
        require DEF_PATH . 'lib/connect.php';
        try {
            new Connect(self::$a_con, 'utf8');
        } catch (Exception $exc) {
            echo 'ssss';
        }
    }

    public static function get_lib($lib) {
        require DEF_PATH . 'lib/' . $lib . '.php';
    }

    public static function get_view() {
        require_once DEF_PATH . 'view/template.php';
        require_once DEF_PATH . 'view/view.php';
    }

    public static function get_model($model) {
        require_once DEF_PATH . 'model/model.php';
        require_once DEF_PATH . 'model/' . $model . '.php';
    }

    private function check_main_seg() {
        session_start();
        $url = new Url(MAIN_SEG);
        //check ajax request
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            require 'controller/con_ajax.php';
            $page = $url->main_Segment($this->a_ajax, '');
            if ($page != '404') {
                $con_ajax = new Con_Ajax();
                $con_ajax->{$page}();
            }
        } else {
            if (isset($_COOKIE['us'])) {
                $_SESSION['us'] = $_COOKIE['us'];
            }
            if (isset($_SESSION['us'])) {
                require 'controller/con_user.php';
                $page = $url->main_Segment($this->a_user);
                if ($page != 'loi' && $page != '') {
                    $con_user = new con_User();
                    $con_user->{$page}();
                } else {
                    $this->con_normal($url);
                }
            } else {
                $this->con_normal($url);
            }
        }
    }

    public function con_normal($url) {
        require 'controller/con_normal.php';
        $page = $url->main_Segment($this->a_normal, 'home');
        $con_normal = new con_Normal();
        $con_normal->{$page}();
    }

}
