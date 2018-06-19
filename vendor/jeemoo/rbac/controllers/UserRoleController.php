<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/5
 * Time: 10:47
 */

namespace jeemoo\rbac\controllers;

use Yii;
use jeemoo\rbac\common\BaseController;
use jeemoo\rbac\models\Role;
use jeemoo\rbac\models\UserRole;
use yii\web\NotFoundHttpException;

class UserRoleController extends BaseController
{
    public function actionEdit($id)
    {
        $user = call_user_func(array(Yii::$app->user->identityClass, 'findIdentity'), $id);;
        if (empty($user)) {
            throw new NotFoundHttpException();
        }

        $roles = Role::find()->all();
        $userRoles = UserRole::getUserRoles($user);
        if (!Yii::$app->request->getIsAjax()) {
            return $this->render('@jeemoo/rbac/views/user-role/edit', [
                'user' => $user, 'roles' => $roles, 'userRoles' => $userRoles
            ]);
        }

        $trans = Yii::$app->db->beginTransaction();
        try {
            UserRole::deleteAll(['user_id' => $id]);

            $roles = Yii::$app->request->post('roles', []);
            foreach ($roles as $role) {
                $userRole = new UserRole();
                $userRole->user_id = $id;
                $userRole->role_id = $role;
                $userRole->save();
            }
            UserRole::removeUserRoleFromCache($user);
            $trans->commit();
            return $this->successJson();
        } catch (\Exception $ex) {
            Yii::error($ex);
            $trans->rollBack();
        }
        return $this->errorJson();
    }
}