<?php
namespace common\models;

use Yii;
use jeemoo\rbac\models\Role;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $module
 * @property integer $status
 * @property string $username
 * @property string $mobile
 * @property string $password
 * @property integer $role_id
 * @property string $auth_key
 * @property string $email
 * @property boolean $is_super
 * @property integer $last_login_at
 * @property string $last_login_ip
 * @property integer $create_user
 * @property integer $create_at
 * @property integer $update_user
 * @property integer $update_at
 *
 * @property Role[] $roles
 */
class User extends BaseModel implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $verify_code;
    public $orig_password;
    public $new_password;
    public $confirm_password;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function scenarios()
    {
        return [
            'default' => [],
            'search' => ['username', 'mobile'],
            'login' => ["mobile", "password"],
            'create' => ['username', 'password', 'confirm_password', 'mobile', 'email', 'auth_key'],
            'update' => ['username', 'mobile', 'email'],
            'password' => ["password", "confirm_password"],
            'profile' => ['username', 'mobile', 'email'],
            'resetPassword' => ['password', 'confirm_password'],
            'changePassword' => ['orig_password', 'new_password', 'confirm_password']
        ];

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile'], 'unique', 'on' => ['create', 'update','profile']],
            [['mobile'], 'string', 'max' => 11, 'on' => ['create', 'update','profile']],
            [['mobile'], 'match', 'pattern' => '/^1\d{10}$/', 'message' => '请正确输入您的手机号.', 'on' => ['create', 'update','profile']],

            [['email'], 'email', 'on' => ['create', 'update','profile']],
            [['email'], 'unique', 'on' => ['create', 'update','profile']],
            [['password', 'new_password'], 'string', 'max' => 20, 'min' => 6, 'on' => ['create', 'password', 'changePassword', 'resetPassword']],
            [['password'], 'validatePassword', 'on' => ['login']],

            [['orig_password'], 'validateOrigPassword'],

            ["confirm_password", "compare", "compareAttribute" => "password", "message" => "确认密码不正确.", 'on' => ['create', 'password', 'resetPassword']],
            ["confirm_password", "compare", "compareAttribute" => "new_password", "message" => "确认密码不正确.", 'on' => ['changePassword']],

            [['username'], 'string', 'max' => 30],
            [['username', 'mobile', 'password', 'orig_password', 'new_password', 'confirm_password', 'auth_key', 'email'], 'required'],

            [['status', 'role_id', 'last_login_at', 'create_user', 'create_at', 'update_user', 'update_at'], 'integer'],
            [['is_super'], 'boolean'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !Yii::$app->security->validatePassword($this->password, $user->password)) {
                $this->addError($attribute, '帐号或密码错误.');
            }
        }
    }

    public function validateOrigPassword($attribute, $params)
    {
        if (!Yii::$app->security->validatePassword($this->orig_password, $this->password)) {
            $this->addError($attribute, '原密码错误.');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'module' => '模块',
            'status' => '启用',
            'username' => '姓名',
            'mobile' => '手机',
            'password' => '密码',
            'orig_password' => '原密码',
            'new_password' => '新密码',
            'confirm_password' => '确认密码',
            'role_id' => '劫色',
            'auth_key' => 'Auth Key',
            'email' => '邮箱',
            'is_super' => '是否超级管理员',
            'last_login_at' => '最后登录',
            'last_login_ip' => '最后登录IP',
            'create_user' => 'Create User',
            'create_at' => 'Create At',
            'update_user' => 'Update User',
            'update_at' => 'Update At',
        ];
    }

    private $_user;

    /**
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByMobile($this->mobile);
        }
        return $this->_user;
    }

    /**
     * @return bool
     */
    public function login()
    {
        if (!$this->validate()) {
            return false;
        }
        return Yii::$app->user->login($this->getUser());
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by mobile
     *
     * @param string $mobile
     * @return static|null
     */
    public static function findByMobile($mobile)
    {
        return static::findOne(['mobile' => $mobile, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @return Menu[]
     */
    public static function getSelectOptions()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'username');
    }
}
