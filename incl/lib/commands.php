<?php

class Commands {

    private static function getUserID($udid) {
        include "connection.php";

        $query = $db->prepare("SELECT userID FROM users WHERE udid=:udid");
        $query->execute([':udid' => $udid]);
        $userID = $query->fetchColumn();

        return $userID;

    }

    private static function getRoleID($userID) {
        include "connection.php";

        $query = $db->prepare("SELECT roleID FROM users WHERE userID=:userID");
        $query->execute([':userID' => $userID]);
        $roleID = $query->fetchColumn();

        return $roleID;

    }
    
    private static function getCreatorID($levelID) {
        include "connection.php";

        $query = $db->prepare("SELECT userID FROM levels WHERE levelID=:levelID");
        $query->execute([':levelID' => $levelID]);
        $userID = $query->fetchColumn();

        return $userID;
    }

    private static function updateUserCP($userID, $cp) {
        include "connection.php";

        $query = $db->prepare("UPDATE users SET cp=cp+$cp WHERE userID=:userID");
        $query->execute([':userID' => $userID]);

    }

    public static function command($udid, $comment, $levelID) {
        include "connection.php";
        require_once "roles.php";

        $userID = self::getUserID($udid);
        $roleID = self::getRoleID($userID);
        $creatorID = self::getCreatorID($levelID);
        echo "$userID::$roleID::$creatorID";

        $commentarr = explode(' ', $comment);

        if(substr($comment,0,5) == "!rate" and Roles::getPermissions($roleID, 'setStars')) {
            $stars = $commentarr[1];
            $isDemon = 0;
            $CP = -1;
            $diff = 0;
            $stars > 0 ? $CP = 1 : $CP = -1;

            switch ($stars) {
                case 0:
                    $CP = -1;
                    break;
                case 1:
                    if ($VERSION > 1) {
                        $diff = 60;
                    } else {
                        $diff = 10;
                    }
                    break;
                case 2:
                    $diff = 10;
                    break;
                case 3:
                    $diff = 20;
                    break;
                case 4:
                case 5:
                    $diff = 30;
                    break;
                case 6:
                case 7:
                    $diff = 40;
                    break;
                case 8:
                case 9:
                    $diff = 50;
                    break;
                case 10:
                    $diff = 50;
                    $isDemon = 1;
            }

            $query = $db->prepare("UPDATE levels SET isStars=:stars, difficulty=:diff, isDemon=:demon, diffOverride=:diff WHERE levelID=:levelID");
            $query->execute([':levelID' => $levelID, ':stars' => $stars, ':diff' => $diff, ':demon' => $isDemon]);
            self::updateUserCP($creatorID, $CP);

            return true;
        }

        if (substr($comment,0,4) == "!req" and Roles::getPermissions($roleID, 'req')) {
            $stars = $commentarr[1];

            $query = $db->prepare("UPDATE levels SET reqStars=:stars WHERE levelID=:levelID");
            $query->execute([':stars' => $stars, ':levelID' => $levelID]);

            return true;
        }

        if (substr($comment, 0, 9) == "!unlisted" and Roles::getPermissions($roleID, 'unlisted') and $userID == $creatorID) {

            $query = $db->prepare("UPDATE levels SET unlisted=1 WHERE levelID=:levelID");
            $query->execute([':levelID' => $levelID]);

            return true;

        }

        if (substr($comment, 0, 9) == "!inlisted" and Roles::getPermissions($roleID, 'inlisted') and $userID == $creatorID) {

            $query = $db->prepare("UPDATE levels SET unlisted=0 WHERE levelID=:levelID");
            $query->execute([':levelID' => $levelID]);

            return true;

        }



        return false;


    }


}
