<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'email:email',
            [
                'attribute'=>'created_at',
                'content'=>function($data) {
                    return date('d.m.Y H:m:i', $data->created_at);
                },
                'filter' => DateRangePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'createTimeRange',
                    'convertFormat'=>true,
                    'startAttribute'=>'createTimeStart',
                    'endAttribute'=>'createTimeEnd',
                    'pluginOptions'=>[
                        'timePicker'=>true,
                        'timePickerIncrement'=>30,
                        'locale'=>[
                            'format'=>'Y-m-d h:i A'
                        ]
                    ]
                ]),
            ],
            /*[
                'attribute'=>'updated_at',
                'content'=>function($data) {
                    return date('d.m.Y H:m:i', $data->updated_at);
                },
                'filter' => DateRangePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'updateTimeRange',
                    'convertFormat'=>true,
                    'startAttribute'=>'updateTimeStart',
                    'endAttribute'=>'updateTimeEnd',
                    'pluginOptions'=>[
                        'timePicker'=>true,
                        'timePickerIncrement'=>30,
                        'locale'=>[
                            'format'=>'Y-m-d h:i A'
                        ]
                    ]
                ]),
            ],*/

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
