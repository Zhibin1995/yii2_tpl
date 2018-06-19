<?php

namespace jeemoo\rbac\controllers;


use jeemoo\rbac\common\BaseController;
use jeemoo\rbac\models\Permission;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class PermissionController extends BaseController
{
    public function actionIndex($id = 0)
    {
        $parent = null;
        if (!empty($id) && ($parent = Permission::getItem($id)) == null) {
            throw new NotFoundHttpException();
        }

        $model = new Permission();
        $model->setScenario('search');
        $model->load(Yii::$app->request->post());

        $query = Permission::find()->orderBy('sort asc');
        $query->andWhere(['parent_id' => $id]);
        $query->andFilterWhere(['like', 'title', $model->title]);

        $items = $query->all();

        return $this->render('@jeemoo/rbac/views/permission/index', ['model' => $model, 'items' => $items, 'parent' => $parent]);
    }

    public function  actionCreate($id = 0)
    {
        $parent = null;
        if (!empty($id) && !($parent = Permission::getItem($id))) {
            throw new NotFoundHttpException();
        }
        if ($parent && $parent->depth > 2) {
            throw new NotFoundHttpException();
        }

        $model = new Permission('create');
        $model->depth = 1;
        $model->parent_id = $id;
        if (!empty($parent)) {
            $model->depth = $parent->depth + 1;
        }

        $items = Permission::getSelectOptions();
        if (!Yii::$app->request->getIsAjax()) {
            return $this->render('@jeemoo/rbac/views/permission/edit', ['model' => $model, 'items' => $items]);
        }

        if (!$model->load(Yii::$app->request->post())) {
            return $this->errorJson('参数错误');
        }

        $trans = Yii::$app->db->beginTransaction();
        try {
            $model->setSortNum();
            $model->save();
            if (!empty($parent)) {
                $parent->child_count = $parent->child_count + 1;
                $parent->save();
            }
            $trans->commit();
            return $this->successJson();
        } catch (\Exception $ex) {
            Yii::error($ex);
            $trans->rollBack();
        }
        return $this->errorJson();
    }

    /**
     * @param $permission Permission
     */
    function updateChildren($permission)
    {
        $children = $permission->getChildren();
        foreach ($children as $child) {
            $child->depth = $permission->depth + 1;
            $permission->save();
            $this->updateChildren($child);
        }
    }

    public function  actionUpdate($id)
    {
        $model = Permission::getItem($id);
        if ($model == null) {
            throw new NotFoundHttpException();
        }

        $model->setScenario('update');
        $items = Permission::getSelectOptions();
        if (!Yii::$app->request->getIsAjax()) {
            return $this->render('@jeemoo/rbac/views/permission/edit', ['model' => $model, 'items' => $items]);
        }

        if (!$model->load(Yii::$app->request->post())) {
            return $this->errorJson('参数错误');
        }
        $trans = Yii::$app->db->beginTransaction();
        try {
            $parentId = $model->getOldAttribute('parent_id');
            if ($model->parent_id != $parentId) {
                if (!empty($parentId)) {
                    $parent = Permission::getItem($parentId);
                    $parent->child_count--;
                    $parent->save();
                }
                if (!empty($model->parent_id)) {
                    $parentId = $model->parent_id;
                    $parent = Permission::getItem($parentId);
                    if ($parent->depth > 2) {
                        throw new InvalidConfigException();
                    }
                    $parent->child_count++;
                    $parent->save();
                    $model->depth = $parent->depth + 1;
                }
                $model->setSortNum();
                $this->updateChildren($model);
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
        $model = Permission::findOne($id);
        if ($model == null) {
            return $this->errorJson();
        }

        if ($model->child_count > 0) {
            return $this->errorJson('该权限无法删除，请先删除子权限');
        }
        $trans = Yii::$app->db->beginTransaction();
        try {
            $model->delete();
            if ($model->parent_id) {
                $parent = Permission::getItem($model->parent_id);
                $parent->child_count = $parent->child_count - 1;
                $parent->save();
            }
            $trans->commit();
            return $this->successJson(null, self::CALLBACK_REFRESH);
        } catch (\Exception $ex) {
            Yii::error($ex);
            $trans->rollBack();
        }
        return $this->errorJson();

    }

    public function actionOrder($id)
    {
        $arr = Yii::$app->request->post('order', array());
        if (empty($arr)) {
            return $this->errorJson();
        }

        $list = Permission::findAll(['parent_id' => $id]);
        if (count($list) != count($arr)) {
            return $this->errorJson();
        }

        $trans = Yii::$app->db->beginTransaction();
        try {
            foreach ($arr as $key => $val) {
                foreach ($list as $item) {
                    if ($item->id == $val) {
                        $item->sort = $key + 1;
                        $item->save();
                        break;
                    }
                }
            }
            $trans->commit();
            return $this->successJson(null, null);
        } catch (\Exception $ex) {
            Yii::error($ex);
            $trans->rollBack();
        }
        return $this->errorJson();

    }
}
