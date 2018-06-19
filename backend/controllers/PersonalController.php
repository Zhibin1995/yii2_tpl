<?php

namespace backend\controllers;

use backend\common\BaseController;
use common\models\User;
use common\utils\MessageUtils;
use Yii;
use yii\filters\AccessControl;

class PersonalController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index', ['model' => $this->getUser()]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionPassword()
    {
        $model = $this->getUser();
        $model->setScenario('changePassword');

        if (!Yii::$app->request->getIsAjax()) {
            return $this->render('password', array('model' => $model));
        }

        if (!$model->load(Yii::$app->request->post())) {
            return $this->errorJson();
        }
        if (!$model->validate()) {
            return $this->errorJson($model->getErrorMsg());
        }
        $model->setPassword($model->new_password);
        $model->save(true, false);

        return $this->successJson();
    }

    public function actionSendVerifyCode()
    {
        $mobile = Yii::$app->request->post('mobile');
        if (!preg_match("/^1\\d{10}$/", $mobile)) {
            echo json_encode(array('msg' => '请正确输入您的手机号'));
            return;
        }

        if (!User::findOne(['mobile' => $mobile])) {
            echo json_encode(array('msg' => '不存在的用户'));
            return;
        }

        if (isset(Yii::$app->session['change_mobile_code'])) {
            $code = Yii::$app->session['change_mobile_code'];
            $time = $code['time'];
            if ($time + 60 > time()) {
                echo json_encode(array('msg' => '发送过于频繁'));
                return;
            }
        }

        $code = rand(100000, 999999);
        $message = "您的验证码是：" . $code . "，十分钟有效，请立即输入。";
        $result = MessageUtils::sendSMS($mobile, $message);
        if ($result == 'success') {
            $code = array('code' => $code, 'time' => time(), 'mobile' => $mobile, 'scope' => 'change_mobile');
            Yii::$app->session['change_mobile_code'] = $code;
            echo json_encode(array('status' => 0, 'msg' => '发送成功'));
            return;
        }
        echo json_encode(array('msg' => '发送失败，请稍候再试'));
    }

    public function actionProfile()
    {
        $model = $this->getUser();
        $model->setScenario('profile');

        if (!Yii::$app->request->getIsAjax()) {
            return $this->render('profile', array('model' => $model));
        }

        $mobile = $model->mobile;
        if (!$model->load(Yii::$app->request->post())) {
            return $this->errorJson('参数错误');
        }

        if (!$model->validate()) {
            return $this->errorJson($model->getErrorMsg());
        }

        if ($mobile != $model->mobile) {
            $verifyCode = Yii::$app->request->post('verify_code');
            if (!preg_match("/^\\d{6}/", $verifyCode)) {
                return $this->errorJson('请正确输入6位验证码');
            }

            $session_code = Yii::$app->session['change_mobile_code'];
            if (empty($session_code) || $session_code['mobile'] != $mobile || $session_code['time'] + 600 < time() || $session_code['code'] != $verifyCode || $session_code['scope'] != 'change_mobile') {
                return $this->errorJson('验证码错误');
            }
        }

        if (!$model->save()) {
            return $this->errorJson($model->getErrorMsg());
        }

        return $this->successJson();
    }
}