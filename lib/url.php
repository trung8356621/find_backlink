<?php

/*
 * Project_Name: Your node
 * Coder: Nguyễn Trần Minh Trung
 */

class Url {

    //lấy url bằng biến #_SERVER
    public $url;
    //Mảng segment trích từ $url
    private $url_Exp;
    //Vị trí segment chính (mặc định = 1 thay đổi khi dùng folder phụ)
    public $main_seg;

    function __construct($main_seg) {
        $this->url = $this->remove_Slash($_SERVER['REQUEST_URI']);
        $this->url_Exp = explode("/", $this->url);
        $this->main_seg = $main_seg;
    }

    //hàm xóa dấu / cuối cùng
    static function remove_Slash($url) {
        if ($url[strlen($url) - 1] == '/') {
            $url = rtrim($url, '/');
        }
        return $url;
    }

    //hàm lấy segment chính
    function main_Segment($a_Exist = "", $def_page = '') {
        $result = $def_page;
        if (isset($this->url_Exp[$this->main_seg + 1])) {
            $segment = trim($this->url_Exp[$this->main_seg + 1]);
            if (count($a_Exist) > 0) {
                $result = $this->search_lst($segment, $a_Exist) ? $this->search_lst($segment, $a_Exist) : 'loi';
            }
        }
        return str_replace('-', '_', $result);
    }

    //hàm kiểm tra với danh sách segment 
    function search_lst($val, $lst) {
        if (array_search($val, $lst)) {
            $result = str_replace('-', '_', $val);
        } else {
            $result = false;
        }
        return $result;
    }

    //hàm lấy segment
    function segment($nu_Seg, $line_Con = true, $to = false) {
        $num_Seg = $nu_Seg + $this->main_seg;
        $result = '';
        if ($num_Seg > 0 && $num_Seg < count($this->url_Exp)) {
            $segment = trim($this->url_Exp[$num_Seg]);
            $result = $line_Con ? str_replace('-', '_', $segment) : $segment;
            if ($to) {
                $i = 0;
                $result = $this->remove_Slash(DEF_URL);
                while ($i <= $num_Seg) {
                    $result.=$this->url_Exp[$i] . '/';
                    $i++;
                }
                $this->remove_Slash($result);
            }
        }
        return $result;
    }

}
