<?php

class s_link extends model {

    function __construct() {
        parent::__construct(Controller::$a_con);
    }

    function check_ip($ipv4) {
        return $this->get_Results($this->con->select_query('SELECT * FROM domain WHERE ipv4="' . $ipv4 . '"', 'info-web'));
    }

    function save_link($domain, $a_value) {
        $backlink = $a_value['total_res'] * 0.8;
        $public = $backlink * 0.75;
        $a_sel = array(
            'INSERT INTO domain(ipv4,domain,`referring-page`,backlink,ipv4_dad) VALUES ("' . $a_value['ipv4'] . '","' . $domain . '","' . $a_value['total_res'] . '","' . $backlink . '","' . ($a_value['ipv4_sub'] != '' ? $a_value['ipv4_sub'] : '127.0.0.1') . '")',
            'INSERT INTO domain_backlink_info(`ipv4`, `com`, `net`, `org`, `country`, `education`, `bl_text`, `bl_img`, `bl_dofollow`, `bl_notfollow`) '
            . 'VALUES("' . $a_value['ipv4'] . '"'
            . ',' . rand(round($public * 0.5), round($public * 0.7))
            . ',' . rand(round($public * 0.01), round($public * 0.2))
            . ',' . rand(round($public * 0.01), round($public * 0.1))
            . ',' . rand(round($backlink * 0.05), round($backlink * 0.2))
            . ',' . rand(round($backlink * 0.01), round($backlink * 0.05))
            . ',' . rand(round($backlink * 0.5), $public)
            . ',' . rand(round($backlink * 0.1), round($backlink * 0.3))
            . ',' . rand(round($backlink * 0.8), round($backlink * 0.9)) . ',' . rand(round($backlink * 0.05), round($backlink * 0.2)) . ')',
            $a_value['google_query'],
            $a_value['bing_query']
        );
        return $this->con->ect_query_trans($a_sel);
    }

    function save_bl($lst_link) {
        return $this->con->ect_query_trans($lst_link);
    }

    function save_bl_spec($lst_link) {
        return $this->con->ect_query_non_trans($lst_link);
    }

    function domain_info($ipv4) {
        return $this->get_ResultA_One($this->con->select_query('SELECT `referring-page`,backlink FROM domain WHERE ipv4="' . $ipv4 . '"', 'domain_info'));
    }

    function domain_backlink_info($ipv4) {
        return $this->get_ResultA_One($this->con->select_query('SELECT `com`, `net`, `org`, `country`, `education`, `bl_text`, `bl_img`, `bl_dofollow`, `bl_notfollow` FROM domain_backlink_info WHERE ipv4="' . $ipv4 . '"', 'domain_info'));
    }

    function get_backlink($ipv4) {
        return $this->get_ResultA_One($this->con->select_query('SELECT refering_page,backlink FROM domain WHERE ipv4="' . $ipv4 . '"', 'domain_info'));
    }

    function get_tbl_bl($ipv4, $cur, $type, $ran, $like = '') {
        $type_w = '';
        switch ($type) {
            case 'com':
                $type_w = 'bl_ext=1';
                break;
            case 'net':
                $type_w = 'bl_ext=2';
                break;
            case 'org':
                $type_w = 'bl_ext=3';
                break;
            case 'country':
                $type_w = 'bl_ext=8';
                break;
            case 'education':
                $type_w = 'bl_edu=1';
                break;
            case 'bl_text':
                $type_w = 'link_title!="txt_img"';
                break;
            case 'bl_img':
                $type_w = 'link_title="txt_img"';
                break;
            case 'bl_dofollow':
                $type_w = 'bl_follow=0';
                break;
            case 'bl_notfollow':
                $type_w = 'bl_follow=1';
                break;
            default:
                $type_w = '';
                break;
        }
        $a_res['data'] = $this->get_Results($this->con->select_query('SELECT `bl_tit`, `bl`, `link_title`, `link`, `bl_follow`, `bl_ext`, `last_check` FROM backlink WHERE ipv4 = "' . $ipv4 . '" AND ' . ($like != '' ? 'bl LIKE "%' . $like . '%"' : '1=1 ') . ' AND ' . ($type_w != '' ? $type_w : ' 1=1 ') . ' ORDER BY last_check DESC LIMIT ' . ($cur != 0 ? $cur . ',' : '') . $ran, 'get_bl'), MYSQLI_ASSOC, true);
        $a_res['count'] = $this->get_Result_One($this->con->select_query('SELECT count(bl) FROM backlink WHERE ipv4 = "' . $ipv4 . '" AND ' . ($like != '' ? 'bl LIKE "%' . $like . '%"' : '1=1 ') . ' AND ' . ($type_w != '' ? $type_w : ' 1=1 ') . ' ORDER BY last_check DESC', 'get_bl'), true);

        return $a_res;
    }

}
