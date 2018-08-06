<?php

namespace backend\controllers;

use backend\assets\RolesAsset;
use common\components\OnlyAuthController;
use common\models\m\PermissionsModel;
use Yii;
use common\models\Roles;
use app\models\RolesSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RolesController implements the CRUD actions for Roles model.
 */
class RolesController extends OnlyAuthController
{
    public $closedActions = [
        'Index',
        'View',
        'Create',
        'Update',
        'Delete',
    ];

    public function beforeAction($action) {
        RolesAsset::register($this->view);
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
     * Lists all Roles models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RolesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'permissionList' => PermissionsModel::getList(),
        ]);
    }

    /**
     * Displays a single Roles model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $permissions = PermissionsModel::find()
            ->alias('self')
            ->innerJoinWith(['roles' => function($query) use ($id) {
                $query->andOnCondition(['roles.id' => $id]);
            }])
            ->where([
                'self.active' => 1,
                'self.trash' => 0
            ])
            ->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'permissions' => $permissions
        ]);
    }

    /**
     * Creates a new Roles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Roles();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Roles model.
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

        $permissions = PermissionsModel::find()
            ->alias('self')
            ->with(['roles' => function($query) use ($id) {
                $query->where(['roles.id' => $id]);
            }])
            ->where([
                'self.active' => 1,
                'self.trash' => 0
            ])
            ->all();

        return $this->render('update', [
            'model' => $model,
            'permissions' => $permissions
        ]);
    }

    /**
     * Deletes an existing Roles model.
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
     * Finds the Roles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Roles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Roles::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
