<?php

    namespace papalapa\yiistart\models;

    use papalapa\yiistart\modules\i18n\models\SourceMessage;
    use papalapa\yiistart\modules\i18n\models\SourceMessageCategories;
    use yii\i18n\MissingTranslationEvent;

    /**
     * Class TranslationEventHandler
     * @package papalapa\yiistart\models
     */
    class TranslationEventHandler
    {
        /**
         * @param MissingTranslationEvent $event
         */
        public static function handleMissingTranslation(MissingTranslationEvent $event)
        {
            $sourceMessage = SourceMessage::find()->where(['[[category]]' => $event->category, '[[message]]' => $event->message])->one();

            if (!$sourceMessage) {
                $sourceMessage = new SourceMessage();
                $sourceMessage->setAttributes(['category' => $event->category, 'message' => $event->message]);
                $sourceMessage->save();

                if (!$sourceMessage->categoryDescription) {
                    $sourceMessageCategory = new SourceMessageCategories([
                        'category' => $event->category,
                    ]);
                    $sourceMessageCategory->save();
                }

                \Yii::warning(sprintf('Translation (%s|%s) created as "%s"', $event->category, $event->language, $event->message));
            }

            $sourceMessage->initMessages();
            $sourceMessage->saveMessages();
        }
    }
