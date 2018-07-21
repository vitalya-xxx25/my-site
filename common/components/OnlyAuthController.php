<?php
namespace common\components;

use yii\web\Controller;

class OnlyAuthController extends Controller {
    public $closedActions = [];

    public function beforeAction($action) {

        // проверка на авторизацию
        if (\Yii::$app->user->isGuest) {
            $this->redirect(\Yii::$app->user->loginUrl);
            return false;
        }

        $key = $action->controller->id . '_' . $action->id;

        // есть закрытые экшены в контроллере
        if (!empty($this->closedActions)) {
            $controllerId = $action->controller->id;

            $this->closedActions = array_map(function($v) use ($controllerId) {
                return strtolower($controllerId . '_' . $v);
            }, $this->closedActions);

            if (in_array(strtolower($key), $this->closedActions)) {
                $userModel = \Yii::$app->user->userModel;

                // у юзера нет доступа к этому экшену
                if (!$userModel->hasPermission($key)) {
                    $this->redirect('/');
                    return false;
                }
            }
        }

        return parent::beforeAction($action);
    }
}