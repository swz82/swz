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
	private $closed;
	
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

		$records = $mysqli->query("SELECT * FROM Students WHERE 
		username LIKE '{$name}' AND password LIKE '{$pwd}'");
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
				return 1;
   		 	}
   		 	else
  		 	{
				echo "<script>alert('Success!');location='welcomeU.html'</script>";
				return 0;
   		 	} 
		}
	}

	public function Search($id, $name, $mysqli)
	{
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
	}

	public function Create($enteredName, $enteredPwd, $enteredInfo, $enteredId, $mysqli)
	{
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
			echo "<script>alert('Create success!');location='create.html'</script>";
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

}
?>