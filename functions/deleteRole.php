<?php include("../config.php") ?>
<?php  

function removeRole(){
global $conn;
$message = "";
if($_SERVER["REQUEST_METHOD"] == "DELETE"){
if(!isset($_SERVER["HTTP_AUTHORIZATION"])){

        $message = "Unauthorized";
        $response = (object) array("status" => "Fail", "message" => $message);
        return $response;
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
    if($role["name"] == "Super-admin"){
        
        $path = explode("/", $_SERVER["REQUEST_URI"]);
        if(!isset($path[2])) {
        $message = "Bad request";
            $response = (object) array("status" => "Fail", "message" => $message );
            return $response; 
        }
        $id = $path[2];
        $sql = "DELETE FROM roles WHERE id='$id'";
        $query = mysqli_query($conn, $sql);
        if($query){
            $message = "Role with id " . $id . " successfully deleted";
            $response = (object) array("status" => "Fail", "message" => $message );
            return $response;  
        }else{
            $message = "Something went wrong";
            $response = (object) array("status" => "Fail", "message" => $message );
            return $response; 
        }
    }
}

            $message = "Bad request, Not the accepted request method";
            $response = (object) array("status" => "Fail", "message" => $message );
            return $response; 

}