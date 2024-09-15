<?php

class Roles {

    public static function getPermissions($roleID, $permission) {
        include "connection.php";

        $query = $db->prepare("SELECT permissions FROM roles WHERE roleID=:roleID");
        $query->execute([':roleID' => $roleID]);
        $perm = $query->fetchColumn();

        $pa = explode(';', $perm);

        foreach ($pa as $p) {
            echo "$p ";
            if ($p == $permission) {
                return true;
            }
        }
        return false;
    }

    public static function getRoleName($roleID) {
        include "connection.php";

        $query = $db->prepare("SELECT name FROM roles WHERE roleID=:roleID");
        $query->execute([':roleID' => $roleID]);
        $name = $query->fetchColumn();

        return $name;

    }

}



// $roleID = $_POST['rid'];
// $perm = $_POST['perm'];

// $d = Roles::getPermissions($roleID, $perm);
// echo $d;
