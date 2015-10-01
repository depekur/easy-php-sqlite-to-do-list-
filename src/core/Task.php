<?php 

class Task 
{
	private $db;

	/*
	* если файл базы уже есть - подключаемся
	* если нет - создаем базу и добавляем 3 стартовые записи 
	*/
	public function __construct() {
		if (!file_exists('kek')) {
			try {
				$this->db = new PDO('sqlite:kek');
				$this->db -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	   		$this->db->exec("CREATE TABLE IF NOT EXISTS tasks (
			                    id INTEGER PRIMARY KEY, 
			                    active INTEGER DEFAULT 1,
			                    title TEXT, 
			                    message TEXT, 
			                    time TEXT,
			                    finish TEXT DEFAULT 'never',
			                    ip TEXT
		                     )");	


		      $dt = date('d-m-Y G:i');
				$ip = $_SERVER["REMOTE_ADDR"];
				
				$this->db->exec("INSERT INTO tasks (title, message, time, ip) 
										VALUES ('me too', 'Developed by http://bananagarden.net/', '$dt', '$ip')");
				$this->db->exec("INSERT INTO tasks (title, message, time, ip) 
										VALUES ('and me', 'Don`t forget to do all this things what you`re added', '$dt', '$ip')");
				$this->db->exec("INSERT INTO tasks (title, message, time, ip) 
										VALUES ('please kill me', 'Feel free to add your task or any stuff you want', '$dt', '$ip')");

 


			} catch (PDOException $e) { echo $e->getMessage(); }

		} else {
			$this->db = new PDO('sqlite:kek');
			$this->db -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}
		
	}

	public function close(){
		$this->db = null;
	}

	/*
	* фильтруем данные
	*/
	public function clearData($data){
		$data = sqlite_escape_string($data);
		$data = stripslashes($data);
		$data = trim($data);
		$data = strip_tags($data);
		return $data;
	}

	/*
	* принимаем стейтмент, гоним его в массив и отдаем жсон 
	*/
	static private function toJson($sth) {
		$sth->setFetchMode(PDO::FETCH_ASSOC);	
		while($row = $sth->fetch()){
	   	$list[] = $row;
		}

		echo json_encode($list);
	}

	/*
	* выбираем все таски из базы
	* 1 - активные
	* 0 - архив
	*/
	public function getAll($a) {

		try {
			$sth = $this->db->prepare("SELECT * FROM tasks WHERE active='$a' ORDER BY id DESC");
		   $sth->execute();

		   self::toJson($sth);	

		} catch (PDOException $e) { echo $e->getMessage(); }

	}


	/*
	* берем текущее время и айпи жертвы
	* и записываем таску в базу	
	*/
	public function addTask($title, $message) {

		$dt = date('d-m-Y G:i');
		$ip = $_SERVER["REMOTE_ADDR"];

		$insert = "INSERT INTO tasks (title, message, time, ip) 
						VALUES (:title, :message, :time, :ip)";

		$stmt = $this->db->prepare($insert);

		$stmt->bindParam(':title', $title);
		$stmt->bindParam(':message', $message);
		$stmt->bindParam(':time', $dt);
		$stmt->bindParam(':ip', $ip);

		$stmt->execute();
	}

	public function deleteTask($del) {
		$this->db->exec("DELETE FROM tasks WHERE id='$del'");
	}

	/*
	* берем текущее время (время удаления, завершения таски)
	* меняем поле active на 0, т.е. архивируем 
	*/
	public function toArchive($del) {
		$fin = date('d-m-Y G:i');
		$this->db->exec("UPDATE tasks SET active=0, finish='$fin' WHERE id='$del'");
	}

	/*
	* выбираем последнюю добавленную таску
	* отдаем жсон
	*/
	public function selectLast() {
		try 
		{
		   $sth = $this->db->prepare("SELECT * FROM tasks ORDER BY id DESC LIMIT 1");
		   $sth->execute();

         self::toJson($sth);

		} catch (PDOException $e) { echo $e->getMessage(); }

	}

}
?>