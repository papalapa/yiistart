<?php

    namespace papalapa\yiistart\modules\social\models;

    use yii\base\Model;
    use yii\data\ActiveDataProvider;

    /**
     * Class SocialSearch
     * @package papalapa\yiistart\modules\social\models
     */
    class SocialSearch extends Social
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
                [['id', 'order'], 'integer'],
                [['is_active'], 'boolean'],
                [['position'], 'in', 'range' => array_keys(self::positions())],
                [['name', 'url', 'image'], 'safe'],
            ];
        }

        /**
         * Creates data provider instance with search query applied
         *
         * @param array $params
         *
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = Social::find();

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
                'order'     => $this->order,
                'is_active' => $this->is_active,
            ]);

            $query->andFilterWhere(['like', 'name', $this->name])
                  ->andFilterWhere(['like', 'url', $this->url]);

            if (!is_null($this->image) && $this->image !== '') {
                if ($this->image) {
                    $query->andWhere(['AND', ['!=', 'image', ''], ['IS NOT', 'image', null]]);
                } else {
                    $query->andWhere(['OR', ['=', 'image', ''], ['IS', 'image', null]]);
                }
            }

            return $dataProvider;
        }
    }
