<?php

namespace Main;		// Call the namespace "main"

class Objects
{
	// To simulate get and set properties we use interceptor methods
	// Use this as a parent class, so child classes can use get and set
	public function __get($property)
	{
		$method = "Get{$property}";			// Create method name e.g. "GetName", in local variable $method
		if(method_exists($this, $method))	// Check function exists
		{
			return $this->$method();		// Call it
		}
		return;	// Return undefined so isset() will give false (because property may contain bool)
	}
	
	public function __set($property, $value)
	{
		$method = "Set{$property}";
		if(method_exists($this, $method))
		{
			$this->$method($value);
		}
		return;
	}
}

?>