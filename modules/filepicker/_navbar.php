<?php

function tab_active($page)
{
    echo substr($_SERVER['SCRIPT_NAME'], - strlen($page)) === $page ? ' class="active"' : '';
}

function demo_nav()
{
    ?>
    <ul class="nav nav-tabs demo-nav-tabs">
        <li<?php tab_active('index.php'); ?>><a href="index.php">Basic UI</a></li>
        <li<?php tab_active('plus.php'); ?>><a href="plus.php">Basic Plus UI</a></li>
        <li<?php tab_active('basic.php'); ?>><a href="basic.php">Basic</a></li>
        <li<?php tab_active('avatar.php'); ?>><a href="avatar.php">Avatar</a></li>
        <li<?php tab_active('zero.php'); ?>><a href="zero.php">Zero UI</a></li>
    </ul>
    <?php
}
?>

<div class="navbar navbar-default">
    <div class="container">
        <div class="col-md-9 col-md-offset-2">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="index.php" class="navbar-brand">Filepicker</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="http://docs.hazzardweb.com/filepicker" target="_blank">
                            <i class="fa fa-file-text-o"></i> Documentation
                        </a>
                    </li>
                    <!-- <li>
                        <a href="http://codecanyon.net/item/filepicker-multi-file-uploader/11557024">
                            <i class="fa fa-shopping-cart"></i> Download
                        </a>
                    </li> -->
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="https://hazzardweb.com" target="_blank">HazzardWeb</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
