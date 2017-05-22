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
            'AutoFormat.AutoParagraph'                     => true,
            'AutoFormat.RemoveEmpty'                       => true,
            'AutoFormat.RemoveEmpty.RemoveNbsp'            => true,
            'AutoFormat.RemoveEmpty.RemoveNbsp.Exceptions' => ['th' => true, 'td' => true],
            'HTML.AllowedAttributes'                       => ['style', 'align', 'a.href', 'a.target', 'img.src', 'img.class'],
            'HTML.AllowedElements'                         => 'div,p,pre,a,b,strong,i,em,u,s,strike,big,small,sup,sub,ul,ol,li,table,tbody,thead,tfoot,tr,th,td,h2,h3,h4,h5,h6,blockquote,img',
            'CSS.AllowedFonts'                             => [],
            'CSS.AllowedProperties'                        => ['ul[list-style-type]', 'ol[list-style-type]', 'text-align'],
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
