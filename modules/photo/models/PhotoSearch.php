<?php

    namespace papalapa\yiistart\modules\photo\models;

    use yii\base\Model;
    use yii\data\ActiveDataProvider;

    /**
     * Class PhotoSearch
     * @package papalapa\yiistart\modules\photo\models
     */
    class PhotoSearch extends Photo
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
                [['id', 'index_number'], 'integer'],
                [['is_active'], 'boolean'],
            ];
        }

        /**
         * Creates data provider instance with search query applied
         * @param array $params
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = Photo::find()->multilingual();

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
                'id'           => $this->id,
                'index_number' => $this->index_number,
                'is_active'    => $this->is_active,
            ]);

            return $dataProvider;
        }
    }
