<?php

namespace backend\controllers;

use backend\assets\PermissionsAsset;
use common\models\m\RolesModel;
use common\models\Roles;
use common\models\Roles2Permissions;
use Yii;
use common\models\Permissions;
use backend\models\PermissionsSearch;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * PermissionsController implements the CRUD actions for Permissions model.
 */
class PermissionsController extends Controller
{
    public function beforeAction($action) {
        PermissionsAsset::register($this->view);
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
     * Lists all Permissions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PermissionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'rolesList' => RolesModel::getList()
        ]);
    }

    /**
     * Displays a single Permissions model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $roles = Roles::find()
            ->alias('self')
            ->innerJoinWith(['permissions' => function($query) use ($id) {
                $query->andOnCondition(['permissions.id' => $id]);
            }])
            ->where([
                'self.active' => 1,
                'self.trash' => 0
            ])
            ->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'roles' => $roles
        ]);
    }

    /**
     * Creates a new Permissions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Permissions();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Permissions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $roles = Roles::find()
            ->alias('self')
            ->with(['permissions' => function($query) use ($id) {
                $query->where(['permissions.id' => $id]);
            }])
            ->where([
                'self.active' => 1,
                'self.trash' => 0
            ])
            ->all();

        return $this->render('update', [
            'model' => $model,
            'roles' => $roles
        ]);
    }

    /**
     * Deletes an existing Permissions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->trash = 1;
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Permissions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Permissions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Permissions::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Привязка ролей к правам
     *
     * @return array
     */
    public function actionSetRole() {
        if (\Yii::$app->request->isAjax && !Yii::$app->user->isGuest) {
            $action = \Yii::$app->request->post('action');
            $roleId = \Yii::$app->request->post('roleId');
            $permissionId = \Yii::$app->request->post('permissionId');
            $result = [];

            try {
                if ($action && $roleId && $permissionId) {
                    $roleExists = Roles::find()
                        ->where([
                            'id' => $roleId,
                            'active' => 1,
                            'trash' => 0
                        ])
                        ->exists();

                    $permissionExists = Permissions::find()
                        ->where([
                            'id' => $permissionId,
                            'active' => 1,
                            'trash' => 0
                        ])
                        ->exists();

                    if ($roleExists && $permissionExists) {
                        $model = Roles2Permissions::find()
                            ->where([
                                'role_id' => $roleId,
                                'permission_id' => $permissionId,
                            ])
                            ->one();

                        switch ($action) {
                            case 'add' :
                                if (!$model) {
                                    $model = new Roles2Permissions([
                                        'role_id' => $roleId,
                                        'permission_id' => $permissionId,
                                    ]);
                                }
                                else {
                                    $model->active = 1;
                                    $model->trash = 0;
                                }

                                $model->save();
                                $result = ['success' => 1, 'action' => 'add'];
                                break;

                            case 'remove' :
                                if ($model) {
                                    $model->active = 0;
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
}
