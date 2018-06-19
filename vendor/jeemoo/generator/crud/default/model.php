<?php

use \yii\helpers\Inflector;
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator jeemoo\generator\crud\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;
use yii\helpers\ArrayHelper;
use <?= $generator->baseClass ?>;

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?=  substr($generator->baseClass,strrpos($generator->baseClass,'\\')+1) ?>
{
<?php foreach ($tableSchema->columns as $column): ?>
<?php if (substr($column->dbType,0,4)=="enum"): ?>
<?php foreach($column->enumValues as $value):?>
    const <?=strtoupper($column->name)?>_<?=strtoupper($value)?> = '<?=$value?>';
<?php endforeach;?>

<?php endif; ?>
<?php endforeach; ?>
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
    * @inheritdoc
    */
    public function scenarios()
    {
        return [
<?php foreach($generator->generateScenarios($tableSchema) as $scenario=>$columns): ?>
            '<?=$scenario?>' => <?=json_encode($columns)?>,
<?php endforeach; ?>
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . ",\n        " ?>];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php foreach ($tableSchema->columns as $column): ?>
    <?php if (!empty($column->comment) && ($pos = strpos($column->comment, '=')) > 0 && substr($column->comment, $pos + 1) == 'img'){ ?>

    /**
    * @return string
    */
    public function get<?= Inflector::id2camel(str_replace('_', '-', $column->name)) ?>()
    {
        if (!empty($this-><?=$column->name?>)) {
            return $this-><?=$column->name?>;
        }
        return '/images/<?=$column->name?>.jpg';
    }
    <?php } ?>
<?php endforeach; ?>
<?php foreach ($tableSchema->columns as $column): ?>
<?php if (substr($column->dbType,0,4)=="enum"): ?>

    /**
    * @return array
    */
    public static function get<?=Inflector::pluralize(Inflector::id2camel(str_replace('_', '-', $column->name))) ?>()
    {
        return [
<?php foreach($column->enumValues as $index=>$value):?>
<?php if (!empty($column->comment) && ($pos = strpos($column->comment, '=')) > 0 && $options = explode(',', substr(str_replace('，',',',$column->comment), $pos + 1))){ ?>
            self::<?=strtoupper($column->name)?>_<?=strtoupper($value)?> => '<?=isset($options[$index])?$options[$index]: $value ?>',
<?php } else { ?>
            self::<?=strtoupper($column->name)?>_<?=strtoupper($value)?> => '<?=$value?>',
<?php }?>
<?php endforeach;?>
        ];
    }

    /**
    * @return string
    */
    public function get<?= Inflector::id2camel(str_replace('_', '-', $column->name)) ?>Name()
    {
        $array = <?= $className ?>::get<?=Inflector::pluralize(Inflector::id2camel(str_replace('_', '-', $column->name))) ?>();
        if (isset($array[$this-><?=$column->name?>])) {
            return $array[$this-><?=$column->name?>];
        }
        return '';
    }
<?php endif; ?>
<?php endforeach; ?>

    /**
    * @param array $filter
    * @return array
    */
    public static function getSelectOptions($filter=[])
    {
        $items = <?= $className ?>::find()->filterWhere($filter)->orderBy('id desc')->all();
<?php
$name='id';
foreach($tableSchema->columns as $column){
    if($column->name=='name'||$column->name=='title'){
        $name=$column->name;
        break;
    }
}?>
        return ArrayHelper::map($items, 'id', '<?=$name?>');
    }
}
