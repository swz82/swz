<?php
require_once 'Counter.php';
require_once 'CountListener.php';

use \Main\ObserverPattern\Counter;
use \Main\ObserverPattern\CountListener;

// Set up variables for the database
$dbServer = "localhost";
$dbName = "users";
$dbUName = "root";
$dbPassword = "";

// Open a connection to the database server
$mysqli = new mysqli($dbServer, $dbUName, $dbPassword, $dbName);

/*
* This is the "official" OO way to do it,
* BUT $connect_error was broken until PHP 5.2.9 and 5.3.0.
*/
if ($mysqli->connect_error) 
{
die("Connect Error (" . $mysqli->connect_errno . ")" 
. $mysqli->connect_error);
}

$func = $_POST['function'];

if($func!="login"&&$func!="sign")
{
	$cookie = $_COOKIE["name"];
	$counter = new Counter(5);
	$observer = new CountListener($cookie);
	$counter->Attach($observer);
}


switch($func)
{
	case "login":
	$enteredName = $_POST['username'];
	$enteredPwd = $_POST['password'];
	if($enteredName=='')
	{
		echo "<script>alert('Please enter username!');location='login.html'</script>";
	}
	else if($enteredPwd=='')
	{
		echo "<script>alert('Please enter password!');location='login.html'</script>";
	}
	$name = PreventSqlInjection($mysqli, $_POST["username"]);
	$pwd = PreventSqlInjection($mysqli, $_POST["password"]);
	$counter = new Counter(5);
	$observer = new CountListener($enteredName);
	$counter->Attach($observer);
	$counter->Login($name, $pwd,  $mysqli);
	break;
	
	case "sign":
	$enteredName = $_POST["username"];   
	$enteredPwd = $_POST["password"];
	$enteredInfo = $_POST["information"];
	if($enteredName=='')
	{
		echo "<script>alert('Please enter username!');location='sign.html'</script>";
	}
	else if($enteredPwd=='')
	{
		echo "<script>alert('Please enter password!');location='sign.html'</script>";
	}
	$name = PreventSqlInjection($mysqli, $_POST["username"]);
	$pwd = PreventSqlInjection($mysqli, $_POST["password"]);
	$info = PreventSqlInjection($mysqli, $_POST["information"]);
	$counter = new Counter(5);
	$observer = new CountListener($name);
	$counter->Attach($observer);
	$counter->Sign($name, $pwd, $info, $mysqli);
	$counter->Login($name, $pwd,  $mysqli);
	break;
	
    case "search":
    $search = PreventSqlInjection($mysqli, $_POST['search']);
    $counter->Search($cookie, $search, $mysqli);
    break;
	
    case "create":
    $name = PreventSqlInjection($mysqli, $_POST["username"]);
	$pwd = PreventSqlInjection($mysqli, $_POST["password"]);
	$info = PreventSqlInjection($mysqli, $_POST["information"]);
    $id = PreventSqlInjection($mysqli, $_POST["id"]);
    $counter->Create($name, $pwd, $info, $id, "create a user: {$name}", 1, $mysqli);
    break;
	
	case "delete":
    $key = PreventSqlInjection($mysqli, $_POST['username']);
    $counter->Delete($key, $mysqli);
    break;
	
	case "update":
    $name = PreventSqlInjection($mysqli, $_POST["username"]);
	$pwd = PreventSqlInjection($mysqli, $_POST["password"]);
	$info = PreventSqlInjection($mysqli, $_POST["information"]);
    $id = PreventSqlInjection($mysqli, $_POST["id"]);
	$new = PreventSqlInjection($mysqli, $_POST["newname"]);
    $counter->Update($cookie, $name, $pwd, $info, $id, $new, $mysqli);
    break;
	
	case "profile":
	$pwd = PreventSqlInjection($mysqli, $_POST["password"]);
	$info = PreventSqlInjection($mysqli, $_POST["information"]);
    $counter->Profile($cookie, $pwd, $info, $mysqli);
    break;
	
	case "logout":
    $counter->Logout($mysqli);
    break;
	
	case "searchG":
    $search = PreventSqlInjection($mysqli, $_POST['searchG']);
    $counter->SearchG($search, $mysqli);
    break;
	
	case "searchS":
    $search = PreventSqlInjection($mysqli, $_POST['searchS']);
    $counter->SearchS($cookie, $search, $mysqli);
    break;
	
	case "createG":
    $name = PreventSqlInjection($mysqli, $_POST["name"]);
	$math = PreventSqlInjection($mysqli, $_POST["math"]);
	$art = PreventSqlInjection($mysqli, $_POST["art"]);
    $science = PreventSqlInjection($mysqli, $_POST["science"]);
	$feedback = PreventSqlInjection($mysqli, $_POST["feedback"]);
    $counter->CreateG($name, $math, $art, $science, $feedback, "submit a grade for {$name}", $mysqli);
    break;
	
	case "deleteG":
    $key = PreventSqlInjection($mysqli, $_POST['name']);
    $counter->DeleteG($key, $mysqli);
    break;
	
	case "updateG":
    $name = PreventSqlInjection($mysqli, $_POST["name"]);
	$math = PreventSqlInjection($mysqli, $_POST["math"]);
	$art = PreventSqlInjection($mysqli, $_POST["art"]);
    $science = PreventSqlInjection($mysqli, $_POST["science"]);
	$feedback = PreventSqlInjection($mysqli, $_POST["feedback"]);
    $counter->UpdateG($name, $math, $art, $science, $feedback, $mysqli);
    break;
}

// Prevent hacker typing something nasty into a Form
// and performing an SQL injection attack.
function PreventSqlInjection($mysqli, $text)
{
    // Magic Quotes can be turned on in php.ini to protect
    // beginner PHP coders from SQL injection attacks.
    // e.g. "It's good" after applying magic quotes
    // becomes "It\'s good".
    // Not all GET and POST data needs to be escaped,
    // so experienced coders turn this off.
    // When writing to MySQL, remove 
    if (get_magic_quotes_gpc())		// Is magic quotes on? 
    {
        $text = stripslashes($text);	// Remove the slashes added
    }
	
    // If using MySQL, escape special characters	
    return $mysqli->real_escape_string($text);
}

$closed = $mysqli->close();
if($closed == false)
{
    die("Connection closed failed " . mysqli_error());
}

?>