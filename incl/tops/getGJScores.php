<?php
chdir(dirname(__FILE__));
//error_reporting(1);
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
	/*if($type == "relative"){
		$user = $result[0];
		$udid = $user["udid"];
		$e = "SET @row := 0;";
		$query = $db->prepare($e);
		$query->execute();
		$f = "SELECT rank, stars FROM (SELECT @rown := @row + 1 AS rank, stars, udid FROM users ORDER BY stars DESC) as result WHERE udid=:udid";
		$query = $db->prepare($f);
		$query->execute([':udid' => $udid]);
		$leaderboard = $query->fetchAll();
		//var_dump($leaderboard);
		$leaderboard = $leaderboard[0];
		$xx = $leaderboard["rank"] - 1;
	}*/
	foreach($result as &$user) {
		$udid = 0;
		$udid = $user["udid"];
		$xx++;
        $lbstring .= "1:".$user["userName"].":2:".$user["userID"].":6:".$xx.":9:".$user["icon"].":10:".$user["color1"].":11:".$user["color2"].":3:".$user["stars"].":8:".round($user["creatorPoints"],0,PHP_ROUND_HALF_DOWN).":4:".$user["demons"].":7:".$udid.":12:".$user["ship"]."|";
    }
}

if($lbstring == ""){
	exit("-1");
}
$lbstring = substr($lbstring, 0, -1);
echo $lbstring;
?>
