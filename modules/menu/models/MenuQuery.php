<?php

    namespace papalapa\yiistart\modules\menu\models;

    use papalapa\yiistart\models\MultilingualActiveQuery;

    /**
     * Class MenuQuery
     * This is the ActiveQuery class for [[Menu]].
     * @package papalapa\yiistart\modules\menu\models
     */
    class MenuQuery extends MultilingualActiveQuery
    {
        /**
         * Only active links
         * @return $this
         */
        public function visible()
        {
            return $this->andWhere(['[[is_active]]' => 1]);
        }

        /**
         * Only invisible links
         * @return $this
         */
        public function hidden()
        {
            return $this->andWhere(['[[is_active]]' => 0]);
        }

        /**
         * Header links (all visibilities)
         * @return $this
         */
        public function top()
        {
            return $this->visible()
                        ->andWhere(['[[position]]' => Menu::POSITION_TOP])
                        ->orderBy(['[[sort]]' => SORT_ASC]);
        }

        /**
         * Header links (all visibilities)
         * @return $this
         */
        public function main()
        {
            return $this->visible()
                        ->andWhere(['[[position]]' => Menu::POSITION_MAIN])
                        ->orderBy(['[[sort]]' => SORT_ASC]);
        }

        /**
         * Footer links (all visibilities)
         * @return $this
         */
        public function bottom()
        {
            return $this->visible()
                        ->andWhere(['[[position]]' => Menu::POSITION_BOTTOM])
                        ->orderBy(['[[sort]]' => SORT_ASC]);
        }
    }
