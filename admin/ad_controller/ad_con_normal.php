<?php

class Ad_Con_Normal {

    public $view;
    public $page;
    public static $a_conf = array(1000000, array('jpg', 'png'));

    function __construct() {
        ad_controller::get_view();
        $this->view = new View(DEF_AD_URL, DEF_AD_PATH, 'default', 'en', array(
            'web_name' => 'ADMIN PAGE',
            'web_img' => '',
            'web_title' => 'ADMIN PAGE',
            'web_discription' => '',
            'web_keyword' => '',
            'robot' => -2,
            'css' => array('S_Ad_Style', 'S_Table'),
            'js' => array('con_ajax')
        ));
        $this->view->head();
    }

    function home($act = '') {
        if (isset($_SESSION['ad'])) {
            $this->home_view($act);
        } else {
            $this->login();
        }
    }

    public function login() {
        $data = array('error' => '');
        if (isset($_POST['log_ad'])) {
            if (!empty($_POST['user']) && !empty($_POST['pass'])) {
                ad_controller::get_model('ad_login');
                try {
                    $al = new ad_login();
                    $us = $al->ad_login($_POST['user'], $_POST['pass']);
                    if ($us != -1) {
                        $_SESSION['ad'] = $us;
                        header('location:' . DEF_AD_URL);
                    } else {
                        throw new Exception('err');
                    }
                } catch (Exception $exc) {
                    $data['error'] = $this->view->a_lan['err'];
                }
            } else {
                $data['error'] = $this->view->a_lan['err'];
            }
        }
        $this->view->get_View('login.php', $data);
    }

    public function home_view($act) {
        $this->view->get_View('left.php', array('act' => $act));
    }

    public function acc_type() {
        $this->page = 'reg';
        $this->home($this->page);
        ad_controller::get_lib('table');
        ad_controller::get_model('ad_table');
        ad_controller::get_lib('user_choose');
        ad_controller::get_model('user_choose');
        $at = new ad_table();
        $data = $at->ad_acc_type();
        $this->view->get_View('modules/regency.php', array('a_table' => array('acc_type', 'at_id'), 'mod' => 'regency', 'tbl_data' => $data, 'page' => $this->page, 'tbl_head' => array('id', 'at_name', 'at_color', 'at_price'), 'delete' => true, 'edit' => true));
        echo '<div class="Clear"></div></div>';
    }

    public function account() {
        $this->page = 'acc';
        $this->home($this->page);
        ad_controller::get_lib('table');
        ad_controller::get_model('ad_table');
        ad_controller::get_lib('user_choose');
        ad_controller::get_model('user_choose');
        $at = new ad_table();
        $data = $at->ad_account();
        $this->view->get_View('modules/account.php', array('a_table' => array('users', 'us_id'), 'page' => $this->page, 'mod' => 'account', 'tbl_data' => $data, 'tbl_head' => array('id', 'Email', 'Name', 'regency')));
        echo '<div class="Clear"></div></div>';
    }

    public function bill() {
        $this->home('bill');
        ad_controller::get_lib('uploader');
        $up = new uploader($this->view->a_lan, DEF_PATH . 'public/pro/', 'up file', 1000000, array('jpg', 'png'), 'ab');
        $up->process_form();
    }

    public function web_info() {
        $this->home('info');
    }

    function tbl_add() {
        $url = new Url(MAIN_SEG);
        $seg4 = $url->segment(4);
        $this->home($seg4);
        $seg = $url->segment(3);
        ad_controller::get_model('ad_table');
        if (isset($seg)) {
            $id = $url->segment(5);
            echo '<div id="Right">';
            ad_controller::get_lib('tbl_add');
            if ($seg == 'acc_type') {
                $this->ins_after($id, $seg , 'acc_type');
            } else if ($seg == 'acc_func') {
                $this->ins_after($id, $seg, 'acc_func');
            } else {
                new tbl_add($seg, $this->view->a_lan, $id);
            }
            echo '</div>';
        }
    }

    function ins_after($id, $seg, $table) {
        $con = new tbl_add($seg, $this->view->a_lan, $id);
        if ($id == '') {
            echo $con->flag;
            if ($con->flag) {
                ad_controller::get_model('user_choose');
                $uc = new user_c();
                $uc->{'insert_tf_b_' . $table}($con->flag);
            } else {
                echo $this->view->a_lan['false'];
            }
        }
    }

}
