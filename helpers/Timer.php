<?php

    namespace papalapa\yiistart\helpers;

    /**
     * Class Timer
     * @package papalapa\yiistart\helpers
     */
    class Timer extends \DateTime
    {
        /**
         * Parse time by string patterns
         * @param null|string|int $time
         * @return int|false
         */
        public static function timestampOf($time = null)
        {
            if (is_null($time)) {
                return time();
            }

            list($sec, $min, $hour, $day, $mon, $year) = [0, 0, 0, 1, 1, 1970];

            // just timestamp
            if (preg_match('~^\d+$~', $time)) {
                list($year, $mon, $day, $hour, $min, $sec) = explode(' ', date('Y m d H i s', $time));
            } // dd.mm.yyyy hh:ii:ss
            elseif (preg_match('~^\d{2}\.\d{2}\.\d{4}\s\d{2}\:\d{2}\:\d{2}$~', $time)) {
                $arr = explode(' ', $time);
                list($day, $mon, $year) = explode('.', $arr[0]);
                list($hour, $min, $sec) = explode(':', $arr[1]);
            } // dd-mm-yyyy hh:ii:ss
            elseif (preg_match('~^\d{2}\-\d{2}\-\d{4}\s\d{2}\:\d{2}\:\d{2}$~', $time)) {
                $arr = explode(' ', $time);
                list($day, $mon, $year) = explode('-', $arr[0]);
                list($hour, $min, $sec) = explode(':', $arr[1]);
            } // dd/mm/yyyy hh:ii:ss
            elseif (preg_match('~^\d{2}\/\d{2}\/\d{4}\s\d{2}\:\d{2}\:\d{2}$~', $time)) {
                $arr = explode(' ', $time);
                list($day, $mon, $year) = explode('/', $arr[0]);
                list($hour, $min, $sec) = explode(':', $arr[1]);
            } // yyyy.mm.dd hh:ii:ss
            elseif (preg_match('~^\d{4}\.\d{2}\.\d{2}\s\d{2}\:\d{2}\:\d{2}$~', $time)) {
                $arr = explode(' ', $time);
                list($year, $mon, $day) = explode('.', $arr[0]);
                list($hour, $min, $sec) = explode(':', $arr[1]);
            } // yyyy-mm-dd hh:ii:ss
            elseif (preg_match('~^\d{4}\-\d{2}\-\d{2}\s\d{2}\:\d{2}\:\d{2}$~', $time)) {
                $arr = explode(' ', $time);
                list($year, $mon, $day) = explode('-', $arr[0]);
                list($hour, $min, $sec) = explode(':', $arr[1]);
            } // yyyy/mm/dd hh:ii:ss
            elseif (preg_match('~^\d{4}\/\d{2}\/\d{2}\s\d{2}\:\d{2}\:\d{2}$~', $time)) {
                $arr = explode(' ', $time);
                list($year, $mon, $day) = explode('/', $arr[0]);
                list($hour, $min, $sec) = explode(':', $arr[1]);
            } // hh:ii:ss dd.mm.yyyy
            elseif (preg_match('~^\d{2}\:\d{2}\:\d{2}\s\d{2}\.\d{2}\.\d{4}$~', $time)) {
                $arr = explode(' ', $time);
                list($hour, $min, $sec) = explode(':', $arr[0]);
                list($day, $mon, $year) = explode('.', $arr[1]);
            } // hh:ii:ss dd-mm-yyyy
            elseif (preg_match('~^\d{2}\:\d{2}\:\d{2}\s\d{2}\-\d{2}\-\d{4}$~', $time)) {
                $arr = explode(' ', $time);
                list($hour, $min, $sec) = explode(':', $arr[0]);
                list($day, $mon, $year) = explode('-', $arr[1]);
            } // hh:ii:ss dd/mm/yyyy
            elseif (preg_match('~^\d{2}\:\d{2}\:\d{2}\s\d{2}\/\d{2}\/\d{4}$~', $time)) {
                $arr = explode(' ', $time);
                list($hour, $min, $sec) = explode(':', $arr[0]);
                list($day, $mon, $year) = explode('/', $arr[1]);
            } // hh:ii:ss yyyy.mm.dd
            elseif (preg_match('~^\d{2}\:\d{2}\:\d{2}\s\d{4}\.\d{2}\.\d{2}$~', $time)) {
                $arr = explode(' ', $time);
                list($hour, $min, $sec) = explode(':', $arr[0]);
                list($year, $mon, $day) = explode('.', $arr[1]);
            } // hh:ii:ss yyyy-mm-dd
            elseif (preg_match('~^\d{2}\:\d{2}\:\d{2}\s\d{4}\-\d{2}\-\d{2}$~', $time)) {
                $arr = explode(' ', $time);
                list($hour, $min, $sec) = explode(':', $arr[0]);
                list($year, $mon, $day) = explode('-', $arr[1]);
            } // hh:ii:ss yyyy/mm/dd
            elseif (preg_match('~^\d{2}\:\d{2}\:\d{2}\s\d{4}\/\d{2}\/\d{2}$~', $time)) {
                $arr = explode(' ', $time);
                list($hour, $min, $sec) = explode(':', $arr[0]);
                list($year, $mon, $day) = explode('/', $arr[1]);
            } // dd.mm.yy
            elseif (preg_match('~^\d{2}\.\d{2}\.\d{2}$~', $time)) {
                list($day, $mon, $year) = explode('.', $time);
            } // dd.mm.yyyy
            elseif (preg_match('~^\d{2}\.\d{2}\.\d{4}$~', $time)) {
                list($day, $mon, $year) = explode('.', $time);
            } // dd-mm-yy
            elseif (preg_match('~^\d{2}\-\d{2}\-\d{2}$~', $time)) {
                list($day, $mon, $year) = explode('-', $time);
            } // dd-mm-yyyy
            elseif (preg_match('~^\d{2}\-\d{2}\-\d{4}$~', $time)) {
                list($day, $mon, $year) = explode('-', $time);
            } // dd/mm/yy
            elseif (preg_match('~^\d{2}\/\d{2}\/\d{2}$~', $time)) {
                list($day, $mon, $year) = explode('/', $time);
            } // dd/mm/yyyy
            elseif (preg_match('~^\d{2}\/\d{2}\/\d{4}$~', $time)) {
                list($day, $mon, $year) = explode('/', $time);
            } // yyyy.mm.dd
            elseif (preg_match('~^\d{4}\.\d{2}\.\d{2}$~', $time)) {
                list($year, $mon, $day) = explode('.', $time);
            } // yyyy-mm-dd
            elseif (preg_match('~^\d{4}\-\d{2}\-\d{2}$~', $time)) {
                list($year, $mon, $day) = explode('-', $time);
            } // yyyy/mm/dd
            elseif (preg_match('~^\d{4}\/\d{2}\/\d{2}$~', $time)) {
                list($year, $mon, $day) = explode('/', $time);
            } // dd.mm
            elseif (preg_match('~^\d{2}\.\d{2}$~', $time)) {
                list($day, $mon) = explode('.', $time);
                $year = date('Y');
            } // dd-mm
            elseif (preg_match('~^\d{2}\-\d{2}$~', $time)) {
                list($day, $mon) = explode('-', $time);
                $year = date('Y');
            } // dd/mm
            elseif (preg_match('~^\d{2}\/\d{2}$~', $time)) {
                list($day, $mon) = explode('/', $time);
                $year = date('Y');
            } // mm.yyyy
            elseif (preg_match('~^\d{2}\.\d{4}$~', $time)) {
                list($mon, $year) = explode('.', $time);
                $day = date('d');
            } // mm-yyyy
            elseif (preg_match('~^\d{2}\-\d{4}$~', $time)) {
                list($mon, $year) = explode('-', $time);
                $day = date('d');
            } // mm/yyyy
            elseif (preg_match('~^\d{2}\/\d{4}$~', $time)) {
                list($mon, $year) = explode('/', $time);
                $day = date('d');
            } // HH:ii:ss
            elseif (preg_match('~^\d{2}\:\d{2}\:\d{2}$~', $time)) {
                list($hour, $min, $sec) = explode(':', $time);
            } // HH:ii
            elseif (preg_match('~^\d{2}\:\d{2}$~', $time)) {
                list($hour, $min) = explode(':', $time);
            } else {
                return false;
            }

            // if the year set in two numbers
            if ($year < 100) {
                if ($year > date('y')) {
                    $year += 1900;
                } else {
                    $year += 2000;
                }
            }

            // check hours-minutes-seconds
            if ($hour > 23 || $min > 59 || $sec > 59) {
                return false;
            }

            // check date
            if (false === checkdate((int)$mon, (int)$day, (int)$year)) {
                return false;
            }

            return mktime((int)$hour, (int)$min, (int)$sec, (int)$mon, (int)$day, (int)$year);
        }

        /**
         * @param string          $format
         * @param null|string|int $time
         * @return string|null
         */
        public static function formatTo($format, $time = null)
        {
            return ($timestamp = self::timestampOf($time)) ? date((string)$format, $timestamp) : null;
        }

        /**
         * @param null|string|int $time
         * @return bool|null
         */
        public static function isWeekend($time = null)
        {
            return ($timestamp = self::timestampOf($time)) ? (date('w', $timestamp) % 6 === 0) : null;
        }
    }
