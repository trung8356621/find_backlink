<?php

class prt_bl_info {

    private $domain;
    private $lan;
    public $ipv4;
    public $type;
    public $link;
    private $domain_info;
    private $domain_backlink_info;

    function __construct($lan, $domain, $ipv4, $type = 0) {
        $this->ipv4 = $ipv4;
        $this->type = $type;
        $this->lan = $lan;
        $this->link = DEF_URL . 'search/';
        $this->domain = $domain;
        $this->get_domain_info();
        $url = new Url(MAIN_SEG);
        $seg2 = $url->segment(2, false);
        echo '<div id="' . $this->ipv4 . '" class="bl_info_tb">';
        if ($seg2 == 'referring-page') {
            $this->prt_chart();
        } else {
            $this->prt_table($seg2);
        }
        echo '</div>';
    }

    function prt_table($seg2) {
        Controller::get_lib('check_back_link/prt_list_bl');
        new prt_list_bl($this->ipv4, $this->domain_info['backlink'], $this->lan, $seg2);
    }

    function get_domain_info() {
        $di = new s_link();
        $this->domain_info = $di->domain_info($this->ipv4);
        $dbi = new s_link();
        $this->domain_backlink_info = $dbi->domain_backlink_info($this->ipv4);
        $this->prt_head_info();
        $url = new Url(MAIN_SEG);
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                ipv4 = '<?php echo $this->ipv4 ?>';
                $('.action').click(function (event) {
                    event.preventDefault();
                    send_bl_tbl(0, '', $(this).attr('title'), '<?php echo $this->domain_info['backlink'] ?>', $(this));
                });
                submit(0, '', $(this).attr('title'), '<?php echo $this->domain_info['backlink'] ?>');
            })
        </script>
        <div id="bl_info">
            <?php
            $this->prt_domain_info($this->domain_info, $this->domain_backlink_info);
            $this->prt_backlink_info($this->domain_backlink_info);
            echo '</div>';
        }

        function prt_chart() {
            ?>

            <script type="text/javascript">
                var chart_bl_e = [
                    {
                        value: <?php echo $this->domain_backlink_info['com']; ?>,
                        color: "#F7464A",
                        highlight: "#FF5A5E",
                        label: "Com"
                    },
                    {
                        value: <?php echo $this->domain_backlink_info['net']; ?>,
                        color: "#46BFBD",
                        highlight: "#5AD3D1",
                        label: "Net"
                    },
                    {
                        value: <?php echo $this->domain_backlink_info['org']; ?>,
                        color: "#FDB45C",
                        highlight: "#FFC870",
                        label: "Org"
                    },
                    {
                        value: <?php echo $this->domain_backlink_info['country']; ?>,
                        color: "#949FB1",
                        highlight: "#A8B3C5",
                        label: "Country"
                    },
                    {
                        value: <?php echo $this->domain_backlink_info['education']; ?>,
                        color: "#4D5360",
                        highlight: "#616774",
                        label: "Education"
                    }
                ];

                var chart_bl_t = [
                    {
                        value: <?php echo $this->domain_backlink_info['bl_text']; ?>,
                        color: "#F7464A",
                        highlight: "#FF5A5E",
                        label: "Text"
                    },
                    {
                        value: <?php echo $this->domain_backlink_info['bl_img']; ?>,
                        color: "#46BFBD",
                        highlight: "#5AD3D1",
                        label: "Img"
                    }
                ];

                var chart_f = [
                    {
                        value: <?php echo $this->domain_backlink_info['bl_dofollow']; ?>,
                        color: "#46BFBD",
                        highlight: "#5AD3D1",
                        label: "Do follow"
                    },
                    {
                        value: <?php echo $this->domain_backlink_info['bl_notfollow']; ?>,
                        color: "#F7464A",
                        highlight: "#FF5A5E",
                        label: "No follow"
                    }
                ];

                window.onload = function () {
                    var ctx = document.getElementById("chart_bl_e").getContext("2d");
                    window.myDoughnut = new Chart(ctx).Pie(chart_bl_e, {responsive: true});

                    var ctx2 = document.getElementById("chart_bl_t").getContext("2d");
                    window.myDoughnut = new Chart(ctx2).Pie(chart_bl_t, {responsive: true});

                    var ctx3 = document.getElementById("chart_f").getContext("2d");
                    window.myDoughnut = new Chart(ctx3).Pie(chart_f, {responsive: true});
                };
            </script>
            <div class="chart">
                <div class="char_c">
                    <canvas id="chart_bl_e" width="300" height="300"/>
                </div>
                <div class='chart_name'><?php echo $this->lan['bl_e']; ?></div>
            </div>
            <div class="chart">
                <div class="char_cs">
                    <div class="char_c">
                        <canvas id="chart_bl_t" width="200" height="200"/>
                    </div>
                    <div class='chart_name'><?php echo $this->lan['di_tit']; ?></div>
                </div>
                <div class="char_cs">
                    <div class="char_c">
                        <canvas id="chart_f" width="200" height="200"/>
                    </div>
                    <div class='chart_name'><?php echo $this->lan['tbl_result']['tr_3']; ?></div>
                </div>
            </div>
            <?php
        }

        function get_a_type() {
            
        }

        function prt_link_num($name, $value) {
            echo '<div class="bli_con">';
            if ($this->type != -1) {
                echo $this->lan['bl_info'][$name] . '<a title="' . $name . '" class="action" href = "' . $this->link . $name . '/?q=' . $this->domain . '">' . number_format((double) $value, 0, '.', '.') . '</a>';
            } else {
//            echo '<a href="">' . $this->lan['must_up'] . '</a>';
                echo $this->lan['bl_info'][$name] . '<div class="But_2"><a href="' . DEF_URL . 'register">' . $this->lan['must_reg'] . '</a></div>';
            }
            echo '</div>';
        }

        function prt_domain_info($a_info, $a_info_1) {
            ?>
            <div class="bl_info">
                <?php
                $i = 0;
                foreach ($a_info as $key => $value) {
                    echo '<div class="' . ($i == 0 ? 'bli_head' : 'bli_con') . '" >' . $this->lan[$key] . '<a title="' . $key . '" class="action" href="' . $this->link . $key . '/?q=' . $this->domain . '">' . number_format((double) $value, 0, '.', '.') . '</a></div>';
                    $i++;
                }
                $j = 0;
                foreach ($a_info_1 as $key => $value) {
                    if ($j < 5) {
                        $this->prt_link_num($key, $value);
                    } else {
                        break;
                    }
                    $j++;
                }
                ?>
            </div>
            <?php
        }

        function prt_backlink_info($a_info) {
            ?>
            <div class="bl_info">
                <div class="bli_head"><?php echo $this->lan['di_tit'] ?></div>
                <?php
                $i = 0;
                foreach ($a_info as $key => $value) {
                    if ($i >= 5) {
                        $this->prt_link_num($key, $value);
                    }
                    $i++;
                }
                ?>
            </div>
            <?php
        }

        function prt_bl_tbl() {
            ?>
            <div id="<?php echo $this->ipv4 ?>" class="bl_info_tb">


            </div>
            <script type="text/javascript">
                ipv4 = '<?php echo $this->ipv4 ?>';
                $(document).ready(function () {
                    submit(0);
                });
            </script>
            <?php
        }

        public function prt_head_info() {
            ?>
            <table id='head_info'>
                <tr>
                    <th><?php echo $this->lan['your-domain'] ?></th>
                    <th><?php echo $this->lan['referring-page'] ?></th>
                    <th><?php echo $this->lan['backlink'] ?></th>
                    <th><?php echo $this->lan['social'] ?></th>
                </tr>
                <tr>
                    <td><?php echo $this->domain ?></td>
                    <td><?php echo number_format((double) $this->domain_info['referring-page'], 0, '.', '.') ?></td>
                    <td><?php echo number_format((double) $this->domain_info['backlink'], 0, '.', '.') ?></td>
                    <td>
                        <ul id="social_box">
                            <li>
                                <img src="<?php echo DEF_URL . 'view/default/img/face.png' ?>" /><br/>
                                <b>0</b>
                            </li>
                            <li>
                                <img src="<?php echo DEF_URL . 'view/default/img/google.png' ?>" /><br/>
                                <b>0</b>
                            </li>
                            <li>
                                <img src="<?php echo DEF_URL . 'view/default/img/twi.png' ?>" /><br/>
                                <b>0</b>
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <?php
        }

    }
    