<?php include("./config.php"); ?>
<?php require_once(ROOT_PATH . "/functions/update_deleteblog.php") ?>
<?php $blog = deleteBlog(); ?>
<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
header("Content-type: application/json");

    echo $blog;
    exit;