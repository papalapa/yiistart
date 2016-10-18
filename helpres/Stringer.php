<?php

    namespace papalapa\yii2start\helpers;

    use yii\helpers\StringHelper;

    /**
     * Class Stringer
     * @package papalapa\yii2start\helpers
     */
    class Stringer extends StringHelper
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
                return preg_replace('#([\xD0-\xD1])([\x80-\xBF])#se', 'isset($table["$0"]) ? $table["$0"] : chr(ord("$2")+("$1" == "\xD0" ? 0x30 : 0x70)) ', $str);
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
                return preg_replace('#[\x80-\xFF]#se', ' "$0" >= "\xF0" ? "\xD1".chr(ord("$0")-0x70) : ("$0" >= "\xC0" ? "\xD0".chr(ord("$0")-0x30) : (isset($table["$0"]) ? $table["$0"] : "") )', $str);
            }
        }

        /**
         * Translate cyrillic characters into the english characters
         * @param string $str - string
         * @param string $sep - words separator
         * @return string
         */
        public static function translate($str, $sep = '-')
        {
            $in = [
                '/а/', '/А/', '/б/', '/Б/', '/в/', '/В/', '/г/', '/Г/', '/д/', '/Д/', '/е/', '/Е/', '/ё/', '/Ё/', '/ж/',
                '/Ж/', '/з/', '/З/', '/и/', '/И/', '/й/', '/Й/', '/к/', '/К/', '/л/', '/Л/', '/м/', '/М/', '/н/', '/Н/',
                '/о/', '/О/', '/п/', '/П/', '/р/', '/Р/', '/с/', '/С/', '/т/', '/Т/', '/у/', '/У/', '/ф/', '/Ф/', '/х/',
                '/Х/', '/ц/', '/Ц/', '/ч/', '/Ч/', '/ш/', '/Ш/', '/щ/', '/Щ/', '/ъ/', '/Ъ/', '/ы/', '/Ы/', '/ь/', '/Ь/',
                '/э/', '/Э/', '/ю/', '/Ю/', '/я/', '/Я/', '/Ә/', '/І/', '/Ң/', '/Ғ/', '/Ү/', '/Ұ/', '/Қ/', '/Ө/', '/Һ/',
                '/ә/', '/і/', '/ң/', '/ғ/', '/ү/', '/ұ/', '/қ/', '/ө/', '/һ/',
                '/[^a-zA-Z0-9абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ ]/', '/ /',
            ];

            $out = [
                'a', 'A', 'b', 'B', 'v', 'V', 'g', 'G', 'd', 'D', 'e', 'E', 'jo', 'JO', 'zh', 'ZH', 'z', 'Z', 'i', 'I',
                'j', 'J', 'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'o', 'O', 'p', 'P', 'r', 'R', 's', 'S', 't', 'T', 'u',
                'U', 'f', 'F', 'h', 'H', 'c', 'C', 'ch', 'CH', 'sh', 'SH', 'sch', 'SCH', '\'', '\'', 'y', 'Y', '\'', '\'',
                'e', 'E', 'ju', 'JU', 'ja', 'JA', '/E/', '/I/', '/N/', '/G/', '/Y/', '/Y/', '/K/', '/O/', '/H/', '/e/',
                '/i/', '/n/', '/g/', '/y/', '/y/', '/k/', '/o/', '/h/', '', $sep,
            ];

            return static::CP1251toUTF8(preg_replace($in, $out, $str));
        }

        /**
         * Remove any spaces such as \s\t\r\n
         * @param mixed $data
         * @return string
         */
        public static function textInline($data = null)
        {
            return null === $data ? null : preg_replace('~(\h|\v)+~u', ' ', $data);
        }

        /**
         * Cleans multi-spaces
         * @param null $str    - removes empty rows
         * @param bool $strict - when true removes any single empty rows
         * @return string
         */
        public static function whiteSpaceNormalize($str = null, $strict = true)
        {
            if (null === $str) {
                return null;
            }

            // разбиваем текст в массив строк
            $data = explode("\n", $str);

            foreach ((array)$data as $line => $text) {
                // заменяем все горизонтальные проблельные символы
                $text = preg_replace('~\h~u', ' ', $text);

                // удаляем все вертикальные проблельные символы
                $text = preg_replace('~\v~u', null, $text);

                // удаляем начальные пробелы
                $text = preg_replace('~^\h+~u', null, $text);

                // удаляем конечные пробелы
                $text = preg_replace('~\h+$~u', null, $text);

                // удаляем множественные пробелы
                $text = preg_replace('~\h+~u', ' ', $text);

                $data[$line] = $text;
            }

            // объединяем строки в массив
            $data = implode("\n", $data);

            // удаляем пустые строки в начале блока
            $data = preg_replace('~^\n+~u', null, $data);

            // удаляем пустые строки в конце блока
            $data = preg_replace('~\n+$~u', null, $data);

            // удаляем пустые строки
            if ((bool)$strict) {
                $data = preg_replace('~\n{2,}~u', "\n", $data);
            } else {
                $data = preg_replace('~\n{3,}~u', "\n\n", $data);
            }

            return $data;
        }

        /**
         * Escape html special chars
         * @param string $data
         * @param bool   $inline
         * @return string
         */
        public static function escape($data, $inline = true)
        {
            /**
             * ENT_COMPAT - одиночные - остаются без изменений, двойные кавычки преобразуются в '&quot;' (значение по умолчанию)
             * ENT_QUOTES - одиночные кавычки преобразуются в '&#039;', двойные кавычки преобразуются в '&quot;'
             * ENT_NOQUOTES - одиночные и двойные кавычки остаются без изменений
             * Также производятся следующие преобразования:
             * '&' (амперсанд) преобразуется в '&amp;'
             * '<' (знак "меньше чем") преобразуется в '&lt;'
             * '>' (знак "больше чем") преобразуется в '&gt;'
             */
            if ((bool)$inline) {
                $data = nl2br($data);
            }

            return htmlspecialchars($data, ENT_QUOTES | ENT_SUBSTITUTE | ENT_DISALLOWED | ENT_HTML5, 'UTF-8');
        }

        /**
         * HTML entities
         * @param $data
         * @return string
         */
        public static function entity($data)
        {
            return htmlentities($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        /**
         * HTML entities decode
         * @param $data
         * @return string
         */
        public static function entityDecode($data)
        {
            return html_entity_decode($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        /**
         * Separate text by new lines and wrap all in p-tags
         * @param      $text
         * @param bool $save_nl
         * @return string
         */
        public static function nl2pp($text, $save_nl = true)
        {
            $replacement = $save_nl ? "</p>\n<p>" : '</p><p>';

            return '<p>'.preg_replace("/\n/", $replacement, $text).'</p>';
        }

        /**
         * Replace html-br-tag to new lines
         * @param $text
         * @return string
         */
        public static function br2nl($text)
        {
            return preg_replace('/<br[^>]*>/', "\n", $text);
        }

        /**
         * Generate a random alpha or alpha-numeric string.
         * <code>
         *    // Generate a 40 character random alpha-numeric string
         *    echo Str::random(40);
         *    // Generate a 16 character random alphabetic string
         *    echo Str::random(16, 'alpha');
         * </code>
         * @param  int    $length
         * @param  string $type
         * @return string
         */
        public static function random($length = 32, $type = null)
        {
            return substr(str_shuffle(str_repeat(static::pool($type), 5)), 0, $length);
        }
        
        /**
         * Get the character pool for a given type of random string.
         * @param  string $type
         * @return string
         */
        protected static function pool($type)
        {
            switch ($type) {
                case 'digit':
                    return '0123456789';

                case 'alpha':
                    return 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

                default:
                    return '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            }
        }
    }