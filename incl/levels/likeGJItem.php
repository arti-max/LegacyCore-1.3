<?php
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/exploitPatch.php";
require_once "../lib/Lib.php";
$gs = new Lib();

if(!isset($_POST['itemID']))
	exit(-1);

$levelID = ExploitPatch::remove($_POST['itemID']);
$type = $_POST["type"];
$isLike = $_POST["like"];

$query = $db->prepare("SELECT count(*) FROM likes WHERE levelID=:levelID AND type=:type");
$query->execute([':levelID' => $levelID, ':type' => $type]);
if($query->fetchColumn() > 2)
	exit("-1");

$query = $db->prepare("INSERT INTO likes (levelID, type, isLike) VALUES (:levelID, :type, :isLike)");
$query->execute([':levelID' => $levelID, ':type' => $type, ':isLike' => $isLike]);

switch($type){
	case 1:
		$table = "levels";
		$column = "levelID";
		break;
	case 2:
		$table = "comments";
		$column = "commentID";
		break;
    }

$query=$db->prepare("SELECT likes FROM $table WHERE $column = :levelID LIMIT 1");
$query->execute([':levelID' => $levelID]);
$likes = $query->fetchColumn();

if ($isLike == 1) {
    $sign = "+";
} else {
    $sign = "-";
}

$query = $db->prepare("UPDATE $table SET likes = likes $sign 1 WHERE $column = :levelID");
$query->execute([':levelID' => $levelID]);
echo "1";
?>
