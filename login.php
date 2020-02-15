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

// You can use mysqli_select($connection, $dbname) to connect to another database if you need to.

$name = $_POST['username'];
$pwd = $_POST['password'];

$counter = new Counter(5);
$observer = new CountListener($name);
$counter->Attach($observer);

if($name=='')
{
    echo "<script>alert('Please enter username!');location='login.html'</script>";
}
else if($pwd=='')
{
    echo "<script>alert('Please enter password!');location='login.html'</script>";
}

$counter->Login(PreventSqlInjection($mysqli, $name), PreventSqlInjection($mysqli, $pwd), $mysqli);
setcookie("name",$name,time()+3600);

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