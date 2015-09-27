<?php
include 'src/core/Task.php';

$task = new Task();


if($_SERVER["REQUEST_METHOD"]=="POST") {

	if ( 
		isset($_POST['title']) && !empty($_POST['title'] ) &&
      isset($_POST['message']) && !empty($_POST['message'])
      ) 
	{
		$title = $task->clearData($_POST['title']);
		$message = $task->clearData($_POST['message']);
		$task->addTask($title, $message);

		$task->selectLast();

		die();

   } elseif (isset($_POST['del'])) {

	   $del = $_POST['del'];
	   $task->toArchive($del);

	   return true;

	   die();
   }
}

?>