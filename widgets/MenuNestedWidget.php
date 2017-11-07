<?php

    namespace papalapa\yiistart\widgets;

    use papalapa\yiistart\modules\menu\models\Menu;
    use papalapa\yiistart\modules\menu\models\MenuQuery;
    use yii\base\InvalidParamException;
    use yii\db\ActiveQuery;

    /**
     * Class MenuNestedWidget
     * @see     Menu
     * @package papalapa\yiistart\widgets
     */
    class MenuNestedWidget extends \yii\widgets\Menu
    {
        /**
         * @var ActiveQuery
         */
        public $query;
        /**
         * @var string
         */
        public $parentAttribute = 'parent_id';
        /**
         * @var string
         */
        public $levelAttribute = 'level';
        /**
         * @var string
         */
        public $position;

        /**
         * @inheritdoc
         */
        public function init()
        {
            if (is_null($this->query)) {
                $this->query = Menu::find();
            } elseif (!($this->query instanceof MenuQuery)) {
                throw new InvalidParamException('Query attribute must be an instance of MenuQuery');
            }

            /**
             * @var $models array|Menu[]
             * ```php
             * [
             *      ['id' => 1, 'name' => 1, 'parent_id' => null, 'level' => 0, 'url' => 1],
             *      ['id' => 2, 'name' => 2, 'parent_id' => null, 'level' => 0, 'url' => 2],
             *      ['id' => 11, 'name' => 11, 'parent_id' => 1, 'level' => 1, 'url' => 11],
             *      ['id' => 111, 'name' => 111, 'parent_id' => 11, 'level' => 2, 'url' => 111],
             *      ['id' => 22, 'name' => 22, 'parent_id' => 2, 'level' => 1, 'url' => 22],
             *      ['id' => 222, 'name' => 222, 'parent_id' => 22, 'level' => 2, 'url' => 222],
             *      ['id' => 2222, 'name' => 2222, 'parent_id' => 222, 'level' => 3, 'url' => 2222],
             * ]
             * ```
             */
            $models = $this->query->andFilterWhere(['position' => $this->position])->andWhere(['is_active' => true])
                                  ->orderBy(['order' => SORT_ASC])->asArray()->indexBy('id')->all();

            if (empty($models)) {
                return null;
            }

            $this->items = $this->prepareItems($models);

            parent::init(); // TODO: Change the autogenerated stub
        }

        /**
         * Parse all of models into array with level as key and items as value first,
         * then run foreach mode and put it into the parent children attribute
         * Return data will looks like a tree:
         * ```php
         * [
         *      parent1 => [..., children1 => [..., children11 => [...,]]]
         *      parent2 => [..., children2 => [..., children22 => [...,]]]
         * ]
         * ```
         * @param array $items
         * @return mixed
         */
        protected function prepareItems($items)
        {
            $levels = [];

            foreach ($items as $item) {
                $levels[$item['level']][$item['id']] = $item;
            }

            // sort by level down
            krsort($levels);

            // get max nest level
            $level = max(array_keys($levels));

            while ($level > 0) {
                foreach ($levels[$level] as $id => $item) {
                    if ($item['parent_id']) {
                        $levels[$level - 1][$item['parent_id']]['children'][$id] = $item;
                        unset($levels[$level][$id]);
                    }
                }
                if (empty($levels[$level])) {
                    unset($levels[$level]);
                }
                $level--;
            }

            return $this->createNestedTree(reset($levels));
        }

        /**
         * @param $tree
         * @return array
         */
        protected function createNestedTree($tree)
        {
            $output = [];
            foreach ($tree as $branch) {
                $output[] = [
                    'label' => $branch['name'],
                    'url'   => $branch['url'],
                    'items' => array_key_exists('children', $branch) && !empty($branch['children'])
                        ? $this->createNestedTree($branch['children']) : null,
                ];
            }

            return $output;
        }
    }
