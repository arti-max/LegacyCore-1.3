<?php
class Commands {
    public static function CheckPermission($udid) {
        if(is_numeric($udid)) return false;

		include __DIR__ . "/connection.php";
		//isAdmin check
		$query = $db->prepare("SELECT isAdmin FROM users WHERE udid = :udid");
		$query->execute([':udid' => $udid]);
		$isAdmin = $query->fetchColumn();
		if($isAdmin == 1){
			return 1;
		}
		
		return false;
    }
 
    public static function doCommands($udid, $comment, $levelID) {

		include dirname(__FILE__)."/../lib/connection.php";
		require_once "../lib/Lib.php";
		$gs = new Lib();

		$commentarray = explode(' ', $comment);

        $query = $db->prepare("SELECT udid FROM levels WHERE levelID = :levelID");
		$query->execute([':levelID' => $levelID]);
		$udid2 = $query->fetchColumn();
        //COMMANDS
        if(substr($comment,0,9) == '!featured' AND self::CheckPermission($udid)){
			$isFeatured = $commentarray[1];
            $FeaturePOS = $commentarray[2];

            if ($isFeatured == "") {
                $isFeatured = 0;
            }
            if ($FeaturePOS == "") {
                $FeaturePOS = 0;
            }

            $query = $db->prepare("UPDATE levels SET isFeatured=:isFeatured, F_POS=:F_POS WHERE levelID=:levelID");
            $query->execute([':isFeatured' => $isFeatured, ':F_POS' => $FeaturePOS, ':levelID' => $levelID]);

            return true;
        }
        if (substr($comment,0,5) == '!rate' AND self::CheckPermission($udid)){
            $stars = $commentarray[1];
            $isDemon = 0;
            $diff = 0;
            $isCP = 0;
            if ($stars >= 1) {
                $isCP = 1;
            }
            switch ($stars) {
                case 1:
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
                    break;
            }

            $query = $db->prepare("UPDATE levels SET isDemon=:isDemon, stars=:stars, difficulty=:diff WHERE levelID=:levelID");
            $query->execute([':isDemon' => $isDemon, ':stars' => $stars, ':diff' => $diff, ':levelID' => $levelID]);
            $query = $db->prepare("UPDATE users SET cp = cp+$isCP WHERE udid=:udid");
            $query->execute([':udid' => $udid2]);
            echo $stars;
            
            return true;
        }
        return false;
    }


}

?>
