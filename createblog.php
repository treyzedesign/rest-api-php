<?php include("./config.php"); ?>
<?php require_once(ROOT_PATH . "/functions/createBlog.php") ?>
<?php $blog = createBlog(); ?>
<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");


echo json_encode($blog);