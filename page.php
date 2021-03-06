<?php
$name=$_POST['search'];
$id=$_COOKIE["name"];

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

$date = date('Y-m-d h:i:s', time());
$log = $mysqli->query(
    "INSERT INTO Log(name, log, time) VALUES(" 
    . "'" . PreventSqlInjection($mysqli, $id)
    . "', 'search for " . PreventSqlInjection($mysqli, $name)
    . "' , '{$date}' )"
  );
if($log == false)
{
 die("Query contains error");
 
}

$keys = $mysqli->query("SELECT * FROM Students WHERE username LIKE '" . PreventSqlInjection($mysqli, $id) . "'");
if($keys == false)
{
    die("Query contains error" . mysqli_error());
    echo "<script>alert('Query contains error!');location='login.html'</script>";   
}
$key = mysqli_fetch_array($keys);
if($key["id"]=="administrator")
{
    $flag=1;
}
else{
    $flag=0;
}

if($name=="")
{
    if($flag==1){echo "<script>alert('No result!');location='welcomeA.html'</script>";}
    else{echo "<script>alert('No result!');location='welcomeU.html'</script>";}
}

// Execute SQL query, for SELECT returns resource 
// on true or false if error
$records = $mysqli->query("SELECT * FROM Students WHERE 
    username LIKE '%" . PreventSqlInjection($mysqli, $name) . "%'");
if($records == false)
{
    die("Query contains error" . mysqli_error());
    if($flag==1){echo "<script>alert('Query contains error!');location='welcomeA.html'</script>";}
    else{echo "<script>alert('Query contains error!');location='welcomeU.html'</script>";}
}

$record = mysqli_fetch_array($records);
if($record==NULL)
{
    if($flag==1){echo "<script>alert('No result!');location='welcomeA.html'</script>";}
    else{echo "<script>alert('No result!');location='welcomeU.html'</script>";}
}
else
{
    echo " ". $record["id"]
        . " : name=" . $record["username"] 
        . " info: " . $record["information"] . "<br/>";
    
}

while($record = mysqli_fetch_array($records))
{
    echo " ". $record["id"]
        . " : name=" . $record["username"] 
        . " info " . $record["information"] . "<br/>";	
    
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