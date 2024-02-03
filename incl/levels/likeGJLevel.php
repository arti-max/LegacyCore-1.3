<?php
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/exploitPatch.php";
require_once "../lib/Lib.php";
$gs = new Lib();

if(!isset($_POST['levelID']))
	exit(-1);

$levelID = ExplotPatch::remove($_POST['levelID']);

$query = $db->prepare("SELECT count(*) FROM likes WHERE levelID=:levelID");
$query->execute([':levelID' => $levelID]);
if($query->fetchColumn() > 2)
	exit("-1");

$query = $db->prepare("INSERT INTO likes (levelID) VALUES (:levelID)");
$query->execute([':levelID' => $levelID]);

$query=$db->prepare("SELECT likes FROM levels WHERE $levelID = :levelID LIMIT 1");
$query->execute([':levelID' => $levelID]);
$likes = $query->fetchColumn();


$query=$db->prepare("UPDATE levels SET likes = likes + 1 WHERE levelID = :levelID");
$query->execute([':levelID' => $levelID]);
echo "1";
?>
