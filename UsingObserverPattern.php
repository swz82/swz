<?php
require_once 'Counter.php';
require_once 'CountListener.php';

use \Main\ObserverPattern\Counter;
use \Main\ObserverPattern\CountListener;

$counter = new Counter(5);
$observer = new CountListener(2);

$counter->Attach(new CountListener(1));
$counter->Attach($observer);
$counter->Attach(new CountListener(3));
$counter->Increment();
$counter->Increment();
$counter->Increment();
$counter->Increment();
$counter->Increment();
$counter->Increment(); // Notify
$counter->Increment(); // Notify
$counter->Decrement();
$counter->Decrement();
$counter->Decrement();
$counter->Detach($observer);
$counter->Increment();
$counter->Increment(); // Notify


?>