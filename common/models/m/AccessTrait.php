<?php
namespace common\models\m;


trait AccessTrait {

    /**
     * Проверка роли пользователя
     *
     * @param $role // [key] => super_admin
     * @return bool
     */
    public function hasRole($role) {
        $status = false;

        if (!empty($this->roles)) {
            $rolesKeys = array_map(function($v) {
                return strtolower($v->key);
            }, $this->roles);

            if (in_array($role, $rolesKeys)) {
                $status = true;
            }
        }

        return $status;
    }

    /**
     * Проверка прав пользователя
     *
     * @param $perm // [key] => roles_view
     * @return bool
     */
    public function hasPermission($perm) {
        $status = false;

        if (!empty($this->roles->permissions)) {
            $permissionsKeys = array_map(function($v) {
                return strtolower($v->key);
            }, $this->roles->permissions);

            if (in_array($perm, $permissionsKeys)) {
                $status = true;
            }
        }

        return $status;
    }
}