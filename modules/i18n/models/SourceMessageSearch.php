<?php

    namespace papalapa\yiistart\modules\i18n\models;

    use yii\base\Model;
    use yii\data\ActiveDataProvider;

    /**
     * Class SourceMessageSearch
     * @package papalapa\yiistart\modules\i18n\models
     */
    class SourceMessageSearch extends SourceMessage
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
                [['category', 'message'], 'safe'],
            ];
        }

        /**
         * Creates data provider instance with search query applied
         * @param array $params
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = SourceMessage::find();

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
                'id' => $this->id,
            ]);

            $query->andFilterWhere(['like', 'category', $this->category])
                  ->andFilterWhere(['like', 'message', $this->message]);

            return $dataProvider;
        }
    }
