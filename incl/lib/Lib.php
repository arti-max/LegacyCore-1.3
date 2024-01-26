<?php

class mainLib {
  public function getIDFromPost(){

		if(!empty($_POST["udid"])) 
		{
			$id = $_POST["udid"];
			if(is_numeric($id)) exit("-1");
		}
		else
		{
			exit("-1");
		}
		return $id;
	}

  public function getUserID($udid, $userName = "Undefined") {
		include __DIR__ . "/connection.php";
		if(is_numeric($udid)){
			$register = 1;
		}else{
			$register = 0;
		}
		$query = $db->prepare("SELECT userID FROM users WHERE udid LIKE BINARY :id");
		$query->execute([':id' => $udid]);
		if ($query->rowCount() > 0) {
			$userID = $query->fetchColumn();
		} else {
			$query = $db->prepare("INSERT INTO users (udid, userName)
			VALUES (:id, :userName)");

			$query->execute([':id' => $udid, ':userName' => $userName]);
			$userID = $db->lastInsertId();
		}
		return $userID;
	}
  
}

?>
