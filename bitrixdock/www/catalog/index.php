<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

// SEF-параметры из urlrewrite: /catalog/{SECTION_CODE}/{ELEMENT_CODE}/
$sectionCode = trim((string)($_GET['SECTION_CODE'] ?? ''), '/');
$elementCode = trim((string)($_GET['ELEMENT_CODE'] ?? ''), '/');

if ($elementCode !== '') {
    // ---- Детальная страница дома ----
    ?>
    <div class="container">
        <?php $APPLICATION->IncludeComponent('bitrix:breadcrumb', 'bruswood', [
            'START_FROM' => '0',
            'PATH' => '',
            'SITE_ID' => 's1',
        ]); ?>
    </div>
    <?php
    // catalog.element возвращает ID показанного элемента — пригодится для «соседних»
    $elementId = $APPLICATION->IncludeComponent('bitrix:catalog.element', 'house', [
        'IBLOCK_TYPE' => 'catalog',
        'IBLOCK_ID' => '1',
        'ELEMENT_ID' => '',
        'ELEMENT_CODE' => $elementCode,
        'SECTION_CODE' => $sectionCode,
        'CHECK_SECTION_ID_VARIABLE' => 'N',
        'SET_TITLE' => 'Y',
        'SET_META_KEYWORDS' => 'N',
        'SET_META_DESCRIPTION' => 'Y',
        'SET_CANONICAL_URL' => 'Y',
        'ADD_SECTIONS_CHAIN' => 'Y',
        'ADD_ELEMENT_CHAIN' => 'Y',
        'SET_STATUS_404' => 'Y',
        'SHOW_404' => 'Y',
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => '3600',
        'CACHE_GROUPS' => 'N',
    ]);

    if ($elementId): ?>
        <section class="section similar">
            <div class="container">
                <header class="section-head" data-reveal>
                    <h2>Похожие проекты</h2>
                    <a href="/catalog/" class="link-arrow">Весь каталог<span aria-hidden="true"> →</span></a>
                </header>
                <?php
                $GLOBALS['similarFilter'] = ['!ID' => (int)$elementId];
                $APPLICATION->IncludeComponent('bitrix:catalog.section', 'featured', [
                    'IBLOCK_TYPE' => 'catalog',
                    'IBLOCK_ID' => '1',
                    'INCLUDE_SUBSECTIONS' => 'Y',
                    'FILTER_NAME' => 'similarFilter',
                    'CACHE_FILTER' => 'Y',
                    'ELEMENT_SORT_FIELD' => 'sort',
                    'ELEMENT_SORT_ORDER' => 'asc',
                    'PAGE_ELEMENT_COUNT' => '3',
                    'PROPERTY_CODE_1' => ['PRICE', 'AREA', 'BEDROOMS', 'FLOORS'],
                    'SET_TITLE' => 'N',
                    'SET_META_KEYWORDS' => 'N',
                    'SET_META_DESCRIPTION' => 'N',
                    'ADD_SECTIONS_CHAIN' => 'N',
                    'DISPLAY_TOP_PAGER' => 'N',
                    'DISPLAY_BOTTOM_PAGER' => 'N',
                    'CACHE_TYPE' => 'A',
                    'CACHE_TIME' => '3600',
                    'CACHE_GROUPS' => 'N',
                ]); ?>
            </div>
        </section>
    <?php endif;

    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
    return;
}

$APPLICATION->SetTitle('Каталог проектов домов из бруса');

// Сортировка: только значения из белого списка
$sortOptions = [
    'default'    => ['По умолчанию', 'sort', 'asc'],
    'price-asc'  => ['Сначала дешевле', 'PROPERTY_PRICE', 'asc'],
    'price-desc' => ['Сначала дороже', 'PROPERTY_PRICE', 'desc'],
    'area-asc'   => ['Площадь ↑', 'PROPERTY_AREA', 'asc'],
    'area-desc'  => ['Площадь ↓', 'PROPERTY_AREA', 'desc'],
];
$sort = (string)($_GET['sort'] ?? 'default');
if (!isset($sortOptions[$sort])) {
    $sort = 'default';
}
[, $sortField, $sortOrder] = $sortOptions[$sort];
?>

