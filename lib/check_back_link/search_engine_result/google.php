<?php

class google_result extends search_engine {

    private $link;

    function __construct($lan, $domain, $ipv4, $cur_page = 0, $total_result = 0) {
        parent::__construct($lan, $domain, $ipv4, $cur_page);
        $this->search_xPath['s_url'] = 'https://www.google.com.vn/search?filter=0&q=';
        $this->search_xPath['result'] = './/h3';
        $this->search_xPath['num_result'] = './/div[@id="resultStats"]';
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
            $this->get_Result_Content_gg($link . '&start=' . $i, array('google', Check_Link::remove_http($this->domain)));
            $this->prt_file($i, 'google');
            if ($this->end != '-1') {
                $i+=10;
            } else {
                $this->end = '-1';
                break;
            }
        }
        $this->prt_query('google', 0);
    }
    
        function get_Result_Content_gg($link, $se_ip) {
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
                $arr = substr($xPath->query($this->search_xPath['link'], $value)->item(0)->nodeValue, 7, 300);
                $res = explode('/', $arr);
                if (isset($res[2]) && !empty($res[2])) {
                    $ipv4 = gethostbyname(Check_Link::remove_http($res[2]));
                    if ($ipv4 != $this->ipv4 && $ipv4 != $se_ip) {
                        $gres = '';
                        $gares = explode('&', $arr);
                        $i = 0;
                        while ($i < count($gares)) {
                            if ($i < count($gares) - 4) {
                                $gres.=$gares[$i];
                            }
                            $i++;
                        }
                        $this->list_result[] = $gres;
                    }
                }
                unset($arr, $res);
            }
        } else {
            $this->end = -1;
        }
    }

}
