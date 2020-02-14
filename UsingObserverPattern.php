<?php
require_once 'Counter.php';
require_once 'CountListener.php';

use \Main\ObserverPattern\Counter;
use \Main\ObserverPattern\CountListener;

$counter1 = new Counter(5);
$counter2 = new Counter(5);
$counter3 = new Counter(5);
$observer = new CountListener('swz');

$counter1->Attach(new CountListener('www'));
$counter2->Attach($observer);
$counter3->Attach(new CountListener(3));
$counter1->Login();
$counter2->Login();
$counter3->Login();
$counter2->Detach($observer);
$counter1->Login();
$counter2->Login(); // Notify
$counter3->Search();


?>