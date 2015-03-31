<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title><?php echo $this->a_head['web_title'] ?></title>
        <meta name="discription" content="<?php echo $this->a_head['web_discription'] ?>" />
        <meta name="keyword" content="<?php echo $this->a_head['web_keyword'] ?>" />
        <meta name="robots" content="<?php echo $this->a_head['robot'] ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo DEF_URL . 'view/default/css/S_Reset.css' ?>" />
        <?php $this->prt_listcss(); ?>
        <?php $this->prt_listjs(); ?>
        <style type="text/css">
            body{
                font-size:15px;
            }
            .bl_info div a{
                color: <?php echo View::$color['main'] ?>;
            }
            .bl_info div.bli_head,#bl_info_head{
                background: <?php echo View::$color['main'] ?>;
            }
            .bl_info div.bli_head a{
                color:#fff;
            }
            .But_1{
                background: <?php echo View::$color['but_1'] ?>;
            }
            .Err{
                color:<?php echo View::$color['but_1'] ?>;
            }
            .But_2{
                background: <?php echo View::$color['but_2'] ?>;
            }
            .But{
                padding: 8px;
                color:#fff;
                border: none;
                font-weight: bold;
                display: inline-block;
                cursor: pointer;
                font-size: 1.1em;
            }
            table tr.c{
                background: <?php echo View::$color['sub'] ?>;
            }
            table tr.l{
                background: #fff;
            }
            #Foot{
                background: <?php echo View::$color['sub'] ?>;
            }
        </style>
        <script type="text/javascript">
            url='<?php echo DEF_URL ?>';
        </script>
    </head>

