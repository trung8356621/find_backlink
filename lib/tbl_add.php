<?php
/*
 * Project_Name: thietkewebsangtao
 * Coder: Nguyễn Trần Minh Trung
 */

class tbl_add {

    private $func;
    private $hide;
    private $id;
    public $tbl_name;
    private $a_title;
    private $name_val;
    private $foreign_key;
    public $a_err;
    public $a_var;
    public $lan;
    public $val_fill;
    public $flag;

    function __construct($tbl_name, $lan, $id = '', $val_fill = [], $hide = [], $func = '', $tit_more = array()) {
        $this->tbl_name = $tbl_name;
        $this->lan = $lan;
        $this->get_title($tit_more);
        $this->hide = $hide;
        $this->val_fill = $val_fill;
        $this->func = $func;
        if ($id != '') {
            $this->id = $id;
            $this->get_by_id();
            $this->get_foreign_key();
            $this->create_form();
            $this->prt_update();
        } else {
            $this->get_foreign_key();
            $this->create_form();
            $this->prt_insert();
        }
    }

    function get_by_id() {
        $at = new ad_table();
        $this->val_fill = $at->select_by_id($this->tbl_name, $this->a_title[0]['name'], $this->id);
    }

    function create_form() {
        echo '<script src="' . DEF_URL . 'library/ckeditor/ckeditor.js" ></script>';
        echo '<script src="' . DEF_URL . 'library/ckeditor/samples/sample.js" ></script>';
        echo '<form enctype="multipart/form-data" class="Form" method="POST" action="" >';
        foreach ($this->a_title as $key => $value) {
            if (isset($value['name'])) {
                $extra = isset($value['extra']) ? $value['extra'] : '';
                if ($extra != 'auto_increment') {
                    $this->prt_input($value['name'], $key);
                    $this->name_val .= $value['name'] . ',';
                }
            }
        }
        echo '<div class="Clear"></div>';
        echo '<input class="But But_2" type="submit" value="' . (isset($this->lan['but_' . $this->tbl_name]) ? $this->lan['but_' . $this->tbl_name] : 'ok') . '" name="but_add" />';
        echo '</form>';
    }

    function get_title($tit_more) {
        $at = new ad_table();
        $title = $at->show_colums($this->tbl_name);
        foreach ($title as $value) {
            $this->a_title[] = array('name' => $value['Field'], 'type' => $value['Type'], 'extra' => $value['Extra']);
        }
        count($tit_more) > 0 ? $this->a_title[] = $tit_more : '';
        unset($title);
    }

    function get_foreign_key() {
        $at = new ad_table();
        $foreign_key = $at->show_foreign($this->tbl_name);
        if (count($foreign_key) > 0) {
            $this->foreign_key = $foreign_key;
        }
        unset($foreign_key);
    }

    function prt_input($name, $key) {
        if (count($this->foreign_key) > 0) {
            $flag = false;
            foreach ($this->foreign_key as $value) {
                if (!isset($this->hide[$name])) {
                    if ($name == $value['COLUMN_NAME']) {
                        $tbl_mol = new ad_table();
                        $this->prt_checked($name, $tbl_mol->{$name . '_check'}(), $key);
                        $flag = false;
                        break;
                    } else {
                        $flag = true;
                    }
                }
            }
            if ($flag) {
                $this->check_input_name($name, $key);
            }
        } else {
            $this->check_input_name($name, $key);
        }
    }

    function check_input_name($name, $key) {
        $type = array();
        preg_match('/\w+/', $this->a_title[$key]['type'], $type['type']);
        preg_match('/\d+/', $this->a_title[$key]['type'], $type['num']);
        echo!isset($this->hide[$name]) ? '<label>' . $this->lan[$this->tbl_name][$key] . ': </label><br/>' : '';
        $text = array('', 'text', 'tinytext', 'mediumtext', 'longtext');
        if (strpos($this->a_title[$key]['name'], 'file') !== false) {
            if (!isset($this->hide[$name])) {
                ad_controller::get_lib('uploader');
                @mkdir(DEF_PATH . 'public/' . $name);
                $up = new uploader($this->lan, (DEF_PATH . 'public/'), $name, Ad_Con_Normal::$a_conf[0], Ad_Con_Normal::$a_conf[1], $name, (isset($this->val_fill[$name]) ? $this->val_fill[$name] : ''), '', false);
                if (isset($_POST['but_add'])) {
                    $up->check_input();
                    $this->a_var[$key] = '"' . $up->name . '"';
                }
            } else {
                $this->a_var[$key] = '""';
            }
        } else if (strpos($this->a_title[$key]['name'], 'zip') !== false) {
            if (!isset($this->hide[$name])) {
                ad_controller::get_lib('uploader');
                @mkdir(DEF_PATH . 'public/' . $name);
                $up = new uploader($this->lan, (DEF_PATH . 'public/'), $name, 20000000, array('zip'), $name, '', '', false);
                $ran_name = $up->name;
                if (isset($_POST['but_add'])) {
                    $up->check_input();
                    $check = $this->unzip(($up->folder . $up->name), ($up->folder . $ran_name));
                    $check ? $this->a_var[$key] = '"' . $ran_name . '"' : '<p class="Err" >false</p>';
                }
            } else {
                $this->a_var[$key] = '""';
            }
        } else if (strpos($this->a_title[$key]['name'], 'pass') !== false) {
            echo '<input class="Text" type="password" value="' . (isset($_POST['tbl_add'][$name]) ? $_POST['tbl_add'][$name] : (isset($this->val_fill[$name]) ? $this->val_fill[$name] : '')) . '" name="tbl_add[' . $name . ']" /></label><br/>';
            $this->validate($name, $key, $type);
        } else if ((isset($type['num'][0]) && $type['num'][0] >= 200) || array_search(strtolower($this->a_title[$key]['type']), $text)) {
            ?>
            <script>
                CKEDITOR.replace('tbl_add[<?php echo $name ?>]');
            </script>
            <?php
            echo '<textarea class="ckeditor" name="tbl_add[' . $name . ']" cols="30" rows="10">' . (isset($_POST['tbl_add'][$name]) ? $_POST['tbl_add'][$name] : (isset($this->val_fill[$name]) ? base64_decode($this->val_fill[$name]) : '')) . '</textarea>';
            $this->a_var[$key] = '"' . (isset($_POST['tbl_add'][$name]) ? base64_encode($_POST['tbl_add'][$name]) : '') . '"';
        } else {
            echo '<input placeholder="' . $this->lan[$this->tbl_name][$key] . '" class="Text" type="' . (isset($this->hide[$name]) ? 'hidden' : 'text') . '" value="' . (isset($_POST['tbl_add'][$name]) ? $_POST['tbl_add'][$name] : (isset($this->val_fill[$name]) ? $this->val_fill[$name] : '')) . '" name="tbl_add[' . $name . ']" /></label><br/>';
            if (!isset($this->hide[$name])) {
                $this->validate($name, $key, $type);
            } else {
                $this->a_var[$key] = '""';
            }
        }
    }

