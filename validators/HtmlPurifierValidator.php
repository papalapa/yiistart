<?php

    namespace papalapa\yiistart\validators;

    use yii\base\InvalidConfigException;
    use yii\helpers\HtmlPurifier;
    use yii\validators\FilterValidator;

    /**
     * Class HtmlPurifierValidator
     * @package papalapa\yiistart\validators
     */
    class HtmlPurifierValidator extends FilterValidator
    {
        public $options = [
            'AutoFormat.AutoParagraph'                     => false,
            'HTML.AllowedAttributes'                       => null,
            'HTML.AllowedElements'                         => null,
            'CSS.AllowedFonts'                             => null,
            'CSS.AllowedProperties'                        => null,
            'AutoFormat.RemoveEmpty'                       => true,
            'AutoFormat.RemoveEmpty.RemoveNbsp'            => true,
            'AutoFormat.RemoveEmpty.RemoveNbsp.Exceptions' => ['th' => true, 'td' => true],
            'HTML.BlockWrapper'                            => 'p',
            'Output.Newline'                               => "\n",
        ];

        public function init()
        {
            if (!is_array($this->options)) {
                throw new InvalidConfigException('Options property must be an array.');
            }

            $this->filter = function ($string) {
                return HtmlPurifier::process($string, $this->options);
            };

            parent::init();
        }
    }
