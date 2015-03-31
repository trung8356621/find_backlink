<?php

class User_Choose {

    private $res;
    private $width;
    public $a_head;
    public $a_body;
    private $a_func;

    function __construct($res = false, $width = 100) {
        $this->width = $width;
        echo '<link rel="stylesheet" type="text/css" href="' . DEF_URL . 'view/default/css/S_UC.css" />';
        $this->get_head();
        echo '<table id="tbl_UC" width="' . $width . '%">';
        $this->res = $res;
        $this->prt_head();
        $this->get_func();
        $this->prt_body();
        $this->prt_foot();
        $this->prt_but();
        echo '</table>';
    }

    function get_head() {
        $uc = new user_c();
        $this->a_head = $uc->get_Results($uc->get_type());
        ?>
        <style type="text/css">
            #tbl_UC{
                margin-left: <?php echo (100-$this->width)/2 ?>%;
            }
            .UC_FH div{
                padding-left:3%;
                margin: 1%;
            }
            .UC div{
                background:#F7F7F7;
                color:#000;
            }
            th div.But_2{
                height: 30px;
                line-height: 30px;
                width: 90%;
                margin:5% auto;
            }
            #tbl_UC a{
                text-decoration: none;
                color:#Fff;
                font-weight: bold;
            }
            .UC_BUT div{
                cursor: pointer;
            }
            .price{
                background: <?php echo View::$color['but_1'] ?>;
                color: #fff;
            }
            .add{
                border-top: 5px solid <?php echo View::$color['but_1'] ?>;
            }
        </style>
        <?php
    }

    function get_func() {
        $uc = new user_c();
        $this->a_func = $uc->get_Results($uc->get_func());
    }

    function get_body($acc_type, $acc_func) {
        $uc = new user_c();
        return $uc->get_Result_One($uc->get_type_func($acc_type, $acc_func));
    }

    function prt_head() {
        $i = 0;
        echo '<tr><th class="UC_FH Fir"></th>';
        while ($i < count($this->a_head)) {
            echo '<th class="UC"><div style="color:#fff;background:' . $this->a_head[$i]['at_color'] . '">' . $this->a_head[$i]['at_name'] . '</div></th>';
            $i++;
        }
        echo '</tr>';
    }

    function prt_foot() {
        $i = 0;
        echo '<tr><td class="UC_FH price"><div>Price</div></td>';
        while ($i < count($this->a_head)) {
            echo '<th class="UC"><div class="Price">' . number_format($this->a_head[$i]['at_price'], 3, '.', '.') . ' VND</div></th>';
            $i++;
        }
        echo '</tr>';
    }

    function prt_but() {
        if ($this->res != false) {
            $i = 0;
            echo '<tr><td class="UC"></td>';
            while ($i < count($this->a_head)) {
                echo '<th><a href="' . DEF_URL . 'bill/' . $this->a_head[$i]['at_id'] . '"><div style="background:' . $this->a_head[$i]['at_color'] . '" class="But_2">SUBSCRIBE</div></a></th>';
                $i++;
            }
            echo '</tr>';
        } else {
            ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#tbl_UC').hover(function () {
                        $('.add,.UC_But,.UC a').stop().show(200);
                    }, function () {
                        $('.add,.UC_But,.UC a').stop().hide(200);
                    });
                });
            </script>
            <tr>
                <td colspan="5" class="add">
                    <a href="<?php echo DEF_AD_URL . 'tbl-add/acc_func/acc_func' ?>">
                        <div style="height: 20px;line-height: 20px;width: 100%" class="But But_1">+</div>
                    </a>
                </td>
            </tr>
            <?php
        }
    }

    function prt_body() {
        foreach ($this->a_func as $value) {
            ?>
            <tr>
                <td class="UC_FH"><?php echo $value['af_name'] . ($this->res == false ? '<a class="UC_But" href="' . DEF_AD_URL . 'tbl-add/acc_func/acc_func/' . $value['af_id'] . '"><img src="' . DEF_URL . 'view/default/img/edit.png" /></a>' : '') ?></td>
                <?php
                $i = 0;
                while ($i < count($this->a_head)) {
                    echo '<th class="UC"><div>' . $this->get_body($this->a_head[$i]['at_id'], $value['af_id']) . $value['unit'] . (!$this->res ? '<a href="' . DEF_AD_URL . 'tbl-add/type_func/type_func/' . $value['af_id'] . '"><img src="' . DEF_URL . 'view/default/img/edit.png" /></a>' : '') . '</div></th>';
                    $i++;
                }
                ?>
            </tr>
            <?php
        }
    }

}
