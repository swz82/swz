<?php
// Set up variables for the database
$dbServer = "mysql:3306";
$dbName = "Users";
$dbUName = "root";
$dbPassword = "docker";

// Returns connection resource if OK or false if error
// You don't have to specify $dbName, in which case you
// will need to call mysqli_select($dbName) instead.
$connection = mysqli_connect($dbServer, 
                             $dbUName, 
                             $dbPassword, 
                             $dbName);       
if($connection == false)
{
    die("Unable to open connection to database" . mysqli_error());
}

// You can use mysqli_select($connection, $dbname) to connect to another database if you need to.

// Execute SQL query, for SELECT returns resource 
// on true or false if error
$records = mysqli_query($connection, "SELECT * FROM Students");
if($records == false)
{
    die("Query contains error");
}

echo "mysqli procedural approach<br/>";
echo "Display all records<br/>";

// Fetch an array representing the result row.
// Returns row or false if end of rows reached
while($record = mysqli_fetch_array($records))
{
    // Output the results
    echo "row: name=" . $record["username"] 
         . " pwd " . $record["password"] 
         . " info " . $record["information"] . "<br/>";	
}



$closed = mysqli_close($connection);
if($closed == false)
{
    die("Connection closed failed " . mysqli_error());
}
?>