<?php

    namespace papalapa\yiistart\modules\i18n\models;

    use yii\base\Model;
    use yii\data\ActiveDataProvider;

    /**
     * SourceMessageCategoriesSearch represents the model behind the search form about
     * `papalapa\yiistart\modules\i18n\models\SourceMessageCategories`.
     */
    class SourceMessageCategoriesSearch extends SourceMessageCategories
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
                [['category', 'translate'], 'safe'],
            ];
        }

        /**
         * Creates data provider instance with search query applied
         * @param array $params
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = SourceMessageCategories::find();

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
            $query->andFilterWhere(['like', 'category', $this->category])
                  ->andFilterWhere(['like', 'translate', $this->translate]);

            return $dataProvider;
        }
    }
