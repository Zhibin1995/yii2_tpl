<?php
namespace jeemoo\rbac\controllers;

use common\models\User;
use jeemoo\rbac\common\BaseController;
use jeemoo\rbac\models\Permission;
use jeemoo\rbac\models\Role;
use jeemoo\rbac\models\RolePermission;
use jeemoo\rbac\models\UserRole;
use Yii;
use yii\base\Exception;
use yii\data\Pagination;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class RoleController extends BaseController
{
    public function actionIndex()
    {
        $model = new Role('search');
        $model->load(Yii::$app->request->post());

        $data = Role::find();
        $data->andFilterWhere(['like', 'name', $model->name]);

        $pager = new Pagination(['totalCount' => $data->count()]);
        $items = $data->offset($pager->offset)->limit($pager->limit)->orderBy('id desc')->all();

        return $this->render('@jeemoo/rbac/views/role/index', ['model' => $model, 'items' => $items, 'pager' => $pager]);
    }

    public function  actionCreate()
    {
        $model = new Role('create');

        if (!Yii::$app->request->getIsAjax()) {
            return $this->render('@jeemoo/rbac/views/role/create', ['model' => $model]);
        }

        if (!$model->load(Yii::$app->request->post())) {
            return $this->errorJson('参数错误');
        }

        $trans = Yii::$app->db->beginTransaction();
        try {
            $model->save();
            $permissions = Yii::$app->request->post('permissions', []);
            foreach ($permissions as $perm) {
                $permission = Permission::getItem($perm);
                if (empty($permission) || !Yii::$app->user->can($permission->route, $permission->id)) {
                    throw new ForbiddenHttpException();
                }
                $item = new RolePermission();
                $item->role_id = $model->id;
                $item->permission_id = $perm;
                $item->save();
            }
            $trans->commit();
            return $this->successJson();
        } catch (\Exception $ex) {
            Yii::error($ex);
            $trans->rollBack();
        }
        return $this->errorJson();
    }

    public function  actionUpdate($id)
    {
        $model = Role::findOne($id);
        if ($model == null) {
            throw new NotFoundHttpException();
        }

        $model->setScenario('update');
        if (!Yii::$app->request->getIsAjax()) {
            return $this->render('@jeemoo/rbac/views/role/update', ['model' => $model]);
        }

        if (!$model->load(Yii::$app->request->post())) {
            return $this->errorJson('参数错误');
        }

        $trans = Yii::$app->db->beginTransaction();
        try {
            foreach ($model->getPermissions() as $permission) {
                if (Yii::$app->user->can($permission->route)) {
                    RolePermission::deleteAll(['role_id' => $model->id, 'permission_id' => $permission->id]);
                }
            }

            $permissions = Yii::$app->request->post('permissions', []);
            foreach ($permissions as $perm) {
                $permission = Permission::getItem($perm);
                if (empty($permission) || !Yii::$app->user->can($permission->route, $permission->id)) {
                    throw new ForbiddenHttpException();
                }
                $item = new RolePermission();
                $item->role_id = $model->id;
                $item->permission_id = $perm;
                $item->save();
            }
            $model->save();
            $trans->commit();
            return $this->successJson();
        } catch (\Exception $ex) {
            Yii::error($ex);
            $trans->rollBack();
        }
        return $this->errorJson();
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        $model = Role::findOne($id);

        if (empty($model)) {
            return $this->errorJson();
        }

        if (UserRole::find()->where(['role_id' => $id])->exists()) {
            return $this->errorJson('该角色在使用中，无法删除');
        }
        $trans = Yii::$app->db->beginTransaction();
        try {
            RolePermission::deleteAll(['role_id' => $id]);
            $model->delete();

            $trans->commit();
            return $this->successJson(null, self::CALLBACK_REFRESH);
        } catch (\Exception $ex) {
            Yii::error($ex);
            $trans->rollBack();
        }
        return $this->errorJson();
    }
}