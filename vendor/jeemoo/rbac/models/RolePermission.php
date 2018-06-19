<?php

namespace jeemoo\rbac\models;

use Yii;

/**
 * This is the model class for table "role_permission".
 *
 * @property integer $id
 * @property integer $permission_id
 * @property integer $role_id
 * @property integer $create_user
 * @property integer $create_at
 * @property integer $update_user
 * @property integer $update_at
 *
 * @property Role $role
 * @property Permission $permission
 */
class RolePermission extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role_permission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permission_id', 'role_id'], 'required'],
            [['permission_id', 'role_id', 'create_user', 'create_at', 'update_user', 'update_at'], 'integer'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
            [['permission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Permission::className(), 'targetAttribute' => ['permission_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'permission_id' => '权限',
            'role_id' => '角色',
            'create_user' => 'Create User',
            'create_at' => 'Create At',
            'update_user' => 'Update User',
            'update_at' => 'Update At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermission()
    {
        return $this->hasOne(Permission::className(), ['id' => 'permission_id']);
    }
}
