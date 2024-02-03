<?php
class Commands {

    public static function Commands($udid, $comment, $levelID) {

		include dirname(__FILE__)."/../lib/connection.php";
		require_once "../lib/Lib.php";
		$gs = new Lib();
		$commentarray = explode(' ', $comment);
		$uploadDate = time();

        $query2 = $db->prepare("SELECT udid FROM levels WHERE levelID = :id");
		$query2->execute([':id' => $levelID]);
		$targetUDID = $query2->fetchColumn();

        if(substr($comment,0,9) == '!featured' AND $gs->checkPermission($udid)){
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
}


}

?>
