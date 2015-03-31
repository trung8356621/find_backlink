<style type="text/css">
    #Left,.Ad_Table th{
        background: #363636;
    }
    #Ad_Menu .active,.tit_tbl{
        background: <?php echo View::$color['main'] ?>;
    }
</style>
<script src="<?php echo DEF_URL . 'view/default/js/jquery-1.8.1.js'; ?>"></script>
<script src="<?php echo DEF_URL . 'view/default/js/func.js'; ?>"></script>
<script>
    $(document).ready(function () {
        $('#Ad_Menu .iterm').hover(function () {
            $(this).find('.iterm_info').show(200);
        }, function () {
            $(this).find('.iterm_info').hide(200);
        });
        $('#Ad_Menu').height($(window).height());
    });
</script>
<div id="Dad">
    <div id="Left">
        <h1><?php echo $this->a_head['web_name'] ?></h1>
        <div id="log_info">
            <div class="But"><b><?php echo $this->a_lan['welcome'] . 'admin ' . $_SESSION['ad']; ?></b></div>
            <a href="<?php echo DEF_URL ?>" ><div class="But But_2"><?php echo $this->a_lan['quit']; ?></div></a>
            <div class="Clear"></div>
        </div>
        <div id="Ad_Menu">
            <?php
            foreach ($this->a_lan['menuAd'] as $key => $value) {
                ?>
                <div class="iterm<?php echo ($data['act'] == $key ? ' active' : '') ?>">
                    <span><?php echo $value[0] ?></span>
                    <div class="iterm_info">
                        <?php if ($key != 'wi') { ?><a href="<?php echo DEF_AD_URL . $value[1] ?>"><div class="ii_info">Danh sách <?php $value[0] ?></div></a><?php } ?>
                        <a href="<?php echo DEF_AD_URL . 'tbl-add/' . $value[2] . '/' . $value[1] ?>"><div class="ii_info">Thêm <?php $value[0] ?></div></a>
                    </div>
                </div>

                <?php
            }
            ?>
        </div>
    </div>
