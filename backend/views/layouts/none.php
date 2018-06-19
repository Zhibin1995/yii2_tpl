<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use backend\assets\AppAsset;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?= Html::csrfMetaTags() ?>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php $this->head() ?>

    <script type="text/javascript">
        if (window.top != window) {
            window.top.location.href = window.location.href;
        }
    </script>
</head>
<body class="white-bg">
    <?php $this->beginBody() ?>
    <div class="wrapper wrapper-content" style="padding: 0 200px;">
        <?= $content ?>
    </div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>