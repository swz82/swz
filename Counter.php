<?php
namespace Main\ObserverPattern;

require_once 'Objects.php';
require_once 'IObservable.php';

use \Main\Objects;

class Counter extends Objects implements IObservable
{
	// Attributes
	private $_observers = [];
	private $_count = 0;
	private $_limit;
	
	// Constructor
	public function __construct($limit)
	{
		$this->_limit = $limit;
	}
	
	// Methods for properties
	// Getters and Setters
	
	// Methods
	
	public function Login($name, $pwd, $mysqli)
	{
		if($name=='')
		{
			echo "<script>alert('Please enter username!');location='login.html'</script>";
		}

		else if($pwd=='')
		{
			echo "<script>alert('Please enter password!');location='login.html'</script>";
		}

		// Execute SQL query, for SELECT returns resource 

		// on true or false if error
		$records = $mysqli->query("SELECT * FROM Students WHERE 
		username LIKE '" . PreventSqlInjection($mysqli, $name) . "' 
    	AND password LIKE '" . PreventSqlInjection($mysqli, $pwd) . "'");
		if($records == false)
		{
    		die("Query contains error" . mysqli_error());
    		echo "<script>alert('Query contains error!');location='login.html'</script>";   
		}

		$date = date('Y-m-d h:i:s', time());
		$record = mysqli_fetch_array($records);
		if($record==NULL)
		{
		    echo "<script>alert('Wrong password!');location='login.html'</script>";
		}
		else
		{
    		$log = $mysqli->query(
       			"INSERT INTO Log(name, log, time) VALUES(" 
		           . "'" . PreventSqlInjection($mysqli, $name)
  		         . "', 'login' , '{$date}' )"
  		       );
  		    if($log == false)
 		    {
 		       die("Query contains error");
   		 	}
 		    if($record["id"]=="administrator")
  		    {
  		      echo "<script>alert('Success!');location='welcomeA.html'</script>";
   		 	}
   		 	else
  		 	{
  		      echo "<script>alert('Success!');location='welcomeU.html'</script>";
   		 	}
    
		}

			
		    // If using MySQL, escape special characters	
		    return $mysqli->real_escape_string($text);
		}

		$closed = $mysqli->close();
		if($closed == false)
		{
			die("Connection closed failed " . mysqli_error());
		}
	}

	public function Search()
	{
		//echo "User used search.<br/>";
		foreach($this->_observers as $observer)
		{
			$observer->Log($this);
		}
		
	}
	
	public function Logout()
	{
		//echo "Login number decremented to {$this->_count}<br/>";
		if($this->_count!=0)
		{
			$this->_count--;
		}
	}
	
	public function Attach(IObserver $observer)
	{
		$this->_observers[] = $observer;
	}
	
	public function Detach(IObserver $observer)
	{
		$index = array_search($observer, $this->_observers, true);
		if($index !== false)
		{
			array_splice($this->_observers, $index, 1);
		}
	}
	
	public function Notify()
	{
		foreach($this->_observers as $observer)
		{
			$observer->Update($this);
		}
		
	}

	public function PreventSqlInjection($mysqli, $text)
	{
    	if (get_magic_quotes_gpc())		// Is magic quotes on? 
  	    {
  	    	$text = stripslashes($text);	// Remove the slashes added
		}
	}
}
?>