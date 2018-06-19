<?php
/**
 * This is the template for generating a controller class file.
 */

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator jeemoo\generator\crud\Generator */

echo "<?php\n";
?>

namespace api\controllers;

use Yii;
use yii\rest\ActiveController;

class <?= StringHelper::basename($generator->modelClass) ?>Controller extends ActiveController
{
    public $modelClass = 'common\models\<?= StringHelper::basename($generator->modelClass) ?>';

}

<?php
$file = __DIR__ . '/../../../../api/config/controllers.php';
$controllers = require($file);
$controller = Inflector::camel2id($generator->modelClass);
if (!in_array($controller, $controllers)) {
    $controllers[]=$controller;
    $fp = fopen($file, "w");
    fwrite($fp, "<?php \n". "return " . json_encode($controllers).';');
    fclose($fp);
}
?>


