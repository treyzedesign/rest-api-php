<?php

$HOST = 'localhost';
$USER = 'root';
$PASSWORD = 'Kanayo10.10';
$DB = 'cyclobold_blog';
$conn = mysqli_connect($HOST, $USER, $PASSWORD, $DB);

if(!$conn){
    
     die('Failed to connect to database' . mysqli_connect_error());
}

function createBlog() {
    if($_SERVER["REQUEST_METHOD"] = "POST"){
    global $conn;
    if(!isset($_SERVER["HTTP_AUTHORIZATION"])){
        return "Unauthorized";
    };
    $get_id = $_SERVER["HTTP_AUTHORIZATION"];
    $id = explode(" ", $get_id);
    $id = $id[1];
    $user_sql = "SELECT * FROM users WHERE id='$id' LIMIT 1";
    $user_query = mysqli_query($conn, $user_sql);
    if(mysqli_num_rows($user_query) != 1){
        http_response_code(401);
        return "Unauthorized User";
    }
    $data = file_get_contents("php://input");
    $data = json_decode($data);
    $title = $data->title;
    $content = $data->content;
    $category = $data->category_id;
    if(!$title || !$content || !$category){
        http_response_code(400);
        return "All fields are required";
    }
    $title = esc($data->title);
    $content = esc($data->content);
    $category = esc($data->category_id);
    $sql = "INSERT INTO blogs (`title`, `content`, `author`, `category_id`) VALUES ('$title', '$content', '$id', '$category')";
    $query = mysqli_query($conn, $sql);
    if(!$query){
        http_response_code(500);
        return "Something went wrong, try again";
    }
    http_response_code(201);
    return $id;
}else{
    http_response_code(400);
    return "Bad Request";
}
}

function esc(String $value)
	{	
		// bring the global db connect object into function
		global $conn;

		$val = trim($value); // remove empty space sorrounding string
		$val = mysqli_real_escape_string($conn, $value);
        return $val;

    }