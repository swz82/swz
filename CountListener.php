<?php
namespace Main\ObserverPattern;

require_once 'Objects.php';
require_once 'IObserver.php';

use \Main\Objects;

class CountListener extends Objects implements IObserver
{
	// Attributes
	private $_id;
	
	// Constructor
	public function __construct($id)
	{
		$this->_id = $id;
	}
	
	// Methods for properties
	// Getters and Setters
	
	// Methods
	
	public function Update(IObservable $observable, $mysqli, $log)
	{
		$date = date('Y-m-d h:i:s', time());
		$log = $mysqli->query(
			"INSERT INTO Log(name, log, time) VALUES(" 
			. "'{$this->_id}', '{$log}' , '{$date}' )"
		);
		if($log == false)
		{
			die("Query contains error");		
		}
	}
	
	public function Update2(IObservable $observable, $id, $mysqli, $log)
	{
		$date = date('Y-m-d h:i:s', time());
		$log = $mysqli->query(
			"INSERT INTO Log(name, log, time) VALUES(" 
			. "'" . PreventSqlInjection($mysqli, $id)
			. "', '{$log}' , '{$date}' )"
		);
		if($log == false)
		{
			die("Query contains error");		
		}
	}
	
}
?>