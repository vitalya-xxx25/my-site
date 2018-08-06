<?php

namespace backend\models;

use common\models\m\PermissionsModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Permissions;

/**
 * PermissionsSearch represents the model behind the search form of `common\models\Permissions`.
 */
class PermissionsSearch extends Permissions
{
    public $roles_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'active', 'trash', 'roles_id'], 'integer'],
            [['name', 'key', 'description'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PermissionsModel::find()
            ->alias('self')
            ->joinWith('roles');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'self.id' => $this->id,
            'self.active' => $this->active,
            'self.trash' => 0,
        ]);

        $query->andFilterWhere(['like', 'self.name', $this->name])
            ->andFilterWhere(['like', 'self.key', $this->key])
            ->andFilterWhere(['like', 'self.description', $this->description]);

        if ($this->roles_id) {
            $query->andWhere(['roles.id' => $this->roles_id]);
        }

        return $dataProvider;
    }
}
