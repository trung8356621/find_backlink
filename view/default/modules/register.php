<style type="text/css">
    #Reg_Box{
        border-left: 4px solid #EFEFEF;
    }
</style>
<div id="Content">
    <div id="log_Box">
        <h1><?php echo $this->a_lan['login'] ?></h1>
        <div class="Input">
            <label class="Pic"><img src="<?php echo $this->def_theme_url . 'img/' . $this->a_lan['email'][0]; ?>" /></label>
            <input type="text" name="email" placeholder="<?php echo $this->a_lan['email'][1] ?>" />
        </div>
        <div class="Clear"></div>
        <div class="Input">
            <label class="Pic"><img src="<?php echo $this->def_theme_url . 'img/' . $this->a_lan['pass'][0]; ?>" /></label>
            <input type="password" name="pass" placeholder="<?php echo $this->a_lan['pass'][1] ?>" />
        </div>
        <div class="But_Box">
            <div>
                <label for="remember"><input type="checkbox" id="remember" name="remember" value="on" /><?php echo $this->a_lan['rem'] ?></label><br/>
                <a href="<?php echo DEF_URL . 'forgot'; ?>"><?php echo $this->a_lan['forgot'] ?></a>
            </div>
            <input class="But But_2" type="submit" name="but_log" value="<?php echo $this->a_lan['login'] ?>" />
        </div>
    </div>
    <div id="Reg_Box">
        <h1><?php echo $this->a_lan['register'] ?></h1>
        <div class="Input">
            <label for="R_Email" class="Pic"><img src="<?php echo $this->def_theme_url . 'img/' . $this->a_lan['email'][0]; ?>" /></label>
            <input name="R_Email" type="text" name="email" placeholder="<?php echo $this->a_lan['email'][1] ?>" />
        </div>
        <div class="Input">
            <label for="R_Name" class="Pic"><img src="<?php echo $this->def_theme_url . 'img/' . $this->a_lan['name'][0]; ?>" /></label>
            <input for="R_Name" type="text" name="name" placeholder="<?php echo $this->a_lan['name'][1] ?>" />
        </div>
        <div class="Clear"></div>
        <div class="Input">
            <label class="Pic"><img src="<?php echo $this->def_theme_url . 'img/' . $this->a_lan['pass'][0]; ?>" /></label>
            <input class="Pass" style="border-right: 2px solid <?php echo View::$color['main'] ?>;" type="password" name="pass" placeholder="<?php echo $this->a_lan['pass'][1] ?>" />
        </div>
        <div class="Input">'
            <label class="Pic"><img src="<?php echo $this->def_theme_url . 'img/' . $this->a_lan['pass'][0]; ?>" /></label>
            <input class="Pass" type="password" name="pass2" placeholder="<?php echo $this->a_lan['pass2'] ?>" />
        </div>
        <input class="But But_2" type="submit" name="but_log" value="<?php echo $this->a_lan['register'] ?>" />
    </div>
</div>
