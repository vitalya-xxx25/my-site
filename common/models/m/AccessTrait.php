<?php
namespace common\models\m;


trait AccessTrait {

    private $_permissionsKeys;
    private $_rolesKeys;

    /**
     * Проверка роли пользователя
     *
     * @param $role // [key] => super_admin
     * @return bool
     */
    public function hasRole($role) {
        $status = false;

        if (!empty($this->roles)) {
            if (empty($this->rolesKeys)) {
                $this->_rolesKeys = array_map(function($v) {
                    return strtolower($v->key);
                }, $this->roles);
            }

            if (in_array($role, $this->_rolesKeys)) {
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
            if (empty($this->_permissionsKeys)) {
                $this->_permissionsKeys = array_map(function($v) {
                    return strtolower($v->key);
                }, $this->roles->permissions);
            }

            if (in_array($perm, $this->_permissionsKeys)) {
                $status = true;
            }
        }

        return $status;
    }
}