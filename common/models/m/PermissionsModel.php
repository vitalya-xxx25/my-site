<?php

namespace common\models\m;

use common\models\m\DecoratorTrait;
use common\models\Permissions;
use common\models\Roles;

class PermissionsModel extends Permissions
{
    use DecoratorTrait;

    public function init() {
        $this->_decorators = [
            Roles::className() => RolesModel::className(),
        ];
    }
}