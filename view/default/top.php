<?php $us = isset($_SESSION['us']) ? true : false ?>
<script type="text/javascript">
    $(document).ready(function () {
        login_box();
        S_Process();
        login();
        $('.blueberry').blueberry();
        respond_res();
    });
</script>
<style type="text/css">
    #S_Bar{
        background: <?php echo View::$color['sub'] ?>;
    }
    #Top,#S_Choose,#info b,#con table th{
        background: <?php echo View::$color['main'] ?>;
    }
    .link{
        background: <?php echo View::$color['main'] ?>;
    }
    #con h1{
        background: <?php echo View::$color['but_1'] ?>;
    }
    #Log_Box{
        border-top:4px solid <?php echo View::$color['but_2'] ?>;
        background: <?php echo View::$color['sub'] ?>;
    }
    #Log_Box div.Input div{
        border-right:2px solid <?php echo View::$color['main'] ?>;
    }
    .Input{
        border:2px solid <?php echo View::$color['main'] ?>;
        background: <?php echo View::$color['main'] ?>;
    }
    #user_menu li,#user_menu li:hover{
        background: <?php echo View::$color['main'] ?>;
    }
</style>
<body>
    <div id="Dad">
        <div id="Top">
            <div id="User_Bar">
                <a href="<?php echo $this->main_url . (!$us ? 'register' : 'pricing') ?>" ><div class="But But_1"><?php echo $this->a_lan[(!$us ? 'register' : 'update')] ?></div></a>
                <?php echo $us ? '<a style="font-weight:bold;font-size:1.1em;color:#fff">' . $_SESSION['us']['us_name'] . '</a>' : ''; ?>
                <?php if (!$us) { ?>
                    <div id="Log" class="But But_2"><?php echo $this->a_lan['login'] ?></div>
                <?php } else { ?>
                    <a href="<?php echo DEF_URL . 'logout' ?>"><div class="But But_2"><?php echo $this->a_lan['logout'] ?></div></a>
                <?php } ?>
            </div>
        </div>
        <?php if (!isset($_SESSION['us'])) { ?>
            <form action="<?php echo DEF_URL . 'login-ac' ?>" id="Log_Box">
                <div class="Input">
                    <label for="Email" class="Pic"><img src="<?php echo $this->def_theme_url . 'img/' . $this->a_lan['email'][0]; ?>" /></label>
                    <input id="Email" type="text" name="email" placeholder="<?php echo $this->a_lan['email'][1] ?>" />
                </div>
                <div class="Clear"></div>
                <div class="Input">
                    <label for="Pass" class="Pic"><img src="<?php echo $this->def_theme_url . 'img/' . $this->a_lan['pass'][0]; ?>" /></label>
                    <input id="Pass" type="password" name="pass" placeholder="<?php echo $this->a_lan['pass'][1] ?>" />
                </div>
                <div class="But_Box">
                    <div>
                        <label for="remember"><input id="Rem" type="checkbox" id="remember" name="remember" value="on" /><?php echo $this->a_lan['rem'] ?></label><br/>
                        <a href="<?php echo DEF_URL . 'forgot'; ?>"><?php echo $this->a_lan['forgot'] ?></a>
                    </div>
                    <input class="But But_2" type="submit" name="but_log" value="<?php echo $this->a_lan['login'] ?>" />
            </form>
        <?php } ?>
    </div>
</div>

