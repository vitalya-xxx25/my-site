<?php

namespace common\models\m;

use yii\helpers\ArrayHelper;
use common\models\Roles;

class RolesModel extends Roles
{
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
}