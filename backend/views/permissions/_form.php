<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Permissions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permissions-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'permissions-active-form',
            'data-permis_id' => $model->id
        ]
    ]); ?>

    <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textArea(['maxlength' => true]) ?>

    <?= $form->field($model, 'active')->checkbox(['value' => '1', 'checked ' => true]) ?>

    <?= $form->field($model, 'trash')->checkbox(['value' => '1']) ?>

    <? if (!empty($roles)) : ?>
        <div class="panel panel-default">
            <div class="panel-heading">Роли</div>
            <div class="panel-body">
                <ul class="list-group roles-list-group">
                    <? foreach ($roles as $role) : ?>
                        <li data-id="<?=$role->id?>" class="list-group-item">
                            <span class="roles-list-item-name"><?=$role->name?></span>
                            <button type="button" class="btn btn-success btn-sm remove-role-btn <?=(!$role->permissions ? 'hidden' : '')?>">установленно</button>
                            <button type="button" class="btn btn-sm add-role-btn <?=($role->permissions ? 'hidden' : '')?>">добавить</button>
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
