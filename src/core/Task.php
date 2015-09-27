<?php 

class Task 
{
	private $db;


	public function __construct() {
		if (!file_exists('kek')) {
			try {
				$this->db = new PDO('sqlite:kek');
				$this->db -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	   		$this->db->exec("CREATE TABLE IF NOT EXISTS tasks (
		                    id INTEGER PRIMARY KEY, 
		                    title TEXT, 
		                    message TEXT, 
		                    time TEXT,
		                    ip TEXT)");	


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

	public function clearData($data){
		$data = sqlite_escape_string($data);
		$data = stripslashes($data);
		$data = trim($data);
		$data = strip_tags($data);
		return $data;
	}

	public function getAll() {

		try {
			$sth = $this->db->prepare("SELECT * FROM tasks ORDER BY id DESC");
		   $sth->execute();
		   return $sth;

		} catch (PDOException $e) { echo $e->getMessage(); }

	}

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

	public function selectLast() {
		try 
		{
		   $sth = $this->db->prepare("SELECT * FROM tasks ORDER BY id DESC LIMIT 1");
		   $sth->execute();
         $sth->setFetchMode(PDO::FETCH_ASSOC);	
			while($row = $sth->fetch()){
		   	$list[] = $row;
			}

			echo json_encode($list);

		} catch (PDOException $e) { echo $e->getMessage(); }

	}

}
?>