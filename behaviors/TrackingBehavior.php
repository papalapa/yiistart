<?php

    namespace papalapa\yiistart\behaviors;

    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\models\Tracking;
    use yii\base\Behavior;
    use yii\base\InvalidConfigException;
    use yii\db\ActiveRecord;
    use yii\helpers\Inflector;

    /**
     * Class TrackingBehavior
     * @property string $model_name
     * @property string $model_pk
     * @package papalapa\yiistart\behaviors
     */
    class TrackingBehavior extends Behavior
    {
        /**
         * Event for update `$viewCountAttribute` attribute only
         */
        const EVENT_VIEW = 'eventView';
        /**
         * Event for update `$viewCountAttribute` attribute and touch `$viewedAtAttribute` attribute
         */
        const EVENT_TOUCH = 'eventTouch';
        /**
         * Event for update `$viewCountAttribute`, `$viewedAtAttribute`
         * and make new record to log in tracking table
         * @see /app/vendor/papalapa/yiistart/models/Tracking.php
         */
        const EVENT_TRACK = 'eventTrack';
        /**
         * @var ActiveRecord|MultilingualActiveRecord
         */
        public $owner;
        /**
         * @var string
         */
        public $viewCountAttribute = 'view_count';
        /**
         * @var string
         */
        public $viewedAtAttribute = 'viewed_at';

        /**
         * @throws InvalidConfigException
         */
        public function init()
        {
            if (!is_string($this->viewCountAttribute) || $this->viewCountAttribute === '') {
                throw new InvalidConfigException('Property "viewCountAttribute" must be a string in behavior '.__CLASS__);
            }

            if (!is_string($this->viewedAtAttribute) || $this->viewedAtAttribute === '') {
                throw new InvalidConfigException('Property "viewedAtAttribute" must be a string in behavior '.__CLASS__);
            }

            parent::init();
        }

        /**
         * @return array
         */
        public function events()
        {
            return [
                self::EVENT_VIEW  => 'viewEvent',
                self::EVENT_TOUCH => 'touchEvent',
                self::EVENT_TRACK => 'trackEvent',
            ];
        }

        /**
         * Update `$viewCountAttribute`
         */
        public function viewEvent()
        {
            $this->owner->updateCounters([$this->viewCountAttribute => 1]);
        }

        /**
         * Update `$viewCountAttribute` and touch `$viewedAtAttribute` attribute
         */
        public function touchEvent()
        {
            $this->owner->updateAttributes([
                $this->viewCountAttribute => $this->owner->getAttribute($this->viewCountAttribute) + 1,
                $this->viewedAtAttribute  => date('Y-m-d H:i:s'),
            ]);
        }

        /**
         * Update `$viewCountAttribute`, touch `$viewedAtAttribute` attribute and track model
         * @throws \yii\db\Exception
         */
        public function trackEvent()
        {
            $this->touchEvent();

            $track             = new Tracking();
            $track->model_name = Inflector::camel2id((new \ReflectionClass($this->owner->className()))->getShortName());
            $track->model_pk   = $this->owner->getPrimaryKey();
            $track->save();
        }
    }
