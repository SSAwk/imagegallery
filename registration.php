<?php

function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

//Login
$db = mysqli_connect('localhost' , 'root' , '' , 'database') or die("Could not connect to database" ) ; //Connect to database
$username = $_POST['username'] ; //Takes username input as variable
$password = $_POST['password'] ; //Takes password input as variable

if(isset($_POST['login']) )
 {//Number of error checks to ensure login functions correctly
    $errors = array() ;

    if( empty($username))
    {
        array_push($errors , "Username is required" ) ;
    }
    if( empty($password))
    {
        array_push($errors , "Password is required" ) ;
    }
    if( count($errors) == 0 )
    {
        $password = md5($password) ;
        $query = "Select * from appuser where login = '$username' AND pwd = '$password'" ;
        $results = mysqli_query($db, $query) ;
        echo '<script>console';
        //Results of login information submission
        if( mysqli_num_rows($results) ) 
        {
            $_SESSION['username'] = $username ;
            $_SESSION['success'] = "Logged in Successfully" ;
            echo "You are now logged in." ;
            header('Location: index.php');
        }
        else
        {
            array_push($errors , "Wrong Username/Password combination. Please try again." ) ;
        }
    }
mysqli_close($db); //Closing connection to database after usage is finished
 }

 else

 {
session_start(); //Starts session to carry over information in session


$errors = array();

//Register
$username = $_POST['username'];
$confirmpassword = $_POST['confirmpassword'];

//Basic form validation
if( empty($username) )
{
    array_push($errors , "Username is required");
} 

if( empty($password))
{
    array_push($errors , "Password is required");
} 

if( $password != $confirmpassword )
{
    array_push($errors , "Password do not match");
}

//Checks database for existing user with same username
$user_check_query = "Select * from appuser where login = '$username' LIMIT 1";

$results = mysqli_query( $db , $user_check_query ); //Places query request into results variable
$user = mysqli_fetch_assoc($results); //Places results of query $result into user variable


if($user)
{
    if($user["username"] === $username)
    {
        array_push($errors , "This username is already registered" );
    }
}

//Register user if no errors
if( count($errors) == 0 )
{
    $password = md5($password); //Password encryption

    $query = "Insert into appuser (login , pwd) values ( '$username' , '$password' )";  //Store query command
    
    mysqli_query($db , $query); //Run query command to insert account information into database

    $_SESSION['UserName'] = $username;
    $_SESSION['success'] = "You are now signed up";

    header( 'location: login.php' ); //Direct to login page after registration is finished
    mysqli_close($db); //Close connection to database
}
 
}

?>
