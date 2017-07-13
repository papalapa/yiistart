<?php

    namespace papalapa\yiistart\modules\banners\models;

    use yii\base\Model;
    use yii\data\ActiveDataProvider;

    /**
     * BannersCategorySearch represents the model behind the search form about `papalapa\yiistart\modules\banners\models\BannersCategory`.
     */
    class BannersCategorySearch extends BannersCategory
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
                [['is_visible', 'is_active'], 'boolean'],
                [['alias', 'name'], 'safe'],
            ];
        }

        /**
         * Creates data provider instance with search query applied
         * @param array $params
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = BannersCategory::find();

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
                'id'         => $this->id,
                'is_visible' => $this->is_visible,
                'is_active'  => $this->is_active,
            ]);

            $query->andFilterWhere(['like', 'alias', $this->alias])
                  ->andFilterWhere(['like', 'name', $this->name]);

            return $dataProvider;
        }
    }