    function unzip($file, $folder) {
        $zip = new ZipArchive;
        $res = $zip->open($file);
        if ($res === TRUE) {
            echo $folder;
            mkdir($folder);
            $zip->extractTo($folder);
            $zip->close();
            unlink($file);
            return true;
        } else {
            return false;
        }
    }

    function prt_radio($name) {
        foreach ($this->lan[$name] as $key => $value) {
            echo '<label for="' . $name . $key . '">' . $value['tit'] . '<input class="Radio" value="' . $key . '" type="radio" ' . ((isset($this->val_fill[$name]) ? $this->val_fill[$name] : (isset($_POST['tbl_add'][$name]) ? $_POST['tbl_add'][$name] : 0)) == $value['val'] ? 'checked="checked"' : '') . ' name="tbl_add[' . $name . ']" id="' . $name . $key . '" /></label>';
        }
        echo '<br>';
    }

    function prt_checked($name, $a_value, $key) {
        echo '<label>' . $this->lan[$this->tbl_name][$key] . '</label><br/>';
        echo '<select name="tbl_add[' . $name . ']" class="Text">';
        foreach ($a_value as $value) {
            echo '<option ' . ((isset($this->val_fill[$name]) ? $this->val_fill[$name] : (isset($_POST['tbl_add'][$name]) ? $_POST['tbl_add'][$name] : '')) == $value[0] ? 'selected="selected"' : '') . ' value="' . $value[0] . '">' . $value[1] . '</option>';
        }
        echo '</select><br/>';
        if (isset($_POST['but_add'])) {
            $this->a_var[$key] = $_POST['tbl_add'][$name];
        }
    }

    function prt_insert() {
        if (isset($_POST['but_add']) && count($this->a_err) == 0) {
            $at = new ad_table();
            if ($res = $at->insert_last('INSERT INTO ' . $this->tbl_name . '(' . substr($this->name_val, 0, strlen($this->name_val) - 1) . ') VALUES(' . implode(',', $this->a_var) . ')')) {
                $this->flag = $res;
            } else {
                $this->flag = false;
            }
        } else {
            $this->a_err = array();
        }
    }

    function prt_update() {
        if (isset($_POST['but_add']) && count($this->a_err) == 0) {
            $a_up = array();
            foreach ($this->a_var as $key => $value2) {
                $a_up[] = $this->a_title[$key]['name'] . '=' . $value2;
            }
            $at = new ad_table();
            if ($res = $at->insert_last('UPDATE ' . $this->tbl_name . ' SET ' . implode(',', $a_up) . ' WHERE ' . $this->a_title[0]['name'] . '=' . $this->id)) {
                $this->flag = $res;
            } else {
                $this->flag = false;
            }
        } else {
            $this->a_err = array();
        }
    }

    function validate($name, $key, $type) {
        if (isset($_POST['tbl_add'][$name])) {
            if ($_POST['tbl_add'][$name] !== '') {
                if (isset($type['num'][0])) {
                    if ($type['num'][0] >= 200) {
                        $this->a_var[$key] = '"' . base64_encode($_POST['tbl_add'][$name]) . '"';
                    } else {
                        $this->check_ok($name, $_POST['tbl_add'][$name], $key, $type['num'][0]);
                    }
                } else {
                    $this->check_ok($name, $_POST['tbl_add'][$name], $key);
                }
            } else {
                echo '<p class = "Err" >' . $this->lan['e_null'] . '</p>';
                $this->a_err[] = $name;
            }
        }
    }

    function check_ok($name, $val, $key) {
        if (strpos($this->a_title[$key]['name'], 'email') !== false) {
            if (filter_var($val, FILTER_VALIDATE_EMAIL) !== false) {
                $this->a_var[$key] = '"' . addslashes($val) . '"';
            } else {
                echo '<p class = "Err" >' . $this->lan['e_email'] . '</p>';
                $this->a_err[] = $name;
            }
        } else if (strpos($this->a_title[$key]['name'], 'pass') !== false) {
            $this->a_var[$key] = '"' . md5($val) . '"';
        } else {
            $this->a_var[$key] = '"' . $val . '"';
        }
    }

}
