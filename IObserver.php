<?php
namespace Main\ObserverPattern;

require_once 'IObservable.php';

interface IObserver
{
	public function Update(IObservable $observable, $mysqli, $log);
}
?>