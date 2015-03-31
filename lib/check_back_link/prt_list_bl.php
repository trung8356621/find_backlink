<?php

/**
 * Description of prt_list_bl
 *
 * @author trung
 */
class prt_list_bl {

    private $num_bl;
    private $ran;
    private $lan;
    private $cur;
    private $like;
    public $ipv4;
    private $lst_bl;
    private $num;
    private $type;

    function __construct($ipv4, $num_bl, $lan, $typef, $like = '', $cur = 0) {
        $this->ipv4 = $ipv4;
        $this->like = $like;
        $this->cur = $cur;
        $this->lan = $lan;
        $this->num_bl = $num_bl;
        $this->type = isset($_SESSION['us']) ? true : false;
        $this->ran = $this->type ? 10 : 5;
        $this->typef = $typef;
        $this->get_bl();
        $this->prt_tit();
        echo '<table>';
        $this->prt_bl_head();
        $this->prt_bl_body();
        echo '</table>';
    }

    function get_bl() {
        Controller::get_model('s_link');
        $sl = new s_link();
        $a_res = $sl->get_tbl_bl($this->ipv4, $this->cur, $this->typef, $this->ran, $this->like);
        $this->lst_bl = $a_res['data'];
        $this->num = $a_res['count'];
    }

    function prt_tit() {
        ?>
        <div id="bl_info_head">
            <form id="filter" action="">
                <input id="bl_In" type="text" placeholder="<?php echo $this->lan['fil_tit']; ?>" />
                <input class="But But_1" type="submit" value="<?php echo $this->lan['fil_but']; ?>" />
            </form>
            <div class="But But_2"><?php echo $this->lan['export']; ?></div>
            <div id="pag_box" alt="<?php echo $this->num ?>">
                <div class="But But_2" onclick="next(this)" id='next'><</div>
                <div id="pagging" alt="<?php echo $this->num ?>"><?php echo ($this->cur + $this->ran) . '/' . $this->num_bl ?></div>
                <div class="But But_2" onclick="next(this)" id='prev'>></div>

            </div>
        </div>
        <?php
    }

    function prt_bl_head() {
        ?>
        <tr>
            <th rowspan="2"></th>
            <th><?php echo $this->lan['tbl_result']['tr_1'][0]; ?></th>
            <th><?php echo $this->lan['tbl_result']['tr_2'][0]; ?></th>
            <th rowspan="2"><?php echo $this->lan['tbl_result']['tr_3']; ?></th>
            <th rowspan="2"><?php echo $this->lan['tbl_result']['tr_4'][0]; ?></th>
        </tr>
        <tr>
            <th class="lnk"><?php echo $this->lan['tbl_result']['tr_1'][1]; ?></th>
            <th class="lnk"><?php echo $this->lan['tbl_result']['tr_2'][1]; ?></th>
        </tr>
        <?php
    }

    function prt_bl_body() {
        $i = 1;
        $class = '';
        foreach ($this->lst_bl as $value) {
            $class = $i % 2 == 0 ? 'c' : 'l';
            ?>
            <tr class="<?php echo $class ?>">
                <td style="text-align: center" rowspan="2"><?php echo $i; ?></td>
                <td><?php echo $value['bl_tit']; ?></td>
                <td><?php echo $value['link_title']; ?></td>
                <td rowspan="2"><img src="<?php echo DEF_URL . 'view/default/img/' . ($value['bl_follow'] == 0 ? 'follow' : 'nofollow'); ?>.png" /></td>
                <td rowspan="2"><?php echo $value['last_check']; ?></td>
            </tr>
            <tr class="<?php echo $class ?>">
                <td class="lnk"><a target="_blank" href="<?php echo $value['bl']; ?>"><?php echo $value['bl']; ?></a></td>
                <td class="lnk"><a target="_blank" href="<?php echo $value['link']; ?>"><?php echo $value['link']; ?></a></td>
            </tr>
            <?php
            $i++;
        }
        $count = count($this->lst_bl);
        if ($count < 10) {
            $j = $i;
            while ($j < 11) {
                $class = $j % 2 == 0 ? 'c' : 'l';
                ?>
                <tr class="<?php echo $class ?>">
                    <td style="text-align: center"><?php echo $j; ?></td>
                    <td class="lnk" colspan="4">
                        <a href="<?php echo DEF_URL . ($this->type ? 'pricing' : 'register') ?>"><?php echo $this->lan['not-aval'] ?></a>
                    </td>
                </tr>
                <?php
                $j++;
            }
        }
    }

}
