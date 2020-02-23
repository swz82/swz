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
		$this->Create($name, $pwd, $info, "user", "sign up", 0, $mysqli);
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
			echo "&nbsp;--------------------------------------------------------------------------------------------------------------<br/>";
			echo "<p style='display:inline-block;width: 200px'>| ID</p>";
			echo "<p style='display:inline-block;width: 200px'>| Username</p>";
			echo "<p style='display:inline-block;width: 200px'>| Password</p>";
			echo "<p style='display:inline-block;width: 200px'>| Information </p>|";			
			//echo "|";$this->Space("id",20);$this->Space("user",20);$this->Space("password",20);$this->Space("information",30);
			echo "<br>";
			echo "&nbsp;--------------------------------------------------------------------------------------------------------------<br/>";
			$id = $record['id'];$name = $record['username'];$pwd = $record['password'];$info = $record['information'];
			echo "<p style='display:inline-block;width: 200px'>| $id</p>";
			echo "<p style='display:inline-block;width: 200px'>| $name</p>";
			echo "<p style='display:inline-block;width: 200px'>| $pwd</p>";
			echo "<p style='display:inline-block;width: 200px'>| $info</p>|";
			//echo "|";$this->Space($record['id'],20);$this->Space($record['username'],20);$this->Space($record['password'],20);$this->Space($record['information'],30);
			echo "<br>";
			echo "&nbsp;--------------------------------------------------------------------------------------------------------------<br/>";	
		}else
		{
			echo "<p style='color:#6600FF; font-size:30px;'>Student Search Result</p>";
			echo "&nbsp;--------------------------------------------------------------------------------------<br>";	
			echo "<p style='display:inline-block;width: 200px'>| ID</p>";
			echo "<p style='display:inline-block;width: 200px'>| Username</p>";
			echo "<p style='display:inline-block;width: 200px'>| Information </p>|";			
			//echo "|";$this->Space("id",20);$this->Space("user",20);$this->Space("information",30);
			echo "<br>";
			echo "&nbsp;--------------------------------------------------------------------------------------<br/>";
			$id = $record['id'];$name = $record['username'];$info = $record['information'];
			echo "<p style='display:inline-block;width: 200px'>| $id</p>";
			echo "<p style='display:inline-block;width: 200px'>| $name</p>";
			echo "<p style='display:inline-block;width: 200px'>| $info</p>|";
			//echo "|";$this->Space($record['id'],20);$this->Space($record['username'],20);$this->Space($record['information'],30);
			echo "<br>";	
			echo "&nbsp;--------------------------------------------------------------------------------------<br/>";
		}

		while($record = mysqli_fetch_array($records))
		{
			if($flag==1)
			{
				$id = $record['id'];$name = $record['username'];$pwd = $record['password'];$info = $record['information'];
			echo "<p style='display:inline-block;width: 200px'>| $id</p>";
			echo "<p style='display:inline-block;width: 200px'>| $name</p>";
			echo "<p style='display:inline-block;width: 200px'>| $pwd</p>";
			echo "<p style='display:inline-block;width: 200px'>| $info</p>|";
			//echo "|";$this->Space($record['id'],20);$this->Space($record['username'],20);$this->Space($record['password'],20);$this->Space($record['information'],30);
			echo "<br>";
			echo "&nbsp;--------------------------------------------------------------------------------------------------------------<br/>";	
			}else
			{
				$id = $record['id'];$name = $record['username'];$info = $record['information'];
			echo "<p style='display:inline-block;width: 200px'>| $id</p>";
			echo "<p style='display:inline-block;width: 200px'>| $name</p>";
			echo "<p style='display:inline-block;width: 200px'>| $info</p>|";
			//echo "|";$this->Space($record['id'],20);$this->Space($record['username'],20);$this->Space($record['information'],30);
			echo "<br>";	
			echo "&nbsp;--------------------------------------------------------------------------------------<br/>";
			}			
		}
	}

	public function SearchID($id, $name, $mysqli)
	{
		$this->Notify($mysqli, "search for id: {$name}");
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
		$records = $mysqli->query("SELECT * FROM Students WHERE id LIKE '%{$name}%'");
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
			echo "&nbsp;--------------------------------------------------------------------------------------------------------------<br/>";
			echo "<p style='display:inline-block;width: 200px'>| ID</p>";
			echo "<p style='display:inline-block;width: 200px'>| Username</p>";
			echo "<p style='display:inline-block;width: 200px'>| Password</p>";
			echo "<p style='display:inline-block;width: 200px'>| Information </p>|";			
			//echo "|";$this->Space("id",20);$this->Space("user",20);$this->Space("password",20);$this->Space("information",30);
			echo "<br>";
			echo "&nbsp;--------------------------------------------------------------------------------------------------------------<br/>";
			$id = $record['id'];$name = $record['username'];$pwd = $record['password'];$info = $record['information'];
			echo "<p style='display:inline-block;width: 200px'>| $id</p>";
			echo "<p style='display:inline-block;width: 200px'>| $name</p>";
			echo "<p style='display:inline-block;width: 200px'>| $pwd</p>";
			echo "<p style='display:inline-block;width: 200px'>| $info</p>|";
			//echo "|";$this->Space($record['id'],20);$this->Space($record['username'],20);$this->Space($record['password'],20);$this->Space($record['information'],30);
			echo "<br>";
			echo "&nbsp;--------------------------------------------------------------------------------------------------------------<br/>";	
		}else
		{
			echo "<p style='color:#6600FF; font-size:30px;'>Student Search Result</p>";
			echo "&nbsp;--------------------------------------------------------------------------------------<br>";	
			echo "<p style='display:inline-block;width: 200px'>| ID</p>";
			echo "<p style='display:inline-block;width: 200px'>| Username</p>";
			echo "<p style='display:inline-block;width: 200px'>| Information </p>|";			
			//echo "|";$this->Space("id",20);$this->Space("user",20);$this->Space("information",30);
			echo "<br>";
			echo "&nbsp;--------------------------------------------------------------------------------------<br/>";
			$id = $record['id'];$name = $record['username'];$info = $record['information'];
			echo "<p style='display:inline-block;width: 200px'>| $id</p>";
			echo "<p style='display:inline-block;width: 200px'>| $name</p>";
			echo "<p style='display:inline-block;width: 200px'>| $info</p>|";
			//echo "|";$this->Space($record['id'],20);$this->Space($record['username'],20);$this->Space($record['information'],30);
			echo "<br>";	
			echo "&nbsp;--------------------------------------------------------------------------------------<br/>";
		}

		while($record = mysqli_fetch_array($records))
		{
			if($flag==1)
			{
				$id = $record['id'];$name = $record['username'];$pwd = $record['password'];$info = $record['information'];
			echo "<p style='display:inline-block;width: 200px'>| $id</p>";
			echo "<p style='display:inline-block;width: 200px'>| $name</p>";
			echo "<p style='display:inline-block;width: 200px'>| $pwd</p>";
			echo "<p style='display:inline-block;width: 200px'>| $info</p>|";
			//echo "|";$this->Space($record['id'],20);$this->Space($record['username'],20);$this->Space($record['password'],20);$this->Space($record['information'],30);
			echo "<br>";
			echo "&nbsp;--------------------------------------------------------------------------------------------------------------<br/>";	
			}else
			{
				$id = $record['id'];$name = $record['username'];$info = $record['information'];
			echo "<p style='display:inline-block;width: 200px'>| $id</p>";
			echo "<p style='display:inline-block;width: 200px'>| $name</p>";
			echo "<p style='display:inline-block;width: 200px'>| $info</p>|";
			//echo "|";$this->Space($record['id'],20);$this->Space($record['username'],20);$this->Space($record['information'],30);
			echo "<br>";	
			echo "&nbsp;--------------------------------------------------------------------------------------<br/>";
			}			
		}
	}

	public function Create($enteredName, $enteredPwd, $enteredInfo, $enteredId, $log, $flag, $mysqli)
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
	
	public function Delete($key, $mysqli)
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
	
	public function Update($id, $enteredName, $enteredPwd, $enteredInfo, $enteredId, $enteredNew, $mysqli)
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
			if($enteredName==$id)setcookie("name",$enteredNew,time()+3600);
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
	
	public function Logout($mysqli)
	{
		$this->Notify($mysqli, "logout");
		echo "<script>alert('You have been logged out!');location='login.html'</script>";		
	}
	
	public function SearchG($name, $mysqli)
	{
		$this->Notify($mysqli, "search grades for {$name}");
		
		if($name=="") echo "<script>alert('No result!');location='welcomeA.html'</script>";
		
		// Execute SQL query, for SELECT returns resource 
		// on true or false if error
		$records = $mysqli->query("SELECT * FROM Grades WHERE name LIKE '%{$name}%'");
		if($records == false)
		{
			die("Query contains error" . mysqli_error());
			echo "<script>alert('Query contains error!');location='welcomeA.html'</script>";
		}

		$record = mysqli_fetch_array($records);
		if($record==NULL)
		{
			echo "<script>alert('No result!');location='welcomeA.html'</script>";
		}else
		{
			echo "<p style='color:#6600FF; font-size:30px;'>Grades Search Result</p>";
			echo "&nbsp;----------------------------------------------------------------------------------------------------------------------------------------<br>";	
			/*echo "<p style='display:inline-block;width: 200px'>| Name</p>";
			echo "<p style='display:inline-block;width: 200px'>| Math</p>";
			echo "<p style='display:inline-block;width: 200px'>| Art </p>|";
			echo "<p style='display:inline-block;width: 200px'>| Science </p>|";
			echo "<p style='display:inline-block;width: 300px'>| Feedback </p>|";*/			
			echo "|";
			$this->Space("name",20);
			$this->Space("math",20);
			$this->Space("art",20);
			$this->Space("science",20);
			$this->Space("feedback",30);echo "<br>";
			echo "&nbsp;----------------------------------------------------------------------------------------------------------------------------------------<br>";	
			/*$name = $record['name'];$math = $record['math'];$art = $record['art'];$science = $record['science'];$feedback = $record['feedback'];
			echo "<p style='display:inline-block;width: 200px'>| $name</p>";
			echo "<p style='display:inline-block;width: 200px'>| $math</p>";
			echo "<p style='display:inline-block;width: 200px'>| $art </p>|";
			echo "<p style='display:inline-block;width: 200px'>| $science </p>|";
			echo "<p style='display:inline-block;width: 200px'>| $feedback </p>|";*/
			echo "|";
			$this->Space($record['name'],20);
			$this->Space($record['math'],20);
			$this->Space($record['art'],20);
			$this->Space($record['science'],20);
			$this->Space($record['feedback'],30);echo "<br>";
			echo "&nbsp;----------------------------------------------------------------------------------------------------------------------------------------<br>";	
		}

		while($record = mysqli_fetch_array($records))
		{
			/*$name = $record['name'];$math = $record['math'];$art = $record['art'];$science = $record['science'];$feedback = $record['feedback'];
			echo "<p style='display:inline-block;width: 200px'>| $name</p>";
			echo "<p style='display:inline-block;width: 200px'>| $math</p>";
			echo "<p style='display:inline-block;width: 200px'>| $art </p>|";
			echo "<p style='display:inline-block;width: 200px'>| $science </p>|";
			echo "<p style='display:inline-block;width: 200px'>| $feedback </p>|";*/
			echo "|";
			$this->Space($record['name'],20);
			$this->Space($record['math'],20);
			$this->Space($record['art'],20);
			$this->Space($record['science'],20);
			$this->Space($record['feedback'],30);echo "<br>";
			echo "&nbsp;----------------------------------------------------------------------------------------------------------------------------------------<br>";	
		}
	}
	
	public function SearchS($id, $name, $mysqli)
	{
		$this->Notify($mysqli, "search for {$name} grade");
		
		if($name=="") echo "<script>alert('No result!');location='welcomeU.html'</script>";
		
		// Execute SQL query, for SELECT returns resource 
		// on true or false if error
		$records = $mysqli->query("SELECT {$name} FROM Grades WHERE name LIKE '{$id}'");
		if($records == false)
		{
			die("Query contains error" . mysqli_error());
			echo "<script>alert('Query contains error!');location='welcomeU.html'</script>";
		}

		$record = mysqli_fetch_array($records);
		if($record==NULL)
		{
			echo "<script>alert('No result!');location='welcomeU.html'</script>";
		}else
		{
			if($name=="*")
			{
				echo "<p style='color:#6600FF; font-size:30px;'>Grades Search Result</p>";
				echo "&nbsp;----------------------------------------------------------------------------------------------------------------------------------------<br>";	
				/*echo "<p style='display:inline-block;width: 200px'>| Name</p>";
				echo "<p style='display:inline-block;width: 200px'>| Math</p>";
				echo "<p style='display:inline-block;width: 200px'>| Art </p>|";
				echo "<p style='display:inline-block;width: 200px'>| Science </p>|";
				echo "<p style='display:inline-block;width: 300px'>| Feedback </p>|";*/				
				echo "|";
				$this->Space("name",20);
				$this->Space("math",20);
				$this->Space("art",20);
				$this->Space("science",20);
				$this->Space("feedback",30);echo "<br>";
				echo "&nbsp;----------------------------------------------------------------------------------------------------------------------------------------<br>";	
				/*$name = $record['name'];$math = $record['math'];$art = $record['art'];$science = $record['science'];$feedback = $record['feedback'];
				echo "<p style='display:inline-block;width: 200px'>| $name</p>";
				echo "<p style='display:inline-block;width: 200px'>| $math</p>";
				echo "<p style='display:inline-block;width: 200px'>| $art </p>|";
				echo "<p style='display:inline-block;width: 200px'>| $science </p>|";
				echo "<p style='display:inline-block;width: 200px'>| $feedback </p>|";*/
				echo "|";
				$this->Space($record['name'],20);
				$this->Space($record['math'],20);
				$this->Space($record['art'],20);
				$this->Space($record['science'],20);
				$this->Space($record['feedback'],30);echo "<br>";
				echo "&nbsp;----------------------------------------------------------------------------------------------------------------------------------------<br>";	
			}else
			{
				echo "<p style='color:#6600FF; font-size:30px;'>Grades Search Result</p>";
				echo "<br> {$name} : {$record[$name]} ";
			}
		}
	}
	
	public function CreateG($name, $math, $art, $science, $feedback, $log, $mysqli)
	{
		$keys = $mysqli->query("SELECT * FROM Students WHERE username LIKE '{$name}'");
		if($keys == false)
		{
			die("Query contains error" . mysqli_error());
			echo "<script>alert('Query contains error!');location='createG.html'</script>";
			return ;
		}

		$key = mysqli_fetch_array($keys);
		if($key == NULL)
		{
			$this->Notify($mysqli, "submit wrong user");
			echo "<script>alert('User does not exist!');location='createG.html'</script>";
			return ;
		}
		
		$records = $mysqli->query("SELECT * FROM Grades WHERE name LIKE '{$name}'");
		if($records == false)
		{
			die("Query contains error" . mysqli_error());
			echo "<script>alert('Query contains error!');location='createG.html'</script>";
			return ;
		}

		$record = mysqli_fetch_array($records);
		if($record != NULL)
		{
			$this->Notify($mysqli, "submit grades failed");
			echo "<script>alert('User already have grades!');location='createG.html'</script>";
			return ;
		}
		
		$result = $mysqli->query("INSERT INTO Grades(name, math, art, science,feedback) 
			VALUES('{$name}','{$math}','{$art}','{$science}','{$feedback}')");
		if($result == false)
		{
			die("Query contains error");
		}
		else
		{
			$this->Notify($mysqli, $log);
			echo "<script>alert('Submit success!');location='createG.html'</script>";
		}   
	}
	
	public function DeleteG($key, $mysqli)
	{
		$result = $mysqli->query("DELETE FROM Grades WHERE name = '{$key}'");
		if($result == false)
		{
			die("Query contains error");
		}
		else
		{
			$this->Notify($mysqli, "delete a grade for {$key}");
			echo "<script>alert('Delete success!');location='deleteG.html'</script>";
		}   
	}
	
	public function UpdateG($name, $math, $art, $science, $feedback, $mysqli)
	{	
		$records = $mysqli->query("SELECT * FROM Grades WHERE name LIKE '{$name}'");
		if($records == false)
		{
			die("Query contains error" . mysqli_error());
			echo "<script>alert('Query contains error!');location='updateG.html'</script>";
			return ;
		}

		$record = mysqli_fetch_array($records);
		if($record==NULL)
		{
			$this->Notify($mysqli, "update grades failed");
			echo "<script>alert('Record not exists!');location='updateG.html'</script>";
			return ;
		}
		
		$result = $mysqli->query("UPDATE Grades SET math = '{$math}', art = '{$art}', 
			science = '{$science}' , feedback = '{$feedback}' WHERE name = '{$name}'");
		if($result == false)
		{
			die("Query contains error");
		}
		else
		{			
			$this->Notify($mysqli, "update a grade for {$name}");
			echo "<script>alert('Update success!');location='updateG.html'</script>";
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
		$txt = str_replace("t","+t",$txt);
		$txt = str_replace("j","+j",$txt);
		$txt = str_replace(".","+.",$txt);
		$txt = str_replace(",","+,",$txt);
		$txt = str_replace("'","+'",$txt);
		$len = strlen($txt);
		$txt = str_replace("+i","i",$txt);
		$txt = str_replace("+t","t",$txt);
		$txt = str_replace("+j","j",$txt);
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