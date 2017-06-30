<?php

    namespace papalapa\yiistart\modules\users\models;

    use yii\base\Model;
    use yii\data\ActiveDataProvider;

    /**
     * Class UserSearch
     * @package papalapa\yiistart\modules\users\models
     */
    class UserSearch extends User
    {
        /**
         * @inheritdoc
         */
        public function scenarios()
        {
            // bypass scenarios() implementation in the parent class
            return Model::scenarios();
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['email'], 'safe'],
                [['status', 'role'], 'integer'],
                [['activity_at'], 'date', 'format' => 'yyyy-mm-dd'],
                [['last_ip'], 'ip', 'ipv4' => true],
            ];
        }

        /**
         * Creates data provider instance with search query applied
         * @param array $params
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = User::find();

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
                'status' => $this->status,
                'role'   => $this->role,
            ]);

            if (isset($this->last_ip) && $this->last_ip !== '') {
                $query->andWhere(['last_ip' => ip2long($this->last_ip)]);
            }

            $query->andFilterWhere(['like', 'activity_at', $this->activity_at]);
            $query->andFilterWhere(['like', 'email', $this->email]);

            return $dataProvider;
        }
    }
