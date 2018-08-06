<?php

namespace backend\controllers;

use backend\assets\UserAsset;
use common\components\OnlyAuthController;
use common\models\m\UserModel;
use common\models\Roles;
use common\models\User2roles;
use Yii;
use common\models\User;
use backend\models\UserSearch;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends OnlyAuthController
{
    public $closedActions = [
        'Index',
        'View',
        'Create',
        'Update',
        'Delete',
        'SetRole'
    ];

    public function beforeAction($action) {
        UserAsset::register($this->view);
        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $roles = Roles::find()
            ->select(['id', 'name'])
            ->asArray()
            ->all();

        return $this->render('index', [
            'roles' => ArrayHelper::map($roles, 'id', 'name'),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = UserModel::find()
            ->alias('self')
            ->with(['user2roles'])
            ->where(['self.id' => $id])
            ->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $roles = Roles::find()
            ->alias('self')
            ->where([
                'self.active' => 1,
                'self.trash' => 0
            ])
            ->all();

        return $this->render('update', [
            'model' => $model,
            'roles' => $roles,
        ]);
    }

    /**
     * Привязка ролей к пользователям
     *
     * @return array
     */
    public function actionSetRole() {
        if (\Yii::$app->request->isAjax && !Yii::$app->user->isGuest) {
            $action = \Yii::$app->request->post('action');
            $roleId = \Yii::$app->request->post('roleId');
            $userId = \Yii::$app->request->post('userId');
            $result = [];

            try {
                if ($action && $roleId && $userId) {
                    $roleExists = Roles::find()
                        ->where([
                            'id' => $roleId,
                            'active' => 1,
                            'trash' => 0
                        ])
                        ->exists();

                    $user = $this->findModel($userId);

                    if ($roleExists && $user) {
                        $model = User2roles::find()
                            ->where([
                                'role_id' => $roleId,
                                'user_id' => $user->id,
                            ])
                            ->one();

                        switch ($action) {
                            case 'add' :
                                \Yii::$app->db->createCommand()
                                    ->update(User2roles::tableName(), ['active' => 0], ['user_id' => $user->id,])
                                    ->execute();

                                if (!$model) {
                                    $model = new User2roles([
                                        'role_id' => $roleId,
                                        'user_id' => $user->id,
                                        'modify_user_id' => Yii::$app->user->id,
                                    ]);
                                }
                                else {
                                    $model->active = 1;
                                    $model->trash = 0;
                                    $model->modify_user_id = Yii::$app->user->id;
                                }

                                $model->save();
                                $result = ['success' => 1, 'action' => 'add'];
                                break;

                            case 'remove' :
                                if ($model) {
                                    $model->active = 0;
                                    $model->modify_user_id = Yii::$app->user->id;
                                    $model->save();
                                    $result = ['success' => 1, 'action' => 'remove'];
                                }
                                break;
                        }
                    }
                }
            }
            catch (Exception $e) {
                $result = ['error' => 1];
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
            exit();
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserModel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
