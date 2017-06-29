<?php

    namespace papalapa\yiistart\modules\histories\models;

    use yii\base\Model;
    use yii\data\ActiveDataProvider;

    /**
     * Class HistoriesSearch
     * @package papalapa\yiistart\modules\histories\models
     */
    class HistoriesSearch extends Histories
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
                [['is_active'], 'boolean'],
                [['date'], 'date', 'format' => 'yyyy-mm-dd'],
                [['title', 'text'], 'safe'],
            ];
        }

        /**
         * Creates data provider instance with search query applied
         * @param array $params
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = Histories::find()->multilingual();

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
                'id'        => $this->id,
                'date'      => $this->date,
                'is_active' => $this->is_active,
            ]);

            $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'text', $this->text]);

            return $dataProvider;
        }
    }
