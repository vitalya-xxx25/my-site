<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'user-active-form',
            'data-user_id' => $model->id
        ]
    ]); ?>

    <?php echo $form->errorSummary($model); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->checkbox(['value' => User::STATUS_ACTIVE, 'checked ' => true]) ?>

    <? if (!empty($roles) && $model->status == User::STATUS_ACTIVE) : ?>
        <div class="panel panel-default">
            <div class="panel-heading">Роли</div>
            <div class="panel-body">
                <ul class="list-group roles-list-group">
                    <? foreach ($roles as $role) : ?>
                        <li data-id="<?=$role->id?>" class="list-group-item">
                            <span class="roles-list-item-name"><?=$role->name?></span>
                            <button type="button" class="btn btn-success btn-sm remove-role-btn <?=((!empty($model->roles) && $role->id == $model->roles->id) ? '' : 'hidden')?>">установленно</button>
                            <button type="button" class="btn btn-sm add-role-btn <?=((empty($model->roles) || !empty($model->roles) && $role->id != $model->roles->id) ? '' : 'hidden')?>">добавить</button>
                        </li>
                    <? endforeach; ?>
                </ul>
            </div>
        </div>
    <? endif; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
