<?php

    namespace papalapa\yiistart\modules\banners\models;

    use papalapa\yiistart\modules\users\models\BaseUser;
    use yii\base\Model;
    use yii\data\ActiveDataProvider;

    /**
     * Class BannersSearch
     * @package papalapa\yiistart\modules\banners\models
     */
    class BannersSearch extends Banners
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
                [['id', 'category_id', 'order'], 'integer'],
                [['is_active'], 'boolean'],
                [['title', 'link'], 'safe'],
            ];
        }

        /**
         * Creates data provider instance with search query applied
         * @param array $params
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = Banners::find()->multilingual();

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
                'order'       => $this->order,
                'is_active'   => $this->is_active,
                'category_id' => $this->category_id,
            ]);

            if (\Yii::$app->user->identity->role <> BaseUser::ROLE_DEVELOPER) {
                $query->joinWith([
                    'category' => function ($q) /* @var $q \yii\db\ActiveQuery */ {
                        return $q->from(['{{CATEGORY}}' => BannersCategory::tableName()])
                                 ->andWhere(['{{CATEGORY}}.[[is_visible]]' => true]);
                    },
                ]);
            }

            $query->andFilterWhere(['like', 'title', $this->title])
                  ->andFilterWhere(['like', 'link', $this->link]);

            $query->with('category');

            return $dataProvider;
        }
    }
