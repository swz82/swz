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
		$records = $mysqli->query("SELECT * FROM Students WHERE username LIKE '{$name}' AND password LIKE '{$pwd}'");
		if($records == false)
		{
			die("Query contains error" . mysqli_error());
			echo "<script>alert('Query contains error!');location='login.html'</script>";   
			return ;
		}

		$record = mysqli_fetch_array($records);
		if($record==NULL)
		{
			$this->Notify($mysqli, "login failed");
			echo "<script>alert('Wrong password!');location='login.html'</script>";
			return ;
		}
		else
		{
			setcookie("name",$record["username"],time()+3600);
			setcookie("pwd",$record["password"],time()+3600);
			setcookie("info",$record["information"],time()+3600);
			$this->Notify($mysqli, "login");
			if($record["id"]=="administrator")
			{
				echo "<script>alert('Success!');location='welcomeA.html'</script>";
			}
			else
			{
				echo "<script>alert('Success!');location='welcomeU.html'</script>";
			} 
		}
	}
	
	public function Sign($name, $pwd, $info, $mysqli)
	{
		$this->Create($name, $name, $pwd, $info, "user", "sign up", 0, $mysqli);
	}
	
	public function Search($id, $name, $mysqli)
	{
		$this->Notify($mysqli, "search for {$name}");
		$keys = $mysqli->query("SELECT * FROM Students WHERE username LIKE '{$id}'");
		if($keys == false)
		{
			die("Query contains error" . mysqli_error());
			echo "<script>alert('Query contains error!');location='login.html'</script>";
			return ;			
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
		$records = $mysqli->query("SELECT * FROM Students WHERE username LIKE '%{$name}%'");
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
		else if($flag==1)
		{
			echo "<p style='color:#6600FF; font-size:30px;'>Student Search Result</p>";
			echo "--------------------------------------------------------------------------------------------------------------<br/>";
			/*echo "<p style='display:inline-block;width: 200px'>| ID</p>";
			echo "<p style='display:inline-block;width: 200px'>| Username</p>";
			echo "<p style='display:inline-block;width: 200px'>| Password</p>";
			echo "<p style='display:inline-block;width: 200px'>| Information </p>|";*/			
			echo "|";
			$this->Space("id",20);
			$this->Space("user",20);
			$this->Space("password",20);
			$this->Space("information",30);echo "<br>";
			echo "--------------------------------------------------------------------------------------------------------------<br/>";
			/*$id = $record['id'];$name = $record['username'];$pwd = $record['password'];$info = $record['information'];
			echo "<p style='display:inline-block;width: 200px'>| $id</p>";
			echo "<p style='display:inline-block;width: 200px'>| $name</p>";
			echo "<p style='display:inline-block;width: 200px'>| $pwd</p>";
			echo "<p style='display:inline-block;width: 200px'>| $info</p>|";*/
			echo "|";
			$this->Space($record['id'],20);
			$this->Space($record['username'],20);
			$this->Space($record['password'],20);
			$this->Space($record['information'],30);echo "<br>";
			echo "--------------------------------------------------------------------------------------------------------------<br/>";	
		}else
		{
			echo "<p style='color:#6600FF; font-size:30px;'>Student Search Result</p>";
			echo "--------------------------------------------------------------------------------------<br>";	
			/*echo "<p style='display:inline-block;width: 200px'>| ID</p>";
			echo "<p style='display:inline-block;width: 200px'>| Username</p>";
			echo "<p style='display:inline-block;width: 200px'>| Information </p>|";*/			
			echo "|";
			$this->Space("id",20);
			$this->Space("user",20);
			$this->Space("information",30);echo "<br>";
			echo "--------------------------------------------------------------------------------------<br/>";
			/*$id = $record['id'];$name = $record['username'];$info = $record['information'];
			echo "<p style='display:inline-block;width: 200px'>| $id</p>";
			echo "<p style='display:inline-block;width: 200px'>| $name</p>";
			echo "<p style='display:inline-block;width: 200px'>| $info</p>|";*/
			echo "|";
			$this->Space($record['id'],20);
			$this->Space($record['username'],20);
			$this->Space($record['information'],30);echo "<br>";	
			echo "--------------------------------------------------------------------------------------<br/>";
		}

		while($record = mysqli_fetch_array($records))
		{
			if($flag==1)
			{
				/*$id = $record['id'];$name = $record['username'];$pwd = $record['password'];$info = $record['information'];
				echo "<p style='display:inline-block;width: 200px'>| $id</p>";
				echo "<p style='display:inline-block;width: 200px'>| $name</p>";
				echo "<p style='display:inline-block;width: 200px'>| $pwd</p>";
				echo "<p style='display:inline-block;width: 200px'>| $info</p>|";*/
				echo "|";
				$this->Space($record['id'],20);
				$this->Space($record['username'],20);
				$this->Space($record['password'],20);
				$this->Space($record['information'],30);echo "<br>";
				echo "--------------------------------------------------------------------------------------------------------------<br/>";
			}else
			{
				/*$id = $record['id'];$name = $record['username'];$info = $record['information'];
				echo "<p style='display:inline-block;width: 200px'>|       $id</p>";
				echo "<p style='display:inline-block;width: 200px'>|       $name</p>";
				echo "<p style='display:inline-block;width: 200px'>|       $info</p>|";*/
				echo "|";
				$this->Space($record['id'],20);
				$this->Space($record['username'],20);
				$this->Space($record['information'],30);echo "<br>";
				echo "--------------------------------------------------------------------------------------<br/>";
			}			
		}
	}

	public function Create($id, $enteredName, $enteredPwd, $enteredInfo, $enteredId, $log, $flag, $mysqli)
	{
		$records = $mysqli->query("SELECT * FROM Students WHERE username LIKE '{$enteredName}'");
		if($records == false)
		{
			die("Query contains error" . mysqli_error());
			echo "<script>alert('Query contains error!');location='create.html'</script>";
			return ;
		}

		$record = mysqli_fetch_array($records);
		if($record!=NULL)
		{
			$this->Notify($mysqli, "create failed");
			echo "<script>alert('User already exists!');location='create.html'</script>";
			return ;
		}
		
		$result = $mysqli->query("INSERT INTO Students(username, password, information, id) 
			VALUES('{$enteredName}','{$enteredPwd}','{$enteredInfo}','{$enteredId}')");
		if($result == false)
		{
			die("Query contains error");
		}
		else
		{
			$this->Notify($mysqli, $log);
			if($flag==1)echo "<script>alert('Create success!');location='create.html'</script>";
			else echo "<script>alert('Create success!');location='welcomeU.html'</script>";
		}   
	}
	
	public function Delete($id, $key, $mysqli)
	{
		$result = $mysqli->query("DELETE FROM Students WHERE username = '{$key}'");
		if($result == false)
		{
			die("Query contains error");
		}
		else
		{
			$this->Notify($mysqli, "delete a user: {$key}");
			echo "<script>alert('Delete success!');location='delete.html'</script>";
		}   
	}
	
	public function Update($id, $enteredNew, $enteredPwd, $enteredInfo, $enteredId, $enteredName, $mysqli)
	{
		if($enteredName!=$enteredNew)
		{
			$records = $mysqli->query("SELECT * FROM Students WHERE username LIKE '{$enteredNew}'");
			if($records == false)
			{
				die("Query contains error" . mysqli_error());
				echo "<script>alert('Query contains error!');location='update.html'</script>";
				return ;				
			}

			$record = mysqli_fetch_array($records);
			if($record!=NULL)
			{
				$this->Notify($mysqli, "update failed");
				echo "<script>alert('User already exists!');location='update.html'</script>";
				return ;
			}
		}
		
		$result = $mysqli->query("UPDATE Students SET username = '{$enteredNew}', password = '{$enteredPwd}', 
			information = '{$enteredInfo}', id = '{$enteredId}' WHERE username = '{$enteredName}'");
		if($result == false)
		{
			die("Query contains error");
		}
		else
		{
			$this->Notify($mysqli, "update a user: {$enteredName}");
			echo "<script>alert('Update success!');location='update.html'</script>";
		}   
	}
	
	public function Profile($id, $enteredPwd, $enteredInfo, $mysqli)
	{
		$result = $mysqli->query("UPDATE Students SET password = '{$enteredPwd}', 
			information = '{$enteredInfo}' WHERE username = '{$id}'");
		if($result == false)
		{
			die("Query contains error");
		}
		else
		{
			$this->Notify($mysqli, "update profile");
			echo "<script>alert('Update success!');location='welcomeU.html'</script>";
		}   
	}
	
	public function Logout($id, $mysqli)
	{
		$this->Notify($mysqli, "logout");
		echo "<script>alert('You have been logged out!');location='login.html'</script>";		
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
	
	public function Notify($mysqli, $log)
	{
		foreach($this->_observers as $observer)
		{
			$observer->Update($this, $mysqli, $log);
		}		
	}
	
	public function Space($str, $lens)
	{
		$str = strtolower($str);
		
		$txt = str_replace("m","-m",$str);
		$txt = str_replace("w","-w",$txt);
		$txt = str_pad($txt,$lens,"*",STR_PAD_LEFT);		
		$txt = str_replace("-m","m",$txt);
		$txt = str_replace("-w","w",$txt);		
		
		$txt = str_replace("i","+i",$txt);
		$txt = str_replace(".","+.",$txt);
		$txt = str_replace(",","+,",$txt);
		$txt = str_replace("'","+'",$txt);
		$len = strlen($txt);
		$txt = str_replace("+i","i",$txt);
		$txt = str_replace("+.",".",$txt);
		$txt = str_replace("+,",",",$txt);
		$txt = str_replace("+'","'",$txt);
		$txt = str_pad($txt,$len,"+",STR_PAD_LEFT);
		$txt = str_replace("+",'&nbsp;',$txt);
		
		$txt = str_replace(" ",'&ensp;',$txt);
	
		echo str_replace("*",'&ensp;',$txt);echo " |";
	}
	

}
?>