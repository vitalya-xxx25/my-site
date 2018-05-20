<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "permissions".
 *
 * @property int $id
 * @property string $name
 * @property string $key
 * @property string $description
 * @property int $active
 * @property int $trash
 *
 * @property Roles2permissions[] $roles2permissions
 * @property Roles[] $roles
 */
class Permissions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'permissions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'key'], 'required'],
            [['active', 'trash'], 'integer'],
            [['name', 'key', 'description'], 'string', 'max' => 50],
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
        return $this->hasMany(Roles2permissions::className(), ['permission_id' => 'id'])
            ->andWhere([
                'roles2permissions.active' => 1,
                'roles2permissions.trash' => 0
            ])
            ->alias('roles2permissions');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(Roles::className(), ['id' => 'role_id'])
            ->viaTable('roles2permissions', ['permission_id' => 'id'], function($query) {
                $query->andWhere([
                    'roles2permissions.active' => 1,
                    'roles2permissions.trash' => 0
                ]);
            })
            ->alias('roles')
            ->andWhere([
                'roles.active' => 1,
                'roles.trash' => 0
            ]);
    }
}
