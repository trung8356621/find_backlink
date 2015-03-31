<?php

class View extends Template {

    private $def_theme;
    public static $color = array('main' => '#4285F4', 'sub' => '#EFEFEF', 'but_1' => '#D84734', 'but_2' => '#F36523');
    private $def_theme_url;

    function __construct($main_url, $main_path, $def_theme, $lan, $a_head) {
        parent::__construct($main_url, $main_path, $lan, $a_head);
        $this->def_theme = $main_path . 'view/' . $def_theme . '/';
        $this->def_theme_url = $main_url . 'view/' . $def_theme . '/';
    }

    function prt_listcss() {
        $i = 0;
        while ($i < count($this->a_head['css'])) {
            echo '<link rel="stylesheet" type="text/css" href="' . $this->def_theme_url . 'css/' . $this->a_head[('css')][$i] . '.css" />';
            $i++;
        }
    }

    function prt_listjs() {
        $i = 0;
        while ($i < count($this->a_head['js'])) {
            echo "\n".'<script src="' . $this->def_theme_url . 'js/' . $this->a_head[('js')][$i] . '.js" ></script>';
            $i++;
        }
    }

    public function get_View($view, $data = array()) {
        require $this->def_theme . $view;
    }

    public function head() {
        require DEF_PATH . 'view/head.php';
    }

    public function top() {
        require $this->def_theme . 'top.php';
    }

    public function foot() {
        require $this->def_theme . 'foot.php';
    }

}
