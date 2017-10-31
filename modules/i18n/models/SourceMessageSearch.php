<?php

    namespace papalapa\yiistart\modules\i18n\models;

    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    use yii\db\Expression;

    /**
     * Class SourceMessageSearch
     * @package papalapa\yiistart\modules\i18n\models
     */
    class SourceMessageSearch extends SourceMessage
    {
        public $is_translated;

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
                [['is_translated'], 'boolean'],
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
            $query = SourceMessage::find()->from(['SRM' => SourceMessageSearch::tableName()]);
            $query->select(['id' => 'SRM.id', 'category' => 'SRM.category', 'message' => 'SRM.message']);

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
            $query->andFilterWhere(['SRM.id' => $this->id])
                  ->andFilterWhere(['like', 'SRM.category', $this->category])
                  ->andFilterWhere(['like', 'SRM.message', $this->message]);

            if (!is_null($this->is_translated) && $this->is_translated !== '') {
                $query->addSelect(['translates' => new Expression('COUNT(MSG.id)')]);
                $query->innerJoin(['MSG' => Message::tableName()], 'SRM.id = MSG.id')
                      ->where(['AND', ['IS NOT', 'MSG.translation', null], ['<>', 'MSG.translation', '']]);
                if ($this->is_translated) {
                    $query->having(['translates' => count(i18n::locales())]);
                } else {
                    $query->having(['<', 'translates', count(i18n::locales())]);
                }
            }

            $query->groupBy('SRM.id');

            $query->with(['messages', 'categoryDescription']);

            return $dataProvider;
        }
    }
