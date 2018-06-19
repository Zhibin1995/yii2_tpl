<?php

namespace jeemoo\rbac\common;


class TimestampBehavior extends \yii\behaviors\TimestampBehavior
{
    public $createdAtAttribute = 'create_at';

    public $updatedAtAttribute = 'update_at';
}