<?php 

$HOST = 'localhost';
$USER = 'root';
$PASSWORD = 'Kanayo10.10';
$DB = 'cyclobold_blog';
$conn = mysqli_connect($HOST, $USER, $PASSWORD, $DB);
if(!$conn){
    
     die('Failed to connect to database' . mysqli_connect_error());
}

function updateBlog(){
    global $conn;

     if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(!isset($_SERVER["HTTP_AUTHORIZATION"])){
        return "Unauthorized";
    };
    $get_id = $_SERVER["HTTP_AUTHORIZATION"];
    $id = explode(" ", $get_id);
    $id = $id[1];
    $user_sql = "SELECT * FROM `roles` WHERE `id`='$id' LIMIT 1";
    $user_query = mysqli_query($conn, $user_sql);
    if(mysqli_num_rows($user_query) != 1){
        http_response_code(401);
        return "Unauthorized User";
    }
    $user_result = mysqli_fetch_assoc($user_query);
    $role = $user_result["description"];
    
    if($role != "Super-admin"){
        return "Not authorized to edit blog";
    }
    $path = explode("/", $_SERVER["REQUEST_URI"]);
    if(!isset($path[3])) {
        return "Bad Request";
    }
    $id = $path[3]; 
    $data = file_get_contents("php://input");
    $data = json_decode($data);

    $title = $data->title;
    $content = $data->content;
    $category = $data->category_id;
    $published = $data->published;
    $author_id = $data->author;

    if(!$title || !$content || !$category || !$author_id || !$published){
        http_response_code(400);
        return "All fields are required";
    }

    $title = esc($data->title);
    $author_id = esc($data->author);
    $content = esc($data->content);
    $category = esc($data->category_id);
    $published = esc($data->published);
    $sql = "SELECT * FROM blogs WHERE id = '$id' LIMIT 1";
    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) != 1){
        return "No blog with matching id";
    }
    $update_sql = "UPDATE blogs SET `title`='$title', `content`='$content', `category_id`='$category', `published`='$published', `author`='$author_id', updated_at=now() WHERE id='$id'";
    $update_query = mysqli_query($conn, $update_sql);
    if(!$update_query){
        return "Something went wrong, try again";
    }
    return "Blog with id" . $id . "successfully updated";
}

function deleteBlog(){
    global $conn;

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(!isset($_SERVER["HTTP_AUTHORIZATION"])){
        return "Unauthorized";
    };
    $get_id = $_SERVER["HTTP_AUTHORIZATION"];
    $id = explode(" ", $get_id);
    $id = $id[1];
    $user_sql = "SELECT * FROM `roles` WHERE `id`='$id' LIMIT 1";
    $user_query = mysqli_query($conn, $user_sql);
    if(mysqli_num_rows($user_query) != 1){
        http_response_code(401);
        return "Unauthorized User";
    }
    $user_result = mysqli_fetch_assoc($user_query);
    $role = $user_result["description"];

    if($role != "Super user"){
        return "Not authorized to edit blog";
    }
    $path = explode("/", $_SERVER["REQUEST_URI"]);
    if(!isset($path[3])) {
        return "Bad Request";
    }
    $id = $path[3];
    $sql = "SELECT * FROM blogs WHERE id = '$id' LIMIT 1";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    if(mysqli_num_rows(json_decode($result)) < 1){
        return "No blog with matching id";
    }
    $delete_sql = "DELETE FROM blogs WHERE id='$id'";
    $result = mysqli_query($conn, $delete_sql);
    if(!$result){
        return "Something went wrong, try again";
    }
    return "Blog with id" . $id . "successfully deleted";
     }
    }
     return "Bad Request";
}

function esc(String $value)
	{	
		// bring the global db connect object into function
		global $conn;

		$val = trim($value); // remove empty space sorrounding string
		$val = mysqli_real_escape_string($conn, $value);
        return $val;

    }