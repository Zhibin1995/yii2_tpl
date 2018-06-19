<?php

use jeemoo\rbac\models\Menu;

/* @var $this yii\web\View */
/* @var $parent Menu|null */
/* @var $items Menu[] */
?>

<ul class="nav <?= $parent != null ? 'child' : "top" ?>" <?= $parent == null ? 'id="side-menu"' : "" ?>>
    <?php foreach ($items as $item) { ?>
        <li>
            <a <?= !empty($item->route) ? 'class="J_menuItem"' : '' ?>  href="<?= empty($item->route) ? '#' : '/' . $item->route ?>" title="<?= $item->title ?>">
                <?php if(!empty($item->icon)){ ?>
                    <i class="<?=$item->icon?>"></i>
                <?php } ?>
                <span class="nav-label"><?= $item->title ?></span>
                <?php if ($item->child_count > 0) { ?>
                    <span class="fa arrow"></span>
                <?php } ?>
            </a>
            <?php if ($menus = Menu::getUserMenus($item->id)) { ?>
                <?= $this->render('_menu', ['items' => $menus, 'parent' => $item]) ?>
            <?php } ?>
        </li>
    <?php } ?>
</ul>