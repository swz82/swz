<?php
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


// Perform an INSERT query
$enteredName = $_POST["username"];   
$enteredPwd = $_POST["password"];
$enteredInfo = $_POST["information"];
$enteredId = $_POST["id"];
$enteredNew = $_POST["newname"];

//CREATE
$result = $mysqli->query(
         "UPDATE Students SET username = '" . PreventSqlInjection($mysqli, $enteredNew)
            . "', password = '" . PreventSqlInjection($mysqli, $enteredPwd)
            . "', information = '" . PreventSqlInjection($mysqli, $enteredInfo) 
            . "', id = '" . PreventSqlInjection($mysqli, $enteredId)
            . "' WHERE username = '" . PreventSqlInjection($mysqli, $enteredName) 
            . "'"
          );
if($result == false)
{
	die("Query contains error");
}
else
{
echo "<script>alert('Update success!');location='welcomeA.html'</script>";
}

$closed = $mysqli->close();
if($closed == false)
{
	die("Connection closed failed " . mysqli_error());
}
?>