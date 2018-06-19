<?php

namespace jeemoo\rbac\models;


use Yii;
use yii\base\Exception;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user_role".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $role_id
 * @property integer $create_user
 * @property integer $create_at
 * @property integer $update_user
 * @property integer $update_at
 *
 * @property IdentityInterface $user
 * @property Role $role
 */
class UserRole extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'role_id'], 'required'],
            [['user_id', 'role_id', 'create_user', 'create_at', 'update_user', 'update_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户',
            'role_id' => '角色',
            'create_user' => 'Create User',
            'create_at' => 'Create At',
            'update_user' => 'Update User',
            'update_at' => 'Update At',
        ];
    }

  /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    /**
     * @param $user
     * @return string
     */
    public static function getUserRoleCacheKey($user)
    {
        if (empty($user)) {
            throw new \InvalidArgumentException('$user is not invalid');
        }
        return "user_roles_" . $user->id;
    }

    /**
     * @param $user
     */
    public static function removeUserRoleFromCache($user)
    {
        $cache = Yii::$app->getCache();
        $cacheKey = self::getUserRoleCacheKey($user);
        $cache->delete($cacheKey);
    }

    /**
     * @param $user
     */
    public static function getUserRolesFromCache($user)
    {
        $cache = Yii::$app->getCache();
        $cacheKey = self::getUserRoleCacheKey($user);
        $cache->get($cacheKey);
    }

    /**
     * @param $user IdentityInterface
     * @return Role[]
     */
    public static function getUserRoles($user)
    {
        if (empty($user)) {
            throw new \InvalidArgumentException('$user is not invalid');
        }
        $cache = Yii::$app->getCache();
        $cacheKey = self::getUserRoleCacheKey($user);
        $roles = $cache->get($cacheKey);
        if ($roles !== false) {
            return $roles;
        }

        /**
         * @var UserRole[] $items
         */
        $items = UserRole::find()->where(['user_id' => $user->getId()])->all();

        $roles = [];
        foreach ($items as $item) {
            $roles[] = $item->role;
        }
        $cache->set($cacheKey, $roles);
        return $roles;
    }

    /**
     * @param $user IdentityInterface
     * @return string
     */
    public static function getRoleNames($user)
    {
        $names = '';
        $roles = self::getUserRoles($user);
        foreach ($roles as $role) {
            $names .= (empty($names) ? '' : '，') . $role->name;
        }
        return $names;
    }
}
