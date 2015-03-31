<?php

class yahoo_result extends search_engine {

    private $link;

    function __construct($lan, $domain, $ipv4, $cur_page = 0, $total_result = 0) {
        parent::__construct($lan, $domain, $ipv4, $cur_page);
        $this->search_xPath['s_url'] = 'http://www.yahoo.com/search?q=';
        $this->search_xPath['result'] = './/*[@id="b_results"]/li/h2';
        $this->search_xPath['num_result'] = './/*[@id="b_tween"]/span';
        $this->search_xPath['num_page'] = 10;
        $this->search_xPath['title'] = './/h3';
        $this->search_xPath['link'] = './/a/@href';
        $this->cur_page = $cur_page;
        $this->link = urldecode($this->search_xPath['s_url'] . Check_Link::remove_http($this->domain));
        if ($total_result != 0) {
            $this->total_result = $total_result;
        } else {
            $this->get_result();
        }
        $this->loop_page($this->link);
    }

    //duyệt phân trang
    function get_result() {
        //set time and method
        libxml_set_streams_context(stream_context_create($this->options));
        //crate new DOM
        $dom = new DOMDocument('1.1', 'utf-8');
        //kill error
        libxml_use_internal_errors(true);
        //load new 
        $dom->loadHTMLFile($this->link);
        //create new xPath
        $xPath = new DOMXPath($dom);
        //Lấy số kết quả nhận dc
        $result = 0;
        preg_match_all('!\d+!', $xPath->query($this->search_xPath['num_result'])->item(0)->nodeValue, $result);
        $this->total_result = (int) implode('', $result[0]);
    }

    function update_ref($ref) {
        
    }

    function loop_page($link) {
        $i = $this->cur_page;
        $this->total_page = round($this->total_result > 10 ? $this->total_result / $this->search_xPath['num_page'] : $this->total_result);
        while ($i <= ($this->cur_page + 70)) {
            $this->get_Result_Content($link . '&start=' . $i, array('yahoo', Check_Link::remove_http($this->domain)));
            $this->prt_file($i, 'yahoo');
            if ($this->end != '-1') {
                $i+=10;
            } else {
                $this->end = '-1';
                break;
            }
        }
        echo 'ss' . $this->end;
        $this->prt_query('yahoo', 2);
    }

}
