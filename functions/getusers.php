<?php include("../config.php") ?>
<?php
function getUsers(){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(!isset($_SERVER["HTTP_AUTHORIZATION"])){
        http_response_code(401);
        $message = "Unauthorized";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
        }
    }
}