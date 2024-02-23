<?php
class Commands {

 
    public static function doCommands($udid, $comment, $levelID) {

		include dirname(__FILE__)."/../lib/connection.php";
		require_once "../lib/Lib.php";
		$gs = new Lib();

		$commentarray = explode(' ', $comment);
        //COMMANDS
        if(substr($comment,0,9) == '!featured'){
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
