<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user2roles".
 *
 * @property int $id
 * @property int $user_id
 * @property int $role_id
 * @property string $date_create
 * @property string $date_modify
 * @property int $modify_user_id
 * @property int $active
 * @property int $trash
 *
 * @property Roles $role
 * @property User $user
 * @property User $modifyUser
 */
class User2roles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user2roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'role_id', 'modify_user_id'], 'required'],
            [['user_id', 'role_id', 'modify_user_id', 'active', 'trash'], 'integer'],
            [['date_create', 'date_modify'], 'safe'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::className(), 'targetAttribute' => ['role_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['modify_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modify_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'role_id' => Yii::t('app', 'Role ID'),
            'date_create' => Yii::t('app', 'Date Create'),
            'date_modify' => Yii::t('app', 'Date Modify'),
            'modify_user_id' => Yii::t('app', 'Modify User ID'),
            'active' => Yii::t('app', 'Active'),
            'trash' => Yii::t('app', 'Trash'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Roles::className(), ['id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifyUser()
    {
        return $this->hasOne(User::className(), ['id' => 'modify_user_id']);
    }
}
