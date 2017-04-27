<?php

    namespace papalapa\yiistart\behaviors;

    use yii\base\Behavior;
    use yii\base\InvalidConfigException;
    use yii\base\InvalidParamException;
    use yii\db\ActiveRecord;
    use yii\db\Expression;

    /**
     * Class TrackingBehavior
     * TODO: refactor
     * @property ActiveRecord $owner
     * @package papalapa\yiistart\behaviors
     */
    class TrackingBehavior extends Behavior
    {
        const EVENT_TOUCH = 'eventTouch';
        const EVENT_TRACK = 'eventTrack';
        const EVENT_VIEW  = 'eventView';
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
                throw new InvalidConfigException('Property "viewCountAttribute" must be a string in behavior ' . __CLASS__);
            }

            if (!is_string($this->viewedAtAttribute) || $this->viewedAtAttribute === '') {
                throw new InvalidConfigException('Property "viewedAtAttribute" must be a string in behavior ' . __CLASS__);
            }

            parent::init();
        }

        /**
         * @return string
         */
        public static function tableName()
        {
            return '{{tracking}}';
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
         * Update "view_count" only
         */
        public function viewEvent()
        {
            $this->owner->updateCounters([$this->viewCountAttribute => 1]);
        }

        /**
         * Update "view_count" and touch "viewed_at" attribute
         */
        public function touchEvent()
        {
            $view_count = $this->owner->getAttribute($this->viewCountAttribute);
            $result     = $this->owner->updateAttributes([
                $this->viewCountAttribute => new Expression("[[{$this->viewCountAttribute}]] + 1"),
                $this->viewedAtAttribute  => new Expression('NOW()'),
            ]);
            if ($result) {
                $this->owner->setAttribute($this->viewCountAttribute, ++$view_count);
            }
        }

        /**
         * Update "view_count", touch "viewed_at" attribute and track model
         * @throws \yii\db\Exception
         */
        public function trackEvent()
        {
            $this->touchEvent();
            \Yii::$app->db->createCommand()->insert(self::tableName(), [
                'content_type' => $this->ensureContentType(),
                'content_id'   => $this->owner->getAttribute('id'),
            ])->execute();
        }

        /**
         * Ensure that contentType is set
         * @return string
         */
        protected function ensureContentType()
        {
            if (!is_string($this->contentType) || $this->contentType === '') {
                if (!$this->owner->hasMethod('contentType')) {
                    throw new InvalidParamException('Attribute "contentType" must have a string value or owner has method contentType().');
                } else {
                    $this->contentType = $this->owner->contentType();
                }
            }

            return $this->contentType;
        }
    }
