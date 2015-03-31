<?php

class user_c extends model {

    function __construct() {
        parent::__construct(Controller::$a_con);
    }

    function get_type() {
        return $this->con->select_query('SELECT * FROM acc_type WHERE at_id!=0 ORDER BY at_id', 'ss');
    }

    function get_func() {
        return $this->con->select_query('SELECT af_id,af_name,unit FROM acc_func ORDER BY af_id', 'ss');
    }

    function get_type_func($acc_type, $acc_func) {
        return $this->con->select_query('SELECT val 
FROM type_func as tf inner join acc_func as af ON tf.af_id=af.af_id 
inner join acc_type as aty ON tf.at_id=aty.at_id WHERE  aty.at_id="' . $acc_type . '" AND af.af_id="' . $acc_func . '" ORDER BY aty.at_id', 'ss');
    }

}
