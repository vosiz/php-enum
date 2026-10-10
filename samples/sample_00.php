<?php

use Vosiz\Utils\Collections\Dictionary;
use Vosiz\Enums\Enum;

require_once(__DIR__.'/../src/enum.php');

class RoleEnum extends Enum {

    public static function Init(): void {

        $vals = [
            'admin' => 1,
            'user'  => 2,
        ];
        self::AddValues($vals);
    } 
}

class User {

    private $Roles;

    public function __construct() {

        $this->Roles = new Dictionary();
    } 

    public function GetRoles() {

        return $this->Roles;
    }

    private function AddRole(RoleEnum $role) {

        $this->Roles->Add($role);
    }
}

// Tests
function EnumTest_SetAndGetEnums() {

    $user = new User();
    $admin = RoleEnum::GetEnumVal('admin');

    return $admin;
}

function EnumTest_CompareExpl() {

    $user = new User();
    $admin = RoleEnum::GetEnumVal('admin');

    return $admin == RoleEnum::GetEnumVal('user');
}
