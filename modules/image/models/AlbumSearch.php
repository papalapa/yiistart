<?php

    namespace papalapa\yiistart\modules\image\models;

    use yii\base\Model;
    use yii\data\ActiveDataProvider;

    /**
     * AlbumSearch represents the model behind the search form about `papalapa\yiistart\modules\image\models\Album`.
     */
    class AlbumSearch extends Album
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
                [['id', 'scale', 'has_name', 'has_alt', 'has_title', 'has_text', 'has_caption', 'has_src', 'has_cssclass', 'has_twin', 'has_twin_cssclass', 'has_link', 'has_link_cssclass', 'validator_min_size', 'validator_max_size', 'is_multilingual_images', 'is_visible', 'is_active', 'created_by', 'updated_by'], 'integer'],
                [['alias', 'name', 'template', 'validator_controller', 'validator_extensions', 'description', 'created_at', 'updated_at'], 'safe'],
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
            $query = Album::find();

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
                'id'                     => $this->id,
                'scale'                  => $this->scale,
                'has_name'               => $this->has_name,
                'has_alt'                => $this->has_alt,
                'has_title'              => $this->has_title,
                'has_text'               => $this->has_text,
                'has_caption'            => $this->has_caption,
                'has_src'                => $this->has_src,
                'has_cssclass'           => $this->has_cssclass,
                'has_twin'               => $this->has_twin,
                'has_twin_cssclass'      => $this->has_twin_cssclass,
                'has_link'               => $this->has_link,
                'has_link_cssclass'      => $this->has_link_cssclass,
                'validator_min_size'     => $this->validator_min_size,
                'validator_max_size'     => $this->validator_max_size,
                'is_multilingual_images' => $this->is_multilingual_images,
                'is_visible'             => $this->is_visible,
                'is_active'              => $this->is_active,
                'created_by'             => $this->created_by,
                'updated_by'             => $this->updated_by,
                'created_at'             => $this->created_at,
                'updated_at'             => $this->updated_at,
            ]);

            $query->andFilterWhere(['like', 'alias', $this->alias])
                  ->andFilterWhere(['like', 'name', $this->name])
                  ->andFilterWhere(['like', 'template', $this->template])
                  ->andFilterWhere(['like', 'validator_controller', $this->validator_controller])
                  ->andFilterWhere(['like', 'validator_extensions', $this->validator_extensions])
                  ->andFilterWhere(['like', 'description', $this->description]);

            return $dataProvider;
        }
    }
