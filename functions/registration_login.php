<?php 


$HOST = 'localhost';
$USER = 'root';
$PASSWORD = 'Kanayo10.10';
$DB = 'cyclobold_blog';

$conn = mysqli_connect($HOST, $USER, $PASSWORD, $DB);
function registerUser (){
    global $conn;
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $sql = "SELECT * FROM users";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
        if(count($result) < 1){ 
             $data = file_get_contents("php://input");
                $data = json_decode($data);
                $email = $data->email;
                $password = $data->password;
                $cpassword = $data->cpassword;
                $lname = $data->lname;
                $fname = $data->fname;
                if(!$email || !$cpassword || !$password || !$lname || !$fname){
                    return "All fields must be provided";
                }
                $email = esc($data->email);
                $password = esc($data->password);
                $cpassword = esc($data->cpassword);
                $lname = esc($data->lname);
                $fname = esc($data->fname);
                $role = "Super-admin";
                if($password != $cpassword){
                    return "Password mismatch";
                }

                $password = md5($password);
                $sql = "INSERT INTO users (`email`, `password`, `role`, `lname`, `fname`) VALUES ('$email','$password', '$role', '$lname', '$fname')";
            $query = mysqli_query($conn, $sql);
            if($query){
                $user_id = mysqli_insert_id($conn);
                $sql = "SELECT * FROM users WHERE id='$user_id' LIMIT 1";
                $getUser = mysqli_query($conn, $sql); 
                $result = mysqli_fetch_assoc($getUser);
               
                return "User Super admin user created";
            }else{
                return "Something went wrong try again";
            }
        }else{
            $data = file_get_contents("php://input");
            $data = json_decode($data);
             $password = $data->password;
            $cpassword = $data->cpassword;
            $email = $data->email;
            $lname = $data->lname;
            $fname = $data->fname;

             if(!$email || !$cpassword || !$password || !$lname || !$fname){
                return "All fields must be provided";
            }

            $password = esc($data->password);
            $cpassword = esc($data->cpassword);
            $email = esc($data->email);
            $lname = esc($data->lname);
            $fname = esc($data->fname);
            $sql = "SELECT * FROM users WHERE email='$email'";
            $query = mysqli_query($conn, $sql);
            $result = mysqli_fetch_assoc($query);
            if(count($$result) > 0){
                return "User with this email already exists";
            }
            $role = "user";
            if($password != $cpassword){
                return "Password mismatch";
            }
           
            $password = md5($password);
            $sql = "INSERT INTO `users` (`email`, `password`, `role`, `lname`, `fname`) VALUES ('$email', '$password', '$role', '$lname', '$fname')";
            $query = mysqli_query($conn, $sql);
            if($query){
                $user_id = mysqli_insert_id($conn);
                $sql = "SELECT * FROM users WHERE id='$user_id' LIMIT 1";
                $getUser = mysqli_query($conn, $sql); 
                $result = mysqli_fetch_assoc($getUser);
                return "User register successfully";
                
            } 

     }
    }
    mysqli_close($conn);
    }


function loginUser () {
    global $conn;
    if($_SERVER["REQUEST_METHOD"]  == "POST"){
        $data = file_get_contents("php://input");
        $data = json_decode($data);
         $email = $data->email;
         $password = $data->password;
         if(!$email || !$password){
             return "All fields must be provided";
         }
        $email = esc($data->email);
        $password = esc($data->password);
        $sql = "SELECT * FROM `users` WHERE `email`='$email' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($result);
    
        if(mysqli_num_rows($result) == 1){
            if($user["password"] != md5($password)){
                return "Invalid Credentials";
            }
                $_SESSION["USER_ID"] = $user["id"];
                $_SESSION["USER_ROLE"] = $user["role"];
            return $user["email"];
        }
        return "Something went wrong, try again";
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