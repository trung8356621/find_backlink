<?php

abstract class Template {

    public $main_url;
    public $main_path;
    public $a_lan;
    public $a_head;

//    array(
//    'web_name' => '',
//    'web_img' => '',
//        'web_title' => '',
//        'web_discription' => '',
//        'web_keyword' => '',
//        'robot' => 1,
//        'css' => array(),
//        'js' => array()
//    );

    function __construct($main_url, $main_path, $lan, $a_head) {
        $this->main_path = $main_path;
        $this->main_url = $main_url;
        $this->a_head = $a_head;
        $this->get_lan($lan);
        $this->check_robot();
    }

    function get_lan($lan) {
        require $this->main_path . 'lan/' . $lan . '.php';
        $this->a_lan = $lan;
    }

    function check_robot() {
        switch ($this->a_head['robot']) {
            case 1:
                $this->a_head['robot'] = 'INDEX,FOLLOW';
                break;
            case 0:
                $this->a_head['robot'] = 'NOINDEX,FOLLOW';
                break;
            case -1:
                $this->a_head['robot'] = 'INDEX,NOFOLLOW';
                break;
            case -2:
                $this->a_head['robot'] = 'NOINDEX,NOFOLLOW';
                break;
        }
    }

    abstract function head();

    abstract function top();

    abstract function foot();
}
