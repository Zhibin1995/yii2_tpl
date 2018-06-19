<?php
namespace backend\controllers;


use backend\common\BaseController;
use common\models\User;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class UserController extends BaseController
{
    public function actionIndex()
    {
        $model = new User('search');
        $model->load(Yii::$app->request->post());

        $data = User::find()->where(['status' => User::STATUS_ACTIVE]);
        $data->andFilterWhere(['like', 'username', $model->username]);
        $data->andFilterWhere(['like', 'mobile', $model->mobile]);

        $pager = new Pagination(['totalCount' => $data->count()]);
        $items = $data->offset($pager->offset)->limit($pager->limit)->orderBy('id desc')->all();

        return $this->render('index', ['model' => $model, 'items' => $items, 'pager' => $pager]);
    }

    public function  actionCreate()
    {
        $model = new User('create');
        if (!Yii::$app->request->getIsAjax()) {
            return $this->render('create', ['model' => $model]);
        }

        if (!$model->load(Yii::$app->request->post())) {
            return $this->errorJson();
        }
        $model->generateAuthKey();
        if (!$model->validate()) {
            return $this->errorJson($model->getErrorMsg());
        }

        $model->setPassword($model->password);
        $model->status = User::STATUS_ACTIVE;
        $model->save(true, false);
        return $this->successJson();
    }

    public function  actionUpdate($id)
    {
        $model = User::findOne($id);
        if ($model == null || $model->status != User::STATUS_ACTIVE) {
            throw new NotFoundHttpException();
        }

        $model->setScenario('update');
        if (!Yii::$app->request->getIsAjax()) {
            return $this->render('update', ['model' => $model]);
        }

        if (!$model->load(Yii::$app->request->post())) {
            return $this->errorJson();
        }
        $model->save();

        return $this->successJson();
    }

    public function actionPassword($id)
    {
        $model = User::findOne($id);
        if ($model == null || $model->status != User::STATUS_ACTIVE) {
            throw new NotFoundHttpException();
        }

        $model->setScenario('password');
        $model->password = null;
        if (!Yii::$app->request->getIsAjax()) {
            return $this->render('password', ['model' => $model]);
        }

        if (!$model->load(Yii::$app->request->post())) {
            return $this->errorJson();
        }
        if (!$model->validate()) {
            return $this->errorJson($model->getErrorMsg());
        }

        $model->setPassword($model->password);
        $model->save(true, false);

        return $this->successJson();
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        $model = User::findOne($id);
        if (empty($model) || $model->is_super) {
            return $this->errorJson();
        }

        $model->status = User::STATUS_DELETED;
        if (!$model->save()) {
            return $this->errorJson($model->getErrorMsg());
        }

        return $this->successJson(null, self::CALLBACK_REFRESH);
    }
}