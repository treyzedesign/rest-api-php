<?php 

$HOST = 'localhost';
$USER = 'root';
$PASSWORD = 'Kanayo10.10';
$DB = 'cyclobold_blog';
$conn = mysqli_connect($HOST, $USER, $PASSWORD, $DB);
if(!$conn){
    
     die('Failed to connect to database' . mysqli_connect_error());
}

$paths = explode("/", $_SERVER['REQUEST_URI']);
function getAllBlogs(){
    global $conn;
      $blog_sql = "SELECT * FROM blogs WHERE published=1";
        $blog_result = mysqli_query($conn, $blog_sql);
        $blogs = mysqli_fetch_all($blog_result, MYSQLI_ASSOC);
        if(count($blogs) < 1){
            return "No blogs yet";
        }

    return json_encode($blogs);    
}

function getSingleBlog() {
    global $conn;
    $paths = explode("/", $_SERVER['REQUEST_URI']);
    if(isset($paths[3])){
    $query_id = $paths[3];

    if(isset($_SERVER["HTTP_AUTHORIZATION"])){   
    $get_id = $_SERVER["HTTP_AUTHORIZATION"];
    $id = explode(" ", $get_id);
    $id = $id[1];
    $sql = "SELECT * FROM  roles WHERE id='$query_id' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $role = mysqli_fetch_assoc($result);
    if($role["description"] == "Super-admin" || $role["description"] == "Editor admin"){

        $sql = "SELECT * FROM blogs WHERE id='$id'";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);
        if(!$result){
            return "No blog with matching id";
        }
        if(count($result) < 1){
            return "No blog with matching id";
        }
        return json_encode($result);


    }else{

    
    $sql = "SELECT * FROM blogs WHERE id='$query_id' AND published=1 LIMIT 1";

        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);
        if(!$result){
            return "No blog with matching id";
        }
        if(count($result) < 1){
            return "No blog with matching id";
        }
        return json_encode($result);
    }
       
}
 $sql = "SELECT * FROM blogs WHERE id='$query_id' AND published=1 LIMIT 1";

        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);
        if(!$result){
            return "Unauthorized to view this blog";
        }
        if(count($result) < 1){
            return "No blog with matching id";
        }
        return json_encode($result);
    }
    return "Couldn't find the resource you are looking for";
}