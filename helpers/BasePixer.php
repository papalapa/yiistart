<?php

    namespace papalapa\yiistart\helpers;

    use Imagine\Exception\InvalidArgumentException;
    use Imagine\Exception\RuntimeException;
    use Imagine\Gd\Imagine;
    use Imagine\Image\Box;
    use Imagine\Image\ImageInterface;
    use Imagine\Image\Point;
    use yii;

    /**
     * Class BasePixer
     * @package papalapa\yiistart\helpers
     */
    abstract class BasePixer extends yii\base\Object
    {
        const WATERMARK_MARGIN = 0.1; // watermark margin in percent of original image
        const WATERMARK_RATIO  = 0.3; // watermark size in percent of original image
        /**
         * Processing image path
         * Is a local directory, f.e. '...yii/frontend/web/upload/news/1234/AB01.jpg'
         * @var string
         */
        public $path;
        /**
         * Crop resulted thumb
         * @var bool
         */
        public $crop = true;
        /**
         * Quality of thumb image
         * @var integer
         */
        public $quality = 92;
        /**
         * Processing image instance
         * @var ImageInterface
         */
        private $image;
        /**
         * Box of processing image
         * @var Box
         */
        private $box;
        /**
         * Width of resulted thumb
         * @var integer
         */
        private $width;
        /**
         * Height of resulted thumb
         * @var integer
         */
        private $height;
        /**
         * Watermark instance
         * @var ImageInterface
         */
        private $watermark;
        /**
         * Result thumb path
         * @var string
         */
        protected $thumbName;

        /**
         * Path could not contain any '..' sub folders
         * @param $path
         * @return $this|bool|null
         */
        public function path($path)
        {
            // Reinitialize variables
            $this->path      = $path;
            $this->image     = null;
            $this->box       = null;
            $this->width     = null;
            $this->height    = null;
            $this->watermark = null;
            $this->thumbName = null;

            if (mb_substr($this->path, 0, 1) !== '/' || false !== mb_strpos($this->path, '..')) {
                \Yii::warning('Image path is not correct');

                return $this;
            }

            ini_set('memory_limit', '256M');

            try {
                $this->image = (new Imagine())->open($this->findFrom($this->path));
            } catch (InvalidArgumentException $e) {
                \Yii::warning($e->getMessage());
                $this->image = (new Imagine())->open($this->processingImage());
            } catch (RuntimeException $e) {
                \Yii::warning($e->getMessage());
                $this->image = (new Imagine())->open($this->processingImage());
            }

            return $this;
        }

        /**
         * @param integer $width
         * @param integer $height
         * @return $this
         */
        public function thumb($width, $height)
        {
            if ($this->image) {
                $this->box = $this->image->getSize();
                if ($width > $this->box->getWidth() || $height > $this->box->getHeight()) {
                    if ($width > $this->box->getWidth() && $height <= $this->box->getHeight()) {
                        $this->box->widen($width);
                    } elseif ($width <= $this->box->getWidth() && $height > $this->box->getHeight()) {
                        $this->box->heighten($height);
                    } else {
                        $this->box->scale(max($width / $this->box->getWidth(), $height / $this->box->getHeight()));
                    }
                }
                $this->width  = $width;
                $this->height = $height;
            }

            return $this;
        }

        /**
         * @param integer $width
         * @return $this
         */
        public function widen($width)
        {
            if ($this->image) {
                $this->box    = $this->image->getSize()->widen($width);
                $this->width  = $this->box->getWidth();
                $this->height = $this->box->getHeight();
            }

            return $this;
        }

        /**
         * @param integer $height
         * @return $this
         */
        public function heighten($height)
        {
            if ($this->image) {
                $this->box    = $this->image->getSize()->heighten($height);
                $this->width  = $this->box->getWidth();
                $this->height = $this->box->getHeight();
            }

            return $this;
        }

        /**
         * @param integer $size
         * @return $this
         */
        public function increase($size)
        {
            if ($this->image) {
                $this->box    = $this->image->getSize()->increase($size);
                $this->width  = $this->box->getWidth();
                $this->height = $this->box->getHeight();
            }

            return $this;
        }

        /**
         * @param float $ratio
         * @return $this
         */
        public function scale($ratio)
        {
            if ($this->image) {
                $this->box    = $this->image->getSize()->scale($ratio);
                $this->width  = $this->box->getWidth();
                $this->height = $this->box->getHeight();
            }

            return $this;
        }

        /**
         * @return $this
         */
        public function watermarked()
        {
            $this->watermark = (new Imagine())->open($this->watermarkPath());

            return $this;
        }

        /**
         * @param bool $redraw
         * @return $this
         */
        public function save($redraw = false)
        {
            if ($this->box) {

                $this->thumbName = $this->generateThumbName();
                clearstatcache(true, $this->saveTo($this->thumbName));
                $thumbExists = file_exists($this->saveTo($this->thumbName)) && is_file($this->saveTo($this->thumbName));

                if ($redraw || !$thumbExists) {

                    // 1. Creating directory
                    if (!is_dir(dirname($this->saveTo($this->thumbName)))) {
                        mkdir(dirname($this->saveTo($this->thumbName)), 0755, true);
                    }

                    // 2. Resize image to new box size
                    $this->image = $this->image->resize($this->box);

                    // 3. Crop image if new width and height is different to new box (thumb method)
                    if ($this->width <> $this->box->getWidth() || $this->height <> $this->box->getHeight()) {
                        $crop        = $this->crop ? ImageInterface::THUMBNAIL_OUTBOUND : ImageInterface::THUMBNAIL_INSET;
                        $this->image = $this->image->thumbnail(new Box($this->width, $this->height), $crop);
                    }

                    // 4. Paste watermark on image
                    if ($this->watermark) {

                        // Get boxes of image and watermark
                        $imageBox     = new Box($this->width, $this->height);
                        $watermarkBox = $this->watermark->getSize();

                        // Checks ratio of both boxes to calculate watermark box
                        if ($imageBox->getWidth() / $imageBox->getHeight() >= $watermarkBox->getWidth() / $watermarkBox->getHeight()) {
                            $watermarkBox = $watermarkBox->heighten($imageBox->getHeight() * self::WATERMARK_RATIO);
                        } else {
                            $watermarkBox = $watermarkBox->widen($imageBox->getWidth() * self::WATERMARK_RATIO);
                        }

                        // Resize watermark box
                        $this->watermark = $this->watermark->resize($watermarkBox);

                        // Past watermark on image
                        $this->image = $this->image->paste($this->watermark, new Point(
                            $imageBox->getWidth() * (1 - self::WATERMARK_MARGIN) - $watermarkBox->getWidth(),
                            $imageBox->getHeight() * (1 - self::WATERMARK_MARGIN) - $watermarkBox->getHeight()
                        ));
                    }

                    // 5. Save image
                    $this->image->save($this->saveTo($this->thumbName), ['quality' => $this->quality]);
                }
            }

            return $this;
        }

        /**
         * @return ImageInterface
         */
        public function getImage()
        {
            if (!$this->image) {
                Yii::warning('Image is not initialized');
            }

            return $this->image;
        }

        /**
         * @param bool $redraw
         * @return string
         */
        public function getLink($redraw = false)
        {
            if (!$this->thumbName) {
                $this->save($redraw);
            }

            return $this->thumbName;
        }

        /**
         * Return html <IMG> tag
         * @param array $options
         * @return string
         */
        public function getHtml($options = [])
        {
            $options['width']  = $this->width;
            $options['height'] = $this->height;

            return yii\helpers\Html::img($this->getLink(), $options);
        }

        /**
         * Set default image path to imagine processing
         * @return string
         */
        protected function processingImage()
        {
            return \Yii::getAlias('@vendor/papalapa/yii2/assets/img/default.png');
        }

        /**
         * @return string
         */
        protected function generateThumbName()
        {
            /* @var $dirname string */
            /* @var $basename string */
            /* @var $filename string */
            /* @var $extension string */
            extract(pathinfo($this->path));

            /**
             * How to save the thumb image
             * Like this: /upload/news/1234/thumb_ABCD_400x300_c1_w1_q90.jpg
             */
            return implode('_', [
                // thumb prefix
                $dirname.'/thumb',
                // original filename
                $filename,
                // thumb size
                $this->width.'x'.$this->height,
                // crop flag
                'c'.(int)$this->crop,
                // watermark flag
                'w'.(int)(bool)$this->watermark,
                // quality flag
                'q'.(int)$this->quality,
            ]).'.'.$extension;
        }

        /**
         * Where to find the image
         * For example: Yii::getAlias("@webroot{$findFrom}")
         * @param $path
         * @return mixed
         */
        abstract protected function findFrom($path);

        /**
         * Where to save the image
         * For example: Yii::getAlias("@webroot{$saveTo}")
         * @param $path
         * @return mixed
         */
        abstract protected function saveTo($path);

        /**
         * Where to find watermark file
         * For example: Yii::getAlias('@webroot/img/watermark.png')
         * @return string
         */
        abstract protected function watermarkPath();
    }