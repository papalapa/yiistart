<?php

    namespace papalapa\yiistart\modules\images\models;

    use papalapa\yiistart\models\BaseUser;
    use yii\data\ActiveDataProvider;

    /**
     * Class ImagesSearch
     * @package papalapa\yiistart\modules\images\models
     */
    class ImagesSearch extends Images
    {
        /**
         * @inheritdoc
         */
        public function scenarios()
        {
            // bypass scenarios() implementation in the parent class
            return Images::scenarios();
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['id', 'order'], 'integer'],
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
            $query = Images::find()->multilingual();

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

            if (\Yii::$app->user->identity->role <> BaseUser::ROLE_DEVELOPER) {
                $query->joinWith([
                    'category' => function ($q) /* @var $q \yii\db\ActiveQuery */ {
                        return $q->from(['{{CATEGORY}}' => ImageCategory::tableName()])
                                 ->andWhere(['{{CATEGORY}}.[[is_visible]]' => true]);
                    },
                ]);
            }

            $query->with('category');

            return $dataProvider;
        }
    }