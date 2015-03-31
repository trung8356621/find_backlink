<?php

class Ad_Con_Ajax {

    function __construct() {
    }
    
    function loi(){
        echo 'ss';
    }
    
    public function delete() {
        $url = new Url(MAIN_AD_SEG);
        ad_controller::get_model('ad_table');
        $at = new ad_table();
        $test = $at->del_ever($url->segment(2), $url->segment(3), $url->segment(4));
        if ($test) {
            echo 'ok';
        } else {
            echo 'false';
        }
    }

}
