<?php
namespace backend\controllers;


use backend\common\BaseController;
use common\models\User;
use common\models\UserLoginLog;
use common\utils\MessageUtils;
use Yii;
use yii\filters\AccessControl;

class SiteController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'roles' => ['@'],],
                    ['allow' => true, 'actions' => ['login', 'error', 'home', 'find-password', 'send-verify-code', 'verify-code', 'request-reset-password', 'reset-password']],
                ]
            ]
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionHome()
    {
        return $this->render('home');
    }

    public function actionIndex()
    {
        return $this->renderPartial('index', ['user' => $this->user]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new User('login');
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = $this->user;
            $user->last_login_at = time();
            $user->last_login_ip = Yii::$app->request->userIP;
            $user->save();

            $log = new UserLoginLog();
            $log->user_id = $user->id;
            $log->create_ip = Yii::$app->request->userIP;
            $log->save();

            return $this->goBack();
        }
        return $this->render('login', ['model' => $model,]);
    }


    public function actionFindPassword()
    {
        return $this->render('find_password');
    }

    public function actionSendVerifyCode()
    {
        $mobile = Yii::$app->request->post('mobile');
        if (!preg_match("/^1\\d{10}$/", $mobile)) {
            echo json_encode(array('msg' => '请正确输入您的手机号'));
            return;
        }

        if (!User::findByMobile($mobile)) {
            echo json_encode(array('msg' => '不存在的用户'));
            return;
        }

        if (isset(Yii::$app->session['set_password_code'])) {
            $code = Yii::$app->session['set_password_code'];
            $time = $code['time'];
            if ($time + 60 > time()) {
                echo json_encode(array('msg' => '发送过于频繁'));
                return;
            }
        }

        $code = rand(100000, 999999);
        $message = "您的验证码是：" . $code . "，30分钟有效，请尽快输入。";
        $result = MessageUtils::sendSMS($mobile, $message);
        if ($result == 'success') {
            $code = [
                'code' => $code, 'time' => time(),
                'mobile' => $mobile, 'scope' => 'reset_password'
            ];
            Yii::$app->session['reset_password_code'] = $code;
            echo json_encode(array('status' => 0, 'msg' => '发送成功'));
            return;
        }
        echo json_encode(array('msg' => '发送失败，请稍候再试'));
    }

    public function actionVerifyCode()
    {
        $mobile = Yii::$app->request->post('mobile');
        if (!preg_match("/^1\\d{10}$/", $mobile)) {
            echo json_encode(array('msg' => '请正确输入您的手机号'));
            return;
        }

        $code = Yii::$app->request->post('code');
        if (!preg_match("/^\\d{6}/", $code)) {
            echo json_encode(array('msg' => '请正确输入6位验证码'));
            return;
        }

        $verify_code = Yii::$app->session['reset_password_code'];
        if (empty($verify_code)
            || $verify_code['mobile'] != $mobile
            || $verify_code['time'] + 1800 < time()
            || $verify_code['code'] != $code
            || $verify_code['scope'] != 'reset_password'
        ) {
            echo json_encode(array('msg' => '验证码错误'));
            return;
        }

        echo json_encode(array('status' => 0));
    }

    public function actionRequestResetPassword($mobile, $code)
    {
        $verify_code = Yii::$app->session['reset_password_code'];
        if (empty($verify_code)
            || $verify_code['mobile'] != $mobile
            || $verify_code['time'] + 1800 < time()
            || $verify_code['code'] != $code
            || $verify_code['scope'] != 'reset_password'
        ) {
            return $this->redirect('/site/find-password');
        }
        return $this->render('reset_password', ['mobile' => $mobile, 'code' => $code]);
    }

    public function actionResetPassword($mobile, $code)
    {
        $verify_code = Yii::$app->session['reset_password_code'];
        if (empty($verify_code)
            || $verify_code['mobile'] != $mobile
            || $verify_code['time'] + 1800 < time()
            || $verify_code['code'] != $code
            || $verify_code['scope'] != 'reset_password'
        ) {
            return $this->errorJson('凭据无效');
        }

        if (empty($mobile)) {
            return $this->errorJson('凭据无效');
        }

        $user = User::findOne(['mobile' => $mobile]);
        if (!$user) {
            return $this->errorJson('凭据无效');
        }

        $user->setScenario('resetPassword');
        if (!$user->load(Yii::$app->request->post(), '')) {
            return $this->errorJson();
        }
        if (!$user->validate()) {
            return $this->errorJson($user->getErrorMsg());
        }

        $user->setPassword($user->password);
        $user->save(true, false);

        Yii::$app->session['reset_password_code'] = null;
        echo json_encode(array('status' => 0));
    }

}
