<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\PermissionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Permissions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permissions-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Permissions'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'key',
            'description',
            [
                'attribute'=>'active',
                'filter' => [1 => 'Да', 0 => 'Нет'],
                'content'=>function($data) {
                    return $data->active ? 'Активно' : 'Не активно';
                },
            ],
            [
                'attribute'=>'roles_id',
                'label'=>'Роли',
                'format'=>'html',
                'content'=>function($data) {
                    if (!empty($data->roles)) {
                        foreach ($data->roles as $role) {
                            return $role->name . '<br>';
                        }
                    }
                },
                'filter' => $rolesList,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'visibleButtons' => [
                    'view' => true,
                    'update' => true,
                    'delete' => function($data) {
                        return ($data->trash == 0);
                    }
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
