<?php

    namespace papalapa\yiistart\modules\menu\models;

    use yii\base\Model;
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
            return Model::scenarios();
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['order'], 'integer'],
                [['parent_id'], 'integer'],
                [['is_active', 'is_static', 'image'], 'boolean'],
                [['position'], 'in', 'range' => array_keys(Menu::positions())],
                [['name', 'url', 'css_class', 'template'], 'safe'],
            ];
        }

        /**
         * Creates data provider instance with search query applied
         * @param array $params
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = Menu::find()->multilingual()->with('parent');

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
                'is_static' => $this->is_static,
                'parent_id' => $this->parent_id,
                'order'     => $this->order,
                'position'  => $this->position,
            ]);

            if (!is_null($this->image) && $this->image !== '') {
                if ($this->image) {
                    $query->andWhere(['AND', ['!=', 'image', ''], ['IS NOT', 'image', null]]);
                } else {
                    $query->andWhere(['OR', ['=', 'image', ''], ['IS', 'image', null]]);
                }
            }

            $query->andFilterWhere(['like', 'name', $this->name])
                  ->andFilterWhere(['like', 'css_class', $this->css_class])
                  ->andFilterWhere(['like', 'template', $this->template])
                  ->andFilterWhere(['like', 'url', $this->url]);

            return $dataProvider;
        }
    }
