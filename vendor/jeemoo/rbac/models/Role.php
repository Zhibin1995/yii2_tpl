<?php

namespace jeemoo\rbac\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "role".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $create_user
 * @property integer $create_at
 * @property integer $update_user
 * @property integer $update_at
 *
 * @property Permission[] $permissions
 */
class Role extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role';
    }

    public function scenarios()
    {
        return [
            'default' => [],
            'search' => ['name'],
            'create' => ['name', 'description'],
            'update' => ['name', 'description'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_user', 'create_at', 'update_user', 'update_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '角色名称',
            'description' => '角色介绍',
            'create_user' => 'Create User',
            'create_at' => 'Create At',
            'update_user' => 'Update User',
            'update_at' => 'Update At',
        ];
    }

    public function afterDelete()
    {
        $this->removePermissionsFromCache();
        parent::afterDelete();
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->removePermissionsFromCache();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @param $filter
     * @return array
     */
    public static function getSelectOptions($filter)
    {
        $items = Role::find()->where($filter)->orderBy('id desc')->all();
        return ArrayHelper::map($items, 'id', 'name');
    }

    /**
     * @return string
     */
    public function getPermissionsCacheKey()
    {
        return "role_" . $this->id;
    }

    /**
     *
     */
    public function removePermissionsFromCache()
    {
        $cache = Yii::$app->getCache();
        $cacheKey = $this->getPermissionsCacheKey();

        $cache->delete($cacheKey);
    }

    /**
     *
     */
    public function getPermissionsFromCache()
    {
        $cache = Yii::$app->getCache();
        $cacheKey = $this->getPermissionsCacheKey();
        return $cache->get($cacheKey);
    }

    /**
     * @return Permission[]
     */
    public function getPermissions()
    {
        $permissions = $this->getPermissionsFromCache();
        if ($permissions !== false) {
            return $permissions;
        }

        /**
         * @var RolePermission[] $items
         */
        $items = RolePermission::find()->where(['role_id' => $this->id])->all();

        $permissions = [];
        foreach ($items as $item) {
            $permissions[$item->permission_id] = $item->permission;
        }

        $cache = Yii::$app->getCache();
        $cacheKey = $this->getPermissionsCacheKey();
        $cache->set($cacheKey, $permissions);
        return $permissions;
    }

    /**
     * @param $id
     * @return Permission|null
     */
    public function getPermission($id)
    {
        $permissions = $this->getPermissions();
        if (isset($permissions[$id])) {
            return $permissions[$id];
        }
        return null;
    }

}
