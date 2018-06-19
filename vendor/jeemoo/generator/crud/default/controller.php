<?php
/**
 * This is the template for generating a controller class file.
 */

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $module string */
/* @var $tableSchema yii\db\TableSchema */
/* @var $generator jeemoo\generator\crud\Generator */

echo "<?php\n";
?>

namespace <?= $generator->controllerNs ?>;


use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use <?= $generator->ns . '\\' . $generator->getModelClass() ?>;
use <?= $generator->controllerBaseClass ?>;

class <?= StringHelper::basename($generator->modelClass) ?>Controller extends <?=  substr($generator->controllerBaseClass,strrpos($generator->controllerBaseClass,'\\')+1) ?>
{
    public function actionIndex()
    {
        $model = new <?= StringHelper::basename($generator->modelClass) ?>();
        $model->setScenario('search');
        $model->load(Yii::$app->request->post());

        $query = <?= StringHelper::basename($generator->modelClass) ?>::find()->orderBy('id desc');
        <?= implode("        ", $generator->generateSearchConditions($tableSchema)) ?>

        $pager = new Pagination(['totalCount' => $query->count()]);
        $items = $query->offset($pager->offset)->limit($pager->limit)->all();

        return $this->render('index', ['model' => $model, 'items' => $items, 'pager' => $pager]);
    }

    public function  actionCreate()
    {
        $model = new <?= StringHelper::basename($generator->modelClass) ?>();
        $model->setScenario('create');

        if (!Yii::$app->request->getIsAjax()) {
            return $this->render('edit', ['model' => $model]);
        }

        if (!$model->load(Yii::$app->request->post())) {
            return $this->errorJson('参数错误');
        }
<?php foreach($tableSchema->columns as $column){
    if(!empty($column->comment) && ($pos = strpos($column->comment, '=')) > 0 && substr($column->comment, $pos + 1) == 'img'){?>
        if ($<?=$column->name?> = Yii::$app->request->post('<?=$column->name?>')) {
            $model-><?=$column->name?> = $this->saveImage($<?=$column->name?>, '<?=$tableSchema->name?>');
        }

    <?php }
}?>
        $model->save();
        return $this->successJson();
    }

    public function  actionUpdate($id)
    {
        $model = <?= StringHelper::basename($generator->modelClass) ?>::findOne($id);
        if ($model == null) {
            throw new NotFoundHttpException();
        }

        $model->setScenario('update');
        if (!Yii::$app->request->getIsAjax()) {
            return $this->render('edit', ['model' => $model]);
        }

        if (!$model->load(Yii::$app->request->post())) {
            return $this->errorJson('参数错误');
        }
<?php foreach($tableSchema->columns as $column){
    if(!empty($column->comment) && ($pos = strpos($column->comment, '=')) > 0 && substr($column->comment, $pos + 1) == 'img'){?>
        $<?=$column->name?> = Yii::$app->request->post('<?=$column->name?>');
        if ($<?=$column->name?> !== null) {
            $model-><?=$column->name?> = $this->saveImage($<?=$column->name?>, '<?=$tableSchema->name?>');
        }

    <?php }
}?>
        $model->save();
        return $this->successJson();
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        $model = <?= StringHelper::basename($generator->modelClass) ?>::findOne($id);
        if ($model == null) {
            return $this->errorJson();
        }

        $model->delete();
        return $this->successJson(null, self::CALLBACK_REFRESH);
    }
}
