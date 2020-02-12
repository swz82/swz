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

// Try another query
$enteredName = "swz";	// Pretend the user entered this

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

// Fetch an array representing the result row.
while($record = mysqli_fetch_array($records))
{
    echo "row: name=" . $record["username"] 
        . " pwd " . $record["password"] 
        . " info " . $record["information"] . "<br/>";	
}

$closed = $mysqli->close();
if($closed == false)
{
	die("Connection closed failed " . mysqli_error());
}

// Perform an INSERT query
$enteredName = $_POST["nameTextBox"];   
$enteredPwd = $_POST["pwdTextBox"];
$enteredInfo = $_POST["infoTextBox"];
$enteredId = $_POST["idTextBox"];

$mysqli = new mysqli($dbServer, $dbUName, $dbPassword, $dbName);
if ($mysqli->connect_error) 
{
    die("Connect Error (" . $mysqli->connect_errno . ")" 
                          . $mysqli->connect_error);
}

//CREATE
$result = $mysqli->query(
         "INSERT INTO Students(username, password, information, id) VALUES(" 
            . "'" . PreventSqlInjection($mysqli, $enteredName)
            . "','" . PreventSqlInjection($mysqli, $enteredPwd)
		    . "','" . PreventSqlInjection($mysqli, $enteredInfo) 
            . "','" . PreventSqlInjection($mysqli, $enteredId) 
            . "')"
          );
if($result == false)
{
	die("Query contains error");
}
else
{
echo "Create success!<br/>";
}


echo "Rows added = " . $mysqli->affected_rows . "<br/>";
echo "Primary Key (int) ID of inserted row = " . $mysqli->insert_id . "<br/>";

//DELETE
$result = $mysqli->query("DELETE FROM Students WHERE username = ''");
if($result == false)
{
die("Query contains error");
}
else
{
echo "Delete success!<br/>";
}

//UPDATE
$result = $mysqli->query("UPDATE Students SET username = 3 WHERE username = 'swz'");
if($result == false)
{
die("Query contains error");
}
else
{
echo "Update success!<br/>";
}

$closed = $mysqli->close();
if($closed == false)
{
	die("Connection closed failed " . mysqli_error());
}
?>