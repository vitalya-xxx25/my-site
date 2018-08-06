<?php

namespace app\models;

use common\models\m\RolesModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Roles;

/**
 * RolesSearch represents the model behind the search form of `common\models\Roles`.
 */
class RolesSearch extends Roles
{
    public $permission_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'active', 'trash', 'permission_id'], 'integer'],
            [['name', 'description'], 'safe'],
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
        $query = RolesModel::find()
            ->alias('self')
            ->joinWith('permissions');

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
            ->andFilterWhere(['like', 'self.description', $this->description]);

        if (!empty($this->permission_id)) {
            $query->andWhere(['permissions.id' => $this->permission_id]);
        }

        return $dataProvider;
    }
}
