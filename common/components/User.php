<?php
namespace common\components;

class User extends \yii\web\User {

    public function getUserModel() {
        return \Yii::$app->user->identity;
    }
}
