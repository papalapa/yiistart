<?php

    namespace papalapa\yiistart\modules\partners\models;

    use yii\base\Model;
    use yii\data\ActiveDataProvider;

    /**
     * Class PartnersSearch
     * @package papalapa\yiistart\modules\partners\models
     */
    class PartnersSearch extends Partners
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
                [['order'], 'integer'],
                [['is_active'], 'boolean'],
                [['url', 'alt', 'title'], 'safe'],
            ];
        }

        /**
         * Creates data provider instance with search query applied
         * @param array $params
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = Partners::find()->multilingual();

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
                'order'     => $this->order,
                'is_active' => $this->is_active,
            ]);

            $query->andFilterWhere(['like', 'url', $this->url])
                  ->andFilterWhere(['like', 'alt', $this->alt])
                  ->andFilterWhere(['like', 'title', $this->title]);

            return $dataProvider;
        }
    }
