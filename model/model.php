<?php

/*
 * Project_Name: Your node
 * Coder: Nguyễn Trần Minh Trung
 */

abstract class model {

    protected $arr_Con;
    protected $name_Mod;
    protected $con;

    function __construct($arr_Con) {
        $this->arr_Con = $arr_Con;
        $this->name_Mod = "model";
        $this->connect();
    }

    function connect() {
        $this->con = new Connect($this->arr_Con, 'utf8');
    }

    public function get_Result_One($result, $con = true) {
        $res = -1;
        if (@mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result, MYSQLI_NUM);
            $res = $row[0];
        }
        $this->con->dis_Connect();
        $con ? '' : $this->con->dis_Connect();
        return $res;
    }

    public function get_Results($result, $type = MYSQLI_ASSOC, $con = true) {
        $a_Re = array();
        if ($result) {
            while ($row = mysqli_fetch_array($result, $type)) {
                $a_Re[] = $row;
            }
        }
        $con ? '' : $this->con->dis_Connect();
        return $a_Re;
    }

    public function get_ResultA_One($result, $type = MYSQLI_ASSOC, $con = true) {
        $a_Re = array();
        if ($result) {
            while ($row = mysqli_fetch_array($result, $type)) {
                $a_Re = $row;
            }
        }
        $con ? '' : $this->con->dis_Connect();
        return $a_Re;
    }

    function order_by($val, $type) {
        return ' ORDER BY ' . $val . ' ' . ($type == 1 ? '' : 'DESC');
    }

    function like_list($val, $str_like, $ao) {
        $result = '';
        $i = 0;
        $like = explode(',', $str_like);
        while ($i < count($like)) {
            $i == 0 && $i == count($like) - 1 ? $val . ' LIKE ' . $like[$i] : $val . ' LIKE ' . $like[$i] . $ao;
            $i++;
        }
        return $result;
    }

    function join_Add($join_need, $join, $val_Join, $join_as = "") {
        return ' INNER JOIN ' . $join . ' AS ' . $join_as . ' ON ' . $join_need . '.' . $val_Join . '=' . ($join_as != '' ? $join_as : $join) . '.' . $val_Join;
    }

}
