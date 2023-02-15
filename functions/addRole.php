<?php include("../config.php") ?>
<?php  

function addRole(){
global $conn;
$message = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
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
    if($role["name"] == "Super-admin" || $role["name"] == "Sub-admin"){
        $data = json_decode(file_get_contents("php://input"));
        $user_id = $data->user_id;
        $policies = $data->policies;
        $name = $data->name;
        if(!$user_id || !$policies || !$name){
            $message = "All fields are required";
            $response = (object) array("status" => "Fail", "message" => $message );
            return $response;
        }
        if(!count($policies)){
            $message = "Policies must be assigned to user";
            $response = (object) array("status" => "Fail", "message" => $message );
            return $response; 
        }
        $policy_id_arrays = [];
        for($x = 0; $x < count($policies); $x++){
            $sql = "SELECT id FROM policies WHERE privileges='$policies[$x]'";
            $policies_query = mysqli_query($conn, $sql);
            $policy_result = mysqli_fetch_assoc($policies_query);
            array_push($policy_id_arrays, $policy_result["id"]);
        }
        $policy_id_arrays = json_encode($policy_id_arrays);
        $create_role_sql = "INSERT INTO roles (`user_id`, `policies`, `name`) VALUES ('$user_id', '$policy_id_arrays', '$name')";
        $role_query = mysqli_query($conn, $create_role_sql);
        if($role_query){

            $message = "Role successfully created";
            $response = (object) array("status" => "Success", "message" => $message );
            return $response;    
        }else{
            $message = "Something went wrong, try again";
            $response = (object) array("status" => "Failed", "message" => $message );
            return $response; 
        }
    }
}

            $message = "Bad request, Not the accepted request method";
            $response = (object) array("status" => "Fail", "message" => $message );
            return $response; 

}