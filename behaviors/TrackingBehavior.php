<?php

    namespace papalapa\yiistart\behaviors;

    use yii\base\Behavior;
    use yii\base\InvalidConfigException;
    use yii\db\ActiveRecord;
    use yii\db\Expression;
    use yii\helpers\Inflector;

    /**
     * Class TrackingBehavior
     * TODO: need to refactor
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

            $modelName = Inflector::camel2id((new \ReflectionClass($this->owner->className()))->getShortName());
            $modelId   = $this->owner->getAttribute('id');

            \Yii::$app->db->createCommand()->insert(self::tableName(), [
                'model_name' => $modelName,
                'model_id'   => $modelId,
            ])->execute();
        }
    }
