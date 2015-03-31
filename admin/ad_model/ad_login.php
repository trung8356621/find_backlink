<?php

class ad_login extends model {

    function __construct() {
        parent::__construct(ad_controller::$a_con);
    }

    function ad_login($email, $pass) {
        return $this->get_Result_One($this->con->select_query('SELECT us_id FROM users WHERE us_email="' . $email . '" AND us_pass="' . md5($pass) . '" AND at_id=0', 'info-web'));
    }

}
