<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "admin_login_log".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $create_at
 * @property integer $create_user
 * @property string $create_ip
 * @property integer $update_at
 * @property integer $update_user
 *
 * @property User $user
 */
class UserLoginLog extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_login_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'create_at', 'create_user', 'update_at', 'update_user'], 'integer'],
            [['create_ip'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Admin ID',
            'create_at' => 'Create At',
            'create_user' => 'Create User',
            'create_ip' => 'Create Ip',
            'update_at' => 'Update At',
            'update_user' => 'Update User',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
