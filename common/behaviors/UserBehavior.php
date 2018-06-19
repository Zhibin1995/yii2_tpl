<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

class UserBehavior extends AttributeBehavior
{
    public $createUserAttribute = 'create_user';

    public $updateUserAttribute = 'update_user';

    public $value;

    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->createUserAttribute, $this->updateUserAttribute],
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->updateUserAttribute,
            ];
        }
    }

    protected function getValue($event)
    {
        return Yii::$app->user->getId();
    }
}