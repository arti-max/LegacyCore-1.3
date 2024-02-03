<?php
chdir(dirname(__FILE__));
//error_reporting(0);
include "../lib/connection.php";
require_once "../lib/Lib.php";
$gs = new Lib();

$commentstring = "";
$userstring = "";
$users = array();

$isSpam = 0;
$secret = $_POST["secret"];



$page = $_POST['page'];


if(isset($_POST['levelID'])){
	$filterColumn = 'levelID';
	$filterToFilter = '';
	$displayLevelID = false;
	$filterID = $_POST["levelID"];
}
else
	exit(-1);

$countquery = "SELECT count(*) FROM comments WHERE levelID = :filterID";
$countquery = $db->prepare($countquery);
$countquery->execute([':filterID' => $filterID]);
$commentcount = $countquery->fetchColumn();
if($commentcount == 0){
	exit("-2");
}


$query = "SELECT comments.levelID, comments.commentID, comments.timestamp, comments.comment, comments.userID, comments.likes, users.userName, users.udid FROM comments LEFT JOIN users ON comments.userID = users.userID WHERE comments.levelID = :filterID ORDER BY comments.commentID DESC LIMIT 20 OFFSET $page";
$query = $db->prepare($query);
$query->execute([':filterID' => $filterID]);
$result = $query->fetchAll();
$visiblecount = $query->rowCount();

foreach($result as &$comment1) {
	if($comment1["commentID"]!=""){
		$uploadDate = date("d/m/Y G.i", $comment1["timestamp"]);
		$commentText = $comment1["comment"];
		if($displayLevelID) $commentstring .= "1~".$comment1["levelID"]."~";
		$commentstring .= "2~".$commentText."~3~".$comment1["userID"]."~4~".$comment1["likes"]."~5~0~7~".$isSpam."~9~".$uploadDate."~6~".$comment1["commentID"];
		if ($comment1['userName']) { //TODO: get rid of queries caused by getMaxValuePermission and getAccountCommentColor
			$udid = $comment1["udid"];
            if(!in_array($comment1["userID"], $users)){
				$users[] = $comment1["userID"];
				$userstring .=  $comment1["userID"] . ":" . $comment1["userName"] . ":" . $udid . "|";
            }
			$commentstring .= "|";
		}
	}
}

$commentstring = substr($commentstring, 0, -1);
echo $commentstring;
$userstring = substr($userstring, 0, -1);
echo "#$userstring";
echo "#${commentcount}:$page:${visiblecount}";
?>
