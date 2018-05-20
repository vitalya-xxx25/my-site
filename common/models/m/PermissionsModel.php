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
        $rows = self::find()
            ->select(['id', 'name'])
            ->where([
                'active' => 1,
                'trash' => 0
            ])
            ->all();

        return ArrayHelper::map($rows, 'id', 'name');
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