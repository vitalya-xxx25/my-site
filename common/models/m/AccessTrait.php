<?php
namespace common\models\m;


trait AccessTrait {

    public function hasRole($role) {
        return true;
    }

    public function hasPermission($perm) {
        return true;
    }
}