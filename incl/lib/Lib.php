<?php

class Lib {
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

	public function getUserString($userdata) {
		include __DIR__ . "/connection.php";
		/*$query = $db->prepare("SELECT userName, extID FROM users WHERE userID = :id");
		$query->execute([':id' => $userID]);
		$userdata = $query->fetch();*/
		$udid = is_numeric($userdata['udid']) ? $userdata['udid'] : 0;
		return "${userdata['userID']}:${userdata['userName']}:${udid}";
	}
  
}

?>
