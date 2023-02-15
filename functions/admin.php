<?php include("../config.php") ?>
<?php
function getAdminBlogs(){
    global $conn;
  if(!isset($_SERVER["HTTP_AUTHORIZATION"])){
        return "Unauthorized";
    };
    $get_id = $_SERVER["HTTP_AUTHORIZATION"];
    $id = explode(" ", $get_id);
    $id = $id[1];
    $sql = "SELECT * FROM  roles WHERE id='$id' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $role = mysqli_fetch_assoc($result);
    if(!$role){
        http_response_code(401);
        return "Unauthorized User";
        
    }
    if($role["name"] == "Super-admin" || $role["name"] == "Editor admin"){
        $sql = "SELECT * FROM blogs";
        $result = mysqli_query($conn, $sql);
        $blogs = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if(count($blogs) < 1){
            http_response_code(200);
            return "No published blogs yet";
        }
        http_response_code(200);
        return json_encode($blogs);
    }
}