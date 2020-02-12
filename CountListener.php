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
	
	public function Update(IObservable $observable)
	{
		echo "Count listener {$this->_id} just got updated<br/>";
	}
}
?>