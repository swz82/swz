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

	public function Update($name,$mysqli)
	{
		
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

}
?>