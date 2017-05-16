<?php

    namespace vendor\papalapa\yiistart\modules\subscribe\controllers;

    use papalapa\yiistart\modules\subscribe\models\Dispatches;
    use papalapa\yiistart\modules\subscribe\models\PivotDispatchesSubscribers;
    use papalapa\yiistart\modules\subscribe\models\Subscribers;
    use yii\console\Controller;
    use yii\db\Expression;

    /**
     * Class SendController
     * @package vendor\papalapa\yiistart\modules\subscribe\controllers
     */
    class SendController extends Controller
    {
        public $defaultAction = 'mail';

        /**
         * TODO:
         */
        public function actionMail()
        {
        }

        /**
         * Connects pivot with dispatches and subscribers
         */
        public function actionConnect()
        {
            if (!$dispatch = $this->activeDispatch()) {
                echo 'No anyone active dispatches' . PHP_EOL;
                exit;
            }

            if (!$emails = $this->activeSubscribers()) {
                echo 'No anyone active subscribers' . PHP_EOL;
                exit;
            }

            while ($batch = array_splice($emails, 0, 10)) {
                $batch = array_map(function ($element) use ($dispatch) {
                    array_push($element, $dispatch->id, PivotDispatchesSubscribers::STATUS_WAIT);
                    var_dump($element);die;
                }, $batch);

                var_dump($batch);die;
                \Yii::$app->db->createCommand()
                              ->batchInsert(PivotDispatchesSubscribers::tableName(), ['subscriber_id', 'dispatch_id', 'status'], $batch)
                              ->execute();
            }

            $dispatch->updateAttributes(['status' => Dispatches::STATUS_END]);

            echo sprintf('%d subscribers has been connected with dispatch %d', count($emails), $dispatch->id) . PHP_EOL;
        }

        /**
         * Returns oldest active dispatch
         * @return array|null|Dispatches
         */
        protected function activeDispatch()
        {
            return Dispatches::find()->where(['status' => Dispatches::STATUS_ON])
                             ->andWhere(['<=', 'start_at', new Expression('CURDATE()')])
                             ->orderBy(['created_at' => SORT_ASC])->one();
        }

        /**
         * Returns all active subscribers
         * @return array|Subscribers[]
         */
        protected function activeSubscribers()
        {
            return Subscribers::find()->select(['id'])->where(['status' => Subscribers::STATUS_ON])->asArray()->all();
        }
    }
