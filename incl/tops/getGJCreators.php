<?php
chdir(dirname(__FILE__));
include "../lib/connection.php";

$udid = $_POST["udid"];
$type = $_POST["type"];

$query = "SELECT * FROM users WHERE cp > 0 ORDER BY cp DESC LIMIT 50";
$query = $db->prepare($query);
//$query->execute([':stars' => $stars]);
$result = $query->fetchAll();
foreach($result as &$user){
	$udid = $user["udid"];
	$xx++;
	$pplstring .= "1:".$user["userName"].":2:".$user["userID"].":13:".":6:".$xx.":9:".$user["icon"].":10:".$user["color1"].":11:".$user["color2"].":3:".$user["stars"].":8:".round($user["cp"],0,PHP_ROUND_HALF_DOWN).":4:".$user["demons"].":7:".$udid.":12:".$user["ship"]."|";
}
$pplstring = substr($pplstring, 0, -1);
echo $pplstring;
?>
?>
