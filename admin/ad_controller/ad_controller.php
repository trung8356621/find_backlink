<?php

//coder :Nguyen Tran Minh Trung
class ad_controller {

    public static $a_con = array('localhost', 'root', '', 'check_backlink');
    public $a_ajax = array('', 'tbl-add', 'delete');
    public $a_normal = array('', 'login', 'acc-type', 'bill', 'account', 'web-info', 'tbl-add');
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

    public static function get_view() {
        require DEF_PATH . 'view/template.php';
        require DEF_PATH . 'view/view.php';
    }

    public static function get_model($model) {
        require_once DEF_PATH . 'model/model.php';
        require_once DEF_AD_PATH . 'ad_model/' . $model . '.php';
    }

    public static function get_lib($lib) {
        require DEF_PATH . 'lib/' . $lib . '.php';
    }

    private function check_main_seg() {
        session_start();
        $url = new Url(MAIN_AD_SEG);
        //check ajax request
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            require 'ad_controller/ad_con_ajax.php';
            $page = $url->main_Segment($this->a_ajax, '404');
            if ($page != '404') {
                $con_ajax = new Ad_Con_Ajax();
                $con_ajax->{$page}();
            }
        } else {
            require 'ad_controller/ad_con_normal.php';
            $page = $url->main_Segment($this->a_normal, 'home');
            $con_normal = new Ad_Con_Normal();
            $con_normal->{$page}();
        }
    }

}
