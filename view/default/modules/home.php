<?php
foreach ($this->a_lan['pack'] as $key => $value) {
    ?>
    <div class="park_c">
        <div class="img" style="background: <?php echo $value[1] ?>"><?php echo $value[0] ?></div>
        <p><b style="font-weight:bold;color: <?php echo $value[1] ?>"><?php echo $value[2] ?>.</b><?php echo $value[3] ?></p>
    </div>
    <?php
}
?>