<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $nickname
 * @property string $open_id
 * @property string $avatar
 * @property string $username
 * @property string $mobile
 * @property string $auth_key
 * @property integer $create_user
 * @property integer $create_at
 * @property integer $update_user
 * @property integer $update_at
 */
class Member extends BaseModel implements  IdentityInterface{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
    * @inheritdoc
    */
    public function scenarios()
    {
        return [
            'default' => [],
            'search' => ["nickname","avatar","username","mobile","auth_key"],
            'create' => ["nickname","avatar","username","mobile","auth_key"],
            'update' => ["nickname","avatar","username","mobile","auth_key"],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_key'], 'required'],
            [['create_user', 'create_at', 'update_user', 'update_at'], 'integer'],
            [['nickname', 'username'], 'string', 'max' => 50],
            [['avatar'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 11],
            [['auth_key'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nickname' => '昵称',
            'avatar' => '头像',
            'username' => '姓名',
            'mobile' => '手机',
            'auth_key' => 'Auth Key',
            'create_user' => 'Create User',
            'create_at' => 'Create At',
            'update_user' => 'Update User',
            'update_at' => 'Update At',
        ];
    }
                                        
    /**
    * @param array $filter
    * @return array
    */
    public static function getSelectOptions($filter=[])
    {
        $items = Member::find()->filterWhere($filter)->orderBy('id desc')->all();
        return ArrayHelper::map($items, 'id', 'id');
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}
