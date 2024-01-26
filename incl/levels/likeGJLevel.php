<?php
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/Lib.php";
$gs = new Lib();

if(!isset($_POST['levelID']))
	exit(-1);

$levelID = $_POST['levelID'];

$query=$db->prepare("SELECT likes FROM levels WHERE $levelID = :levelID LIMIT 1");
$query->execute([':levelID' => $levelID]);
$likes = $query->fetchColumn();


$query=$db->prepare("UPDATE levels SET likes = likes + 1 WHERE levelID = :levelID");
$query->execute([':levelID' => $levelID]);
echo "1";
?>
