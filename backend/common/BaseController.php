<?php
namespace backend\common;


use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\User;

/**
 * @property User $user
 * @property integer $userId
 */
class BaseController extends Controller
{
    public $pageSize = 10;
    public $layout = 'main';
    public $allowImg = array('.jpg', '.png', '.gif');

    const CALLBACK_REFRESH = 'refresh';
    const CALLBACK_CLOSE_AND_REFRESH = 'close_and_refresh';
    const CALLBACK_CLOSE_ALL_AND_REFRESH = 'close_all_and_refresh';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'roles' => ['@'],],
                ],
            ]
        ];
    }

    public function saveImage($item, $folder)
    {
        if (empty($item)) {
            return null;
        }

        $ext = '.' . trim(substr($item, 11, 4), ';');
        if ($ext == '.jpeg') {
            $ext = '.jpg';
        }
        if (!in_array($ext, $this->allowImg)) {
            return null;
        }

        $image = substr(strstr($item, ','), 1);
        $image = str_replace(' ', '+', $image);
        $data = base64_decode($image);

        $name = time() . rand(1, 9999) . $ext;
        $url = '/uploads/images/';
        $dir = realpath('./') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
        if ($folder) {
            $url = $url . $folder . '/';
            $dir = $dir . $folder . DIRECTORY_SEPARATOR;
        }
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($dir . $name, $data);

        return $url . $name;
    }


    /**
     * @return User
     */
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