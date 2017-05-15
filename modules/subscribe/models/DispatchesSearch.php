<?php

    namespace papalapa\yiistart\modules\subscribe\models;

    use yii\base\Model;
    use yii\data\ActiveDataProvider;

    /**
     * Class DispatchesSearch
     * @package papalapa\yiistart\modules\subscribe\models
     */
    class DispatchesSearch extends Dispatches
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
                [['status'], 'in', 'range' => array_keys(Dispatches::statuses())],
                [['start_at'], 'date', 'format' => 'yyyy-mm-dd'],
                [['subject', 'html', 'text'], 'safe'],
            ];
        }

        /**
         * Creates data provider instance with search query applied
         * @param array $params
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = Dispatches::find();

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
                'id'       => $this->id,
                'start_at' => $this->start_at,
                'status'   => $this->status,
            ]);

            $query->andFilterWhere(['like', 'subject', $this->subject])
                  ->andFilterWhere(['like', 'html', $this->html])
                  ->andFilterWhere(['like', 'text', $this->text]);

            return $dataProvider;
        }
    }
