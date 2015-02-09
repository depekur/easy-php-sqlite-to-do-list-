<?php
error_reporting(E_ERROR | E_PARSE);


// открываем базу данных 
// создаем если базы нет 
$db = sqlite_open("k"); 
  
// Создадим таблицу в базе 
$query=("SELECT id FROM chat LIMIT 1");

if (!sqlite_query($db, $query)){
  $query = ("CREATE TABLE chat 
            (id       INTEGER   PRIMARY KEY, 
             name     TEXT, 
             massage  TEXT,
             time     TEXT);
            ");
  sqlite_query($db, $query, $query_error); 

}


//вычисляем по айпи
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}



//принимаем переменные из формы
if (isset($_POST['massage'])){
  $massage = strip_tags($_POST['massage']);

  if ($name = $_POST['name']) 
    $name = strip_tags($_POST['name']);
  else
    $name = $ip; 

  $time = date('D m-Y G:i:s'); 

  // пишем сообщение в бд 
  $query = ("INSERT INTO chat(name, massage, time) 
            VALUES ('$name', '$massage', '$time');
          ");

  sqlite_exec($db, $query); 



}

//удаляем пост по запросу
if (isset($_POST['delete'])) {
    $delete = $_POST['delete'];
    $query = ("DELETE FROM chat WHERE id='$delete'");
    sqlite_exec($db, $query); 
}


// делаем выборку  
$res = sqlite_query($db, "SELECT * FROM chat", $query_error); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
		<title>free chat</title>
	<link rel="stylesheet" href="style.css" />
</head>
<body>

<h1>free chat</h1>

<div class="chat-wrap">
  <div id="chat"> 

<?php 

// строим чат 
$id = 0; 

while ($array = sqlite_fetch_array($res)) 
  { 
    $id++;

    if (($id % 2)==0){  
      $color = 'black';
    }  
    else {        
      $color = 'white';
    }
?>
  <p class="post <?php echo($color);?>">
    <i class="post-meta">><?echo($id);?> by <b><?echo($array['name']);?></b> at <?echo($array['time']);?> 
    </i>
        <form class="post-form">
          <button name="delete" class="delete-button" formmethod="post" value=<?echo($array['id']);?> >x
          </button>
        </form>
    
    <p class="post-data <?echo($color);?>" > <?echo($array['massage']);?> </p>
  
<?php  }  //чат готов, хозяин ?>

  </div>

<form class="chat-form" method="post" action="index.php">
    <!--<p>Имя <input name="name" placeholder="anon"></p>-->
  <textarea name="massage" cols="72" rows="3" required autofocus>
    
  </textarea>
  <input type="submit">
</form>


</div>




<script type="text/javascript">
  var chat = document.getElementById("chat");
  chat.scrollTop = chat.scrollHeight;
</script>

</body>
</html>
