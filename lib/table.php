<?php
/*
 * Project_Name: Your node
 * Coder: Nguyễn Trần Minh Trung
 */

class Table {

    private $page;
    private $edit;
    private $delete;
    private $a_table; //array('name-table','name_id','id')
    public $a_lbl;
    public $a_result;
    private $add;
    private $a_title;

    function __construct($a_lbl, $a_result, $a_table, $page, $delete = true, $edit = true, $add = true) {
        $this->page = $page;
        $this->a_lbl = $a_lbl;
        $this->a_result = $a_result;
        $this->a_table = $a_table;
        $this->delete = $delete;
        $this->add = $add;
        $this->edit = $edit;
        $this->get_title();
        $this->prt_table();
    }

    function get_title() {
        ad_controller::get_model('ad_table');
        $at = new ad_table();
        $title = $at->show_colums($this->a_table[0]);
        foreach ($title as $value) {
            $this->a_title[] = $value['Field'];
        }
        unset($title);
    }

    function prt_table() {
        ?>
        <div id="Tbl_Add"></div>
        <table class="Ad_Table">
            <?php
            $this->prt_head();
            $this->prt_body();
            echo '</table>';
        }

        function prt_head() {
            $i = 0;
            echo '<tr>';
            while ($i < count($this->a_lbl)) {
                ?>
                <th><?php echo $i == 0 ? 'ID' : mb_convert_case($this->a_lbl[$i], MB_CASE_UPPER, "UTF-8"); ?></th>
                <?php
                $i++;
            }
            echo $this->edit ? '<th>EDIT</th>' : '';
            echo $this->delete ? '<th>DELETE</th>' : '';
            echo '</tr>';
        }

        function prt_body() {
            $i = 0;
            while ($i < count($this->a_result)) {
                echo '<tr ' . ($i % 2 == 0 ? 'class="c"' : 'class="l"') . '>';
                foreach ($this->a_result[$i] as $key => $value) {
                    ?>
                    <td><?php
                        if (strpos($key, 'file') !== false) {
                            echo '<img style="background:#777" height="50px" src="' . DEF_URL . 'public/' . $value . '" />';
                        } else if (strpos($key, 'pass') !== false) {
                            echo '*********';
                        }
                         else if (strpos($key, 'price') !== false) {
                            echo number_format((double) $value, 3, '.', '.').' VND';
                        }else {
                            echo $value;
                        }
                        ?></td>
                    <?php
                }
                echo $this->edit ? '<td><a href="' . DEF_AD_URL . 'tbl-add/' . $this->a_table[0] . '/' . $this->page . '/' . $this->a_result[$i][$this->a_title[0]] . '"><img width="20px" src="' . DEF_URL . 'view/default/img/edit.png" /></a></td>' : '';
                echo $this->delete ? '<td><a onclick="delete_td(this,event,'.$this->a_result[$i][$this->a_title[0]].')" href="' . DEF_AD_URL . 'delete/' . implode('/', $this->a_table) . '/' . $this->a_result[$i][$this->a_title[0]] . '"><img width="20px" src="' . DEF_URL . 'view/default/img/delete.png" /></a></td>' : '';
                $i++;
                echo '</tr>';
            }
        }

    }
    