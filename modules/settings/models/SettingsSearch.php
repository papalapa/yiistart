<?php

    namespace papalapa\yiistart\modules\settings\models;

    use papalapa\yiistart\modules\users\models\BaseUser;
    use yii\base\Model;
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
            return Model::scenarios();
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['id', 'type'], 'integer'],
                [['type'], 'in', 'range' => array_keys(Settings::types())],
                [['is_active'], 'boolean'],
                [['multilingual'], 'boolean'],
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
                'sort'  => ['defaultOrder' => ['key' => SORT_ASC]],
            ]);

            $this->load($params);

            if (!$this->validate()) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }

            // grid filtering conditions
            $query->andFilterWhere([
                'id'           => $this->id,
                'type'         => $this->type,
                'is_active'    => $this->is_active,
                'multilingual' => $this->multilingual,
            ]);

            $query->andFilterWhere(['like', 'title', $this->title])
                  ->andFilterWhere(['like', 'key', $this->key])
                  ->andFilterWhere(['like', 'value', $this->value]);

            $query->andFilterWhere(['>=', 'created_at', $this->created_at]);
            $query->andFilterWhere(['>=', 'updated_at', $this->updated_at]);

            if (\Yii::$app->user->identity->role <> BaseUser::ROLE_DEVELOPER) {
                $query->andWhere(['is_visible' => true]);
            }

            return $dataProvider;
        }
    }
