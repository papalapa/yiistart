<?php

    namespace papalapa\yiistart\modules\image\models;

    use papalapa\yiistart\modules\users\models\BaseUser;
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    use yii\db\ActiveQuery;

    /**
     * ImageSearch represents the model behind the search form about `papalapa\yiistart\modules\image\models\Image`.
     */
    class ImageSearch extends Image
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
                [['id', 'album_id', 'size', 'width', 'height', 'order', 'is_active', 'created_by', 'updated_by'], 'integer'],
                [['name', 'alt', 'title', 'text', 'caption', 'src', 'cssclass', 'twin', 'twin_cssclass', 'link', 'link_cssclass', 'created_at', 'updated_at'], 'safe'],
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
            $query = Image::find()->with('album')->multilingual();

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
                'album_id'   => $this->album_id,
                'size'       => $this->size,
                'width'      => $this->width,
                'height'     => $this->height,
                'order'      => $this->order,
                'is_active'  => $this->is_active,
                'created_by' => $this->created_by,
                'updated_by' => $this->updated_by,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]);

            $query->andFilterWhere(['like', 'name', $this->name])
                  ->andFilterWhere(['like', 'alt', $this->alt])
                  ->andFilterWhere(['like', 'title', $this->title])
                  ->andFilterWhere(['like', 'text', $this->text])
                  ->andFilterWhere(['like', 'caption', $this->caption])
                  ->andFilterWhere(['like', 'src', $this->src])
                  ->andFilterWhere(['like', 'cssclass', $this->cssclass])
                  ->andFilterWhere(['like', 'twin', $this->twin])
                  ->andFilterWhere(['like', 'twin_cssclass', $this->twin_cssclass])
                  ->andFilterWhere(['like', 'link', $this->link])
                  ->andFilterWhere(['like', 'link_cssclass', $this->link_cssclass]);

            if (\Yii::$app->user->identity->role <> BaseUser::ROLE_DEVELOPER) {
                $query->joinWith([
                    'album' => function (ActiveQuery $q) {
                        return $q->from(['{{ALBUM}}' => Album::tableName()])
                                 ->andWhere(['{{ALBUM}}.[[is_visible]]' => true]);
                    },
                ]);
            }

            return $dataProvider;
        }
    }
