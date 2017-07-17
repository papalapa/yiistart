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

            if (!is_null($this->is_translated) && $this->is_translated !== '') {
                if ($this->is_translated) {
                    $query->joinWith([
                        'messages' => function ($q) /* @var $q \yii\db\ActiveQuery */ {
                            return $q->where(['OR', ['IS NOT', 'translation', null], ['<>', 'translation', '']]);
                        },
                    ]);
                } else {
                    $query->joinWith([
                        'messages' => function ($q) /* @var $q \yii\db\ActiveQuery */ {
                            return $q->where(['OR', ['IS', 'translation', null], ['=', 'translation', '']]);
                        },
                    ]);
                }
            }

            $query->andFilterWhere(['like', 'category', $this->category])
                  ->andFilterWhere(['like', 'message', $this->message])
                  ->groupBy('id');

            return $dataProvider;
        }
    }
