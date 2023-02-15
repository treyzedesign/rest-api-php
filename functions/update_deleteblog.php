<?php include("../config.php") ?>
<?php 
function updateBlog(){
    global $conn;
     if($_SERVER["REQUEST_METHOD"] == "PUT" || $_SERVER["REQUEST_METHOD"] == "PATCH"){
        if(!isset($_SERVER["HTTP_AUTHORIZATION"])){
        return "Unauthorized";
    };
    $get_id = $_SERVER["HTTP_AUTHORIZATION"];
    $id = explode(" ", $get_id);
    $id = $id[1];
    $user_sql = "SELECT * FROM `roles` WHERE `user_id`='$id' LIMIT 1";
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
    
    if(!in_array("can-update-blog", $policy_array)){
        $message = "Unauthorized";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
    }

    
    $path = explode("/", $_SERVER["REQUEST_URI"]);
    if(!isset($path[3])) {
         http_response_code(400);
        $message = "Bad request";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
    }
    $data = file_get_contents("php://input");
    $data = json_decode($data);
    
    $id = $path[3]; 
    $title = $data->title;
    $content = $data->content;
    $category = $data->category_id;
    $published = $data->published;
    $author_id = $data->author;

    if(!$title || !$content || !$category || !$author_id || !$published){
        http_response_code(400);
        $message = "All fields are required";
        $response = array("status" => "Fail", "message" => $message);
        return json_encode($response);
    }

    $title = esc($data->title);
    $author_id = esc($data->author);
    $content = esc($data->content);
    $category = esc($data->category_id);
    $published = esc($data->published);
    $sql = "SELECT * FROM blogs WHERE id = '$id' LIMIT 1";
    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) != 1){
        http_response_code(404);
        $message = "No blog with matching id";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
    }
    $update_sql = "UPDATE blogs SET `title`='$title', `content`='$content', `category_id`='$category', `published`='$published', `author`='$author_id', updated_at=now() WHERE id='$id'";
    $update_query = mysqli_query($conn, $update_sql);
    if(!$update_query){
        http_response_code(500);
         $message = "Something went wrong, try again";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
    }
        http_response_code(201);
        $message =  "Blog with id " . $id . " successfully updated";
        $response = array("status" => "Success", "message" => $message);
        return $response;
}

}
function deleteBlog(){
    global $conn;
    if($_SERVER["REQUEST_METHOD"] == "DELETE"){
        if(!isset($_SERVER["HTTP_AUTHORIZATION"])){
        $message = "Unauthorized User";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
    };
    $get_id = $_SERVER["HTTP_AUTHORIZATION"];
    $id = explode(" ", $get_id);
    $user_id = $id[1];
    $user_sql = "SELECT * FROM `roles` WHERE `user_id`='$user_id' LIMIT 1";
    $user_query = mysqli_query($conn, $user_sql);
    if(mysqli_num_rows($user_query) != 1){
        http_response_code(401);
        $message = "Unauthorized User";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
    }

    $user_result = mysqli_fetch_assoc($user_query);
    $user_policies = $user_result["policies"];
    $user_policies = json_decode($user_policies);
    $policy_array = [];
    for($i = 0; $i <= count($user_policies); $i++){
    $priv_sql = "SELECT * FROM `policies` WHERE id='$user_policies[$i]'";
    $policy_query = mysqli_query($conn, $priv_sql);
    $policy_result = mysqli_fetch_assoc($policy_query);
    array_push($policy_array, $policy_result["privileges"]);
    }
    if(!in_array("can-delete-blog", $policy_array)){
        http_response_code(401);
        $message = "Unauthorized";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
    }

    $path = explode("/", $_SERVER["REQUEST_URI"]);
    if(!isset($path[3])) {
        http_response_code(400);
        $message = "Bad request";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
    }
    $id = $path[3];
    $sql = "SELECT * FROM blogs WHERE id = '$id' LIMIT 1";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    if(mysqli_num_rows($query) < 1){
        http_response_code(404);
        $message = "No blog with matching id";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
    }
    $delete_sql = "DELETE FROM blogs WHERE id='$id'";
    $result = mysqli_query($conn, $delete_sql);
    if(!$result){
        http_response_code(500);
        $message = "Something went wrong, try again";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
    }
    http_response_code(200);
        $message =  "Blog with id " . $id . " successfully deleted";
        $response = array("status" => "Fail", "message" => $message);
        return $response;
     }
     http_response_code(400);
     $message =  "Bad request";
     $response = array("status" => "Fail", "message" => $message);
     return $response;
    }



function esc(String $value)
	{	
		// bring the global db connect object into function
		global $conn;

		$val = trim($value); // remove empty space sorrounding string
		$val = mysqli_real_escape_string($conn, $value);
        return $val;

    }