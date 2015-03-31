<?php

class Con_Ajax {

    public $view;
    private $ahead;

    function __construct() {
        Controller::get_view();
        $this->get_info_web();
        $this->view = new View(DEF_URL, DEF_PATH, 'default', 'en', $this->ahead);
    }

    function get_info_web() {
        Controller::get_model('home');
        $this->ahead['robot'] = 1;
        $this->ahead['css'] = array('S_Search');
        $this->ahead['js'] = array();
    }

    function login_ac() {
        if (!empty($_POST['id']) && !empty($_POST['pass'])) {
            $hm = new home();
            $res = $hm->get_Results($hm->login(addslashes($_POST['id']), $_POST['pass']));
            if ($res != -1) {
                $_SESSION['us'] = $res[0];
            } else {
                echo 'die';
            }
            echo 'ok';
        } else {
            echo 'die';
        }
    }

    function s_false() {
        $this->view->get_View('modules/search/s_false.php');
    }

    function s_result() {
        $this->view->prt_listcss($this->ahead['css']);
//        $url = new Url(MAIN_SEG);
        if (isset($_POST['val'])) {
            Controller::get_lib('check_back_link/check_link');
            $cl = new Check_Link($_POST['val'], $this->view);
//        $this->view->get_View('modules/search/s_result.php');
        }
    }

    function bl_tbl() {
        $this->view->prt_listcss($this->ahead['css']);
        if (isset($_POST['ipv4']) && isset($_POST['type'])) {
            Controller::get_lib('check_back_link/prt_list_bl');
            new prt_list_bl($_POST['ipv4'],$_POST['num_bl'], $this->view->a_lan, $_POST['type'], (isset($_POST['like']) ? $_POST['like'] : ''), (isset($_POST['cur']) ? $_POST['cur'] : 0));
        }
    }

    function loi() {
        echo 'ss';
    }

}
