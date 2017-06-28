<?php

    namespace papalapa\yiistart\helpers;

    use yii\base\InvalidParamException;

    /**
     * Class StringHelper
     * @package papalapa\yiistart\helpers
     */
    class StringHelper extends \yii\helpers\StringHelper
    {
        /**
         * cp1251/utf8
         * @param $str
         * @return mixed
         */
        public static function UTF8toCP1251($str)
        {
            $table = [
                "\xD0\x81" => "\xA8" /*-Ё-*/,
                "\xD1\x91" => "\xB8" /*-ё-*/
            ];

            if (function_exists('preg_replace_callback')) {
                return preg_replace_callback('#([\xD0-\xD1])([\x80-\xBF])#s', function ($matches) use ($table) {
                    return isset($table[$matches[0]])
                        ? $table[$matches[0]]
                        : chr(ord($matches[2]) + ($matches[1] == "\xD0" ? 0x30 : 0x70));
                }, $str);
            } else {
                return preg_replace('#([\xD0-\xD1])([\x80-\xBF])#se',
                    'isset($table["$0"]) ? $table["$0"] : chr(ord("$2")+("$1" == "\xD0" ? 0x30 : 0x70)) ', $str);
            }
        }

        /**
         * utf8/cp1251
         * @param mixed $str
         * @return mixed
         */
        public static function CP1251toUTF8($str)
        {
            $table = [
                "\xA8" => "\xD0\x81" /*-Ё-*/,
                "\xB8" => "\xD1\x91" /*-ё-*/
            ];

            if (function_exists('preg_replace_callback')) {
                return preg_replace_callback('#[\x80-\xFF]#s', function ($matches) use ($table) {
                    if ($matches[0] >= "\xF0") {
                        return "\xD1".chr(ord($matches[0]) - 0x70);
                    } else {
                        return $matches[0] >= "\xC0"
                            ? "\xD0".chr(ord($matches[0]) - 0x30)
                            : (isset($table[$matches[0]]) ? $table[$matches[0]] : "");
                    }
                }, $str);
            } else {
                return preg_replace('#[\x80-\xFF]#se',
                    ' "$0" >= "\xF0" ? "\xD1".chr(ord("$0")-0x70) : ("$0" >= "\xC0" ? "\xD0".chr(ord("$0")-0x30) : (isset($table["$0"]) ? $table["$0"] : "") )',
                    $str);
            }
        }

        /**
         * Translate cyrillic characters into the english characters
         * @param string $str       - string
         * @param string $separator - words separator
         * @return string
         */
        public static function translate($str, $separator = '-')
        {
            $in = [
                '/а/', '/А/', '/б/', '/Б/', '/в/', '/В/', '/г/', '/Г/', '/д/', '/Д/', '/е/', '/Е/', '/ё/', '/Ё/', '/ж/', '/Ж/', '/з/', '/З/', '/и/', '/И/',
                '/й/', '/Й/', '/к/', '/К/', '/л/', '/Л/', '/м/', '/М/', '/н/', '/Н/', '/о/', '/О/', '/п/', '/П/', '/р/', '/Р/', '/с/', '/С/', '/т/', '/Т/',
                '/у/', '/У/', '/ф/', '/Ф/', '/х/', '/Х/', '/ц/', '/Ц/', '/ч/', '/Ч/', '/ш/', '/Ш/', '/щ/', '/Щ/', '/ъ/', '/Ъ/', '/ы/', '/Ы/', '/ь/', '/Ь/',
                '/э/', '/Э/', '/ю/', '/Ю/', '/я/', '/Я/', '/Ә/', '/І/', '/Ң/', '/Ғ/', '/Ү/', '/Ұ/', '/Қ/', '/Ө/', '/Һ/', '/ә/', '/і/', '/ң/', '/ғ/', '/ү/',
                '/ұ/', '/қ/', '/ө/', '/һ/', '/[^a-zA-Z0-9абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ]/', '//',
            ];

            $out = [
                'a', 'A', 'b', 'B', 'v', 'V', 'g', 'G', 'd', 'D', 'e', 'E', 'jo', 'JO', 'zh', 'ZH', 'z', 'Z', 'i', 'I', 'j', 'J', 'k', 'K', 'l', 'L',
                'm', 'M', 'n', 'N', 'o', 'O', 'p', 'P', 'r', 'R', 's', 'S', 't', 'T', 'u', 'U', 'f', 'F', 'h', 'H', 'c', 'C', 'ch', 'CH', 'sh', 'SH', 'sch',
                'SCH', '\'', '\'', 'y', 'Y', '\'', '\'', 'e', 'E', 'ju', 'JU', 'ja', 'JA', '/E/', '/I/', '/N/', '/G/', '/Y/', '/Y/', '/K/', '/O/', '/H/',
                '/e/', '/i/', '/n/', '/g/', '/y/', '/y/', '/k/', '/o/', '/h/', '', $separator,
            ];

            return static::CP1251toUTF8(preg_replace($in, $out, $str));
        }

        /**
         * Implode text rows to inline string
         * @param $text
         * @return string
         */
        public static function textInline($text)
        {
            return trim(preg_replace('~[\h\v]+~u', ' ', (string)$text));
        }

        /**
         * Cleans vertical and horizontal multi-spaces
         * @param null $text
         * @param bool $strict
         * @return InvalidParamException|null|string
         */
        public static function whiteSpaceNormalize($text = null, $strict = true)
        {
            if (null === $text) {
                return null;
            }

            try {
                $text = (string)$text;
            } catch (\Exception $e) {
                throw new InvalidParamException('Param "text" could not be converted to string');
            }

            // разбиваем текст в массив строк
            $text = explode("\n", $text);

            foreach ((array)$text as $line => $string) {
                // заменяем все горизонтальные проблельные символы
                $string = preg_replace('~\h~u', ' ', $string);

                // удаляем все вертикальные проблельные символы
                $string = preg_replace('~\v~u', null, $string);

                // удаляем начальные пробелы
                $string = preg_replace('~^\h+~u', null, $string);

                // удаляем конечные пробелы
                $string = preg_replace('~\h+$~u', null, $string);

                // удаляем множественные пробелы
                $string = preg_replace('~\h+~u', ' ', $string);

                $text[$line] = $string;
            }

            // объединяем строки в массив
            $text = implode("\n", $text);

            // удаляем пустые строки в начале блока
            $text = preg_replace('~^\n+~u', null, $text);

            // удаляем пустые строки в конце блока
            $text = preg_replace('~\n+$~u', null, $text);

            // удаляем пустые строки
            if ((bool)$strict) {
                $text = preg_replace('~\n{2,}~u', "\n", $text);
            } else {
                $text = preg_replace('~\n{3,}~u', "\n\n", $text);
            }

            return $text;
        }
    }
