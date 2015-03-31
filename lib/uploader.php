<?php

/*
 * Project_Name: Your node
 * Coder: Nguyễn Trần Minh Trung
 */

class uploader {

    public $a_lan;
    public $folder;
    public $name; //name file (if exists ) ? is random 
    public $label;
    public $max_size;
    public $list_style;
    public $form_prt;
    public $ran_up;

    function __construct($a_lan, $folder, $label, $max_size, $list_style, $ran_up, $name = '', $form_prt = true) {
        $this->a_lan = $a_lan;
        $this->folder = $folder;
        $this->ran_name($name);
        $this->label = $label;
        $this->max_size = $max_size;
        $this->list_style = array_merge(array(''), $list_style);
        $this->form_prt = $form_prt;
        $this->ran_up = $ran_up;
        $this->prt_input();
    }

    function ran_name($name) {
        if ($name == '') {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $now = new DateTime();
            $this->name = substr(uniqid(), 2) . '_' . md5($now->format('H:i:s')) . '_' . md5($now->format('Y-m-d'));
        } else {
            $this->name = $name;
        }
    }

    function prt_input() {
        echo $this->form_prt ? '<form enctype="multipart/form-data" name="fup_' . $this->ran_up . '" method="POST">' : '';
        echo '<input type="file" name="up_' . $this->ran_up . '" /><br/>';
        echo $this->form_prt ? '<input type="submit"  name="fbut_' . $this->ran_up . '" /></form>' : '';
    }

    function process_form() {
        $name = 'up_' . $this->ran_up;
        if (isset($_POST['fbut_' . $this->ran_up])) {
            try {
                $type = explode('.', $_FILES[$name]['name']);
                $this->check_size($_FILES[$name]['size']);
                $this->check_type($type[1]);
                $this->check_exist($type[1]);
                move_uploaded_file($_FILES[$name]['tmp_name'], $this->folder . $this->name);
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }
        }
    }

    function check_input() {
        $name = 'up_' . $this->ran_up;
        try {
            $type = explode('.', $_FILES[$name]['name']);
            $this->check_size($_FILES[$name]['size']);
            if (isset($type[1])) {
                $this->check_type($type[1]);
                $this->check_exist($type[1]);
                $this->name.='.' . $type[1];
                move_uploaded_file($_FILES[$name]['tmp_name'], $this->folder . $this->name);
            } else {
                throw new Exception($this->a_lan['f_size_err']);
            }
        } catch (Exception $exc) {
            echo '<p class="Err" >' . $exc->getMessage() . '</p>';
        }
    }

    public function check_size($size) {
        if ($size > $this->max_size) {
            throw new Exception($this->a_lan['f_size_err']);
        }
    }

    public function check_type($type) {
        if (!array_search($type, $this->list_style)) {
            throw new Exception($this->a_lan['f_type_err'] . implode(' , ', $this->list_style));
        }
    }

    public function check_exist($type) {
        if (file_exists($this->folder . $this->name . $type)) {
            throw new Exception($this->a_lan['f_exists']);
        }
    }

}
