<div class="blueberry">
    <ul class="slides">
    <?php
    foreach ($data['img'] as $value) {
        echo '<li><img  alt="' . $this->a_lan['pack_tit'] . '" src="' . DEF_URL . 'public/banner/' . $value . '" width="1125" height="365"/></li>';
    }
    ?>
    </ul>
</div>