<?php

namespace frontend\controllers;

use app\components\wechat\WxConfig;
use app\components\wechat\WxUtils;
use app\models\Member;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['actions' => ['login', 'login2', 'error', 'index'], 'allow' => true,],
                    ['allow' => true, 'roles' => ['@'],],
                ],
            ],
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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->getIsGuest()) {
            return $this->goBack();
        }

        if (!isset($_GET['code'])) {
            $state = urlencode(Yii::$app->user->getReturnUrl());
            $uri = urlencode(Yii::$app->request->hostInfo . '/site/login');
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . WxConfig::APPID . "&redirect_uri=$uri&response_type=code&scope=snsapi_userinfo&state=$state#wechat_redirect";
            return $this->redirect($url);
        }

        $code = $_GET['code'];
        $state = Yii::$app->user->getReturnUrl();
        $user = WxUtils::getUser($code);
        $member = Member::findOne(['open_id' => $user->openid]);
        if (empty($member)) {
            $member = new Member();
            $member->open_id = $user->openid;
            $member->nickname = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', empty($user->nickname) ? '' : $user->nickname);;
            $member->avatar = empty($user->headimgurl) ? '' : $user->headimgurl;
            $member->save();
        }
        Yii::$app->user->login($member);
        return $this->redirect($state);
    }

    public function actionLogin2($id = null)
    {
        $member = Member::findOne($id ? $id : 1);
        Yii::$app->user->login($member);
        return $this->goBack();
    }
}
