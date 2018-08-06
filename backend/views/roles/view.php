<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Roles */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="roles-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= !empty(Yii::$app->controller->btns['update']) ? Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : '' ?>
        <?= !empty(Yii::$app->controller->btns['delete']) ? Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) : '' ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'key',
            'name',
            'description',
            [
                'attribute'=>'active',
                'value'=>function($model) {
                    return $model->active ? 'Активно' : 'Не активно';
                },
            ],
            [
                'attribute'=>'trash',
                'value'=>function($model) {
                    return $model->trash ? 'Удален' : '';
                },
            ],
        ],
    ]) ?>

    <? if (!empty($permissions)) : ?>
        <div class="panel panel-default">
            <div class="panel-heading">Роли</div>
            <div class="panel-body">
                <ul class="list-group roles-list-group">
                    <? foreach ($permissions as $permission) : ?>
                        <li data-id="<?=$permission->id?>" class="list-group-item">
                            <span class="roles-list-item-name"><?=$permission->name .' ('. $permission->description .')'?></span>
                        </li>
                    <? endforeach; ?>
                </ul>
            </div>
        </div>
    <? endif; ?>

</div>
