<?php

    namespace papalapa\yiistart\modules\elements\models;

    use yii\data\ActiveDataProvider;

    /**
     * Class ElementsSearch
     * @package papalapa\yiistart\modules\elements\models
     */
    class ElementsSearch extends Elements
    {
        /**
         * @inheritdoc
         */
        public function scenarios()
        {
            // bypass scenarios() implementation in the parent class
            return Elements::scenarios();
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['id', 'category_id'], 'integer'],
                [['is_active'], 'boolean'],
                [['format'], 'in', 'range' => array_keys(Elements::formats())],
                [['name', 'description'], 'safe'],
            ];
        }

        /**
         * Creates data provider instance with search query applied
         * @param array $params
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = Elements::find()->multilingual();

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
                'id'          => $this->id,
                'category_id' => $this->category_id,
                'is_active'   => $this->is_active,
                'format'      => $this->format,
            ]);

            $query->andFilterWhere(['like', 'name', $this->name])
                  ->andFilterWhere(['like', 'description', $this->description]);

            $query->with('category');

            return $dataProvider;
        }
    }
