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
	
	public function Increment()
	{
		$this->_count++;
		echo "Counter incremented to {$this->_count}<br/>";
		if($this->_count > $this->_limit)
		{
			$this->Notify();
		}	
	}
	
	public function Decrement()
	{
		$this->_count--;	
		echo "Counter decremented to {$this->_count}<br/>";
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