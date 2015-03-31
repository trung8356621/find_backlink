<?php

class ad_table extends model {

    function __construct() {
        parent::__construct(ad_controller::$a_con);
    }

    function ad_acc_type() {
        return $this->get_Results($this->con->select_query('SELECT * FROM acc_type WHERE at_id!=0', 'ad_acc_type'));
    }

    function ad_account() {
        return $this->get_Results($this->con->select_query('SELECT  us_id,us_email,us_name,at_name  FROM users AS us INNER JOIN acc_type AS act ON us.at_id=act.at_id', 'ad_acc_type'), MYSQLI_NUM);
    }

    function insert_last($query) {
        return $this->con->ect_query_id($query, 'sds');
    }

    function del_ever($table, $id_name, $value) {
        $ex_tbl = explode(',', $table);
        if (count($ex_tbl) <= 1) {
            return $this->con->ect_query('DELETE FROM ' . $table . ' WHERE ' . $id_name . '=' . $value, 'del_ever');
        } else {
            $lst_query = array();
            $i = 0;
            while ($i < count($ex_tbl)) {
                $lst_query[] = 'DELETE FROM ' . $ex_tbl[$i] . ' WHERE ' . $id_name . '=' . $value;
                $i++;
            }
            print_r($lst_query);
            return $this->con->ect_query_trans($lst_query);
        }
    }

    function insert($query) {
        echo $query;
        return $this->con->ect_query($query, 'insert');
    }

    function select_by_id($table, $name_w, $id) {
        return $this->get_ResultA_One($this->con->select_query('SELECT * FROM ' . $table . ' WHERE ' . $name_w . '=' . $id, 'select_by_id'));
    }

    function show_colums($table) {
        return $this->get_Results($this->con->select_query('SHOW COLUMNS FROM ' . $table, 'show_colums'));
    }

    function show_foreign($table) {
        return $this->get_Results($this->con->select_query('SELECT i.TABLE_NAME, k.REFERENCED_TABLE_NAME, k.COLUMN_NAME 
                                                            FROM information_schema.TABLE_CONSTRAINTS i 
                                                            LEFT JOIN information_schema.KEY_COLUMN_USAGE k ON i.CONSTRAINT_NAME = k.CONSTRAINT_NAME 
                                                            WHERE i.CONSTRAINT_TYPE = "FOREIGN KEY" 
                                                            AND i.TABLE_SCHEMA = "' . ad_controller::$a_con[3] . '"
                                                            AND i.TABLE_NAME = "' . $table . '"', 'show_colums'));
    }

    function at_id_check() {
        return $this->get_Results($this->con->select_query('SELECT at_id,at_name FROM acc_type', 'at_id_check'), MYSQLI_NUM);
    }

    function af_id_check() {
        return $this->get_Results($this->con->select_query('SELECT af_id,af_name FROM acc_func', 'af_id_check'), MYSQLI_NUM);
    }

}
