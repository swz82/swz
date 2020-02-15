<?php
require_once 'Counter.php';
require_once 'CountListener.php';

use \Main\ObserverPattern\Counter;
use \Main\ObserverPattern\CountListener;

// Set up variables for the database
$dbServer = "mysql:3306";
$dbName = "Users";
$dbUName = "root";
$dbPassword = "docker";

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
$cookie = $_COOKIE["name"];
$name = $_POST['username'];
$counter = new Counter(5);
$observer = new CountListener($cookie);
$counter->Attach($observer);

switch($func)
{
    case "login":
    $name = $_POST['username'];
    $pwd = $_POST['password'];
    setcookie("name",$name,time()+3600);
    $counter->Login(PreventSqlInjection($mysqli, $name), PreventSqlInjection($mysqli, $pwd), $mysqli);
    break;
    case "search":
    $search = $_POST['search'];
    $counter->Search($cookie, PreventSqlInjection($mysqli, $search), $mysqli);
    break;
    case "create":
    $name = $_POST['username'];
    $pwd = $_POST['password'];
    $info = $_POST["information"];
    $id = $_POST["id"];
    $counter->Create(PreventSqlInjection($mysqli, $name), PreventSqlInjection($mysqli, $pwd), 
        PreventSqlInjection($mysqli, $info), PreventSqlInjection($mysqli, $id), $mysqli);
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