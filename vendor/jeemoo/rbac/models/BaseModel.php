<?php
namespace jeemoo\rbac\models;


use Yii;
use yii\base\UserException;
use yii\db\ActiveRecord;
use jeemoo\rbac\common\UserBehavior;
use jeemoo\rbac\common\TimestampBehavior;

class BaseModel extends ActiveRecord
{
    /**
     * Model constructor.
     * @param array|string $scenario
     * @param array $config
     */
    public function  __construct($scenario = self::SCENARIO_DEFAULT, $config = [])
    {
        $this->setScenario($scenario);
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            UserBehavior::className(),
            TimestampBehavior::className(),
        ];
    }

    /**
     * @return mixed|null
     */
    public function getErrorMsg()
    {
        $error = $this->getFirstErrors();
        if ($error) {
            return current($error);
        }
        return null;
    }

    /**
     * @param bool|true $throwException
     * @param bool|true $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws UserException
     */
    public function save($throwException = true, $runValidation = true, $attributeNames = null)
    {
        $result = parent::save($runValidation, $attributeNames);
        if (!$result && $throwException) {
            throw new UserException($this->getErrorMsg());
        }
        return $result;
    }
}
