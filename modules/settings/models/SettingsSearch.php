<?php

    namespace papalapa\yiistart\modules\settings\models;

    use yii\data\ActiveDataProvider;

    /**
     * Class SettingsSearch
     * @package papalapa\yiistart\modules\settings\models
     */
    class SettingsSearch extends Settings
    {
        /**
         * @inheritdoc
         */
        public function scenarios()
        {
            // bypass scenarios() implementation in the parent class
            return Settings::scenarios();
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['id'], 'integer'],
                [['is_active'], 'boolean'],
                [['key', 'value'], 'safe'],
            ];
        }

        /**
         * Creates data provider instance with search query applied
         * @param array $params
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = Settings::find()->multilingual();

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

            $query->andFilterWhere(['like', 'key', $this->key])
                  ->andFilterWhere(['like', 'value', $this->value]);

            return $dataProvider;
        }
    }
