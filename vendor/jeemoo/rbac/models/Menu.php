<?php

namespace jeemoo\rbac\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $icon
 * @property string $title
 * @property string $route
 * @property integer $child_count
 * @property integer $sort
 * @property integer $depth
 * @property integer $create_user
 * @property integer $create_at
 * @property integer $update_user
 * @property integer $update_at
 */
class Menu extends BaseModel
{
    const CACHE_KEY = 'menus';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'default' => [],
            'search' => ["title"],
            'create' => ["title", "parent_id", "icon", "route",],
            'update' => ["title", "parent_id", "icon", "route",],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'title', 'sort'], 'required'],
            [['parent_id', 'child_count', 'sort', 'create_user', 'create_at', 'update_user', 'update_at'], 'integer'],
            [['icon', 'title'], 'string', 'max' => 50],
            [['route'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => '父菜单',
            'icon' => '图标',
            'title' => '名称',
            'route' => '路由',
            'child_count' => '子菜单数量',
            'sort' => '排序',
            'create_user' => 'Create User',
            'create_at' => 'Create At',
            'update_user' => 'Update User',
            'update_at' => 'Update At',
        ];
    }

    public function afterDelete()
    {
        Yii::$app->getCache()->delete(self::CACHE_KEY);
        parent::afterDelete();
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->getCache()->delete(self::CACHE_KEY);
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return Menu[]
     */
    private static function getMenus()
    {
        $cache = Yii::$app->getCache();
        $menus = $cache->get(self::CACHE_KEY);
        if ($menus === false) {
            $menus = Menu::find()->orderBy(['parent_id' => SORT_ASC, 'sort' => SORT_ASC])->indexBy('id')->all();
            $cache->set(self::CACHE_KEY, $menus);
        }
        return $menus;
    }

    /**
     * @param $id
     * @return Menu|null
     */
    public static function getItem($id)
    {
        $items = self::getMenus();
        return isset($items[$id]) ? $items[$id] : null;
    }

    /**
     * @return Menu[]
     */
    public function getChildren()
    {
        $menuItems = [];
        $items = self::getMenus();
        foreach ($items as $item) {
            if ($item->parent_id != $this->id) {
                continue;
            }
            $menuItems[] = $item;
        }
        return $menuItems;
    }

    public function setSortNum()
    {
        $nextNum = 0;
        $items = self::getMenus();
        foreach ($items as $item) {
            if ($item->parent_id != $this->parent_id) {
                continue;
            }
            $nextNum = max($item->sort, $nextNum);
        }
        $this->sort = $nextNum + 1;
    }

    /**
     * @return Menu[]
     */
    public static function getSelectOptions()
    {
        $menuItems = [];
        $items = self::getMenus();
        foreach ($items as $item) {
            if ($item->parent_id != 0) {
                continue;
            }
            $menuItems[$item->id] = $item->title;
        }
        return $menuItems;
    }

    /**
     * @param int $parentId
     * @return Menu[]
     */
    public static function getUserMenus($parentId = 0)
    {
        $menuItems = [];
        $items = self::getMenus();

        foreach ($items as $item) {
            if ($item->parent_id != $parentId) {
                continue;
            }

            if (!empty($item->route) && !Yii::$app->user->can($item->route)) {
                continue;
            }
            if (empty($item->route) && !self::getUserMenus($item->id)) {
                continue;
            }
            $menuItems[] = $item;
        }
        return $menuItems;
    }

}
