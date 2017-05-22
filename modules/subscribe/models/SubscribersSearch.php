<?php

    namespace papalapa\yiistart\modules\subscribe\models;

    use yii\base\Model;
    use yii\data\ActiveDataProvider;

    /**
     * Class SubscribersSearch
     * @package papalapa\yiistart\modules\subscribe\models
     */
    class SubscribersSearch extends Subscribers
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
                [['id'], 'integer'],
                [['status'], 'in', 'range' => array_keys(Subscribers::statuses())],
                [['email'], 'safe'],
            ];
        }

        /**
         * Creates data provider instance with search query applied
         * @param array $params
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = Subscribers::find();

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
                'id'     => $this->id,
                'status' => $this->status,
            ]);

            $query->andFilterWhere(['like', 'email', $this->email]);

            return $dataProvider;
        }
    }
