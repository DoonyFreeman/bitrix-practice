<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arResult */

if (empty($arResult['ITEMS'])) {
    return;
}
?>
<div class="house-cards">
    <?php foreach ($arResult['ITEMS'] as $item): ?>
        <?php
        $img = $item['PREVIEW_PICTURE']
            ? CFile::ResizeImageGet($item['PREVIEW_PICTURE'], ['width' => 960, 'height' => 720], BX_RESIZE_IMAGE_EXACT)
            : null;
        $props = $item['DISPLAY_PROPERTIES'];
        $price = (float)($props['PRICE']['VALUE'] ?? 0);
        ?>
        <a href="<?= $item['DETAIL_PAGE_URL'] ?>" class="house-card" data-reveal>
            <span class="house-card__media">
                <?php if ($img): ?>
                    <img src="<?= $img['src'] ?>" alt="Проект дома «<?= $item['NAME'] ?>»" loading="lazy" width="960" height="720">
                <?php endif; ?>
            </span>
            <span class="house-card__body">
                <span class="house-card__row">
                    <span class="house-card__name"><?= $item['NAME'] ?></span>
                    <span class="house-card__price"><?= number_format($price, 0, '', ' ') ?> ₽</span>
                </span>
                <span class="house-card__meta">
                    <?= $props['AREA']['VALUE'] ?> м² · спален: <?= $props['BEDROOMS']['VALUE'] ?> · <?= mb_strtolower($props['FLOORS']['VALUE']) ?>
                </span>
            </span>
        </a>
    <?php endforeach; ?>
</div>
