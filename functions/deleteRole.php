<?php include("../config.php") ?>
<?php  

function removeRole(){
global $conn;
if($_SERVER["REQUEST_METHOD"] == "DELETE"){
if(!isset($_SERVER["HTTP_AUTHORIZATION"])){
        http_response_code(401);
        $message = "Unauthorized";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
    };
    $get_id = $_SERVER["HTTP_AUTHORIZATION"];
    $id = explode(" ", $get_id);
    $id = $id[1];
    $sql = "SELECT * FROM  roles WHERE id='$id' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $role = mysqli_fetch_assoc($result);
    if(mysqli_num_rows($result)){
        http_response_code(401);
        $message = "Unauthorized";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
        
    }
    if($role["name"] == "Super-admin"){
        
        $path = explode("/", $_SERVER["REQUEST_URI"]);
        if(!isset($path[2])) {
            http_response_code(400);
            $message = "Bad request";
            $response = array("status" => "Fail", "message" => $message );
            return $response; 
        }
        $user_id = $path[2];
        $sql = "DELETE FROM roles WHERE user_id='$user_id'";
        $query = mysqli_query($conn, $sql);
        if($query){
            http_response_code(200);
            $message = "Role with id " . $id . " successfully deleted";
            $response = array("status" => "Fail", "message" => $message );
            return $response;  
        }else{
            http_response_code(500);
            $message = "Something went wrong";
            $response = array("status" => "Fail", "message" => $message );
            return $response; 
        }
    }
}

            $message = "Bad request, Not the accepted request method";
            $response = array("status" => "Fail", "message" => $message );
            return $response; 

}