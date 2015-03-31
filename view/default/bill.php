<div id="con">
    <h1><?php echo $this->a_lan['send_bill'] ?></h1>
    <div id='info'>
        <p><b><?php echo $this->a_lan['email'] ?> : </b><span><?php echo $data['user']['us_name'] ?></span></p>
        <p><b><?php echo $this->a_lan['name'] ?> : </b><span><?php echo $data['user']['us_email'] ?></span></p>
    </div>
    <table>
        <tr>
            <th><?php echo $this->a_lan['name_pro'] ?></th>
            <th><?php echo $this->a_lan['time'] ?></th>
            <th><?php echo $this->a_lan['price'] ?></th>
            <th></th>
        </tr>
        <tr>
            <td><div class="tc" style="background:<?php echo $data['type']['at_color'] ?>;color:#Fff"><?php echo $data['type']['at_name'] ?></div></td>
        <form>
            <td><input type="text" /></td>
            <td>
                <?php echo $data['type']['at_price'] ?>
            </td>
            <td>                <input class="But But_1" type="submit" name="bill" value="Buy" /></td>
        </form>
        </tr>
    </table>
    <div id='result'></div>
</div>