<?php

echo '<div id="Right">';

echo '<h1 class="tit_tbl">' . $data['mod'] . '</h1>';
new Table($data['tbl_head'], $data['tbl_data'], $data['a_table'], $data['page'], $data['delete'], $data['edit']);

echo '<h1 class="tit_tbl">' . $this->a_lan['user_c'] . '</h1>';
new User_Choose();

echo '</div>';
