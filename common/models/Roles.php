<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "roles".
 *
 * @property int $id
 * @property string $name
 * @property string $key
 * @property string $description
 * @property int $active
 * @property int $trash
 *
 * @property Roles2permissions[] $roles2permissions
 * @property Permissions[] $permissions
 * @property User2roles[] $user2roles
 */
class Roles extends \yii\db\ActiveRecord
{
    public $selected;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'key'], 'required'],
            [['active', 'trash'], 'integer'],
            [['name', 'key'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'key' => Yii::t('app', 'Key'),
            'description' => Yii::t('app', 'Description'),
            'active' => Yii::t('app', 'Active'),
            'trash' => Yii::t('app', 'Trash'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles2permissions()
    {
        return $this->hasMany(Roles2permissions::className(), ['role_id' => 'id'])
            ->andOnCondition([
                'roles2permissions.active' => 1,
                'roles2permissions.trash' => 0
            ])
            ->alias('roles2permissions');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissions()
    {
        return $this->hasMany(Permissions::className(), ['id' => 'permission_id'])
            ->viaTable('roles2permissions', ['role_id' => 'id'], function($query) {
                $query->andOnCondition([
                    'roles2permissions.active' => 1,
                    'roles2permissions.trash' => 0
                ]);
            })
            ->alias('permissions')
            ->andOnCondition([
                'permissions.active' => 1,
                'permissions.trash' => 0
            ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser2roles()
    {
        return $this->hasMany(User2roles::className(), ['role_id' => 'id']);
    }
}
