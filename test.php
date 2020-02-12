<?php
// Set up variables for the database
$dbServer = "mysql:3306";
$dbName = "Users";
$dbUName = "root";
$dbPassword = "docker";

// Open a connection to the database server
$mysqli = new mysqli($dbServer, $dbUName, $dbPassword, $dbName);


// Returns connection resource if OK or false if error
// You don't have to specify $dbName, in which case you
// will need to call mysqli_select($dbName) instead.
$connection = mysqli_connect($dbServer, $dbUName, $dbPassword, $dbName);       
if($connection == false)
{
    die("Unable to open connection to database" . mysqli_error());
}

// You can use mysqli_select($connection, $dbname) to connect to another database if you need to.

$name = $_POST['username'];
$pwd = $_POST['password'];

$enteredName = "xy";	// Pretend the user entered this

// Execute SQL query, for SELECT returns resource 
// on true or false if error
$records = $mysqli->query("SELECT * FROM Students WHERE username LIKE '" . PreventSqlInjection($mysqli, $enteredName) . "'");
if($records == false)
{
    die("Query contains error");
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


$closed = mysqli_close($connection);
if($closed == false)
{
    die("Connection closed failed " . mysqli_error());
}
?>