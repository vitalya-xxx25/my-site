<?php
namespace common\components;

use yii\web\Controller;

class OnlyAuthController extends Controller {
    public $closedActions = [];

    protected $userModel;
    public $btns;

    public function beforeAction($action) {

        // проверка на авторизацию
        if (\Yii::$app->user->isGuest || !($this->userModel = \Yii::$app->user->userModel)) {
            $this->redirect(\Yii::$app->user->loginUrl);
            return false;
        }

        $this->btns = [
            'view' => $this->userModel->hasPermission($action->controller->id . '_view'),
            'update' => $this->userModel->hasPermission($action->controller->id . '_update'),
            'delete' => $this->userModel->hasPermission($action->controller->id . '_delete'),
        ];

        $key = $action->controller->id . '_' . $action->id;

        // есть закрытые экшены в контроллере
        if (!empty($this->closedActions)) {
            $controllerId = $action->controller->id;

            $this->closedActions = array_map(function($v) use ($controllerId) {
                return strtolower($controllerId . '_' . $v);
            }, $this->closedActions);

            if (in_array(strtolower($key), $this->closedActions)) {
                // у юзера нет доступа к этому экшену
                if (!$this->userModel->hasPermission($key)) {
                    $this->redirect('/');
                    return false;
                }
            }
        }

        return parent::beforeAction($action);
    }
}