<?php

class home extends model {

    function __construct() {
        parent::__construct(Controller::$a_con);
    }

    function get_info_web() {
        return $this->get_ResultA_One($this->con->select_query('SELECT * FROM info_web', 'info-web'));
    }

    function get_info_user($id) {
        return $this->get_ResultA_One($this->con->select_query('SELECT us_name,us_email FROM users WHERE us_id="' . $id . '"', 'info-web'), MYSQLI_ASSOC, true);
    }

    function get_acc_type($id) {
        return $this->get_ResultA_One($this->con->select_query('SELECT at_name,at_color,at_price FROM acc_type WHERE at_id="' . $id . '"', 'info-web'), MYSQLI_ASSOC, true);
    }

    function insert_payment($at_id, $pay_num, $total_price, $us_id) {
        return $this->get_Results($this->con->select_query('INSERT INTO payment VALUES("' . $at_id . '","' . $pay_num . '","' . $total_price . '","' . $us_id . '")', 'info-web'));
    }

    function login($email, $pass) {
        return $this->con->select_query('SELECT us_id,at_id,us_name FROM users  WHERE us_email="' . $email . '" AND us_pass="' . md5($pass) . '"', 'login');
    }

}
