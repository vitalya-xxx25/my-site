<?php
namespace common\models\m;

use common\models\Roles;
use common\models\User;
use common\models\m\AccessTrait;
use common\models\m\DecoratorTrait;

class UserModel extends User {

    use AccessTrait;
    use DecoratorTrait;

    public function init() {
        $this->_decorators = [
            Roles::className() => RolesModel::className(),
        ];
    }

}