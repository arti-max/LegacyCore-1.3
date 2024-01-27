<?php
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/Lib.php";
$Lib = new Lib();

$udid = $_POST["udid"];
$userName = $_POST["userName"];
$secret = $_POST["secret"];

$query = db->prepare("UPDATE users SET userName=:userName WHERE udid=:udid");
$query->execute([':userName' => $userName, ':udid' => $udid]);

echo $userName;

?>
