<?php

namespace common\models\m;

use common\models\m\DecoratorTrait;
use common\models\Permissions;
use common\models\Roles;
use yii\helpers\ArrayHelper;

class PermissionsModel extends Permissions
{
    use DecoratorTrait;

    public function init() {
        $this->_decorators = [
            Roles::className() => RolesModel::className(),
        ];
    }

    public static function getList() {
        $list = [];
        $rows = self::find()
            ->select(['id', 'name', 'description'])
            ->where([
                'active' => 1,
                'trash' => 0
            ])
            ->all();

        if (!empty($rows)) {
            foreach ($rows as $r) {
                $list[$r['id']] = $r['name'] .' ('. $r['description'] .')';
            }
        }

        return $list;
    }

    public function getRolesList() {
        $roles = [];
        if (!empty($this->roles)) {
            foreach ($this->roles as $role) {
                 array_push($roles, $role->name);
            }
        }
        return implode('<br>', $roles);
    }
}