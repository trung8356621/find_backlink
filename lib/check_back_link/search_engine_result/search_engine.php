<?php

class search_engine {

    private $lan;
    //domain
    public $domain;
    //thời gian đọc link
    public $options = [
        'http' => [
            'method' => 'GET',
            'timeout' => '1000'
        ]
    ];
    //mảng chứa danh sách xpath
    protected $search_xPath = array(
        //đường dẫn search engine
        's_url' => '',
        //xpath chứa kết quả
        'result' => '',
        //lấy số kết quả
        'num_result' => '',
        //số kết quả 1 trang
        'num_page' => 10,
        //lấy tiêu đề
        'title' => '',
        //lấy link
        'link' => '',
        //lấy miêu tả
        'dis' => '',
    );
    //danh sách kết quả
    //ipv4
    public $ipv4;
    protected $list_result;
    //total_result
    public $total_result;
    //cur page
    public $cur_page;
    //danh sách backlink
    public $list_backlink; //final yeah
    public $query;
    public $file;
    public $end;

    function __construct($lan, $domain, $ipv4, $cur_page) {
        $this->domain = $domain;
        $this->lan = $lan;
        $this->ipv4 = $ipv4;
        $this->cur_page = $cur_page;
    }

    function get_Result_Content($link, $se_ip) {
        $this->list_result = array();
        //set time and method
        libxml_set_streams_context(stream_context_create($this->options));
        //crate new DOM
        $dom = new DOMDocument('1.1', 'utf-8');
        //kill error
        libxml_use_internal_errors(true);
        //load new 
        $dom->loadHTMLFile($link);
        //create new xPath
        $xPath = new DOMXPath($dom);
        //Lấy danh sách result trừ domain được search
        $lst_Pro = $xPath->query($this->search_xPath['result']);
        if ($lst_Pro->length != 0) {
            $this->end = $lst_Pro->length < 10 ? -1 : 0;
            foreach ($lst_Pro as $value) {
                $arr = $xPath->query($this->search_xPath['link'], $value)->item(0)->nodeValue;
                $res = explode('/', $arr);
                if (isset($res[2]) && !empty($res[2])) {
                    $ipv4 = gethostbyname(Check_Link::remove_http($res[2]));
                    if ($ipv4 != $this->ipv4 && $ipv4 != $se_ip) {
                        $this->list_result[] = $arr;
                    }
                }
                unset($arr, $res);
            }
        } else {
            $this->end = -1;
        }
    }

    function prt_file($page, $se) {
        @mkdir(DEF_PATH . 'public/se_file/', 0700);
        $file = @fopen(DEF_PATH . 'public/se_file/' . $this->ipv4 . '-' . $se . '.txt', $page != 0 ? 'a' : 'w');
        $txt = '';
        foreach ($this->list_result as $value) {
            $txt.=$value . "\n";
        }
        fwrite($file, $txt);
        fclose($file);
    }

    function prt_query($se, $num_se) {
        $this->file = 'se_file/' . $this->ipv4 . '-' . $se . '.txt';
        /*
          num_se = 0 -- google
         * 1 -- bing
         * 2 -- yahoo
         *          */
        $this->query = 'INSERT INTO search_engine(bl_page,bl_num_result,bl_page_checked,bl_se_link,ipv4,bl_se_file,bl_end) VALUES("' . $this->total_result . '","' . round($this->total_result*0.3) . '",70,' . $num_se . ',"' . $this->ipv4 . '","' . $this->file . '",' . $this->end . ')';
    }

}
