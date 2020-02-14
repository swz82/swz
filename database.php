<?php

// The PHP Data Objects (PDO) extension defines a lightweight, consistent interface

// for accessing databases in PHP. Each database driver that implements the PDO interface

// can expose database-specific features as regular extension functions.

// Note that you cannot perform any database functions using the PDO extension by itself;

// you must use a database-specific PDO driver to access a database server.

// PDO provides a data-access abstraction layer, which means that, regardless of which

// database you're using, you use the same functions to issue queries and fetch data.

// WAMP enables the PDO for mysql by default.



// First specify the type of content being returned

//header("Content-Type:text/plain");

echo "<html><head></head><body>";



// Set up variables for the database

// May need to change data source name for WAMP

//$dsn = "mysql:host=127.0.0.1;port=3307;dbname=mydatabase";

$dsn = 'mysql:host=mysql;port=3306;dbname=TestDatabase';

$dbUName = "root";

$dbPassword = "docker";



/*********************************************************/

// WARNING: If there is a connection error and exception is

// thrown and by default the the full connection string

// including username and password are displayed. Not

// good for a production environment. So always catch the

// exception or use the class SafePDO at the bottom of the

// file instead.

// $dbh = new SafePDO($dsn, $dbUName, $dbPassword);

/*********************************************************/



$dbh = new SafePDO($dsn, $dbUName, $dbPassword);



// Many web applications will benefit from making persistent connections

// to database servers. Persistent connections are not closed at the end

// of the script, but are cached and re-used when another script requests

// a connection using the same credentials. The persistent connection cache

// allows you to avoid the overhead of establishing a new connection every

// time a script needs to talk to a database, resulting in a faster web application.

//

//$dbh = new PDO($dsn, $user, $pass, array(PDO::ATTR_PERSISTENT => true));





foreach($dbh->query('SELECT * from Student') as $record)

{

	echo "row: name=" . $record["Name"] . " age " . $record["Age"] . " course " . $record["Course"] . "<br/>";

}



// Can execute a non-query like this.

//$count = $dbh->exec("DELETE FROM fruit WHERE colour = 'red'");

// if($count !== false)  // $count is number of rows modified/deleted.

//	die("SQL error");



// Prepared statements in PHP prevent SQL injection attacks and

// provide performance benefits because the query can be parsed once

// but the parameters can be changed multiple times. When the query is

// prepared, the database will analyze, compile and optimize it's plan

// for executing the query. By using a prepared statement the application

// avoids repeating the analyze/compile/optimize cycle.

// This means that prepared statements use fewer resources and thus run faster.

$searchName = "Bob";

$stmt = $dbh->prepare("SELECT * FROM Student WHERE name = :name");		// :name is named placeholder

$stmt->bindParam(':name', $searchName);

//$stmt->bindParam(':name', $studentName, PDO::PARAM_STR, 50);	<-- using explicit datatype & length

//$stmt->bindParam(':age', $studentAge, PDO::PARAM_INT);

if($stmt->execute())

{

	while($record = $stmt->fetch())

	{

		echo "row: name=" . $record["Name"] . " age " . $record["Age"] . " course " . $record["Course"] . "<br/>";

	}

}







// To use LIKE, you need the % character for a wildcard, you

// have to use it like this.

$stmt = $dbh->prepare("SELECT * FROM Student WHERE name LIKE ?");	// ? is a positional placeholder

if($stmt->execute(array("{$searchName}%")))

{

	while($record = $stmt->fetch())

	{

		echo "row: name=" . $record["Name"] . " age " . $record["Age"] . " course " . $record["Course"] . "<br/>";

	}

}



// Positional placeholders, can be used as follows.

//$calories = 150;

//$colour = 'red';

//$sth = $dbh->prepare('SELECT name, colour, calories  FROM fruit WHERE calories < ? AND colour = ?');

//$sth->bindParam(1, $calories, PDO::PARAM_INT);

//$sth->bindParam(2, $colour, PDO::PARAM_STR, 12);

//$sth->execute();





/***********************************************************************************************************/

/*********************************** INSERT USING FORM *****************************************************/

/***********************************************************************************************************/



echo "<br/>Do an insert<br/>";



/*

 * CREATE HTML FORM TO SUBMIT THE FIELDS IN THE FOLLOWING EXAMPLES

 */



// Perform an INSERT query

$enteredName = $_POST["nameTextBox"];

$enteredAge = $_POST["ageTextBox"];

$enteredCourse = $_POST["courseTextBox"];



// Check if key exists e.g. a hidden field

//if (array_key_exists('nameTextBox', $_POST))

//{

//     /* ... do something with the form parameters ... */

//}



// Check if the value was entered and was a number

if(empty($_POST['ageTextBox']))

{

	if ($_POST['ageTextBox'] != strval(intval($_POST['ageTextBox'])))

	{

	     //$errors[] = 'Please enter a valid age.';

	}

}



//if ($_POST['price'] != strval(floatval($_POST['price'])))

//{

//     $errors[] = 'Please enter a valid price.';

//}



// Can also do regular expression matching using

// preg_match();



// When displaying text being posted from a form

// prevent cross script posting attack by converting all

// characters to their HMTL entity equivalents

echo "name is " . htmlentities($enteredName) . "<br/>";



// When storing data, trim leading and trailing spaces...

$enteredName = trim($enteredName);



//...also strip HTML tags you don't want e.g. <b>,

// you may optionally also list tags you want to allow

// e.g. to allow <b> and <i> tags, strip_tags($str, "<b><i>")

$enteredName = strip_tags($enteredName);



// Insert the row

$stmt = $dbh->prepare("INSERT INTO Student(name, age, course) VALUES (:name, :age, :course)");

$stmt->bindParam(':name', $name);

$stmt->bindParam(':age', $age);

$stmt->bindParam(':course', $course);

$name = $enteredName;

$age = $enteredAge;

$course = $enteredCourse;

$stmt->execute();



echo "ID of last inserted record is " . $dbh->lastInsertId();



// Note: You can execute the statement again

// Close the cursor, enabling the statement to be executed again.

// Also do this if you haven't read all the rows for a previous

// statement and want to execute a new statement. Close the

// previous statement first.

$stmt->closeCursor();



// PHP will automatically close the connection when the script ends,

// but you can do it explicitly.

$dbh = NULL;	// Close the connection by deleting the object



echo "</body></html>";





// Safer version of the PDO class that catches the exception and

// prevents the connection string details being dumped for all to see.

class SafePDO extends PDO

{

	public static function exception_handler($exception)

	{

		// Output the exception details

		die('Uncaught exception: ' . $exception->getMessage());

	}



	public function __construct($dsn, $username='', $password='', $driver_options=array())

	{

		// Temporarily change the PHP exception handler while we . . .

		set_exception_handler(array(__CLASS__, 'exception_handler'));



		// . . . create a PDO object

		parent::__construct($dsn, $username, $password, $driver_options);



		// Change the exception handler back to whatever it was before

		restore_exception_handler();

	}

}





?>

