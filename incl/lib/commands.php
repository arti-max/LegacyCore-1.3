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
        require_once "../../bot/DSsend.php";
		$gs = new Lib();

		$commentarray = explode(' ', $comment);
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
        return false;
    }


}

?>
