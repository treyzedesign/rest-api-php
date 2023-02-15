<?php include("../config.php") ?>
<?php 

$paths = explode("/", $_SERVER['REQUEST_URI']);
function getAllBlogs(){
    global $conn;
      $blog_sql = "SELECT * FROM blogs WHERE published=1";
        $blog_result = mysqli_query($conn, $blog_sql);
        $blogs = mysqli_fetch_all($blog_result, MYSQLI_ASSOC);
        if(count($blogs) < 1){
            http_response_code(404);
        $message =  "No blogs yet";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
        }
        http_response_code(200);
        $response = array("status" => "Success", "data" => $blogs);
        return $response;

}
// mysqli_close($conn);

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
    if($role["name"] == "Super-admin" || $role["name"] == "Editor-admin"){

        $sql = "SELECT * FROM blogs WHERE id='$id'";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);
        if(!$query){
            $message =  "Something went wrong";
            $response = array("status" => "Fail", "message" => $message);
            return $response;
        }
        if(mysqli_num_rows($query) < 1){
            $message =   "No blog with matching id";
            $response = array("status" => "Fail", "message" => $message);
            return $response;
        }
            $response = array("status" => "Success", "message" => $result);
            return $response;


    }else{

    
    $sql = "SELECT * FROM blogs WHERE id='$query_id' AND published=1 LIMIT 1";

        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);
        if(!$query){
            $message =   "Something went wrong";
            $response = array("status" => "Fail", "message" => $message);
            return $response;
        }
        if(mysqli_num_rows($query) != 1){
            $message =   "No blog with matching id";
            $response = array("status" => "Fail", "message" => $message);
            return $response;
        }
            $response = array("status" => "Success", "message" => $result);
            return $response;
    }
       
}
 $sql = "SELECT * FROM blogs WHERE id='$query_id' AND published=1 LIMIT 1";

        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);
        if(!$query){
            $message =   "Something went wrong";
            $response = array("status" => "Fail", "message" => $message);
            return $response;
        }
        if(mysqli_num_rows($query) < 1){
            $message =   "No blog with matching id";
            $response = array("status" => "Fail", "message" => $message);
            return $response;
        }
            $response = array("status" => "Success", "message" => $result);
            return $response;
    }
    return "Couldn't find the resource you are looking for";
}