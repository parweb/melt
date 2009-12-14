<?php
/**
* vcms.Info.php
* @desc This is the standard nanoMVC information page.
* @desc It is used as a wrapper when displaying user information to make
* @desc the interface more user friendly and stylish, even when falling back from errors.
*/

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>nanoMVC Information Page</title>
    <style type="text/css">
        body {
            margin: 0;
            font-size: 16px;
            font-family: verdana;
        }
        .content {
            padding: 17px 10px 10px 10px;
        }
        .topic {
            padding: 0 10px;
            font: Bold 24px verdana;
            background-color: white;
        }
        .timefoot {
            border-style: solid;
            border-width: 1px 0 0 0;
            margin-top: 10px;
            font-size: 10px;
            padding: 3px 5px;
        }
    </style>
</head>
<body>

    <div class="content">
        <span class="topic">
            <?php echo $topic; ?>
        </span>
        <div class="logo"></div>
        <?php echo $body; ?>
        <div class="timefoot"><?php echo __('This page was generated by nanoMVC: %s', date("r")); ?></div>
    </div>
</body>
</html>