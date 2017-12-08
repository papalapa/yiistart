<?php

    namespace papalapa\yiistart\modules\pages\models;

    use yii\base\Model;
    use yii\data\ActiveDataProvider;

    /**
     * Class PagesSearch
     * @package papalapa\yiistart\modules\pages\models
     */
    class PagesSearch extends Pages
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
                [['image'], 'boolean'],
                [['title', 'header', 'url'], 'safe'],
            ];
        }

        /**
         * Creates data provider instance with search query applied
         * @param array $params
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = Pages::find()->multilingual();

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
                'is_active' => $this->is_active,
            ]);

            if (!is_null($this->image) && $this->image !== '') {
                if ($this->image) {
                    $query->andWhere(['AND', ['!=', 'image', ''], ['IS NOT', 'image', null]]);
                } else {
                    $query->andWhere(['OR', ['=', 'image', ''], ['IS', 'image', null]]);
                }
            }

            $query->andFilterWhere(['like', 'header', $this->header]);
            $query->andFilterWhere(['like', 'url', $this->url]);

            return $dataProvider;
        }
    }
