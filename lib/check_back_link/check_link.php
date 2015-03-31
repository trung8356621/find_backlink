<?php

class Check_Link {

    private $view;
//domain cần search backlink
    public $domain;
//tách domain thành 2 phần domain và sub domain nếu có 
    protected $domain_split;
//danh sách link ra
    public $list_BL;
//
    private $key_word;
//flag db link
    private $flag_db;
//option
    public $options = [
        'http' => [
            'method' => 'GET',
            'timeout' => '1000'
        ]
    ];
    private $https;

    function __construct($domain, $view) {
        $this->domain = $this->remove_Slash($domain);
        $this->view = $view;
        if ($this->check_exists($this->domain) == 0) {
            $this->view->get_View('modules/search/s_false.php');
        } else {
            $this->flag_db = false;
            $this->format_domain();
            $this->split_domain($this->domain_split[0]);
            if (count(explode('.', $this->key_word['ipv4'])) == 4) {
                $this->check_exist();
            }
        }
    }

//kiểm tra link tồn tại
    function check_exists($link) {
        if (strpos($link, 'http://') === false) {
            $link = 'http://' . $link;
        }
        $file_headers = @get_headers($file);
        if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            $exists = 0;
        } else {
            $exists = 1;
        }
        return $exists;
    }

    public static function remove_http($link) {
        if (strpos($link, 'https://') !== false) {
            $link = str_replace('https://', '', $link);
            $this->https = true;
        } else if (strpos($link, 'http://') !== false) {
            $link = str_replace('http://', '', $link);
        }
        if (strpos($link, 'www.') !== false) {
            $link = str_replace('www.', '', $link);
        }
        return $link;
    }

//hàm xóa dấu / cuối cùng
    static function remove_Slash($url) {
        if ($url[strlen($url) - 1] == '/') {
            $url = rtrim($url, '/');
        }
        return $url;
    }

//loc domain để tách
    function format_domain() {
        $link = $this->domain;
        if (strpos($link, 'http://') !== false) {
            $link = str_replace('http://', '', $link);
        } else if (strpos($link, 'https://') !== false) {
            $link = str_replace('https://', '', $link);
        }

        if (strpos($link, 'www.') !== false) {
            $link = str_replace('www.', '', $link);
        }
        $this->domain_split = explode('/', $link);
    }

//kiểm backlink tồn tại hay die và đưa ra mảng kết quả cuối
    function test_Backlink_Alive() {
        $final_list = array();
        if (count($this->list_BL) > 0) {
            $i = 0;
            foreach ($this->list_BL as $value) {
                $final_list[$i]['link_form'] = $this->domain;
                $final_list[$i]['link_to'] = $value;
                $final_list[$i]['alive'] = $this->check_exists($value) ? 'V' : 'X';
                $i++;
            }
            return $final_list;
        }
    }

    //tách domain ra để lấy từ khóa so sánh
    function split_domain($main_domain) {
        $exp_domain = explode('.', $main_domain);
        $len_domain = count($exp_domain);
        $domain = array(
            'main_domain' => '', //domain chính
            'extention' => '', //đuôi
            'sub_domain' => '', //domain phụ
            'ipv4' => '',
            'ipv4_sub' => '',
        );
        if ($len_domain == 4) {
            //trường hợp 1 đầy đủ 3 thành phần
            $domain['main_domain'] = $exp_domain[1];
            $domain['extention'] = $exp_domain[2] . '.' . $exp_domain[3];
            $domain['sub_domain'] = $exp_domain[0];
            $domain['ipv4_sub'] = gethostbyname($domain['sub_domain'] . '.' . $domain['main_domain'] . '.' . $domain['extention']);
        } else if ($len_domain == 3) {
            if (array_search($exp_domain[1], $this->list_public)) {
                //có 2 thành phần (đuôi gộp)
                $domain['main_domain'] = $exp_domain[0];
                $domain['extention'] = $exp_domain[1] . '.' . $exp_domain[2];
                $domain['sub_domain'] = '';
            } else {
                //có đủ 3 thành phần
                $domain['main_domain'] = $exp_domain[1];
                $domain['extention'] = $exp_domain[2];
                $domain['sub_domain'] = $exp_domain[0];
                $domain['ipv4_sub'] = gethostbyname($domain['sub_domain'] . '.' . $domain['main_domain'] . '.' . $domain['extention']);
            }
        } else if ($len_domain == 2) {
            //có 2 thành phần
            $domain['main_domain'] = $exp_domain[0];
            $domain['extention'] = $exp_domain[1];
            $domain['sub_domain'] = '';
        }
        $domain['ipv4'] = gethostbyname($domain['main_domain'] . '.' . $domain['extention']);
        $this->key_word = $domain;
    }

    function check_exist() {
        Controller::get_model('s_link');
        $sl = new s_link();
        $res = '';
        if ($this->key_word['sub_domain'] != '') {
            $res = $sl->check_ip($this->key_word['ipv4_sub']);
        } else {
            $res = $sl->check_ip($this->key_word['ipv4']);
        }
        $link = ($this->key_word['sub_domain'] != '' ? $this->key_word['sub_domain'] . '.' : '') . $this->key_word['main_domain'] . '.' . $this->key_word['extention'];
        Controller::get_lib('check_back_link/check_link_in');
        if (is_array($res) && count($res) > 0) {
            $this->flag_db = true;
            $this->check_user();
        } else {
            $this->search_Engine($link);
            $this->check_user();
            $this->insert_info_bl($this->key_word['google_file']);
            $this->insert_info_bl($this->key_word['bing_file']);
            //print
        }
    }

    function check_user() {
        Controller::get_lib('check_back_link/prt_bl_info');
        if (isset($_SESSION['us'])) {
            new prt_bl_info($this->view->a_lan, $this->domain, $this->key_word['ipv4'], $_SESSION['us']);
        } else {
            new prt_bl_info($this->view->a_lan, $this->domain, $this->key_word['ipv4'], -1);
        }
    }

    function search_Engine($link) {
        Controller::get_lib('check_back_link/search_engine_result/search_engine');
        Controller::get_lib('check_back_link/search_engine_result/google');
        Controller::get_lib('check_back_link/search_engine_result/bing');

        $gg = new google_result($this->view->a_lan, $link, $this->key_word['ipv4']);
        $this->key_word['google_query'] = $gg->query;
        $this->key_word['google_file'] = $gg->file;

        $bing = new bing_result($this->view->a_lan, $link, $this->key_word['ipv4']);
        $this->key_word['bing_query'] = $bing->query;
        $this->key_word['bing_file'] = $bing->file;

        $this->key_word['total_res'] = $gg->total_result + $bing->total_result;
        $sa = new s_link();
        $sa->save_link($link, $this->key_word);
    }

    function insert_info_bl($file) {
        $handle = fopen(DEF_PATH . 'public/' . $file, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                echo urldecode($line) . '<br/><br/>';
                new Check_Link_In(urldecode($line), $this->key_word['ipv4']);
            }
            fclose($handle);
        } else {
            echo 'die';
        }
    }

    private $list_public = array(
        'com' => 'com',
        'net' => 'net',
        'org' => 'org',
        'info' => 'info',
        'edu' => 'edu',
        'mobi' => 'mobi'
    );

}
