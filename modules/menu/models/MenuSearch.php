<?php

    namespace papalapa\yiistart\modules\menu\models;

    use yii\data\ActiveDataProvider;

    /**
     * Class MenuSearch
     * @package papalapa\yiistart\modules\menu\models
     */
    class MenuSearch extends Menu
    {
        /**
         * @inheritdoc
         */
        public function scenarios()
        {
            // bypass scenarios() implementation in the parent class
            return Menu::scenarios();
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['is_active', 'sort'], 'integer'],
                [['position'], 'in', 'range' => Menu::positions()],
                [['title', 'url'], 'safe'],
            ];
        }

        /**
         * Creates data provider instance with search query applied
         * @param array $params
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = Menu::find()->multilingual();

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
                'is_active' => $this->is_active,
                'sort'      => $this->sort,
                'position'  => $this->position,
            ]);

            $query->andFilterWhere(['like', 'title', $this->title])
                  ->andFilterWhere(['like', 'url', $this->url]);

            return $dataProvider;
        }
    }
