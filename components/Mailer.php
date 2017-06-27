<?php

    namespace papalapa\yiistart\components;

    use papalapa\yiistart\modules\settings\models\Settings;
    use yii\base\DynamicModel;
    use yii\base\InvalidConfigException;
    use yii\base\InvalidParamException;
    use yii\helpers\ArrayHelper;

    /**
     * Class Mailer
     * @package papalapa\yiistart\components
     */
    class Mailer extends \yii\swiftmailer\Mailer
    {
        /**
         * @param \yii\mail\MessageInterface $message
         * @return bool
         * @throws \yii\base\InvalidConfigException
         */
        public function send($message)
        {
            /**
             * Get "noreply.email" param from config file
             */
            if (false === $email = ArrayHelper::getValue(\Yii::$app->params, 'noreply.email', false)) {
                $errorMessage = 'Error on sending email: missing mandatory parameter "noreply.email" in config file.';
                throw new InvalidConfigException($errorMessage);
            }

            if (DynamicModel::validateData(['email' => $email], [['email', 'required'], ['email', 'email']])->hasErrors()) {
                $errorMessage = 'Invalid email address in param "noreply.email" in config file.';
                throw new InvalidParamException($errorMessage);
            }

            $message->setFrom([$email => \Yii::$app->name.' robot']);

            /**
             * Get "admin.email" param from config file
             */
            if (!$message->getTo()) {
                if ($emails = Settings::valueOrParam('admin.email', false)) {
                    /* Splitting multiple */
                    $emails = preg_split('/[\s\,\;]+/', $emails);
                    foreach ($emails as $index => $email) {
                        if (DynamicModel::validateData(['email' => $email], [['email', 'required'], ['email', 'email']])->hasErrors()) {
                            unset($emails[$index]);
                        }
                    }
                    $emails = array_unique($emails);
                }

                if (empty($emails)) {
                    $errorMessage = 'Error on sending email: missing mandatory parameter "admin.email" in config file.';
                    throw new InvalidConfigException($errorMessage);
                }

                $message->setTo($emails);
            }

            return parent::send($message);
        }
    }
