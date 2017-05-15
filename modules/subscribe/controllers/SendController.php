<?php

    namespace vendor\papalapa\yiistart\modules\subscribe\controllers;

    use papalapa\yiistart\modules\subscribe\models\Dispatches;
    use papalapa\yiistart\modules\subscribe\models\PivotDispatchesSubscribers;
    use yii\console\Controller;

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
            $emails = $this->batch(10);


        }

        /**
         * @param int $count
         * @return array|PivotDispatchesSubscribers[]
         */
        protected function batch($count = 10)
        {
            /* @var $dispatch Dispatches */
            $dispatch = Dispatches::find()->where(['status' => Dispatches::STATUS_ON])
                                  ->orderBy(['created_at' => SORT_ASC])->one();
            $emails   = $dispatch->getPivotDispatchesSubscribers()->select(['subscriber_id'])
                                 ->where(['status' => PivotDispatchesSubscribers::STATUS_WAIT])->limit($count)->all();

            return $emails;
        }
    }
