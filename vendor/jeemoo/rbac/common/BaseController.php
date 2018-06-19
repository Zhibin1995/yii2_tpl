<?php
namespace jeemoo\rbac\common;


use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
    public $pageSize = 10;
    public $layout = 'main';

    const CALLBACK_REFRESH = 'refresh';
    const CALLBACK_CLOSE_AND_REFRESH = 'close_and_refresh';
    const CALLBACK_CLOSE_ALL_AND_REFRESH = 'close_all_and_refresh';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
            ]
        ];
    }

    public function getModulePath()
    {
        $path = $this->module->uniqueId;
        if (empty($path)) {
            return '';
        }
        return $path . '/';
    }

    public function getUser()
    {
        return Yii::$app->user->getIdentity();
    }

    public function getUserId()
    {
        return Yii::$app->user->getId();
    }

    public function getParam($name)
    {
        $value = Yii::$app->request->post($name);
        if ($value === null) {
            $value = Yii::$app->request->get($name);
        }
        return $value;
    }

    public function render($view, $params = [])
    {
        if (!Yii::$app->request->getIsAjax()) {
            return parent::render($view, $params);
        }
        return parent::renderPartial($view, $params);
    }

    public function errorJson($msg = null, $status = -1)
    {
        return json_encode(['status' => $status, 'msg' => $msg]);
    }

    public function successJson($data = null, $callback = self::CALLBACK_CLOSE_AND_REFRESH)
    {
        return json_encode(['status' => 200, 'data' => $data, 'callback' => $callback]);
    }

    public function json($status, $msg, $data, $callback)
    {
        return json_encode(['status' => $status, 'msg' => $msg, 'data' => $data, 'callback' => $callback]);
    }

}