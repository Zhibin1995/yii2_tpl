<?php

namespace jeemoo\rbac\controllers;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use jeemoo\rbac\models\Menu;
use jeemoo\rbac\common\BaseController;

class MenuController extends BaseController
{
    public function actionIndex($id = 0)
    {
        $parent = null;
        if ($id !== 0 && ($parent = Menu::getItem($id)) == null) {
            throw new NotFoundHttpException();
        }

        $model = new Menu('search');
        $model->load(Yii::$app->request->post());

        $query = Menu::find()->orderBy('sort asc');
        $query->andWhere(['parent_id' => $id]);
        $query->andFilterWhere(['like', 'title', $model->title]);

        $items = $query->all();

        return $this->render('@jeemoo/rbac/views/menu/index', ['model' => $model, 'parent' => $parent, 'items' => $items]);
    }

    public function  actionCreate($id = 0)
    {
        $parent = null;
        if (!empty($id) && !($parent = Menu::getItem($id))) {
            throw new NotFoundHttpException();
        }
        if ($parent && $parent->depth > 1) {
            throw new NotFoundHttpException();
        }

        $model = new Menu('create');
        $model->depth = 1;
        $model->parent_id = $id;
        if (!empty($parent)) {
            $model->depth = $parent->depth + 1;
        }

        $menus = Menu::getSelectOptions();
        if (!Yii::$app->request->getIsAjax()) {
            return $this->render('@jeemoo/rbac/views/menu/edit', ['model' => $model, 'menus' => $menus]);
        }

        if (!$model->load(Yii::$app->request->post())) {
            return $this->errorJson('参数错误');
        }
        $trans = Yii::$app->db->beginTransaction();
        try {
            $model->setSortNum();
            $model->save();

            if ($model->parent_id) {
                $parent = Menu::getItem($model->parent_id);
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

    public function  actionUpdate($id)
    {
        $model = Menu::findOne($id);
        if ($model == null) {
            throw new NotFoundHttpException();
        }

        $model->setScenario('update');
        $menus = Menu::getSelectOptions();
        if (!Yii::$app->request->getIsAjax()) {
            return $this->render('@jeemoo/rbac/views/menu/edit', ['model' => $model, 'menus' => $menus]);
        }

        if (!$model->load(Yii::$app->request->post())) {
            return $this->errorJson();
        }
        $trans = Yii::$app->db->beginTransaction();
        try {
            $parentId = $model->getOldAttribute('parent_id');
            if ($model->parent_id != $parentId) {
                if (!empty($parentId)) {
                    $oldParent = Menu::getItem($parentId);
                    $oldParent->child_count--;
                    $oldParent->save();
                }
                if (!empty($model->parent_id)) {
                    $parentId = $model->parent_id;
                    $parent = Menu::getItem($parentId);
                    if ($parent->depth > 1) {
                        throw new InvalidConfigException();
                    }
                    $parent->child_count++;
                    $parent->save();

                    $model->depth = $parent->depth + 1;
                }
                $children = $model->getChildren();
                foreach ($children as $child) {
                    $child->depth = $model->depth + 1;
                    $child->save();
                }
                $model->setSortNum();
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
        $model = Menu::findOne($id);
        if ($model == null) {
            return $this->errorJson();
        }

        if ($model->child_count > 0) {
            return $this->errorJson('该菜单无法删除，请先删除子节点');
        }
        $trans = Yii::$app->db->beginTransaction();
        try {
            $model->delete();

            if ($model->parent_id) {
                $parent = Menu::getItem($model->parent_id);
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

        $list = Menu::findAll(['parent_id' => $id]);
        if (count($list) != count($arr)) {
            return $this->errorJson();
        }
        $trans = Yii::$app->db->beginTransaction();
        try {
            foreach ($arr as $key => $val) {
                foreach ($list as $item) {
                    if ($item->id == $val) {
                        $item->sort = $key + 1;
                        $item->save(false);
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
