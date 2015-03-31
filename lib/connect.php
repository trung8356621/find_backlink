<?php

/*
 * Project_Name: Your node
 * Coder: Nguyá»…n Tráº§n Minh Trung
 */

/**
 * Description of connect
 *
 * @author trung
 */
class Connect {

    private $a_Info;
    private $unc;

    function __construct($a_Info, $unc) {
        $this->a_Info = $a_Info;
        $this->unc = $unc;
        $this->open_connect();
    }

    private function open_connect() {
        $con = @mysqli_connect($this->a_Info[0], $this->a_Info[1], $this->a_Info[2], $this->a_Info[3]);
        if ($con) {
            $this->con = $con;
            $this->set_Unicode();
        } else {
            throw new Exception("err_ser");
        }
    }

    private function set_Unicode() {
        mysqli_query($this->con, 'SET NAMES ' . $this->unc);
    }

    public function select_query($query, $error) {
        try {
            return @mysqli_query($this->con, $query);
        } catch (Exception $exc) {
            throw new Exception($error);
        }
    }

    public function ect_query_id($query, $error) {
        try {
            mysqli_query($this->con, $query);
            return @mysqli_affected_rows($this->con) > 0 ? mysqli_insert_id($this->con) : false;
        } catch (Exception $exc) {
            throw new Exception($error);
        }
    }

    public function ect_query($query, $error) {
        try {
            mysqli_query($this->con, $query);
            return @mysqli_affected_rows($this->con) > 0 ? true : false;
        } catch (Exception $exc) {
            throw new Exception($error);
        }
    }

    public function ect_query_trans($list_query) {
        mysqli_autocommit($this->con, false);
        try {
            $i = 0;
            while ($i < count($list_query)) {
                mysqli_query($this->con, $list_query[$i]);
                $i++;
            }
            mysqli_commit($this->con);
            return 1;
        } catch (Exception $exc) {
            mysqli_rollback($this->con);
            return -1;
        }
    }

    public function ect_query_non_trans($list_query) {
        try {
            $i = 0;
            while ($i < count($list_query)) {
                mysqli_query($this->con, $list_query[$i]);
                $i++;
            }
            return 1;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function dis_connect() {
        mysqli_close($this->con);
    }

}
