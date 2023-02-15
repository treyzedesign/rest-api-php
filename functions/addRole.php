<?php include("../config.php") ?>
<?php  

function addRole(){
global $conn;
$message = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
if(!isset($_SERVER["HTTP_AUTHORIZATION"])){
        http_response_code(401);
        $message = "Unauthorized";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
    };
    $get_id = $_SERVER["HTTP_AUTHORIZATION"];
    $id = explode(" ", $get_id);
    $user_id = $id[1];

    $user_sql = "SELECT * FROM `roles` WHERE `user_id`='$user_id' LIMIT 1";
    $user_query = mysqli_query($conn, $user_sql);
    $user_result = mysqli_fetch_assoc($user_query);
    if(mysqli_num_rows($user_query) != 1){
        http_response_code(401);
        $message = "Unauthorized User";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
    }
    $user_policies = $user_result["policies"];
    $user_policies = json_decode($user_policies);
    $policy_array = [];
    for($i = 0; $i < count($user_policies); $i++){
    $priv_sql = "SELECT * FROM `policies` WHERE id='$user_policies[$i]'";
    $policy_query = mysqli_query($conn, $priv_sql);
    $policy_result = mysqli_fetch_assoc($policy_query);
    array_push($policy_array, $policy_result["privileges"]);
    }
    
    if(!in_array("can-create-admin", $policy_array)){
        $message = "Unauthorized";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
    }

        $data = json_decode(file_get_contents("php://input"));
        $user_id = $data->user_id;
        $policies = $data->policies;
        $name = $data->name;
        if(!$user_id || !$policies || !$name){
            $message = "All fields are required";
            $response = array("status" => "Fail", "message" => $message );
            return $response;
        }
        if(!count($policies)){
            $message = "Policies must be assigned to user";
            $response = array("status" => "Fail", "message" => $message );
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
            $response = array("status" => "Success", "message" => $message );
            return $response;    
        }else{
            $message = "Something went wrong, try again";
            $response = array("status" => "Failed", "message" => $message );
            return $response; 
        }
    
}

            $message = "Bad request, Not the accepted request method";
            $response = array("status" => "Fail", "message" => $message );
            return $response; 

}