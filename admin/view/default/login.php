<style type="text/css">
    body{
        background: <?php echo View::$color['sub'] ?>;
    }
    #Ad_Box{
        background: #fff;
    }
    #AB_Head{
        background:  <?php echo View::$color['main'] ?>;
    }
</style>
<body>
    <div id="Dad">
        <div id="Ad_Box">
            <div id="AB_Head"><?php echo $this->a_head['web_title'] ?></div>
            <form name="log_ad" method="post">
                <input type="text" name="user" class="Text" placeholder="<?php echo $this->a_lan['id'] ?>" />
                <br/><input type="password" name="pass" class="Text" placeholder="<?php echo $this->a_lan['pass'] ?>" />
                <br/><input class="But But_1" value="<?php echo $this->a_lan['login'] ?>" type="submit" name="log_ad" />
                <br/><b class="Err"><?php echo !empty($data['error']) ? $data['error'] : '' ?></b>
            </form>
        </div>
    </div>
</body>
</html>