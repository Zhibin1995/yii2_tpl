<?php

namespace jeemoo\rbac\models;

use Yii;

/**
 * This is the model class for table "permission".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $title
 * @property string $route
 * @property string $rule
 * @property integer $child_count
 * @property integer $sort
 * @property integer $depth
 * @property integer $create_user
 * @property integer $create_at
 * @property integer $update_user
 * @property integer $update_at
 */
class Permission extends BaseModel
{
    const CACHE_KEY = 'permissions';

    public static function tableName()
    {
        return 'permission';
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'default' => [],
            'search' => ["title"],
            'create' => ["title", "parent_id", "icon", "route", 'rule'],
            'update' => ["title", "parent_id", "icon", "route", 'rule'],
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
            [['title'], 'string', 'max' => 50],
            [['route', 'rule'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => '父节点',
            'title' => '名称',
            'route' => '路由',
            'rule' => '规则',
            'child_count' => '子节点数量',
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
    private static function getPermissions()
    {
        $cache = Yii::$app->getCache();
        $items = $cache->get(self::CACHE_KEY);
        if ($items === false) {
            $items = Permission::find()->orderBy(['parent_id' => SORT_ASC, 'sort' => SORT_ASC])->indexBy('id')->all();
            $cache->set(self::CACHE_KEY, $items);
        }
        return $items;
    }

    /**
     * @param $id
     * @return Permission|null
     */
    public static function getItem($id)
    {
        $items = self::getPermissions();
        return isset($items[$id]) ? $items[$id] : null;
    }

    /**
     * @param $route
     * @return Menu|null
     */
    public static function getItemByRoute($route)
    {
        $items = self::getPermissions();

        $permission = null;
        foreach ($items as $item) {
            if ($item->route === $route) {
                $permission = $item;
                break;
            }
        }
        return $permission;
    }

    /**
     * @return Permission|null
     */
    public function getParent()
    {
        if (empty($this->parent_id)) {
            return null;
        }
        return Permission::getItem($this->parent_id);
    }

    /**
     * @return int
     */
    public function setSortNum()
    {
        $nextNum = 0;
        $items = self::getPermissions();
        foreach ($items as $item) {
            if ($item->parent_id != $this->parent_id) {
                continue;
            }
            $nextNum = max($item->sort, $nextNum);
        }
        $this->sort = $nextNum + 1;
    }

    /**
     * @return Permission[]
     */
    public static function getSelectOptions()
    {
        $menuItems = [];
        $items = self::getPermissions();
        foreach ($items as $item) {
            if ($item->parent_id != 0) {
                continue;
            }
            $menuItems[$item->id] = $item->title;
            foreach ($items as $subItem) {
                if ($subItem->parent_id != $item->id) {
                    continue;
                }
                $menuItems[$subItem->id] = $item->title.' / ' . $subItem->title;
            }
        }
        return $menuItems;
    }

    /**
     * @return Permission[]
     */
    public function getChildren()
    {
        $menuItems = [];
        $items = self::getPermissions();
        foreach ($items as $item) {
            if ($item->parent_id != $this->id) {
                continue;
            }
            $menuItems[] = $item;
        }
        return $menuItems;
    }


    /**
     * @param int $parentId
     * @return Permission[]
     */
    public static function getUserItems($parentId = 0)
    {
        $menuItems = [];
        $items = self::getPermissions();

        foreach ($items as $item) {
            if ($item->parent_id != $parentId) {
                continue;
            }
            if (!empty($item->route) && !Yii::$app->user->can($item->route)) {
                continue;
            }
            if (empty($item->route) && !self::getUserItems($item->id)) {
                continue;
            }
            $menuItems[] = $item;
        }
        return $menuItems;
    }
}
