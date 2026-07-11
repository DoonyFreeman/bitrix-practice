<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arResult */

if (!function_exists('bwIcon')) {
    /** Инлайн-SVG иконка Phosphor из assets шаблона (fill=currentColor). */
    function bwIcon(string $name): string
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/assets/img/icons/' . $name . '.svg';
        return is_file($path)
            ? str_replace('<svg ', '<svg class="icon" aria-hidden="true" ', file_get_contents($path))
            : '';
    }
}

if (empty($arResult['ITEMS'])):
?>
    <div class="catalog__empty">
        <h3>Ничего не нашлось</h3>
        <p>Попробуйте ослабить условия фильтра — например, расширить диапазон цены.</p>
    </div>
<?php else: ?>
    <div class="house-cards house-cards--catalog">
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
                    <span class="house-card__meta house-card__meta--icons">
                        <span class="meta-item"><?= bwIcon('ruler') ?><?= $props['AREA']['VALUE'] ?> м²</span>
                        <span class="meta-item"><?= bwIcon('bed') ?><?= $props['BEDROOMS']['VALUE'] ?></span>
                        <span class="meta-item"><?= bwIcon('steps') ?><?= mb_strtolower($props['FLOORS']['VALUE']) ?></span>
                    </span>
                </span>
            </a>
        <?php endforeach; ?>
    </div>

    <?= $arResult['NAV_STRING'] ?? '' ?>
<?php endif; ?>
