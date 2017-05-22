<?php

    namespace papalapa\yiistart\modules\images\models;

    use yii\base\Model;
    use yii\data\ActiveDataProvider;

    /**
     * Class ImageCategorySearch
     * @package papalapa\yiistart\modules\images\models
     */
    class ImageCategorySearch extends ImageCategory
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
                [['alias', 'name'], 'string'],
            ];
        }

        /**
         * Creates data provider instance with search query applied
         * @param array $params
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = ImageCategory::find();

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

            $query->with('images');

            return $dataProvider;
        }
    }
