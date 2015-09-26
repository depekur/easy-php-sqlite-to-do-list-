<?php
include 'Task.php';

$task = new Task();

if($_SERVER["REQUEST_METHOD"]=="POST"){
   if(isset($_POST['title']) && !empty($_POST['title']) &&
      isset($_POST['message']) && !empty($_POST['message'])) 
   {
      $title = $task->clearData($_POST['title']);
      $massage = $task->clearData($_POST['message']);
      $task->addTask($title, $massage);
   } 
}

if (isset($_GET['del'])) {
   $del = $_GET['del'];
   $task->deleteTask($del);

}

$data = $task->getAll();




$task->close();

?>