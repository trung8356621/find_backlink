<?php

//coder :Nguyen Tran Minh Trung
class con_Normal {

    public $view;
    public $ahead;

    function __construct() {
        Controller::get_view();
    }

    function get_info_web() {
        Controller::get_model('home');
        $mh = new home();
        $this->ahead = $mh->get_info_web();
        $this->ahead['robot'] = 1;
        $this->ahead['css'] = array('S_Style', 'S_Search', 'blueberry');
        $this->ahead['js'] = array('jquery-1.8.1', 'func', 'jquery.blueberry', 'Chart');
    }

    function bill() {
        $seg = new Url(MAIN_SEG);
        $seg2 = $seg->segment(2);
        if (isset($_SESSION['us']) && !empty($seg2)) {
            $this->get_info_web();
            $this->ahead['css'][] = 'S_Bill';
            $this->view = new View(DEF_URL, DEF_PATH, 'default', 'en', $this->ahead);
            $this->view->head();
            $this->view->top();
            $this->search_Page();
            Controller::get_model('home');
            $bill = new home();
            $data['user'] = $bill->get_info_user($_SESSION['us']['us_id']);
            $data['type'] = $bill->get_acc_type($seg2);
            $this->view->get_View('bill.php', $data);
        } else {
            header('location:' . DEF_URL . 'register');
        }
    }

    function home() {
        $this->get_info_web();
        $this->view = new View(DEF_URL, DEF_PATH, 'default', 'en', $this->ahead);
        $this->view->head();
        $this->view->top();
        $this->search_Page();
        $this->banner();
        $this->view->get_View('modules/home.php');
        $this->view->get_View('foot.php');
    }

    function banner() {
        $dir = DEF_PATH . 'public/banner/';
        $dh = opendir($dir);
        while (false !== ($filename = readdir($dh))) {
            $files[] = $filename;
        }
        $images['img'] = preg_grep('/\.jpg|.png$/i', $files);
        $this->view->get_View('banner.php', $images);
    }

    function search() {
        $this->get_info_web();
        $this->view = new View(DEF_URL, DEF_PATH, 'default', 'en', $this->ahead);
        $this->view->head();
        $this->view->top();
        $this->search_Page();
        if (isset($_GET['q'])) {
            echo '<div id="S_Con">';
            Controller::get_lib('check_back_link/check_link');
            $cl = new Check_Link($_GET['q'], $this->view);
            echo '</div>';
        }
        $this->view->get_View('foot.php');
    }

    function pricing() {
        $this->get_info_web();
        $this->view = new View(DEF_URL, DEF_PATH, 'default', 'en', $this->ahead);
        $this->view->head();
        $this->view->top();
        $this->search_Page();
        Controller::get_lib('user_choose');
        Controller::get_model('user_choose');
        $this->view->get_View('modules/pricing.php', array('wid' => 80));
        $this->view->get_View('foot.php');
    }

    function register() {
        $this->get_info_web();
        $this->ahead['css'][] = 'S_Register';
        $this->view = new View(DEF_URL, DEF_PATH, 'default', 'en', $this->ahead);
        $this->view->head();
        $this->view->top();
        Controller::get_lib('user_choose');
        Controller::get_model('user_choose');
        $this->view->get_View('S_Inside.php');
        $this->view->get_View('modules/register.php');
        $this->view->get_View('foot.php');
    }

    function search_Page() {
        $this->view->get_View('S_Inside.php');
    }

    function logout() {
        session_destroy();
        echo "<script language=javascript> javascript:history.back();</script>";
    }

    function loi() {
        echo 'ss';
    }

    function admin() {
        header('loction:' . DEF_URL . '/admin');
    }

}
