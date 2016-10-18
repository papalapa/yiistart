<?php

    namespace papalapa\yii2start\helpers;

    use yii\helpers\FileHelper;

    /**
     * Class Filer
     * @package papalapa\yii2start\helpers
     */
    class Filer extends FileHelper
    {
        const BYTE_MULTIPLIER = 1024;

        /**
         * Converts kilobytes to bytes
         * @param $bytes
         * @return mixed
         */
        public static function KB2B($bytes)
        {
            return $bytes * self::BYTE_MULTIPLIER;
        }

        /**
         * Converts bytes to kilobytes
         * @param $bytes
         * @return string
         */
        public static function B2KB($bytes)
        {
            return self::_modify($bytes, self::BYTE_MULTIPLIER).' KB';
        }

        /**
         * Converts megabytes to bytes
         * @param $bytes
         * @return mixed
         */
        public static function MB2B($bytes)
        {
            return $bytes * pow(self::BYTE_MULTIPLIER, 2);
        }

        /**
         * Converts bytes to megabytes
         * @param $bytes
         * @return string
         */
        public static function B2MB($bytes)
        {
            return self::_modify($bytes, pow(self::BYTE_MULTIPLIER, 2)).' MB';
        }

        /**
         * Determine if in path is a file
         * @param $path
         * @return bool
         */
        public static function is_file($path)
        {
            clearstatcache(true, $path);

            return is_file($path);
        }

        /**
         * Determine if in path is a directory
         * @param $path
         * @return bool
         */
        public static function is_dir($path)
        {
            clearstatcache(true, $path);

            return is_dir($path);
        }

        /**
         * Determine if a file exists.
         * @param $path
         * @return bool
         */
        public static function exists($path)
        {
            clearstatcache(true, $path);

            return file_exists($path);
        }

        /**
         * Get the contents of a file.
         * <code>
         *    // Get the contents of a file
         *    $contents = File::get(path('app').'routes'.EXT);
         *    // Get the contents of a file or return a default value if it doesn't exist
         *    $contents = File::get(path('app').'routes'.EXT, 'Default Value');
         * </code>
         * @param      $path
         * @param null $default
         * @return null|string
         */
        public static function get($path, $default = null)
        {
            return self::exists($path) ? file_get_contents($path) : $default;
        }

        /**
         * Write to a file.
         * @param $path
         * @param $data
         * @return int
         */
        public static function put($path, $data)
        {
            return file_put_contents($path, $data, LOCK_EX);
        }

        /**
         * Append to a file.
         * @param $path
         * @param $data
         * @return int
         */
        public static function append($path, $data)
        {
            return file_put_contents($path, $data, LOCK_EX | FILE_APPEND);
        }

        /**
         * Delete a file.
         * @param $path
         * @return bool
         */
        public static function delete($path)
        {
            if (self::exists($path) && self::is_file($path)) {
                unlink($path);
            }

            return !self::exists($path);
        }

        /**
         * Move a file to a new location.
         * @param $path
         * @param $target
         * @return bool
         */
        public static function move($path, $target)
        {
            if (self::is_file($path)) {
                rename($path, $target);
            }

            return self::exists($target);
        }

        /**
         * Copy a file to a new location.
         * @param $path
         * @param $target
         * @return bool
         */
        public static function copy($path, $target)
        {
            copy($path, $target);

            return self::exists($target);
        }

        /**
         * Extract the file extension from a file path.
         * @param $path
         * @return mixed
         */
        public static function extension($path)
        {
            return pathinfo($path, PATHINFO_EXTENSION);
        }

        /**
         * Extract the file name from a file path.
         * @param $path
         * @return mixed
         */
        public static function filename($path)
        {
            return pathinfo($path, PATHINFO_FILENAME);
        }

        /**
         * Get the file type of a given file.
         * @param $path
         * @return string
         */
        public static function type($path)
        {
            clearstatcache(true, $path);

            return filetype($path);
        }

        /**
         * Get the file size of a given file.
         * @param $path
         * @return int
         */
        public static function size($path)
        {
            clearstatcache(true, $path);

            return filesize($path);
        }

        /**
         * Get the file's last modification time.
         * @param $path
         * @return int
         */
        public static function modified($path)
        {
            clearstatcache(true, $path);

            return filemtime($path);
        }

        /**
         * Determine if a file is a given type.
         * The Fileinfo PHP extension is used to determine the file's MIME type.
         * <code>
         *    // Determine if a file is a JPG image
         *    $jpg = File::is('path/to/file', 'jpg');
         *    // Determine if a file is one of a given list of types
         *    $image = File::is('path/to/file', ['jpg', 'png', 'gif']);
         * </code>
         * @param $path
         * @param $extensions array
         * @return bool
         */
        public static function is($path, $extensions)
        {
            $extensions         = (array)$extensions;
            $mime               = self::getMimeType($path);
            $possibleExtensions = self::getExtensionsByMimeType($mime);

            return count(array_intersect($extensions, $possibleExtensions)) > 0;
        }

        /**
         * Create a new directory.
         * @param     $path
         * @param int $chmod
         * @return bool
         */
        public static function mkdir($path, $chmod = 0755)
        {
            clearstatcache(true, $path);

            return self::is_dir($path) ?: mkdir($path, $chmod, true);
        }

        /**
         * Empty the specified directory.
         * @param $directory
         * @return bool
         */
        public static function cleandir($directory)
        {
            return static::rmdir($directory, true);
        }

        /**
         * Recursively delete a directory.
         * @param      $directory
         * @param bool $preserve
         * @return bool
         */
        public static function rmdir($directory, $preserve = false)
        {
            clearstatcache(true, $directory);

            if (!is_dir($directory)) {
                return false;
            }

            if ($dir = opendir($directory)) {
                while ($file = readdir($dir)) {
                    if (is_file($directory.DIRECTORY_SEPARATOR.$file)) {
                        unlink($directory.DIRECTORY_SEPARATOR.$file);
                    } elseif ($file !== '.' && $file !== '..' && is_dir($directory.DIRECTORY_SEPARATOR.$file)) {
                        static::rmdir($directory.DIRECTORY_SEPARATOR.$file);
                    }
                }
                closedir($dir);
            }

            if (!(bool)$preserve) {
                @rmdir($directory);
            }

            return true;
        }

        /**
         * Filesize modifier
         * @param $bytes
         * @param $modifier
         * @return int|string
         */
        private static function _modify($bytes, $modifier)
        {
            return ($total = $bytes / $modifier) ? number_format($total, 0, '.', ' ') : 0;
        }
    }