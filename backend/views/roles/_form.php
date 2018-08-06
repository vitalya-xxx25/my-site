<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Roles */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="roles-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'roles-active-form',
            'data-roles_id' => $model->id
        ]
    ]); ?>

    <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textArea(['maxlength' => true]) ?>

    <?= $form->field($model, 'active')->checkbox(['value' => '1', 'checked ' => true]) ?>

    <?= $form->field($model, 'trash')->checkbox(['value' => '1']) ?>

    <? if (!empty($permissions) && !$model->trash) : ?>
        <div class="panel panel-default">
            <div class="panel-heading">Права</div>
            <div class="panel-body">
                <ul class="list-group roles-list-group">
                    <? foreach ($permissions as $permission) : ?>
                        <li data-id="<?=$permission->id?>" class="list-group-item">
                            <span class="roles-list-item-name"><?=$permission->name .' ('. $permission->description .')'?></span>
                            <button type="button" class="btn btn-success btn-sm remove-role-btn <?=(!$permission->roles ? 'hidden' : '')?>">установленно</button>
                            <button type="button" class="btn btn-sm add-role-btn <?=($permission->roles ? 'hidden' : '')?>">добавить</button>
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
