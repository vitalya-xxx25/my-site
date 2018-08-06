<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;
use kartik\daterange\DateRangeBehavior;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class UserSearch extends User
{
    public $createTimeRange;
    public $createTimeStart;
    public $createTimeEnd;

    public $updateTimeRange;
    public $updateTimeStart;
    public $updateTimeEnd;

    public $userRole;

    public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::className(),
                'attribute' => 'createTimeRange',
                'dateStartAttribute' => 'createTimeStart',
                'dateEndAttribute' => 'createTimeEnd',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'userRole'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email'], 'safe'],
            [['createTimeRange'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
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
        $query = User::find()
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
            'self.status' => $this->status,
            'self.created_at' => $this->created_at,
            'self.updated_at' => $this->updated_at,
            'self.roles.id' => $this->userRole,
        ]);

        $query->andFilterWhere(['like', 'self.username', $this->username])
/*            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])*/
            ->andFilterWhere(['like', 'self.email', $this->email]);

        $query->andFilterWhere(['>=', 'self.created_at', $this->createTimeStart])
            ->andFilterWhere(['<', 'self.created_at', $this->createTimeEnd]);

        /*$query->andFilterWhere(['>=', 'updated_at', $this->updateTimeStart])
            ->andFilterWhere(['<', 'updated_at', $this->updateTimeEnd]);*/

        return $dataProvider;
    }
}
