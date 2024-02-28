<?php
chdir(dirname(__FILE__));
include "../lib/connection.php";
$stars = 0;
$count = 0;
$xx = 0;
$lbstring = "";

$udid = $_POST["udid"];
$type = $_POST["type"];


if ($type != "") {
    if ($type == "top") {
        $query = $db->prepare("SELECT * FROM users WHERE stars > 0 ORDER BY stars DESC LIMIT 50");
        $query->execute();
    }
    if($type == "relative"){
        $query = $db->prepare("SELECT * FROM users WHERE udid = :udid");
        $query->execute([':udid' => $udid]);
        $result = $query->fetchAll();
        $user = $result[0];
        $stars = $user["stars"];
        //$query = $db->prepare("SELECT * FROM users WHERE stars <= :stars ORDER BY stars DESC LIMIT 25");
        $query = $db->prepare("SELECT A.* FROM ((SELECT * FROM users WHERE stars <= :stars ORDER BY stars DESC LIMIT 10) UNION (SELECT * FROM users WHERE stars >= :stars ORDER BY stars ASC LIMIT 10)) as A ORDER BY A.stars DESC");
        $query->execute([':stars' => $stars]);
    }
    $result = $query->fetchAll();
	foreach($result as &$user) {
		$udid = 0;
		if(is_numeric($user["udid"])){
			$udid = $user["udid"];
		}
		$xx++;
        $lbstring .= "1:".$user["userName"].":2:".$user["userID"].":6:".$xx.":9:".$user["icon"].":10:".$user["color1"].":11:".$user["color2"].":3:".$user["stars"].":8:".round($user["creatorPoints"],0,PHP_ROUND_HALF_DOWN).":4:".$user["demons"].":7:".$udid.":14:".$user["ship"]."|";
    }
}

if($lbstring == ""){
	exit("-1");
}
$lbstring = substr($lbstring, 0, -1);
echo $lbstring;
?>
