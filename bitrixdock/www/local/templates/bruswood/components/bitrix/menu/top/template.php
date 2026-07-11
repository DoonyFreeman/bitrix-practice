<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (empty($arResult)) {
    return;
}
?>
<ul class="nav__list">
    <?php foreach ($arResult as $item): ?>
        <?php if ($item['DEPTH_LEVEL'] > 1) continue; ?>
        <li>
            <a href="<?= $item['LINK'] ?>" class="nav__link<?= $item['SELECTED'] ? ' is-active' : '' ?>">
                <?= $item['TEXT'] ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
