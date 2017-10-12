<?php

    namespace papalapa\yiistart\validators;

    use yii\base\DynamicModel;
    use yii\validators\RegularExpressionValidator;

    /**
     * Class TelephoneValidator
     * @package papalapa\yiistart\validators
     */
    class TelephoneValidator extends RegularExpressionValidator
    {
        /**
         * Telephone pattern
         * @var string
         */
        public $pattern = '/^\+?(\d+(-| )?)?(\(\d+\)(-| )?)?\d+((-| )\d+)*$/';
        /**
         * Minimum telephone length
         * @var int
         */
        public $min = 4;
        /**
         * Maximum telephone length
         * @var int
         */
        public $max = 20;
        /**
         * @var string
         */
        public $message = 'Значение «{attribute}» не является правильным номером телефона.';

        /**
         * @param mixed $value
         * @return array|null
         */
        protected function validateValue($value)
        {
            $result = parent::validateValue($value);

            if (is_null($result)) {
                $model = DynamicModel::validateData(compact(['value']), [
                    [['value'], 'string', 'min' => $this->min, 'max' => $this->max],
                ]);

                if ($model->hasErrors()) {
                    return [$model->getFirstError('value'), []];
                }

                return null;
            }

            return $result;
        }
    }
