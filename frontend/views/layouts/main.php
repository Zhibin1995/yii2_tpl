<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    <script type="text/javascript">
        shareTimeLineContent = {
            title: '富图文化',
            link: "<?php echo Yii::$app->request->absoluteUrl ?>",
            imgUrl: "<?php echo Yii::$app->request->hostInfo ?>/images/logo.jpg"
        };
        shareAppMessageContent = {
            title: '富图文化',
            desc: '富图文化',
            link: "<?php echo Yii::$app->request->absoluteUrl ?>",
            imgUrl: "<?php echo Yii::$app->request->hostInfo ?>/images/logo.jpg"
        };
        <?php $signPackage=common\wechat\WxUtils::getJsApiParameters() ?>
        wx.config({
            appId: '<?php echo $signPackage["appId"];?>',
            timestamp: <?php echo $signPackage["timestamp"];?>,
            nonceStr: '<?php echo $signPackage["nonceStr"];?>',
            signature: '<?php echo $signPackage["signature"];?>',
            jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'getLocation', 'scanQRCode']
        });
        window.loading = function () {
            window.loadingId = weui.loading();
        };
        window.hideLoading = function () {
            if (window.loadingId) {
                window.loadingId.hide();
            }
        };
        window.success = function (text, callback) {
            var options = {duration: 2000};
            if (callback) {
                options.callback = callback;
            }
            weui.toast(text, options);
        };
        window.alert = function (text) {
            weui.toast(text, {className: 'alert', duration: 2000})
        };
        window.confirm = function (text, confirm) {
            weui.confirm(text, confirm);
        };
    </script>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?= $content ?>
</div>

<?php $this->endBody() ?>
<script type="text/javascript">
    wx.error(function (res) {
        alert(res.errMsg);
    });
    wx.ready(function () {
        wx.onMenuShareTimeline(shareTimeLineContent);
        wx.onMenuShareAppMessage(shareAppMessageContent);
    });
</script>
</body>
</html>
<?php $this->endPage() ?>
