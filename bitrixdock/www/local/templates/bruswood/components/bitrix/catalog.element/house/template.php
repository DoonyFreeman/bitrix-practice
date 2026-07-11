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

$props = $arResult['DISPLAY_PROPERTIES'];
$price = (float)($props['PRICE']['VALUE'] ?? 0);

// детальная картинка: catalog.element отдаёт массив файла
$detailImg = is_array($arResult['DETAIL_PICTURE'] ?? null) ? $arResult['DETAIL_PICTURE'] : null;

// галерея: детальное фото + множественное файловое свойство GALLERY
$galleryIds = (array)($arResult['PROPERTIES']['GALLERY']['VALUE'] ?? []);
$gallery = [];
if ($detailImg) {
    $gallery[] = ['full' => $detailImg, 'alt' => 'Дом «' . $arResult['NAME'] . '»'];
}
foreach ($galleryIds as $fileId) {
    $file = CFile::GetFileArray($fileId);
    if ($file) {
        $gallery[] = ['full' => $file, 'alt' => $file['DESCRIPTION'] ?: 'Дом «' . $arResult['NAME'] . '»'];
    }
}

// планировки: SVG + подпись этажа в DESCRIPTION файла
$plans = [];
foreach ((array)($arResult['PROPERTIES']['PLANS']['VALUE'] ?? []) as $fileId) {
    $file = CFile::GetFileArray($fileId);
    if ($file) {
        $plans[] = ['src' => $file['SRC'], 'label' => $file['DESCRIPTION'] ?: 'План'];
    }
}

$specs = [
    'Площадь' => ($props['AREA']['VALUE'] ?? '') . ' м²',
    'Габариты' => $props['SIZE']['VALUE'] ?? '',
    'Этажность' => $props['FLOORS']['VALUE'] ?? '',
    'Спален' => $props['BEDROOMS']['VALUE'] ?? '',
    'Материал' => $props['MATERIAL']['VALUE'] ?? '',
    'Срок строительства' => $props['BUILD_TIME']['VALUE'] ?? '',
];

$equipmentHtml = $arResult['PROPERTIES']['EQUIPMENT']['~VALUE']['TEXT'] ?? '';
?>
<article class="house">

    <header class="house-hero">
        <div class="container house-hero__grid">
            <div class="house-hero__media" data-reveal>
                <?php if ($detailImg): ?>
                    <img src="<?= $detailImg['SRC'] ?>" alt="Дом «<?= $arResult['NAME'] ?>»" fetchpriority="high">
                <?php endif; ?>
            </div>
            <div class="house-hero__info">
                <h1 class="house-hero__title">Дом «<?= $arResult['NAME'] ?>»</h1>
                <p class="house-hero__lead"><?= $arResult['PREVIEW_TEXT'] ?></p>
                <ul class="house-hero__facts">
                    <li><?= bwIcon('ruler') ?><?= $props['AREA']['VALUE'] ?> м²</li>
                    <li><?= bwIcon('bed') ?>спален: <?= $props['BEDROOMS']['VALUE'] ?></li>
                    <li><?= bwIcon('steps') ?><?= mb_strtolower($props['FLOORS']['VALUE']) ?></li>
                    <li><?= bwIcon('timer') ?><?= $props['BUILD_TIME']['VALUE'] ?></li>
                </ul>
                <div class="house-hero__price">
                    <?= number_format($price, 0, '', ' ') ?> ₽
                    <span>под ключ, цена фиксируется в договоре</span>
                </div>
                <a href="tel:+79215550188" class="btn">Обсудить проект</a>
            </div>
        </div>
    </header>

    <?php if ($gallery): ?>
        <section class="house-block">
            <div class="container">
                <h2 data-reveal>Фотографии</h2>
                <div class="house-gallery" id="house-gallery">
                    <?php foreach ($gallery as $i => $shot): ?>
                        <?php $thumb = CFile::ResizeImageGet($shot['full']['ID'], ['width' => 900, 'height' => 700], BX_RESIZE_IMAGE_PROPORTIONAL); ?>
                        <a href="<?= $shot['full']['SRC'] ?>"
                           data-pswp-width="<?= $shot['full']['WIDTH'] ?>"
                           data-pswp-height="<?= $shot['full']['HEIGHT'] ?>"
                           class="house-gallery__item<?= $i === 0 ? ' house-gallery__item--wide' : '' ?>"
                           data-reveal>
                            <img src="<?= $thumb['src'] ?>" alt="<?= htmlspecialcharsbx($shot['alt']) ?>" loading="lazy">
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($plans): ?>
        <section class="house-block house-block--soft">
            <div class="container">
                <h2 data-reveal>Планировки</h2>
                <div class="plans" data-plans>
                    <?php if (count($plans) > 1): ?>
                        <div class="plans__tabs" role="tablist">
                            <?php foreach ($plans as $i => $plan): ?>
                                <button type="button" role="tab" class="plans__tab<?= $i === 0 ? ' is-active' : '' ?>"
                                        aria-selected="<?= $i === 0 ? 'true' : 'false' ?>" data-plan-tab="<?= $i ?>">
                                    <?= htmlspecialcharsbx($plan['label']) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="plans__panes">
                        <?php foreach ($plans as $i => $plan): ?>
                            <div class="plans__pane<?= $i === 0 ? ' is-active' : '' ?>" data-plan-pane="<?= $i ?>">
                                <img src="<?= $plan['src'] ?>" alt="Планировка: <?= htmlspecialcharsbx($plan['label']) ?>" loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="house-block">
        <div class="container house-details">
            <div class="house-details__text" data-reveal>
                <h2>О проекте</h2>
                <?= $arResult['DETAIL_TEXT'] ?>

                <?php if ($equipmentHtml): ?>
                    <h2>Комплектация</h2>
                    <div class="house-equipment"><?= $equipmentHtml ?></div>
                <?php endif; ?>
            </div>
            <aside class="house-specs" data-reveal>
                <h3>Характеристики</h3>
                <dl class="house-specs__list">
                    <?php foreach ($specs as $label => $value): ?>
                        <?php if ((string)$value === '') continue; ?>
                        <div class="house-specs__row">
                            <dt><?= $label ?></dt>
                            <dd><?= $value ?></dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            </aside>
        </div>
    </section>

</article>
