<div id="S_Bar">
    <a id="Logo" href="<?php echo $this->main_url . 'home' ?>"><img alt="<?php echo $this->a_head['web_name'] ?>" src="<?php echo $this->main_url . 'public/' . $this->a_head['web_img'] ?>" /></a>
    <form id="S_Box" method="GET" action="">
        <input class="S_Text" type="text" name="link" placeholder="<?php echo $this->a_lan['pl_sbox'] ?>" />
        <input type="submit" name="search" class="But But_1" value="<?php echo $this->a_lan['search'] ?>" />
    </form>
    <div id='share_but'>
        <div class="But But_2">Share<img width="10" src="<?php echo DEF_URL . 'view/default/img/arr.png' ?>" /></div>
        <ul id="menu">
            <li><a><img width="20" src="<?php echo DEF_URL . 'view/default/img/face.png' ?>" />facebook</a></li>
            <li><a><img width="20" src="<?php echo DEF_URL . 'view/default/img/google.png' ?>" />twiter</a></li>
            <li><a><img width="20" src="<?php echo DEF_URL . 'view/default/img/twi.png' ?>" /></a></li>
        </ul>
        <a id='contact'><img width="40" src="<?php echo DEF_URL . 'view/default/img/mail.png' ?>" /></a>
    </div>
</div>

