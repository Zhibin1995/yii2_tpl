<?php

namespace jeemoo\rbac\common;

use jeemoo\rbac\models\Permission;
use jeemoo\rbac\models\RolePermission;
use jeemoo\rbac\models\UserRole;
use Yii;
use yii\rbac\CheckAccessInterface;

class AuthManager implements CheckAccessInterface
{
    public function checkAccess($userId, $route, $params = [])
    {
        if (Yii::$app->user->getIsGuest()) {
            return false;
        }

        $user = Yii::$app->user->getIdentity();
        if (empty($user)) {
            return false;
        }

        if ($user->is_super) {
            return true;
        }

        if (is_int($params)) {
            $permission = Permission::getItem($params);
        } else {
            $permission = Permission::getItemByRoute($route);
        }
        if (empty($permission)) {
            return false;
        }

        $hasPermission = false;

        $roles = UserRole::getUserRoles($user);
        foreach ($roles as $role) {
            while ($permission != null) {
                $rolePermission = $role->getPermission($permission->id);
                if (!empty($rolePermission)) {
                    $hasPermission = true;
                    break;
                }
                $permission = $role->getPermission($permission->parent_id);

            }
            if ($hasPermission) {
                break;
            }
        }

        return $hasPermission;
    }
}