<div class="page-head">
    <div class="container">
        <h1>Каталог проектов</h1>
        <p class="page-head__lead">Дома из профилированного и клеёного бруса под ключ. Цена фиксируется в договоре.</p>
    </div>
</div>

<div class="container catalog">
    <aside class="catalog__aside" id="catalog-filter" aria-label="Фильтр каталога">
        <div class="catalog__aside-head">
            <span class="filter__caption">Фильтры</span>
            <button type="button" class="catalog__aside-close" id="filter-close" aria-label="Закрыть фильтры">✕</button>
        </div>
        <?php
        // фильтр пишет условия в $GLOBALS['arrFilter'] — секция ниже читает их по FILTER_NAME
        $APPLICATION->IncludeComponent('bitrix:catalog.smart.filter', 'bruswood', [
            'IBLOCK_TYPE' => 'catalog',
            'IBLOCK_ID' => '1',
            'SECTION_CODE' => $sectionCode,
            'FILTER_NAME' => 'arrFilter',
            'PRICE_CODE' => [],
            'SEF_MODE' => 'N',
            'SAVE_IN_SESSION' => 'N',
            'FILTER_VIEW_MODE' => 'vertical',
            'DISPLAY_ELEMENT_COUNT' => 'Y',
            'HIDE_NOT_AVAILABLE' => 'N',
            'CONVERT_CURRENCY' => 'N',
            'XML_EXPORT' => 'N',
            'CACHE_TYPE' => 'A',
            'CACHE_TIME' => '3600',
            'CACHE_GROUPS' => 'N',
        ]); ?>
    </aside>

    <div class="catalog__main">
        <div class="catalog__toolbar">
            <button type="button" class="btn btn--ghost catalog__filter-toggle" id="filter-toggle">Фильтры</button>
            <label class="catalog__sort">
                <span>Сортировка:</span>
                <select id="catalog-sort">
                    <?php foreach ($sortOptions as $key => [$label]): ?>
                        <option value="<?= $key ?>"<?= $key === $sort ? ' selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>

        <?php $APPLICATION->IncludeComponent('bitrix:catalog.section', 'grid', [
            'IBLOCK_TYPE' => 'catalog',
            'IBLOCK_ID' => '1',
            'SECTION_CODE' => $sectionCode,
            'INCLUDE_SUBSECTIONS' => 'Y',
            'FILTER_NAME' => 'arrFilter',
            'CACHE_FILTER' => 'Y',
            'ELEMENT_SORT_FIELD' => $sortField,
            'ELEMENT_SORT_ORDER' => $sortOrder,
            'ELEMENT_SORT_FIELD2' => 'id',
            'ELEMENT_SORT_ORDER2' => 'asc',
            'PAGE_ELEMENT_COUNT' => '6',
            'PROPERTY_CODE_1' => ['PRICE', 'AREA', 'BEDROOMS', 'FLOORS', 'SIZE'],
            'SET_TITLE' => $sectionCode !== '' ? 'Y' : 'N',
            'SET_META_KEYWORDS' => 'N',
            'SET_META_DESCRIPTION' => $sectionCode !== '' ? 'Y' : 'N',
            'ADD_SECTIONS_CHAIN' => 'N',
            'DISPLAY_TOP_PAGER' => 'N',
            'DISPLAY_BOTTOM_PAGER' => 'Y',
            'PAGER_TEMPLATE' => 'round',
            'PAGER_SHOW_ALWAYS' => 'N',
            'PAGER_TITLE' => 'Проекты',
            'CACHE_TYPE' => 'A',
            'CACHE_TIME' => '3600',
            'CACHE_GROUPS' => 'N',
        ]); ?>
    </div>
</div>
<div class="catalog__overlay" id="filter-overlay" aria-hidden="true"></div>

<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
