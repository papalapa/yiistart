<?php

    namespace vendor\papalapa\yiistart\controllers;

    use frontend\models\SubscribeForm;
    use papalapa\yiistart\modules\settings\models\Settings;
    use papalapa\yiistart\modules\subscribe\models\Dispatches;
    use papalapa\yiistart\modules\subscribe\models\PivotDispatchesSubscribers;
    use papalapa\yiistart\modules\subscribe\models\Subscribers;
    use yii\console\Controller;
    use yii\db\Expression;

    /**
     * Class SendController
     * @package vendor\papalapa\yiistart\controllers
     */
    class SendController extends Controller
    {
        public $defaultAction = 'mail';

        /**
         * Sending emails to subscribers
         */
        public function actionMail()
        {
            $domain = Settings::paramOf('domain');

            if (!$domain) {
                exit('Set domain param to params.php file in console app'.PHP_EOL);
            }

            if (!$pivots = $this->activePivots()) {
                echo 'No anyone active dispatches'.PHP_EOL;
                exit;
            }

            $count = count($pivots);

            while ($pivot = array_shift($pivots)) {
                /* @var $pivot PivotDispatchesSubscribers */

                // correct image links
                $html = preg_replace('/(src="\/uploads\/)/', sprintf('src="http://%s/uploads/', $domain), $pivot->dispatch->html);
                $text = $pivot->dispatch->text;

                $unsubscribeUrl = sprintf('http://%s/site/unsubscribe?email=%s&token=%s', $domain, $pivot->subscriber->email,
                    \Yii::$app->security->hashData($pivot->subscriber->email, SubscribeForm::TOKEN_KEY));

                $html .= sprintf('<br /><p><small>Если вы не хотите больше получать подобные уведомления, перейдите по <a href="%s">этой</a> ссылке.</small></p>',
                    $unsubscribeUrl);

                $text .= PHP_EOL.PHP_EOL.sprintf('Если вы не хотите больше получать подобные уведомления, перейдите по ссылке %s',
                        $unsubscribeUrl);

                \Yii::$app->mailer
                    ->compose()
                    ->setFrom([\Yii::$app->params['noreply.email'] => \Yii::$app->name.' robot'])
                    ->setTo($pivot->subscriber->email)
                    ->setSubject($pivot->dispatch->subject)
                    ->setHtmlBody($html)
                    ->setTextBody($text)
                    ->send();

                $pivot->updateAttributes(['status' => PivotDispatchesSubscribers::STATUS_SEND]);

                echo sprintf('Email to %s is sent', $pivot->subscriber->email).PHP_EOL;
            }

            echo '----------'.PHP_EOL;
            echo sprintf('%d emails sent', $count).PHP_EOL;
        }

        /**
         * Returns active pivot relations dispatch-subscribe
         * @return array|\yii\db\ActiveRecord[]
         */
        protected function activePivots()
        {
            $this->connectPivot();

            return PivotDispatchesSubscribers::find()->where(['status' => PivotDispatchesSubscribers::STATUS_WAIT])
                                             ->orderBy(['id' => SORT_ASC])->with(['subscriber', 'dispatch'])->all();
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

        /**
         * Connects pivot with dispatches and subscribers
         */
        protected function connectPivot()
        {
            if (!$dispatch = $this->activeDispatch()) {
                echo 'No anyone active dispatches'.PHP_EOL;
                exit;
            }

            if (!$emails = $this->activeSubscribers()) {
                echo 'No anyone active subscribers'.PHP_EOL;
                exit;
            }

            $count = count($emails);

            while ($batch = array_splice($emails, 0, 10)) {
                /**
                 * Batch on input: [[id]]
                 * Batch on output: [[id],[dispatch_id],[status:ON]]
                 */
                $batch = array_map(function ($element) use ($dispatch) {
                    $element = array_values($element);
                    array_push($element, $dispatch->id, PivotDispatchesSubscribers::STATUS_WAIT);

                    return $element;
                }, $batch);

                \Yii::$app->db->createCommand()
                              ->batchInsert(PivotDispatchesSubscribers::tableName(), ['subscriber_id', 'dispatch_id', 'status'], $batch)
                              ->execute();
            }

            $dispatch->updateAttributes(['status' => Dispatches::STATUS_END]);

            echo sprintf('%d subscribers has been connected with dispatch %d', $count, $dispatch->id).PHP_EOL;
        }
    }
