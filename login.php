<?php
// Set up variables for the database
$dbServer = "mysql:3306";
$dbName = "Users";
$dbUName = "root";
$dbPassword = "docker";

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

if($name=='')
{
    echo "<script>alert('Please enter username!');location='login.html'</script>";
}
else if($pwd=='')
{
    echo "<script>alert('Please enter password!');location='login.html'</script>";
}

//echo "<script>alert('{$name} {$pwd}');location='login.html'</script>";

// Execute SQL query, for SELECT returns resource 
// on true or false if error
$records = $mysqli->query("SELECT * FROM Students WHERE username = '" . PreventSqlInjection($mysqli, $name) . "'");
if($records == false)
{
    die("Query contains error");
}

$records = $mysqli->query("SELECT * FROM Students WHERE password = '" . PreventSqlInjection($mysqli, $pwd) . "'");
if($records == false)
{
    die("Query contains error");
}

/*$records = $mysqli->query("SELECT * FROM Students WHERE username = '" . PreventSqlInjection($mysqli, $name) . "' AND password = '" . PreventSqlInjection($mysqli, $pwd) . "'");
if($records == false)
{
    die("Query contains error" . mysqli_error());echo "<script>alert('Wrong password!');location='login.html'</script>";   
}
else
{
    echo "<script>alert('Success!');location='web1.php'</script>";
};*/

$closed = mysqli_close($connection);
if($closed == false)
{
    die("Connection closed failed " . mysqli_error());
}
?>