<?php

    namespace papalapa\yiistart\modules\settings\models;

    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    use yii\helpers\ArrayHelper;

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
                [['title'], 'string'],
                [['key', 'value'], 'safe'],
                [['created_at', 'updated_at'], 'date', 'format' => 'yyyy-mm-dd'],
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

            $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'key', $this->key])
                ->andFilterWhere(['like', 'value', $this->value]);

            $query->andFilterWhere(['>=', 'created_at', $this->created_at]);
            $query->andFilterWhere(['>=', 'updated_at', $this->updated_at]);

            return $dataProvider;
        }
    }
